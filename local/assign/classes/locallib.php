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
 * Local plugin "assign" - Settings class file
 *
 * @package    local_assign
 * @copyright  2022 @shiva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_assign;
use context_course;
use moodle_url;
use html_writer;
defined('MOODLE_INTERNAL') || die();

/**
 * Class used for uploading the assign files into file storage, inherits quite everything from \admin_setting_configstoredfile.
 *
 * @package    local_assign
 * @copyright  2022 @shiva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class locallib {

    public function test(){
        global $DB,$CFG,$SESSION;

        echo 'testcode';die;
    }
    /**
     * This function is used to store data
     * @param cmid-int
     * @return bool - true if successful.
     */
    public function store_data($data){
        global $DB,$COURSE,$USER,$CFG;
        $label_name = $data['label_name'];
        $comment_text = $data['comment_text'];
        if (!empty($label_name) && !empty($comment_text)) {
            foreach ($label_name as $stkey => $stvalue) {
                $fordb                    = new stdClass();
                $fordb->id                = null;
                $fordb->user_id           = $data['userid'];
                $fordb->course_id         = $data['cid'];
                $fordb->button_lable      = trim($stvalue);
                $fordb->comment_text      = trim($comment_text[$stkey]);
                $fordb->timecreated       = time();
                $newid                    = $DB->insert_record('speed_text', $fordb, true);
            }
            redirect(new moodle_url('/local/assign/speedtext.php',array('cid'=>$data['cid'])));           
        } else {
           $msg = 'yes';
        }
        return $msg;

    }
    /**
     * This function is used to send Intructor comments to Learners viq their email
     * @param userid-int
     * @param $assignmentid-int
     * @param feedbackcomments-string
     * @param cmid-int
     * @return bool - true if successful.
     */
    public static function sendemail($userid, $assignmentid,$feedbackcomments,$cmid) {
        global $DB,$CFG,$USER,$SESSION;
        if (!$user = $DB->get_record('user', array('id'=>$userid),"id,username,email,firstname,lastname")) {
            mtrace("Can not find user");
        }
        if (!$assign = $DB->get_record('assign', array('id'=>$assignmentid),"id,course,name")) {
            mtrace("Can not find final exam");
        }
        if (!$courseobj = $DB->get_record('course', array('id'=>$assign->course),"id,fullname")) {
            mtrace("Can not find course");
        }
        $coursename = format_string($courseobj->fullname);
        $from = "qsctraining@qscaudio.com";
        $subject = $courseName." Exit Exam Feedback";
        $messagehtml = "Dear ".ucfirst($user->firstname).",";
        unset($user->firstname);
        unset($user->lastname);
        
        $messagehtml.="<br><br>";
          
        $messagehtml.= "A QSC instructor has responded to your final exam submission. <a href='".$CFG->wwwroot."/mod/assign/view.php?id=".$cmid."'>Click here to login and view the feedback</a>";

        $messagehtml.="<br><br>";
        $messagehtml.="Cheers,";
        $messagehtml.="<br>";

        $messagehtml.="Training & Education Team";
        $messagehtml.="<br>";
        $messagehtml.="<a href='".$CFG->wwwroot."'>www.training.qsc.com</a>";
                
        $messagetext = $messagehtml;
        $messagehtml = text_to_html($messagehtml, false, false, true);
        $user->mailformat = 1;  // Always send HTML version as well.
        
        if(email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
            $user->email = "sameer.chourasia@beyondkey.com";
            email_to_user($user,$from, $subject, $messagetext, $messagehtml);

        }
        return true;
    }
    /**
     * This function is used to send Intructor comments to Learners viq their email
     * @param userid-int
     * @param $assignmentid-int
     * @param courseid-int
     * @param rolename-string
     * @return bool - true if successful.
     */
    public static function check_roleuser($userid,$courseid,$rolename) {
        global $DB,$CFG,$USER,$SESSION;
        $sql = "SELECT id FROM {role} WHERE shortname='".$rolename."'";
        $roleobj = $DB->get_record_sql($sql);
        $rsql = "SELECT contextid FROM {role_assignments} where userid=".$userid." AND roleid=".$roleobj->id;
        $roleexists = $DB->get_record_sql($rsql);
        //echo $roleexists;die;
        if($roleexists){
           return 1;
        }else{
           return 0;
        }
        
    }
    /**
     * This function is used to send Intructor comments to Learners viq their email
     * @param userid-int
     * @param $courseid-int
     * @return bool - true if successful.
     */
   public static function complition_check($userid,$courseid){
        global $DB,$COURSE,$CFG,$USER,$SESSION;
        $completionCurrentCourse = $DB->get_record('course_completions', array('userid'=> $userid, 'course' => $courseid), '*');
        if($completionCurrentCourse){
            if(isset($_SESSION['newmodulescourseidarray'])){
              $arrChkCourseNewModule = $_SESSION['newmodulescourseidarray'];
              if(in_array($courseid,$arrChkCourseNewModule)){
                 redirect(new moodle_url('/my/'));
              }
                
            }else{
              if($completionCurrentCourse->timecompleted != NULL && $completionCurrentCourse->timecompleted != ""){
                 redirect(new moodle_url('/my/'));
              }
                
            }
        }

        $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$courseid,'criteriatype'=>8),'','id, course, courseinstance');
        if(!empty($coursesData)){
        $chkflagcomp=0;
        foreach($coursesData as $keyCD){
          // Course first level checking completion
          if($keyCD->courseinstance != ''){
            $courseinstance=$keyCD->courseinstance;
          }
        
        //New Change Classroom Start
        if($courseinstance){
          //New Change Classroom End
          $sql32 = "SELECT id,category FROM {course} WHERE id = $courseinstance";
          $rs132 = $DB->get_record_sql($sql32);
          //Level 2 Submission Restriction start  
          $flagAccessLevel2=0;
          $sql0m421 = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$userid.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course IN (SELECT id FROM {course} WHERE category = '.$rs132->category.')';
          $completionParent = $DB->get_record_sql($sql0m421);

          $sql61 = "SELECT * FROM {assign} where course=$courseid and type='classroom'";
          $rs61 = $DB->get_record_sql($sql61);
          //1069 start
          if(!empty($rs61)){
            //1069 end
            $rs71 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$userid AND asu.assignment=$rs61->id order by id desc limit 0,1 ");
            //1069 start
          } //1069 end

          if($completionParent->cntcert == 0 || empty($completionParent->cntcert)){
            if($rs71->status !== 'submitted' && $rs71->status !== 'reopened'){
             // redirect(new moodle_url('/my/'));
            }//1069 start
            elseif(empty($rs61)){ //for online courses belongs to level 2 types
              //redirect(new moodle_url('/my/'));
 
            } //1069 end
          }
          else{
            $chkflagcomp=1;
          }
        //New Change Classroom Start
        }
    }
        if($chkflagcomp == 0){
            redirect(new moodle_url('/my/'));
        }
    }
        //1069 start AND combination
        $sqlCCC = 'SELECT ccc.* FROM {course_completion_aggr_methd} ccam JOIN {course_completion_criteria} ccc ON ccam.course=ccc.course WHERE ccc.course =' . $courseid . ' AND ccc.course != 42 AND ccam.criteriatype = 8 AND ccam.method = 1';
        $cccRes = $DB->get_records_sql($sqlCCC);
        if($cccRes) {
            foreach ($cccRes as $cccObj) {
                if($cccObj->courseinstance != '') {        
                    $sqlmCCC = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$userid.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course = '.$cccObj->courseinstance;
                    $sqlmCCCRes = $DB->get_record_sql($sqlmCCC);
                    if($sqlmCCCRes->cntcert == 0){
                      redirect(new moodle_url('/my/'));
                    }
                }
            }
        }
        //1069 end AND combination
        //New Change Classroom End
        return true;
    }//@fn ends
    public static function  secondsToTime($ss) {
        //print_r($ss);die;
        $ss = (int)$ss;
        $s = $ss%60;
        $m = floor(($ss%3600)/60);
        $h = floor(($ss%86400)/3600);
        $d = floor(($ss%2592000)/86400);
        $M = floor($ss/2592000);

        if($ss != 0){
            if($M != 0)
              return $M."m ".$d."d ".$h."h ".$m."m ".$s."s";
            elseif($d != 0)
              return $d."d ".$h."h ".$m."m ".$s."s";
            elseif($h != 0)
              return $h."h ".$m."m ".$s."s";
            elseif($m != 0)
              return $m."m ".$s."s";
            elseif($h != 0)
              return "0m ".$s."s";
        } else{
            return "0m 0s";
        }
    }
    public static function sections_view($cm){
        global $DB,$COURSE,$CFG,$USER,$SESSION;
        $courseSecData = $DB->get_record('course_sections', array('id'=>$cm->section), 'course, summary, id', MUST_EXIST);
        $context = context_course::instance($courseSecData->course);
        $summarytext = file_rewrite_pluginfile_urls($courseSecData->summary, 'pluginfile.php',$context->id, 'course', 'section', $courseSecData->id);
        $summaryData = format_text($summarytext, $courseSecData->summaryformat, array('noclean'=>true, 'overflowdiv'=>true));

        //print_r($context); echo "<br>"; print_r($summaryData); exit;
        //print_r($summaryData); exit;
        $regex = '#\<div class="text_to_html"\>(.+?)\<\/div\>#';
        preg_match($regex, $summaryData, $matches);
        $match = $matches[0];

        $regextwo = '#\<p\>(.+?)\<\/p\>#';
        preg_match($regextwo, $matches[0], $matchestwo);

        $regexthree = '#\<img src=\"(.*?)\".*\>#';
        preg_match($regexthree, $matchestwo[0], $matchesthree);

        $r = ($matchestwo)?explode($matchestwo[0],$matches[0]):1;
        $summaryTxtRaw = strip_tags($r[1]);
        $lessonImg = $matchesthree[1];
        return true;

    }
    public static function facetofacecheck($classroomSignUpSet,$course,$moduleDetailObj,$facetoface){
        global $DB,$COURSE,$CFG,$SESSION,$USER;
        //echo $classroomSignUpSet;die;
        if($classroomSignUpSet==0){
            //echo 'ddddd';die;
          //Check first course time
          $cmcObj = $DB->get_records('course_modules_completion', array('userid' => $USER->id), 'id ASC','*');
          //echo "<pre>";
          foreach ($cmcObj as $keyCMC) {
            # code...
            //$cmObjData = $DB->get_record('course_modules', array('id'=>$keyCMC->coursemoduleid,'course'=>$course->id, 'visible' => 1), "*");
            $cmObjData = $DB->get_record('course_modules', array('id'=>$keyCMC->coursemoduleid,'course'=>$course->id), "*");
            if($cmObjData){
              //print_r($cmObjData);print_r($keyCMC);exit;
              $timeForFinalExamAccess = $keyCMC->timemodified;
              break;
            }
          }

          $i=0;
          foreach($moduleDetailObj as $keyModules){
             // echo $keyModules->id ." -- ".$course->id;die;
            if($keyModules->id == $course->id){
                    $sectionData = $DB->get_records('course_sections', array('course'=>$keyModules->id), '', "id, name");
                    //$cmAssignDate = $DB->get_record('course_modules', array('course'=>$keyModules->id,'module'=>1,'visible'=>1), "id,added");
                    //$cmAssignDate = $DB->get_record('course_modules', array('course'=>$keyModules->id,'module'=>1), "id,added");
                    //echo "<pre>";print_r($cmAssignDate->added); exit;
                    $jk=0;
              
                foreach ($sectionData as $key) {
                  //print_r($key->id); exit;
                  if($jk >= 1){
                    if(!empty($timeForFinalExamAccess)){

                      $cmCourse[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 3 AND visible = 1 AND added <= $timeForFinalExamAccess");
                      $cmQuiz[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 16  AND visible = 1 AND added <= $timeForFinalExamAccess");

                      //$cmCourse[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 3 AND added <= $timeForFinalExamAccess");
                      //$cmQuiz[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 16  AND added <= $timeForFinalExamAccess");
                    }else{
                      $cmCourse[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 3 AND visible = 1");
                      $cmQuiz[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 16 AND visible = 1"); 

                      //$cmCourse[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 3");
                      //$cmQuiz[$key->id][] = $DB->get_records_select('course_modules', "section=$key->id AND module = 16"); 
                    }

                    //$cmAssign[$key->id][] = $DB->get_records('course_modules', array('section'=>$key->id,'module'=>1,'visible'=>1), '', "id");
                    $cmCertificate[$key->id][] = $DB->get_records('course_modules', array('section'=>$key->id,'module'=>25), '', "id"); 
                    
                    if(empty($cmCertificate[$key->id][0])){

                        $lessonNameArr[$i]['module_name'] = $key->name;
                        $modCalTime = 0;
                        foreach ($cmCourse[$key->id] as $keyCMs) {
                          $j=0;
                          foreach($keyCMs as $keyCM){
                           
                            $lessonNameArr[$i][$j]['Lesson'] = $keyCM->id;
                            $lessonCompletionObj = $lessonNameArr[$i][$j]['LessonCompletionData'] = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCM->id, 'userid' => $USER->id), '*');
                            //$cmIdBook[$keyModules->id][] = $keyCM->id;
                            $instance = $keyCM->instance;
                            $bookLeftPanel = $DB->get_record('book', array('id'=>$instance), 'name, displaytime');
                            $lessonNameArr[$i][$j]['book_name'] = $bookLeftPanel->name;
                            $lessonNameArr[$i][$j]['book_time'] = self::secondsToTime($bookLeftPanel->displaytime);
                            $modCalTime = (int)$bookLeftPanel->displaytime + $modCalTime;

                            $lessonNameArr[$i][$j]['book_id'] = $instance;
                            if(is_object($lessonCompletionObj) && !empty($lessonCompletionObj)){
                              $lessonNameArr[$i][$j]['lessoncompletionStatus'] = $lessonCompletionObj->completionstate; // 0 is for noncompletion
                            }else{
                              $lessonNameArr[$i][$j]['lessoncompletionStatus'] = 0;
                             
                              $flag=1; // 0 is for noncompletion          
                            }
                            $lessonNameArr[$i][$j]['timing'] = 0;
                            $j++;
                          }
                        }

                        $lessonNameArr[$i]['module_time'] = self::secondsToTime($modCalTime);
                        foreach ($cmQuiz[$key->id] as $keyCMQs) {
                          foreach ($keyCMQs as $keyCMQ) {
                            // /echo "<pre>"; print_r($keyCMQs); exit;
                              $lessonNameArr[$i]["Quiz"][] = $keyCMQ->id;
                              
                              $quizGradeData = get_coursemodule_from_id('quiz', $keyCMQ->id, 0, false, MUST_EXIST);
                              $RequireGradeData = $lessonNameArr[$i]["RequireGrade"][] = $DB->get_record('quizaccess_passgrade', array('quizid'=>$quizGradeData->instance), 'passgrade');

                              $lessonNameArr[$i]["QuizGrade"][] = $DB->get_field('quiz_grades', 'grade', array('quiz' => $quizGradeData->instance, 'userid' => $USER->id));

                              $quizCompletionObj = $DB->get_record_select('quiz_attempts', "quiz = $quizGradeData->instance AND userid = $USER->id AND sumgrades >= $RequireGradeData->passgrade");
                              if(empty($quizCompletionObj)){
                                     $quizCompletionObj = $DB->get_record_select('quiz_grades', "quiz = $quizGradeData->instance AND userid = $USER->id AND grade >= $RequireGradeData->passgrade");
                              }
                         
                              //$quizCompletionObj = $lessonNameArr[$i]['QuizCompletionData'] = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCMQ->id, 'userid' => $USER->id), '*');
                              if(is_object($quizCompletionObj) && !empty($quizCompletionObj)){
                                $lessonNameArr[$i]['quizcompletionStatus'] = $quizCompletionObj->sumgrades; // 0 is for noncompletion
                              }else{
                                $lessonNameArr[$i]['quizcompletionStatus'] = 0; 
                                $flag=1; // 0 is for noncompletion
                                    
                              }
                          }
                          //$cmIdQuiz[$keyModules->id][] = $keyCMQ->id;
                        }

                    }
                    $i++;
                  }
                  $jk++;
                }

                if(!empty($cmCertificate[$key->id][0])){
                    foreach($cmCertificate[$key->id] as $keyCMCs){
                      foreach ($keyCMCs as $keyCMC) {
                        # code...
                        $cmCertificateId = $keyCMC->id;
                        $certificateURL = "/mod/simplecertificate/view.php?id=".$cmCertificateId."&action=get";
                      }
                    }     
                } 
            }
           }
        }//end empty if facetoface
      return true;
    }//@fn ends
    public static function examdata($examExistObjData,$facetoface,$course,$user,$cm){
        global $DB,$COURSE,$CFG,$SESSION,$USER;
        if (empty($examExistObjData)){
            //Cust start
            $facetofaceObj = $DB->get_record_sql('SELECT f.id,fsi.sessionid,fss.signupid,fss.statuscode FROM {facetoface} as f JOIN {facetoface_sessions} as fs ON f.id = fs.facetoface JOIN {facetoface_signups} as fsi ON fs.id=fsi.sessionid JOIN {facetoface_signups_status} as fss ON fsi.id=fss.signupid WHERE f.course=? AND fsi.userid = ? order by fss.timecreated desc LIMIT 0,1', array($course->id, $USER->id));

            if(empty($facetofaceObj) || ($facetofaceObj->statuscode != 70 && $facetofaceObj->statuscode != 60)){
                 //echo $facetofaceObj->statuscode; exit("Success2");
            }
         //Cust end
        }else{
            //US #824 chnage sql and below code start
            $ftof_signups_query = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $USER->id AND fss.superceded=0  and fse.facetoface = $facetoface->id order by fss.id asc";
            $facetofaceObj1 = $DB->get_record_sql($ftof_signups_query);
            //US #824 chnage sql and below code end
            //PP2 start
            //change for grader assignment error 2
            //US #824 chnage if cond and inner loop logic start
          if($facetofaceObj1->statuscode == 10 && $USER->id!=2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin'){
              //PP2 end
                $ftof_signups_query2 = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $USER->id and (fss.statuscode = 70 OR fss.statuscode = 60) AND fss.superceded=0  and fse.facetoface = $facetoface->id order by fss.id desc";
                $facetofaceObj2 = $DB->get_record_sql($ftof_signups_query2);

                if(empty($facetofaceObj2) && $USER->id!=2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin'){
                    redirect(new moodle_url('/my/'));
                  }
                  //US #824 chnage if cond and inner loop logic end
              //PP2 start 
          }
          //PP2 end
        }
        return true;
    }//@fn ends

    public static function salesforce_err($USER,$course,$id,$flag,$menuflag,$pflag){
        global $DB,$USER,$COURSE;
        $flagReviewData=0;
        $rs3 = $DB->get_record('simplecertificate',array('course'=>$course->id),'id, certexpirydateinyear');
        $sql4 = 'SELECT id, userid, timecreated as timecompletion, timecreatedclassroom, timeexpired
                        FROM {simplecertificate_issues}
                       WHERE certificateid='.$rs3->id.' AND userid ='.$USER->id.' order by id desc';
        if($rs3){
            $rs4 = $DB->get_record_sql($sql4);
        }
        
        //print_r($rs4);die;
        $sqlCC = 'SELECT id, userid, course, timecompleted
                            FROM {course_completions}
                           WHERE userid='.$USER->id.' AND course='.$course->id.' AND timecompleted != ""';
        $courseCompObjData = $DB->get_record_sql($sqlCC);
        //Start Commented to invalidate the condition LMS to Salesforce Reporting Errors
        $getCMObj2 = $DB->get_record('course_modules', array('id' => $id), '*');
        $rs71 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=$getCMObj2->instance order by id desc limit 0,1");
        //If user NOT certified and attempts are non-completion criteria
        $sqlCM1 = 'SELECT count(cm.id) as totalmodules FROM {course_modules} cm where cm.visible=1 and cm.course='.$course->id.' and (cm.module=3 || cm.module=16 || cm.module=15)';
        $rsCM1 = $DB->get_record_sql($sqlCM1);

        $sqlCMC1 = 'SELECT count(cmc.id) as totalcompletedmodules FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cm.id=cmc.coursemoduleid where cmc.userid ='.$USER->id.' AND cm.course='.$course->id.' and cm.visible=1 and (cm.module=3 || cm.module=16 || cm.module=15)';
        $rsCMC1 = $DB->get_record_sql($sqlCMC1);

          if(($rsCMC1->totalcompletedmodules != $rsCM1->totalmodules) && ($rs71->status != 'submitted' && $rs71->status != 'reopened')){
            //End Commented to invalidate the condition LMS to Salesforce Reporting Error
            if ($USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $flag == 1 ) { 
                redirect(new moodle_url('/login/index.php'),"Please complete the remaining activities of this course..");
            }
          } //Start Commented to invalidate the condition LMS to Salesforce Reporting Error
          elseif(empty($rs4) && !empty($courseCompObjData)){
            if ( $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $flag != 1 ) { 
              redirect(new moodle_url('/login/index.php'),"Please download the certificate of this course to review and then review teacher feedback.");
            }
        }else{
            $getCMObj = $DB->get_record('course_modules', array('id' => $id), '*');

            $rs71 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=$getCMObj->instance order by id desc limit 0,1 ");
            //echo "SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=$getCMObj->instance order by id desc limit 0,1";
            //exit;
            //Start Commented to invalidate the condition LMS to Salesforce Reporting Errors
            if(!empty($rs4)){
              if(empty($rs71)){
                $sql81a = "SELECT count(id) as cnt_sub_grade, ag.id FROM {assign_grades} ag where grade!=-1.00000 AND ag.assignment=$getCMObj->instance AND ag.userid=".$USER->id;
                $rs81a = $DB->get_record_sql($sql81a);

                if($rs81a->cnt_sub_grade != 0){
                  redirect(new moodle_url('/login/index.php'),"You have not allowed to review this exam. Administrator manually issued certification to you for this course.");
                }else{
                  $flagReviewData=1;
                }
              }
              
              //Start Commented to invalidate the condition LMS to Salesforce Reporting Errors
            }
            //End Commented to invalidate the condition LMS to Salesforce Reporting Errors

        }
      return $flagReviewData;

    } //@fn ends
    public static function scripts_load($flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass){
        global $DB,$USER,$COURSE,$SESSION,$CFG;
        $actionURL = optional_param('action', '', PARAM_ALPHA);
        $indicator = get_string("indicator", "assign");
        $o = '<script type="text/javascript">
                     
                      var menuflag = '.$menuflag.';
                      if(menuflag != 0){
                         $("#actionmenuaction-3").parent().parent().hide();
                      }
                      var flagReviewData = "'.$flagReviewData.'";
                      var gradepass = "'.$gradePass.'";
                      var errstr = "'.$errorStr.'";
                       //alert(flagReviewData);
                      var valattempt = $("#id_grade").val();
                      if(valattempt == null){
                        $("#id_addattempt>option:eq(1)").attr("selected", true);
                      }
                      if(valattempt < gradepass){
                        $("#id_addattempt>option:eq(1)").attr("selected", true);
                      }
                      $("#id_grade").bind("cut copy paste",function(e) {
                             e.preventDefault();
                      });
               </script>'; 
        //US #2576 start
        $o .= '<script>
                // $("#id_grade").on("keyup",function() {
                //     $("#aerr").remove();
                //     var dInput = document.getElementById("id_grade").value;
                //     if(dInput >= gradepass){
                 //       $("#id_addattempt>option:eq(0)").attr("selected", true);
                //       $("#id_addattempt>option:eq(1)").attr("selected", false);
                //       $("#id_addattempt").val(0);
                //     }
                //     else{
                //       $("#id_addattempt>option:eq(1)").attr("selected", true);
                //       $("#id_addattempt>option:eq(0)").attr("selected", false);
                //       $("#id_addattempt").val(1);
                //     }     
                // });
              </script>';
        $o .= '<script>
                    var menuflag = '.$menuflag.';
                    var flagReviewData = "'.$flagReviewData.'";
                    var gradepass = "'.$gradePass.'";
                    var errstr = "'.$errorStr.'";
                    $("#id_addattempt").change(function () {
                     //alert(gradepass);
                     var end = this.value;
                     //alert(end);
                     var dInputs = document.getElementById("id_grade").value;
                     //alert(dInputs);
                     if(dInputs >= gradepass && end == 1){
                        alert(gradepass);
                        $("#id_addattempt>option:eq(0)").attr("selected", true);
                        $("#id_addattempt>option:eq(1)").attr("selected", false);
                        if($("#aerr").hasClass("aeee") == 0){
                          $("#id_addattempt").parent().append("<span class=error aeee id=aerr>"+errstr+"</span>");
                        }
                    }  
                });
                $(".submissionstatustable").append($(".feedback").html());
                $(".feedback").remove();
                $(".editsubmissionform #id_submitbutton").click(function(){
                  $(".editsubmissionform").hide();
                  $("#region-main").append("<div id=notfiy_indication><h2>'.$indicator.'</h2></div><br>");
                  $("#intro").hide();
                  $("#region-main").focus();
                });
                if($( "tr" ).hasClass( "submission_remove" )){
                    //$(".submissionstatustable h3").remove();
                    //Submission Feedback Code start
                    if(flagreviewdata == "0"){
                      //Start Commented to invalidate the condition LMS to Salesforce Reporting Errors
                      //$(".submission_remove").parent().parent().hide();
                      //End Commented to invalidate the condition LMS to Salesforce Reporting Errors
                    }
                    //Submission Feedback Code end
                    //Start Commented to invalidate the condition LMS to Salesforce Reporting Errors

                    //$(".submissionstatustable h3").remove();  
                    //End Commented to invalidate the condition LMS to Salesforce Reporting Errors

                    //$(".submission_remove").parent().parent().hide();
                    //$(".feedbacktable").remove();
                    $(".submissionstatustable").css("margin-bottom","0.5em");
                }
                $( ".c10").hide();
                $("hr:first").remove();
                $(".grader_pulldown").change(function() {
                    var end = this.value;
                    $.ajax({
                        url: "'.$CFG->wwwroot.'/local/assign/grader_ajax.php",
                        type: "post",
                        data: {grader_val: end},
                        success: function(response) {
                          console.log("Response "+response);
                        }
                    });
                });
              </script>';
        //US #2576 end   
        $o .= '<script>
                var menuflag = '.$menuflag.';
                var flagReviewData = "'.$flagReviewData.'";
                var gradepass = "'.$gradePass.'";
                var errstr = "'.$errorStr.'";
                $("#id_savegrade, #id_saveandshownext").click(function(){
                    var grade = document.getElementById("id_grade").value;
                   // alert(grade);
                    if (grade.indexOf(".") == -1) {
                        grade = grade+".00";
                        document.getElementById("id_grade").value = grade;
                         //$(this).val(grade);
                         //alert(grade);
                    }

                    if(grade == ".00"){
                        //US #2576 start
                        //alert("Please enter Grade.");
                        //US #2576 end
                        $("#id_addattempt>option:eq(1)").attr("selected", true);
                        document.getElementById("id_grade").value = 0.00;
                        $("#id_grade").focus();
                            return false;
                    }
                    //console.log(parseFloat(grade + " Test"));
                    if(grade == "0.00"){
                     //US #2576 start
                     //alert("Please enter Grade.");
                     //US #2576 end
                      $("#id_addattempt>option:eq(1)").attr("selected", true);
                      document.getElementById("id_grade").value = 0.00;
                      $("#id_grade").focus();
                        return false;
                    }

                    if(grade == "0."){
                          //US #2576 start
                          //alert("Please enter Grade.");
                          //US #2576 end
                          $("#id_addattempt>option:eq(1)").attr("selected", true);
                          document.getElementById("id_grade").value = 0.00;
                          $("#id_grade").focus();
                          return false;
                    }
                    return true;
                });
                $(".gradingform form #mform1").submit(function() {
                    var valattempt = $("#id_grade").val();
                    if(valattempt == null){
                        //US #2576 start
                        //alert("Please enter Grade.");
                        //US #2576 end
                        $("#id_addattempt>option:eq(1)").attr("selected", true);
                        //return false;
                    }
                      // your code here
                    var grade = document.getElementById("id_grade").value;
                    if (grade.indexOf(".") == -1) {
                        grade = grade+".00";
                        document.getElementById("id_grade").value = grade;
                        //$(this).val(grade);
                        //alert(grade);
                    }
                    return true;
                });
                //US #2576 start
                $("#id_savegrade").on("click",function(){
                   var passfailval = $("#id_passfail").val();
                   console.log(passfailval);
                   if(passfailval == 0 || passfailval == "Select"){
                     alert("Please select Pass/Fail grade.");
                     $("#id_passfail").focus();
                     return false;
                   }
                });
                //US #2576 end
              </script>';
        // Custom code - Updated by Lakhan - 25May2017--modify here shiva 2022
        $o .= '<script>
                var menuflag = '.$menuflag.';
                var flagReviewData = "'.$flagReviewData.'";
                var gradepass = "'.$gradePass.'";
                var errstr = "'.$errorStr.'";
                $("#id_grade").keydown(function (e){
                    if(e.keyCode == 13){
                         var grade = document.getElementById("id_grade").value;
                        if(grade != ""){
                          if($.isNumeric(grade)){
                            if (grade.indexOf(".") == -1) {
                              grade = grade+".00";
                              //document.getElementById("id_grade").value = grade;
                              $(this).val(grade);
                               //alert(grade);
                            }
                            if(($("#grade_error").length) > 0){
                              $("#grade_error").remove();
                              $("#id_savegrade").removeAttr( "disabled" );
                            }
                      
                          }else{
                             // not a number
                            if(($("#grade_error").length) < 1){
                              $("<div id=grade_error><span class=error>The grade provided could not be understood. Please provide numeric value.</span></div>").insertAfter("#id_grade");
                              $("#id_grade").focus();
                              $("#id_savegrade").attr("disabled", "disabled");

                            }

                          }
                        }
                    }
                });
                $("#id_grade").on("focusout",function(){
                    var grade = document.getElementById("id_grade").value;
                    if(grade != ""){
                        if($.isNumeric(grade)){
                           if (grade.indexOf(".") == -1) {
                              grade = grade+".00";
                              //document.getElementById("id_grade").value = grade;
                              $(this).val(grade);
                               //alert(grade);
                            }
                            if(($("#grade_error").length) > 0){
                              $("#grade_error").remove();
                              $("#id_savegrade").removeAttr( "disabled" );
                            }
                        }else{
                        // not a number
                            if(($("#grade_error").length) < 1){
                              $("<div id=grade_error><span class=error>The grade provided could not be understood. Please provide numeric value.</span></div>").insertAfter("#id_grade");
                              $("#id_grade").focus();
                              $("#id_savegrade").attr("disabled", "disabled");

                            }
                        }
                    }
                });
                /*Grade Panel Start*/
                  var actionURL = "'.$actionURL.'";
                  if(actionURL == "savegradingresult"){
                    $("#intro").hide();
                  }
                /*Grade Panel end*/
              </script>';
        return $o;     
               
    }//@ fn ends
    public static function grader_scripts_load($flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass){
        global $DB,$USER,$USER,$CFG,$SESSION;
        $o = '<script>
                var dirroot = "'.$CFG->wwwroot.'";
                var menuflag = '.$menuflag.';
                var flagReviewData = "'.$flagReviewData.'";
                var gradepass = "'.$gradePass.'";
                var errstr = "'.$errorStr.'";
                $(".gradingform").after($("#hiddenComments").html());
                // - - - - -Start -Feature Request: "Speed Text" Buttons Nav - - - - - --//
                $("#fitem_id_assignfeedbackcomments_editor").after($("#hiddenSpeedText").html());
                $(document).on("click",".speedTextButton",function(){
                    var sID = this.id;
                    //alert(sID);
                    $.ajax({
                       url: dirroot+"/local/assign/speedtext_ajax.php",
                       type: "post",
                       data: {speedtext_id: sID},
                       success: function(response) {
                          //console.log("Response "+response);
                          //document.getElementById("id_assignfeedbackcomments_editoreditable").innerHTML= response;
                          document.getElementById("id_assignfeedbackcomments_editoreditable").focus();
                          pasteHtmlAtCaret(response);
                          $("#id_assignfeedbackcomments_editor").val(response);
                       }
                    });
                });
                function pasteHtmlAtCaret(html) {
                    var sel, range;
                    if (window.getSelection) {
                        // IE9 and non-IE
                        sel = window.getSelection();
                        if (sel.getRangeAt && sel.rangeCount) {
                            range = sel.getRangeAt(0);
                            range.deleteContents();

                            // Range.createContextualFragment() would be useful here but is
                            // non-standard and not supported in all browsers (IE9, for one)
                            var el = document.createElement("div");
                            el.innerHTML = html;
                            var frag = document.createDocumentFragment(), node, lastNode;
                            while ( (node = el.firstChild) ) {
                                lastNode = frag.appendChild(node);
                            }
                            range.insertNode(frag);
                            
                            // Preserve the selection
                            if (lastNode) {
                                range = range.cloneRange();
                                range.setStartAfter(lastNode);
                                range.collapse(true);
                                sel.removeAllRanges();
                                sel.addRange(range);
                            }
                        }
                    } else if (document.selection && document.selection.type != "Control") {
                        // IE < 9
                        document.selection.createRange().pasteHTML(html);
                    }
                }
                // - - - - -End -Feature Request: "Speed Text" Buttons Nav - - - - - --//
                /*PP3 end*/   /*PP3 end*/
             </script>';
        return $o;
    } //@ends fn
    public static function learner_scripts_load($course,$USER,$flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass){
        global $DB,$USER,$USER,$CFG,$SESSION;
           //End Commented to invalidate the condition LMS to Salesforce Reporting Errors
            $scertificateObj = $DB->get_record_sql('SELECT id FROM mdl_simplecertificate WHERE course = ? ', array($course->id));

            $certIssueObj = $DB->get_record_sql('SELECT id, timecreated, timeexpired, timecreatedclassroom, haschange, status FROM mdl_simplecertificate_issues WHERE userid = ? AND certificateid = ? AND timecreated is NOT NULL order by id desc', array($USER->id, $scertificateObj->id));
            if(!empty($certIssueObj)){
               $sqlCM1 = 'SELECT count(cm.id) as totalmodules FROM {course_modules} cm where cm.visible=1 and cm.course='.$course->id.' and (cm.module=3 || cm.module=16 || cm.module=15)';
               $rsCM1 = $DB->get_record_sql($sqlCM1);

               $sqlCMC1 = 'SELECT count(cmc.id) as totalcompletedmodules FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cm.id=cmc.coursemoduleid where cmc.userid ='.$USER->id.' AND cm.course='.$course->id.' and cm.visible=1 and (cm.module=3 || cm.module=16 || cm.module=15)';
               $rsCMC1 = $DB->get_record_sql($sqlCMC1);
               //
                if($rsCMC1->totalcompletedmodules < $rsCM1->totalmodules){
                    if(empty($_SESSION['once_per_login_fifth_assign'.$course->id])){
                        $_SESSION['once_per_login_fifth_assign'.$course->id] = 2;
                
                        $o = '<div class="modal fade" id="memberModalNM" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                           <div class="modal-dialog">
                           <div class="modal-content">
                           <div class="modal-header" style="background-color:pink;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                            <h4 class="modal-title" id="memberModalLabel">EEK!</h4>
                           </div>
                           <div class="modal-body">';
                  
                        $getCMObj23 = $DB->get_record('course_modules', array('id' => $id), '*');
                        $rs7123 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=$getCMObj23->instance order by id desc limit 0,1 ");

                        if($rs7123->status != "new"){
                            $sql8123a = "SELECT count(ag.id) as cnt_sub_grade, ag.id, ag.grade FROM {assign_grades} ag where grade >= $gradePass AND ag.assignment=$getCMObj23->instance AND ag.userid=".$USER->id." order by id desc limit 0,1";
                            $rs8123a = $DB->get_record_sql($sql8123a);
                            if($rs8123a->cnt_sub_grade > 0){ 
                              $o .= 'Weve recently updated some of the training curriculum and we want to make sure you learn it on your way to getting certified. Not to worry though, just return to the course and watch these new modules. If you have any questions, simply e-mail <a href="mailto:qsc.training@qsc.com">qsc.training@qsc.com</a>.';
                            }else{ 
                              //if not finished exam
                               $o .= 'Weve recently updated some of the training curriculum and we want to make sure you learn it on your way to getting certified. Not to worry though, just return to the course and watch these new modules before submitting your next exit exam. Look for the new modules that are missing the green checkmark. If you have any questions, simply e-mail <a href="mailto:qsc.training@qsc.com">qsc.training@qsc.com</a>.';
                            } 
                        } 

                        $o .='</div>
                            </div>
                            </div>
                            </div>';   
                        $o .='<script src="/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
                            <script type="text/javascript">
                            //alert("Checktyui");
                                $(document).ready(function () {
                                //console.log("'.$_SESSION['NM'].'");
                                $("#memberModalNM").modal("show");

                            });
                            </script>';
                      //Start Commented to invalidate the condition LMS to Salesforce Reporting Errors
                    }//close for once_per_login_fifth_assign
                
                    $o .='<script type="text/javascript">
                            $(document).ready(function () {
                              $("#assign_passed_msg").html("Weve recently updated some of the training curriculum and we want to make sure you learn it on your way to getting certified. Not to worry though, just return to the course and watch these new modules. If you have any questions, simply e-mail <a href="mailto:qsc.training@qsc.com">qsc.training@qsc.com</a>.");
                              $("#assign_passed_msg").css("background-color","pink");
                              $("#assign_passed_msg").css("font-size","medium");
                          
                            });
                        </script>';

                    //End Commented to invalidate the condition LMS to Salesforce Reporting Errors
                        //56 Est NM end
                }
            } // ends certIssueObj
            return $o;
    }//@fn ends
    public static function passfail_scripts_load($course,$USER,$flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass){
        global $DB,$USER,$USER,$CFG,$SESSION;
        echo '<script>
                $(document).ready(function(){
                    $("#id_grade").attr("readonly","readonly");
                    $("#id_grade").css("background-color","white");
                    $("#id_grade").css("cursor","text");
                    $("#id_grade").on("focus",function(){
                        $("#id_grade").removeAttr("readonly");
                    });

                    //US #2576 start
                    $("#id_passfail").on("change",function() {

                        if(this.value == "2"){
                            //alert(this.value);
                          $("#id_grade").val("100.00");
                          $("#id_addattempt>option:eq(0)").attr("selected", true);
                          $("#id_addattempt>option:eq(1)").attr("selected", false);      
                          $("#id_addattempt").val(0);
                        }else{
                          $("#id_grade").val("1.00");

                          $("#id_addattempt>option:eq(1)").attr("selected", true);
                          $("#id_addattempt>option:eq(0)").attr("selected", false);
                          $("#id_addattempt").val(1);
                        }
                    });
                   
                      var gradepass = "'.$gradePass.'";
		      var dInput = parseInt(document.getElementById("id_grade").value);		  
	              if(dInput >= gradepass){
                            $("#id_passfail>option:eq(2)").attr("selected", true);
                            $("#id_passfail>option:eq(1)").attr("selected", false);
                            $("#id_passfail>option:eq(0)").attr("selected", false);  
                            $("#id_passfail").val(2);

                            $("#id_addattempt>option:eq(0)").attr("selected", true);
                            $("#id_addattempt>option:eq(1)").attr("selected", false);      
                            $("#id_addattempt").val(0);
                        }else{
                            $("#id_addattempt>option:eq(1)").attr("selected", true);
                            $("#id_addattempt>option:eq(0)").attr("selected", false);
                            $("#id_addattempt").val(1);

                            if(isNaN(dInput)){
                              console.log("loop 1");
                              $("#id_passfail>option:eq(1)").attr("selected", false);
                              $("#id_passfail>option:eq(2)").attr("selected", false);
                              $("#id_passfail>option:eq(0)").attr("selected", true); 
                              $("#id_passfail").val(0);
                            }else{
                              console.log("loop 2");
                              $("#id_passfail>option:eq(1)").attr("selected", true);
                              $("#id_passfail>option:eq(2)").attr("selected", false);
                              $("#id_passfail>option:eq(0)").attr("selected", false); 
                              $("#id_passfail").val(1);
                            }
                        } 
                        $("#fitem_id_grade").hide();
                          if(dInput != null){        
                          $("fieldset div").children("div").eq(2).hide();
                          var passfaill = $("#id_passfail").val();
                          console.log("passfail " + passfaill);
                        }
                        //US #2576 end
                        //- - - - - Start- User Comments AFTER Passing Grade - Nav - -----//
                        if($("#assign_passed_msg").is(":visible")){
                          //alert("Call hide");
                          $(".comment-area").hide();
                          $(".comment-area").remove();
                        }
                    //- - - - - End- User Comments AFTER Passing Grade - Nav - -----//
                });
            </script>';
    }//@fn ends
    //Below functions for Dashboard Assign 
    public function load_assign_welcome_view($rsSessData){
        global $DB,$OUTPUT,$SESSION,$USER,$COURSE,$CFG;
        $html = '<!--Dashboard design start-->
                  <div class="newdashboard_design">
                  <div class="newdashboard_welcome_section">
                  <div class="profile_img_section"> 
                  '.$OUTPUT->user_picture($USER, array('size' => 100, 'class' => 'welcome_userpicture')).'
                  </div>
                  <div class="profile_details_section">   
                  <h3>'.get_string("welcomeback","local_assign", ucwords($USER->firstname)).'</h3>
                  <!-- Grader as a Manager Code Start -->
                  <div class="three_tabs_newdashboard">
                  <ul>
                  <li><a href="'.$CFG->wwwroot.'/user/profile.php?id='.$USER->id.'" title="'.get_string("user").''.get_string("profile").'">'.get_string("user").''.get_string("profile").'</a>  <span>|</span></li>
                  <li><a href="'.$CFG->wwwroot.'/grade/report/overview/index.php" title="'.get_string('grades').'">
                  '.get_string('grades').'
                  </a>
                  <span>|</span>
                </li>';

                if($rsSessData){
                    $slashCnt = 0;
                    foreach ($rsSessData as $rsSessDataKey) {        
                        if($rsSessDataKey){
                            $html .='<li>
                                   <a href="'.$CFG->wwwroot.'/mod/facetoface/view.php?f='.$rsSessDataKey->facetofaceid.'&crole=instructor" title="'.get_string('classroom_enrollments','local_assign').'">
                                   '.format_string($rsSessDataKey->name).' Enrollments
                                   </a>';
                            $slashCnt++;
                            if(count($rsSessData) > $slashCnt){
                               $html .= "<span>|</span></li>";
                            }else{
                               $html .= "</li>";
                            }                 
                        }                       
                    }
                }else{
          
                 $html .='<li>
                          <a href="'.$CFG->wwwroot.'/user/preferences.php" title="'.get_string('preferences').'">'.get_string("preferences").'
                          </a>
                         </li>';
                }

                $html .='</ul>
                           </div>
                            <!-- Grader as a Manager Code End -->
                           </div>
                          </div>
                          ';
        return $html;
    } //@ fn ends
    public function filter(){
        global $OUTPUT, $PAGE, $DB, $CFG;
        //starts here====================//
        $output = '<div class="certification_in_progress notification-panel"><h4>Notifications</h4>';
        $output .= '<div class="table-view tabel_base_cls">';
        $output .='<table class = "studentAdminDataTable table-bordered graderpg_cls gradenew_cls" cellpadding="20" cellspacing="10" style="width:100%;">
                        <tr>
                        <td>Total Users
                        <thead>
                            <tr>
                            <th id="col0">'.get_string('grader_studentname','local_assign').'</th>
                            <th id="col1" width="150">'.get_string('company','local_assign').'</th>
                            <th id="col2">'.get_string('grader_status','local_assign').'</th>
                            <th id="col3">'.get_string('grader_coursename','local_assign').'</th>
                            <th id="col4">'.get_string('grader_grade_view','local_assign').'</th>
                            <th id="col5" class="sorting">'.get_string('grader_last_modified','local_assign').'</th>
                            <th id="col6" class=""></th>
                            <th id="col7">'.get_string('grader_file_submissions','local_assign').'</th>
                            <th id="col8" class="sorting">'.get_string('grader_grader','local_assign').'</th>
                            ';              
        $output .=  '</tr>
                    </thead>
                    </table>';
        
        $output .= html_writer::script("$(document).ready(function() {
                                        var imgsrc = M.cfg.wwwroot + '/mod/assign/pix/4V0b.gif';
                                        var oTable = $('.studentAdminDataTable').DataTable({
                                            'processing': true,
                                            'pageLength': 25,
                                            lengthMenu: [[3,15, 25, 50, 100], [3,15, 25, 50, 100]],
                                            'aoColumnDefs': [
                                              { 'iDataSort': 6, 'aTargets': [ 5 ] }
                                            ],
                                            'columns': [
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    {'searchable': false,'orderable': false, },
                                                    null,
                                                    {'bVisible':false,'searchable': false },
                                                    null,
                                                    {'searchable': false,'orderable': false, },    
                                                               
                                                ],
                                            'columnDefs': [ {
                                                'targets': [4,8], // column index (start from 0)
                                                'orderable': false, // set orderable false for selected columns
                                             }],
                                           ajax:{
                                                url : M.cfg.wwwroot + '/local/assign/process.php?action=graderallocation',
                                                data: {module:'".$module."'
                                                },
                                            },
                                            oLanguage: {
                                                sProcessing: '<img src='+imgsrc+'>'
                                                },
                                            'order': [[ 6,'desc']],
                                            'search': {
                                                'smart': true,
                                            },
                                            'bSortClasses' : false, 
                                            'orderClasses': false,
                                            'bAutoWidth' : false,
                                            'bProcessing': true,
                                            'bDeferRender': true,

                                        });
                                        $('.dataTables_filter').css('display','none'); 
                                        //$('.dataTables_info').css('display','block !important');
                                        //$('.dataTables_length').css('float','right !important');
                                        })
                                        "
                                    ); 
        return $output;

    }//@fn ends
    // grader functionlatiy strted
    public function grader_filter(){
        global $OUTPUT, $PAGE, $DB, $CFG;
        //starts here====================//
        $output = '<div class="certification_in_progress notification-panel"><h4>Notifications</h4>';
        $output .= '<div class="table-view tabel_base_cls">';
        $output .='<table class = "studentAdminDataTable table-bordered graderpg_cls gradenew_cls" cellpadding="20" cellspacing="10" style="width:100%;">
                        <tr>
                        <td>Total Users
                        <thead>
                            <tr>
                            <th id="col0">'.get_string('grader_studentname','local_assign').'</th>
                            <th id="col1" width="150">'.get_string('company','local_assign').'</th>
                            <th id="col2">'.get_string('grader_status','local_assign').'</th>
                            <th id="col3">'.get_string('grader_coursename','local_assign').'</th>
                            <th id="col4">'.get_string('grader_grade_view','local_assign').'</th>
                            <th id="col5" class="sorting">'.get_string('grader_last_modified','local_assign').'</th>
                            <th id="col6" class=""></th>
                            <th id="col7">'.get_string('grader_file_submissions','local_assign').'</th>
                            <th id="col8" class="sorting">'.get_string('grader_grader','local_assign').'</th>
                            ';              
        $output .=  '</tr>
                    </thead>
                    </table>';
        
        $output .= html_writer::script("$(document).ready(function() {
                                        var imgsrc = M.cfg.wwwroot + '/mod/assign/pix/4V0b.gif';
                                        var oTable = $('.studentAdminDataTable').DataTable({
                                            'processing': true,
                                            'serverSide': true,
                                            'pageLength': 25,
                                            lengthMenu: [[3,15, 25, 50, -1], [3,15, 25, 50, 'All']],
                                            'aoColumnDefs': [
                                              { 'iDataSort': 6, 'aTargets': [ 5 ] }
                                            ],
                                            'columns': [
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    {'searchable': false,'orderable': false, },
                                                    null,
                                                    {'bVisible':false,'searchable': false },
                                                    null,
                                                    {'searchable': false,'orderable': false, },    
                                                               
                                                ],
                                            'columnDefs': [ {
                                                'targets': [4,8], // column index (start from 0)
                                                'orderable': false, // set orderable false for selected columns
                                             }],
                                           ajax:{
                                                url : M.cfg.wwwroot + '/local/assign/process.php?action=graderview',
                                                data: {module:'".$module."'
                                                },
                                            },
                                            oLanguage: {
                                                sProcessing: '<img src='+imgsrc+'>'
                                                },
                                            'order': [[ 6,'desc']],
                                            'search': {
                                                'smart': true,
                                            },
                                            'bSortClasses' : false, 
                                            'orderClasses': false,
                                            'bAutoWidth' : false,
                                            'bProcessing': true,
                                            'bDeferRender': true,

                                        });
                                        $('.dataTables_filter').css('display','none'); 
                                        //$('.dataTables_info').css('display','block !important');
                                        //$('.dataTables_length').css('float','right !important');
                                        })
                                        "
                                    ); 
        return $output;

    }//@fn ends
    public static function ajax_grader_filter($data){
        global $OUTPUT, $PAGE, $DB, $CFG;
        //starts here====================//
        //print_object($data);die;
        $data = json_encode($data);
        $output = '<div class="certification_in_progress notification-panel"><h4>Notifications</h4>';
        $output .= '<div class="table-view tabel_base_cls">';
        $output .='<table class = "studentAdminDataTable table-bordered graderpg_cls gradenew_cls" cellpadding="20" cellspacing="10" style="width:100%;">
                        <tr>
                        <td>Total Users
                        <thead>
                            <tr>
                            <th id="col0">'.get_string('grader_studentname','local_assign').'</th>
                            <th id="col1" width="150">'.get_string('company','local_assign').'</th>
                            <th id="col2">'.get_string('grader_status','local_assign').'</th>
                            <th id="col3">'.get_string('grader_coursename','local_assign').'</th>
                            <th id="col4">'.get_string('grader_grade_view','local_assign').'</th>
                            <th id="col5" class="sorting">'.get_string('grader_last_modified','local_assign').'</th>
                            <th id="col6" class=""></th>
                            <th id="col7">'.get_string('grader_file_submissions','local_assign').'</th>
                            <th id="col8" class="sorting">'.get_string('grader_grader','local_assign').'</th>
                            ';              
        $output .=  '</tr>
                    </thead>
                    </table>';
        
        $output .= html_writer::script("$(document).ready(function() {
                                        var imgsrc = M.cfg.wwwroot + '/mod/assign/pix/4V0b.gif';
                                        var oTable = $('.studentAdminDataTable').DataTable({
                                            'processing': true,
                                            'serverSide': true,
                                            'pageLength': 25,
                                            lengthMenu: [[3,15, 25, 50, -1], [3,15, 25, 50, 'All']],
                                            'aoColumnDefs': [
                                              { 'iDataSort': 6, 'aTargets': [ 5 ] }
                                            ],
                                            'columns': [
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    {'searchable': false,'orderable': false, },
                                                    null,
                                                    {'bVisible':false,'searchable': false },
                                                    null,
                                                    {'searchable': false,'orderable': false, },    
                                                               
                                                ],
                                            'columnDefs': [ {
                                                'targets': [4,8], // column index (start from 0)
                                                'orderable': false, // set orderable false for selected columns
                                             }],
                                            ajax:{
                                                url : M.cfg.wwwroot + '/local/assign/process.php?action=ajaxgraderview',
                                                data: {module:'".$data."'
                                                },
                                            },
                                            oLanguage: {
                                                sProcessing: '<img src='+imgsrc+'>'
                                                },
                                            'order': [[ 6,'desc']],
                                            'search': {
                                                'smart': true,
                                            },
                                            'bSortClasses' : false, 
                                            'orderClasses': false,
                                            'bAutoWidth' : false,
                                            'bProcessing': true,
                                            'bDeferRender': true,

                                        });
                                        $('.dataTables_filter').css('display','none'); 
                                        //$('.dataTables_info').css('display','block !important');
                                        //$('.dataTables_length').css('float','right !important');
                                        })
                                        "
                                    ); 
        return $output;
    } //@fn ends
    public static function ajax_data($requestData,$count){
        //print_object($requestData['order'][0]['column']);die;
        global $DB,$SITE,$COURSE,$USER;
        $coursecontext = "";
        $coursecontextsecond = "";
        $en = "";
        $limit = " LIMIT ".$requestData['start'].",".$requestData['length'];
        if($requestData['length'] == '-1'){
            $limit = "";
        }else{
            $limit = $limit;
        }
        $display = array();
        //print_object($requestData['order'][0]['column']);die;
        $sorting = $requestData['order'][0]['column'];
        $order = ucwords($requestData["order"][0]["dir"]);
        if($sorting == '0'){
            $sort = 'ORDER BY u.firstname '.$order.'';
        }else if($sorting == '1'){
            $sort = 'ORDER BY u.institution '.$order.'';
        }else if($sorting == '2'){
            $sort = 'ORDER BY s.status '.$order.'';
        }else if($sorting == '3'){
            $sort = 'ORDER BY c.fullname '.$order.'';
        }else if($sorting == '4'){
            //$sort = 'ORDER BY g.timemodified '.$order.'';
        }else if($sorting == '5'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '6'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '7'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else{
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }
        //echo $sort;die;
        if($count){
            $sql = "SELECT count(u.id)
                    FROM mdl_user u 
                    LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                    LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                    LEFT JOIN mdl_course c ON c.id = cm.course
                    LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                    WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))".$sort;
            $lists = $DB->count_records_sql($sql);
        }else{
             $sql = "SELECT s.timemodified AS timesubmitted, u.id,u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.institution, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
                FROM mdl_user u 
                LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                LEFT JOIN mdl_course c ON c.id = cm.course
                LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                WHERE u.id != 5  AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ".$sort."
                ".$limit;
            //echo $sql;die;
             $lists = $DB->get_records_sql($sql);
          
        }
        return $lists;
    }// @fn ends
    //filter data
    public static function ajax_filter($data){
        global $OUTPUT, $PAGE, $DB, $CFG;
        //starts here====================//
        //print_object($data);die;
        $data = json_encode($data);
        $output = '<div class="certification_in_progress notification-panel"><h4>Notifications</h4>';
        $output .= '<div class="table-view tabel_base_cls">';
        $output .='<table class = "studentAdminDataTable table-bordered graderpg_cls gradenew_cls" cellpadding="20" cellspacing="10" style="width:100%;">
                        <tr>
                        <td>Total Users
                        <thead>
                            <tr>
                            <th id="col0">'.get_string('grader_studentname','local_assign').'</th>
                            <th id="col1" width="150">'.get_string('company','local_assign').'</th>
                            <th id="col2">'.get_string('grader_status','local_assign').'</th>
                            <th id="col3">'.get_string('grader_coursename','local_assign').'</th>
                            <th id="col4" class="nosorting">'.get_string('grader_grade_view','local_assign').'</th>
                            <th id="col5" class="sorting">'.get_string('grader_last_modified','local_assign').'</th>
                            <th id="col6" class=""></th>
                            <th id="col7">'.get_string('grader_file_submissions','local_assign').'</th>
                            <th id="col8" class="sorting">'.get_string('grader_grader','local_assign').'</th>
                            ';              
        $output .=  '</tr>
                    </thead>
                    </table>';
        
        $output .= html_writer::script("$(document).ready(function() {
                                        var imgsrc = M.cfg.wwwroot + '/mod/assign/pix/4V0b.gif';
                                        var oTable = $('.studentAdminDataTable').DataTable({
                                            'processing': true,
                                            'serverSide': true,
                                            'pageLength': 25,
                                            lengthMenu: [[3,15, 25, 50, -1], [3,15, 25, 50, 'All']],
                                            'aoColumnDefs': [
                                              { 'iDataSort': 6, 'aTargets': [ 5 ] }
                                            ],
                                            'columns': [
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    {'searchable': false,'orderable': false, },
                                                    null,
                                                    {'bVisible':false,'searchable': false },
                                                    null,
                                                    {'searchable': false,'orderable': false, },    
                                                               
                                                ],
                                            'columnDefs': [ {
                                                'targets': [4,8], // column index (start from 0)
                                                'orderable': false, // set orderable false for selected columns
                                             }],
                                            ajax:{
                                                url : M.cfg.wwwroot + '/local/assign/process.php?action=ajaxgraderallocation',
                                                data: {module:'".$data."'
                                                },
                                            },
                                            oLanguage: {
                                                sProcessing: '<img src='+imgsrc+'>'
                                                },
                                            'order': [[ 6,'desc']],
                                            'search': {
                                                'smart': true,
                                            },
                                            'bSortClasses' : false, 
                                            'orderClasses': false,
                                            'bAutoWidth' : false,
                                            'bProcessing': true,
                                            'bDeferRender': true,

                                        });
                                        $('.dataTables_filter').css('display','none'); 
                                        //$('.dataTables_info').css('display','block !important');
                                        //$('.dataTables_length').css('float','right !important');
                                        $('#col4').find('.sorting').replaceWith('.nosorting');
                                        })
                                        "
                                    ); 
        return $output;
    } //@fn ends
    public static function filter_ajax_data($requestData,$count,$filterdata){
        global $DB,$SITE,$COURSE,$USER;
        $coursecontext = "";
        $coursecontextsecond = "";
        $en = "";
        $limit = " LIMIT ".$requestData['start'].",".$requestData['length'];
        if($requestData['length'] != '-1'){
            
            $limit = $limit;
        }else{
            $limit = "";
        }
        $sorting = $requestData['order'][0]['column'];
        $order = ucwords($requestData["order"][0]["dir"]);
        if($sorting == '0'){
            $sort = 'ORDER BY u.firstname '.$order.'';
        }else if($sorting == '1'){
            $sort = 'ORDER BY u.institution '.$order.'';
        }else if($sorting == '2'){
            $sort = 'ORDER BY s.status '.$order.'';
        }else if($sorting == '3'){
            $sort = 'ORDER BY c.fullname '.$order.'';
        }else if($sorting == '4'){
            //$sort = 'ORDER BY g.timemodified '.$order.'';
        }else if($sorting == '5'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '6'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '7'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else{
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }
        $display = array();
        if($count){
            if($filterdata){
                //print_object($filterdata);die;
                if($filterdata->grader !='' && !empty($filterdata->grader) && !is_siteadmin($filterdata->grader)){
                    $sql = "SELECT count(u.id)
                        FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1 LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                          LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                          LEFT JOIN {course} c ON c.id = cm.course
                          LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                        WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$filterdata->grader." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                    $sql .=" ".$sort;

                }else{
                    $sql = "SELECT count(u.id)
                            FROM mdl_user u 
                            LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                            LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                            LEFT JOIN mdl_course c ON c.id = cm.course
                            LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                            WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                     
                    if($filterdata->sname !='' && !empty($filterdata->sname)){
                       $sql .= " AND u.firstname like '%".$filterdata->sname."%'";
                    }
                    if($filterdata->course !='' && !empty($filterdata->course) && $filterdata->course){
                        $sql .= " AND c.id=".$filterdata->course;
                    }
                    if($filterdata->submission !='' && !empty($filterdata->submission) && $filterdata->submission){
                        $day = $filterdata->submission->day;
                        $month = $filterdata->submission->month;
                        $year = $filterdata->submission->year;
                        $stime = mktime(0,0,0,$month,$day,$year);
                        $rstime = strtotime(date("M-d-Y",$stime));
                        $sql .= " AND s.timemodified >=".strtotime(date("M-d-Y",$stime));
                        //echo strtotime(date("M-d-Y",$stime));die;
                    }
                    if($filterdata->institution !='' && $filterdata->institution){
                        $sql .= " AND u.institution like '%".$filterdata->institution."%'";
                    }
                    if($filterdata->status !='' && $filterdata->status){
                        $sql .= " AND s.status like '%".$filterdata->status."%'";
                    }
                    $sql .=" ".$sort." ";
                }//not grader
             }else{
                $sql = "SELECT count(u.id)
                    FROM mdl_user u 
                    LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                    LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                    LEFT JOIN mdl_course c ON c.id = cm.course
                    LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                    WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ".$sort."";
             }
            
            $lists = $DB->count_records_sql($sql);
        }else{
             if($filterdata){
                if($filterdata->grader && $filterdata->grader !='' && !is_siteadmin($filterdata->grader)){
                   $sql = "SELECT s.timemodified as timesubmitted,gr.id as agid,u.id,gr.grader_id,u.picture,u.firstname,u.lastname,u.firstnamephonetic,u.lastnamephonetic,u.institution, u.middlename,u.alternatename,u.imagealt,u.email, u.id as userid, gr.exam_id as course_modules_id, cm.course as course_id, cm.instance as assignmentid, c.fullname as course_name, c.visible AS course_visible, s.status as status, s.id as assign_submission_id, s.timecreated as firstsubmission, s.attemptnumber as attemptnumber, g.id as gradeid, g.grade as grade, g.timecreated as firstmarked
                    FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1 LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                      LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                      LEFT JOIN {course} c ON c.id = cm.course
                      LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                    WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$filterdata->grader." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                    $sql .=" ".$sort." ".$limit;
                }else{
                    $sql = "SELECT s.timemodified AS timesubmitted, u.id,u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.institution, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
                    FROM mdl_user u 
                    LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                    LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                    LEFT JOIN mdl_course c ON c.id = cm.course
                    LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                    WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                    
                    if($filterdata->sname !='' && !empty($filterdata->sname)){
                       $sql .= " AND u.firstname like '%".$filterdata->sname."%'";
                    }
                    if($filterdata->course !='' && !empty($filterdata->course) && $filterdata->course && !is_null($filterdata->course)){
                        $sql .= " AND c.id=".$filterdata->course;
                    }
                    if($filterdata->submission !='' && !empty($filterdata->submission) && $filterdata->submission){
                        $day = $filterdata->submission->day;
                        $month = $filterdata->submission->month;
                        $year = $filterdata->submission->year;
                        $stime = mktime(0,0,0,$month,$day,$year);
                        $rstime = strtotime(date("M-d-Y",$stime));
                        $sql .= " AND s.timemodified >=".strtotime(date("M-d-Y",$stime));
                        //echo strtotime(date("M-d-Y",$stime));die;
                    }
                    if($filterdata->institution !='' && $filterdata->institution){
                        $sql .= " AND u.institution like '%".$filterdata->institution."%'";
                    }
                    if($filterdata->status !='' && $filterdata->status){
                        $sql .= " AND s.status like '%".$filterdata->status."%'";
                    }
                    $sql .=" ".$sort." ".$limit;
                }
             }else{
                $sql = "SELECT s.timemodified AS timesubmitted, u.id,u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.institution, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
                FROM mdl_user u 
                LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                LEFT JOIN mdl_course c ON c.id = cm.course
                LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ".$sort."
                ".$limit;
             }
             
            //echo $sql;die;
            //print_r($filterdata);die;
             $lists = $DB->get_records_sql($sql);
          
        }
        return $lists;
    }// @fn ends
    public static function grader_ajax_data($requestData,$count){
        global $DB,$SITE,$COURSE,$USER;
        $coursecontext = "";
        $coursecontextsecond = "";
        $en = "";
        $limit = " LIMIT ".$requestData['start'].",".$requestData['length'];
        if($requestData['length'] == '-1'){
            $limit = "";
        }else{
            $limit = $limit;
        }
        $sorting = $requestData['order'][0]['column'];
        $order = ucwords($requestData["order"][0]["dir"]);
        if($sorting == '0'){
            $sort = 'ORDER BY u.firstname '.$order.'';
        }else if($sorting == '1'){
            $sort = 'ORDER BY u.institution '.$order.'';
        }else if($sorting == '2'){
            $sort = 'ORDER BY s.status '.$order.'';
        }else if($sorting == '3'){
            $sort = 'ORDER BY c.fullname '.$order.'';
        }else if($sorting == '4'){
            //$sort = 'ORDER BY g.timemodified '.$order.'';
        }else if($sorting == '5'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '6'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '7'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else{
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }
        $display = array();
        if($count){
            $sql = "SELECT count(u.id)
                  FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1 LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                      LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                      LEFT JOIN {course} c ON c.id = cm.course
                      LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                      WHERE s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$USER->id." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ".$sort."";
            $lists = $DB->count_records_sql($sql);
        }else{
             $sql = "SELECT s.timemodified as timesubmitted,g.timemodified as timemarked,gr.id as agid,u.id,gr.grader_id,u.picture,u.firstname,u.lastname,u.firstnamephonetic,u.lastnamephonetic,u.institution, u.middlename,u.alternatename,u.imagealt,u.email, u.id as userid, gr.exam_id as course_modules_id, cm.course as course_id, cm.instance as assignmentid, c.fullname as course_name, c.visible AS course_visible, s.status as status, s.id as assign_submission_id, s.timecreated as firstsubmission,  s.attemptnumber as attemptnumber, g.id as gradeid, g.grade as grade, g.timecreated as firstmarked
                  FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1 LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                      LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                      LEFT JOIN {course} c ON c.id = cm.course
                      LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                      WHERE s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$USER->id." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
            //echo $sql;die;
            $sql .=" ".$sort." ".$limit; 
            //echo $sql;die; 
            $lists = $DB->get_records_sql($sql);
          
        }
        return $lists;
    }// @fn ends
    public static function grader_filter_ajax_data($requestData,$count,$filterdata){
        global $DB,$SITE,$COURSE,$USER;
        $coursecontext = "";
        $coursecontextsecond = "";
        $en = "";
        $limit = " LIMIT ".$requestData['start'].",".$requestData['length'];
        if($requestData['length'] == '-1'){
            $limit = "";
        }else{
            $limit = $limit;
        }
        $sorting = $requestData['order'][0]['column'];
        $order = ucwords($requestData["order"][0]["dir"]);
        if($sorting == '0'){
            $sort = 'ORDER BY u.firstname '.$order.'';
        }else if($sorting == '1'){
            $sort = 'ORDER BY u.institution '.$order.'';
        }else if($sorting == '2'){
            $sort = 'ORDER BY s.status '.$order.'';
        }else if($sorting == '3'){
            $sort = 'ORDER BY c.fullname '.$order.'';
        }else if($sorting == '4'){
            //$sort = 'ORDER BY g.timemodified '.$order.'';
        }else if($sorting == '5'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '6'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else if($sorting == '7'){
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }else{
            $sort = 'ORDER BY s.timemodified '.$order.'';
        }
        $display = array();
        if($count){
            if($filterdata){
                //print_object($filterdata);die;
                $sql = "SELECT count(u.id)
                    FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1 LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                      LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                      LEFT JOIN {course} c ON c.id = cm.course
                      LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$USER->id." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                if($filterdata->sname !='' && !empty($filterdata->sname)){
                   $sql .= " AND u.firstname like '%".$filterdata->sname."%'";
                }
                if($filterdata->course !='' && !empty($filterdata->course) && $filterdata->course){
                    $sql .= " AND c.id=".$filterdata->course;
                }
                if($filterdata->submission !='' && !empty($filterdata->submission) && $filterdata->submission){
                    $day = $filterdata->submission->day;
                    $month = $filterdata->submission->month;
                    $year = $filterdata->submission->year;
                    $stime = mktime(0,0,0,$month,$day,$year);
                    $rstime = strtotime(date("M-d-Y",$stime));
                    $sql .= " AND s.timemodified >=".strtotime(date("M-d-Y",$stime));
                    //echo strtotime(date("M-d-Y",$stime));die;
                }
                if($filterdata->institution !='' && $filterdata->institution){
                    $sql .= " AND u.institution like '%".$filterdata->institution."%'";
                }
                if($filterdata->status !='' && $filterdata->status){
                    $sql .= " AND s.status like '%".$filterdata->status."%'";
                }
                $sql .=" ".$sort." ";
             }else{
                $sql = "SELECT count(u.id)
                        FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1  LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                        LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                        LEFT JOIN {course} c ON c.id = cm.course
                        LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                        WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$USER->id." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ";
                $sql .=" ".$sort." ";
             }
            
            $lists = $DB->count_records_sql($sql);
        }else{
             if($filterdata){
                $sql = "SELECT s.timemodified as timesubmitted,g.timemodified as timemarked,gr.id as agid,u.id,gr.grader_id,u.picture,u.firstname,u.lastname,u.firstnamephonetic,u.lastnamephonetic,u.institution, u.middlename,u.alternatename,u.imagealt,u.email, u.id as userid, gr.exam_id as course_modules_id, cm.course as course_id, cm.instance as assignmentid, c.fullname as course_name, c.visible AS course_visible, s.status as status, s.id as assign_submission_id, s.timecreated as firstsubmission, s.attemptnumber as attemptnumber, g.id as gradeid, g.grade as grade, g.timecreated as firstmarked
                    FROM {assign_graders} gr RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != 1 LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module =1
                      LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance
                      LEFT JOIN {course} c ON c.id = cm.course
                      LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                    WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$USER->id." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                if($filterdata->sname !='' && !empty($filterdata->sname)){
                   $sql .= " AND u.firstname like '%".$filterdata->sname."%'";
                }
                if($filterdata->course !='' && !empty($filterdata->course) && $filterdata->course && !is_null($filterdata->course)){
                    $sql .= " AND c.id=".$filterdata->course;
                }
                if($filterdata->submission !='' && !empty($filterdata->submission) && $filterdata->submission){
                    $day = $filterdata->submission->day;
                    $month = $filterdata->submission->month;
                    $year = $filterdata->submission->year;
                    $stime = mktime(0,0,0,$month,$day,$year);
                    $rstime = strtotime(date("M-d-Y",$stime));
                    $sql .= " AND s.timemodified >=".strtotime(date("M-d-Y",$stime));
                    //echo strtotime(date("M-d-Y",$stime));die;
                }
                if($filterdata->institution !='' && $filterdata->institution){
                    $sql .= " AND u.institution like '%".$filterdata->institution."%'";
                }
                if($filterdata->status !='' && $filterdata->status){
                    $sql .= " AND s.status like '%".$filterdata->status."%'";
                }
                $sql .=" ".$sort." ".$limit;
             }else{
                $sql = "SELECT s.timemodified AS timesubmitted, u.id,u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.institution, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
                FROM mdl_user u 
                LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                LEFT JOIN mdl_course c ON c.id = cm.course
                LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND gr.grader_id=".$USER->id." AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
                $sql .=" ".$sort." ".$limit;
             }
             
            //echo $sql;die;
            //print_r($filterdata);die;
             $lists = $DB->get_records_sql($sql);
          
        }
        return $lists;
    }// @fn ends
    //function for grader selection drop
    public function grader_select($user_id,$grader_id,$course_modules_id,$rowCount,$contextid){
        global $USER,$DB,$COURSE,$CFG;
        $sqlRole = "SELECT id FROM {role} WHERE shortname='grader'";
        $rsRole = $DB->get_record_sql($sqlRole);
        //
        $sqlmdl = 'SELECT * FROM {context} WHERE id ='. (int) $contextid;
        $rsMdl = $DB->get_record_sql($sqlmdl);
        $path = $rsMdl->path;
        $pathCNT = $rsMdl->depth-1;
        $patharray = explode("/",$path);

        $sqlRoleAssignment = "SELECT u.id,u.firstname,u.lastname,u.email,ra.contextid FROM {role_assignments} ra JOIN {user} u ON ra.userid=u.id where ra.contextid=".$patharray[$pathCNT]." and ra.roleid=".$rsRole->id;

        $graderObjData = $DB->get_records_sql($sqlRoleAssignment);
          
        $a = array(0=>"Select");
        $graders = array_merge($a, $graderObjData);
        //print_object($graders);die;
        $selectcol = "<select name=adminGradingDD_".$rowCount."_".$user_id." id=adminGradingDD_".$rowCount."_".$user_id." class='grader_pulldown' onchange='grader_dropdown(this)'>";

        $selected = "";
        $selectedGrader = "";
      
        foreach ($graders as $key=>$values) {
         //if defined $yourselectedGrader
            if($key != 0){
                // $examid = (int) $course_modules_id;
                // $studentid = $user_id;
                $gQuery = "SELECT grader_id FROM {assign_graders} WHERE exam_id = $course_modules_id AND student_id = $user_id";
                $graderObjData = $DB->get_record_sql($gQuery);
                          
                if ($graderObjData->grader_id == $values->id) {
                    //if ($grader_id == $values->id) {
                    $selected = "selected";
                    $selectedGrader = $values->firstname." ".$values->lastname; 
                }else{
                    $selected = "";
                }
                $name = $values->firstname." ".$values->lastname;
                $arr = array((int) $values->id, (int) $user_id, (int) $course_modules_id);
                // $values->id = Grader id, $user_id = student id
                $val = json_encode($arr);
            }else{
              $name = "Administrator";
              $selectedGrader = "Administrator"; 
              $arr = array(2, (int) $user_id, (int) $course_modules_id);
              $val = json_encode($arr);
            }
            $selectcol .= "<option value='".$val."' ".$selected.">" . $name . "</option>";
        }
        $selectcol .= "</select>";
        $arr = array($selectcol,$selectedGrader);
        return $arr;
    } //@fn ends

}
