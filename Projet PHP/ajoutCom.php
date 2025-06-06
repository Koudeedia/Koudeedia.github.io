<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
include_once "condb.php";

//on vérifie des données du formulaire
if(isset($_POST['article_id'], $_POST['auteur'], $_POST['commentaire']) && 
   !empty($_POST['article_id']) && !empty($_POST['auteur']) && !empty($_POST['commentaire'])) {

    //sécurisation des données pas obli 
    $article_id=intval($_POST['article_id']);
    $auteur=mysqli_real_escape_string($con, htmlspecialchars($_POST['auteur']));
    $commentaire=mysqli_real_escape_string($con, htmlspecialchars($_POST['commentaire']));

    //insertion du commentaire dans la base de données
    $requete="INSERT INTO commentaires (article_id, auteur, commentaire) 
                VALUES ('$article_id', '$auteur', '$commentaire')";

    mysqli_query($con, $requete);
}

//redirection vers la page précédente
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>