<?php
session_start();

//connexion à la base de données
include "condb.php";



//id du client connecté
$id_client=isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Exemple

// Vérifier si l'ID du client est valide
if ($id_client<=0) {
    echo "ID client invalide.";
    exit();
}

// Requête pour récupérer les commandes du client
$sql="SELECT c.*, COUNT(ca.id_produit) as nombre_articles 
        FROM commandes c
        LEFT JOIN commandes_articles ca ON c.id_commande = ca.id_commande
        WHERE c.id_client = ?
        GROUP BY c.id_commande";

$stmt=$con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i",$id_client);
    $stmt->execute();
    $result=$stmt->get_result();
} else {
    echo "Erreur de préparation de la requête.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des commandes</title>
    <link rel="stylesheet" href="histo.css">
    <style>
        /* Styles pour l'historique des commandes */
        .commandes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .commandes-table th, 
        .commandes-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .commandes-table th {
            background-color: var(--cultured-2);
            font-weight: var(--fw-600);
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: var(--fs-8);
            font-weight: var(--fw-600);
        }
        
        .badge.en.attente {
            background-color: var(--pale-spring-bud);
            color: var(--hoockers-green);
        }
        
        .badge.payée {
            background-color: var(--hoockers-green_20);
            color: var(--hoockers-green);
        }
    </style>
</head>
<body>
    <!-- Menu de navigation -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <a href="panier.php">Retour au panier</a>
            </div>
        </div>
    </header>
    
    <section class="section" data-section>
        <div class="container">
            <h1 class="h1">Historique de vos commandes</h1>
            
            <?php if ($result->num_rows > 0): ?>
                <table class="commandes-table">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Articles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($commande=$result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($commande['id_commande']) ?></td>
                                <td><?= date('d/m/Y à H:i', strtotime($commande['date_commande'])) ?></td>
                                <td><?= number_format($commande['montant_total'], 2, ',', ' ') ?> €</td>
                                <td>
                                    <span class="badge <?= strtolower($commande['statut']) ?>">
                                        <?= htmlspecialchars($commande['statut']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($commande['nombre_articles']) ?> article(s)</td>
                                <td>
                                    <a href="detail_commande.php?id=<?= $commande['id_commande'] ?>" class="btn-link">
                                        Voir détails
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>Vous n'avez pas encore effectué de commande.</p>
                    <a href="index.php" class="btn btn-primary">Découvrir nos bijoux</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="PremierProjetJS.js"></script>
</body>
</html>