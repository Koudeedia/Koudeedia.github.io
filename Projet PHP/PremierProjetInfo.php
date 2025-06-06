<!-- Diany-Kani MAYILA Koudedia TIMBO -->
<?php
define('HOST','localhost');
define('DB_NAME','projetphp');
define('USER','root');
define('PASS','');

try {
    $db = new PDO("mysql:host=".HOST.";dbname=".DB_NAME, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connecté !!";
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";
if (isset($_POST['valider']) && $_POST['valider'] === 'inscription') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $motDePasse = password_hash($_POST['motDePasse'], PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $message = "Cet email est déjà utilisé.";
    } else {
        $stmt = $db->prepare("INSERT INTO utilisateurs (nom, email, motDePasse) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $motDePasse]);
        $message = "Inscription réussie !";
        header("Location: user.php"); 
exit();
    }
}






// --- Traitement de l'ajout au panier ---
//inclure la page de connexion
include_once "condb.php";
//verifier si une session existe
if(!isset($_SESSION)){
   //si non demarer la session
   session_start();
}
//creer la session
if(!isset($_SESSION['panier'])){
   //s'il nexiste pas une session on créer une et on mets un tableau a l'intérieur 
   $_SESSION['panier'] = array();
}
//récupération de l'id dans le lien
 if(isset($_GET['id'])){//si un id a été envoyé alors :
   $id = $_GET['id'] ;
   //verifier grace a l'id si le produit existe dans la base de  données
   $produit = mysqli_query($con ,"SELECT * FROM articles WHERE id = $id") ;
   if(empty(mysqli_fetch_assoc($produit))){
       //si ce produit n'existe pas
       die("Ce produit n'existe pas");
   }
   //ajouter le produit dans le panier ( Le tableau)

   if(isset($_SESSION['panier'][$id])){// si le produit est déjà dans le panier
       $_SESSION['panier'][$id]++; //Représente la quantité 
   }else {
       //si non on ajoute le produit
       $_SESSION['panier'][$id]= 1 ;
   }}

  //redirection vers la page index.php
  header("Location:user.php");

?>
