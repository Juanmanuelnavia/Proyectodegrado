<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return view('search', ['query' => $query, 'results' => []]);
        }

        $owners = Owner::where('name', 'like', "%$query%")
            ->orWhere('phone', 'like', "%$query%")
            ->orWhere('document', 'like', "%$query%")
            ->take(5)->get();

        $pets = Pet::with('owner')
            ->where('name', 'like', "%$query%")
            ->orWhere('breed', 'like', "%$query%")
            ->orWhere('species', 'like', "%$query%")
            ->take(5)->get();

        $appointments = Appointment::with(['pet', 'owner'])
            ->whereHas('pet', fn($q) => $q->where('name', 'like', "%$query%"))
            ->orWhereHas('owner', fn($q) => $q->where('name', 'like', "%$query%"))
            ->orWhere('reason', 'like', "%$query%")
            ->take(5)->get();

        $records = MedicalRecord::with(['pet', 'veterinarian'])
            ->whereHas('pet', fn($q) => $q->where('name', 'like', "%$query%"))
            ->orWhere('symptoms', 'like', "%$query%")
            ->take(5)->get();

        return view('search', compact('query', 'owners', 'pets', 'appointments', 'records'));
    }
}
