<?php

namespace App\Livewire; 

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;

class DevisFormWizard extends Component
{
    use WithFileUploads;

    // Gestion des étapes
    public $currentStep = 1;
    public $totalSteps = 8;

    // Étape 1 : Informations de contact
    public $distributeur = '';
    public $contact = '';
    public $affaire = '';
    public $telephone = '';
    public $email = '';

    // Étape 2 : Type de coffret
    public $typeCoffret = [];

    // Étape 3 : Matériaux
    public $materiaux = '';

    // Étape 4 : Indice de protection
    public $ip = '';

    // Étape 5 : Protection
    public $protectionTete = [];
    public $protectionPrises = [];

    // Étape 6 : Prises
    public $prise_nf_qte = 0;
    public $prise_16a_qte = 0;
    public $prise_16a_brochage = '';
    public $prise_16a_tension = '';
    public $prise_32a_qte = 0;
    public $prise_32a_brochage = '';
    public $prise_32a_tension = '';
    public $prise_63a_qte = 0;
    public $prise_63a_brochage = '';
    public $prise_63a_tension = '';
    public $prise_125a_qte = 0;
    public $prise_125a_brochage = '';
    public $prise_125a_tension = '';

    // Étape 7 : Pièces jointes
    public $pieceJointes = [];

    // Étape 8 : Observations
    public $observations = '';

    // Validation par étape
    protected function rules()
    {
        return [
            // Étape 1
            'distributeur' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'affaire' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            
            // Étape 2
            'typeCoffret' => 'nullable|array',
            
            // Étape 3
            'materiaux' => 'nullable|string',
            
            // Étape 4
            'ip' => 'nullable|string',
            
            // Étape 5
            'protectionTete' => 'nullable|array',
            'protectionPrises' => 'nullable|array',
            
            // Étape 6
            'prise_nf_qte' => 'nullable|integer|min:0',
            'prise_16a_qte' => 'nullable|integer|min:0',
            'prise_32a_qte' => 'nullable|integer|min:0',
            'prise_63a_qte' => 'nullable|integer|min:0',
            'prise_125a_qte' => 'nullable|integer|min:0',
            
            // Étape 7
            'pieceJointes.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:5120',
            
            // Étape 8
            'observations' => 'nullable|string',
        ];
    }

    // Messages de validation personnalisés
    protected $messages = [
        'distributeur.required' => 'Le nom du distributeur est obligatoire',
        'contact.required' => 'Le nom du contact est obligatoire',
        'affaire.required' => 'La référence de l\'affaire est obligatoire',
        'telephone.required' => 'Le numéro de téléphone est obligatoire',
        'email.required' => 'L\'email est obligatoire',
        'email.email' => 'L\'email doit être une adresse valide',
    ];

