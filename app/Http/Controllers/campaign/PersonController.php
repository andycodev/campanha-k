<?php

namespace App\Http\Controllers\campaign;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Sector;
use App\Models\Family;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    // --- LECTURA (READ) ---
    public function getHierarchy()
    {
        // Trae toda la estructura anidada en un solo JSON
        return response()->json(Location::with('sectors.families.people')->get());
    }

    // --- CREACIÓN (CREATE) ---

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:locations,name',
            'general_needs' => 'nullable|string'
        ]);
        $location = Location::create($validated);
        return response()->json(['message' => 'Lugar creado', 'data' => $location], 201);
    }

    public function storeSector(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string',
            'sector_needs' => 'nullable|string',
            'target_voters' => 'nullable|integer|min:0'
        ]);
        $sector = Sector::create($validated);
        return response()->json(['message' => 'Sector creado', 'data' => $sector], 201);
    }

    public function storeFamily(Request $request)
    {
        $validated = $request->validate([
            'sector_id' => 'required|exists:sectors,id',
            'name' => 'required|string',
            'family_needs' => 'nullable|string',
            'expected_voters' => 'required|integer|min:1'
        ]);
        $family = Family::create($validated);
        return response()->json(['message' => 'Familia creada', 'data' => $family], 201);
    }

    public function store(Request $request)
    {
        $nombreInput = trim($request->full_name);

        // 1. Buscamos duplicado global
        // Usamos ILIKE para que 'juan' sea igual a 'JUAN'
        $duplicado = Person::with(['family.sector'])
            ->where('full_name', 'ILIKE', $nombreInput)
            ->first();

        // 2. Si existe y no viene con la bandera de 'force_save'
        if ($duplicado && !$request->has('force_save')) {
            return response()->json([
                'status' => 'duplicate',
                'message' => "Ya existe una persona con ese nombre en el sector: " . $duplicado->family->sector->name,
                'data' => $duplicado
            ], 422);
        }

        // 3. Validación normal
        $validated = $request->validate([
            'family_id' => 'required|exists:families,id',
            'full_name' => 'required|string|max:255',
            'dni' => 'nullable|digits:8|unique:people,dni',
            'disposition' => 'required|in:seguro,duda,opositor',
            'personal_request' => 'nullable|string',
        ]);

        $person = Person::create($validated);

        return response()->json(['message' => 'Registrado con éxito', 'data' => $person], 201);
    }

    // --- ACTUALIZACIÓN (UPDATE) ---

    public function updateLocation(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:locations,name,' . $id,
            'general_needs' => 'nullable|string'
        ]);
        $location->update($validated);
        return response()->json(['message' => 'Lugar actualizado', 'data' => $location]);
    }

    public function updateSector(Request $request, $id)
    {
        $sector = Sector::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'sector_needs' => 'nullable|string',
            'target_voters' => 'sometimes|integer|min:0'
        ]);
        $sector->update($validated);
        return response()->json(['message' => 'Sector actualizado', 'data' => $sector]);
    }

    public function updateFamily(Request $request, $id)
    {
        $family = Family::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'family_needs' => 'nullable|string',
            'expected_voters' => 'sometimes|integer|min:1'
        ]);
        $family->update($validated);
        return response()->json(['message' => 'Familia actualizada', 'data' => $family]);
    }

    public function updatePerson(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'sometimes|string',
            'dni' => 'nullable|digits:8|unique:people,dni,' . $id,
            'disposition' => 'sometimes|in:seguro,duda,opositor',
            'personal_request' => 'nullable|string',
        ]);
        $person->update($validated);
        return response()->json(['message' => 'Datos de persona actualizados', 'data' => $person]);
    }
}
