<?php
namespace App\Services;

use InvalidArgumentException;

class CalculateurFacture
{
    protected array $taux_tva = [0, 5.5, 10.0, 20.0];

    public function calcul(array $lignes): array
    {
        // On verifie si la facture contient au moins une ligne
        if (count($lignes) === 0) {
            throw new InvalidArgumentException('La facture doit contenir au moins une ligne.');
        }

        $totalHt = 0.0;
        $totalTva = 0.0;
        $donneeLigne = [];

        foreach ($lignes as $i => $ligne) {
            $this->validationLigne($ligne, $i);

            $quantite = (int)$ligne['quantite'];
            $pu = (float)$ligne['prix_unitaire'];
            $tva = (float)$ligne['taux_tva'];

            // on verifie que le taux envoyé est bien conforme et present dans $this->taux_tva
            if (!in_array($tva, $this->taux_tva, true)) {
                throw new InvalidArgumentException("Taux de TVA invalide : {$tva}");
            }

            // On calcule les totaux de la ligne

            $ligneTotalHt = round($quantite * $pu, 2);
            $ligneTotalTva = round($ligneTotalHt * ($tva / 100), 2);
            $ligneTotalTtc = round($ligneTotalHt + $ligneTotalTva, 2);

            $totalHt += $ligneTotalHt;
            $totalTva += $ligneTotalTva;

            // On ajoute la ligne au tableau de donnees de la facture 
            $donneeLigne[] = array_merge($ligne, [
                'ligne_total_ht' => $ligneTotalHt,
                'ligne_total_tva' => $ligneTotalTva,
                'ligne_total_ttc' => $ligneTotalTtc
            ]);
        }

        $totalHt = round($totalHt, 2);
        $totalTva = round($totalTva, 2);
        $totalTtc = round($totalHt + $totalTva, 2);

        return [
            'lignes' => $donneeLigne,
            'total_ht' => $totalHt,
            'total_tva' => $totalTva,
            'total_ttc' => $totalTtc,
        ];
    }

    protected function validationLigne(array $ligne, int $index): void
    {
        // On verfiie si toutes les données on été bien renseignés et les types sont bons
        $index = $index + 1;
        $champs = ['description','quantite','prix_unitaire','taux_tva'];
        foreach ($champs as $valeur) {
            if (!isset($ligne[$valeur]) || $ligne[$valeur] === '' || $ligne[$valeur] === null) {
                throw new InvalidArgumentException("Ligne {$index} : le champ {$valeur}  doit être renseigné.");
            }
        }
        if (!is_numeric($ligne['quantite']) || (int)$ligne['quantite'] <= 0) {
            throw new InvalidArgumentException("Ligne {$index} : la quantité doit être un entier > 0.");
        }
        if (!is_numeric($ligne['prix_unitaire']) || (float)$ligne['prix_unitaire'] < 0) {
            throw new InvalidArgumentException("Ligne {$index} : doit être un entier > 0.");
        }
    }
}
