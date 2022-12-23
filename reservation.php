<?php

if(!isset($_GET['id'])){
    header('Location: index.php');
}   


session_start();

include('config.php');

$reservation_id = $_GET['id'];


$viewdb = $conn->prepare("SELECT * FROM `reservations` INNER JOIN `utilisateurs` ON reservations.id_utilisateur = utilisateurs.id WHERE reservations.id = '$reservation_id' ");
$viewdb->execute();

$res = $viewdb->fetchAll(PDO::FETCH_ASSOC);

//$today = date('Y-m-d'); //return eg. '2022-12-17' 

//var_dump($res);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Livreor</title>
    </head>
    
    
    <body>
        
        <header>
            <nav>
                <a href="index.php" active>Accueil</a>
                <a href="profil.php">Profil</a>
                <a href="connexion.php?deco">Déconnection</a>
            </nav>
        </header>
        
        <section class="style">
            <?php foreach ($res as $value) : ?>
                
                <div class="reservation">
                    <p> Resérvé par <?php echo $value['login'] ?> le <?php echo $value['titre'] ?></p>
                    <p> <?php echo($value['description']) ?> </p>
                    <p> <?php echo($value['debut']) ?> </p>
                    <p> <?php echo($value['fin']) ?> </p>
                </div>
                
                <?php endforeach; ?>
                
                
            </section>
            
        </body>
        </html>
        
        