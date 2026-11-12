<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Owner;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
{
    $query = Pet::with('owner');

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('species', 'like', '%' . $request->search . '%')
              ->orWhere('breed', 'like', '%' . $request->search . '%')
              ->orWhereHas('owner', function($q) use ($request) {
                  $q->where('name', 'like', '%' . $request->search . '%');
              });
        });
    }

    if ($request->filled('species')) {
        $query->where('species', $request->species);
    }

    $pets = $query->latest()->paginate(10)->withQueryString();
    return view('pets.index', compact('pets'));
}

    public function create(Request $request)
    {
        $owners = Owner::orderBy('name')->get();
        $selected_owner = $request->owner_id;
        return view('pets.create', compact('owners', 'selected_owner'));
    }

   public function store(Request $request)
{
    $request->validate([
        'owner_id'   => 'required|exists:owners,id',
        'name'       => 'required|string|max:255',
        'species'    => 'required|string|max:100',
        'breed'      => 'nullable|string|max:100',
        'birth_date' => 'nullable|date',
        'gender'     => 'nullable|in:macho,hembra',
        'color'      => 'nullable|string|max:100',
        'weight'     => 'nullable|numeric|min:0',
        'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except('photo');

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('pets', 'public');
    }

    Pet::create($data);

    return redirect()->route('pets.index')
        ->with('success', 'Mascota registrada exitosamente.');
}


    public function show(Pet $pet)
    {
        $pet->load('owner', 'medicalRecords.diagnoses', 'appointments');
        return view('pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        $owners = Owner::orderBy('name')->get();
        return view('pets.edit', compact('pet', 'owners'));
    }

   public function update(Request $request, Pet $pet)
{
    $request->validate([
        'owner_id'   => 'required|exists:owners,id',
        'name'       => 'required|string|max:255',
        'species'    => 'required|string|max:100',
        'breed'      => 'nullable|string|max:100',
        'birth_date' => 'nullable|date',
        'gender'     => 'nullable|in:macho,hembra',
        'color'      => 'nullable|string|max:100',
        'weight'     => 'nullable|numeric|min:0',
        'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except('photo');

    if ($request->hasFile('photo')) {
        if ($pet->photo) {
            \Storage::disk('public')->delete($pet->photo);
        }
        $data['photo'] = $request->file('photo')->store('pets', 'public');
    }

    $pet->update($data);

    return redirect()->route('pets.index')
        ->with('success', 'Mascota actualizada exitosamente.');
}

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return redirect()->route('pets.index')
            ->with('success', 'Mascota eliminada exitosamente.');
    }
}
