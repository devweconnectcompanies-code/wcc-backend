<?php

namespace App\Services\v1\imports;

use App\Models\Services\ServiceInternetModel;
use App\Services\v1\network\MikrotikInternetService;

class ImportMikrotikPPPService
{
    private const CHUNK_SIZE = 25;

    public function __construct(private MikrotikInternetService $mikrotikService)
    {

    }

    /***
     * Busca si existe un PPPoe Profile, de no encontrarlo se crea en la RB.
     * @param array $server
     * @param int $chunkSize
     * @return array|array[]
     */
    public function sync(array $server, int $chunkSize = self::CHUNK_SIZE): array
    {
        $results = [
            'created' => [],
            'existing' => [],
            'errors' => [],
        ];

        ServiceInternetModel::query()
            ->with(['profile', 'service.client'])
            ->where('status_id', true)
            ->chunkById($chunkSize, function ($chunk) use ($server, &$results) {
                foreach ($chunk as $internetService) {
                    $username = $internetService->user;

                    try {
                        $existingUser = $this->mikrotikService->getUser($server, $username);

                        if ($existingUser !== null) {
                            $results['existing'][] = $username;
                            continue;
                        }

                        $client = $internetService->service?->client;
                        $comment = $client ? trim("{$client->name} {$client->surname}") : $username;

                        $this->mikrotikService->createUser(
                            $server,
                            $internetService->profile->toArray(),
                            $username,
                            $internetService->secret,
                            $comment
                        );

                        $results['created'][] = $username;
                    } catch (\Throwable $e) {
                        $results['errors']['username'] = $e->getMessage();
                    }
                }

            });
        return $results;
    }
}
