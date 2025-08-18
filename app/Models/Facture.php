<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'date',
        'total_ht',
        'total_tva',
        'total_ttc',
    ];
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function ligneFactures(): HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }
}
