<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include_once "condb.php";

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupère les infos utilisateur
$user_id = intval($_SESSION['user_id']);
$user_req = mysqli_query($con, "SELECT * FROM utilisateurs WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_req);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon espace utilisateur</title>
    <link rel="stylesheet" href="paniercss.css">
    <style>
        .user-space { max-width: 700px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 12px; }
        .user-space h2 { color: #b60d6e; }
        .user-space ul { list-style: none; padding: 0; }
        .user-space li { margin-bottom: 10px; }
        .user-actions a { margin-right: 15px; }
        .wishlist-table, .orders-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .wishlist-table th, .wishlist-table td, .orders-table th, .orders-table td { border: 1px solid #eee; padding: 7px; }
        .wishlist-table th, .orders-table th { background: #f8e5f3; }
    </style>
</head>
<body>
<div class="user-space">
    <h2>Bienvenue, <?= htmlspecialchars($user['nom']) ?></h2>
    <div class="user-actions">
        <a href="panier.php">Mon panier</a>
        <a href="historique_commandes.php">Historique de commandes</a>
        <a href="ma_wishlist.php">Ma wishlist</a>
        <a href="logout.php">Déconnexion</a>
    </div>
    <hr>

    <h3>Mes informations</h3>
    <ul>
        <li><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></li>
        <li><strong>Date d'inscription :</strong> <?= date('d/m/Y', strtotime($user['date_inscription'])) ?></li>
    </ul>
    <hr>

    <h3>Ma wishlist</h3>
    <table class="wishlist-table">
        <tr>
            <th>Article</th>
            <th>Ajouté le</th>
            <th>Action</th>
        </tr>
        <?php
        $wish_req = mysqli_query($con, "SELECT w.*, a.nom FROM wishlist w JOIN articles a ON w.id_article = a.id WHERE w.id_utilisateur = $user_id ORDER BY w.date_ajout DESC");
        if(mysqli_num_rows($wish_req) > 0) {
            while($wish = mysqli_fetch_assoc($wish_req)) {
                echo "<tr>
                    <td>".htmlspecialchars($wish['nom'])."</td>
                    <td>".date('d/m/Y', strtotime($wish['date_ajout']))."</td>
                    <td><a href='suppr_wishlist.php?id=".$wish['id']."'>Supprimer</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Votre wishlist est vide.</td></tr>";
        }
        ?>
    </table>
    <hr>

    <h3>Dernières commandes</h3>
    <table class="orders-table">
        <tr>
            <th>N°</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Détail</th>
        </tr>
        <?php
        $orders_req = mysqli_query($con, "SELECT * FROM commandes WHERE id_client = $user_id ORDER BY date_commande DESC LIMIT 5");
        if(mysqli_num_rows($orders_req) > 0) {
            while($order = mysqli_fetch_assoc($orders_req)) {
                echo "<tr>
                    <td>#".$order['id_commande']."</td>
                    <td>".date('d/m/Y', strtotime($order['date_commande']))."</td>
                    <td>".number_format($order['montant_total'],2,","," ")." €</td>
                    <td>".htmlspecialchars($order['statut'])."</td>
                    <td><a href='detail_commande.php?id=".$order['id_commande']."'>Voir</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Aucune commande trouvée.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
