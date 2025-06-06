<?php 
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include_once "condb.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boutique</title>
    <link rel="stylesheet" href="paniercss.css">
</head>
<body>
    <a href="PremierProjetInfoHtml.html" class="link">Retour à la boutique</a>
    <a href="panier.php" class="link">Voir le panier</a>
    <section>
        <table>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Ajouter au panier</th>
                <th>Commentaires</th>
            </tr>
            <?php 
            $req = mysqli_query($con, "SELECT * FROM articles");
            while($article = mysqli_fetch_assoc($req)) { ?>
                <tr>
                    <td>
                        <img src="img/<?= htmlspecialchars($article['img']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>" style="width:60px;">
                    </td>
                    <td><?= htmlspecialchars($article['nom']) ?></td>
                    <td><?= number_format($article['prix'], 2) ?>€</td>
                    <td>
                        <a href="ajoutpanier.php?id=<?= $article['id'] ?>" class="id_product">Ajouter au panier</a>
                    </td>
                    <td>
                        <div class="comments-section">
                            <strong>Commentaires :</strong><br>
                            <?php
                            $comments_query = mysqli_query($con, "SELECT * FROM commentaires WHERE article_id = " . intval($article['id']) . " ORDER BY date_commentaire DESC");
                            if(mysqli_num_rows($comments_query) > 0) {
                                while($comment = mysqli_fetch_assoc($comments_query)) {
                                    echo '<div class="comment">';
                                    echo '<strong>' . htmlspecialchars($comment['auteur']) . '</strong> - ' . 
                                         date('d/m/Y à H:i', strtotime($comment['date_commentaire'])) . '<br>';
                                    echo htmlspecialchars($comment['commentaire']);
                                    echo '</div>';
                                }
                            } else {
                                echo '<em>Aucun commentaire pour cet article.</em>';
                            }
                            ?>
                            <form method="post" action="ajoutCom.php" class="comment-form" style="margin-top:8px;">
                                <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                                <input type="text" name="auteur" placeholder="Votre nom" required>
                                <textarea name="commentaire" placeholder="Votre commentaire" required></textarea>
                                <button type="submit">Envoyer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>
