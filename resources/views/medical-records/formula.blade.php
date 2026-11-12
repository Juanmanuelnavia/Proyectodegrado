<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }

        .header { background-color: #0f766e; color: white; padding: 15px 30px; margin-bottom: 15px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 10px; opacity: 0.85; margin-top: 2px; }
        .header-right { float: right; text-align: right; }

        .content { padding: 0 30px; }

        .title { font-size: 16px; font-weight: bold; color: #0f766e; text-align: center;
                 border-bottom: 2px solid #0f766e; padding-bottom: 8px; margin-bottom: 15px; }

        .patient-box { background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
                       padding: 12px 15px; margin-bottom: 15px; }
        .patient-grid { width: 100%; border-collapse: collapse; }
        .patient-grid td { width: 50%; padding: 3px 5px; font-size: 11px; }
        .label { color: #6b7280; }
        .value { font-weight: bold; color: #1f2937; }

        .section { margin-bottom: 15px; }
        .section-title { font-size: 12px; font-weight: bold; color: #0f766e;
                         border-left: 3px solid #0f766e; padding-left: 8px; margin-bottom: 8px; }
        .section-content { background-color: #f9fafb; border: 1px solid #e5e7eb;
                           border-radius: 6px; padding: 10px 12px; font-size: 11px; line-height: 1.6; }

        .rx { font-size: 28px; font-weight: bold; color: #0f766e; float: left;
              margin-right: 10px; line-height: 1; }

        .diagnosis-box { border: 1px solid #d1fae5; background-color: #f0fdf4;
                         border-radius: 6px; padding: 12px; margin-bottom: 10px; }
        .diagnosis-grid { width: 100%; border-collapse: collapse; }
        .diagnosis-grid td { width: 50%; padding: 4px 5px; vertical-align: top; }

        .signature-area { margin-top: 30px; }
        .signature-line { border-top: 1px solid #1f2937; width: 200px; margin-top: 40px; }
        .signature-label { font-size: 10px; color: #6b7280; margin-top: 4px; }

        .footer { margin-top: 20px; padding: 10px 30px; border-top: 1px solid #e5e7eb;
                  text-align: center; font-size: 9px; color: #9ca3af; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header clearfix">
    <div class="header-right">
        <p style="font-size:13px; font-weight:bold;">FÓRMULA MÉDICA</p>
        <p>{{ now()->format('d/m/Y') }}</p>
    </div>
    <h1>🐾 Hospital Animal Veterinario</h1>
    <p>Barrio El Caney, Cali, Valle del Cauca · Lun-Sáb 8am-8pm · Dom 8am-2pm</p>
</div>

<div class="content">

    <div class="title">FÓRMULA MÉDICA VETERINARIA</div>

    {{-- Datos del paciente --}}
    <div class="patient-box">
        <table class="patient-grid">
            <tr>
                <td><span class="label">Paciente: </span><span class="value">{{ $medicalRecord->pet->name }}</span></td>
                <td><span class="label">Propietario: </span><span class="value">{{ $medicalRecord->pet->owner->name }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Especie/Raza: </span><span class="value">{{ ucfirst($medicalRecord->pet->species) }} — {{ $medicalRecord->pet->breed ?? 'Sin raza' }}</span></td>
                <td><span class="label">Teléfono: </span><span class="value">{{ $medicalRecord->pet->owner->phone }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Peso: </span><span class="value">{{ $medicalRecord->weight ? $medicalRecord->weight . ' kg' : '—' }}</span></td>
                <td><span class="label">Fecha: </span><span class="value">{{ \Carbon\Carbon::parse($medicalRecord->visit_date)->format('d/m/Y') }}</span></td>
            </tr>
        </table>
    </div>

    {{-- Diagnóstico --}}
    @if($medicalRecord->diagnoses->count() > 0)
    <div class="section">
        <div class="section-title">Diagnóstico</div>
        @foreach($medicalRecord->diagnoses as $diagnosis)
        <div class="diagnosis-box">
            <table class="diagnosis-grid">
                <tr>
                    <td colspan="2" style="font-weight:bold; color:#0f766e; font-size:12px; padding-bottom:6px;">
                        {{ $diagnosis->condition }}
                    </td>
                </tr>
                @if($diagnosis->medication)
                <tr>
                    <td><span class="label">Medicamento: </span><span class="value">{{ $diagnosis->medication }}</span></td>
                    <td><span class="label">Dosis: </span><span class="value">{{ $diagnosis->dosage ?? '—' }}</span></td>
                </tr>
                @endif
                @if($diagnosis->next_visit)
                <tr>
                    <td colspan="2"><span class="label">Próxima visita: </span>
                        <span class="value">{{ \Carbon\Carbon::parse($diagnosis->next_visit)->format('d/m/Y') }}</span>
                    </td>
                </tr>
                @endif
            </table>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tratamiento --}}
    @if($medicalRecord->treatment)
    <div class="section">
        <div class="section-title clearfix">
            <span class="rx">℞</span> Tratamiento indicado
        </div>
        <div class="section-content">{{ $medicalRecord->treatment }}</div>
    </div>
    @endif

    {{-- Observaciones --}}
    @if($medicalRecord->observations)
    <div class="section">
        <div class="section-title">Observaciones</div>
        <div class="section-content">{{ $medicalRecord->observations }}</div>
    </div>
    @endif

    {{-- Firma --}}
    <div class="signature-area clearfix">
        <div style="float:right; text-align:center;">
            <div class="signature-line"></div>
            <div class="signature-label">{{ $medicalRecord->veterinarian->name }}</div>
            <div class="signature-label">Médico Veterinario</div>
            <div class="signature-label">Hospital Animal Veterinario</div>
        </div>
    </div>

</div>

<div class="footer">
    <p>Este documento es una fórmula médica veterinaria oficial · Hospital Animal Veterinario · Cali, Colombia</p>
    <p>Ante cualquier reacción adversa, comuníquese inmediatamente con el hospital.</p>
</div>

</body>
</html>