<!doctype html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Documento Tributario Electrónico Emitido</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    @include('v1.management.mails.dte.styles.dte_mail_style')
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        <!-- ===== HEADER ===== -->
        <div class="email-header">
            <div class="header-logo-wrap">
        <span class="header-icon">
            <img src="{{ $message->embed(public_path('assets/img/logos/logo_wth.png')) }}" alt="Netplus Logo">
        </span>
            </div>
            <h1 class="header-title">Documento Tributario Electrónico Generado</h1>
        </div>

        <!-- ===== BODY ===== -->
        <div class="email-body">

            <p class="greeting">{{ $clientName }},</p>
            <p class="greeting-text">
                Le informamos que hemos generado correctamente su <strong>Documento Tributario Electrónico
                    (DTE)</strong>. A continuación encontrará el resumen de la transacción registrada en nuestro
                sistema.
            </p>

            <!-- INFO CARD -->
            <div class="info-card">
                <div class="info-card-title">Detalle del Documento</div>

                <div class="info-row">
                    <span class="info-label">Tipo de Documento</span>
                    <span class="info-value">{{ $dteTypeName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Número de Control</span>
                    <span class="info-value">{{ $dte->control_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Código de Generación</span>
                    <span class="info-value">{{ $dte->generation_code }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sello de Recepción</span>
                    <span class="info-value">{{ $dte->reception_stamp }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de Emisión</span>
                    <span class="info-value">{{ $generatedAt }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Emisor</span>
                    <span class="info-value">NETPLUS COMPANY WORK, S.A. DE C.V.</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Monto Total</span>
                    <span class="info-value highlight">$ {{ $dte->total_amount }} USD</span>
                </div>
            </div>

            <!-- AVISO -->
            <div class="notice-box">
                <p>
                    <strong>⚠ Importante:</strong> Este correo es una notificación automática. Conserve este documento
                    para efectos de su declaración tributaria. El DTE tiene plena validez legal ante el Ministerio de
                    Hacienda.
                </p>
            </div>

            <!-- CTA BUTTON -->
            <div class="cta-wrap">
                <a href="{{ $uri }}"
                   class="cta-button"
                   target="_blank">
                    Consultar Documento
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="white" stroke-width="1.8" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </a>
                <p class="cta-fallback">
                    Si el botón no funciona, copie y pegue este enlace en su navegador:<br>
                    <a href="{{ $uri }}">
                        {{ $uri }}
                    </a>
                </p>
            </div>

            <hr class="divider">

            <!-- AYUDA -->
            <div class="help-section">
                <p>
                    ¿Tiene alguna duda sobre este documento? Contáctenos a
                    <a href="mailto:netplus.facturacion@gmail.com">netplus.facturacion@gmail.com</a>
                    o llámenos al <strong>(503) 7626 6022</strong> en horario de lunes a viernes de 8:00 a.m. a 5:00
                    p.m.
                </p>
            </div>

        </div>

        <!-- ===== FOOTER ===== -->
        <div class="email-footer">
            <div class="footer-logo">Netplus</div>
            <div class="footer-tagline">Facturación Electrónica &amp; Cumplimiento Tributario</div>
            <p class="footer-legal">
                Este mensaje fue generado automáticamente por el sistema de facturación electrónica.<br>
                Por favor, no responda directamente a este correo.<br>
                © 2026 Netplus. Todos los derechos reservados.
            </p>
        </div>

    </div>
</div>

</body>
</html>
