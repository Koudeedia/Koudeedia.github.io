<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include_once "condb.php";

if (isset($_POST['add_wishlist']) && isset($_POST['id_article'])) {
    $article_id = intval($_POST['id_article']);

    // Si utilisateur connecté → utiliser son id
    if (isset($_SESSION['utilisateur_id'])) {
        $id_utilisateur = intval($_SESSION['utilisateur_id']);
    } else {
        // Sinon → id_utilisateur = 0 ou NULL (à toi de voir selon ta table)
        $id_utilisateur = 0;
    }

    // Vérifier si l'article est déjà dans la wishlist
    $check = $con->prepare("SELECT id FROM wishlist WHERE id_utilisateur = ? AND id_article = ?");
    $check->bind_param("ii", $id_utilisateur, $article_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        // Ajouter à la wishlist
        $insert = $con->prepare("INSERT INTO wishlist (id_utilisateur, id_article) VALUES (?, ?)");
        $insert->bind_param("ii", $id_utilisateur, $article_id);
        $insert->execute();
        $insert->close();
    }

    $check->close();
}

// Redirection vers la page précédente
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: index.php');
}
exit();
?>
