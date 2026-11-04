<?php

namespace App\Libraries\Accounting\DTE;

class IssuerUtils
{
    /***
     * Nit de la empresa.
     * @return string
     */
    public function getNit(): string
    {
        return '12170206211014';
    }

    /***
     * NRC de la empresa
     * @return string
     */
    public function getNrc(): string
    {
        return '3034839';
    }

    /***
     * Nombre de la empresa.
     * @return string
     */
    public function getName(): string
    {
        return 'NETPLUS COMPANY WORK S.A. DE C.V';
    }

    /***
     * ID del giro.
     * @return string
     */
    public function activityCode(): string
    {
        return '61109';
    }

    /***
     * Nombre del giro.
     * @return string
     */
    public function activityName(): string
    {
        return 'Servicio de Internet N.C.P.';
    }

    /***
     * Código del departamento.
     * @return int
     */
    public function getState(): int
    {
        return 12;
    }

    /***
     * Código del municipio.
     * @return int
     */
    public function getMunicipality(): int
    {
        return 22;
    }

    /***
     * Código del distrito.
     * @return int
     */
    public function getDistrict(): int
    {
        return 17;
    }

    /***
     * Dirección de la empresa.
     * @return string
     */
    public function getAddress(): string
    {
        return 'Calle principal, Col. San Francisco, #34';
    }

    /***
     * Retorna número de teléfono.
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return '76266022';
    }

    /***
     * Correo electrónico de la empresa.
     * @return string
     */
    public function getEmail(): string
    {
        return 'netpluscompanywork@gmail.com';
    }
}
