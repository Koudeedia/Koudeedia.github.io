<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include_once "condb.php"; 
$message = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurisation des entrées
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['motDePasse'];

    // On récupère l'utilisateur correspondant à l'email
    $res = mysqli_query($con, "SELECT * FROM utilisateurs WHERE email='$email'");
    $user = mysqli_fetch_assoc($res);

    // Vérification de l'existence de l'utilisateur et du mot de passe
    if ($user && !empty($user['motDePasse'])) {
        if (password_verify($password, $user['motDePasse'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: user.php');
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Utilisateur non trouvé.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connecter</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .logo-container {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .login-form {
            margin-top: auto;
            margin-bottom: auto;
            text-align: center;
        }
        h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="img/Logoo.png" width="179" height="230" alt="JewelsWorld">
    </div>
    <div class="login-form">
        <h2>Connecter</h2>
        <?php if ($message != "") { ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Email: <input type="email" name="email" required><br><br>
            Mot de passe: <input type="password" name="motDePasse" required><br><br>
            <button type="submit">Connecter</button>
        </form>
    </div>
</body>
</html>
