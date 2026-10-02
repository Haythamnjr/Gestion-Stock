<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Stock</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Formulaire de saisie des mouvements de stock -->
    <form action="Afficher.php" method="post" id="stock-form" class="container-input">
        <div class="oper">
            <label for="Rec">Rechercher le produit :</label>
            <input type="text" name="Rec" id="Rec" onkeyup="Recherche()">
            <label for="oper">Nature des opérations :</label>
            <input type="text" name="oper" id="oper">
        </div>
        <fieldset class="entry-group">
            <legend>Entrée</legend>
            <div class="qte">
                <label for="qte-enter">Quantité entrée :</label>
                <input type="text" name="qte_entree" id="qte-enter">
            </div>
            <div class="cu">
                <label for="cu-enter">Coût unitaire entrée :</label>
                <input type="text" name="cu_entree" id="cu-enter">
            </div>
        </fieldset>
        <fieldset class="exit-group">
            <legend>Sortie</legend>
            <div class="qte">
                <label for="qte-sortie">Quantité sortie :</label>
                <input type="text" name="qte_sortie" id="qte-sortie">
            </div>
            <div class="cu">
                <label for="cu-sortie">Coût unitaire sortie :</label>
                <input type="text" name="cu_sortie" id="cu-sortie">
            </div>
        </fieldset>

        <fieldset class="stock-group">
            <legend>Stock</legend>
            <div class="qte">
                <label for="qte-stock">Quantité stock :</label>
                <input type="text" name="qte_stock" id="qte-stock">
            </div>
            <div class="cu">
                <label for="cu-stock">Coût unitaire stock :</label>
                <input type="text" name="cu_stock" id="cu-stock">
            </div>
        </fieldset>

        <div class="actions">
            <button type="submit" name="Valide">Valider</button>
        </div>
    </form>
    <!-- Tableau d'affichage des mouvements de stock enregistrés -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Nature des oper</th>
                <th colspan="3">Entrées</th>
                <th colspan="3">Sorties</th>
                <th colspan="3">Stock</th>
                <th rowspan="2">Supprimer</th>
                <th rowspan="2">Modifier</th>
            </tr>
            <tr>
                <th>QTE</th>
                <th>CU</th>
                <th class="break">MT</th>
                <th>QTE</th>
                <th>CU</th>
                <th class="break">MT</th>
                <th>QTE</th>
                <th>CU</th>
                <th class="break">MT</th>
            </tr>
        </thead>
        <tbody id="tbody">
            <!-- Les lignes seront ajoutées via JavaScript -->
        </tbody>
    </table>

    <p>Le coût moyen pondéré unique : <span></span></p>
    <script src="JS.js"></script>
</body>

</html>