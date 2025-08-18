<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Client::all());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:clients,email',
            'siret' => 'required|string|unique:clients,siret',
        ]);

        $client = Client::create($data);
        return response()->json($client, 201);
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json(Client::with('factures.ligneFactures')->findOrFail($client->id));
    }
}
