<?php
require_once('../../config.php');
$graderValue    = $_REQUEST['grader_val'];
$graderValueArr = json_decode($graderValue);
$grader_id      = $graderValueArr[0];
$studentid      = $graderValueArr[1];
$examid         = $graderValueArr[2];
$flagNetworkError = 0;

$select_header = "SELECT * FROM `mdl_assign_graders` where exam_id = $examid AND student_id = $studentid";
$graderPrevObjData = $DB->get_record_sql($select_header);

if (!$suser = $DB->get_record('user', array('id'=>$studentid),"id,username,email,firstname,lastname")) {

    $flagNetworkError=1;
}

$exam_query = "SELECT cm.id as cmid, c.id, cm.instance, a.course, a.name, c.fullname FROM {course_modules} as cm JOIN {assign} as a ON cm.instance = a.id JOIN {course} as c ON a.course=c.id WHERE cm.id = $examid";
$examExistObjData = $DB->get_record_sql($exam_query);
$assignmentName = format_string($examExistObjData->name);
$courseName = format_string($examExistObjData->fullname);

//Delete into mdl_assign_graders table start
$delete_header = "DELETE FROM `mdl_assign_graders` WHERE exam_id = $examid AND student_id = $studentid";
$temp = $DB->execute($delete_header);
if($graderPrevObjData){
    if($graderPrevObjData->grader_id != $grader_id && $graderPrevObjData->grader_id!=0){
        // $delete_header = "DELETE FROM `mdl_assign_graders` WHERE exam_id = $examid AND student_id = $studentid";
        // $temp = $DB->execute($delete_header);
        //var_dump($temp); die;
        if($temp === true){
            //Insert into mdl_assign_graders table start
            $insert_header = "INSERT INTO `mdl_assign_graders` (`grader_id`, `exam_id`, `student_id`) VALUES ($grader_id, $examid, $studentid)";
            $temp1 = $DB->execute($insert_header);
        }
    }else{
         $insert_header = "INSERT INTO `mdl_assign_graders` (`grader_id`, `exam_id`, `student_id`) VALUES ($grader_id, $examid, $studentid)";
        $temp1 = $DB->execute($insert_header);

    }
}else{
    $insert_header = "INSERT INTO `mdl_assign_graders` (`grader_id`, `exam_id`, `student_id`) VALUES ($grader_id, $examid, $studentid)";
    $temp1 = $DB->execute($insert_header);
}
//Delete Mail Sent to Previous Grader (if different grader assigned)

if($graderPrevObjData){
    if($graderPrevObjData->grader_id != $grader_id && $graderPrevObjData->grader_id!=0){
        //Delete Mail functionality start
        if (!$user = $DB->get_record('user', array('id'=>$graderPrevObjData->grader_id),"id,username,email,firstname,lastname")) {
            //cli_error("Can not find user");
            $flagNetworkError=1;
        }
        $subject = 'Admin removed as a Grader';

        $messagehtml = "Dear $user->firstname,<br><br>
                        Admin removed you as a Grader for a $suser->firstname $suser->lastname to you for $courseName::$assignmentName.
                        <br><br>
                        If you have any issues, questions or concerns, please email <a href='mailto:qsc.training@qsc.com'>qsc.training@qsc.com</a>.<br> <br>
                        Cheers, <br>
                        The QSC Training & Education Team <br>
                        <a href='".$CFG->wwwroot."'>www.training.qsc.com</a>";
        $messagetext = $messagehtml;
        $messagehtml = text_to_html($messagehtml, false, false, true);
        $user->mailformat = 1;  // Always send HTML version as well.
        $from="qsctraining@qscaudio.com";

        if(!email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
            $insert_step4 = "INSERT INTO `log_assign_graders` (`request_step`,`grader_id`,`student_id`,`exam_id`,`request_data`) VALUES ('Step 4','$grader_id','$studentid','$examid','Can not sent email to user from Admin removed as a Grader')";
            $step4 = $DB->execute($insert_step4);
            //cli_error("Can not sent email to user");
            $flagNetworkError=2;
            //echo "error";
        }
    }
}

//Insert mail functionality start
if($grader_id != 0){
    if (!$user = $DB->get_record('user', array('id'=>$grader_id),"id,username,email,firstname,lastname")) {
            //cli_error("Can not find user");
            $flagNetworkError=1;
    }
    try{
        $subject = 'Admin assigned as a Grader';

        $messagehtml = "Dear $user->firstname,<br><br>
    Admin added you as a Grader for a $suser->firstname $suser->lastname to you for $courseName::$assignmentName.
    <br><br>
    So log in to <a href='".$CFG->wwwroot."/login'>www.training.qsc.com</a><br><br>
    If you have any issues, questions or concerns, please email <a href='mailto:qsc.training@qsc.com'>qsc.training@qsc.com</a>.<br> <br>
    Cheers, <br>
    The QSC Training & Education Team <br>
    <a href='".$CFG->wwwroot."'>www.training.qsc.com</a>";
        $messagetext = $messagehtml;
        $messagehtml = text_to_html($messagehtml, false, false, true);
        $user->mailformat = 1;  // Always send HTML version as well.
        $from="qsctraining@qscaudio.com";

        if(!email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
            $insert_step6 = "INSERT INTO `log_assign_graders` (`request_step`,`grader_id`,`student_id`,`exam_id`,`request_data`) VALUES ('Step 6','$grader_id','$studentid','$examid','Can not sent email to user from Admin assigned as a Grader')";
            $step6 = $DB->execute($insert_step6);               
            $flagNetworkError=3;
        }
    }catch(exception $e){
        $exception_msg = $e->getMessage();
        $insert_step7 = "INSERT INTO `log_assign_graders` (`request_step`,`grader_id`,`student_id`,`exam_id`,`request_data`) VALUES ('Step 7','$grader_id','$studentid','$examid','$exception_msg')";
        $step7 = $DB->execute($insert_step7);
    }

}
//Insert mail functionality end

$select_chk = "SELECT * FROM `mdl_assign_graders` where grader_id = $grader_id AND exam_id = $examid AND student_id = $studentid";
$graderPrevObjDataChk = $DB->get_record_sql($select_chk);
if(empty($graderPrevObjDataChk)){
    $flagNetworkError = 4;
}
echo $flagNetworkError;
die;
//Insert into mdl_assign_graders table ends