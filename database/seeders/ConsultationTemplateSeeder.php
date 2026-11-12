<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConsultationTemplate;

class ConsultationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'         => 'Control de vacunación',
                'category'     => 'vacunacion',
                'symptoms'     => 'Paciente acude a control de vacunación anual. Sin síntomas aparentes. Buen estado general.',
                'treatment'    => 'Aplicación de vacuna correspondiente según esquema de vacunación. Se recomienda reposo relativo por 24 horas.',
                'observations' => 'Paciente tolera bien el procedimiento. Sin reacciones adversas inmediatas.',
                'condition'    => 'Paciente sano en control de vacunación',
            ],
            [
                'name'         => 'Consulta general',
                'category'     => 'general',
                'symptoms'     => 'Propietario refiere cambios en el comportamiento del paciente. Se realiza examen físico completo.',
                'treatment'    => 'Se indica tratamiento según hallazgos del examen físico. Se recomienda dieta balanceada y ejercicio moderado.',
                'observations' => 'Paciente colaborador durante el examen. Se programa control en 15 días.',
                'condition'    => 'Consulta de rutina',
            ],
            [
                'name'         => 'Desparasitación',
                'category'     => 'desparasitacion',
                'symptoms'     => 'Paciente acude a desparasitación de rutina. Sin síntomas de parasitosis evidentes.',
                'treatment'    => 'Aplicación de antiparasitario interno y externo según peso del paciente. Se indica repetir en 3 meses.',
                'observations' => 'Se recomienda mantener limpio el entorno del animal y revisar periódicamente signos de parasitosis.',
                'condition'    => 'Desparasitación preventiva',
            ],
            [
                'name'         => 'Pre-quirúrgico',
                'category'     => 'cirugia',
                'symptoms'     => 'Paciente acude a valoración pre-quirúrgica. Se realizan exámenes de laboratorio de rutina.',
                'treatment'    => 'Ayuno de 8 horas previo a la cirugía. Suspender medicamentos según indicación. Baño previo el día anterior.',
                'observations' => 'Paciente en condiciones aptas para procedimiento quirúrgico. Firma de consentimiento informado por el propietario.',
                'condition'    => 'Valoración pre-quirúrgica',
            ],
            [
                'name'         => 'Gastroenteritis',
                'category'     => 'enfermedad',
                'symptoms'     => 'Paciente presenta vómito y diarrea de 24-48 horas de evolución. Posible decaimiento y pérdida de apetito.',
                'treatment'    => 'Dieta blanda por 3 días. Hidratación oral o intravenosa según grado de deshidratación. Antieméticos y protectores gástricos.',
                'observations' => 'Se recomienda control en 48 horas. Evitar cambios bruscos de alimentación.',
                'condition'    => 'Gastroenteritis aguda',
            ],
            [
                'name'         => 'Control post-operatorio',
                'category'     => 'cirugia',
                'symptoms'     => 'Paciente acude a control post-operatorio. Se evalúa cicatrización y estado general.',
                'treatment'    => 'Curación de herida quirúrgica. Continuar antibióticos y analgésicos según prescripción. Mantener collar isabelino.',
                'observations' => 'Herida en proceso de cicatrización normal. Sin signos de infección. Próximo control en 7 días.',
                'condition'    => 'Post-operatorio en evolución favorable',
            ],
        ];

        foreach ($templates as $template) {
            ConsultationTemplate::create($template);
        }
    }
}
