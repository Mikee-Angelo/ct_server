<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Establishment;
use App\Http\Requests\Establishment\StoreEstablishmentRequest;
use Illuminate\Support\Str;

class EstablishmentController extends Controller
{
    public function index() {
        return Inertia::render('Establishment/Index');
    }

    public function store(StoreEstablishmentRequest $request) {
        try {
            $validated = $request->validated();

            //Generate establishment code
            $id = Establishment::latest()->first()->id ?? 1;
            $validated['establishment_code'] = 'EST-' . $id . '-'. Str::upper(Str::random(15));

            //Store final data
            Establishment::create($validated);

            back()->with('message', [
                'title' => 'Establishment successfully added',
                'description' => 'Added new establishment'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'Something went wrong'
            ]);
        }
    }
}
