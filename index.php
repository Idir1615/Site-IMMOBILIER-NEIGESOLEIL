<?php
session_start();
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();


$page = isset($_GET["page"]) ? $_GET["page"] : 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neige & Soleil</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- ===== HEADER ===== -->
<header class="header-main d-flex justify-content-between align-items-center py-3 px-4">
    <div class="d-flex align-items-center gap-3">
        <a href="index.php?page=1" class="logo-link">
            <img src="images/design/logoo.png" alt="Logo Neige & Soleil" class="logo-img">
        </a>
    </div>

    <?php if(isset($_SESSION['email'])): ?>
        <div>
            <a href="index.php?page=6" class="header-btn">Déconnexion</a>
        </div>
    <?php endif; ?>
</header>

<!-- ===== NAVIGATION ===== -->
<?php if(isset($_SESSION['email'])): ?>
<div class="container my-3">
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="index.php?page=1" class="btn-dashboard">Accueil</a>
        <a href="index.php?page=2" class="btn-dashboard">Appartements</a>
        <a href="index.php?page=3" class="btn-dashboard">Propriétaires</a>
        <a href="index.php?page=4" class="btn-dashboard">Clients</a>
        <a href="index.php?page=5" class="btn-dashboard">Locations</a>
    </div>
</div>
<?php endif; ?>

<!-- ===== CONTENU PRINCIPAL ===== -->
<main class="container my-5">
<?php
// Page d'inscription
if ($page === "inscription") {
    require_once("controleur/gestion_inscription.php");
    require_once("vue/vue_inscription.php");

// Formulaire de connexion si non connecté
} elseif (!isset($_SESSION['email'])) {
    echo '<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">';
    require_once("vue/vue_connexion.php");
    echo '</div>';

    if (isset($_POST['Connexion'])) {
    $email = $_POST['email'];
    $mdp   = $_POST['mdp'];
    $unUser = $unControleur->getModele()->select_user_by_email($email);

    if (!$unUser) {
        echo "<p class='text-danger text-center mt-3'>Veuillez vérifier vos identifiants.</p>";
    } else {
        if (password_verify($mdp, $unUser['mdp'])) {
            session_regenerate_id(true);

            $_SESSION['email']  = $unUser['email'];
            $_SESSION['nom']    = $unUser['nom'];
            $_SESSION['prenom'] = $unUser['prenom'];

            header("Location: index.php?page=1");
            exit;
        } else {
            echo "<p class='text-danger text-center mt-3'>Veuillez vérifier vos identifiants.</p>";
        }
    }
}


// Pages après connexion
} else {
    switch($page){
        case 1: require_once("controleur/home.php"); break;
        case 2: require_once("controleur/gestion_appartement.php"); break;
        case 3: require_once("controleur/gestion_proprietaire.php"); break;
        case 4: require_once("controleur/gestion_client.php"); break;
        case 5: require_once("controleur/gestion_location.php"); break;
        case 6:
            session_destroy();
            unset($_SESSION['email']);
            header("Location: index.php");
            exit;
        default: require_once("controleur/erreur.php"); break;
    }
}
?>
</main>

<!-- ===== FOOTER ===== -->
<footer class="footer-main text-center py-3">
    &copy; <?= date('Y') ?> Neige & Soleil. Tous droits réservés.
    <img src="images/design/footer.png" width="50" alt="">
</footer>
</body>
</html>
