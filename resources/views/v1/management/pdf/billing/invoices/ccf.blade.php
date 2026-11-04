<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    @include('v1.management.pdf.styles.invoices')
</head>
<body>
<div class="container">
    <!--    Header      -->
    <div class="header">
        <div class="header-content">
            <div class="company-info">
                <div class="company-name"> NETPLUS S.A. DE C.V.</div>
                <div class="company-details">
                    {{ $data['branch_name'] }}<br>
                    {{ $data['branch_address'] }}, {{ $data['branch_state'] }}, {{ $data['branch_district'] }} <br>
                    <b>NRC: </b>303483-9 <br>
                    <b>Tel: </b>{{ $data['branch_phone'] }} <br>
                    <b>Email: </b> facturacion.netplus@gmail.com
                </div>
            </div>

            <div class="invoice-info">
                <div class="invoice-title">Comprobante de crédito fiscal</div>
                <div class="invoice-details">
                    <strong>Fecha emisión: </strong>{{ $data['invoice_issued'] }}<br>
                    <strong>Vencimiento: </strong>{{ $data['invoice_overdue'] }}<br>
                    <strong>Mes a cancelar: </strong>{{ $data['invoice_period'] }}<br>
                    @switch($data['invoice_status'])
                        @case(1)
                            <div class="status-badge status-issued">EMITIDA</div>
                            @break
                        @case(2)
                            <div class="status-badge status-pending">PENDIENTE</div>
                            @break
                        @case(3)
                            <div class="status-badge status-paid">PAGADA</div>
                            @break
                        @case(4)
                            <div class="status-badge status-overdue">VENCIDA</div>
                            @break
                        @case(5)
                            <div class="status-badge status-canceled">ANULADA</div>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>
    <!--    Fin Header      -->

    <!--    Información de facturación      -->
    <div class="billing-section">
        <div class="billing-to">
            <div class="section-title">
                Datos del cliente
            </div>
            <strong>Cliente: {{ $data['client_name'] }} </strong> <br>
            <b>N.R.C: </b>{{ $data['client_nrc'] }}<br>
            <b>N.I.T: </b>{{ $data['client_nit'] }}<br>
            <b>Giro: </b>{{ $data['client_activity'] }}<br>
            <b>Dirección: </b> {{ $data['client_address'] }}
            {{ $data['client_state'] }},
            {{ $data['client_district'] }}. <br>
            <b>Tel: </b>{{ $data['client_mobile'] }}<br>
            <b>Email: </b>{{ $data['client_email'] }}<br>
        </div>
    </div>
    <!--    Fin Información de facturación      -->

    <!--    Items de factura        -->
    <table class="items-table">
        <thead>
        <tr>
            <th style="width: 8%;">#</th>
            <th style="width: 8%;">Cant.</th>
            <th style="width: 45%;">Descripción</th>
            <th style="width: 13%;" class="text-right">Precio Unit.</th>
            <th style="width: 13%;" class="text-right">Descuento</th>
            <th style="width: 13%;" class="text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['items'] as $item)
            <tr>
                <td class="text-center">{{ $item['index'] }}</td>
                <td class="text-center">{{ $item['quantity'] }}</td>
                <td><strong>{{ $item['description'] }}</strong></td>
                <td class="text-right">$ {{ $item['unit_price'] }}</td>
                <td class="text-right">$ {{ number_format($item['discount'], 2) }}</td>
                <td class="text-right">$ {{ $item['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!--    Fin Items de factura        -->

    <!--    Totales     -->
    <div class="totals-section">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div class="total-value">$ {{ $data['subtotal'] }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Descuentos:</div>
            <div class="total-value">- $ {{ number_format($data['discounts'], 2) }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">IVA (13%):</div>
            <div class="total-value">$ {{ $data['iva'] }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">IVA Retenido (1%):</div>
            <div class="total-value">- $ {{ $data['detained_iva'] }}</div>
        </div>
        <div class="total-row grand-total">
            <div class="total-label">TOTAL A PAGAR:</div>
            <div class="total-value">$ {{ $data['total'] }}</div>
        </div>
    </div>
    <!--    Fin Totales     -->

    <!--    Notas       -->
    <div style="margin-top: 20px; font-size: 9pt;">
        <strong>Notas:</strong><br>
        • El pago debe realizarse antes de la fecha de vencimiento para evitar suspensión del servicio.<br>
        {{--        • Mora del 2% mensual por pagos después de la fecha de vencimiento.<br>--}}
        {{--        • Esta factura es un documento fiscal válido para efectos tributarios.<br>--}}
        • Para soporte técnico comuníquese al (503) 7626-6022, atención 24/7
    </div>
    <!--    Fin Notas       -->

    <!--    Footer      -->
    <!-- Footer -->
    <div class="footer">
        {{--        Este documento fue generado electrónicamente y es válido sin sello ni firma.<br>--}}
        NETPLUS S.A. DE C.V. - Tel. (503) 7626-6022
    </div>
    <!--    Fin Footer      -->
</div>
</body>
</html>
