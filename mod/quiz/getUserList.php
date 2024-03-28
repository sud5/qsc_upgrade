<?php
require_once('../../config.php');

global $CFG, $PAGE, $USER, $DB;
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin') {
    redirect("/");
}
$responce = array();
$group_id = 0;
$pcourseCount =0;
if ($_GET['q'] != '') {
        $search = $_GET['q']; 
        $course =$_GET['course'];
        $users = $DB->get_records_sql("SELECT u.id, u.firstname, u.lastname, u.username FROM {user} u LEFT JOIN {user_enrolments} ue ON ue.userid = u.id 
            JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = '".$course."' )
            where  u.deleted=0 and u.firstname!='' AND (CONCAT_WS(  ' ', u.firstname, u.lastname ) LIKE '%".$search."%' OR  u.username LIKE '%".$search."%' OR  u.email LIKE '%".$search."%' ) GROUP BY u.id ORDER BY u.id desc LIMIT 0,100");
        global $rs;
        //PP0 end
        $resultArr = array();
        $totalCount = count($users);
        foreach ($users as $keyuser) {            
            //PP0 start 
            $fname = str_replace("?", "", $keyuser->firstname);
            $lname = str_replace("?", "", $keyuser->lastname);
            $fname = str_replace("'", "", $fname);
            $lname = str_replace("'", "", $lname);
            //PP0 end
            
                //$options[$keyuser->id] = $keyuser->firstname . " " . $keyuser->lastname;
            $fordb                    = new stdClass();
            //$fordb->_type                = "user";
            $fordb->id                = $keyuser->id;
            $fordb->name = $fname . " " . $lname . " (" . $keyuser->username . ")";
            $resultArr[]= $fordb;
            unset($fordb);
            
        }
    $responce['query'] =$_GET['q'];
    $responce['total'] =$totalCount;
    $responce['results'] = $resultArr;
}
header('Content-Type: application/json');
echo json_encode(array("status"=>"success","data"=>$responce));
die;