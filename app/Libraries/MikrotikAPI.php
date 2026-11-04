<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\QueryException;
use RouterOS\Query;
use Throwable;

/**
 * Clase cliente para interactuar con la API de RouterOS.
 * Permite gestionar conexiones, perfiles PPP, secrets y monitorear el estado del hardware.
 */
class MikrotikAPI
{
    private const DEFAULT_PORT = 45000;
    private const TIMEOUT = 30;
    private const SOCKET_TIMEOUT = 120;
    private const ATTEMPTS = 1;
    private ?Client $client = null;

    /**
     * Crea y retorna un objeto de configuración para la conexión con el RouterOS.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return Config
     * @throws ConfigException
     */
    private function createConfig(string $host, string $user, string $pass, int $port = self::DEFAULT_PORT): Config
    {
        return new Config([
            'host' => $host,
            'user' => $user,
            'pass' => $pass,
            'port' => $port,
            'timeout' => self::TIMEOUT,
            'attempts' => self::ATTEMPTS,
            'socket_timeout' => self::SOCKET_TIMEOUT,
            'throw_timeout_exception' => false,
        ]);
    }

    /**
     * Establece la conexión con la API del equipo Mikrotik e inicializa la propiedad $client.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return void
     * @throws BadCredentialsException
     * @throws ClientException
     * @throws ConfigException
     * @throws ConnectException
     * @throws QueryException
     */
    private function connect(string $host, string $user, string $pass, int $port = self::DEFAULT_PORT): void
    {
        $config = $this->createConfig(
            host: $host,
            user: $user,
            pass: $pass,
            port: $port
        );
        $this->client = new Client($config);
    }

    /**
     * Cierra la sesión limpia restableciendo la propiedad $client a null.
     *
     * @return void
     */
    private function disconnect(): void
    {
        $this->client = null;
    }

    /**
     * Ejecuta una acción enviada mediante una función callback sobre la conexión Mikrotik
     * y garantiza el cierre del socket al finalizar.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param callable $action
     * @param int $port
     * @return mixed
     * @throws ConfigException
     */
    public function performActionAndClose(
        string   $host,
        string   $user,
        string   $pass,
        callable $action,
        int      $port = self::DEFAULT_PORT
    ): mixed
    {
        try {
            $this->connect(host: $host, user: $user, pass: $pass, port: $port);
            return $action($this->client);
        } catch (BadCredentialsException|ConnectException|QueryException $e) {
            throw ValidationException::withMessages([
                'operation' => "Ha ocurrido un error al conectarse al equipo de Mikrotik. {$e->getMessage()}",
            ]);
        } catch (ClientException $e) {
            throw ValidationException::withMessages([
                'operation' => "Tiempo de espera agotado al leer la respuesta del equipo Mikrotik. Verifique la conexión de red. ({$e->getMessage()})",
            ]);
        } finally {
            $this->disconnect();
        }
    }

