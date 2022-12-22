<?php

session_start();
include('config.php');



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=style.css href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/28029eb12b.js" crossorigin="anonymous"></script>

</head> 
    <title>Planning</title>
</head>

<body>

<div class="dynamic-calender">
    <table class="calender">
        <?php
        $days = [
            "Lundi",
            "Mardi",
            "Mercredi",
            "Jeudi",
            "Vendredi",
            "Samedi",
            "Dimanche"
        ];?>
        <?php for($h = 8; $h < 18; $h++); ?>
        <tr>
        <?php for ($j = 0; $j < 7; $j++);?>
       <?php if ($h == 8);?>
           <td><?= $days[$j];?></td>
  
        
     <td><?= $h ?></td>
        </tr>
    </table>
</div>

</body>
</html>
