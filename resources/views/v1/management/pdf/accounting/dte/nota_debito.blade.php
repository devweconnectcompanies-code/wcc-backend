<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Document</title>
    @include('v1.management.pdf.styles.dte-style')
</head>
<body>
<p class="title-h1">Documento Tributario Electrónico</p>
<p class="title-h1 mt-xs">Nota de Débito</p>

@if($invalidated)
    <div class=" color-red">
        INVALIDADO
    </div>
@endif

<!--        Encabezado: Logo | Datos | QR       -->
<table class="table-full mt-xs">
    <tbody>
    <tr>
        <td class="header-logo">
            <img src="{{ public_path('assets/img/logos/color.png') }}" alt="LOGO GOES HERE" style="width: 95%"/>
        </td>

        <td class="header-data">
            <p>
                <span class="font-bold">Número de control:</span> {{ $data['identificacion']['numeroControl'] }}
            </p>
            <p>
                <span class="font-bold">Código de generación: </span>{{ $data['identificacion']['codigoGeneracion'] }}
            </p>
            <p>
                <span class="font-bold">Sello de recepción: </span>{{ $receptionStamp }}
            </p>
            <p>
                <span class="font-bold">Fecha y hora de emisión: </span>
                {{ $data['identificacion']['fecEmi'] }} {{ $data['identificacion']['horEmi'] }}
            </p>
            <p><span class="font-bold">Modelo de facturación: </span>Previo</p>
            <p><span class="font-bold">Tipo de transmisión: </span>Normal</p>
        </td>

        <td class="header-qr">
            <img src="data:iamge/png;base64, {{ $qrCode }}" alt="QR GOES HERE">
        </td>
    </tr>
    </tbody>
</table>
<!--        Fin Encabezado      -->

<!--        Emisor/Receptor     -->
<table class="table-full mt-md section-container">
    <tbody>
    <tr>
        <td class="box-cell">
            <div class="box-content">
                <p class="font-bold center-text">Emisor</p>
                <div class="text-small">
                    <p><span class="font-bold">Nombre o razón social: </span>{{ $data['emisor']['nombre'] }}</p>
                    <p><span class="font-bold">NIT: </span> {{ $data['emisor']['nit'] }}</p>
                    <p><span class="font-bold">NRC: </span>{{ $data['emisor']['nrc'] }}</p>
                    <p><span class="font-bold">Actividad económica: </span>Servicio de internet N.C.P.</p>
                    <p>
                        <span class="font-bold">Dirección: </span>
                        Calle principal, Colonia San Francisco, #34, San Miguel, San Miguel Centro, San Miguel
                    </p>
                    <p><span class="font-bold">Número de teléfono: </span>{{ $data['emisor']['telefono'] }}</p>
                    <p><span class="font-bold">Correo electrónico: </span>{{ $data['emisor']['correo'] }}</p>
                </div>
            </div>
        </td>

        <td style="width: 2%;"></td>

        <td class="box-cell">
            <div class="box-content">
                <p class="font-bold center-text">Receptor</p>
                <div class="text-small">
                    <p><span class="font-bold">Nombre o razón social: </span>{{ $clientData['name'] }}</p>
                    <p><span class="font-bold">NIT: </span>{{ $clientData['nit'] }}</p>
                    <p><span class="font-bold">NRC: </span>{{ $clientData['nrc'] }}</p>
                    <p><span class="font-bold">Actividad: </span>{{ $clientData['giro'] }}</p>
                    <p><span class="font-bold">Dirección: </span>{{ $clientData['address'] }}</p>
                    <p><span class="font-bold">Número de teléfono: </span>{{ $clientData['phone'] }}</p>
                    <p><span class="font-bold">Correo electrónico: </span>{{ $clientData['email'] }}</p>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<!--        Fin Emisor/Receptor     -->