    /**
     * Obtiene la lista completa de perfiles PPPoE configurados en el router.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function listPPPPoeProfiles(
        string $host,
        string $user,
        string $pass,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) {
            $query = new Query('/ppp/profile/print');
            return $client->query($query)->read();
        }, $port);
    }

    /**
     * Ejecuta una consulta genérica a un endpoint de Mikrotik aplicando filtros opcionales (WHERE).
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $endpoint
     * @param array $where
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function executeQuery(
        string $host,
        string $user,
        string $pass,
        string $endpoint,
        array  $where = [],
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($endpoint, $where) {
            $query = new Query($endpoint);

            foreach ($where as $key => $value) {
                $query->where($key, $value);
            }
            return $client->query($query)->read();
        }, $port);
    }

    /**
     * Crea un nuevo usuario/secret de tipo PPP en el equipo Mikrotik.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param array $secretData
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function createPPPSecret(
        string $host,
        string $user,
        string $pass,
        array  $secretData,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($secretData) {
            $query = (new Query('/ppp/secret/add'))
                ->equal('name', $secretData['name'])
                ->equal('password', $secretData['password'])
                ->equal('service', $secretData['service'])
                ->equal('profile', $secretData['profile'])
                ->equal('comment', $secretData['comment'])
                ->equal('disabled', $secretData['disabled']);

            return $client->query($query)->read();
        }, $port);
    }

    /**
     * Habilita o deshabilita un usuario/secret PPP en Mikrotik buscando primero por su nombre.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $secretName
     * @param bool $disable
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function togglePPPSecret(
        string $host,
        string $user,
        string $pass,
        string $secretName,
        bool   $disable = true,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($secretName, $disable) {
            $queryFind = (new Query('/ppp/secret/print'))->where('name', $secretName);
            $result = $client->query($queryFind)->read();

            if (empty($result)) {
                throw ValidationException::withMessages([
                    'secret' => "No se encontró el usuario {$secretName}."
                ]);
            }
            $secretID = $result[0]['.id'];

            $queryToggle = (new Query('/ppp/secret/set'))
                ->equal('.id', $secretID)
                ->equal('disabled', $disable ? 'yes' : 'no');

            return $client->query($queryToggle)->read();
        }, $port);
    }

    /**
     * Actualiza la información de un usuario PPP especifíco según los datos provistos.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $currentName
     * @param array $newData
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function updatePPPSecret(
        string $host,
        string $user,
        string $pass,
        string $currentName,
        array  $newData,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($currentName, $newData) {
            $queryFind = (new Query('/ppp/secret/print'))->where('name', $currentName);
            $result = $client->query($queryFind)->read();

            if (empty($result)) {
                throw ValidationException::withMessages([
                    'secret' => "No se encontró el usuario {$currentName}."
                ]);
            }
            $secretID = $result[0]['.id'];

            $queryUpdate = (new Query('/ppp/secret/set'))
                ->equal('.id', $secretID);

            foreach ($newData as $key => $value) {
                $queryUpdate->equal($key, $value);
            }

            return $client->query($queryUpdate)->read();
        }, $port);
    }

    /**
     * Actualiza varios PPPoE Secrets en una única conexión RouterOS.
     * Los que no se encuentran o fallen quedan registrados en el resultado bajo 'error', sin abortar el lote.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param array $updates
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function updateMultiplePPPSecrets(
        string $host,
        string $user,
        string $pass,
        array  $updates,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($updates) {
            $results = [];

            foreach ($updates as $update) {
                $secretName = $update['name'];

                try {
                    $queryFind = (new Query('/ppp/secret/print'))->where('name', $secretName);
                    $found = $client->query($queryFind)->read();

                    if (empty($found)) {
                        Log::channel('cut-service')
                            ->warning('PPPoE secret no encontrado:', ['secret' => $secretName]);
                        $results[$secretName] = ['OK' => false, 'error' => "No se encontró el usuario {$secretName}."];
                        continue;
                    }

                    $secretID = $found[0]['.id'];
                    $queryUpdate = (new Query('/ppp/secret/set'))->equal('.id', $secretID);

                    foreach ($update['data'] as $key => $value) {
                        $queryUpdate->equal($key, $value);
                    }

                    $client->query($queryUpdate)->read();
                    $results[$secretName] = ['OK' => true];

                } catch (Throwable $e) {
                    Log::channel('cut-service')->error('Error al actualizar a deuda a:', [
                        'secret' => $secretName,
                        'error' => $e->getMessage(),
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                    $results[$secretName] = ['OK' => false, 'error' => $e->getMessage()];
                }
            }
            return $results;
        }, $port);
    }

    /**
     * Elimina permanentemente un usuario PPP del equipo Mikrotik.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $secretName
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function deletePPPSecret(
        string $host,
        string $user,
        string $pass,
        string $secretName,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($secretName) {
            $queryFind = (new Query('/ppp/secret/print'))->where('name', $secretName);
            $result = $client->query($queryFind)->read();

            if (empty($result)) {
                throw ValidationException::withMessages([
                    'secret' => "No se encontró el usuario {$secretName}."
                ]);
            }
            $secretID = $result[0]['.id'];

            $queryDelete = (new Query('/ppp/secret/remove'))->equal('.id', $secretID);
            return $client->query($queryDelete)->read();
        }, $port);
    }

    /**
     * Obtiene los datos detallados de un usuario PPP específico mediante su nombre de usuario.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $secretName
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function getPPPSecret(
        string $host,
        string $user,
        string $pass,
        string $secretName,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->executeQuery($host, $user, $pass, '/ppp/secret/print', ['name' => $secretName], $port);
    }

    /**
     * Obtiene información general de hardware y sistema del Mikrotik (CPU, Memoria RAM, Almacenamiento).
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function getSystemResources(
        string $host, string $user, string $pass, int $port = self::DEFAULT_PORT): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) {
            $resources = $client->query(new Query('/system/resource/print'))->read();
            $identity = $client->query(new Query('/system/identity/print'))->read();
            return [
                'resources' => $resources,
                'identity' => $identity
            ];
        }, $port);
    }


    /**
     * Monitorea de forma instantánea (una sola muestra) el tráfico de red o de una interfaz dada.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $interface
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function getInterfaceTraffic(
        string $host,
        string $user,
        string $pass,
        string $interface = 'eth1',
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($interface) {
            $query = new Query('/interface/monitor-traffic')
                ->equal('interface', $interface)
                ->equal('once', '');
            return $client->query($query)->read();
        }, $port);
    }

    /**
     * Obtiene el listado de todas las interfaces del router y sus contadores acumulados de tráfico.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function getInterfaces(string $host, string $user, string $pass, int $port = self::DEFAULT_PORT): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) {
            return $client->query(new Query('/interface/print'))->read();
        }, $port);
    }

    /**
     * Obtiene el listado de todas las conexiones PPPoE/PPP que se encuentran activas en el momento.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function getActivePPPConnections(
        string $host,
        string $user,
        string $pass,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) {
            return $client->query(new Query('/ppp/active/print'))->read();
        }, $port);
    }

    /**
     * Busca los detalles unificados de un usuario PPPoE: su sesión activa, los datos de su secret
     * y el consumo de tráfico dinámico actual.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $pppoeUser
     * @param int $port
     * @return array
     * @throws ConfigException
     */
    public function getActiveConnectionDetails(
        string $host,
        string $user,
        string $pass,
        string $pppoeUser,
        int    $port = self::DEFAULT_PORT
    ): array
    {
        return $this->performActionAndClose($host, $user, $pass, function (Client $client) use ($pppoeUser) {
            $activeResult = $client->query(
                new Query('/ppp/active/print')->where('name', $pppoeUser)
            )->read();

            $secretResult = $client->query(
                new Query('/ppp/secret/print')->where('name', $pppoeUser)
            )->read();

            $traffic = null;

            if (!empty($activeResult)) {
                $interfaceName = "<pppoe-{$pppoeUser}>";

                $trafficQuery = new Query('/interface/monitor-traffic')
                    ->equal('interface', $interfaceName)
                    ->equal('once', '');

                $trafficResult = $client->query($trafficQuery)->read();
                $traffic = $trafficResult[0] ?? null;
            }

            return [
                'active' => $activeResult[0] ?? null,
                'secret' => $secretResult[0] ?? null,
                'traffic' => $traffic,
            ];
        }, $port);
    }
}
