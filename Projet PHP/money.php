<?php
session_start();
include "condb.php";

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifie que le panier existe
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    die("Votre panier est vide.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Traitement du paiement

    $id_client = intval($_SESSION['user_id']);

    // Calcul du total du panier
    $total = 0;
    $ids = array_keys($_SESSION['panier']);
    if (!empty($ids)) {
        $ids_string = implode(',', array_map('intval', $ids));
        $result = mysqli_query($con, "SELECT * FROM articles WHERE id IN ($ids_string)");
        while ($article = mysqli_fetch_assoc($result)) {
            $quantite = $_SESSION['panier'][$article['id']];
            $total += $article['prix'] * $quantite;
        }
    }

    // Insère la commande
    $date = date('Y-m-d H:i:s');
    $statut = 'payée';
    $adresse_livraison = 'Adresse à compléter'; // Tu pourras l'améliorer plus tard

    mysqli_query($con, "INSERT INTO commandes (id_client, date_commande, montant_total, statut, adresse_livraison)
        VALUES ($id_client, '$date', $total, '$statut', '$adresse_livraison')");

    $id_commande = mysqli_insert_id($con);

    // Insère les articles commandés
    foreach ($_SESSION['panier'] as $id_article => $quantite) {
        $result = mysqli_query($con, "SELECT prix FROM articles WHERE id = " . intval($id_article));
        $article = mysqli_fetch_assoc($result);
        $prix_unitaire = $article['prix'];
        mysqli_query($con, "INSERT INTO commandes_articles (id_commande, id_produit, quantite, prix_unitaire)
            VALUES ($id_commande, $id_article, $quantite, $prix_unitaire)");
            
mysqli_query($con, "UPDATE articles SET stock = stock - $quantite WHERE id = $id_article");

    }

    // Vide le panier
    unset($_SESSION['panier']);

    // Redirige vers la page d'historique
    header("Location: historique_commandes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement en ligne</title>
    <link rel="stylesheet" href="histo.css">
    <style> 

 body { 

 font-family: Arial, sans-serif; 

background-color: #f7cef3; 

 } 

 .container { 

 width: 300px; 

 margin: 20px auto; 

 padding: 20px; 

 border: 6px solid  #f880ee; 

 border-radius: 5px; 

 } 

 .form-group { 

 margin-bottom: 15px; 

 } 

 .form-group label { 

 display: block; 

 margin-bottom: 5px; 

 } 

 .form-group input { 

 width: 100%; 

 padding: 8px; 

 border: 1px solid #ccc; 

 border-radius: 4px; 

 box-sizing: border-box; 

 } 

 .form-group input[type="number"] { 

 width: 48%; 

 } 

 .btn { 

 background-color: #f880ee; 

 color: white; 

 padding: 10px 15px; 

 border: none; 

 border-radius: 4px; 

 cursor: pointer; 

 width: 100%; 

 } 

 .logos { 

 text-align: center; 

 margin-bottom: 20px; 

 } 

 .logos img { 

  margin: 0 5px; 

  height: 45px; 

  } 

 </style> 
</head>
<body>
    <div class="container">
        <h2>Payez en ligne</h2>
        <div class="logos">
            <img src="img/money.jpg" alt="Mastercard">
        </div>
        <form method="post" action="">
            <div class="form-group">
                <label for="card_name">Nom sur la carte</label>
                <input type="text" id="card_name" name="card_name" required>
            </div>
            <div class="form-group">
                <label for="card_number">N° de carte</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>
            <div class="form-group">
                <label>Date d'expiration</label>
                <input type="number" placeholder="MM" name="expiry_month" required>
                <input type="number" placeholder="AA" name="expiry_year" required>
            </div>
            <div class="form-group">
                <label for="cvv">Cryptogramme visuel</label>
                <input type="number" id="cvv" name="cvv" required>
            </div>
            <button type="submit" class="btn">Confirmer le paiement</button>
        </form>
    </div>
</body>
</html>