<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Commande #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }

        .company-info h1 {
            font-size: 28px;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .company-info p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 24px;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .invoice-date {
            font-size: 12px;
            color: #666;
        }

        .content {
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
        }

        .section {
            flex: 1;
        }

        .section h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #2563eb;
            margin-bottom: 8px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .section p {
            font-size: 12px;
            margin-bottom: 3px;
            line-height: 1.5;
        }

        .section p strong {
            font-weight: bold;
            display: block;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background-color: #f3f4f6;
            border-top: 2px solid #2563eb;
            border-bottom: 2px solid #2563eb;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            color: #2563eb;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        tbody tr:last-child td {
            border-bottom: 2px solid #2563eb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-left: auto;
            width: 350px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
            padding: 8px 0;
        }

        .summary-row.total {
            border-top: 2px solid #2563eb;
            border-bottom: 2px solid #2563eb;
            font-weight: bold;
            font-size: 16px;
            padding: 15px 0;
            color: #2563eb;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        .currency {
            font-size: 12px;
            color: #666;
        }

        .unbreakable {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>ECOM-ERP</h1>
                <p>E-commerce & ERP Management System</p>
                <p>contact@ecom-erp.fr</p>
                <p>+33 1 23 45 67 89</p>
            </div>

            <div class="invoice-title">
                <h2>FACTURE</h2>
                <div class="invoice-number">N° {{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="invoice-date">{{ now()->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Client & Date Information -->
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <div class="section">
                        <h3>Facturer à :</h3>
                        <p>
                            <strong>{{ $order->customer->name ?? 'Client' }}</strong>
                            {{ $order->customer->email ?? '' }}<br>
                            {{ $order->customer->phone ?? '' }}<br>
                            {{ $order->customer->address ?? '' }}<br>
                            {{ $order->customer->postal_code ?? '' }} {{ $order->customer->city ?? '' }}
                        </p>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                    <div class="section">
                        <h3>Informations de la commande :</h3>
                        <p>
                            <strong>Commande du :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}<br>
                            <strong>Agent :</strong> {{ $order->user->name ?? 'N/A' }}<br>
                            <strong>Statut :</strong> {{ ucfirst($order->status) }}<br>
                            <strong>Montant :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} DH
                        </p>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Products Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40%">Produit</th>
                    <th style="width: 15%" class="text-center">Quantité</th>
                    <th style="width: 20%" class="text-right">Prix Unitaire</th>
                    <th style="width: 25%" class="text-right">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                    @php
                        $quantity = $product->pivot->quantity;
                        $unitPrice = $product->pivot->unit_price;
                        $subtotal = $quantity * $unitPrice;
                    @endphp
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">{{ $quantity }}</td>
                        <td class="text-right">{{ number_format($unitPrice, 2, ',', ' ') }} DH</td>
                        <td class="text-right"><strong>{{ number_format($subtotal, 2, ',', ' ') }} DH</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="page-break-inside: avoid;">
                    <td colspan="3" class="text-right" style="padding-top: 20px;">Sous-total :</td>
                    <td class="text-right" style="padding-top: 20px;">{{ number_format($order->total_amount, 2, ',', ' ') }} DH</td>
                </tr>
                <tr style="page-break-inside: avoid;">
                    <td colspan="3" class="text-right">Frais de livraison :</td>
                    <td class="text-right">0,00 DH</td>
                </tr>
                <tr style="page-break-inside: avoid;">
                    <td colspan="3" class="text-right">Taxe (TVA) :</td>
                    <td class="text-right">Incluse</td>
                </tr>
                <tr style="border-top: 2px solid #2563eb; border-bottom: 2px solid #2563eb; page-break-inside: avoid;">
                    <td colspan="3" class="text-right" style="font-weight: bold; font-size: 16px; color: #2563eb; padding: 15px 0;">MONTANT TOTAL TTC :</td>
                    <td class="text-right" style="font-weight: bold; font-size: 16px; color: #2563eb; padding: 15px 0;">{{ number_format($order->total_amount, 2, ',', ' ') }} DH</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Merci de votre achat ! / Thank you for your purchase!</p>
            <p>Cette facture a été générée automatiquement par ECOM-ERP le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
