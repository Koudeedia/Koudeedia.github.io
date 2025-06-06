<!-- Diany-Kani MAYILA Koudedia TIMBO -->
<?php

include_once "condb.php"; // Inclure le fichier de connexion

if(!isset($_SESSION)) session_start();

if(!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    if(!is_numeric($id)) die("ID invalide");

    $produit = mysqli_query($con, "SELECT * FROM articles WHERE id = '$id'");
    if(mysqli_num_rows($produit) === 0) die("Ce produit n'existe pas");

    $_SESSION['panier'][$id] = isset($_SESSION['panier'][$id]) ? $_SESSION['panier'][$id] + 1 : 1;
    header("Location: index.php");
    exit();
}

?>

