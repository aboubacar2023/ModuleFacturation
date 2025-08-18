<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneFacture extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'quantite',
        'prix_unitaire',
        'taux_tva',
        'ligne_total_ht',
        'ligne_total_tva',
        'ligne_total_ttc',
        'facture_id'
    ];
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }
}
