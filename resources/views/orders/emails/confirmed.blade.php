<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin: 12px 0;
            font-size: 14px;
        }
        .summary {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .summary p {
            margin: 8px 0;
            font-size: 13px;
        }
        .summary strong {
            color: #2563eb;
        }
        .invoice-note {
            background-color: #e0f2fe;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
            font-size: 13px;
        }
        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Merci pour votre commande</h1>
            <p>ECOM-ERP - E-commerce & ERP Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Bonjour <strong>{{ $order->customer->name }}</strong>,</p>

            <p>Nous vous confirmons la reception de votre commande. Celle-ci a ete validee avec succes dans notre systeme.</p>

            <!-- Summary -->
            <div class="summary">
                <p><strong>Numero de commande :</strong> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Montant total :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} DH</p>
                <p><strong>Statut :</strong> Confirmee</p>
            </div>

            <!-- Invoice Attachment Note -->
            <div class="invoice-note">
                <p>Votre facture detaillee est disponible en piece jointe. Vous pouvez la telecharger a tout moment depuis votre compte client.</p>
            </div>

            <p>Nous vous remercions de votre confiance et nous nous engageons à traiter votre commande avec la plus grande attention.</p>

            <p>Si vous avez des questions ou besoin d'assistance, n'hesitez pas à nous contacter.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Cet e-mail a ete genere automatiquement par ECOM-ERP. Veuillez ne pas repondre directement à ce message.</p>
            <p>Pour toute question, contactez-nous à contact@ecom-erp.fr</p>
        </div>
    </div>
</body>
</html>
