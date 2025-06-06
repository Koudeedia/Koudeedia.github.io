<?php
// Diany-Kani MAYILA Koudedia TIMBO
session_start();
include_once "condb.php";

if (isset($_SESSION['utilisateur_id'])) {
    $id_utilisateur = intval($_SESSION['utilisateur_id']);
} else {
    $id_utilisateur = 0;
}

// Récupérer les articles de la wishlist
$result = mysqli_query($con, "SELECT a.* FROM articles a 
JOIN wishlist w ON a.id = w.id_article 
WHERE w.id_utilisateur = $id_utilisateur");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Wishlist</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .wishlist-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .wishlist-table th, .wishlist-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .wishlist-table th {
            background-color: #ec7de2;
            color: white;
            font-size: 16px;
        }

        .wishlist-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px auto;
            background-color:rgb(146, 58, 139);
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
            text-align: center;
        }

        .btn:hover {
            background-color: #ec7de2;
        }

        .btn-delete {
            background-color:rgb(228, 122, 110);
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
            font-size: 14px;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Ma Wishlist</h2>

<?php if (mysqli_num_rows($result) == 0): ?>
    <p style="text-align:center;">Votre wishlist est vide.</p>
<?php else: ?>
    <table class="wishlist-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($article = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($article['nom']) ?></td>
                    <td><?= number_format($article['prix'], 2) ?> €</td>
                    <td><a href="supprimer_wishlist.php?id_article=<?= $article['id'] ?>" class="btn-delete">Supprimer</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php endif; ?>

<div style="text-align:center;">
    <a href="index.php" class="btn"> Retour à la boutique</a>
</div>

</body>
</html>