    // Validation en temps réel
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Passer à l'étape suivante
    public function nextStep()
    {
        // Valider l'étape actuelle
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    // Revenir à l'étape précédente
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // Aller directement à une étape (si déjà validée)
    public function goToStep($step)
    {
        if ($step <= $this->currentStep || $this->isStepCompleted($step - 1)) {
            $this->currentStep = $step;
        }
    }

    // Valider l'étape actuelle
    private function validateCurrentStep()
    {
        $rules = [];

        switch ($this->currentStep) {
            case 1:
                $rules = [
                    'distributeur' => 'required|string|max:255',
                    'contact' => 'required|string|max:255',
                    'affaire' => 'required|string|max:255',
                    'telephone' => 'required|string|max:20',
                    'email' => 'required|email|max:255',
                ];
                break;
            
            case 2:
                $rules = [
                    'typeCoffret' => 'required|array|min:1',
                ];
                break;
            
            case 3:
                $rules = [
                    'materiaux' => 'required|string',
                ];
                break;
            
            case 4:
                $rules = [
                    'ip' => 'required|string',
                ];
                break;
            
            // Les autres étapes sont optionnelles
            case 5:
            case 6:
            case 7:
            case 8:
                // Pas de validation obligatoire pour ces étapes
                return;
        }

        $this->validate($rules);
    }

    // Vérifier si une étape est complétée
    public function isStepCompleted($step)
    {
        switch ($step) {
            case 1:
                return !empty($this->distributeur) && 
                       !empty($this->contact) && 
                       !empty($this->affaire) && 
                       !empty($this->telephone) && 
                       !empty($this->email);
            
            case 2:
                return count($this->typeCoffret) > 0;
            
            case 3:
                return !empty($this->materiaux);
            
            case 4:
                return !empty($this->ip);
            
            case 5:
            case 6:
            case 7:
            case 8:
                return true; // Ces étapes sont optionnelles
            
            default:
                return false;
        }
    }

    // Soumettre le formulaire
    public function submit()
    {
        // Valider toutes les données
        $validatedData = $this->validate();

        // Traiter les fichiers uploadés
        $fichiers = [];
        if (!empty($this->pieceJointes)) {
            foreach ($this->pieceJointes as $file) {
                $fichiers[] = [
                    'nom' => $file->getClientOriginalName(),
                    'chemin' => $file->store('devis-pieces-jointes', 'public'),
                    'taille' => $file->getSize()
                ];
            }
        }

        // Générer le contenu de l'email
        $contenu = $this->genererContenuEmail($fichiers);

        // Envoyer l'email (optionnel)
        try {
            // Décommentez pour activer l'envoi d'email
            // Mail::raw($contenu, function ($message) {
            //     $message->to('info@bals-france.fr')
            //             ->subject('Demande de devis coffret - ' . $this->affaire)
            //             ->from($this->email, $this->contact);
            // });

            session()->flash('success', 'Votre demande de devis a été envoyée avec succès !');
            
            // Réinitialiser le formulaire
            $this->reset();
            $this->currentStep = 1;

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }

    // Générer le contenu de l'email
    private function genererContenuEmail($fichiers = [])
    {
        $contenu = "";
        $contenu .= "╔════════════════════════════════════════════════════════════╗\n";
        $contenu .= "║     DEMANDE DE DEVIS - COFFRET DE CHANTIER                 ║\n";
        $contenu .= "╚════════════════════════════════════════════════════════════╝\n\n";

        // Section Contact
        $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
        $contenu .= "│   📋 INFORMATIONS DE CONTACT                             │\n";
        $contenu .= "└─────────────────────────────────────────────────────────┘\n";
        $contenu .= "Distributeur       : " . $this->distributeur . "\n";
        $contenu .= "Contact            : " . $this->contact . "\n";
        $contenu .= "Affaire/Référence  : " . $this->affaire . "\n";
        $contenu .= "Téléphone          : " . $this->telephone . "\n";
        $contenu .= "Email              : " . $this->email . "\n\n";

        // Section Type de coffret
        if (count($this->typeCoffret) > 0) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│   TYPE DE COFFRET                                     │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            foreach ($this->typeCoffret as $type) {
                $contenu .= "  ✓ " . $type . "\n";
            }
            $contenu .= "\n";
        }

        // Section Matériaux
        if (!empty($this->materiaux)) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│    MATÉRIAUX                                            │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            $contenu .= "  ➤ " . $this->materiaux . "\n\n";
        }

        // Section IP
        if (!empty($this->ip)) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│    INDICE DE PROTECTION                                │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            $contenu .= "  ➤ " . $this->ip . "\n\n";
        }

        // Section Protection
        if (count($this->protectionTete) > 0 || count($this->protectionPrises) > 0) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│    PROTECTION                                          │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            if (count($this->protectionTete) > 0) {
                $contenu .= "Protection de tête  : " . implode(", ", $this->protectionTete) . "\n";
            }
            if (count($this->protectionPrises) > 0) {
                $contenu .= "Protection prises   : " . implode(", ", $this->protectionPrises) . "\n";
            }
            $contenu .= "\n";
        }

        // Section Prises
        $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
        $contenu .= "│   PRISES                                              │\n";
        $contenu .= "└─────────────────────────────────────────────────────────┘\n";
        
        $aucunePrise = true;

        if ($this->prise_nf_qte > 0) {
            $contenu .= "NF 10/16A (domestique)\n";
            $contenu .= "  → Quantité : " . $this->prise_nf_qte . "\n";
            $contenu .= "  → Brochage : 2P+T\n";
            $contenu .= "  → Tension  : 230V\n\n";
            $aucunePrise = false;
        }

        // Ajouter les autres prises...
        // (code similaire pour les autres types de prises)

        if ($aucunePrise) {
            $contenu .= "  (aucune prise spécifiée)\n\n";
        }

        // Section Observations
        if (!empty($this->observations)) {
            $contenu .= "┌─────────────────────────────────────────────────────────┐\n";
            $contenu .= "│    OBSERVATIONS                                        │\n";
            $contenu .= "└─────────────────────────────────────────────────────────┘\n";
            $contenu .= $this->observations . "\n\n";
        }

        // Footer
        $contenu .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $contenu .= "           BALS France - Coffrets sur mesure\n";
        $contenu .= "                 info@bals-france.fr\n";
        $contenu .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        return $contenu;
    }

    // Supprimer un fichier de la liste
    public function removeFile($index)
    {
        array_splice($this->pieceJointes, $index, 1);
    }

    public function render()
    {
        return view('livewire.devis-form-wizard');
    }
}