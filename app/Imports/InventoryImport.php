<?php

namespace App\Imports;

use App\DTOs\v1\management\infrastructure\equipments\InventoryLogDTO;
use App\Models\Infrastructure\Equipment\InventoryLogModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Infrastructure\Equipment\InventoryModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class InventoryImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */

    private $baseData;
    private $results = [];

    public function __construct(array $baseData)
    {
        $this->baseData = $baseData;
    }

    public function collection(Collection $rows)
    {
        $successful = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                if (empty($row['mac_address']) || empty($row['serial_number'])) {
                    $errors[] = "Fila " . ($index + 2) . ": MAC y serial son obligatorios";
                    continue;
                }

                $existingMac = InventoryModel::query()
                    ->where('mac_address', $row['mac_address'])
                    ->exists();
                $existingSerial = InventoryModel::query()
                    ->where('serial_number', $row['serial_number'])
                    ->exists();

                if ($existingMac) {
                    $errors[] = "Fila " . ($index + 2) . ": MAC {$row['mac_address']} ya ha sido ingresada";
                    continue;
                }

                if ($existingSerial) {
                    $errors[] = "Fila " . ($index + 2) . ": Serial {$row['serial_number']} ya ha sido ingresada";
                    continue;
                }

                if (!$this->isValidMac($row['mac_address'])) {
                    $errors[] = "Fila " . ($index + 2) . " : Formato de MAC inválido: {$row['mac_address']}";
                    continue;
                }

                $equipment = InventoryModel::query()
                    ->create([
                        'brand_id' => $this->baseData['brand_id'],
                        'type_id' => $this->baseData['type_id'],
                        'model_id' => $this->baseData['model_id'],
                        'branch_id' => $this->baseData['branch_id'],
                        'mac_address' => strtoupper($row['mac_address']),
                        'serial_number' => strtoupper($row['serial_number']),
                        'registration_date' => Carbon::today(),
                        'status_id' => $this->baseData['status_id'],
                        'comments' => $row['comments'] ?? $this->baseData['comments'],
                        'company_id' => $this->baseData['company_id'],
                    ]);

                $log = new InventoryLogDTO(
                    equipment_id: $equipment->id,
                    user_id: Auth::user()->id,
                    technician_id: null,
                    execution_date: Carbon::today(),
                    service_id: null,
                    status_id: 1,
                    description: 'Equipo registrado desde archivo .xlsx',
                );

                InventoryLogModel::query()->create($log->toArray());

                $successful++;
            } catch (\Exception $exception) {
                $errors[] = "Fila " . ($index + 2) . ": Error - " . $exception->getMessage();
            }
        }
        $this->results = [
            "successful" => $successful,
            "errors" => $errors,
            'total_processed' => $rows->count(),
        ];
    }

    public function rules(): array
    {
        return [
            '*.mac_address' => 'required|mac_address',
            '*.serial_number' => 'required|string',
        ];
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function isValidMac(string $mac): bool
    {
        $pattern = '/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$|^([0-9A-Fa-f]{4}[.]){2}([0-9A-Fa-f]{4})$|^[0-9A-Fa-f]{12}$/';
        return preg_match($pattern, $mac);
    }
}
