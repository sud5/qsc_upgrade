<?php
require_once(dirname(__FILE__) . '/../../config.php');

global $CFG, $PAGE, $USER, $DB;
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin') {
    redirect("/");
}
$gradeStr= "";
if ($_REQUEST['gname'] != '' && $_REQUEST['quizid'] != '') {
    $quizid      = $_REQUEST['quizid'];
    $gname      = $_REQUEST['gname'];
    $gnameArr = explode("(", $gname);
    $gnameArr1 = explode(")", $gnameArr[1]);
 
    //echo "<pre>"; print_r($gnameArr1[0]); exit;
    $strEmail = $gnameArr1[0];
    //$DB->set_debug(true);
    $gtadeRes = $DB->get_record_sql("SELECT quizg.* FROM  mdl_quiz_grades quizg RIGHT JOIN mdl_user us ON quizg.userid= us.id WHERE us.username = '".$strEmail."' AND quiz= '".$quizid."'  ORDER BY quizg.id DESC");
    //$DB->set_debug(false);
    if (!empty($gtadeRes)) {
        $gradeStr= $gtadeRes->grade;    
    }
}
echo number_format($gradeStr,2);
die;