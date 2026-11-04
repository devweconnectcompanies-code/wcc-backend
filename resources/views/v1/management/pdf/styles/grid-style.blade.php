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
