<?php

session_start();
include('config.php');

$timezone = new DateTimeZone('Europe/Paris');
$date = new DateTime("now", $timezone);

echo $date->format('Y/m/d H:i'); // returns eg. '2022/12/19 18:23'


$date_format = 'Y-m-d';

//$any_date = date_create('2023-10-10');
$any_date = date($date_format); 
//$any_date = '2022-07-25'; 

$today = date($date_format, strtotime($any_date));

$startWeek = date($date_format, strtotime('last mon', strtotime($today)));
$endWeek = date($date_format, strtotime('next sun', strtotime($today)));

if (date('D', strtotime($today)) == 'Mon') {
    $startWeek = $today;
}

if (date('D', strtotime($today)) == 'Sun') {
    $endWeek = $today;
}


$interval = DateInterval::createFromDateString('1 day');

$start_date = date_create($startWeek);
$end_date = date_create($endWeek . '+1 day');

$daterange = new DatePeriod($start_date, $interval, $end_date);

echo "<br>";
//echo "any_date => " . $any_date->format('D.d,Y') . "<br>";
echo "today => " . $today . "<br>";
echo "startWeek => " . $startWeek . "<br>";
echo "endWeek => " . $endWeek . "<br>";

$real_dates = []; // returns eg. ['2022-12-19', '2022-12-20', '2022-12-21', ...
$short_dates = []; // returns eg. ['Mon. 19', 'Tue. 20', 'Wed 23', ...

foreach ($daterange as $current_date) {
    $real_dates[] = $current_date->format('Y-m-d');
    $short_dates[] = $current_date->format('D. d');
}

var_dump($real_dates);
var_dump($short_dates);

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

        <thead>
            <tr>
                <td>Horraires</td>

                <?php foreach($days as $day):?>
                <td><?= $day ?></td>
                <?php endforeach; ?>
            </tr>
        </thead>



        <tbody>
            <?php for($h = 8; $h <= 18; $h++): ?>
            <tr>
                <td><?= $h . "h - " . ($h + 1) . "h" ?></td>
    
                <?php for ($j = 1; $j <= 7; $j++):?>
                <td><?= "jour $j" ;?></td>
                <?php endfor; ?>
    
            </tr>
            <?php endfor; ?>

        </tbody>




    </table>
</div>

</body>
</html>
