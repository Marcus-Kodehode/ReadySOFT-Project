<?php

// File: app/Http/Controllers/ResourceController.php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * ResourceController
 * 
 * Håndterer CRUD operasjoner for booking-ressurser (hytter, stoler, rom, etc.)
 * Sikrer tenant-isolasjon ved å filtrere alle queries på auth()->user()->tenant_id
 */
class ResourceController extends Controller
{
    /**
     * Display a listing of the resources for the authenticated tenant.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Hent alle ressurser for innlogget tenant med eager loading av availabilities
        $resources = Resource::where('tenant_id', Auth::user()->tenant_id)
            ->with('availabilities')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valider input
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('resources', 'name')
                    ->where('tenant_id', Auth::user()->tenant_id)
            ],
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'active' => 'boolean',
            'availabilities' => 'nullable|array',
            'availabilities.*.enabled' => 'nullable|boolean',
            'availabilities.*.start_time' => 'nullable|date_format:H:i',
            'availabilities.*.end_time' => 'nullable|date_format:H:i|after:availabilities.*.start_time',
        ]);

        // Legg til tenant_id automatisk
        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['active'] = $request->has('active') ? true : false;

        try {
            // Opprett ressurs
            $resource = Resource::create($validated);

            // Lagre availabilities hvis de finnes
            if ($request->has('availabilities')) {
                $this->saveAvailabilities($resource, $request->input('availabilities'));
            }

            // Flash success melding
            session()->flash('success', 'Resource created successfully');

            return redirect()->route('resources.index');
        } catch (\Exception $e) {
            // Flash error melding
            session()->flash('error', 'Failed to create resource');

            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Hent ressurs med eager loading av availabilities
        // Global scope sikrer at kun tenant sine ressurser kan hentes
        $resource = Resource::where('tenant_id', Auth::user()->tenant_id)
            ->with('availabilities')
            ->findOrFail($id);

        return view('resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Hent ressurs (sikrer tenant-isolasjon)
        $resource = Resource::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        // Valider input
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('resources', 'name')
                    ->where('tenant_id', Auth::user()->tenant_id)
                    ->ignore($id)
            ],
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'active' => 'boolean',
            'availabilities' => 'nullable|array',
            'availabilities.*.enabled' => 'nullable|boolean',
            'availabilities.*.start_time' => 'nullable|date_format:H:i',
            'availabilities.*.end_time' => 'nullable|date_format:H:i|after:availabilities.*.start_time',
        ]);

        $validated['active'] = $request->has('active') ? true : false;

        try {
            // Oppdater ressurs
            $resource->update($validated);

            // Oppdater availabilities
            // Slett eksisterende og opprett nye
            $resource->availabilities()->delete();
            if ($request->has('availabilities')) {
                $this->saveAvailabilities($resource, $request->input('availabilities'));
            }

            // Flash success melding
            session()->flash('success', 'Resource updated successfully');

            return redirect()->route('resources.index');
        } catch (\Exception $e) {
            // Flash error melding
            session()->flash('error', 'Failed to update resource');

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Hent ressurs (sikrer tenant-isolasjon)
        $resource = Resource::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        try {
            // Slett ressurs (cascade vil slette tilhørende availabilities og bookings)
            $resource->delete();

            // Flash success melding
            session()->flash('success', 'Resource deleted successfully');

            return redirect()->route('resources.index');
        } catch (\Exception $e) {
            // Flash error melding
            session()->flash('error', 'Failed to delete resource');

            return back();
        }
    }

    /**
     * Save resource availabilities.
     *
     * @param  \App\Models\Resource  $resource
     * @param  array  $availabilities
     * @return void
     */
    private function saveAvailabilities(Resource $resource, array $availabilities)
    {
        foreach ($availabilities as $dayOfWeek => $availability) {
            // Kun lagre hvis dagen er enabled og har tider
            if (
                isset($availability['enabled']) && 
                $availability['enabled'] && 
                isset($availability['start_time']) && 
                isset($availability['end_time'])
            ) {
                $resource->availabilities()->create([
                    'day_of_week' => $dayOfWeek,
                    'start_time' => $availability['start_time'],
                    'end_time' => $availability['end_time'],
                ]);
            }
        }
    }
}

// CRUD controller for booking resources - håndterer hytter, stoler, rom, etc.
