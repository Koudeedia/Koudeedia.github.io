<?php
// Diany-Kani MAYILA Koudedia TIMBO
session_start();
include_once "condb.php";

if (isset($_GET['id_article'])) {
    $id_article = intval($_GET['id_article']);

    if (isset($_SESSION['utilisateur_id'])) {
        $id_utilisateur = intval($_SESSION['utilisateur_id']);
    } else {
        $id_utilisateur = 0;
    }

    // Supprimer l'article spécifique pour cet utilisateur
    $stmt = $con->prepare("DELETE FROM wishlist WHERE id_article = ? AND id_utilisateur = ?");
    $stmt->bind_param("ii", $id_article, $id_utilisateur);
    $stmt->execute();
    $stmt->close();
}

// Retour à la wishlist
header("Location: ma_wishlist.php");
exit();
?>
