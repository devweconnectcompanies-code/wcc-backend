<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('v1.management.pdf.styles.fonts')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.15em;
            color: #333;
            background: #fff;
            margin: 1.5cm 1.9cm;
            text-align: justify;
        }

        .header {
            width: 100%;
            margin-bottom: 12px;
        }

        .logo {
            width: 130px;
            vertical-align: middle;
            text-align: left;
        }

        .header-table {
            width: 100%;
            vertical-align: middle;
            font-family: 'Bodoni', sans-serif;
            font-size: 20px;
            padding-bottom: 7px;
            border-bottom: #0a0a0a 1px solid;
        }

        .content {
            /*font-family: 'Raleway', sans-serif;*/
            font-family: 'Calibri', sans-serif;
            font-size: 11px;
            text-align: justify;
        }

        .text-subtitle {
            font-size: 14px;
            text-align: justify;
            font-weight: bold;
        }

        .mt-sm {
            margin-top: .5em;
        }

        .signature-section {
            margin-top: 1em;
            page-break-inside: avoid;
        }

        .client-data {
            margin-top: 1.5em;
            margin-bottom: 1.5em;
        }

        .client-data-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Calibri', sans-serif;
            font-size: 11px;
        }

        .client-data-table td {
            padding: 4px;
            border: 1px solid #333;
            vertical-align: middle;
            line-height: 0.9;
        }

        .client-data-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }

        .signature-table {
            width: 100%;
            margin-top: 3em;
            font-family: 'Calibri', sans-serif;
            font-size: 11px;
        }

        .signature-box {
            text-align: center;
            border-top: 2px solid #333;
            padding-top: 5px;
            margin-top: 2em;
        }

        .signature-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }

        .date-place {
            margin-top: 1.5em;
            text-align: left;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="header">
    <table class="header-table">
        <tr>
            <th style="width: 25%; border-right: #0a0a0a 1px solid;">
                <img src="{{ public_path('assets/img/logos/logo_color.png') }}" alt="NETPLUS LOGO" class="logo">
            </th>
            <th style="width: 75%;">
                Contrato de prestación de servicios de internet
            </th>
        </tr>
    </table>
</div>

<div class="content">
    <p class="text-subtitle">I. CONDICIONES GENERALES APLICABLES A TODOS LOS SERVICIOS.</p>
    <p>
        <b>PRIMERO: OBJETO.</b> El presente contrato tiene como objeto regular la relación de prestación de los
        servicios de Telecomunicaciones que NETPLUS, presta a EL CLIENTE, incluyendo las condiciones aplicables de
        manera general a todos los servicios prestados, así como los términos y condiciones especiales y particulares
        aplicables a cada servicio contratado por EL CLIENTE, los cuales pueden ser: <b>a) INTERNET. b) Enlace de Datos.
            c) Otros.</b> <br/>
    </p>

    <p class="mt-sm">
        <b>SEGUNDO: PLAZO Y VIGENCIA.</b> La vigencia del presente contrato será de 12 meses contados a partir de la
        fecha de instalación, y será renovado de manera automática por el mismo período a menos que una de las partes
        haga del conocimiento de la otra de su intención de cancelación con al menos cinco (15) días calendario previos
        a la fecha de finalización del Contrato. Sin perjuicio de lo anterior, el Contrato puede terminarse sin
        responsabilidad por cualquiera de las partes comunicando por escrito a la otra con al Menos cinco (15) días
        calendario de anticipación a la fecha de terminación quedando vigente cualquier obligación
        que surja antes de la fecha de terminación EL CLIENTE deberá devolver, en las oficinas establecidas por NETPLUS,
        cualquier equipo propiedad de NETPLUS en poder de EL CLIENTE caso contrario será sumado a la cuenta de EL
        CLIENTE, el monto equivalente del equipo.
    </p>

    <p class="mt-sm">
        <b>TERCERO: TARIFAS Y PRECIOS.</b> El servicio contratado, así como las tarifas derivadas de cada uno de los
        mismos, serán determinadas por NETPLUS. Los precios estarán sujetos a cambios por NETPLUS, quien dictaminará el
        precio en base al Servicio requerido.
    </p>

    <p class="mt-sm">
        <b>CUARTO: PERIODO DE FACTURACIÓN.</b> El período de facturación es mensual a partir del día en que el servicio
        es instalado. Si por alguna razón EL CLIENTE decide terminar de forma anticipada el presente Contrato antes del
        primer mes de servicio, no se devolverá dinero alguno acorde al principio "MES COMERCIALIZADO, MES PAGADO". En
        caso de que EL CLIENTE cancele por cualquier causa uno o varios de los servicios contratados antes del
        vencimiento de dicho período, NETPLUS estará facultado para realizar las gestiones de cobro correspondientes, EL
        CLIENTE deberá cancelar el monto correspondiente a los meses restantes del contrato suscrito, en concepto
        indemnizatorio. En consecuencia, EL CLIENTE se compromete a pagar a NETPLUS: a) La mensualidad de los servicios
        prestados; y, b) Todo cargo extra por servicios adicionales solicitados por EL CLIENTE. c) Todo pago acordado en
        concepto de arrendamiento de los equipos requeridos para la prestación de los Servicios. d) Todo equipo es dado
        al cliente en comodato, al terminar el servicio, un upgrade o cualquier otra causa que de por terminado dicha
        instalación el cliente debe regresar todos los equipos en perfectas condiciones o realizar el pago por el mismo.
    </p>

    <p class="mt-sm">
        <b>QUINTO: FORMA DE PAGO, REQUISITO Y MORA.</b> Las partes acuerdan que el pago de la suma mensual adeudada por
        EL CLIENTE de acuerdo con el servicio contratado podrá ser cancelada mediante a) Descuento o rebaja realizada de
        manera directa y automática a la Tarjeta de Crédito que EL CLIENTE indique en la boleta de cargo Automático
        estipulada por el banco emisor. b) Mediante pago en efectivo ante cualquier oficina de NETPLUS. c) Mediante
        cheque a nombre de NETPLUS, el cual se recibirá sujeto al buen cobro por parte de NETPLUS. e) Pago en efectivo
        en ventanillas de instituciones financieras o comercios autorizado por NETPLUS. f) Cualquier otro medio definido
        por NETPLUS. La facturación de los servicios se hará en forma mensual y el cargo automático o en su defecto el
        pago, se hará dentro de los últimos diez (10) días de cada mes. Si el día diez (10) llegara a ser un día
        inhábil, el pago se realizará el día hábil posterior más cercano, y su valor se consignará y cancelará en moneda
        nacional. NETPLUS se reserva el derecho de emitir facturación con otra periodicidad, la cual deberá ser
        notificada a EL CLIENTE con al menos quince (15) días calendarios previos a su aplicación o entrada en vigor. EL
        CLIENTE acepta que, si los saldos pendientes adeudados no han sido cancelados a la fecha de vencimiento, se le
        aplicará un recargo por mora del uno (1%) mensual, sin perjuicio del derecho de NETPLUS de proceder a la
        desconexión del servicio sin la necesidad de notificar a EL CLIENTE, y sin responsabilidad de su parte. NETPLUS
        ejercerá sus mejores esfuerzos a efectos de hacer entrega de la factura o recibo de pago correspondiente por los
        siguientes medios; (i) Vía correo electrónico en la dirección consignada en el presente contrato. (ii) Vía SMS o
        mensaje de texto por medio del móvil o en su defecto en el lugar de prestación del servicio o en el domicilio
        señalado por EL CLIENTE. Asimismo, EL CLIENTE podrá consultar los valores adeudados por medio de llamada
        telefónica a las oficinas de NETPLUS; EL CLIENTE a entendido de que el no recibir la factura o recibo de pago no
        exonera a EL CLIENTE de su obligación para cancelar los saldos adecuados en las oficinas de NETPLUS o en los
        agentes autorizados.
    </p>

    <p class="mt-sm">
        <b>SEXTO: SUSPENSIÓN DEL SERVICIO.</b> En caso de incumplimiento en el pago de los valores adeudados por parte
        de EL CLIENTE, durante más de 30 días calendario contados a partir de la fecha en que el pago debió realizarse,
        dará derecho a NETPLUS para suspender el servicio objeto del presente contrato, sin necesidad de aviso previo ni
        declaración judicial alguna, lo que EL CLIENTE acepta expresamente. Una vez suspendido el servicio, NETPLUS
        tendrá derecho a cobrar los saldos que le adeude EL CLIENTE, y de las cuotas pendientes del plazo contractual,
        aplicando de manera adicional a la tarifa del servicio, los costos o cargos aplicables a los valores que
        correspondan en concepto de impuesto, tasa o contribución según las leyes aplicables. En caso de que EL CLIENTE
        no concilie con NETPLUS, dichos pagos, se procederá de conformidad con la cláusula DECIMOSEXTA: JURISDICCIÓN,
        COMPETENCIA Y SOLUCIÓN DE CONTROVERSIAS del presente contrato, siendo causal para terminación inmediata. NETPLUS
        reanudará el servicio únicamente si EL CLIENTE ha efectuado los pagos atrasados y los correspondientes a los
        cargos que se hubieren generado por el atraso.
    </p>

    <p class="mt-sm">
        <b>SÉPTIMO: PLAZO PARA LAS INSTALACIONES Y CONDICIONES.</b> El periodo de instalación del servicio solicitado es
        de hasta veinticinco (25) días hábiles. a) La Instalación del servicio solicitado queda sujeta a que no existan
        condiciones fuera del alcance de NETPLUS o que sean desconocidas por NETPLUS al momento de la contratación. En
        caso de darse un evento una circunstancia considerada fuera del alcance de NETPLUS, esta le notificará
        oportunamente a EL CLIENTE para convenir el costo adicional de instalación, o la devolución del dinero que haya
        pagado por el Contrato, todo lo anterior, sin responsabilidad de NETPLUS. b) La forma de instalación del o los
        servicios queda a discreción de NETPLUS. c) Todo equipo adicional que se requiera instalar deberá permanecer en
        el domicilio de EL CLIENTE y deberá ser utilizado únicamente dentro de la misma unidad habitacional (casa,
        apartamento, oficina, etc.) de EL CLIENTE, sobre la cual recae el presente Contrato. d) NETPLUS no se hace
        responsable de conectar equipos y accesorios adicionales que EL CLIENTE tenga, para lo cual EL CLIENTE deberá
        llamar a su técnico de confianza. c) EL CLIENTE deberá de absorber los costos de reinstalación, cuando el
        servicio se haya desinstalado por cualquier causa imputable a EL CLIENTE.
    </p>

    <p class="mt-sm">
        <b>OCTAVO: OBLIGACIONES DE LAS PARTES.</b> <br>
        <b>I. Son obligaciones de NETPLUS:</b> a) Brindar las conexiones y el equipo requerido para la efectiva
        prestación del (los) servicio (s) contratados, siempre y cuando EL CLIENTE haya cumplido con los términos
        establecidos por NETPLUS, y detallados en la sección de disposiciones generales y especiales de este Contrato; y
        cuando exista viabilidad técnica para la instalación y prestación del (los) servicio (s) requeridos. b) Efectuar
        las reparaciones que sean necesarias para mantener la continuidad y la calidad del servicio. c) Responder los
        reclamos que presente EL CLIENTE, en el plazo establecido por el marco regulatorio vigente. d) Si el servicio
        entregado incluye Doble Velocidad esta se debe brindar dependiendo de la disponibilidad del enlace; en el
        periodo que el CLIENTE tenga habilitado dicha mejora en su servicio, la empresa proveerá más ancho de banda
        logrando alcanzar hasta el doble de la velocidad contratada, esto no es un upgrade a Servicio CORPORATIVO. e)
        Las demás que se establezcan en el presente Contrato.<br>
        <b>II. Son obligaciones de EL CLIENTE:</b> a) Cancelar la tarifa y demás cargos estipulados por el (los)
        servicio (s) contratado (s) en el plazo y la forma pactada en el presente contrato. b) Cumplir con los
        requerimientos técnicos necesarios para acceder al servicio contratado. c) Comunicar a NETPLUS sobre cualquier
        daño en el sistema o uso inapropiado del mismo, d) No modificar, cambiar, alterar o dañar el equipo que NETPLUS
        instaló para la prestación de los servicios contratados. e) No utilizar los servicios contratados para fines
        ilícitos, incluyendo el bypass telefónico. f) No vender, arrendar o ceder en forma alguna en los derechos y
        beneficios que otorga el presente contrato. g) Declarar la cantidad de equipos instalados. h) Pagar en forma
        adicional, las visitas posteriores que se deban realizar por conductas u omisiones de EL CLIENTE, cuando los
        técnicos no pueden instalar el (los) servicio (s) pactado(s) debido a falta de cumplimiento con los requisitos
        de instalación por parte de EL CLIENTE, o las visitas posteriores que deban hacerse para la instalación del
        servicio si las mismas se originan por información errónea suministrada por EL CLIENTE que representan mayores
        gastos para NETPLUS. i) Realizar el pago de los costos de reconexión del servicio en caso de que éste se
        desprograme por incumplimiento en el pago de este o por incumplimiento de otras cláusulas de este contrato. j)
        No utilizar el envío simultáneo (correo masivo) de mensajes a terceros que afecten la operación normal del
        servicio y la red para el caso del servicio de internet. k) Solicitar cuando proceda a NETPLUS cualquier cambio
        del domicilio o lugar donde se encuentra instalado el equipo necesario para la prestación del servicio. I) Ser
        responsable por el mal funcionamiento o daño que cause a la red de NETPLUS, por la conexión en los puntos de
        terminación de la red de cualquier equipo o aparato de su propiedad. m) Utilizar los Servicios exclusivamente
        para su uso particular, sin dedicarlo a la comercialización de servicios de telecomunicaciones en beneficio de
        terceros. n) Reparar las instalaciones de telecomunicaciones, tales como el cableado interno que sean necesarias
        en el interior del local en el que se le preste el servicio, sin perjuicio de que NETPLUS podrá aceptar realizar
        estas reparaciones, con cargo a cuenta de EL CLIENTE. <br>
        <b>III. Incumplimiento de EL CLIENTE:</b> Se considerará que el usuario final ha incumplido las condiciones
        contractuales del servicio, y por lo tanto será sujeto de suspensión inmediata del servicio, en las siguientes
        situaciones: a)Cuando se encuentren instalaciones conectadas incorrectamente dentro de la red del CLIENTE;
        b)Cuando sin previo aviso a NETPLUS se modifiquen conexiones internas o se conecten equipos para revender o
        distribuir el servicio fuera de la residencia contratada; c)Cuando se realicen alteraciones en la instalación o
        en el equipo de distribución, la rotura, cambio o desaparición de sellos en los equipos sin autorización, daños
        en los equipos o cualquier objeto o substancia colocada en el equipo que evite el funcionamiento correcto del
        consumo de energía eléctrica; y, d)Cuando el usuario final permita la conexión de sus instalaciones con las de
        un tercero
    </p>
    <p class="mt-sm">
        <b>NOVENO: CAUSAS DE TERMINACIÓN DEL CONTRATO.</b> El presente contrato se podrá dar por terminado o resuelto,
        sin necesidad de declaratoria judicial alguna, por las siguientes causas: Cuando el Contrato finalice por haber
        llegado a su vencimiento o por cualquiera de las otras causales previstas, EL CLIENTE deberá cancelar a NETPLUS,
        los valores pendientes en concepto de pago por los servicios prestados, caso contrario, se procederá de
        conformidad con la cláusula DÉCIMO SEXTA: JURISDICCIÓN, COMPETENCIA Y SOLUCIÓN DE CONTROVERSIAS del presente
        contrato.
    </p>
    <p class="mt-sm">
        <b>DÉCIMO: AUTORIZACIÓN DE INSTALACIÓN, REVISIÓN, Y DESINSTALACIÓN.</b> El CLIENTE autoriza al personal de
        NETPLUS debidamente identificado, para que realice una inspección previa a la instalación de los Servicios
        contratados y de la red Interna del (los) inmueble (s) donde se instalarán los servicios. EL CLIENTE autoriza al
        personal de NETPLUS para que realice todas las instalaciones que sean necesarias para el buen funcionamiento de
        los Servicios Contratados. La instalación del servicio o cambio de domicilio del cliente tendrán un costo
        adicional el cual la empresa determinará en base al trabajo realizado.
    </p>
    <p class="mt-sm">
        <b>DÉCIMO PRIMERO: FALLAS DEL SERVICIO.</b> NETPLUS no asume responsabilidad alguna por interrupciones
        justificadas en el Servicio, por causas relacionadas a condiciones climáticas, equinoccio solar,
        mantenimientos programados en la red, causas fuera de su control o por causas de fuerza mayor o caso fortuito.
    </p>
    <p class="mt-sm">
        <b>DÉCIMO SEGUNDO: SERVICIO DE ATENCIÓN DE AVERIAS.</b> NETPLUS ofrece a sus clientes el servicio de atención y
        reparación de averías suscitadas en la utilización del servicio, el cual estará disponible en los horarios que
        NETPLUS asigne para tal fin. Para acceder a este servicio, El CLIENTE llamará al teléfono que NETPLUS designe y
        su atención será gratuita o en su defecto podrán ser reportadas en las agencias de servicio. Si se comprueba que
        la avería corresponde a situaciones relacionadas al suministro de electricidad o ajenas a NETPLUS, EL CLIENTE
        deberá contactar terceros que puedan resolver su situación, sin que NETPLUS deba asumir alguna responsabilidad o
        costos por esta situación. La resolución de las averías reportadas por EL CLIENTE a través de los canales
        autorizados por NETPLUS para tal efecto, la resolución de averías no excederá de 3 días hábiles contados a
        partir de la hora de reporte de la avería. En caso de que la avería sea ocasionada por caso fortuito o fuerza
        mayor, NETPLUS hará su mejor esfuerzo para resolver el problema del cliente en el menor tiempo posible. En caso
        de presentarse averías en el servicio de internet, EL CLIENTE recibirá como compensación una cantidad
        determinada de horas mensuales de ancho de banda incremental al contratado y libres de costo, las cuales
        dependerán del plan contratado por EL CLIENTE y el tiempo de indisponibilidad del servicio por averías
        imputables a NETPLUS.
    </p>
    <p class="mt-sm">
        <b>DÉCIMO TERCERO: AUTORIZACIÓN DE SESIÓN.</b> NETPLUS podrá en cualquier momento, y sin necesidad de
        autorización de EL CLIENTE ceder en todo o en parte los derechos y obligaciones que deriven del presente
        Contrato.
    </p>
    <p class="mt-sm">
        <b>DÉCIMO CUARTO: INTERRUPCIÓN DEL SERVICIO.</b> a) NETPLUS Podrá efectuar interrupción en el Servicio con la
        finalidad de efectuar tareas de mantenimiento, siempre y cuando ponga al tanto de dichas situaciones a EL
        CLIENTE con antelación. b) En el caso de haber falta de servicio por pérdida de señal, NETPLUS dará soporte en
        un periodo de 72 horas hábiles, estas 72 horas no exoneran al cliente de ningún pago a NETPLUS. c) El cliente
        podrá comunicarse con Soporte Técnico a través del número +503 7626 6022
    </p>
    <p class="mt-sm">
        <b>DÉCIMO QUINTO: NOTIFICACIONES.</b> Para cualquier información que EL CLIENTE requiera de NETPLUS, podrá hacer
        uso de los siguientes medios: a) Centro De Atención Telefónica. b) Agencias de Servicio al Cliente.
    </p>
    <p class="mt-sm">
        <b>DÉCIMO SEXTO: JURISDICCIÓN, COMPETENCIA Y SOLUCIÓN DE CONTROVERSIAS.</b> Este contrato se rige de conformidad
        con las Leyes de EL SALVADOR. EL CLIENTE acepta que cualquier controversia, discrepancia, litigio, disputa,
        reclamo o diferencia que surja entre las partes contratantes como consecuencia de este contrato, se resolverá
        por la vía directa y amigable y de buena fe en el término de 30 días hábiles. Fracasada esta vía, y transcurrido
        dicho plazo de 30 días, y si no existiera acuerdo, EL CLIENTE desde ya renuncia al fuero de su domicilio y se
        somete a los Juzgados de SAN MIGUEL, EL SALVADOR. Los gastos del juicio, así como los costos de honorarios
        legales en los que incurra la parte demandante, serán absorbidos por la parte vencida. Esta cláusula
        compromisoria subsistirá no obstante se impugne de nulidad, anulabilidad o invalidez, parcial o totalmente, del
        presente contrato.
    </p>

    <p class="mt-sm">
        El cliente acepta que forma parte integral del presente contrato cualquier documento que NETPLUS establezca a
        posterior. En fe de todo lo anteriormente expresado, estando de acuerdo de todas y cada una de las cláusulas de
        este contrato de adhesión, firma.
    </p>

    <div class="signature-section">
        <div class="client-data">
            <p class="text-subtitle" style="margin-bottom: 10px;">DATOS DEL CLIENTE</p>

            <table class="client-data-table">
                <tr>
                    <td class="label">Nombre completo:</td>
                    <td>{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td class="label">Número de documento ({{ $data['document_type'] }}):</td>
                    <td>{{ $data['document_number'] }}</td>
                </tr>
                <tr>
                    <td class="label">Teléfono:</td>
                    <td>{{ $data['phone'] }}</td>
                </tr>
                <tr>
                    <td class="label">Dirección:</td>
                    <td>
                        {{ $data['address'] }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Plan contratado:</td>
                    <td>
                        {{ $data['plan'] }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Cuota mensual:</td>
                    <td>
                        ${{ $data['price'] }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Costo de instalación:</td>
                    <td>
                        ${{ $data['installation_price'] }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="date-place">
            <p><b>Lugar y Fecha: </b>
                {{ $data['office_address'] }}, {{ $data['contract_date'] }}
            </p>
        </div>

        <div class="signature-table">
            <div style="width: 50%; margin: 0 auto; text-align: center">
                <div class="signature-box">
                    <div
                            class="signature-name">{{ $data['name'] ?? 'NOMBRE DEL CLIENTE' }}</div>
                    <div class="signature-title">FIRMA DEL CLIENTE</div>
                    <div class="signature-title">
                        {{$data['document_type']}}: {{ $data['document_number'] ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
