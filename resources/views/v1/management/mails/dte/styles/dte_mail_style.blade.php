<style>
    /* ===== RESET ===== */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body, table, td, a {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }

    table, td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    img {
        -ms-interpolation-mode: bicubic;
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
    }

    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
    }

    /* ===== BASE ===== */
    body {
        background-color: #EEF2F7;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        width: 100% !important;
        min-width: 100%;
    }

    /* ===== WRAPPER ===== */
    .email-wrapper {
        background-color: #EEF2F7;
        padding: 40px 16px;
        width: 100%;
    }

    /* ===== CONTAINER ===== */
    .email-container {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 32px rgba(0, 0, 0, 0.10);
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
    }

    /* ===== HEADER ===== */
    .email-header {
        background: linear-gradient(135deg, #1A3A6B 0%, #0F5FA8 60%, #1A8FE3 100%);
        padding: 40px 40px 32px 40px;
        text-align: center;
        position: relative;
    }

    .header-logo-wrap {
        margin-bottom: 20px;
    }

    .header-icon {
        display: inline-block;
        /*background: rgba(255, 255, 255, 0.15);*/
        /*border-radius: 50%;*/
        padding: 16px;
        line-height: 0;
    }

    .header-icon img {
        width: 50%;
    }

    .header-icon svg {
        display: block;
    }

    .header-badge {
        display: inline-block;
        background: #00C48C;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 20px;
        padding: 4px 14px;
        margin-bottom: 14px;
    }

    .header-title {
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.3px;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.75);
        font-size: 14px;
        line-height: 1.5;
    }

    /* ===== BODY ===== */
    .email-body {
        padding: 40px 40px 32px 40px;
    }

    /* ===== GREETING ===== */
    .greeting {
        color: #1A3A6B;
        font-size: 17px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .greeting-text {
        color: #4A5568;
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    /* ===== INFO CARD ===== */
    .info-card {
        background: #F5F8FF;
        border: 1.5px solid #D0DFF7;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 28px;
    }

    .info-card-title {
        color: #0F5FA8;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card-title::before {
        content: '';
        display: inline-block;
        width: 3px;
        height: 14px;
        background: #0F5FA8;
        border-radius: 2px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #E2ECFA;
        gap: 12px;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        color: #718096;
        font-size: 13px;
        flex-shrink: 0;
        padding-right: 8px;
    }

    .info-value {
        color: #1A3A6B;
        font-size: 13px;
        font-weight: 600;
        text-align: right;
    }

    .info-value.highlight {
        color: #00C48C;
    }

    /* ===== NOTICE ===== */
    .notice-box {
        background: #FFFBEA;
        border-left: 4px solid #F6C344;
        border-radius: 0 8px 8px 0;
        padding: 14px 18px;
        margin-bottom: 28px;
    }

    .notice-box p {
        color: #7A5800;
        font-size: 13px;
        line-height: 1.6;
    }

    .notice-box strong {
        font-weight: 700;
    }

    /* ===== CTA BUTTON ===== */
    .cta-wrap {
        text-align: center;
        margin-bottom: 32px;
    }

    .cta-button {
        display: inline-block;
        background: linear-gradient(135deg, #0F5FA8 0%, #1A8FE3 100%);
        border-radius: 10px;
        color: #ffffff !important;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 16px 40px;
        text-decoration: none;
        transition: opacity 0.2s;
        box-shadow: 0 4px 16px rgba(15, 95, 168, 0.30);
    }

    .cta-button:hover {
        opacity: 0.88;
    }

    .cta-button svg {
        vertical-align: middle;
        margin-left: 8px;
        margin-top: -2px;
    }

    .cta-fallback {
        color: #718096;
        font-size: 12px;
        margin-top: 14px;
        line-height: 1.6;
    }

    .cta-fallback a {
        color: #0F5FA8;
        word-break: break-all;
    }

    /* ===== DIVIDER ===== */
    .divider {
        border: none;
        border-top: 1px solid #E8EEF7;
        margin: 0 0 28px 0;
    }

    /* ===== HELP ===== */
    .help-section {
        background: #F5F8FF;
        border-radius: 10px;
        padding: 20px 24px;
        margin-bottom: 0;
    }

    .help-section p {
        color: #4A5568;
        font-size: 13px;
        line-height: 1.7;
    }

    .help-section a {
        color: #0F5FA8;
        font-weight: 600;
        text-decoration: none;
    }

    /* ===== FOOTER ===== */
    .email-footer {
        background: #1A3A6B;
        padding: 28px 40px;
        text-align: center;
    }

    .footer-logo {
        color: rgba(255, 255, 255, 0.9);
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .footer-tagline {
        color: rgba(255, 255, 255, 0.45);
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .footer-links {
        margin-bottom: 16px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.55);
        font-size: 12px;
        text-decoration: none;
        margin: 0 10px;
    }

    .footer-links a:hover {
        color: #fff;
    }

    .footer-legal {
        color: rgba(255, 255, 255, 0.35);
        font-size: 11px;
        line-height: 1.6;
    }

    /* ===== RESPONSIVE ===== */
    @media only screen and (max-width: 600px) {
        .email-wrapper {
            padding: 16px 8px;
        }

        .email-header {
            padding: 28px 20px 24px 20px;
        }

        .header-title {
            font-size: 21px;
        }

        .email-body {
            padding: 28px 20px 24px 20px;
        }

        .email-footer {
            padding: 24px 20px;
        }

        .info-row {
            flex-direction: column;
            gap: 2px;
        }

        .info-value {
            text-align: left;
        }

        .cta-button {
            padding: 15px 28px;
            font-size: 14px;
            display: block;
        }
    }
</style>
