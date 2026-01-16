<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Demande de Devis - Coffret de Chantier</title>
</head>

<body>
    <form id="devisForm" class="container">
        <!-- EN-TÊTE -->
        <h1>🔌 Demande de Devis - Coffret de Chantier</h1>
        <p class="email-info">📧 À envoyer à : <strong>info@bals-france.fr</strong></p>

        <!-- SECTION 1 : CONTACT -->
        <div class="section">
            <h2>📋 Informations de Contact</h2>

            <div class="form-group">
                <label>Distributeur :</label>
                <input type="text" id="distributeur" name="distributeur" placeholder="Nom du distributeur" onchange="updatePreview()" required>
            </div>

            <div class="form-group">
                <label>Contact :</label>
                <input type="text" id="contact" name="contact" placeholder="Nom du contact" onchange="updatePreview()" required>
            </div>

            <div class="form-group">
                <label>Affaire / Référence :</label>
                <input type="text" id="affaire" name="affaire" placeholder="Référence de l'affaire" onchange="updatePreview()" required>
            </div>

            <div class="form-group">
                <label>Téléphone :</label>
                <input type="tel" id="telephone" name="telephone" placeholder="01 23 45 67 89" onchange="updatePreview()" required>
            </div>

            <div class="form-group">
                <label>Email :</label>
                <input type="email" id="email" name="email" placeholder="contact@exemple.fr" onchange="updatePreview()" required>
            </div>
        </div>

        <!-- SECTION 2 : TYPE DE COFFRET -->
        <div class="section">
            <h2>🔧 Type de Coffret</h2>

            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="typeCoffret" value="Fixe" onchange="updatePreview()">
                    Fixe
                </label>

                <label class="checkbox-label">
                    <input type="checkbox" name="typeCoffret" value="Mobile" onchange="updatePreview()">
                    Mobile
                </label>

                <label class="checkbox-label">
                    <input type="checkbox" name="typeCoffret" value="Mobile sur pied" onchange="updatePreview()">
                    Mobile sur pied
                </label>
            </div>
        </div>

        <!-- SECTION 3 : MATÉRIAUX -->
        <div class="section">
            <h2>🛠️ Matériaux</h2>

            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="radio" name="materiaux" value="Caoutchouc" onchange="updatePreview()">
                    Caoutchouc
                </label>

                <label class="checkbox-label">
                    <input type="radio" name="materiaux" value="Métallique" onchange="updatePreview()">
                    Métallique
                </label>

                <label class="checkbox-label">
                    <input type="radio" name="materiaux" value="Plastique" onchange="updatePreview()">
                    Plastique
                </label>
            </div>
        </div>

        <!-- SECTION 4 : INDICE DE PROTECTION -->
        <div class="section">
            <h2>🔒 Indice de Protection (IP)</h2>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="radio" name="ip" value="IP44 - Protection contre projections d'eau" onchange="updatePreview()">
                    IP44 - Protection contre projections d'eau
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="radio" name="ip" value="IP54 - Protection contre poussières et projections" onchange="updatePreview()">
                    IP54 - Protection contre poussières et projections
                </label>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="radio" name="ip" value="IP67 - Étanche à la poussière et immersion temporaire" onchange="updatePreview()">
                    IP67 - Étanche à la poussière et immersion temporaire
                </label>
            </div>
        </div>

        <!-- SECTION 5 : PROTECTION -->
        <div class="section">
            <h2>⚡ Protection</h2>

            <div class="form-group">
                <label>Protection de tête :</label>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Sans" onchange="updatePreview()">
                        Sans
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Interrupteur" onchange="updatePreview()">
                        Interrupteur
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Inter différentiel" onchange="updatePreview()">
                        Inter différentiel
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Disjoncteur" onchange="updatePreview()">
                        Disjoncteur
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Disjoncteur Diff." onchange="updatePreview()">
                        Disjoncteur Diff.
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Arrêt d'urgence" onchange="updatePreview()">
                        Arrêt d'urgence
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionTete" value="Je ne sais pas" onchange="updatePreview()">
                        Je ne sais pas
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Protection des prises :</label>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionPrises" value="Sans" onchange="updatePreview()">
                        Sans
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionPrises" value="Par prise" onchange="updatePreview()">
                        Par prise
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionPrises" value="Par groupe de prises" onchange="updatePreview()">
                        Par groupe de prises
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionPrises" value="Disjoncteur" onchange="updatePreview()">
                        Disjoncteur
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionPrises" value="Disjoncteur Diff." onchange="updatePreview()">
                        Disjoncteur Diff.
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="protectionPrises" value="Je ne sais pas" onchange="updatePreview()">
                        Je ne sais pas
                    </label>
                </div>
            </div>
        </div>

        <!-- IMAGE EXEMPLE -->
        <img src="https://www.bals-france.fr/wp-content/uploads/p/2/2/2/222-COFFRET-DE-CHANTIER-Disj-Diff-2P-32A-30mA-AU-4PC-NF-1-Socle-connecteur-2PT-32A-230V-CEI-300x275.jpg" 
             alt="Exemple de coffret">

        <!-- SECTION 6 : PRISES -->
        <div class="section">
            <h2>🔌 Prises</h2>

            <table>
                <thead>
                    <tr>
                        <th>Type de prise</th>
                        <th>Quantité</th>
                        <th>Brochage</th>
                        <th>Tension</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>NF 10/16A (domestique)</td>
                        <td><input type="number" id="prise_nf_qte" name="prise_nf_qte" min="0" value="0" onchange="updatePreview()"></td>
                        <td>2P+T</td>
                        <td>230V</td>
                    </tr>
                    <tr>
                        <td>CEI 16A</td>
                        <td><input type="number" id="prise_16a_qte" name="prise_16a_qte" min="0" value="0" onchange="updatePreview()"></td>
                        <td>
                            <select id="prise_16a_brochage" name="prise_16a_brochage" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="2P+T">2P+T</option>
                                <option value="3P+T">3P+T</option>
                                <option value="3P+N+T">3P+N+T</option>
                            </select>
                        </td>
                        <td>
                            <select id="prise_16a_tension" name="prise_16a_tension" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="230V">230V</option>
                                <option value="400V">400V</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>CEI 32A</td>
                        <td><input type="number" id="prise_32a_qte" name="prise_32a_qte" min="0" value="0" onchange="updatePreview()"></td>
                        <td>
                            <select id="prise_32a_brochage" name="prise_32a_brochage" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="2P+T">2P+T</option>
                                <option value="3P+T">3P+T</option>
                                <option value="3P+N+T">3P+N+T</option>
                            </select>
                        </td>
                        <td>
                            <select id="prise_32a_tension" name="prise_32a_tension" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="230V">230V</option>
                                <option value="400V">400V</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>CEI 63A</td>
                        <td><input type="number" id="prise_63a_qte" name="prise_63a_qte" min="0" value="0" onchange="updatePreview()"></td>
                        <td>
                            <select id="prise_63a_brochage" name="prise_63a_brochage" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="2P+T">2P+T</option>
                                <option value="3P+T">3P+T</option>
                                <option value="3P+N+T">3P+N+T</option>
                            </select>
                        </td>
                        <td>
                            <select id="prise_63a_tension" name="prise_63a_tension" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="230V">230V</option>
                                <option value="400V">400V</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>CEI 125A</td>
                        <td><input type="number" id="prise_125a_qte" name="prise_125a_qte" min="0" value="0" onchange="updatePreview()"></td>
                        <td>
                            <select id="prise_125a_brochage" name="prise_125a_brochage" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="2P+T">2P+T</option>
                                <option value="3P+T">3P+T</option>
                                <option value="3P+N+T">3P+N+T</option>
                            </select>
                        </td>
                        <td>
                            <select id="prise_125a_tension" name="prise_125a_tension" onchange="updatePreview()">
                                <option value="">--</option>
                                <option value="230V">230V</option>
                                <option value="400V">400V</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- APERÇU VISUEL -->
        <div class="section visual-preview">
            <h2>👁️ Aperçu de la configuration</h2>
            <div id="coffretDisplay" class="coffret-container">
                <img id="coffretImage" src="{{ asset('images/empty.png') }}" alt="Aperçu coffret">
                <p id="priseCountLabel">0 prise(s) sélectionnée(s)</p>
            </div>
        </div>

        <!-- SECTION 7 : PIÈCES JOINTES -->
        <div class="section">
            <h2>📎 Pièces jointes (facultatif)</h2>

            <div class="form-group">
                <label>Ajouter des documents (PDF, images) :</label>
                <input type="file" id="pieceJointes" name="pieceJointes[]" multiple accept=".pdf,.jpg,.jpeg,.png,.gif" onchange="afficherFichiers()">
                <small style="color: #666; display: block; margin-top: 5px;">
                    Vous pouvez sélectionner plusieurs fichiers (formats acceptés : PDF, JPG, PNG, GIF)
                </small>
            </div>

            <!-- Liste des fichiers sélectionnés -->
            <div id="listeFichiers" style="margin-top: 15px;"></div>
        </div>

        <!-- SECTION 8 : OBSERVATIONS -->
        <div class="section">
            <h2>💬 Observations</h2>

            <div class="form-group">
                <label>Informations complémentaires :</label>
                <textarea id="observations" name="observations" placeholder="Ajoutez vos remarques, besoins particuliers..." onchange="updatePreview()"></textarea>
            </div>
        </div>

        <!-- PRÉVISUALISATION DE L'EMAIL -->
        <div class="preview-zone">
            <h3>👁️ Prévisualisation de l'email</h3>
            <div id="emailPreview">
                Remplissez le formulaire pour voir la prévisualisation...
            </div>
        </div>

        <!-- BOUTONS D'ACTION -->
        <div class="button-group">
            <button type="button" class="copy-btn" onclick="copierTexte()">
                📋 Copier le texte
            </button>
            <button type="submit" class="submit-btn">
                📧 Envoyer par Email
            </button>
        </div>
    </form>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>