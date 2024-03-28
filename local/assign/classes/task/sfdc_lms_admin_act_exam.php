<?php
/*added by @shiva goud@qsc*/
namespace local_assign\task;

/**
 * An example of a scheduled task.
 */
class sfdc_lms_admin_act_exam extends \core\task\scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('sfdc_lms_admin_act_exam', 'local_assign');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB,$USER,$CFG,$COURSE;
        //sfdc settings start
        require_once($CFG->dirroot . '/local/assign/sfdclib.php');
        require_once($CFG->dirroot . '/my/lib.php');
        mtrace('cron started');
        //logic
        $sqlc3 = "SELECT id as course, fullname,course_version,timecreated FROM {course}";
        $data = $DB->get_records_sql($sqlc3);
        $curent_timestamp = strtotime("NOW");
        echo $diffTimestampExam = $diffTimestampExamOrUser = $curent_timestamp - 820000; // 8 days approx later;

        $febtimestamp=1580613549;
        $octtimestamp=1602913549;
        $this->cron_job($data,$febtimestamp,$octtimestamp);
        mtrace('cron ended');
    }
    /**
     * main fn.
     */
    public function cron_job($data,$param1,$param2){
        update_certificate_details($submissionRes->userid);
        global $DB,$USER,$CFG,$COURSE;
        //FOR CLASSROOM EXAM

        foreach($data as $keyCC){
            $sqlsfdc12 = 'SELECT a.id as assignmentid, cm.id, cm.section, cm.instance FROM {course_modules} cm JOIN {assign} a ON cm.instance=a.id WHERE a.type=? and cm.course = ? AND cm.module = ?';
            $rscsfdc12 = $DB->get_record_sql($sqlsfdc12, array("classroom",$keyCC->course,  1));
            $classFlag=0;
            if($rscsfdc12){
                //NM start
                $submissionChkQuery = "SELECT id, status, timemodified,userid,assignment FROM {assign_submission} WHERE timemodified >= $diffTimestampExam AND assignment=" . $rscsfdc12->instance . " order by id desc";
                $submissionResult = $DB->get_records_sql($submissionChkQuery);
                    
            }
            if($submissionResult){
                foreach ($submissionResult as $submissionRes) {
                    $timemofiedend = $submissionRes->timemodified;
                    $insert_header = "INSERT INTO {sfdc_cron_log_exam_cls} (userid, courseid, asid, timemodified) VALUES (".$submissionRes->userid.", ".$keyCC->course.", '".$submissionRes->id."', '".$timemofiedend."')";
                    $DB->execute($insert_header);
                    //Get username $key->username
                    $sqlc3 = "SELECT id, username FROM {user} where id=".$submissionRes->userid;
                    $key = $DB->get_record_sql($sqlc3);
                    echo $sfdc_contact_id = show_contact_id($key->username);
                    $show_sfdc_course = sfdc_show_courses($sfdc_contact_id, $keyCC->course);
                    echo "\n TestCount4 ".$kj++." \n";
                    if($show_sfdc_course){
                        $course_sfdc_id = $show_sfdc_course['records'][0]['Id'];
                    }
                    else{
                        $course_sfdc_id='';
                    }
                    if(empty($sfdc_contact_id)){
                        continue;
                    }
                    if(empty($course_sfdc_id) and !empty($sfdc_contact_id)){
                        echo "\n TestCount4Check cehk 111  \n";
                        $dot = date("Y-m-d",$keyCC->timecreated);
                        echo "\n TestCount4Check cehk  \n";
                        $course_sfdc_created_id = sfdc_create_course($sfdc_contact_id, format_string($keyCC->fullname), $keyCC->course, "classroom",$dot);
                        echo "\n";
                        echo "\n TestCount51 ".$kj++." \n";  
                        $show_sfdc_course = sfdc_show_courses($sfdc_contact_id, $keyCC->course); 
                                    $course_sfdc_id = $show_sfdc_course['records'][0]['Id'];                    
                    }

                    $sfdc_course_type = $show_sfdc_course['records'][0]["course_version_type__c"];
                    
                    $classFlag=1;

                    if($submissionRes->status == "submitted"){
                        $exam_submission_status="Yes";
                        $exam_time_modified = date('m/d/Y h:i:s',$submissionRes->timemodified);
                        $exam_date_modified = date('Y-m-d', $submissionRes->timemodified);
                    }
                    elseif($submissionRes->status == "reopened"){
                        $exam_submission_status="Revision";
                        $exam_time_modified = "N/A";
                    }
                    else{
                        $exam_submission_status="No";
                        $exam_time_modified = "N/A";
                    }
                    echo "Test";
                    $sqlsfdc1 = "SELECT attemptnumber, grade, id FROM {assign_grades} WHERE userid = $submissionRes->userid AND assignment=" . $submissionRes->assignment . " order by id desc limit 0,1";
                    $rscsfdc1 = $DB->get_record_sql($sqlsfdc1);
                    //echo "<pre>"; print_r($rscsfdc1); exit;
                    if ($rscsfdc1){
                        if($key->qsc_training_id != ""){
                            $sfdc_attemptnumber = $rscsfdc1->attemptnumber;
                        }
                        else{
                            $sfdc_attemptnumber = $rscsfdc1->attemptnumber + 1;
                        }
                        $sfdc_assign_grade = $rscsfdc1->grade;
                    }else{
                        $sfdc_attemptnumber = 0;
                        $sfdc_assign_grade = 0;
                    }

                    $cm_lms_id = $rscsfdc12->id;
                    // Get data
                    $show_sfdc_exam_id = show_sfdc_exam($course_sfdc_id, $cm_lms_id);
                    echo "\n TestCount24 ".$kj++." \n";
                    $exam_design_attempts = $sfdc_attemptnumber;
                    $exam_grade = number_format($sfdc_assign_grade, 2);
                    $instructor_feedback = "";
                    $fchtml="N/A";
                    if($rscsfdc1->id){
                        $sqlsfdc123 = "SELECT id,assignment, grade, commenttext FROM {assignfeedback_comments} WHERE assignment = $submissionRes->assignment AND grade=" . $rscsfdc1->id . " order by id desc limit 0,1";
                        $rscsfdc123 = $DB->get_record_sql($sqlsfdc123);

                        //PP7 start
                        $params12 = array('assignment'=>$submissionRes->assignment, 'userid'=>$submissionRes->userid);
                        $grades12 = $DB->get_records('assign_grades', $params12, 'attemptnumber ASC', '*');
                        $fchtml=0;
                        if(!empty($grades12)){
                            $fchtml = "<table border=2 cellpadding=0 cellspacing=0>";
                            $fchtml .= "<thead><tr>";
                            $fchtml .= "<th colspan=1 rowspan=1>Attempt Number</th>";
                            $fchtml .= "<th colspan=1 rowspan=1>Message</th>";
                            $fchtml .= "<th colspan=1 rowspan=1>Time</th>";
                            $fchtml .= "</tr></thead><tbody>";
                            foreach($grades12 as $grade12){
                                $afc_params = array('grade'=>$grade12->id);
                                $afcObj = $DB->get_record('assignfeedback_comments', $afc_params, '*');
                                $fchtml .= "<tr>";
                                $an = $grade12->attemptnumber + 1;
                                $fchtml .= "<td>$an</td>"; 
                                if($afcObj->commenttext != ''){
                                            $fchtml .= "<td>".$afcObj->commenttext."</td>"; 
                                }
                                else{
                                            $fchtml .= "<td>Empty</td>";
                                }
                                $fchtml .= "<td>".date("m/d/Y h:i:s", $grade12->timecreated)."</td>"; 
                                $fchtml .= "</tr>";
                            }
                            $fchtml .= "</tbody></table>";
                        }   

                        if($fchtml == 0){
                            $instructor_feedback = format_string($rscsfdc123->commenttext);
                        }
                        else{
                            $instructor_feedback = $fchtml;
                        }
                    }else{
                        $instructor_feedback = "";
                    }
                    $instructor_feedback=$fchtml;
                    //PP7 end
                    $sec = $DB->get_record_sql('select name from {course_sections} where id=?', array(
                        $rscsfdc12->section
                    ));
                    //$module_exam_name = format_string($sec->name) . " : Classroom";
                    //New task
                    $sqlc3 = "SELECT id, fullname FROM {course} WHERE id = $keyCC->course";
                    $rsc32 = $DB->get_record_sql($sqlc3);

                    $module_exam_name = format_string($rsc32->fullname) . " :: Classroom Exam";

                    //New changes dec 2018 start
                    $sqlSCExam = 'SELECT id, course FROM {simplecertificate} sci WHERE course='.$keyCC->course;
                    $resSCObjData = $DB->get_record_sql($sqlSCExam);                                
                    if(empty($resSCObjData)){
                        continue;
                    }
                    $sqlSCIExam = 'SELECT id, userid, certificateid, timecreated, timeexpired FROM {simplecertificate_issues} sci                  
                            WHERE userid='.$submissionRes->userid.' and certificateid='.$resSCObjData->id.' order by userid desc';              
                    $issueCertCompNotiObjExamDatas = $DB->get_record_sql($sqlSCIExam);
                    if(!empty($issueCertCompNotiObjExamDatas)){
                        $exam_submission_status="Completed";
                    }
                    //New changes dec 2018 END

                    if (empty($show_sfdc_exam_id)){
                        // insert
                        $exam_sfdc_id = sfdc_create_exam($course_sfdc_id, $cm_lms_id, $module_exam_name, $exam_grade, $exam_design_attempts, $exam_time_modified, $instructor_feedback, $exam_submission_status,$sfdc_contact_id,$exam_date_modified, $rscsfdc12->instance, $key->id); ////Task 2524 - passing new argument value
                        $exam_time_modified = "N/A";
                        $exam_date_modified=null;
                        echo "\n TestCount25 ".$kj++." \n";
                        echo "\n";
                        echo $msg20 = "Exam inserted  " . $exam_sfdc_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg20 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                    } //end if sfdc exam
                    else{
                        // update
                        echo "\n";
                        echo $msg21 = "Exam viewed  " . $show_sfdc_exam_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg21 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                        $exam_sfdc_id = sfdc_update_exam($show_sfdc_exam_id, $module_exam_name, $exam_grade, $exam_design_attempts, $exam_time_modified, $instructor_feedback,$exam_submission_status,$sfdc_contact_id,$exam_date_modified,$rscsfdc12->instance, $key->id); ////Task 2524 - passing new argument value
                        $exam_time_modified = "N/A";
                        $exam_date_modified=null;
                        echo "\n TestCount26 ".$kj++." \n";
                        echo "\n";
                        echo $msg22 = "Exam updated  " . $exam_sfdc_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg22 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                    } //end else sfdc exam
                    $instructor_feedback='';

                    //Delete Final exam if exist in SFDC - START
                    $sqlsfdcdel12 = 'SELECT a.id as assignmentid, cm.id, cm.section, cm.instance FROM {course_modules} cm JOIN {assign} a ON cm.instance=a.id WHERE a.type=? AND cm.course = ? AND cm.module = ?';
                    $rscsfdcdel12 = $DB->get_record_sql($sqlsfdcdel12, array(
                            "online",
                            $keyCC->course,
                            1
                        ));
                    $cm_lms_iddel = $rscsfdcdel12->id;
                    $show_sfdc_exam_iddel = show_sfdc_exam($course_sfdc_id, $cm_lms_iddel);
                    echo "\n TestCount27 ".$kj++." \n";
                    if($show_sfdc_exam_iddel){
                        $exam_del_sfdc_id = sfdc_delete_exam($show_sfdc_exam_iddel);
                        echo "\n TestCount28 ".$kj++." \n";
                        echo "\n";
                        echo $msg221 = "Online Exam deleted  " . $exam_del_sfdc_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg221 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                    }           
                    // echo "<pre>";
                 //    print_r($show_sfdc_course); exit;
                } //endif cmcRes
            }   
        }

        $timemofifiedend =time();
        $insert_header = "INSERT INTO {sfdc_cron_log_exam_cls} (userid, courseid, asid, timemodified) VALUES (1000,1000,1000,'".$timemofifiedend."')";
        $DB->execute($insert_header);
        //for //FOR ONLINE EXAM
        $this->online_excute($data,$param1,$param2);
        
    }//@fn ends
    public function online_excute($data,$param1,$param2){
        global $DB,$COURSE,$USER,$SESSION,$CFG;
        foreach($rsc3 as $keyCC){
            $sqlsfdc12 = 'SELECT a.id as assignmentid, cm.id, cm.section, cm.instance FROM {course_modules} cm JOIN {assign} a ON cm.instance=a.id WHERE a.type=? and cm.course = ? AND cm.module = ?';
            $rscsfdc12 = $DB->get_record_sql($sqlsfdc12, array("online",$keyCC->course, 1));
            $classFlag=0;
            if($rscsfdc12){
                //NM start
                $submissionChkQuery = "SELECT id, status, timemodified,userid,assignment FROM {assign_submission} WHERE timemodified >= $diffTimestampExam AND assignment=" . $rscsfdc12->instance . " order by id desc";
                $submissionResult = $DB->get_records_sql($submissionChkQuery);
                //echo "\n"; print_r($submissionResult); echo "\n";
                echo $rscsfdc12->instance; echo "\n";
            }
            if(!empty($submissionResult)){
                foreach ($submissionResult as $submissionRes) {
                    echo "\n RRRPPP \n"; echo $rscsfdc12->instance; echo "\n RRRQQQ \n";
                         $lastupdatedid = $submissionRes->timemodified;
                    $insert_header = "INSERT INTO {sfdc_cron_log_exam_online} (userid, courseid, asid, timemodified) VALUES (".$submissionRes->userid.", ".$keyCC->course.", '".$submissionRes->id."', '".$lastupdatedid."')";
                    $DB->execute($insert_header);
                
                    //Get username $key->username
                    $sqlc3 = "SELECT id, username FROM {user} where id=".$submissionRes->userid;
                    $key = $DB->get_record_sql($sqlc3);
                    echo $sfdc_contact_id = show_contact_id($key->username);
                    $show_sfdc_course = sfdc_show_courses($sfdc_contact_id, $keyCC->course);
                    echo "\n TestCount4 here ".$kj++." \n";
                    if($show_sfdc_course){
                        $course_sfdc_id = $show_sfdc_course['records'][0]['Id'];
                    }
                    else{
                        $course_sfdc_id='';
                    }
                    if(empty($sfdc_contact_id)){
                    //  exit("Contact");
                        continue;
                    }
                    if(empty($course_sfdc_id) and !empty($sfdc_contact_id)){
                        echo "\n TestCount4Check cehk 111  \n";
                        $dot = date("Y-m-d",$keyCC->timecreated);
                        echo "\n TestCount4Check cehk  \n";
                        $course_sfdc_created_id = sfdc_create_course($sfdc_contact_id, format_string($keyCC->fullname), $keyCC->course, "online",$dot);
                        echo "\n";
                        echo "\n TestCount5 ".$kj++." \n";        
                        $show_sfdc_course = sfdc_show_courses($sfdc_contact_id, $keyCC->course); 
                        $course_sfdc_id = $show_sfdc_course['records'][0]['Id'];                
                    }
                    if(empty($course_sfdc_id)){
                        continue;
                    }
                    echo $rscsfdc12->instance; echo "\n RRR \n";
                    $sfdc_course_type = $show_sfdc_course['records'][0]["course_version_type__c"];
                    
                    $classFlag=1;

                    if($submissionRes->status == "submitted"){
                        $exam_submission_status="Yes";
                        $exam_time_modified = date('m/d/Y h:i:s',$submissionRes->timemodified);
                        $exam_date_modified = date('Y-m-d', $submissionRes->timemodified);
                    }elseif($submissionRes->status == "reopened"){
                        $exam_submission_status="Revision";
                        $exam_time_modified = "N/A";
                    }else{
                        $exam_submission_status="No";
                        $exam_time_modified = "N/A";
                    }
                    echo "Test"; echo "\n"; echo $submissionRes->assignment; echo "\n";
                    $sqlsfdc1 = "SELECT id, attemptnumber, grade, assignment, userid FROM {assign_grades} WHERE userid = $submissionRes->userid AND assignment= $submissionRes->assignment order by id desc limit 0,1";
                    $rscsfdc1 = $DB->get_record_sql($sqlsfdc1);
                    //echo "<pre>"; print_r($rscsfdc1); exit;
                    if ($rscsfdc1){
                        if($key->qsc_training_id != ""){
                            $sfdc_attemptnumber = $rscsfdc1->attemptnumber;
                        }
                        else{
                            $sfdc_attemptnumber = $rscsfdc1->attemptnumber + 1;
                        }
                        $sfdc_assign_grade = $rscsfdc1->grade;
                    }else{
                        $sfdc_attemptnumber = 0;
                        $sfdc_assign_grade = 0;
                    }

                    $cm_lms_id = $rscsfdc12->id;
                    // Get data
                    $show_sfdc_exam_id = show_sfdc_exam($course_sfdc_id, $cm_lms_id);
                    echo "\n TestCount24 ".$kj++." \n";
                    $exam_design_attempts = $sfdc_attemptnumber;
                    $exam_grade = number_format($sfdc_assign_grade, 2);
                    $instructor_feedback = "";
                    $fchtml="N/A";
                    if($rscsfdc1->id){
                        $sqlsfdc123 = "SELECT id,assignment, grade, commenttext FROM {assignfeedback_comments} WHERE assignment = $submissionRes->assignment AND grade=" . $rscsfdc1->id . " order by id desc limit 0,1";
                        $rscsfdc123 = $DB->get_record_sql($sqlsfdc123);

                        //PP7 start
                        $params12 = array('assignment'=>$submissionRes->assignment, 'userid'=>$submissionRes->userid);
                        $grades12 = $DB->get_records('assign_grades', $params12, 'attemptnumber ASC', '*');
                        $fchtml=0;
                        if(!empty($grades12)){
                            $fchtml = "<table border=2 cellpadding=0 cellspacing=0>";
                            $fchtml .= "<thead><tr>";
                            $fchtml .= "<th colspan=1 rowspan=1>Attempt Number</th>";
                            $fchtml .= "<th colspan=1 rowspan=1>Message</th>";
                            $fchtml .= "<th colspan=1 rowspan=1>Time</th>";
                            $fchtml .= "</tr></thead><tbody>";
                            foreach($grades12 as $grade12){
                                $afc_params = array('grade'=>$grade12->id);
                                $afcObj = $DB->get_record('assignfeedback_comments', $afc_params, '*');
                                $fchtml .= "<tr>";
                                $an = $grade12->attemptnumber + 1;
                                $fchtml .= "<td>$an</td>"; 
                                if($afcObj->commenttext != ''){
                                            $fchtml .= "<td>".$afcObj->commenttext."</td>"; 
                                }
                                else{
                                            $fchtml .= "<td>Empty</td>";
                                }
                                $fchtml .= "<td>".date("m/d/Y h:i:s", $grade12->timecreated)."</td>"; 
                                $fchtml .= "</tr>";
                            }
                            $fchtml .= "</tbody></table>";
                        }   

                        if($fchtml == 0){
                            $instructor_feedback = format_string($rscsfdc123->commenttext);
                        }else{
                            $instructor_feedback = $fchtml;
                        }
                    }else{
                        $instructor_feedback = "";
                    }
                    $instructor_feedback=$fchtml;
                    //PP7 end
                    $sec = $DB->get_record_sql('select name from {course_sections} where id=?', array(
                        $rscsfdc12->section
                    ));
                    //$module_exam_name = format_string($sec->name) . " : Classroom";
                    //New task

                    $module_exam_name = format_string($sec->name) . " : Online";
                    


                    //New changes dec 2018 start
                    echo $sqlSCExam = 'SELECT id, course FROM {simplecertificate} sci WHERE course='.$keyCC->course;
                    $resSCObjData = $DB->get_record_sql($sqlSCExam);
                    if(empty($resSCObjData)){
                        continue;
                    }                               
                    //echo "<pre>"; print_r($resSCObjData);
                    $sqlSCIExam = 'SELECT id, userid, certificateid, timecreated, timeexpired FROM {simplecertificate_issues} sci                  
                            WHERE userid='.$submissionRes->userid.' and certificateid='.$resSCObjData->id.' order by userid desc';              
                    $issueCertCompNotiObjExamDatas = $DB->get_record_sql($sqlSCIExam);
                    if(!empty($issueCertCompNotiObjExamDatas)){
                        $exam_submission_status="Completed";
                    }
                    //New changes dec 2018 END

                    if (empty($show_sfdc_exam_id)){
                        // insert
                        $exam_sfdc_id = sfdc_create_exam($course_sfdc_id, $cm_lms_id, $module_exam_name, $exam_grade, $exam_design_attempts, $exam_time_modified, $instructor_feedback, $exam_submission_status,$sfdc_contact_id,$exam_date_modified, $rscsfdc12->instance, $key->id); ////Task 2524 - passing new argument value
                        $exam_time_modified = "N/A";
                        $exam_date_modified=null;
                        echo "\n TestCount25 ".$kj++." \n";
                        echo "\n";
                        echo $msg20 = "Exam inserted  " . $exam_sfdc_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg20 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                    } //end if sfdc exam
                    else{
                        // update
                        echo "\n";
                        echo $msg21 = "Exam viewed  " . $show_sfdc_exam_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg21 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                        $exam_sfdc_id = sfdc_update_exam($show_sfdc_exam_id, $module_exam_name, $exam_grade, $exam_design_attempts, $exam_time_modified, $instructor_feedback,$exam_submission_status,$sfdc_contact_id,$exam_date_modified, $rscsfdc12->instance, $key->id); ////Task 2524 - passing new argument value
                        $exam_time_modified = "N/A";
                        $exam_date_modified=null;
                        echo "\n TestCount26 ".$kj++." \n";
                        echo "\n";
                        echo $msg22 = "Exam updated  " . $exam_sfdc_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg22 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                    } //end else sfdc exam
                    $instructor_feedback='';

                    //Delete Final exam if exist in SFDC - START
                    $sqlsfdcdel12 = 'SELECT a.id as assignmentid, cm.id, cm.section, cm.instance FROM {course_modules} cm JOIN {assign} a ON cm.instance=a.id WHERE a.type=? AND cm.course = ? AND cm.module = ?';
                    $rscsfdcdel12 = $DB->get_record_sql($sqlsfdcdel12, array(
                            "classroom",
                            $keyCC->course,
                            1
                        ));
                    $cm_lms_iddel = $rscsfdcdel12->id;
                    $show_sfdc_exam_iddel = show_sfdc_exam($course_sfdc_id, $cm_lms_iddel);
                    echo "\n TestCount27 ".$kj++." \n";
                    if($show_sfdc_exam_iddel){
                        $exam_del_sfdc_id = sfdc_delete_exam($show_sfdc_exam_iddel);
                        echo "\n TestCount28 ".$kj++." \n";
                        echo "\n";
                        echo $msg221 = "Online Exam deleted  " . $exam_del_sfdc_id . " :: " . $key->username;
                        echo "\n";
                        // $insert_header = "INSERT INTO mdl_sfdc_cron_log (userid, courseid, sfdc_message, created) VALUES (" . $submissionRes->userid . ", " . $keyCC->course . ", '" . $msg221 . "', '" . $cron_date . "')";
                        // $DB->execute($insert_header);
                    }           
                    // echo "<pre>";
                 //    print_r($show_sfdc_course); exit;
                } //endif cmcRes
            }   
        }
        $timemofifiedend =time();
        $insert_header = "INSERT INTO {sfdc_cron_log_exam_online} (userid, courseid, asid, timemodified) VALUES (1000,1000,1000,'".$timemofifiedend."')";
        $DB->execute($insert_header);
    }// @fn ends here

}//@class ends