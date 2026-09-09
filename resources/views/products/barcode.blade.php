<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code-barres — {{ $product->name }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.12.3/JsBarcode.all.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9;
        }

        .toolbar {
            max-width: 320px;
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
            width: 320px;
            margin: 16px auto;
            background: #fff;
            padding: 5mm;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
        }

        .product-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2mm;
            word-break: break-word;
        }

        .shelf {
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            font-weight: 700;
            font-size: 13px;
            padding: 2mm 4mm;
            border-radius: 8px;
            margin-bottom: 3mm;
        }

        svg {
            max-width: 100%;
        }

        .price {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2mm;
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
        <a href="{{ route('products.index') }}" class="btn-back">← Retour au catalogue</a>
        <button class="btn-print" onclick="window.print()">Imprimer</button>
    </div>

    <div class="label">
        <div class="product-name">{{ $product->name }}</div>

        @if($product->shelf_location)
            <div class="shelf">{{ $product->shelf_location }}</div>
        @endif

        <svg id="barcode"></svg>

        <div class="price">{{ number_format($product->selling_price, 2) }} DH</div>
    </div>

    <script>
        if (typeof JsBarcode === 'undefined') {
            document.getElementById('barcode').outerHTML = '<p style="color:#dc2626; font-size:12px;">Erreur : la librairie de code-barres n\'a pas pu se charger (vérifiez la connexion internet).</p>';
        } else {
            JsBarcode("#barcode", "{{ $product->barcode_value }}", {
                format: "CODE128",
                lineColor: "#0f172a",
                width: 2,
                height: 60,
                displayValue: true,
                fontSize: 13,
                margin: 4
            });
        }
    </script>
</body>
</html>
