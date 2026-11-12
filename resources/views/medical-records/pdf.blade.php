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

        .text-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px; margin-bottom: 10px; }
        .text-label { font-size: 10px; color: #6b7280; margin-bottom: 4px; }
        .text-value { font-size: 12px; color: #1f2937; line-height: 1.5; }

        .diagnosis-box { border: 1px solid #d1fae5; background-color: #f0fdf4; border-radius: 6px; padding: 12px; margin-bottom: 10px; }

        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-purple { background-color: #ccfbf1; color: #0f766e; }

        .footer { margin-top: 30px; padding: 15px 30px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }

        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header clearfix">
    <div class="header-right">
        <p>Historia Clínica #{{ $medicalRecord->id }}</p>
        <p>{{ \Carbon\Carbon::parse($medicalRecord->visit_date)->format('d/m/Y') }}</p>
    </div>
    <h1>🐾 Hospital Animal Veterinario</h1>
    <p>Barrio El Caney, Cali, Valle del Cauca</p>
    <p>Lun-Sáb 8:00am - 8:00pm · Dom 8:00am - 2:00pm</p>
</div>

<div class="content">

    {{-- Datos de la mascota --}}
    <div class="section">
        <div class="section-title">Datos del Paciente</div>
        <table class="grid-2">
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Nombre de la mascota</div>
                        <div class="info-value">{{ $medicalRecord->pet->name }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Propietario</div>
                        <div class="info-value">{{ $medicalRecord->pet->owner->name }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Especie / Raza</div>
                        <div class="info-value">{{ ucfirst($medicalRecord->pet->species) }} — {{ $medicalRecord->pet->breed ?? 'Sin raza' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Teléfono propietario</div>
                        <div class="info-value">{{ $medicalRecord->pet->owner->phone }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Datos de la visita --}}
    <div class="section">
        <div class="section-title">Datos de la Visita</div>
        <table class="grid-2">
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Fecha de visita</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($medicalRecord->visit_date)->format('d/m/Y') }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Veterinario</div>
                        <div class="info-value">{{ $medicalRecord->veterinarian->name }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-box">
                        <div class="info-label">Peso</div>
                        <div class="info-value">{{ $medicalRecord->weight ? $medicalRecord->weight . ' kg' : '—' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-box">
                        <div class="info-label">Temperatura</div>
                        <div class="info-value">{{ $medicalRecord->temperature ? $medicalRecord->temperature . ' °C' : '—' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="text-box">
            <div class="text-label">Síntomas</div>
            <div class="text-value">{{ $medicalRecord->symptoms }}</div>
        </div>

        @if($medicalRecord->treatment)
        <div class="text-box">
            <div class="text-label">Tratamiento</div>
            <div class="text-value">{{ $medicalRecord->treatment }}</div>
        </div>
        @endif

        @if($medicalRecord->observations)
        <div class="text-box">
            <div class="text-label">Observaciones</div>
            <div class="text-value">{{ $medicalRecord->observations }}</div>
        </div>
        @endif
    </div>

    {{-- Diagnósticos --}}
    @if($medicalRecord->diagnoses->count() > 0)
    <div class="section">
        <div class="section-title">Diagnósticos</div>
        @foreach($medicalRecord->diagnoses as $diagnosis)
        <div class="diagnosis-box">
            <table class="grid-2">
                <tr>
                    <td>
                        <div class="info-label">Condición</div>
                        <div class="info-value">{{ $diagnosis->condition }}</div>
                    </td>
                    <td>
                        <div class="info-label">Medicamento</div>
                        <div class="info-value">{{ $diagnosis->medication ?? '—' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="info-label">Dosis</div>
                        <div class="info-value">{{ $diagnosis->dosage ?? '—' }}</div>
                    </td>
                    <td>
                        <div class="info-label">Próxima visita</div>
                        <div class="info-value">{{ $diagnosis->next_visit ? \Carbon\Carbon::parse($diagnosis->next_visit)->format('d/m/Y') : '—' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- Footer --}}
<div class="footer">
    <p>Documento generado el {{ now()->format('d/m/Y h:i A') }} · Hospital Animal Veterinario · Cali, Colombia</p>
    <p>Este documento es de carácter confidencial y de uso exclusivo del establecimiento veterinario.</p>
</div>

</body>
</html>