<?php

session_start();
include('config.php');   

if(!isset($_SESSION['id'])){
    header('Location: index.php');

}

$id = $_SESSION['id'];
$recupUser = $conn->prepare("SELECT `login` FROM `utilisateurs` WHERE id = '$id'");
$recupUser->execute();
$user = $recupUser->fetch(PDO::FETCH_ASSOC);
$login = $user['login'];
// $hashPassword = $user['password'];

//var_dump($user);


if(isset($_POST['modifier'])){
    if(!empty($_POST['login']) && !empty($_POST['newPassword']) && !empty($_POST['confirmNewPassword'])){

        $newLogin=htmlspecialchars($_POST['login']);  //htmlspecialchar to secure the data
        $newPassword=htmlspecialchars($_POST['newPassword']); 
        $hashPassword=password_hash($_POST['newPassword'], PASSWORD_DEFAULT); // password_hash(encoding) to secure the password

        $newConfirmPassword=htmlspecialchars($_POST['confirmNewPassword']);

        if ($newPassword == $newConfirmPassword) {

            $recupUser = $conn->prepare('SELECT * FROM `utilisateurs` WHERE login = ?');
            $recupUser->execute(array($newLogin));
            
            $foundLogin = ($recupUser->rowCount() > 0);
            
            $sameLogin = ($newLogin == $login);

    
            if ($sameLogin) {

                // just update the passwords
                $updatePassword = $conn->prepare("UPDATE `utilisateurs` SET `password`= ? WHERE id = ?");
                $updatePassword->execute(array($hashPassword, $id));
                echo "<p class='success-msg'>Password updated successfully</p>";

            } elseif (!$foundLogin) {
                $updatePassword = $conn->prepare("UPDATE `utilisateurs` SET `login` = ?,  `password` = ? WHERE id = ?");
                $updatePassword->execute(array($newLogin, $hashPassword, $id));
                echo "<p class='success-msg'>Profile updated successfully</p>";


            }else {
                echo "<p class='err-msg'>Ce login existe déjà</p>";
            }
                
            
    
    
            // echo "<br>login => $login & newLogin => $newLogin <br>";
            // echo "found login ? " . json_encode($foundLogin) . "<br>";
            // echo "same login ? " . json_encode($sameLogin) . "<br>";
    
            // if($foundLogin && $newLogin != $login) {
            //     echo "<p class='err-msg'>Ce login existe déjà</p>";
               
            // }
            
            
            //  if($newPassword == $newConfirmPassword) {
                
                //         $newPassword=password_hash($newPassword, PASSWORD_DEFAULT);
                //         $updateUser = $conn->prepare("UPDATE `utilisateurs` SET `login` = ?, `password`= ? WHERE id = ?");
                //         $updateUser->execute(array($newLogin, $newPassword, $id));
                
                //         if($updateUser){
                    //             echo "<p class='success-msg'>Profile updated successfully</p>";
                        
            //         }
    
            //     } else {
            //         echo "<p class='err-msg'>Passwords do not match</p>";
            //     }



        }else {
            echo "<p class='err-msg'>Passwords do not match</p>";
        }
        

    } else {
       echo "<p class='err-msg'>Veuillez completer tous les champs...</p>";
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
        <title>Module-connexion</title>
    </head>

<body>
    
    <header>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="reservation-form.php">Reservation</a>
            <a href="planning.php">Planning</a>
            <a href="connexion.php?deco">Déconnection</a>
        </nav>
    </header>
   
    <main> 
    <h2>Modifiez votre profil</h2>

    <form action="" method="post" id="form" class="topBefore" >  <!--The form with method "post" ---->
        <input id="login" name="login" placeholder="Login" type="text" value="<?php echo (isset($newLogin)) ? $newLogin : $login; ?>" ><br> 
        <input id="password" name="newPassword" placeholder="New Password" type="password" ><br>
        <input id="password" name="confirmNewPassword" placeholder="Confirm New Password" type="password" ><br>
        
        <input id="submit" type="submit" name="modifier" value="Modifier"><br>

    </form>

</main>



</body>
</html>