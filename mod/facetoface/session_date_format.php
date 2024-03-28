<?php
    require_once('../../config.php');

        // GET YEAR OF START AND END DATE
        $startDateOfYear = date('Y',$timestartStr);
        $endDateOfYear = date('Y',$timefinishStr);
        
        // GET MONTH OF START AND END DATE
        $startDateOfMonth = date('m',$timestartStr);
        $endDateOfMonth = date('m',$timefinishStr);

        // GET DAY OF START AND END DATE
        $startDateOfDay = date('d',$timestartStr);
        $endDateOfDay = date('d',$timefinishStr);

        if($startDateOfYear != $endDateOfYear){
            // December 29th, 2017 - January 3rd, 2018
            //echo "<br>YEAR: ".$startDateOfYear." - ".$endDateOfYear;
            $datestart = $dateend = '';
            $datestart = date('F j',$timestartStr);
            $datestart .='<sup>';
            $datestart .= date('S',$timestartStr);
            $datestart .='</sup>';
            $datestart .= date(', Y',$timestartStr);

            $dateend = date('F j',$timefinishStr);
            $dateend .='<sup>';
            $dateend .= date('S',$timefinishStr);
            $dateend .='</sup>';
            $dateend .= date(', Y',$timefinishStr);

        }
        else if($startDateOfMonth != $endDateOfMonth){
            // November 30th, 2017 - December 3rd, 2018 OR November 30th - December 3rd, 2017 (IF YEAR SAME)
            //echo "<br>MONTH: ".$startDateOfMonth." - ".$endDateOfMonth;
            $datestart = $dateend = '';
            $datestart = date('l F j\<sup>S\</sup>',$timestartStr);
            $datestart = date('F j',$timestartStr);
            $datestart .='<sup>';
            $datestart .= date('S',$timestartStr);
            $datestart .='</sup>';

            $dateend = date('F j',$timefinishStr);
            $dateend .='<sup>';
            $dateend .= date('S',$timefinishStr);
            $dateend .='</sup>';
            $dateend .= date(', Y',$timefinishStr);

        } else if($startDateOfDay != $endDateOfDay){
            // November 29th, 2017 - 30th, 2017
            //echo "<br>DAY: ".$startDateOfDay." - ".$endDateOfDay;
            $datestart = $dateend = '';
            $datestart = date('F j',$timestartStr);
            $datestart .='<sup>';
            $datestart .= date('S',$timestartStr);
            $datestart .='</sup>';

            $dateend = date('j',$timefinishStr);
            $dateend .='<sup>';
            $dateend .= date('S',$timefinishStr);
            $dateend .='</sup>';
            $dateend .= date(', Y',$timefinishStr);

        }else if($startDateOfDay == $endDateOfDay){
            // November 29th, 2017
            //echo "<br>"." Same Day - ".$endDateOfDay;
            $datestart = $dateend = '';
            $datestart = date('F j',$timestartStr);
            $datestart .='<sup>';
            $datestart .= date('S',$timestartStr);
            $datestart .='</sup>';
            $datestart .= date(', Y',$timestartStr);

            $dateend = '';
        }

?>