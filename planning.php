<?php

session_start();
include('config.php');

// $timezone = new DateTimeZone('Europe/Paris');
// $date = new DateTime("now", $timezone);

// define a `timezone` variable
$timezone = 'Europe/Paris';
// set the default timezone of `date()` and `time()` functions
date_default_timezone_set($timezone);


// If there is no 'week' in our URL search
if (!isset($_GET['week'])) {
    // get the current week number
    $week_number = date('W'); // returns eg. 51
    // redirect to the same planning page with the week number
    header("Location: planning.php?week=$week_number");
    exit(); // <- we exit now to avoid unwanted behaviors
}

// use the week number from GET
$week_number = $_GET['week']; // returns eg. 51
// get the current / today's week number again
$today_week_number = date('W');

// calculate the previous week number
$prev_week_number = $week_number - 1;
// calculate the next week number
$next_week_number = $week_number + 1;

// DEBUG (1): tell us about the `week_number`
// echo "week_number => " . $week_number . "<br>";



// IDEA #1: We want to get the start and end date of a week using the `week_number`
//       For example, say we are in week 51 of 2022, the start date should be '2022-12-19'
//       and the end date should be '2022-12-25' (hooray!!! Christmas!!)


// First of all, let's define our `year_number` variable
$year_number = date('Y'); // returns eg. 2022

// Now, create a new DateTime object as "dateObj" 
$dateObj = new DateTime(); // <- this defaults to "now"
// Set the timezone again of our $dateObj to 'europe/paris'
$dateObj->setTimezone(new DateTimeZone($timezone));

// change the $date to first day of week of year, using the `setISODate()` method
$dateObj->setISODate($year_number, $week_number);

// get the start date from $dateObj in 'YYYY-MM-DD'-like format
$start_date = $dateObj->format('Y-m-d');
// add 6 days to our date object (i.e. $dateObj), using the `modify()` method
$dateObj->modify('+6 days');
// get the end date from $dateObj in 'YYYY-MM-DD'-like format
$end_date = $dateObj->format('Y-m-d');

// While we're at it, why don't we get today's date too ?
// Let's name today's date variable as `today_date`` :)
$today_date = date('Y-m-d');


// DEBUG (2): tell us about our precious dates
// echo "today_date => " . $today_date . "<br>";
// echo "start_date => " . $start_date . "<br>";
// echo "end_date => " . $end_date . "<br>";


// IDEA #2: Now that we have our dates, we would like to get a list of all the dates of this particular week.
//          This list or array must start with our `$start_date` and end with `$end_date``

// create an interval of 1 day
$interval = DateInterval::createFromDateString('+1 day');
// Use the interval to generate a list of dates, 
// and name the list of dates from start to end as 'daterange'
$daterange = new DatePeriod(date_create($start_date), $interval, date_create($end_date . '+1 day'));

// create a real dates array
$real_dates = []; // returns eg. ['2022-12-19', '2022-12-20', '2022-12-21', ...
// create a short dates array
$short_dates = []; // returns eg. ['Mon. 19', 'Tue. 20', 'Wed 23', ...

// for each current date in `daterange`
foreach ($daterange as $current_date) {
    // add dates with 'YYYY-MM-DD'-like format to the `real_dates` list
    $real_dates[] = $current_date->format('Y-m-d');
    // add dates with 'D. d'-like format to `real_dates` list
    $short_dates[] = $current_date->format('D. d');
}

// DEBUG (3): tell us about our real and short dates list
// echo "real_dates: ";
// var_dump($real_dates);
// echo "short_dates: ";
// var_dump($short_dates);

// $week = 2;

// $year = 2022;


// Retreiving all the reservations from our database between 'start_date' and 'end_date'...

$start_datetime = $start_date . " 00:00:00"; // returns eg. '2022-12-19 00:00:00'
$end_datetime = $end_date . " 00:00:00"; // returns eg. '2022-12-25 00:00:00'

$query = "SELECT `reservations`.id, `titre`, `description`, `debut`, `fin`, `id_utilisateur`, `login` FROM `reservations` 
INNER JOIN  `utilisateurs`
ON reservations.id_utilisateur = utilisateurs.id
WHERE debut >= '$start_datetime' AND fin <= '$end_datetime'";
// $query = "SELECT * FROM `reservations`";
$res = $conn->query($query);

