<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=style.css href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/28029eb12b.js" crossorigin="anonymous"></script>

</head> 
    <title>Accueil</title>


<body>
<?php if(isset($_SESSION['id'])){ ?>

<header>
    <nav>
        <a href="profil.php">Profil</a>
        <a href="planning.php">Planning</a>
        <a href="reservation-form.php">Reservation</a>
        <a href="connexion.php?deco">Déconnection</a>
     
       
    </nav>
</header>

 <!-- PHP: If user is not connected ... -->
 <?php } else { ?>

<header>
    <nav>
        <a href="index.php" active>Accueil</a>
        <a href="connexion.php">Se connecter</a>
        <a href="inscription.php">Créer un compte</a>
        <a href="planning.php">Planning</a>
    </nav>
</header>



<?php } ?>  

</body>
</html>