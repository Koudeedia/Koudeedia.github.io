<?php
/* Diany-Kani MAYILA Koudedia TIMBO */
session_start();
include 'condb.php';

if ($connection->connect_error){
    die("La connexion a échoué : " .$connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql= "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt= $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows>0) {
        $user = $result->fetch_assoc();

        if ($password===$user['motDePasse']) {

            $_SESSION['utilisateur']=$user['email'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message']="Mot de passe incorrect.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['message']="Aucun compte trouvé avec cet email.";
        header("Location:login.php");
        exit();
    }
}

$connection->close();
?>