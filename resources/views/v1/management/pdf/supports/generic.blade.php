<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Document</title>
    @include('v1.management.pdf.styles.fonts')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Calibri', sans-serif;
            font-size: 11px;
            line-height: 1.15em;
            color: #333;
            /*background: #fff;*/
            margin: 0.5cm 0.5cm;
            text-align: justify;
        }

        .header {
            width: 100%;
            margin-bottom: 6px;
        }

        .logo {
            width: 95px;
            vertical-align: middle;
            text-align: left;
        }

        .header-table {
            width: 100%;
            vertical-align: middle;
            font-family: 'Bodoni', sans-serif;
            font-size: 14px;
            padding-bottom: 3px;
            border-bottom: #0a0a0a 1px solid;
        }

        .content {
            width: 100%;
            font-family: 'Calibri', sans-serif;
            font-size: 11px;
        }

        .row {
            width: 100%;
            margin-bottom: 3px;
            display: block;
            font-size: 0;
            white-space: nowrap;
        }

        .col-full {
            width: 98.5%;
            display: block;
        }

        .col-half {
            width: 48.5%;
            display: inline-block;
            vertical-align: top;
            font-size: 11px;
            margin-right: 1.5%;
        }

        .col-third {
            width: 32%;
            display: inline-block;
            vertical-align: top;
            font-size: 11px;
            margin-right: 1%;
        }

        .col-fourth {
            width: 23.8%;
            display: inline-block;
            vertical-align: top;
            font-size: 11px;
            margin-right: 1%;
        }

        .field {
            margin-bottom: 4px;
        }

        .field label {
            font-family: 'Bodoni', sans-serif;
            font-weight: bold;
            font-size: 11px;
            color: #444;
            display: block;
            margin-bottom: 2px;
        }

        .field-content {
            border: 1px solid #999;
            /*padding: 2px 10px;*/
            padding: 2px;
            min-height: 10px;
            line-height: 1.0;
            background-color: #fafafa;
            /*border-radius: 20%;*/
            border-radius: 3px;
            font-size: 11px;
        }

        .field-content-large {
            border: 1px solid #999;
            /*padding: 2px 16px;*/
            padding: 2px;
            min-height: 60px;
            line-height: 1.0;
            background-color: #fafafa;
            /*border-radius: 20%;*/
            border-radius: 3px;
            font-size: 11px;
            display: block;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .signature-section {
            margin-top: 40px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
        }

        .signature-box {
            width: 100%;
            height: 50px;
            border: 1px solid #999;
            margin-top: 5px;
            position: relative;
            border-radius: 20%;
        }

        .signature-line {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            text-align: center;
            font-size: 9px;
            border-top: 1px solid #333;
            padding-top: 2px;
        }

        .footer {
            /*position: absolute;*/
            bottom: 5px;
            right: 8px;
            font-size: 8px;
            color: #666;
            font-weight: bold;
            font-family: Bodoni, sans-serif;
        }
    </style>
</head>
<body>
<div class="header">
    <table class="header-table">
        <tr>
            <th style="width: 25%; border-right: #0a0a0a 1px solid; text-align: left;">
                <img src="{{ public_path('assets/img/logos/logo_blk.png') }}" alt="NETPLUS LOGO" class="logo">
            </th>
            <th style="width: 75%;">
                {{ ucwords($data['type']) }}
            </th>
        </tr>
    </table>
</div>
<div class="content">
    <div class="row">
        <div class="col-third">
            <div class="field">
                <label># Ticket</label>
                <div class="field-content">
                    {{ ucwords($data['ticket']) }}
                </div>
            </div>
        </div>

        <div class="col-third">
            <div class="field">
                <label>Cliente</label>
                <div class="field-content">
                    {{ ucwords($data['client']) }}
                </div>
            </div>
        </div>

        <div class="col-third">
            <div class="field">
                <label>Teléfono</label>
                <div class="field-content">
                    {{ ucwords($data['mobile']) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-fourth">
            <div class="field">
                <label>Nodo</label>
                <div class="field-content">
                    {{ ucwords($data['node']) }}
                </div>
            </div>
        </div>

        <div class="col-fourth">
            <div class="field">
                <label>Equipo</label>
                <div class="field-content">
                    {{ ucwords($data['equipment']) }}
                </div>
            </div>
        </div>

        <div class="col-fourth">
            <div class="field">
                <label>Latitud</label>
                <div class="field-content">
                    {{ ucwords($data['latitude']) }}
                </div>
            </div>
        </div>

        <div class="col-fourth">
            <div class="field">
                <label>Longitud</label>
                <div class="field-content">
                    {{ ucwords($data['longitude']) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-third">
            <div class="field">
                <label>Técnico Asignado</label>
                <div class="field-content">
                    {{ $data['technician'] }}
                </div>
            </div>
        </div>

        <div class="col-third">
            <div class="field">
                <label>Departamento</label>
                <div class="field-content">
                    {{ $data['state'] }}
                </div>
            </div>
        </div>

        <div class="col-third">
            <div class="field">
                <label>Distrito</label>
                <div class="field-content">
                    {{ $data['district'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-half">
            <div class="field">
                <label>Dirección</label>
                <div class="field-content-large">
                    {{ $data['address'] }}
                </div>
            </div>
        </div>

        <div class="col-half">
            <div class="field">
                <label>Descripción</label>
                <div class="field-content-large">
                    {{ $data['description'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-full">
            <div class="field">
                <label>Solución</label>
                <div class="field-content-large">
                    {{ $data['solution'] ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-half">
            <div class="field">
                <label>Firma de {{ $data['client'] }} o delegado</label>
                <div class="field-content-large">
                </div>
            </div>
        </div>

        <div class="col-half">
            <div class="field">
                <label>Firma de Técnico ({{ $data['technician'] }})</label>
                <div class="field-content-large">
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        NETPLUS - Soporte Técnico | Ticket: {{ $data['ticket'] }} | Contacto: +503 7626 6022 |
        netpluscompanywork@gmail.com | Atención 24/7 | Confidencial: No redistribuir.
        &copy; {{ $data['year'] }}
    </div>
</div>
</body>
</html>
