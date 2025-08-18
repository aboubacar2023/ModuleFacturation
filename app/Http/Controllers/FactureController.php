<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Services\CalculateurFacture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    protected CalculateurFacture $calculateur;

    public function __construct(CalculateurFacture $calculateur)
    {
        $this->calculateur = $calculateur;
    }

    public function index(): JsonResponse
    {
        $factures = Facture::with('ligneFactures','client')->get();
        return response()->json($factures);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'lignes' => 'required|array|min:1',
        ]);

        try {
            // On calcule la facture et ses lignes
            $resultats = $this->calculateur->calcul($data['lignes']);    
        } catch (\InvalidArgumentException $ex) {
            return response()->json([
                'message' => 'Les données fournies sont invalides.',
                'errors' => [
                    'lignes' => [$ex->getMessage()],
                ],
            ], 422);
        }

        $facture = Facture::create([
            'client_id' => $data['client_id'],
            'date' => $data['date'],
            'total_ht' => $resultats['total_ht'],
            'total_tva' => $resultats['total_tva'],
            'total_ttc' => $resultats['total_ttc'],
        ]);

        foreach ($resultats['lignes'] as $ligne) {
            $facture->ligneFactures()->create($ligne);
        }

        return response()->json(Facture::with('ligneFactures','client')->findOrFail($facture->id), 201);
    }

    public function show(Facture $facture): JsonResponse
    {
        return response()->json(Facture::with('ligneFactures','client')->findOrFail($facture->id));
    }

    public function exportJson(Facture $facture): JsonResponse
    {
        $facture->load('client','ligneFactures');

        // On transforme la facture en tableau pour l'export en JSON 
        $data = [
            'facture_id' => $facture->id,
            'date' => $facture->date,
            'client' => [
                'id' => $facture->client->id,
                'name' => $facture->client->name,
                'email' => $facture->client->email,
                'siret' => $facture->client->siret,
            ],
            // On utilise une map pour transformer les lignes en tableau de donnees et pouvoir les exporter en JSON
            'lignes' => $facture->ligneFactures->map(function($ligne){
                return [
                    'description' => $ligne->description,
                    'quantite' => (int)$ligne->quantite,
                    'prix_unitaire' => (float)$ligne->prix_unitaire,
                    'taux_tva' => (float)$ligne->taux_tva,
                    'ligne_total_ht' => (float)$ligne->ligne_total_ht,
                    'ligne_total_tva' => (float)$ligne->ligne_total_tva,
                    'ligne_total_ttc' => (float)$ligne->ligne_total_ttc,
                ];
            }),
            'totals' => [
                'total_ht' => (float)$facture->total_ht,
                'total_tva' => (float)$facture->total_tva,
                'total_ttc' => (float)$facture->total_ttc,
            ],
        ];

        return response()->json($data);
    }
}
