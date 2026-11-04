<?php

namespace App\Services\v1\management\accounting\DTE;

use App\Enums\v1\Accounting\DTE\EventTypes;
use App\Enums\v1\Billing\DocumentTypes;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Random\RandomException;
use RuntimeException;

readonly class DTESignatureService
{
    public function __construct(
        private readonly Client        $httpClient,
        private readonly DTELogService $logService,
    )
    {
    }

    /****
     * Genera auth Token y lo almacena en redis (vigencia de 24 horas)
     *
     * @return object
     */
    public function auth(): object
    {
        $uri = config('dte.auth_url');

        try {
            $request = $this->httpClient->request('POST', $uri, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'User-Agent' => 'netplus-isp',
                ],
                'form_params' => [
                    'user' => config('dte.user'),
                    'pwd' => config('dte.password'),
                ],
            ]);

            $decoded = json_decode($request->getBody()->getContents());

//            Redis::setex('dte_auth_token', 86400, $decoded->body->token);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Respuesta de auth inválida: ', json_last_error_msg());
            }

            return $decoded;
        } catch (GuzzleException $e) {
            Log::error("DTE auth falló", ['error' => $e->getMessage()]);
            throw new RuntimeException("No se pudo autenticar con el servicio DTE.", previous: $e);
        }
    }

    /****
     * Firma y envía el dte a hacienda según su tipo.
     *
     * @param array $dte
     * @param int $documentId
     * @return object
     * @throws RandomException|RuntimeException
     */
    public function singAndSend(array $dte, int $documentId): object
    {
        $this->ensureAuthToken();
        $signedDocument = $this->signAndValidate($dte);
        $type = DocumentTypes::from($documentId);

        $haciendaResponse = $type === DocumentTypes::ANULACION
            ? $this->invalidateDocument(signedDoc: $signedDocument)
            : $this->sendDocument(
                version: $dte['identificacion']['version'],
                type: $type->code(),
                signedDocument: $signedDocument,
                genCode: $dte['identificacion']['codigoGeneracion'],
            );

        return (object)[
            'haciendaResponse' => $haciendaResponse,
            'signedDocument' => $signedDocument,
        ];
    }

    /**
     * Firma y envía un evento a hacienda.
     *
     * @param array $dte
     * @param EventTypes $eventType
     * @return object
     * @throws RandomException
     */
    public function singAndSendEvent(array $dte, EventTypes $eventType): object
    {
        $this->ensureAuthToken();
        $signedDocument = $this->signAndValidate($dte);
        $haciendaResponse = $this->sendEvent(
            version: $dte['identificacion']['version'],
            type: $eventType->code(),
            signedDocument: $signedDocument,
            genCode: $dte['identificacion']['codigoGeneracion'],
        );

        return (object)[
            'haciendaResponse' => $haciendaResponse,
            'signedDocument' => $signedDocument,
        ];
    }

    /**
     * Envía el DTE al firmador.
     *
     * @param array $dte
     * @return string
     */
    private function signAndValidate(array $dte): string
    {
        $signed = $this->signDocument($dte);

        if (empty($signed->body)) {
            throw new RuntimeException("El firmador no retorno ningún documento encriptado.");
        }

        return $signed->body;
    }


    private function ensureAuthToken(): void
    {
        $ttl = Redis::ttl('dte_auth_token');

        if ($ttl > 1800) return;

        $auth = $this->auth();

        if (empty($auth->body->token)) {
            throw new RuntimeException("No se pudo obtener el token de autenticación.");
        }

        Redis::setex('dte_auth_token', 86400, $auth->body->token);
    }

    /***
     * Firma (encripta el json del dte).
     *
     * @param array $dte
     * @return object
     */
    public function signDocument(array $dte): object
    {
        $uri = config('dte.signer_url');

        try {
            $request = $this->httpClient->request('POST', $uri, [
                'headers' => [
                    'Content-Type' => 'application/JSON',
                ],
                'json' => [
                    'nit' => config('dte.nit'),
                    'activo' => true,
                    'passwordPri' => config('dte.crt_key'),
                    'dteJson' => $dte,
                ],
            ]);

            $decoded = json_decode($request->getBody()->getContents());

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Respuesta del firmador inválida: ', json_last_error_msg());
            }

            return $decoded;
        } catch (GuzzleException $e) {
            Log::error('Fallo la firma del documento: ', ['error' => $e->getMessage()]);
            throw new RuntimeException('No se pudo firmar el documento.', previous: $e);
        }
    }

    /**
     * Envía el documento a hacienda para ser aprobado o no.
     *
     * @param int $version
     * @param string $type
     * @param string $signedDocument
     * @param string $genCode
     * @return object
     * @throws RandomException
     */
    private function sendDocument(
        int    $version,
        string $type,
        string $signedDocument,
        string $genCode
    ): object
    {
        return $this->dispatch(
            uri: config('dte.reception_url'),
            payload: [
                'ambiente' => '01',
                'idEnvio' => random_int(100000, 999999),
                'version' => $version,
                'tipoDte' => $type,
                'documento' => $signedDocument,
                'codigoGeneracion' => $genCode,
            ],
            errorContext: 'el envío del documento.',
        );
    }

    /****
     * Envía a hacienda la invalidación para ser aprobada o rechazada.
     *
     * @param string $signedDoc
     * @return object
     * @throws RandomException
     */
    private function invalidateDocument(string $signedDoc): object
    {
        return $this->dispatch(
            uri: config('dte.invalidation_url'),
            payload: [
                'ambiente' => '01',
                'idEnvio' => random_int(100000, 999999),
                'version' => 2,
                'documento' => $signedDoc,
            ],
            errorContext: 'el envío de la invalidación.',
        );
    }

    /**
     * Envía el evento a hacienda.
     *
     * @param int $version
     * @param string $type
     * @param string $signedDocument
     * @param string $genCode
     * @return object
     * @throws RandomException
     */
    private function sendEvent(int $version, string $type, string $signedDocument, string $genCode): object
    {
        return $this->dispatch(
            uri: config('dte.reception_url'),
            payload: [
                'ambiente' => '01',
                'idEnvio' => random_int(100000, 999999),
                'version' => $version,
                'tipoDte' => $type,
                'documento' => $signedDocument,
                'codigoGeneracion' => $genCode,
            ],
            errorContext: 'el envío de evento',
        );
    }

    /**
     * @param string $uri
     * @param array $payload
     * @param string $errorContext
     * @return object|mixed
     */
    private function dispatch(string $uri, array $payload, string $errorContext): object
    {
        try {
            $request = $this->httpClient->request('POST', $uri, [
                'headers' => [
                    'Authorization' => Redis::get('dte_auth_token'),
                    'User-Agent' => 'netplus-isp',
                    'Content-Type' => 'application/JSON',
                ],
                'json' => $payload,
            ]);

            $decoded = json_decode($request->getBody()->getContents());

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException("Respuesta de hacienda inválida: ", json_last_error_msg());
            }

            return $decoded;

        } catch (GuzzleException $e) {
            Log::channel('dte_logger')
                ->error("Fallo {$errorContext}", ['error' => $e->getMessage()]);

            throw new RuntimeException("No se pudo completar {$errorContext}.", previous: $e);
        }
    }
}
