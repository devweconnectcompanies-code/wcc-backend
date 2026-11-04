<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Libraries\MikrotikAPI;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Models\Management\Profiles\InternetModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class InternetController extends Controller
{
    public function internetProfilesList(): JsonResponse
    {
        return response()->json([
            'response' => InternetModel::query()
                ->select(['id', 'name'])
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function internetPlansList(): JsonResponse
    {
        return response()->json([
            'response' => $this->plans(false)
        ]);
    }

    public function iptvPlansList(): JsonResponse
    {
        return response()->json([
            'response' => $this->plans(true)
        ]);
    }

    /***
     * @return JsonResponse
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     * @throws \RouterOS\Exceptions\QueryException
     */
    public function mikrotikProfilesList(): JsonResponse
    {
        $server = AuthServerModel::query()->find(1);
        $routerOS = new MikrotikAPI();
        $profiles = $routerOS->executeQuery(
            $server->ip,
            $server->user,
            $server->secret,
            '/ppp/profile/print',
        );

        $data = collect($profiles)->filter(function ($profile) {
            return stripos($profile['name'], 'default') === false;
        })->map(function ($profile) {
            return [
                'id' => $profile['name'],
                'name' => $profile['name'],
            ];
        });

        return response()->json([
            'response' => $data->values()
        ]);
    }

    private function plans(bool $iptv): Collection
    {
        return InternetModel::query()
            ->where([
                ['status_id', CommonStatus::ACTIVE->value],
                ['iptv', $iptv]
            ])
            ->select(['id', 'name'])
            ->orderBy('id', 'ASC')
            ->get()
            ->makeHidden('status');
    }
}
