<?php

namespace App\Services\v1\network;

use App\Libraries\MikrotikAPI;
use Illuminate\Validation\ValidationException;
use Throwable;

readonly class MikrotikInternetService
{
    /**
     * Constructor del servicio.
     *
     * @param MikrotikAPI $mikrotikAPI
     */
    public function __construct(private readonly MikrotikAPI $mikrotikAPI)
    {

    }

    /**
     * Obtiene la información detallada de un usuario PPPoE específico.
     *
     * @param array $server
     * @param string $username
     * @return array|null
     * @throws \RouterOS\Exceptions\ConfigException
     */
    public function getUser(array $server, string $username): ?array
    {
        $result = $this->mikrotikAPI->getPPPSecret(
            host: $server['ip'],
            user: $server['user'],
            pass: $server['secret'],
            secretName: $username,
        );

        return $result[0] ?? null;
    }

    /**
     * Crea un nuevo usuario PPPoE activo en el Mikrotik especificado.
     *
     * @param array $server
     * @param array $profile
     * @param string $username
     * @param string $password
     * @param string $comment
     * @return void
     */
    public function createUser(
        array  $server,
        array  $profile,
        string $username,
        string $password,
        string $comment
    ): void
    {
        $data = [
            'name' => $username,
            'password' => $password,
            'service' => 'pppoe',
            'profile' => $profile['mk_profile'],
            'comment' => $comment,
            'disabled' => 'no',
        ];

        try {
            $this->mikrotikAPI->createPPPSecret(
                host: $server['ip'],
                user: $server['user'],
                pass: $server['secret'],
                secretData: $data,
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'mikrotik' => "Error al crear el usuario PPPoE: {$e->getMessage()}",
            ]);
        }
    }

    /**
     * Actualiza los datos de un usuario PPPoE existente en el Mikrotik.
     *
     * @param array $server
     * @param string $currentUsername
     * @param array $data
     * @return void
     */
    public function updateUser(array $server, string $currentUsername, array $data): void
    {
        try {
            $this->mikrotikAPI->updatePPPSecret(
                host: $server['ip'],
                user: $server['user'],
                pass: $server['secret'],
                currentName: $currentUsername,
                newData: $data,
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'mikrotik' => "Error al actualizar el usuario PPPoE: {$e->getMessage()}",
            ]);
        }
    }

    /**
     * Actualiza varios usuarios PPPoE en un solo servidor usando una única conexión RouterOS.
     *
     * @param array $server
     * @param array $updates
     * @return array
     */
    public function updateMultipleUsers(array $server, array $updates): array
    {
        try {
            return $this->mikrotikAPI->updateMultiplePPPSecrets(
                host: $server['ip'],
                user: $server['user'],
                pass: $server['secret'],
                updates: $updates,
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'mikrotik' => "Error al actualizar usuarios PPPoE en lote: {$e->getMessage()}",
            ]);
        }
    }

    /**
     * Cambia el estado de activación (habilitado/deshabilitado) de un usuario PPPoE.
     *
     * @param array $server
     * @param string $username
     * @param bool $disable
     * @return void
     */
    public function toggleUser(array $server, string $username, bool $disable = true): void
    {
        try {
            $this->mikrotikAPI->togglePPPSecret(
                host: $server['ip'],
                user: $server['user'],
                pass: $server['secret'],
                secretName: $username,
                disable: $disable,
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'mikrotik' => "Error al activar/desactivar el usuario PPPoE: {$e->getMessage()}",
            ]);
        }
    }

    /**
     * Habilita el acceso a un usuario PPPoE en el Mikrotik.
     *
     * @param array $server
     * @param string $username
     * @return void
     * @throws \RouterOS\Exceptions\ConfigException
     */
    public function enableUser(array $server, string $username): void
    {
        $this->mikrotikAPI->togglePPPSecret(
            host: $server['ip'],
            user: $server['user'],
            pass: $server['secret'],
            secretName: $username,
            disable: false,
        );
    }

    /**
     * Deshabilita (suspende) el acceso a un usuario PPPoE en el Mikrotik.
     *
     * @param array $server
     * @param string $username
     * @return void
     * @throws \RouterOS\Exceptions\ConfigException
     */
    public function disableUser(array $server, string $username): void
    {
        $this->mikrotikAPI->togglePPPSecret(
            host: $server['ip'],
            user: $server['user'],
            pass: $server['secret'],
            secretName: $username,
        );
    }

    /**
     * Elimina de forma definitiva un usuario PPPoE del Mikrotik.
     *
     * @param array $server
     * @param string $username
     * @return void
     */
    public function deleteUser(array $server, string $username): void
    {
        try {
            $this->mikrotikAPI->deletePPPSecret(
                host: $server['ip'],
                user: $server['user'],
                pass: $server['secret'],
                secretName: $username,
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'mikrotik' => "Error al eliminar el usuario PPPoE: {$e->getMessage()}",
            ]);
        }
    }

    /**
     * Obtiene la lista completa de perfiles PPPoE configurados en el Mikrotik.
     *
     * @param array $server
     * @return array
     */
    public function listProfiles(array $server): array
    {
        try {
            return $this->mikrotikAPI->listPPPPoeProfiles(
                host: $server['ip'],
                user: $server['user'],
                pass: $server['secret'],
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'mikrotik' => "Error al listar los propietarios PPPoE: {$e->getMessage()}",
            ]);
        }
    }
}
