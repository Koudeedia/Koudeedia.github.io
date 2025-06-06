<?php 
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include_once "condb.php";

//supprimer les produits
if(isset($_GET['del'])){
    $id_del = $_GET['del'];
    unset($_SESSION['panier'][$id_del]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link rel="stylesheet" href="paniercss.css">
</head>
<body class="panier">
    <a href="PremierProjetInfoHtml.html" class="link">Retour à la boutique</a>
    <a href="index.php" class="link">Tous les articles</a>
    <a href="ma_wishlist.php" class="link">Ma wishlist</a>

    <section>
        <table>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
            <?php 
            $total = 0;
            
            $ids = isset($_SESSION['panier']) ? array_keys($_SESSION['panier']) : [];

            if(empty($ids)){
                echo "<tr><td colspan='5'>Votre panier est vide</td></tr>";
            } else {
                $products = mysqli_query($con, "SELECT * FROM articles WHERE id IN (".implode(',', $ids).")");
                foreach($products as $product):
                    $quantite = $_SESSION['panier'][$product['id']];
                    $total += $product['prix'] * $quantite;
            ?>
                <tr>
                    <td><img src="img/<?= htmlspecialchars($product['img']) ?>"></td>
                    <td><?= htmlspecialchars($product['nom']) ?></td>
                    <td><?= number_format($product['prix'],2) ?>€</td>
                    <td><?= $quantite ?></td>
                    <td><a href="panier.php?del=<?= $product['id'] ?>"><img src="img/delete.png"></a></td>
                </tr>
            <?php endforeach; } ?>
            <tr class="total">
                <th colspan="5">Total : <?= number_format($total,2) ?>€</th>
            </tr>
        </table>
    </section>
    <a href="money.php" class="link">Payez en ligne</a>
    <a href="historique_commandes.php" class="link">Historique de commandes</a>
</body>
</html>