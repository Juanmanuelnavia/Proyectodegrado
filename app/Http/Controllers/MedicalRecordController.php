<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
   public function index(Request $request)
{
    $query = MedicalRecord::with(['pet', 'veterinarian']);

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->whereHas('pet', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('pet.owner', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('symptoms', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('date_from')) {
        $query->whereDate('visit_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('visit_date', '<=', $request->date_to);
    }

    $records = $query->latest()->paginate(10)->withQueryString();
    return view('medical-records.index', compact('records'));
}

    public function create(Request $request)
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        $veterinarians = User::where('role', 'veterinario')->orderBy('name')->get();
        $selected_pet = $request->pet_id;
        return view('medical-records.create', compact('pets', 'veterinarians', 'selected_pet'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pet_id'      => 'required|exists:pets,id',
            'user_id'     => 'required|exists:users,id',
            'visit_date'  => 'required|date',
            'symptoms'    => 'required|string',
            'treatment'   => 'nullable|string',
            'weight'      => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric|min:0',
            'observations'=> 'nullable|string',
            'condition'   => 'nullable|string|max:255',
            'medication'  => 'nullable|string|max:255',
            'dosage'      => 'nullable|string|max:255',
            'next_visit'  => 'nullable|date',
        ]);

        $record = MedicalRecord::create([
            'pet_id'       => $request->pet_id,
            'user_id'      => $request->user_id,
            'visit_date'   => $request->visit_date,
            'symptoms'     => $request->symptoms,
            'treatment'    => $request->treatment,
            'weight'       => $request->weight,
            'temperature'  => $request->temperature,
            'observations' => $request->observations,
        ]);

        if ($request->condition) {
            Diagnosis::create([
                'medical_record_id' => $record->id,
                'condition'         => $request->condition,
                'medication'        => $request->medication,
                'dosage'            => $request->dosage,
                'next_visit'        => $request->next_visit,
            ]);
        }

        return redirect()->route('medical-records.show', $record)
            ->with('success', 'Historia clínica registrada exitosamente.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load('pet.owner', 'veterinarian', 'diagnoses');
        return view('medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        $veterinarians = User::where('role', 'veterinario')->orderBy('name')->get();
        return view('medical-records.edit', compact('medicalRecord', 'pets', 'veterinarians'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'pet_id'      => 'required|exists:pets,id',
            'user_id'     => 'required|exists:users,id',
            'visit_date'  => 'required|date',
            'symptoms'    => 'required|string',
            'treatment'   => 'nullable|string',
            'weight'      => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric|min:0',
            'observations'=> 'nullable|string',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('medical-records.show', $medicalRecord)
            ->with('success', 'Historia clínica actualizada exitosamente.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('medical-records.index')
            ->with('success', 'Historia clínica eliminada exitosamente.');
    }
    public function pdf(MedicalRecord $medicalRecord)
{
    $medicalRecord->load('pet.owner', 'veterinarian', 'diagnoses');
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medical-records.pdf', compact('medicalRecord'));
    return $pdf->download('historia-clinica-' . $medicalRecord->pet->name . '.pdf');
}
public function formula(MedicalRecord $medicalRecord)
{
    $medicalRecord->load('pet.owner', 'veterinarian', 'diagnoses');
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medical-records.formula', compact('medicalRecord'));
    return $pdf->download('formula-' . $medicalRecord->pet->name . '.pdf');
}
}
