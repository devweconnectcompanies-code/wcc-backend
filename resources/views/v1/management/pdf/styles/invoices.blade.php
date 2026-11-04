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
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 10pt;
        color: #333;
        line-height: 1.0;
        margin: 1.0cm 1.5cm;
    }

    .container {
        width: 100%;
        padding: 20px;
    }

    .header {
        margin-bottom: 30px;
        border-bottom: 3px solid #2c3e50;
        padding-bottom: 15px;
    }

    .header-content {
        display: table;
        width: 100%;
    }

    .company-info {
        display: table-cell;
        width: 60%;
        vertical-align: top;
    }

    .invoice-info {
        display: table-cell;
        width: 40%;
        vertical-align: top;
        text-align: right;
    }

    .company-name {
        font-size: 15pt;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .company-details {
        font-size: 9pt;
        color: #666;
        line-height: 1.1;
    }

    .invoice-title {
        font-size: 15pt;
        font-weight: bold;
        color: #e74c3c;
        margin-bottom: 10px;
    }

    .invoice-details {
        font-size: 9pt;
    }

    .invoice-details strong {
        color: #2c3e50;
    }

    .billing-section {
        display: table;
        width: 100%;
        margin-bottom: 30px;
    }

    .billing-to, .billing-details {
        display: table-cell;
        width: 50%;
        padding: 15px;
        background-color: #f8f9fa;
        vertical-align: top;
    }

    .billing-to {
        margin-right: 10px;
    }

    .section-title {
        font-size: 10pt;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
        text-transform: uppercase;
        border-bottom: 2px solid #e74c3c;
        padding-bottom: 5px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    .items-table thead {
        background-color: #2c3e50;
        color: white;
    }

    .items-table th {
        padding: 7px;
        text-align: left;
        font-weight: bold;
    }

    .items-table td {
        padding: 7px;
        border-bottom: 1px solid #ddd;
    }

    .items-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .totals-section {
        width: 40%;
        margin-left: auto;
        margin-top: 20px;
    }

    .total-row {
        display: table;
        width: 100%;
        padding: 8px 0;
        border-bottom: 1px solid #ddd;
    }

    .total-label {
        display: table-cell;
        text-align: left;
        font-weight: bold;
    }

    .total-value {
        display: table-cell;
        text-align: right;
    }

    .grand-total {
        background-color: #2c3e50;
        color: white;
        padding: 12px;
        margin-top: 5px;
        font-size: 12pt;
    }

    .payment-info {
        margin-top: 30px;
        padding: 15px;
        background-color: #ecf0f1;
        border-left: 4px solid #e74c3c;
    }

    .payment-info-title {
        font-weight: bold;
        font-size: 11pt;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 8pt;
        color: #7f8c8d;
        border-top: 1px solid #ddd;
        padding: 10px 20px;
        background-color: white;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 3px;
        font-weight: bold;
        font-size: 9pt;
        margin-top: 5px;
    }

    .status-paid {
        background-color: #27ae60;
        color: white;
    }

    .status-pending {
        background-color: #f39c12;
        color: white;
    }

    .status-overdue {
        background-color: #e74c3c;
        color: white;
    }

    .status-canceled {
        color: white;
        background-color: #4a4e69;
    }
</style>
