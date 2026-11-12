<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        .header { background-color: #0f766e; color: white; padding: 20px 30px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.8; margin-top: 2px; }
        .header-right { float: right; text-align: right; }
        .content { padding: 0 30px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #0f766e; border-bottom: 2px solid #0f766e; padding-bottom: 4px; margin-bottom: 12px; }
        .grid-2 { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .grid-2 td { width: 50%; padding: 6px 10px; vertical-align: top; }
        .info-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; }
        .info-label { font-size: 10px; color: #6b7280; margin-bottom: 2px; }
        .info-value { font-size: 12px; font-weight: bold; color: #1f2937; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background-color: #f3f4f6; padding: 8px 10px; text-align: left; font-size: 11px; color: #6b7280; text-transform: uppercase; }
        table.items td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
        .total-box { float: right; width: 220px; }
        .total-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; }
        .total-final { border-top: 2px solid #1f2937; padding-top: 6px; font-weight: bold; font-size: 14px; color: #0f766e; }
        .footer { margin-top: 40px; padding: 15px 30px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
        .clearfix::after { content: ""; display: table; clear: both; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-green { background-color: #d1fae5; color: #065f46; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

<div class="header clearfix">
    <div class="header-right">
        <p style="font-size:16px; font-weight:bold;">{{ $invoice->invoice_number }}</p>
        <p>{{ $invoice->created_at->format('d/m/Y') }}</p>
        <p>
            <span class="badge {{ $invoice->status === 'pagada' ? 'badge-green' : 'badge-yellow' }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </p>
    </div>
    <h1>🐾 Hospital Animal Veterinario</h1>
    <p>Barrio El Caney, Cali, Valle del Cauca</p>
    <p>Lun-Sáb 8:00am - 8:00pm · Dom 8:00am - 2:00pm</p>
</div>

<div class="content">

    <div class="section">
        <div class="section-title">Datos del Cliente</div>
        <table class="grid-2">
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Propietario</div>
                        <div class="info-value">{{ $invoice->owner->name }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">{{ $invoice->owner->phone }}</div>
                    </div>
                </td>
            </tr>
            @if($invoice->appointment)
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Mascota</div>
                        <div class="info-value">{{ $invoice->appointment->pet->name }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Atendido por</div>
                        <div class="info-value">{{ $invoice->user->name }}</div>
                    </div>
                </td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Detalle de Servicios</div>
        <table class="items">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Cant.</th>
                    <th>Precio unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>${{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="clearfix">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="total-row total-final">
                    <span>TOTAL:</span>
                    <span>${{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
                @if($invoice->payment_method)
                <div class="total-row" style="margin-top:6px; font-size:11px; color:#6b7280;">
                    <span>Método de pago:</span>
                    <span>{{ ucfirst($invoice->payment_method) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<div class="footer">
    <p>Documento generado el {{ now()->format('d/m/Y h:i A') }} · Hospital Animal Veterinario · Cali, Colombia</p>
    <p>Gracias por confiar en nosotros para el cuidado de su mascota.</p>
</div>

</body>
</html>