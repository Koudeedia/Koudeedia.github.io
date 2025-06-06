<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include "condb.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $motDePasse = $_POST["motDePasse"];
    // Récupére l'utilisteur par email
    $requete = "SELECT id, motDePasse FROM utilisateurs WHERE email = ?";
    $stmt = mysqli_prepare($con, $requete);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);
        if ($resultat && $row = mysqli_fetch_assoc($resultat)) {
            // Vérifie le mot de passe
            if (password_verify($motDePasse, $row["motDePasse"])) {
                // Authntification réussie
                $_SESSION["user_id"] = $row["id"];
                // Rediriger vers la page principale
                header("Location: PremierProjetInfoHtml.html"); 
                exit();
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            $message = "Aucun utilisateur trouvé avec cet email.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "Erreur de requête.";
    }

    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Déconnexion</title>
    <style>
        body {
            display: flex;
            flex-direction: column; /* Aligne les éléments verticalement */
            align-items: center; /* Centre horizontalement */
            min-height: 100vh;
            margin: 0;
        }
        .logo-container {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .login-form {
            /* Pousse le formulaire vers le bass */
            margin-top: auto; 
            /* Centrer verticalement le frmulaire */
            margin-bottom: auto; 
            text-align: center;
        }

        h2 {
            margin-top: 0; /* Réduit l'espace au-dessus du titre h2 */
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="img/Logoo.png" width="179" height="230" alt="JewelsWorld">
    </div>

    <div class="login-form">
        <h2>Déconnexion</h2>
        <?php if ($message != "") { ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Email: <input type="email" name="email" required><br><br>
            Mot de passe: <input type="password" name="motDePasse" required><br><br>
            <button type="submit">Se déconnecter</button>
        </form>
    </div>
</body>
</html>
