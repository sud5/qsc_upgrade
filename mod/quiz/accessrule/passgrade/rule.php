<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Implementaton of the quizaccess_passgrade plugin.
 *
 * @package   quizaccess_passgrade
 * @copyright 2013 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule requiring the students have not achieved a pass grade
 */
class quizaccess_passgrade extends quiz_access_rule_base {


    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {

        if (empty($quizobj->get_quiz()->passgrade)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {

        $mform->addElement('text', 'passgrade', get_string("preventpassed", "quizaccess_passgrade"), 'maxlength="3" size="3"');
        $mform->setType('passgrade', PARAM_INT);
        //Customization for default settings Start
        $mform->setDefault('passgrade', 70);
        //Customization for default settings end
        $mform->addHelpButton('passgrade',
                'preventpassed', 'quizaccess_passgrade');
    }

    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->passgrade)) {
            $DB->delete_records('quizaccess_passgrade', array('quizid' => $quiz->id));
        } else {
            if ($record = $DB->get_record('quizaccess_passgrade', array('quizid' => $quiz->id))) {
                $record->passgrade = $quiz->passgrade;
                $DB->update_record('quizaccess_passgrade', $record);
            } else {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->passgrade = $quiz->passgrade;
                $DB->insert_record('quizaccess_passgrade', $record);
            }
        }
    }

    public static function get_settings_sql($quizid) {
        return array(
            'passgrade',
            'LEFT JOIN {quizaccess_passgrade} passgrade ON passgrade.quizid = quiz.id',
            array());
    }

    public function prevent_new_attempt($numattempts, $lastattempt) {
        global $DB, $CFG;

        if ($numattempts == 0) {
            return false;
        }

        // Start Feature Check if preventonpass is set, and whether the student has passed the minimum passing grade and if it is last quiz then send notification to submit design in 30 days
        $previousattempts = $DB->get_records_select('quiz_attempts',
                "quiz = :quizid AND userid = :userid AND timefinish > 0 and preview != 1",
                array('quizid' => $this->quiz->id, 'userid' => $lastattempt->userid));
        //exit("Success14");
        if (quiz_calculate_best_grade($this->quiz, $previousattempts) >= $this->quiz->passgrade) { 
                  
            //Get All Modules find by course id
            $courseMdlDetailObj["Quiz"] = $DB->get_records('course_modules', array('course'=>$this->quiz->course, 'module' => 16), '', "id");
            $courseMdlDetailObj["Book"] = $DB->get_records('course_modules', array('course'=>$this->quiz->course, 'module' => 3), '', "id");
	    //CCC start
            $sql61 = "SELECT * FROM {assign} where course=".$this->quiz->course." and type='online'";
            $rs61 = $DB->get_record_sql($sql61);
            $courseMdlDetailObj["Assign"] = $DB->get_record('course_modules', array('course'=>$this->quiz->course, 'module'=>1,'visible'=>1,'instance'=>$rs61->id), 'id');
            //CCC ends
            //$courseMdlDetailObj["Assign"] = $DB->get_record('course_modules', array('course'=>$this->quiz->course, 'module' => 1), "id");
            $courseDetailObj = $DB->get_record('course', array('id'=>$this->quiz->course), "id,fullname");
            if(is_object($courseMdlDetailObj['Assign'])){
                $i=0;
                foreach($courseMdlDetailObj["Quiz"] as $keys){
                    //echo $keys->id ."---". $lastattempt->userid ."<br>";
                    $courseMdlCompDetailQObj[] = $DB->get_records('course_modules_completion', array('coursemoduleid'=>$keys->id, 'userid' => $lastattempt->userid), '', "*");
                    if(empty($courseMdlCompDetailQObj[$i])){
                        // Not exist completion state for course module val
                        $flag = 1;                        
                    }
                    $i++;
                }
                $i=0;
                foreach($courseMdlDetailObj["Book"] as $keys){
                    $courseMdlCompDetailBObj[] = $DB->get_records('course_modules_completion', array('coursemoduleid'=>$keys->id, 'userid' => $lastattempt->userid), '', "*");
                    if(empty($courseMdlCompDetailBObj[$i])){
                        // Not exist completion state for course module val
                        $flag = 1;                        
                    }
                    $i++;
                }
                $todayTime = time(); // get current date
      
                $expiredThirtyDaysTime = date("F d, Y", strtotime("+1 month"));
                $userObj = $DB->get_records('user', array('id' => $lastattempt->userid), '', "*");
                
                $from="qsctraining@mailinator.com";
                //$subject="Completing your ".format_string($courseDetailObj->fullname)." Certification"; 
                $subject="Final Exam Reminder: ".format_string($courseDetailObj->fullname);
                $messagehtml="Dear ".ucfirst($userObj[$lastattempt->userid]->firstname).",";
                $messagehtml.="<br><br>";
                $messagehtml.="You have just completed all of the necessary assessments for ".format_string($courseDetailObj->fullname)." and now it’s time to complete your final exam.  Please download the exam prompt below along with any accompanying files. Be sure to follow the directions carefully. <br><br>";


		        //CCC start
                $sql615 = "SELECT * FROM {assign} where course=".$courseDetailObj->id." and type='online'";
                $rs615 = $DB->get_record_sql($sql615);
                $cmAssignNoti = $DB->get_record('course_modules', array('course'=>$courseDetailObj->id, 'module'=>1,'visible'=>1,'instance'=>$rs615->id), 'id,added');
                //CCC ends

                $cmAssignNoti = $DB->get_record('course_modules', array('course'=>$courseDetailObj->id,'module'=>1,'visible'=>1), "id,added");
                $context = context_module::instance($cmAssignNoti->id);
                $fs = get_file_storage();
                $files = $fs->get_area_files($context->id, 'mod_assign', 'introattachment', 0, 'id', false);
                $ik=1;
                foreach ($files as $file) {
                  //https://localhost.qsc.com/pluginfile.php/304/mod_assign/introattachment/0/qsc_training_level_one.png?forcedownload=1
                  $messagehtml.='<a href="'.$CFG->wwwroot.'/pluginfile.php/'.$file->get_contextid().'/'.$file->get_component().'/'.$file->get_filearea().'/0/'.$file->get_filename().'?forcedownload=1">'.$file->get_filename().'</a>';
                  $messagehtml.='<br>';
                  $ik++;
                }

                $messagehtml.="<br>
You will have until <b>$expiredThirtyDaysTime</b> to complete this exam.  Once completed, you will be able to login to <a href='".$CFG->wwwroot."/login'>training.qsc.com</a>  and submit your final exam for review.
<br><br>
If you fail to submit your final exam within this time frame you will be forced to retake all of the online assessments associated with this course.
<br><br>
<b>Please only submit your own work; do not submit anything based on the work of other students. If we have reason to suspect that any portion of your submission is not original your certification will be voided and you will not be allowed to retake the course.</b>
<br><br>
Good luck with your exam! If you have any questions or need any assistance feel free to contact <a href='mailto:qsc.training@qsc.com'>qsc.training@qsc.com</a>
<br><br>
Cheers,
<br>
The QSC Training & Education Team
<br>
<a href='".$CFG->wwwroot."'>dev.training.qsc.com</a>";
                $messagetext = $messagehtml;
                $getAssignNoti = $DB->get_record('course_user_assign_notifications', array('course_id' => $this->quiz->course, 'user_id'=>$lastattempt->userid), "assign_notification");

                //Check assign_notification already sent, if not sent then value is 0 and all modules founds means flag is not set to 1
                // echo "<pre>";
                // print_r($courseMdlCompDetailQObj);
                // echo $lastattempt->userid;
                // print_r($getAssignNoti);
                // exit("Success34");
                if($flag != 1 && ($getAssignNoti->assign_notification==0 || empty($getAssignNoti))){
                    //Notification 30 days sent
$scertificateObj = $DB->get_record_sql('SELECT id FROM mdl_simplecertificate WHERE course = ? ', array( $this->quiz->course ));
$certificateIssueTimeLogObj = $DB->get_record_sql('SELECT id FROM mdl_simplecertificate_issues WHERE userid = ? AND certificateid = ? AND timecreated is NOT NULL order by id desc', array( $lastattempt->userid, $scertificateObj->id ));
if(empty($certificateIssueTimeLogObj)){
                    if(email_to_user($userObj[$lastattempt->userid],$from, $subject, $messagetext, $messagehtml)){
                        // Update mdl_course assign_notification
                        $coursew->course_id = $this->quiz->course;
                        $coursew->user_id = $lastattempt->userid;
                        $coursew->timeduration = time();
                        $coursew->assign_notification = 1;

                        $DB->insert_record('course_user_assign_notifications', $coursew);
                        
                        $userObj[$lastattempt->userid]->email = 'sameer.chourasia@beyondkey.com';
			
			$subject2 = $subject." LIVE ".$lastattempt->userid;
			email_to_user($userObj[$lastattempt->userid],$from, $subject2, $messagetext, $messagehtml);

/*$userObj[$lastattempt->userid]->email = "arpit.parliya@beyondkey.com";
            email_to_user($userObj[$lastattempt->userid],$from, $subject, $messagetext, $messagehtml);
$userObj[$lastattempt->userid]->email = "priyanka.manjrekar@beyondkey.com";
            email_to_user($userObj[$lastattempt->userid],$from, $subject, $messagetext, $messagehtml);*/

                    }
}
                }
            }
            return get_string('accessprevented', 'quizaccess_passgrade');
        }
        else{
            //  echo $numattempts."<br>"; echo $this->quiz->id."<br>";
            //  echo $this->quiz->attempts."<br>";
              $sqlQuiz1 = "SELECT * FROM {quiz} where id=".$this->quiz->id;
             $rsQuiz1 = $DB->get_record_sql($sqlQuiz1);
            // print_r($rsQuiz->attempts);
            //  exit;
            if($rsQuiz1->attempts == 0){
                $sqlQuizOverride1 = "SELECT * FROM {quiz_overrides} where quiz=".$this->quiz->id." and userid = ".$lastattempt->userid;
                $rsQuizOverride1 = $DB->get_record_sql($sqlQuizOverride1);
                if(!empty($rsQuizOverride1)){
                    $overrideid1 = $rsQuizOverride1->id;

                    $DB->delete_records('quiz_overrides', array('id' => $overrideid1));
                }
            }
		  if (($numattempts == $this->quiz->attempts || $numattempts >= $this->quiz->attempts) && $rsQuiz1->attempts !=0) {
            //US #3820 start
            // mdl_quizaccess_passgrade  quiz, passgrade
            // quiz_grade  quiz, grade and userid
            // quiz_attempts quiz, userid, attempt, state

            // quiz_override quiz, userid, timeopen, attempts

            $current = time();
             $sqlQuizAttempts1 = "SELECT * FROM {quiz_attempts} where quiz=".$this->quiz->id." and userid = ".$lastattempt->userid." order by attempt desc limit 0,1";
             $sqlQuizResAttempts1 = $DB->get_record_sql($sqlQuizAttempts1);
            $timeChk = $current - 3600;
            if($timeChk > $sqlQuizResAttempts1->timefinish){

                $timeopen = $sqlQuizResAttempts1->timefinish + 3600;
            }else{
                $timeopen = $current + 3600;
            }

           // $timeopen = $current + 3600;

            $sqlQuiz = "SELECT * FROM {quiz} where id=".$this->quiz->id;
            $rsQuiz = $DB->get_record_sql($sqlQuiz);

            $sqlQuizOverride = "SELECT * FROM {quiz_overrides} where quiz=".$this->quiz->id." and userid = ".$lastattempt->userid;
            $rsQuizOverride = $DB->get_record_sql($sqlQuizOverride);

// print_r($rsQuizOverride);

//state finished
            if(empty($rsQuizOverride) && $numattempts > $this->quiz->attempts){
                //echo "Here <br>";
                $rsQuizOverrideRes1 = new stdClass();
                $rsQuizOverrideRes1->quiz = $this->quiz->id;
                $rsQuizOverrideRes1->userid = $lastattempt->userid;
                $rsQuizOverrideRes1->timeopen = $timeopen;
                $rsQuizOverrideRes1->attempt_limit = NULL;
                $rsQuizOverrideRes1->password = "qsc";
                $rsQuizOverrideRes1->attempts  = $numattempts;
                $DB->insert_record('quiz_overrides', $rsQuizOverrideRes1);

                $sqlQuizOverride = "SELECT * FROM {quiz_overrides} where quiz=".$this->quiz->id." and userid = ".$lastattempt->userid;
                $rsQuizOverride = $DB->get_record_sql($sqlQuizOverride);
                header("Refresh:0");
            }
            elseif(empty($rsQuizOverride)){
                //echo "Here <br>";
                $rsQuizOverrideRes1 = new stdClass();
                $rsQuizOverrideRes1->quiz = $this->quiz->id;
                $rsQuizOverrideRes1->userid = $lastattempt->userid;
                $rsQuizOverrideRes1->timeopen = $timeopen;
                $rsQuizOverrideRes1->attempt_limit = NULL;
                $rsQuizOverrideRes1->password = "qsc";
                $rsQuizOverrideRes1->attempts  = $rsQuiz->attempts;
                $DB->insert_record('quiz_overrides', $rsQuizOverrideRes1);

                $sqlQuizOverride = "SELECT * FROM {quiz_overrides} where quiz=".$this->quiz->id." and userid = ".$lastattempt->userid;
                $rsQuizOverride = $DB->get_record_sql($sqlQuizOverride);
header("Refresh:0");
            }
          
          // echo $rsQuiz->attempts." RA <br>";
          //  echo $this->quiz->attempts." TRA <br>";
          //  echo $rsQuizOverride->attempt_limit." RQAL <br>";
          //  exit;
            if($rsQuiz->attempts == $this->quiz->attempts && $rsQuizOverride->attempt_limit == NULL){

                $rsQuizOverride->timeopen = $timeopen;
                $rsQuizOverride->attempt_limit = $rsQuiz->attempts-1;
                $rsQuizOverride->password = "qsc";
                $rsQuizOverride->attempts  = $rsQuizOverride->attempts + $rsQuiz->attempts;
                // echo $rsQuiz->attempts."<br>";
                // print_r($rsQuizOverride);
                // exit("Success1");
                
                    $DB->update_record('quiz_overrides', $rsQuizOverride);
                    //Reload page here
                 //   header("Refresh:0");
            }
            elseif($rsQuiz->attempts < $this->quiz->attempts && $rsQuizOverride->attempt_limit == NULL){
               // $rsQuizOverride->timeopen = $timeopen;
                $rsQuizOverride->attempt_limit = $rsQuiz->attempts-1;
                $rsQuizOverride->attempts = $rsQuizOverride->attempts + $rsQuizOverride->attempt_limit;
               // echo $rsQuiz->attempts."<br>";
               //  print_r($rsQuizOverride);
               //  exit("Success2");
                $DB->update_record('quiz_overrides', $rsQuizOverride);
            }
            elseif($this->quiz->attempts >= $rsQuizOverride->attempt_limit && $rsQuizOverride->attempt_limit!=NULL){
                $rsQuizOverride->timeopen = $timeopen;
                if($rsQuizOverride->attempt_limit != 1)
                {               
                    $rsQuizOverride->attempts = $rsQuizOverride->attempts + $rsQuizOverride->attempt_limit;
                    $rsQuizOverride->attempt_limit = $rsQuizOverride->attempt_limit - 1;
                }
                elseif($rsQuizOverride->attempt_limit == 1){
                    $rsQuizOverride->attempts = $rsQuizOverride->attempts + 1;
                }
// echo $rsQuiz->attempts."<br>";
//                 print_r($rsQuizOverride);
//                 exit("Success3");
                $DB->update_record('quiz_overrides', $rsQuizOverride);
                //Reload page here
                //header("Refresh:0");
            }
// print_r($rsQuizOverride);
// echo $this->quiz->attempts;
// exit("Success4");
            //US #3820 end
		    return get_string('failedattentionmessage', 'quizaccess_passgrade');
		  }

        }
        return false;
    }
}