$reservations = $res->fetchAll(PDO::FETCH_ASSOC);

// echo "reservations: <br>";
// var_dump($reservations);




/**
 * Checks if the given date is a saturday or sunday.
 * 
 * @param string $date - example: '2022-12-12'
 * @return bool - Returns TRUE if the date is 'Sat' or 'Sun'
 */
function weekend($date) {
    // let's get the name of the day
    $day = date('D', strtotime($date)); // returns eg. 'Mon'

    return ($day == 'Sat' || $day == 'Sun');
}


/**
 * Returns the reservation with the given date.
 * 
 * @param string $datetime - example: '2022-12-12 10:00:00'
 * @param array $reservations - The list of reservations 
 * 
 * @return array - The specific reservation as an associative array (eg. ['titre' => "Petit Dej", 'login' => "katia" ...])
 */
function getReservationByDate($datetime, $reservations) {
    // create a result variable
    $result = [];

    // for each reservation in the `reservations` list...
    foreach ($reservations as $reservation) {
        // ...if our datetime is 'debut' is greater or equal to 'debut' from reservation AND lesser or equal to 'fin'...
        if ($datetime >= $reservation['debut'] && $datetime <= $reservation['fin']) {
            // ...add the reservation to the result list
            $result[] = $reservation;

            // stop the foreach loop immediately
            break;
        }
    }

    // return the result
    return $result;
}



// testing our `getReservationByDate()` function...
// $found_reservation = getReservationByDate('2022-12-19 09:00:00', $reservations);

// echo "found_reservation:";
// var_dump($found_reservation);

// var_dump(weekend('2022-12-12'));



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

    <div class="controls">
        <a href="?week=<?= $prev_week_number ?>"><button>Previous Week</button></a>
        <a href="?week=<?= $today_week_number ?>"><button>Today</button></a>
        <a href="?week=<?= $next_week_number ?>"><button>Next Week</button></a>
    </div>

    <table class="calender">
        <thead>
            <tr>
                <td>Horraires</td>

                <?php foreach($short_dates as $index => $day):?>
                <td <?= ($real_dates[$index] == $today_date) ? 'active' : '' ?> ><?= $day ?></td>
                <?php endforeach; ?>
            </tr>
        </thead>



        <tbody>
            <?php for($h = 8; $h < 19; $h++): ?>
            <tr>
                <td><?= $h . "h - " . ($h + 1) . "h" ?></td>
    
                <?php for ($j = 0; $j < 7; $j++):?>



                    <?php if (!weekend($real_dates[$j])) : ?>

                        <td>      

                            <?php

                            $hour = ($h < 10) ? "0$h" : $h; // returns eg. '09' if $h is 9

                            $date = $real_dates[$j];
                            $time = $hour .":00:00";

                            
                            $datetime = $date . " " . $time;

                            // get the current reservation with this `datetime``
                            $found_reservation = getReservationByDate($datetime, $reservations);
                            // check if the user is connected
                            $userConnected = isset($_SESSION['id']);

                            // var_dump($datetime);
                            // var_dump($found_reservation);
                            
                            ?>


                            <?php if ($found_reservation) : ?>
                            
                                <a href="reservation.php?id=<?= $found_reservation[0]['id'] ?>" title="<?= $datetime ?>">
                                    <span class="login"><?= $found_reservation[0]['login'] ?></span>
                                    <span class="titre"><?= $found_reservation[0]['titre'] ?></span>
                                </a>

                            <?php elseif ($userConnected) : ?>
                                <a href="reservation-form.php?date=<?= $date ?>&time=<?= $time ?>" class="empty-res" title="<?= $datetime ?>">
                                    <p>Réserver</p>
                                </a>
                            <?php endif; ?>

                        </td>

                        
                    <?php else : ?>
                        
                    <td disabled>reservation non disponible</td>
                    
                    <?php endif; ?>
                            
          
                        
                <?php endfor; ?>
    
            </tr>
            <?php endfor; ?>

        </tbody>




    </table>
</div>

</body>
</html>
