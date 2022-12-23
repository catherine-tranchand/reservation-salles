<?php

session_start();
include('config.php');

if (!isset($_SESSION['id'])){
    header('index.php');
}

$dateFormat = 'Y-m-d';
$minDate = date($dateFormat);
$maxDate = date($dateFormat, strtotime('+1 year'));

// date_default_timezone_set('Europe/Paris');
// echo date('Y-m-d H:i:s');

//echo date('D', strtotime('2022-12-25'));


    if(isset($_POST['envoi'])){
      if(!empty($_POST['titre'] && $_POST['date'] && $_POST['hd'] && $_POST['hf'] && $_POST['description'])){

        

        $titre = htmlspecialchars($_POST['titre']);
        $date = htmlspecialchars($_POST['date']); // returns eg. '2022-12-16'
        $debut = htmlspecialchars($_POST['hd']); // returns eg. '09'
        $fin = htmlspecialchars($_POST['hf']); // returns eg. '10'
        $description = htmlspecialchars($_POST['description']);
        $id_utilisateur = $_SESSION['id'];

        $debut_time = $debut . ":00:00"; // return eg. '09:00:00'
        $debut_date = $date . " " . $debut_time; // return eg. '2022-12-16 09:00:00'

        $fin_time = $fin . ":00:00"; //return eg. '10:00:00'
        $fin_date = $date . " " . $fin_time; // return eg. '2022-12-16 10:00:00'

        $day = date('D', strtotime($date)); //return eg. 'Fri'
        //$today = date('Y-m-d'); //return eg. '2022-12-17' 

        

        
        if($day == 'Sat' || $day == 'Sun' ){
            // echo "Vous ne pouvez pas reservez pendant les week-ends";
            header('Location: reservation-form.php?erreur=1');
            exit();
        }elseif($fin < $debut){
            header('Location: reservation-form.php?erreur=3');
            exit();
        }

        
        // format date to look like YYYY-MM-DD 

        $checkReserv = $conn->query("SELECT debut FROM `reservations` WHERE '$debut_date' BETWEEN debut AND fin");
      //  $result = $checkReserv->fetchAll(PDO::FETCH_ASSOC);
        $foundReserv = $checkReserv->rowCount() > 0;

        var_dump($foundReserv);

        if($foundReserv){
            
            header('Location: reservation-form.php?erreur=2');
            exit();
        }


        $insertReserv = $conn->prepare("INSERT INTO `reservations` (`titre`, `description`, `debut`, `fin`, `id_utilisateur`) VALUES (?, ?, ?, ?, ?)");
        $result = $insertReserv->execute(array($titre, $description, $debut_date, $fin_date, $id_utilisateur));

        echo "L'activité est reservé. Merci pour votre reservation!";

    


        // 2022-12-16 15:00:00
      
      
      
      
       //  $recupReservation = $conn->prepare("SELECT debut, fin FROM `reservations` WHERE  debut, fin, id_utilisateur VALUES ?, ?, ?, ?");
      //  $recupReservation->execute(array ($debut, $fin, $id_utilisateur));
      //  $foundReservation=($recupReservation->rowCount() > 0);



        }
    }
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href=style.css>
        <title>Inscription</title>
    </head>

<body>
    <header>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="planning.php">Planning</a>
            <a href="profil.php">Profil</a>
        
        </nav>
    </header>

   
    <main> 
        <h2>Reservation form</h2>
        <form action="reservation-form.php" method="post" id="form"> <!--The form with method "post" ---->

        <?php 
        if(isset($_GET['erreur'])){
            $errorCode = $_GET['erreur'];

            switch ($errorCode) {
                case 1:
                    $errorMessage =  "Vous ne pouvez pas reservez pendant les week-ends";
                    break;
                case 2:
                    $errorMessage =  "La date est l'heure ne sont pas disponibles";
                    break;

                case 3:
                    $errorMessage =  "Veuillez choisir les heures valables";
                    break;
                default:
                    $errorMessage =  "L'erreur de la réservation";
                
            }

            
            echo "<div class='err-msg'>$errorMessage</div>";
        }
        ?>
        <input id="titre" name="titre" placeholder="Titre" type="text" required><br> 
        <input id="date" name="date" placeholder="Date" type="date" min="<?= $minDate ?>" max="<?= $maxDate ?>" required><br>
        <label for="hd">Heure de début</label>
        <select id="hd" name="hd" placeholder="Heure de début" type="text">
            <option value="9">9 H</option>
            <option value="10">10 H</option>
            <option value="11">11 H</option>
            <option value="12">12 H</option>
            <option value="13">13 H</option>
            <option value="14">14 H</option>
            <option value="15">15 H</option>
            <option value="16">16 H</option>
            <option value="17">17 H</option>
            <option value="18">18 H</option>
            <option value="19">19 H</option>
            </select><br>
            <label for="hf">Heure de fin</label>
        <select id="hf" name="hf" placeholder="Heure de de fin" type="text">
            <option value="10">10 H</option>
            <option value="11">11 H</option>
            <option value="12">12 H</option>
            <option value="13">13 H</option>
            <option value="14">14 H</option>
            <option value="15">15 H</option>
            <option value="16">16 H</option>
            <option value="17">17 H</option>
            <option value="18">18 H</option>
            <option value="19">19 H</option>
            </select><br>
       
      
            <input id="description" name="description" placeholder="description" type="text" required><br>
           
            <input id="submit" type="submit" name="envoi" value="Envoi"><br>

        </form>

    </main>

</body>

</html>
