<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de livraison — Commande #{{ $order->id }}</title>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9;
        }

        .toolbar {
            max-width: 100mm;
            margin: 16px auto 0;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .toolbar button, .toolbar a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-print {
            background: linear-gradient(90deg, #6366f1, #7c3aed);
            color: #fff;
        }

        .btn-back {
            background: #fff;
            color: #334155;
            border: 1px solid #cbd5e1 !important;
        }

        .label {
            width: 100mm;
            height: 150mm;
            margin: 16px auto;
            background: #fff;
            padding: 6mm;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .label-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 3mm;
            margin-bottom: 4mm;
        }

        .brand {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #1e293b;
        }

        .ref {
            text-align: right;
            font-size: 12px;
            color: #475569;
        }

        .ref strong {
            display: block;
            font-size: 16px;
            color: #1e293b;
        }

        .section-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 1mm;
        }

        .dest {
            margin-bottom: 4mm;
        }

        .dest .name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .dest .line {
            font-size: 12.5px;
            color: #1e293b;
            margin-top: 0.5mm;
        }

        .products {
            flex: 1;
            border-top: 1px dashed #94a3b8;
            border-bottom: 1px dashed #94a3b8;
            padding: 3mm 0;
            margin-bottom: 3mm;
            font-size: 11.5px;
            color: #1e293b;
        }

        .products div {
            display: flex;
            justify-content: space-between;
            padding: 1mm 0;
        }

        .cod {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border: 2px solid #1e293b;
            border-radius: 8px;
            padding: 3mm 4mm;
            margin-bottom: 3mm;
        }

        .cod .label-text {
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
        }

        .cod .amount {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
        }

        .footer-note {
            font-size: 9.5px;
            color: #64748b;
            text-align: center;
        }

        @media print {
            body {
                background: #fff;
            }
            .toolbar {
                display: none;
            }
            .label {
                margin: 0;
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <a href="{{ route('orders.show', $order) }}" class="btn-back">← Retour à la commande</a>
        <button class="btn-print" onclick="window.print()">Imprimer</button>
    </div>

    <div class="label">
        <div class="label-header">
            <div class="brand">SOFT7ART ERP<br><span style="font-weight:400; font-size:10px; color:#64748b;">Bon de livraison</span></div>
            <div class="ref">
                Réf. commande
                <strong>#{{ $order->id }}</strong>
                {{ $order->created_at->format('d/m/Y') }}
            </div>
        </div>

        <div class="dest">
            <div class="section-title">Destinataire</div>
            <div class="name">{{ $order->customer->name }}</div>
            @if($order->customer->phone)
                <div class="line">Tél : {{ $order->customer->phone }}</div>
            @endif
            <div class="line">{{ $order->customer->address ?? 'Adresse non renseignée' }}</div>
            <div class="line"><strong>{{ $order->customer->city ?? '' }}</strong></div>
        </div>

        <div class="products">
            <div class="section-title" style="margin-bottom:2mm;">Contenu du colis</div>
            @foreach($order->products as $product)
                <div>
                    <span>{{ $product->name }} x{{ $product->pivot->quantity }}</span>
                    <span>{{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2) }} DH</span>
                </div>
            @endforeach
        </div>

        <div class="cod">
            <span class="label-text">Montant à percevoir</span>
            <span class="amount">{{ number_format($order->total_amount, 2) }} DH</span>
        </div>

        <p class="footer-note">Agent : {{ $order->user->name }} — Merci de vérifier le colis avant paiement.</p>
    </div>

</body>
</html>