<!--        Documento Relacionado       -->
<table class="mt-md section-container body-table">
    <thead>
    <tr class="related-docs-header">
        <th colspan="3" class="borderless">Documento relacionado</th>
    </tr>
    <tr class="header-row">
        <th class="col-dtype">Tipo de documento</th>
        <th class="col-control">N° del documento</th>
        <th class="col-date">Fecha del documento</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data['documentoRelacionado'] as $item)
        <tr class="data-row">
            <td>{{ $item['tipoDocumento'] }}</td>
            <td>{{ $item['numeroDocumento'] }}</td>
            <td>{{ $item['fechaEmision'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<!--        Fin Documento Relacionado       -->

<!--        Contenido del documento     -->
<table class="mt-md section-container body-table">
    <thead>
    <tr class="header-row">
        <th class="col-n">N°</th>
        <th class="col-cant">Cant.</th>
        <th class="col-desc">Descripción</th>
        <th class="col-precio">Precio Unitario</th>
        <th class="col-descto">Descuento por Ítem</th>
        <th class="col-nosuj">Ventas no Sujetas</th>
        <th class="col-exentas">Ventas Exentas</th>
        <th class="col-gravadas">Ventas Gravadas</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data['cuerpoDocumento'] as $item)
        <tr class="data-row">
            <td>{{ $item['numItem'] }}</td>
            <td>{{ $item['cantidad'] }}</td>
            <td>{{ $item['descripcion'] }}</td>
            <td>$ {{ number_format($item['precioUni'], 2) }}</td>
            <td>$ {{ number_format($item['montoDescu'], 2) }}</td>
            <td>$ {{ number_format($item['ventaNoSuj'], 2) }}</td>
            <td>$ {{ number_format($item['ventaExenta'], 2) }}</td>
            <td>$ {{ number_format($item['ventaGravada'], 2) }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="2" class="summary-label"><b>Suma de ventas:</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['totalNoSuj'], 2) }}</td>
        <td class="summary-value">$ {{ number_format($data['resumen']['totalExenta'], 2) }}</td>
        <td class="summary-value">$ {{ number_format($data['resumen']['totalGravada'], 2) }}</td>
    </tr>

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="4" class="summary-label"><b>Suma total de operaciones:</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['totalGravada'], 2) }}</td>
    </tr>

    {{--    <tr>--}}
    {{--        <td colspan="3" class="borderless"></td>--}}
    {{--        <td colspan="4" class="summary-label"><b>Monto global descuentos, rebajas y otros a ventas no sujetas:</b></td>--}}
    {{--        <td class="summary-value">$ {{ number_format($data['resumen']['descuNoSuj'], 2) }}</td>--}}
    {{--    </tr>--}}

    {{--    <tr>--}}
    {{--        <td colspan="3" class="borderless"></td>--}}
    {{--        <td colspan="4" class="summary-label"><b>Monto global descuentos, rebajas y otros a ventas exentas:</b></td>--}}
    {{--        <td class="summary-value">$ {{ number_format($data['resumen']['descuExenta'], 2) }}</td>--}}
    {{--    </tr>--}}

    {{--    <tr>--}}
    {{--        <td colspan="3" class="borderless"></td>--}}
    {{--        <td colspan="4" class="summary-label"><b>Monto global descuentos, rebajas y otros a ventas gravadas:</b></td>--}}
    {{--        <td class="summary-value">$ {{ number_format($data['resumen']['descuGravada'], 2) }}</td>--}}
    {{--    </tr>--}}

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="4" class="summary-label"><b>Impuesto al Valor Agregado (13%):</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['tributos'][0]['valor'], 2) }}</td>
    </tr>

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="4" class="summary-label"><b>Subtotal:</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['subTotalVentas'], 2) }}</td>
    </tr>

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="4" class="summary-label"><b>IVA percibido:</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['ivaPerci'], 2) }}</td>
    </tr>

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="4" class="summary-label"><b>IVA retenido:</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['ivaRete'], 2) }}</td>
    </tr>

    <tr>
        <td colspan="3" class="borderless"></td>
        <td colspan="4" class="summary-label"><b>Monto total de la operación:</b></td>
        <td class="summary-value">$ {{ number_format($data['resumen']['montoTotalOperacion'], 2) }}</td>
    </tr>
    </tbody>
</table>
<!--        Fin Contenido del documento     -->

<div style="margin-top: 10px">
    <div>
        <p>
            <span><b>Valor en letras:</b></span> {{ $data['resumen']['totalLetras'] }}
        </p>
        <p>
            <span><b>Condición de operación:</b></span> {{ $condition }}
        </p>
        <p>
            <span><b>Observaciones:</b></span>
        </p>
    </div>
</div>
</body>
</html>
