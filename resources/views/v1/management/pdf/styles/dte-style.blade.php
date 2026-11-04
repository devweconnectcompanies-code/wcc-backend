<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @page {
        margin: 100px 50px 80px 50px;
    }

    body {
        font-family: Calibri, sans-serif;
        font-size: 11px;
        line-height: 1.15em;
        color: #333333;
        margin: 1.0cm 1.5cm;
        text-align: justify;
        position: relative;
        z-index: 1;
    }

    .table-full {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        position: relative;
        z-index: 1;
    }

    .title-h1 {
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
    }

    .header-logo {
        width: 20%;
        text-align: center;
        vertical-align: middle;
    }

    .header-data {
        width: 60%;
        vertical-align: middle;
        padding: 0 1.5em;
    }

    .header-qr {
        width: 20%;
        text-align: center;
        vertical-align: middle;
    }

    .section-cell {
        width: 50%;
        vertical-align: top;
        padding: 5px;
    }

    .section-cell:first-child {
        padding-left: 0;
    }

    .section-cell:last-child {
        padding-right: 0;
    }

    .section-container {
        border-collapse: separate;
        border-spacing: 0;
        position: relative;
        z-index: 1;
    }

    .box-cell {
        width: 49%;
        border: 1px solid #1a202c;
        border-radius: 10px;
        vertical-align: middle;
        padding: 10px;
    }

    .box-content {
        width: 100%;
    }

    .box {
        border: 1px solid #1a202c;
        border-radius: 10px;
        padding: 5px;
        width: 100%;
    }

    .box-inner {
        width: 100%;
        height: 100%;
        border-collapse: collapse;
    }

    .box-inner td {
        vertical-align: top;
        padding: 0;
    }

    .mt-xs {
        margin-top: 5px;
    }

    .mt-sm {
        margin-top: 10px;
    }

    .mt-md {
        margin-top: 15px;
    }

    .font-bold {
        font-weight: bold;
    }

    .center-text {
        text-align: center;
    }

    .text-small {
        font-size: 10px;
        line-height: 1em;
    }


    /*
    * Formato de tabla cuerpo documento
    */
    .body-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        border: 1px solid #999999;
        margin-top: 12px;
        position: relative;
        z-index: 1;
    }

    .body-table th, .body-table td {
        border: 1px solid #999999;
        padding: 4px 5px;
        vertical-align: middle;
        text-align: center;
        word-wrap: break-word;
        margin-top: 15px;
    }

    .header-row th {
        background-color: #d9d9d9;
        font-weight: bold;
        font-size: 7pt;
        text-align: center;
        vertical-align: middle;
    }

    .data-row td {
        height: 18px;
        font-size: 7pt;
    }

    .summary-label {
        text-align: right !important;
        font-size: 8pt;
        font-weight: normal;
        border: 1px solid #999999;
        padding: 3px 5px;
    }

    .summary-label-bold {
        text-align: right;
        font-size: 8pt;
        font-weight: bold;
        border: 1px solid #999999;
        padding: 3px 5px;
    }

    .summary-value {
        border: 1px solid #999999;
        width: 80px;
        height: 16px;
    }

    .tribute-header {
        background-color: #d9d9d9;
        font-weight: bold;
        font-size: 8pt;
        text-align: right;
        border: 1px solid #999999;
        padding: 3px 5px;
    }

    .tribute-header-val {
        background-color: #d9d9d9;
        font-weight: bold;
        font-size: 8pt;
        text-align: center;
        border: 1px solid #999999;
        padding: 3px 5px;
    }

    .col-n {
        width: 4%;
    }

    .col-cant {
        width: 7%;
    }

    .col-unidad {
        width: 7%;
    }

    .col-desc {
        width: 30%;
    }

    .col-precio {
        width: 9%;
    }

    .col-otros {
        /*width: 10%;*/
        width: 9%;
    }

    .col-descto {
        width: 9%;
    }

    .col-nosuj {
        width: 9%;
    }

    .col-exentas {
        width: 9%;
    }

    .col-gravadas {
        width: 10%;
    }

    .borderless {
        border: none !important;
    }

    .related-docs-header {
        border-top: #ffffff 1px solid;
        border-left: #ffffff 1px solid;
        border-right: #ffffff 1px solid;
    }

    .col-dtype {
        width: 20%;
    }

    .col-control {
        width: 60%;
    }

    .col-date {
        width: 20%;
    }

    .watermark {
        position: fixed;
        top: 38%;
        left: -10%;
        width: 120%;
        text-align: center;
        font-size: 110px;
        font-weight: bold;
        transform: rotate(-30deg);
        z-index: 2;
    }

    .color-red {
        color: rgba(231, 76, 60, 0.1);
    }

    .color-blue {
        color: rgba(2, 48, 71, 0.1);
    }
</style>
