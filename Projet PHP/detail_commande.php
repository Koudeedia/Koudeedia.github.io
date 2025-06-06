<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
// Connexion à la base de données
include "condb.php";

// Vérifier la connexion
if ($con->connect_error) {
    die("Connexion échouée: " . $con->connect_error);
}

$id_commande = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_client = $_SESSION['id_client'] ?? 1; // Exemple

// Vérifier que la commande appartient bien au client
$stmt_check=$con->prepare("SELECT * FROM commandes WHERE id_commande = ? AND id_client = ?");
$stmt_check->bind_param("ii", $id_commande, $id_client);
$stmt_check->execute();
$result_check=$stmt_check->get_result();

if ($result_check->num_rows === 0) {
    die("Cette commande n'existe pas ou ne vous appartient pas.");
}

$commande = $result_check->fetch_assoc();

// Récupérer les articles de la commande
$stmt_articles = $con->prepare("
    SELECT ca.*, p.nom as nom_produit 
    FROM commandes_articles ca
    JOIN articles p ON ca.id_produit = p.id
    WHERE ca.id_commande = ?
");
$stmt_articles->bind_param("i", $id_commande);
$stmt_articles->execute();
$result_articles = $stmt_articles->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la commande #<?= $id_commande ?> - DABADI Bijoux</title>
    <link rel="stylesheet" href="#">
    <style>
        .commande-info {
            background-color: var(--cultured-1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .panier {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .panier th, 
        .panier td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .panier th {
            background-color: var(--cultured-2);
            font-weight: var(--fw-600);
        }
    </style>
</head>
<body>
    <!-- Menu de navigation (même code que précédemment) -->
    <header class="header">
        <!-- ... -->
    </header>
    
    <section class="section" data-section>
        <div class="container">
            <h1 class="h1">Détail de la commande #<?= $id_commande ?></h1>
            
            <div class="commande-info">
                <p><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($commande['date_commande'])) ?></p>
                <p><strong>Statut actuel :</strong> 
                    <span class="badge <?= strtolower($commande['statut']) ?>">
                        <?= htmlspecialchars($commande['statut']) ?>
                    </span>
                </p>
                <p><strong>Montant total :</strong> <?= number_format($commande['montant_total'], 2, ',', ' ') ?> €</p>
                <p><strong>Adresse de livraison :</strong> <?= nl2br(htmlspecialchars($commande['adresse_livraison'])) ?></p>
            </div>
            
            <h2 class="h2">Articles commandés</h2>
            <table class="panier">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($article = $result_articles->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($article['nom_produit']) ?></td>
                            <td><?= number_format($article['prix_unitaire'], 2, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($article['quantite']) ?></td>
                            <td><?= number_format($article['prix_unitaire'] * $article['quantite'], 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <a href="historique_commandes.php" class="btn btn-secondary">Retour à l'historique</a>
        </div>
    </section>

    <script src="PremierProjetJS.js"></script>
</body>
</html>