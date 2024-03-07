<?php
class Calendar {
    private $dayLabels = ["Mo","Di","Mi","Do","Fr","Sa","So"];
    private $currentYear = 0;
    private $currentMonth = 0;
    private $days = 0;
    private $naviHref = null;

    public function __construct() {
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }

    public function show() {
        $year = $_GET['year'] ?? date("Y", time());
        $month = $_GET['month'] ?? date("m", time());

        $this->currentYear = $year;
        $this->currentMonth = $month;
        $this->days = $this->_days($month, $year);

        $content = '<body>' .
                   '<div id="calendar">' .
                   '<div class="box">' .
                   $this->_createNavi() .
                   '</div>' .
                   '<div class="box-content">' .
                   '<ul class="label">'.$this->_createLabels().'</ul>'.
                   '<div class="clear"></div>'.
                   '<ul class="dates">';

        $weeks = $this->_weeks($month, $year);

        for ($i = 0; $i < $weeks; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $content .= $this->_showDay($i * 7 + $j);
            }
        }

        $content .= '</ul>' .
                    '<div class="clear"></div>' .
                    '</div>' .
                    '</div>' .
                    '</body>';

        return $content;
    }

    private function _showDay($Number) {
        $firstDayOfTheMonth = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));
        $daysInPreviousMonth = date('t', strtotime('-1 month', strtotime($this->currentYear . '-' . $this->currentMonth . '-01')));
        $daysInCurrentMonth = date('t', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));
        $dayNumber = $Number - $firstDayOfTheMonth + 1;

        if ($dayNumber <= 0) {
            $displayDay = $daysInPreviousMonth + $dayNumber;
            $displayMonth = $this->currentMonth - 1;
            $displayYear = $this->currentYear;
            $numberClass = 'prev-month';
        } elseif ($dayNumber > $daysInCurrentMonth) {
            $displayDay = $dayNumber - $daysInCurrentMonth;
            $displayMonth = $this->currentMonth + 1;
            $displayYear = $this->currentYear;
            $numberClass = 'next-month';
        } else {
            $displayDay = $dayNumber;
            $displayMonth = $this->currentMonth;
            $displayYear = $this->currentYear;
            $numberClass = '';
        }

        $currentDate = sprintf('%04d-%02d-%02d', $displayYear, $displayMonth, $displayDay);
        $numberContent = $displayDay;

        if ($currentDate == date('Y-m-d')) {
            $numberClass .= ' today';
        }

        return '<li id="li-' . $currentDate . '" class="' . trim($numberClass) . '">' . $numberContent . '</li>';
    }

    private function _createNavi() {
        $nextMonth = $this->currentMonth == 12 ? 1 : $this->currentMonth + 1;
        $nextYear = $this->currentMonth == 12 ? $this->currentYear + 1 : $this->currentYear;
        $preMonth = $this->currentMonth == 1 ? 12 : $this->currentMonth - 1;
        $preYear = $this->currentMonth == 1 ? $this->currentYear - 1 : $this->currentYear;
        $currentDate = date('Y-m-d');

        return '<div class="hammagi_header">' .
               '<div class="hammagi_nex_prev">' .
               '<a class="prev" href="' . $this->naviHref . '?month=' . sprintf('%02d', $preMonth) . '&year=' . $preYear . '"><</a>' .
               '<a class="next" href="' . $this->naviHref . '?month=' . sprintf("%02d", $nextMonth) . '&year=' . $nextYear . '">></a>' .
               '<a class="today" href="' . $this->naviHref . '?date=' . $currentDate . '">Heute</a>' .
               '</div>' .
               '<div class="hammagi_date">' .
               '<span>' . date('Y M', strtotime($this->currentYear . '-' . $this->currentMonth . '-1')) . '</span>' .
               '</div>' .
               '</div>';
    }

    private function _createLabels() {
        $content = '';

        foreach ($this->dayLabels as $index => $label) {
            $content .= '<li class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</li>';
        }

        return $content;
    }

    private function _weeks($month = null, $year = null) {
        if (null == ($year)) {
            $year = date("Y", time());
        }

        if (null == ($month)) {
            $month = date("m", time());
        }

        $days = $this->_days($month, $year);
        $numOfweeks = ($days % 7 == 0 ? 0 : 1) + intval($days / 7);
        $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $days));
        $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

        if ($monthEndingDay < $monthStartDay) {
            $numOfweeks++;
        }

        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    private function _days($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());
             
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
     
}