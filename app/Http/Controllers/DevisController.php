<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DevisController extends Controller
{
    /**
     * Afficher le formulaire de devis
     */
    public function index()
    {
        return view('devis-form');
    }

    /**
     * Traiter l'envoi du formulaire de devis
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'distributeur' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'affaire' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'typeCoffret' => 'nullable|array',
            'materiaux' => 'nullable|string',
            'ip' => 'nullable|string',
            'protectionTete' => 'nullable|array',
            'protectionPrises' => 'nullable|array',
            'observations' => 'nullable|string',
            'pieceJointes.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:5120'
        ]);

        // Traiter les fichiers uploadés
        $fichiers = [];
        if ($request->hasFile('pieceJointes')) {
            foreach ($request->file('pieceJointes') as $file) {
                $fichiers[] = [
                    'nom' => $file->getClientOriginalName(),
                    'chemin' => $file->store('devis-pieces-jointes', 'public'),
                    'taille' => $file->getSize()
                ];
            }
        }

        // Générer le contenu de l'email
        $contenu = $this->genererContenuEmail($validated, $fichiers);

        // Envoyer l'email (optionnel - nécessite la configuration SMTP)
        try {
            // Mail::raw($contenu, function ($message) use ($validated) {
            //     $message->to('info@bals-france.fr')
            //             ->subject('Demande de devis coffret - ' . $validated['affaire'])
            //             ->from($validated['email'], $validated['contact']);
            // });

            return response()->json([
                'success' => true,
                'message' => 'Demande de devis enregistrée avec succès',
                'contenu' => $contenu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer le contenu formaté de l'email
     */
    private function genererContenuEmail($data, $fichiers = [])
    {
        $contenu = "";
        $contenu .= "╔════════════════════════════════════════════════════════════╗\n";
        $contenu .= "║     DEMANDE DE DEVIS - COFFRET DE CHANTIER                 ║\n";
        $contenu .= "╚════════════════════════════════════════════════════════════╝\n\n";

        // Section Contact
        $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
        $contenu .= "│   📋 INFORMATIONS DE CONTACT                             │\n";
        $contenu .= "└─────────────────────────────────────────────────────────┘\n";
        $contenu .= "Distributeur       : " . ($data['distributeur'] ?? '(non renseigné)') . "\n";
        $contenu .= "Contact            : " . ($data['contact'] ?? '(non renseigné)') . "\n";
        $contenu .= "Affaire/Référence  : " . ($data['affaire'] ?? '(non renseigné)') . "\n";
        $contenu .= "Téléphone          : " . ($data['telephone'] ?? '(non renseigné)') . "\n";
        $contenu .= "Email              : " . ($data['email'] ?? '(non renseigné)') . "\n";
        $contenu .= "\n";

        // Section Type de coffret
        if (isset($data['typeCoffret']) && count($data['typeCoffret']) > 0) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   🔧 TYPE DE COFFRET                                     │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            foreach ($data['typeCoffret'] as $type) {
                $contenu .= "  ✓ " . $type . "\n";
            }
            $contenu .= "\n";
        }

        // Section Matériaux
        if (isset($data['materiaux'])) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   🛠️ MATÉRIAUX                                            │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            $contenu .= "  ➤ " . $data['materiaux'] . "\n\n";
        }

        // Section IP
        if (isset($data['ip'])) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   🔒 INDICE DE PROTECTION                                │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            $contenu .= "  ➤ " . $data['ip'] . "\n\n";
        }

        // Section Protection
        $protTete = $data['protectionTete'] ?? [];
        $protPrises = $data['protectionPrises'] ?? [];
        
        if (count($protTete) > 0 || count($protPrises) > 0) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   ⚡ PROTECTION                                          │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            if (count($protTete) > 0) {
                $contenu .= "Protection de tête  : " . implode(", ", $protTete) . "\n";
            }
            if (count($protPrises) > 0) {
                $contenu .= "Protection prises   : " . implode(", ", $protPrises) . "\n";
            }
            $contenu .= "\n";
        }

        // Section Prises
        $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
        $contenu .= "│   🔌 PRISES                                              │\n";
        $contenu .= "└─────────────────────────────────────────────────────────┘\n";
        
        $aucunePrise = true;
        $prises = [
            'prise_nf_qte' => ['nom' => 'NF 10/16A (domestique)', 'brochage' => '2P+T', 'tension' => '230V'],
            'prise_16a_qte' => ['nom' => 'CEI 16A', 'brochage' => 'prise_16a_brochage', 'tension' => 'prise_16a_tension'],
            'prise_32a_qte' => ['nom' => 'CEI 32A', 'brochage' => 'prise_32a_brochage', 'tension' => 'prise_32a_tension'],
            'prise_63a_qte' => ['nom' => 'CEI 63A', 'brochage' => 'prise_63a_brochage', 'tension' => 'prise_63a_tension'],
            'prise_125a_qte' => ['nom' => 'CEI 125A', 'brochage' => 'prise_125a_brochage', 'tension' => 'prise_125a_tension'],
        ];

        foreach ($prises as $key => $info) {
            $qte = $data[$key] ?? 0;
            if ($qte > 0) {
                $contenu .= $info['nom'] . "\n";
                $contenu .= "  → Quantité : " . $qte . "\n";
                
                if (is_string($info['brochage'])) {
                    $brochage = $data[$info['brochage']] ?? 'non spécifié';
                    $contenu .= "  → Brochage : " . $brochage . "\n";
                } else {
                    $contenu .= "  → Brochage : " . $info['brochage'] . "\n";
                }
                
                if (is_string($info['tension'])) {
                    $tension = $data[$info['tension']] ?? 'non spécifié';
                    $contenu .= "  → Tension  : " . $tension . "\n";
                } else {
                    $contenu .= "  → Tension  : " . $info['tension'] . "\n";
                }
                
                $contenu .= "\n";
                $aucunePrise = false;
            }
        }

        if ($aucunePrise) {
            $contenu .= "  (aucune prise spécifiée)\n\n";
        }

        // Section Pièces jointes
        if (count($fichiers) > 0) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   📎 PIÈCES JOINTES                                      │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            foreach ($fichiers as $fichier) {
                $taille = $this->formatTailleFichier($fichier['taille']);
                $contenu .= "  • " . $fichier['nom'] . " (" . $taille . ")\n";
            }
            $contenu .= "\n";
        }

        // Section Observations
        if (isset($data['observations']) && !empty($data['observations'])) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   💬 OBSERVATIONS                                        │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            $contenu .= $data['observations'] . "\n\n";
        }

        // Footer
        $contenu .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $contenu .= "           BALS France - Coffrets sur mesure\n";
        $contenu .= "                 info@bals-france.fr\n";
        $contenu .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        return $contenu;
    }

    /**
     * Formater la taille du fichier
     */
    private function formatTailleFichier($bytes)
    {
        if ($bytes == 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}