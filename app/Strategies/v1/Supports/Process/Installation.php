<?php

namespace App\Strategies\v1\Supports\Process;

use App\Contracts\v1\Supports\SupportTypeInterface;
use App\Models\Clients\ContractModel;
use App\Models\Management\Profiles\InternetModel;
use App\Models\Supports\SupportDetailModel;
use App\Models\Supports\SupportModel;
use App\Models\Supports\TypeModel;
use Carbon\Carbon;

class Installation extends BaseSupport implements SupportTypeInterface
{
    public function handle(array $data, string $ticket): SupportModel
    {
        /****
         * Creando Contrato
         ****/
        $begins = Carbon::parse($data['contract_date']);
        $ends = Carbon::parse($data['contract_end_date']);

        //  Obteniendo el costo de instalación
        $type = TypeModel::query()->findOrFail((int)$data['type_id']);

        //  Obteniendo el precio del plan adquirido
        $profile = InternetModel::query()->findOrFail((int)$data['internet_profile_id']);
        $duration = $begins->diffinMonths($ends, false);
        $total = $profile->price * $duration;

        $contractData = [
            'client_id' => (int)$data['client_id'],
            'contract_date' => $begins->toDateString(),
            'contract_end_date' => $ends->toDateString(),
            'installation_price' => $type?->price,
            'contract_amount' => $total,
            'contract_status_id' => 1,
            'status_id' => 1,
        ];
        $contract = ContractModel::query()->create($contractData);

        /****
         * Creando Soporte
         ****/
        $support = $this->createSupport($data, $ticket, $contract->id);

        /****
         * Agregando detalles al Soporte
         ****/
        $supportDetails = [
            'support_id' => $support->id,
            'type_id' => $data['type_id'],
            'internet_profile_id' => $data['internet_profile_id'],
            'node_id' => $data['node_id'],
            'equipment_id' => $data['equipment_id'],
        ];
        SupportDetailModel::query()->create($supportDetails);

        return $support;
    }

    public function update(SupportModel $support, array $data): SupportModel
    {
        /*********
         * Actualizando contrato
         *********/
        $contract = $support->contract;
        if ($contract) {
            $begins = $this->parsingDate($data['contract_date']);
            $ends = $this->parsingDate($data['contract_end_date']);

            //  Obteniendo el costo de instalación
            $type = TypeModel::query()->findOrFail((int)$data['type_id']);

            //  Obteniendo el precio del plan adquirido
            $profile = InternetModel::query()->findOrFail((int)$data['internet_profile_id']);
            $duration = $begins->diffinMonths($ends, false);
            $total = $profile->price * $duration;

            $contract->update([
                'contract_date' => $begins->toDateString(),
                'contract_end_date' => $ends->toDateString(),
                'installation_price' => $type?->price,
                'contract_amount' => $total,
            ]);
        }

        /***************
         * Actualizando detalles
         ***************/
        $support->details()->updateOrCreate(
            ['support_id' => $support->id],
            [
                'type_id' => $data['type_id'],
                'internet_profile_id' => $data['internet_profile_id'],
                'node_id' => $data['node_id'],
                'equipment_id' => $data['equipment_id'],
            ],
        );
        return $this->updateSupport($support, $data);
    }

    protected static function parsingDate(string $date): Carbon
    {
        return Carbon::parse($date);
    }
}
