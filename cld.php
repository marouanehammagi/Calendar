<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calendar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Calendar</h1>
    <div class="calendar">
        <?php
       
            // Get current month and year
            $currentMonth = date('m');
            $currentYear = date('Y');
            // $_GET['month'] = $currentMonth;
            // $_GET['year'] = $currentYear;
            // Check if there's a GET parameter for month and year+
            if (isset($_GET['month']) && isset($_GET['year'])) {
                if($_GET['month']!= $currentMonth){
                    $_GET['month'] = $currentMonth;
                    $_GET['year'] = $currentYear;
                }
            }
            if (isset($_GET['month']) && isset($_GET['year'])) {
                $month = (int)$_GET['month'];
                $year = (int)$_GET['year'];
            } else {
                $month = $currentMonth;
                $year = $currentYear;
            }

            // Validate month and year
            if ($month < 1 ) {
                $month = 12;
                $year = $year - 1;
            }else if( $month > 12){
                $month = 1;
                $year = $year + 1;
            }

            // Create calendar
            echo createCalendar($month, $year);
            $months = [
                "Januar",
                "Februar",
                "März",
                "April",
                "Mai",
                "Juni",
                "Juli",
                "August",
                "September",
                "Oktober",
                "November",
                "Dezember",
            ];
            
            var_dump($month);
        
        ?>
    </div>
    <div class="navigation">
        <h1><?=$months[(int)$month-1]."-".$year;?></h1>
        <a href="?month=<?php echo $month - 1; ?>&year=<?php echo $year; ?>">Previous Month</a>
        <a href="?month=<?php echo $currentMonth; ?>&year=<?php echo $currentYear; ?>">Today</a>
        <a href="?month=<?php echo $month + 1; ?>&year=<?php echo $year; ?>">Next Month</a>
    </div>
</body>
</html>

<?php

function createCalendar($month, $year) {
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('d', mktime(0, 0, 0, $month + 1, 0, $year));
    $dayOfWeek = date('w', $firstDay);

    $calendar = '<table>';
    $calendar .= '<thead><tr>';
    $calendar .= '<th>Sun</th>';
    $calendar .= '<th>Mon</th>';
    $calendar .= '<th>Tue</th>';
    $calendar .= '<th>Wed</th>';
    $calendar .= '<th>Thu</th>';
    $calendar .= '<th>Fri</th>';
    $calendar .= '<th>Sat</th></tr></thead>';

    $calendar .= '<tbody><tr>';

    // Add empty cells for the first week
    for ($i = 0; $i < $dayOfWeek; $i++) {
        $calendar .= '<td></td>';
    }

    // Loop through days
    for ($i = 1; $i <= $daysInMonth; $i++) {
        if ($i == 1 && $dayOfWeek != 0) {
            $calendar .= '</tr>';
        }

        $calendar .= '<td>' . $i . '</td>';

        if (($i + $dayOfWeek - 1) % 7 == 0) {
            $calendar .= '</tr>';
        }
    }

    // Add empty cells for the last week
    if (($i + $dayOfWeek - 1) % 7 != 0) {
        for ($i = 1; ($i + $dayOfWeek - 1) % 7 != 0; $i++) {
            $calendar .= '<td></td>';
        }
    }

    $calendar .= '</tr></tbody>';
    $calendar .= '</table>';

    return $calendar;
}
