<?php
    require_once('../../config.php');
    
    $userArr2 = $_REQUEST['waitlistOrderArr'];   

    $i=1;
    foreach($userArr2 as $key => $value) {
        $update_header = "UPDATE mdl_facetoface_signups_status SET display_order = $i WHERE createdby = $value";
        $temp1 = $DB->execute($update_header); 
        $i++;
    }         

/*  
    $userArr = json_decode($_REQUEST['userArr']);
    $waitlistUserArr = array();
    foreach ($userArr as $key => $value) {
       $waitlistUserArr[$value] = $value;
    }

    $waitlistUserStr = implode(',', $waitlistUserArr);
    
    $waitlistuser = "SELECT id, signupid, createdby, display_order FROM mdl_facetoface_signups_status WHERE statuscode = 60 AND superceded = 0 AND createdby IN (".implode(',', $waitlistUserArr).")";
    $waitlistuserData = $DB->get_records_sql($waitlistuser);*/

    