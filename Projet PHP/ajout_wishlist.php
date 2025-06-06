<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include_once "condb.php";
if (isset($_POST['id_article'])) {
    $id_article=intval($_POST['id_article']);
    $id_utilisateur=$_SESSION['utilisateur_id'];
    //vérifie si l'article est déjà dans la wishlist
    $check=$con->prepare("SELECT id FROM wishlist WHERE id_utilisateur = ? AND id_article = ?");
    $check->bind_param("ii", $id_utilisateur, $id_article);
    $check->execute();
    $check->store_result();
    if ($check->num_rows==0) {
        //ajoute à la wishlist
        $insert = $con->prepare("INSERT INTO wishlist (id_utilisateur, id_article) VALUES (?, ?)");
        $insert->bind_param("ii", $id_utilisateur, $id_article);
        $insert->execute();
        $insert->close();
    }
    $check->close();
}
//redirection vers la wishlist
header("Location: index.php");
exit();
?>