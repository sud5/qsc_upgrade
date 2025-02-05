<?php
defined('MOODLE_INTERNAL') || die();
//RCP start
$_SESSION['flagNewModuleAdded']=0;
//RCP end
//1069 Start
$_SESSION['proceed_restrict']=0;
//1069 End

//echo "<pre>"; print_r($USER); exit;
//ALTER TABLE `mdl_book` ADD `displaytime` INT NOT NULL AFTER `name` ;
function secondsToTime($ss) {
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
    elseif($s != 0)
      return "0m ".$s."s";
  }
  else{
    return "0m 0s";
  }

}
date_default_timezone_set($USER->timezone);
$newflag=0;
$certificate_in_process = "0";

$course_completions_data = $DB->get_record('course_completions', array('userid'=>$USER->id, 'course' => $course->id), "*");

//US #3819 add field admin_reset_flag
$coursesModulesDataSQL = "SELECT cm.id, cm.course, cmc.admin_reset_flag FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =".$course->id." and cmc.userid=".$USER->id;
$coursesModulesDataRes = $DB->get_records_sql($coursesModulesDataSQL);
//print_r($coursesModulesDataRes); exit;
if(empty($coursesModulesDataRes)){
  if(!empty($course_completions_data)){
//    $dataccmp->id = $course_completions_data->id;
    $course_completions_data->timeenrolled = time();
    $DB->update_record('course_completions', $course_completions_data);
  }
}

//US #3819 start
if(!empty($coursesModulesDataRes)){
$validFlag=0;
  foreach($coursesModulesDataRes as $resetCmcKey){
    //print_r($resetCmcKey);
    $adminResetFlag = $resetCmcKey->admin_reset_flag;
    $cmid = $resetCmcKey->id;
    if($adminResetFlag == 1){

      if($cmid == $id){
         // echo $cmid ."==". $id; exit;
        $update_header = "update mdl_course_modules_completion set admin_reset_flag = 0, viewed=1, completionstate=1 where coursemoduleid = ".$cmid." and userid=".$USER->id;
        $DB->execute($update_header);
      }
      if($validFlag != 1){
        $validFlag = $adminResetFlag;
      }
    }
  }
}
//US #3819 end

// $coursesModulesDataSQL = "SELECT cm.id, cm.course FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =".$course->id." and cmc.userid=".$USER->id;
// $coursesModulesDataRes = $DB->get_records_sql($coursesModulesDataSQL);
// //print_r($course_completions_data); exit;
// if(empty($coursesModulesDataRes)){
//   if(!empty($course_completions_data)){
// //    $dataccmp->id = $course_completions_data->id;
//     $course_completions_data->timeenrolled = time();
//     $DB->update_record('course_completions', $course_completions_data);
//   }
// }


// We cheat a bit here in assuming that viewing the last page means the user viewed the whole book.
if (!isguestuser()) {
  $completion = new completion_info($course);
  $completion->set_module_viewed($cm);

$certificateTimeLogObj = $DB->get_record_sql('SELECT id, timecompletion FROM mdl_simplecertificate_issue_logs WHERE userid = ? AND courseid = ? AND timecompletion is NOT NULL order by id desc LIMIT 0,1', array( $USER->id, $course->id ));
//echo "<pre>"; print_r($certificateTimeLogObj); exit;
$scertificateObj = $DB->get_record_sql('SELECT id FROM mdl_simplecertificate WHERE course = ? ', array( $course->id ));

$certificateIssueTimeLogObj = $DB->get_record_sql('SELECT id, timecreated, timeexpired, timecreatedclassroom, haschange, status FROM mdl_simplecertificate_issues WHERE userid = ? AND certificateid = ? AND timecreated is NOT NULL order by id desc', array( $USER->id, $scertificateObj->id ));
}
//}
/*$courseSecData = $DB->get_record('course_sections', array('id'=>$cm->section), 'sequence,course, summary, id', MUST_EXIST);

$context = context_course::instance($courseSecData->course);
$summarytext = file_rewrite_pluginfile_urls($courseSecData->summary, 'pluginfile.php',$context->id, 'course', 'section', $courseSecData->id);
$summaryData = format_text($summarytext, $courseSecData->summaryformat, array('noclean'=>true, 'overflowdiv'=>true));

$regex = '#\<div class="text_to_html"\>(.+?)\<\/div\>#';
preg_match($regex, $summaryData, $matches);

$match = $matches[0];

$regextwo = '#\<p\>(.+?)\<\/p\>#';
preg_match($regextwo, $matches[0], $matchestwo);

$regexthree = '#\<img src=\"(.*?)\".*\>#';
preg_match($regexthree, $matchestwo[0], $matchesthree);

$r = explode($matchestwo[0],$matches[0]);
$summaryTxtRaw = strip_tags($r[1]);
$lessonImg = $matchesthree[1];*/

//GetCategory
$category = $DB->get_record('course_categories', array('id'=>$course->category), 'id, name', MUST_EXIST);

// start - updated for private course
//$moduleDetailObj = $DB->get_records('course', array('category'=>$course->category, 'visible' => 1), '', "id, fullname");
$moduleDetailObj = $DB->get_records('course', array('category'=>$course->category), '', "id, fullname");
//end - updated for private course

//Check first course time
$cmcObj = $DB->get_records('course_modules_completion', array('userid' => $USER->id), 'id ASC','*');

if($cmcObj){
  foreach ($cmcObj as $keyCMC) {
    # code...
    $cmObjData = $DB->get_record('course_modules', array('id'=>$keyCMC->coursemoduleid,'course'=>$course->id), "*");
    if($cmObjData){
      $timeForFinalExamAccess = $keyCMC->timemodified;
      break;
    }
  }
}
$i=0;
$renewFLAG = 0;
$assignURL = '';
foreach($moduleDetailObj as $keyModules){
  
  if($keyModules->id == $course->id){
    $sectionData = $DB->get_records('course_sections', array('course'=>$keyModules->id), '', "id, name,sequence");
//CCC start
        $sql61 = "SELECT * FROM {assign} where course=".$course->id." and type='online'";
        $rs61 = $DB->get_record_sql($sql61);


        $cmAssignDate['DateDuration'] = $DB->get_record('course_modules', array('course'=>$keyModules->id, 'module'=>1,'visible'=>1,'instance'=>$rs61->id), 'id,added');
        
            //CCC ends
   $jk=0;
   
foreach ($sectionData as $key) {
  //print_r($key->id); exit;
  if($jk >= 1){
    $sequence = $key->sequence;
    $cmIdsArr[$key->id] = explode(",", $sequence);
    $seqInt = 0;
    foreach($cmIdsArr[$key->id] as $seqArr){


      $cmObjCourse = $DB->get_record('course_modules', array('id'=>$seqArr,'module'=>3,'visible'=>1,'deletioninprogress'=>0), "*");  
      $cmObjCoursePage = $DB->get_record('course_modules', array('id'=>$seqArr,'module'=>15,'visible'=>1,'deletioninprogress'=>0), "*"); 


      if(!empty($cmObjCourse)){
        $cmCourse[$seqInt][$key->id][0][$cmObjCourse->id] = $cmObjCourse;
        $seqInt++;
      }
      if(!empty($cmObjCoursePage)){
        $cmCourse[$seqInt][$key->id][0][$cmObjCoursePage->id] = $cmObjCoursePage;
        $seqInt++;
      }
    }
    

    $cmQuiz[$key->id][] = $DB->get_records('course_modules', array('section'=>$key->id,'module'=>16,'visible'=>1,'deletioninprogress'=>0), '', "*");

    //CCC start
            $sql61 = "SELECT * FROM {assign} where course=".$course->id." and type='online'";
            $rs61 = $DB->get_record_sql($sql61);

            $cmAssign[$key->id][] = $DB->get_record('course_modules', array('section'=>$key->id, 'module'=>1,'visible'=>1,'instance'=>$rs61->id), 'id,added');

            //CCC ends
    $cmCertificate[$key->id][] = $DB->get_records('course_modules', array('section'=>$key->id,'module'=>25), '', "id,added");  

//We need modulename remains assignment
  if(empty($cmCertificate[$key->id][0])){
    if($key->name != ''){
        $lessonNameArr[$i]['module_name'] = $key->name;
    }
  for ($cs=0; $cs < count($cmCourse); $cs++) {
    if(!empty($cmCourse[$cs][$key->id][0])){
      $modCalTime = 0;
      $r=0;
      $j=0;
      
      foreach ($cmCourse as $keyCMs) {
        
          foreach($keyCMs[$key->id][0] as $keyCM){
        
            if($keyCM->instance != 0){
              $lessonNameArr[$i][$j]['Lesson'] = $keyCM->id;
              $lessonCompletionObj = $lessonNameArr[$i][$j]['LessonCompletionData'] = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCM->id, 'userid' => $USER->id), '*');
              $instance = $keyCM->instance;

            if($keyCM->module == 3){
              $bookLeftPanel = $DB->get_record('book', array('id'=>$instance), 'name, displaytime');
              $lessonNameArr[$i][$j]['book_name'] = $bookLeftPanel->name;
              $bookNameOnPage = $bookLeftPanel->name;

                $lessonNameArr[$i][$j]['added'] = $keyCM->added;

              $lessonNameArr[$i][$j]['book_time'] = secondsToTime(format_string($bookLeftPanel->displaytime));
              $modCalTime = (int)format_string($bookLeftPanel->displaytime) + $modCalTime;
              $lessonNameArr[$i][$j]['book_id'] = $instance;
            }

            if($keyCM->module == 15){
              $pageLeftPanel = $DB->get_record('page', array('id'=>$instance), 'name, content');
              $lessonNameArr[$i][$j]['page_name'] = $pageLeftPanel->name;
              $lessonNameArr[$i][$j]['added'] = $keyCM->added;
              
              

              $lessonNameArr[$i][$j]['page_id'] = $instance;
              $modCalTime=$modCalTime;
            }
if($classroomtypeflag!=0){
if($certificateIssueTimeLogObj->timecreatedclassroom != ''){
$classroomCertIssueTime = strtotime($certificateIssueTimeLogObj->timecreatedclassroom);
        //Update record in haschange 0 copy timecreatedclassroom timestring in timecreated

        if($certificateIssueTimeLogObj->haschange == 0){
//Timezone mistake
$classroomCertIssueTime = $classroomCertIssueTime+20000;
            $certificateIssueTimeLogObj->timecreated = $classroomCertIssueTime;

            //$DB->update_record('simplecertificate_issues', $certificateIssueTimeLogObj);
        }
}
}

              if(is_object($lessonCompletionObj) && !empty($lessonCompletionObj)){
                $lessonNameArr[$i][$j]['lessoncompletionStatus'] = $lessonCompletionObj->completionstate; // 1, 2 is for completion
                $lessonNameArr[$i][$j]['newlyupdated'] = 0;                
              }else{
                $lessonNameArr[$i][$j]['lessoncompletionStatus'] = 0; // 0 is for noncompletion
                if(is_object($certificateTimeLogObj)){
                  $renewFLAG = 1;
                  $keyCertVal = $DB->get_record('simplecertificate', array('course'=>$course->id), "certexpirydateinyear");
                  
                  $certExpDurationDate = $keyCertVal->certexpirydateinyear;
                  if($certificateIssueTimeLogObj->timeexpired == 0 || $certificateIssueTimeLogObj->timeexpired == ''){
                    $certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
                  }
                  else{
                    $certExpiredDate = $certificateIssueTimeLogObj->timeexpired;
                  }
                  //$certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
//RCP start
$beforeCertExpiredThirtyDaysTime = strtotime("-1 month",$certExpiredDate);
  //$beforeCertExpiredThirtyDaysTime = $certExpiredDate - 2592000; //Get one month before date to the expired date
//RCP end
                  //$beforeCertExpiredThirtyDaysTime = $certExpiredDate - 2592000; //Get one month before date to the expired date
                  $todayTime = time(); // get current date
                  if($beforeCertExpiredThirtyDaysTime <= $todayTime){
                    $lessonNameArr[$i][$j]['newlyupdated'] = 1;
                  }
                }
              
                if($classroomtypeflag==0){

                  //For star icon if new lesson added after certification
                  if($keyCM->added >= $certificateIssueTimeLogObj->timecreated && is_object($certificateIssueTimeLogObj)){
                    $lessonNameArr[$i][$j]["newadded"] = 1;
                    $newflags=1;
                  }
                  else{
                    $lessonNameArr[$i][$j]["newadded"] = 0; 
                  }


                  //For star icon if new lesson added before certification intially
//Star
                  if($course_completions_data->timeenrolled == 0){
                    $courseenrolledTime=$course_completions_data->timestarted;
                  }
                  else{
                    $courseenrolledTime=$course_completions_data->timeenrolled;
                  }
//Star
                  if($course_completions_data->timestarted!=1 and $keyCM->added >= $courseenrolledTime && is_object($course_completions_data)){
                    $lessonNameArr[$i][$j]["newadded"] = 1;
                    $lessonNameArr[$i][$j]["debug"] = 1;

                    $newflagsintial=1;
                  }

                }// end if classroomtypeflag not set==0
                else{
                  $chkCMCLessonExist = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCM->id, 'userid' => $USER->id), '*');
                  if(empty($chkCMCLessonExist)){
                      //Timezone mistake
                      $classroomCertIssueTime = $classroomCertIssueTime+20000;

                      //For star icon if new lesson added after certification
                      if($keyCM->added <= $classroomCertIssueTime && is_object($certificateIssueTimeLogObj)){
                        $lessonNameArr[$i][$j]["newadded"] = 0;
                        //Insert record in course_modules_completion on intial level for classroom student
                              
                        $datacmc->coursemoduleid = $keyCM->id;
                        $datacmc->timemodified = time();
                        $datacmc->completionstate = 1;
                        $datacmc->userid = $USER->id;
                        $datacmc->viewed = 1;

                        $DB->insert_record('course_modules_completion', $datacmc);
                        
                        $lessonNameArr[$i][$j]['lessoncompletionStatus'] = 1;

                        $newflags=1;
                      }
                      else{
                        $lessonNameArr[$i][$j]['newlyupdated'] = 0;
                        $lessonNameArr[$i][$j]["newadded"] = 1; 
                      }
                  }
                  //For star icon if new lesson added before certification intially
                }
              //course_completions_data
              }
              $lessonNameArr[$i][$j]['timing'] = 0;
            }
            $j++;
          }                               
      }
      $lessonNameArr[$i]['module_time'] = secondsToTime($modCalTime);
    }//endif
  }
    foreach ($cmQuiz[$key->id] as $keyCMQs) {
      foreach ($keyCMQs as $keyCMQ) {
      $lessonNameArr[$i]["Quizinstance"][] = $keyCMQ->instance;
      $lessonNameArr[$i]["Quiz"][] = $keyCMQ->id;

      $lessonNameArr[$i]["QuizAdded"][] = $keyCMQ->added;

      $quizGradeData = get_coursemodule_from_id('quiz', $keyCMQ->id, 0, false, MUST_EXIST);

      $lessonNameArr[$i]["RequireGrade"][] = $DB->get_record('grade_items', array('courseid'=>$course->id,'iteminstance'=>$quizGradeData->instance), 'gradepass');

       $lessonNameArr[$i]["QuizGrade"] = $DB->get_fieldset_sql('SELECT grade FROM {quiz_grades} where quiz="'. $quizGradeData->instance.'" and userid ="'.$USER->id.'" order by grade desc limit 0,1'); 
       
       if($lessonNameArr[$i]["QuizGrade"][0]==''){
      $lessonNameArr[$i]["QuizGrade"] = $DB->get_fieldset_sql('SELECT sumgrades FROM {quiz_attempts} where quiz="'. $quizGradeData->instance.'" and userid ="'.$USER->id.'" and state="finished" order by id DESC limit 0,1');  
        }        

      $quizCompletionObj = $lessonNameArr[$i]['QuizCompletionData'] = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCMQ->id, 'userid' => $USER->id), '*');
      if(is_object($quizCompletionObj) && !empty($quizCompletionObj)){
        $lessonNameArr[$i]['quizcompletionStatus'] = $quizCompletionObj->completionstate; // 0 is for noncompletion
        $lessonNameArr[$i]['newlyupdated'] = 0;
      }else{
        $lessonNameArr[$i]['quizcompletionStatus'] = 0; // 0 is for noncompletion
        if(is_object($certificateTimeLogObj)){
          $renewFLAG = 1;
          $keyCertVal = $DB->get_record('simplecertificate', array('course'=>$course->id), "certexpirydateinyear");
          $certExpDurationDate = $keyCertVal->certexpirydateinyear;

          if($certificateIssueTimeLogObj->timeexpired == 0 || $certificateIssueTimeLogObj->timeexpired == ''){
            $certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
          }
          else{
            $certExpiredDate = $certificateIssueTimeLogObj->timeexpired;
          }

          //$certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
          
//RCP start
$beforeCertExpiredThirtyDaysTime = strtotime("-1 month",$certExpiredDate);
  //$beforeCertExpiredThirtyDaysTime = $certExpiredDate - 2592000; //Get one month before date to the expired date
//RCP end
         
          $todayTime = time(); // get current date
          //echo "$beforeCertExpiredThirtyDaysTime <= $todayTime";
          if($beforeCertExpiredThirtyDaysTime <= $todayTime){
            $lessonNameArr[$i]['newlyupdated'] = 1;
          }
        }

      if($classroomtypeflag==0){
        $lessonNameArr[$i]["QuizGradeClassroomFlag"] = 0;
        //For star icon if new quiz added after certification 
        if($keyCMQ->added >= $certificateIssueTimeLogObj->timecreated && is_object($certificateIssueTimeLogObj)){
          $lessonNameArr[$i]["NewQuizAdded"][] = 1;
          $newflags=1;
        }
        else{
          $lessonNameArr[$i]["NewQuizAdded"][] = 0; 
        }

        //For star icon if new lesson added before certification intially
 //Star
         if($course_completions_data->timeenrolled == 0){
                    $courseenrolledTimeQ=$course_completions_data->timestarted;
                  }
                  else{
                    $courseenrolledTimeQ=$course_completions_data->timeenrolled;
                  }
        //Star
        if($course_completions_data->timestarted!=1 and $keyCMQ->added >= $courseenrolledTimeQ && is_object($course_completions_data)){
          $lessonNameArr[$i]["NewQuizAdded"][] = 1;
          $newflagsintial=1;
        }

      }//end if classroomtypeflag
      else{
$lessonNameArr[$i]["QuizGradeClassroomFlag"] = 1;
        $classroomCertIssueTime = strtotime($certificateIssueTimeLogObj->timecreatedclassroom);


if($certificateIssueTimeLogObj->timecreatedclassroom != ''){
$classroomCertIssueTime = strtotime($certificateIssueTimeLogObj->timecreatedclassroom);
        //Update record in haschange 0 copy timecreatedclassroom timestring in timecreated
        if($certificateIssueTimeLogObj->haschange == 0){
//Timezone mistake
$classroomCertIssueTime = $classroomCertIssueTime+20000;
            $certificateIssueTimeLogObj->timecreated = $classroomCertIssueTime;

            //$DB->update_record('simplecertificate_issues', $certificateIssueTimeLogObj);
        }
}

        //For star icon if new quiz added after certification 
        if($keyCMQ->added <= $classroomCertIssueTime && is_object($certificateIssueTimeLogObj)){
          $lessonNameArr[$i]["NewQuizAdded"][] = 0;
          //Insert record in course_modules_completion on intial level for classroom student
                
          $chkCMCQuizExist = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCMQ->id, 'userid' => $USER->id), '*');
          if(empty($chkCMCQuizExist)){
            $datacmcq->coursemoduleid = $keyCMQ->id;
            $datacmcq->timemodified = time();
            $datacmcq->completionstate = 1;
            $datacmcq->userid = $USER->id;
            $datacmcq->viewed = 1;
            $DB->insert_record('course_modules_completion', $datacmcq);

            if(empty($lessonNameArr[$i]["QuizGrade"][0])){
              $dataqg->quiz = $quizGradeData->instance;
              $dataqg->timemodified = time();
              $dataqg->grade = 100;
              $dataqg->userid = $USER->id;
              $DB->insert_record('quiz_grades', $dataqg);
              $lessonNameArr[$i]["QuizGrade"][] = $DB->get_field('quiz_grades', 'grade', array('quiz' => $quizGradeData->instance, 'userid' => $USER->id));
              /*if($lessonNameArr[$i]["QuizGrade"][]==''){
              $lessonNameArr[$i]["QuizGrade"][] = $DB->get_record_sql('select sumgrades from {quiz_attempts} where quiz="'.$quizGradeData->instance.'" and userid="'.$USER->id.'" and state="finished" order by id DESC limit 0,1');  
              }*/
            }
          }


          $quizCompletionObj = $lessonNameArr[$i]['QuizCompletionData'] = $DB->get_record('course_modules_completion', array('coursemoduleid' => $keyCMQ->id, 'userid' => $USER->id), '*');

          $lessonNameArr[$i]['quizcompletionStatus'] = 2;
          $lessonNameArr[$i]['newlyupdated'] = 1;
          $newflags=1;
        }
        else{
          $lessonNameArr[$i]['newlyupdated'] = 0;
          $lessonNameArr[$i]["NewQuizAdded"][] = 1; 
        }

        //For star icon if new lesson added before certification intially
      } // end else classroomtypeflag

      }
    }
   }

    }
    $i++;
  }
   $jk++;
 }

 if(!empty($cmCertificate[$key->id][0])){
     foreach($cmCertificate[$key->id] as $keyCMCs){
      foreach ($keyCMCs as $keyCMC) {
       $cmCertificateId = $keyCMC->id;
       $certificateURL = "/mod/simplecertificate/view.php?id=".$cmCertificateId."&action=get";
     }
     }     
   }

  if(!empty($cmAssign[$key->id][0])){
    foreach($cmAssign[$key->id] as $keyCMAs){
      if(is_object($keyCMAs)){
        $cmAssignId = $keyCMAs->id;
        $assignURL = "/mod/assign/view.php?id=".$cmAssignId;
      }
      else{
        foreach ($keyCMAs as $keyCMA) {
          $cmAssignId = $keyCMA->id;
          $assignURL = "/mod/assign/view.php?id=".$cmAssignId;
        }
      }
    }
  }
}
}

$context = context_module::instance($cm->id);
$allowedit  = has_capability('mod/book:edit', $context);
$viewhidden = has_capability('mod/book:viewhiddenchapters', $context);

if ($allowedit) {
    if ($edit != -1 and confirm_sesskey()) {
        $USER->editing = $edit;
    } else {
        if (isset($USER->editing)) {
            $edit = $USER->editing;
        } else {
            $edit = 0;
        }
    }
} else {
    $edit = 0;
}

// read chapters
$chapters = book_preload_chapters($book);

if ($allowedit and !$chapters) {
    redirect('edit.php?cmid='.$cm->id); // No chapters - add new one.
}
// Check chapterid and read chapter data
if ($chapterid == '0') { // Go to first chapter if no given.
    \mod_book\event\course_module_viewed::create_from_book($book, $context)->trigger();

    foreach ($chapters as $ch) {
        if ($edit) {
            $chapterid = $ch->id;
            break;
        }
        if (!$ch->hidden) {
            $chapterid = $ch->id;
            break;
        }
    }
}

$courseurl = new moodle_url('/course/view.php', array('id' => $course->id));

// No content in the book.
if (!$chapterid) {
    $PAGE->set_url('/mod/book/view.php', array('id' => $id));
    notice(get_string('nocontent', 'mod_book'), $courseurl->out(false));
}
// Chapter doesnt exist or it is hidden for students
if ((!$chapter = $DB->get_record('book_chapters', array('id' => $chapterid, 'bookid' => $book->id))) or ($chapter->hidden and !$viewhidden)) {
    print_error('errorchapter', 'mod_book', $courseurl);
}

$PAGE->set_url('/mod/book/view.php', array('id'=>$id, 'chapterid'=>$chapterid));


// Unset all page parameters.

unset($bid);
unset($chapterid);

// Security checks END.

// Read standard strings.
$strbooks = get_string('modulenameplural', 'mod_book');
$strbook  = get_string('modulename', 'mod_book');
$strtoc   = get_string('toc', 'mod_book');

// prepare header
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->fullname);

// prepare chapter navigation icons
$previd = null;
$prevtitle = null;
$nextid = null;
$nexttitle = null;
$last = null;

// =====================================================
// Book display HTML code
// =====================================================

echo $OUTPUT->header();

?>
<script> 

var i = 1;

$('.navli').each(function() {
  $( this ).addClass( "item"+i ); i++;
});

if( i == 13){
    $( ".item10" ).find( "span" ).remove();
    $('.item11').remove();
    $('.item12').remove();
}
else if (i == 17){
    $( ".item14" ).find( "span" ).remove();
    $('.item15').remove();
    $('.item16').remove()
}
else{
    $( ".item12" ).find( "span" ).remove();
    $('.item13').remove();
    $('.item14').remove();
}

</script>

<?php $OUTPUT->sheets = array('slides');?>
<style type="text/css">
#block-region-side-pre{
display: none;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot ?>/mod/book/src/sharer.css?v=1.1" />
<link href="<?php echo $CFG->wwwroot ?>/theme/meline29/style/lesson.css?v=1.5" type="text/css" rel="stylesheet">
<style>
/* body    { color: #222; font: 120%/1.4 sans-serif; padding: 2em 4em; text-align: center; }*/
.penguin {
    font-style: italic;
}
ol li, div.caption-line {
    display: block !important;
}
.coursebox p, span {
    background-color: none !important;
}
.on {
    color: #cc0033 !important;
    background-color: #f0f0fa !important;
}
.modal-backdrop {
   z-index: 20 !important;
}
</style>
<!--Do it your stuff here-->
<?php
//GET Heading Module Name
foreach ($sectionData as $valSec) {
  # code...
  if(!empty($valSec->sequence)){
    $seqArr = explode(",", $valSec->sequence);
    foreach($seqArr as $valSEQ) {
      if($_GET['id'] == $valSEQ)  {
        $moduleName = $valSec->name;
        break;
      }
    }    
  }
}

?>
<div class="heading-container">
  <?=$OUTPUT->heading(format_string($book->name),1,'custom_seo_h1');?>
  <?=format_string($course->fullname);?> : <?=format_string($moduleName)?>
</div>

<div id="fb-root"></div>
<?php include_once("social_icons.php");?>


<?php
$flagYoutube=0;
  // For book summary dispalying Customized sameer
  //$booktext = file_rewrite_pluginfile_urls($book->intro, 'pluginfile.php', $context->id, 'mod_book','intro');
  $booktext = file_rewrite_pluginfile_urls($book->intro, 'pluginfile.php', $context->id, 'mod_book','book', $book->id);

  $iframetext = format_text($booktext, $book->introformat, array('noclean'=>true, 'overflowdiv'=>false, 'context'=>$context));

  if(preg_match('/pluginfile.php/',$booktext) && (preg_match('/youtube/',$booktext)) ){

    $flagYoutube=false;
    echo format_text($booktext, $book->introformat, array('noclean'=>true, 'overflowdiv'=>false, 'context'=>$context));


  }elseif (preg_match('/youtube/',$booktext)) {
    
  preg_match('/src="([^"]+)"/', $booktext, $match);
  $url = $match[1];
  $url_arr=explode("/",$url);
  $last_str = array_pop($url_arr);  
    if(isset($_GET['pagenum'])){ 
      echo "<span class='loadingImage' id='lvideo'><img src='pix/loading_img.gif'/></span>";
//echo "Test14";
echo "<div style='display:none;' class='video-overflow'><iframe id='player' src='https://www.youtube.com/embed/".$last_str."?rel=0&amp;enablejsapi=1' height='750' width='100%' frameborder='0' allowfullscreen=''></iframe></div>"; 
      //echo "<div id='player' style='display:none;' class='video-overflow'>".format_text($booktext, $book->introformat, array('noclean'=>true, 'overflowdiv'=>false, 'context'=>$context))."</div>"; 
    }else{ //echo $booktext;
//echo "Test24";
echo "<div class='video-overflow'><iframe id='player' src='https://www.youtube.com/embed/".$last_str."?rel=0&amp;enablejsapi=1' height='750' width='100%' frameborder='0' allowfullscreen=''></iframe></div>"; 
?>

<?php      //echo "<div id='player' class='video-overflow'>".format_text($booktext, $book->introformat, array('noclean'=>true, 'overflowdiv'=>false, 'context'=>$context))."</div>";       
    }
    $flagYoutube=true; 
  }
  else{
    $flagYoutube=false;
//echo "Test34";
     echo $OUTPUT->box(format_module_intro('book', $book, $cm->id), 'generalbox', 'intro');
    //echo format_text($booktext, $book->introformat, array('noclean'=>true, 'overflowdiv'=>true, 'context'=>$context));
  }
?>


<style type="text/css">
.h1css h1 {
    background: inherit;
    border: inherit;
    color: #666;
    font-family: "Roboto Condensed";
    font-size: 24px !important;
    margin-bottom: 0 !important;
    margin-top: 0px !important;
}
.green_box{
  background-color: #9fcb97;
  color: rgb(0, 0, 0);
  font-size: 17px;
  padding: 15px 25px 25px;
}

@media screen and (max-width: 768px) { 
  .green_box{
     font-size: 11px;

  }
  .h1css h1 {
   
    font-size: 20px !important;
   
}

}
</style>
<?php

if($book->custom_text_display_flag != 0){
  echo "<div class='green_box'><span class='h1css' style='display: inline-block;
line-height: 25px;text-align:justify;'>".$book->c_text_beneath_video."</span></div>";
}
?>
<a id="clicky"/></a>
<div class="bottom-section">
  <div id="slide">
    <div class="left-cover-area" id="searchDemo">
      <div class="new-search">
        <form>
          <input type="text" id="searchText" autocomplete="off" placeholder="Search <?=format_string($course->fullname);?>" value="">
          <input type="submit" id="searchDo" class="" value="Search">
          <br>
           <i class="fa fa-search mag-icon"></i> <i class="fa fa-times-circle clear-search"></i>
        </form>
<span class="success" style="padding-right:105px;color:green;"></span> <span class="error" style="padding-right:85px;"></span>
      </div>
      
        <!--For Pawan fixes search disability start-->

<script> 
      
  $("#searchDo").hide();
  $( window ).on("load", function() {              
    $("#searchDo").prop("disabled",false);
    $("#searchDo").show();
 });

</script>

  <!--For Pawan fixes end search disability-->  
      <div class="toggleAll"> <span class="expandAll"><a href="#"><?=get_string('ccollapse','book')?></a></span> <span class="collapseAll"><a href="#"><?=get_string('cexpand','book')?></a></span> 
<?php if(isset($_GET['pagenum'])){ ?>
      <span class="closeAll" id="closeSearch"><a href="javascript:void(0);" onclick="hideSearchResult();"><?=get_string('csearchresult','book')?></a></span>
<?php
}else{ ?>
<span class="closeAll" id="closeSearch" style="display:none;"><a href="javascript:void(0);" onclick="hideSearchResult();"><?=get_string('csearchresult','book')?></a></span>
  <?php }?>

      </div>
<?php if($course->id != 25){ ?>
      <?php include_once("searchTranscript.php");  // updated by lakhan ?>
<?php } ?>
      <?php if(isset($_GET['pagenum'])){ ?>
<div class="below-search-part" id="searchContentTwo"><span class='loadingImage'><img src='pix/loading_img.gif' /></span> </div>
<?php }else{ ?>
<div class="below-search-part" id="searchContentTwo" style="display:none;"> </div>

<?php }?>
<?php if(isset($_GET['pagenum'])){ ?>
<div class="below-search-part" id="searchContent" style="display:none;"> 
<?php }else{ ?>
<!--div class="below-search-part" id="searchContent" disabled="disabled"--> 
<div class="below-search-part" id="searchContent">
<?php }?>
      

        <!-- Renew Certificate for newly added lesson -->
        <?php 
        if(!empty($certificateTimeLogObj) && $renewFLAG == 1){
        
          $keyCertVal = $DB->get_record('simplecertificate', array('course'=>$course->id), "certexpirydateinyear");
          $certExpDurationDate = $keyCertVal->certexpirydateinyear;
          if($certificateIssueTimeLogObj->timeexpired == 0 || $certificateIssueTimeLogObj->timeexpired == ''){
            $certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
          }
          else{
            $certExpiredDate = $certificateIssueTimeLogObj->timeexpired;
          }
//RCP start
$beforeCertExpiredThirtyDaysTime = strtotime("-1 month",$certExpiredDate);
  //$beforeCertExpiredThirtyDaysTime = $certExpiredDate - 2592000; //Get one month before date to the expired date
//RCP end
       
          $todayTime = time(); 
          if($beforeCertExpiredThirtyDaysTime <= $todayTime){
            // Scenario 4
        ?>
          <!-- Renew Certificate for newly added lesson prompt display before one month expiry date Otherwise else part should be display-->
          <div class="first-part renew-exam">
            <div class="titleTrigger">
              <h3><?php echo get_string('crenewcert','book'); ?></h3>
            </div>
            <div class="toggleContent"> 
              <span class="congrts-text"><?=get_string('crenewexp','book')?>
              <strong><?=date("m/d/y",$certificateTimeLogObj->timecompletion)?></strong>. 
              <?=get_string('crenewmodules','book')?>
                
              </span> 
            </div>
          </div>
        <?php } 
        else { 
          ?>

<?php 
$ccertsteps = get_string('ccertsteps','book');
$cfinalexamcomp = get_string('cfinalexamcomp','book');
$cfinalexamfor = get_string('cfinalexamfor','book');
if($assignURL == ''){
  $proceedExamFlag=1;
  $flagC=1;$assigntext = ""; $styleDisplay="inherit"; $headingsPrompt = strtoupper($ccertsteps); $classPrompt="proceed-exams"; 
}
elseif($assignURL != ''){
  $proceedExamFlag=2;
  $flagC=1; $newmodaddflag=1; $assigntext = $cfinalexamfor; $styleDisplay="none"; $headingsPrompt = strtoupper($cfinalexamcomp); $classPrompt="final-exam"; } 
else{
  $proceedExamFlag=0;
  $classPrompt="proceed-exams";
}
?>
 <?php if($flagC==1){ ?>
        <!--Final exam Start-->
        <div class="first-part final-exam">
          <div class="titleTrigger">
            <h3><?php echo $headingsPrompt; ?></h3>
          </div>
<!-- RCP start -->
          <div class="toggleContent"> <span class="congrts-text" id="newmoduleaddedflag"><?=get_string('ccongcourse','book')?></span>
<!-- RCP end -->
            <?php
        $chk_course_completions = $DB->get_record('course_completions', array('userid'=>$USER->id, 'course' => $course->id), "id, timecompleted, reaggregate");
        if($chk_course_completions->timecompleted != ''){
          //Delete SCertifcate Data and Add in log
          $cert = $DB->get_record('simplecertificate', array('course'=>$course->id), "id");
       ?>
            <span class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span> 
           <?php if($assignURL != ''){?>
            <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span>
            <?php }
        }elseif($chk_course_completions->reaggregate != '' && $chk_course_completions->reaggregate != 0){          
        ?>
        <?php if($assignURL != ''){?>
            <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span> 
        <?php }?>

            <span class="download-certificate-link"><strong><?=get_string('cgeneratecert','book')?></strong></span>
            <span style="display:none;" id="downloadcertlink" class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span>
            <?php
        }elseif(!empty($certificateTimeLogObj)){
            ?>
            <span class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span> 
<?php if($assignURL != ''){ ?>
            <!--span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('proceedfinalexam','book')?></a></span-->
            <?php } ?>
            <?php 
   
        }
        else{ 
          if($assignURL != ''){?>
            <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span>
        <?php } ?>    
        <?php } ?>
          </div>
        </div>        
        <!--Final exam End--> 
<?php }//endif flagC?>
          <div class="first-part third-scenario">
            <div class="titleTrigger">
              <h3><?=get_string('newmodadd','book')?>
<?php 
//RCP start
$_SESSION['flagNewModuleAdded'] = 1;
//RCP end
?>
</h3>
            </div>
            <?php $timecompletioncourse = $certificateTimeLogObj->timecompletion;?>
            <div class="toggleContent"> 
              <span class="congrts-text">One or more modules in this course has been updated.  New modules are signified by a  <span class="star-img"></span> icon.
              <p> When your certification expires on <?=date("m/d/y",$certExpiredDate)?>, you will be required to complete the updated curriculum.</p>
              </span>
            </div>
          </div>
        <?php } ?>       
        <?php 
        }
elseif(!empty($certificateTimeLogObj) && $renewFLAG == 1){
  // Scenario 5
?>
<!--Newly Lesson Completion Certificate Generate-->
        <div class="first-part renew-complete-exam final-exam">
          <div class="titleTrigger">
            <h3><?php echo strtoupper('Renew Certification Steps Completed'); ?></h3>
          </div>
          <div class="toggleContent"> <span class="congrts-text">Congratulations, you have sucessfully completed the newly updated activities for this Course</span>
            <?php
        $chk_course_completions = $DB->get_record('course_completions', array('userid'=>$USER->id, 'course' => $course->id), "id, timecompleted, reaggregate");
        
        //$certificateTimeLogObj = $DB->get_record_sql('SELECT id FROM mdl_simplecertificate_issue_logs WHERE userid = ? AND courseid = ? AND timecompletion is NOT NULL order by id desc', array( $USER->id, $course->id ));

        if($chk_course_completions->timecompleted != ''){
          //Delete SCertifcate Data and Add in log
          $cert = $DB->get_record('simplecertificate', array('course'=>$course->id), "id");
          //$DB->delete_records('simplecertificate_issues', array('userid' => $USER->id, 'certificateid'=>$cert->id));
        ?>
            <span class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span> 
            <?php 
        }
        ?>
        </div></div>
<!--Newly Lesson Completion Certificate Generate-->
<?php
}
elseif($newflagsintial==1){ 
  // Scenario 2
  ?>
  <div class="first-part second-scenario">
    <div class="titleTrigger">
      <h3><?php echo strtoupper('UPDATED CURRICULUM'); ?></h3>
    </div>
    <div class="toggleContent"> <span class="congrts-text">One or more modules in this course has been updated. New modules are signified by a <span class="star-img-icon"></span> icon.</span> 
    <p class="second-scenario-p">Please watch the newly updated content.</p></div>
  </div>
  <!-- Renew Certificate for newly added lesson End--> 
<?php

}
else{
  // Scenario 1
        ?>
         
<?php if($assignURL != '' && $classroomtypeflag == 0){ ?>
        <!-- 1069 Start -->
        <?php
        $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$course->id,'criteriatype'=>8),'','id, course, courseinstance');
        if(!empty($coursesData)) {
          $flagCrt=2;
          foreach($coursesData as $keyCD) {
          // Course first level checking completion
            if($keyCD->courseinstance != '') {
              $courseinstance=$keyCD->courseinstance;
            }          
          
            $sql32 = "SELECT id,category FROM {course} WHERE id = $courseinstance";
            $rs132 = $DB->get_record_sql($sql32);
        
            //Level 2 Submission Restriction start  
            
            $sql0m421 = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$USER->id.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course ='.$courseinstance;
            $completionParent = $DB->get_record_sql($sql0m421);
            if($completionParent->cntcert > 0){
              $flagCrt = 1;
            }
          }
          // AND combination section start
          $sqlCCC = 'SELECT ccc.* FROM {course_completion_aggr_methd} ccam JOIN {course_completion_criteria} ccc ON ccam.course=ccc.course WHERE ccc.course =' . $course->id . ' AND ccc.course != 42 AND ccam.criteriatype = 8 AND ccam.method = 1';
          $cccRes = $DB->get_records_sql($sqlCCC);
          if($cccRes) {
            foreach ($cccRes as $cccObj) {
              if($cccObj->courseinstance != '') {        
                $sqlmCCC = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$USER->id.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course = '.$cccObj->courseinstance;
                $sqlmCCCRes = $DB->get_record_sql($sqlmCCC);
                if($sqlmCCCRes->cntcert == 0){
                  $flagCrt = 2;
                }
              }
            }
          } // AND combination section end (1069 still continue)
        }else{ ?>
          <!--Proceed exam Start-->
      
        <div class="first-part proceed-exam" style="display:none;">
          <div class="titleTrigger">
            <h3><?php echo strtoupper(get_string('proceedtofinalexam','book')); ?></h3>
          </div>
          <div class="toggleContent"> <span class="congrts-text"><?=get_string('ccongsasscourse','book')?></span> 
          <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span> 
          </div>
        </div>
          <?php
          $_SESSION['proceed_restrict'] = 1;
        }

        if($flagCrt == 1){    $_SESSION['proceed_restrict'] = 1;        $flagCrt=0;
        ?>
        <!--Proceed exam Start-->
        <div class="first-part proceed-exam" style="display:none;">
          <div class="titleTrigger">
            <h3><?php echo strtoupper(get_string('proceedtofinalexam','book')); ?></h3>
          </div>
          <div class="toggleContent"> <span class="congrts-text"><?=get_string('ccongsasscourse','book')?></span> 
          <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span> 
          </div>
        </div>
      <?php }?>

      <!-- 1069 End -->
<?php if($USER && $USER->username != 'guest'){ ?>
        <div id="disableproceed" style="display:none;">
          <div class="first-part proceed-exam proceed-exam-disabled">
          <div class="titleTrigger disableproceed">
            <h3><?php echo strtoupper(get_string('proceedtofinalexam','book')); ?></h3>
            <span class="congrts-text"><?=get_string('disabledproceedexamstatus','book')?></span> 
          </div>

          <!--div class="toggleContent"> <span class="congrts-text"><?=get_string('ccongsasscourse','book')?></span> 
          <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span> 
          </div-->
        </div>
        </div>
        <?php }?>
        <!--Proceed exam End--> 
<?php }elseif ($assignURL != '' && $classroomtypeflag == 1) { ?>
    <!--Re-certification online exam after taking classroom Start -->
        <div class="first-part proceed-exam" style="display:none;">
          <div class="titleTrigger">
            <h3><?php echo strtoupper(get_string('ccertsteps','book')); ?></h3>
          </div>
          <div class="toggleContent"> <span class="congrts-text"><?=get_string('ccongs','book')?></span>
            <span class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span>
          </div>
        </div>
  <!--Re-certification online exam after taking classroom End -->

<?php
}
?>

<?php 
$ccertsteps = get_string('ccertsteps','book');
$cfinalexamcomp = get_string('cfinalexamcomp','book');
$cfinalexamfor = get_string('cfinalexamfor','book');
if($assignURL == ''){$proceedExamFlag=1;$flagC=1;$assigntext = ""; $styleDisplay="inherit"; $headingsPrompt = strtoupper($ccertsteps); $classPrompt="proceed-exams"; } 
elseif($assignURL != ''){$proceedExamFlag=2;$flagC=1; $newmodaddflag=1; $assigntext = $cfinalexamfor; $styleDisplay="none"; $headingsPrompt = strtoupper($cfinalexamcomp); $classPrompt="final-exam"; } 
?>
        <?php if($flagC==1){ ?>
        <!--Final exam Start-->
        <div class="first-part final-exam withoutassign-final-exam" style="display:none;">
          <div class="titleTrigger">
          <?php if($classroomtypeflag == 0){ ?>
            <h3><?php echo $headingsPrompt; 
//$_SESSION['ccertstepscomp'] = 'certified';
$_SESSION[$course->id]['ccertstepscomp'] = 'certified';
?></h3>
          <?php }?>
          <?php if($classroomtypeflag == 1){  
//$_SESSION['ccertstepscomp'] = 'certified';
$_SESSION[$course->id]['ccertstepscomp'] = 'certified';
?>
            <h3><?php echo get_string('cdowncert','book'); ?></h3>
          <?php }?>
          </div>
          <div class="toggleContent"> <span class="congrts-text"><?=$ccertsteps?></span>
            <?php
        $chk_course_completions = $DB->get_record('course_completions', array('userid'=>$USER->id, 'course' => $course->id), "id, timecompleted, reaggregate");
        if($chk_course_completions->timecompleted != ''){
          //Delete SCertifcate Data and Add in log
          $cert = $DB->get_record('simplecertificate', array('course'=>$course->id), "id");
        ?>
            <span class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span> 
           <?php if($assignURL != ''){?>
            <!--span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('proceedfinalexam','book')?></a></span-->
            <?php }
        }elseif($chk_course_completions->reaggregate != '' && $chk_course_completions->reaggregate != 0){          
        ?>
        <?php if($assignURL != ''){?>
            <!--span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('proceedfinalexam','book')?></a></span--> 
        <?php }?>
            <span class="download-certificate-link"><strong><?=get_string('cgeneratecert','book')?></strong></span>
            <span style="display:none;" id="downloadcertlink" class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span>
            <?php

            //- - - - - Start- Feature Request: Course Completion Pop-up Nav-- - - - - -//
           $certificate_in_process = "12";
          //- - - - - End- Feature Request: Course Completion Pop-up Nav-- - - - - -//

        }elseif(!empty($certificateTimeLogObj)){
           //if ($renewFLAG == 1){
            ?>
            <span class="download-certificate-link"><a title="" href="<?=$certificateURL?>"><?=get_string('cdownloadcert','book')?></a></span> 
            <?php 
          //}
        }
        else{ 
          //- - - - - Start- Feature Request: Course Completion Pop-up Nav-- - - - - -//
          $certificate_in_process = "12";
          //- - - - - End- Feature Request: Course Completion Pop-up Nav-- - - - - -//
          if($assignURL != ''){?>
            <span class="Proceed-link"><a title="" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span>
        <?php } ?>    
        <?php } ?>
          </div>
        </div>        
        <!--Final exam End--> 
<?php }//endif flagC?>



        <?php if($assignURL != '' && $classroomtypeflag == 0){ ?>
        <!--Final exam Start-->
        <div class="first-part final-exam final-exam-onprocess" style="display:none;">
          <div class="titleTrigger">
            <h3><?php echo strtoupper(get_string('cfinalexamon','book')); ?></h3>
          </div>
          <div class="toggleContent"> <span class="congrts-text" style="color:red;"><?=get_string('cnotcompcourse','book')?></span> <span class="Proceed-link"><a title="Proceed" href="<?=$assignURL?>"><?=get_string('cproceedfinal','book')?></a></span> </div>
        </div>
        <?php } ?>
        <!--Final exam End-->

        <?php }//end else?>
        
        <?php 
            $flagReqPassGrade=0;
                    for($k=0;$k<count($lessonNameArr);$k++){
                      if(!empty($lessonNameArr[$k]["RequireGrade"])){
                        $reqGrade = $lessonNameArr[$k]["RequireGrade"][0];
                        $reqPassedGrade = $reqGrade->gradepass;
                        $flagReqPassGrade=0;
                      }
                      else{
                        $flagReqPassGrade = 1;
                      }

      if($lessonNameArr[$k]['quizcompletionStatus'] == 1){ $assStatus=1; }else{ $assStatus=0; }
                        $module_name = $lessonNameArr[$k]['module_name'];
                        unset($lessonNameArr[$k]['module_name']);                    ?>
        <div class="first-part modules" id="pact_<?=$k+1?>">
          <div class="titleTrigger">
            <h3>
              <?=$k+1?>
              )
              <?=format_string($module_name)?>
            </h3>
            <div class="right-part">
              <?php if (!isguestuser()) { ?>
              <span id="<?=$k+1?>_module_status"></span>
              <?php }?>
              <span class="total-time">
              <?=$lessonNameArr[$k]['module_time']?>
              </span> </div>
          </div>
          <ul class="toggleContent">
            <?php
              $keyCount = array_filter(array_keys($lessonNameArr[$k]),'is_numeric');
                    for($l=0;$l<count($keyCount);$l++){
                        if($lessonNameArr[$k][$l]['book_id'] == $book->id){ 
                          $selectedPart = 'selected-part'; $sel_book_time = $lessonNameArr[$k][$l]['book_time'];}
                        else{$selectedPart = '';}

                        if($lessonNameArr[$k][$l]['lessoncompletionStatus'] == 1){ $completionIcon = 'check-img'; $title = "Viewed"; }
                        elseif($lessonNameArr[$k][$l]['newlyupdated'] == 1){$NUFlag=1;$completionIcon = 'notice-img'; $title = "Newly Updated Lesson";}
                        else{
                          if($lessonNameArr[$k][$l]['newadded'] == 1){
                            $_SESSION['NM'] = 4;

                            $completionIcon = 'star-img'; $title = "View"; $newflag=1;
                          }
                          else{
                            $completionIcon = 'eye-img'; $title = "View"; 
                          }
                        }

                        if($lessonNameArr[$k][$l]['lessoncompletionStatus'] != 1)
                        { $flag=1; $flagM = 1; } 
                    ?>
<?php  if(empty($lessonNameArr[$k][$l]['page_name'])) {?>
            <li class="<?=$selectedPart?>"><a href="/mod/book/view.php?id=<?=$lessonNameArr[$k][$l]['Lesson']?>" id="act_<?=$lessonNameArr[$k][$l]['Lesson']?>" title="<?=$title?>">
              <?=format_string($lessonNameArr[$k][$l]['book_name'])?>
              </a>
              
              <div class="time-for-tree-part">
              <?php if (!isguestuser()) { ?>
              <a href="/mod/book/view.php?id=<?=$lessonNameArr[$k][$l]['Lesson']?>" id="act_<?=$lessonNameArr[$k][$l]['Lesson']?>" title="<?=$title?>"> <span class="<?=$completionIcon?>"></span></a> 
              <?php }?>
              <span class="total-time"><?=$lessonNameArr[$k][$l]['book_time']?></span> 
              </div>
              
            </li>
 <?php }else{ ?>
              <li class="<?=$selectedPart?>"><a href="/mod/page/view.php?id=<?=$lessonNameArr[$k][$l]['Lesson']?>" id="act_<?=$lessonNameArr[$k][$l]['Lesson']?>" title="<?=$title?>">
              <?=format_string($lessonNameArr[$k][$l]['page_name'])?>
              </a>
              <div class="time-for-tree-part">
              <?php if (!isguestuser()) { ?>
              <a href="/mod/page/view.php?id=<?=$lessonNameArr[$k][$l]['Lesson']?>" id="act_<?=$lessonNameArr[$k][$l]['Lesson']?>" title="<?=$title?>"> <span class="<?=$completionIcon?>"></span></a> 
              <?php }?>
              </div>
              
            </li>
              <?php } ?>

            <?php
                    } //end for loop keyCount
                    ?>
            <?php if(!empty($lessonNameArr[$k]['Quiz'][0])){ ?>
<?php if($classroomtypeflag == 0 || $lessonNameArr[$k]["QuizGrade"][0] < $reqPassedGrade || $lessonNameArr[$k]["QuizGrade"][0] == ""){ ?>
            <li class="assesment_cls" data="1" certstat="<?=$classroomtypeflag?>" requiregrade="<?=$lessonNameArr[$k]["QuizGrade"][0]?>"><a href="/mod/quiz/view.php?id=<?=$lessonNameArr[$k]['Quiz'][0]?>" title=""><?=get_string('assessment','book')?></a> 
<?php }else{ ?>
     <li class="assesment_cls"><a href="javascript:void(0);" certstat="<?=$classroomtypeflag?>" requiregrade="<?=$lessonNameArr[$k]["QuizGrade"][0]?>" title=""><?=get_string('assessment','book')?></a> 
<?php }?>
              <?php if($lessonNameArr[$k]['newlyupdated'] == 1){$completionIcon = 'notice-img'; $title = "<?=get_string('newlyupdatedquiz','book')?>"; $NUFlag=1; 

              ?>
                <div class="time-for-tree-part">
                  <?php if (!isguestuser()) { ?>                    
                  <a href="javascript:void(0);" title="<?=$title?>"><span class="<?=$completionIcon?>"></span></a>
                  <?php } ?>
                  <span class="total-time"></span> 
                </div>
              <?php } ?>
              <?php 
              //Star 
              $lastNewQuizAddedVal = isset($lessonNameArr[$k]['NewQuizAdded']) ? end($lessonNameArr[$k]['NewQuizAdded']) : "";
              if($lastNewQuizAddedVal == 1){$completionIcon = 'star-img'; $title = "<?=get_string('newlyupdatedquiz','book')?>"; 
              //Star
              $newflagQ=1;  ?>
                <!--div class="time-for-tree-part">
                  <?php //if (!isguestuser()) { ?>                    
                  <a href="javascript:void(0);" title="<?=$title?>"><span class="<?=$completionIcon?>" style="margin-top:-5px !important;"></span></a>
                  <?php //} ?>
                  <span class="total-time"></span> 
                </div-->
              <?php } ?>
            </li>
            <?php if($lessonNameArr[$k]["QuizGrade"][0] >= $reqPassedGrade){ ?>
            <li class="result_cls_pass"><?=get_string('testrespass','book')?> (
              <?=number_format($lessonNameArr[$k]["QuizGrade"][0],2)?>
              %)</li>
            <?php }
        elseif($lessonNameArr[$k]["QuizGrade"][0] < $reqPassedGrade){ $flag = 1; $flagQM = 1; 

        if (!isguestuser()) { 
                $quizDataObj = $DB->get_record('quiz', array('course'=>$course->id), "id");
                $quizAttemptObj = $DB->get_record('quiz_attempts', array('userid'=>$USER->id, 'quiz' => $lessonNameArr[$k]["Quizinstance"][0]), "id,state");
if(empty($quizAttemptObj) || $quizAttemptObj->state=="inprogress"){ ?>
            <!--li class="result_cls_fail" style="color:#382f04 !important;">Test Not Yet Taken</li-->
            <?php }else{
              //Star
              $newflagQ = 1; 
              ?>
            <li class="result_cls_fail"><?=get_string('testresfail','book')?> (<?=number_format($lessonNameArr[$k]["QuizGrade"][0],2)?>
              %) </li>
            <?php } ?>
            <?php }
              elseif($assStatus==0){ $flag = 1; $flagQM = 1; 
                ?>
            <!--li class="result_cls_fail" style="color:#382f04 !important;">Test Not Yet Taken</li-->
            <?php } ?>
            <?php } } ?>
          </ul>
        </div>



        <script>
        /*console.log("<?=$lessonNameArr[$k]['QuizGrade'][0]?>" + " Grade Student ");
        console.log("<?=$reqPassedGrade?>" + " Grade Quiz ");
        console.log("<?=$lastNewQuizAddedVal?>" + " New Quiz Added Flag ");*/
          var flag_m_s = parseInt("<?=$flagM?>"); 
          var flag_q_m_s = parseInt("<?=$flagQM?>"); 
          var newflag_m_s = parseInt("<?=$newflag?>");
          //Star
          var newflag_q_m_s = parseInt("<?=$newflagQ?>");
          var nuflag_m_s = parseInt("<?=$NUFlag?>");
          var k_m_s = "<?=$k+1?>";
          /*console.log(k_m_s + " Counter");
          console.log(newflag_m_s + " Flag1");
          console.log(newflag_q_m_s + " Flag12");
                    console.log(flag_q_m_s + " Flag20");

          console.log(flag_m_s + " Flag21");
          console.log(nuflag_m_s + " Flag22");*/
          /*console.log("-----------");*/
          //Star
          if(newflag_m_s == 1 || newflag_q_m_s == 1){
            $("#"+k_m_s+"_module_status").addClass("star-in-process");
          }
          else if(flag_q_m_s == 1){
            
          }
          else if(flag_m_s == 1){
            
          }
          //else if(flag_m_s == 1 && nuflag_m_s != 1){
            //$("#"+k_m_s+"_module_status").addClass("in-process");
          //}          
          else if(nuflag_m_s == 1){
            $("#"+k_m_s+"_module_status").addClass("notice-in-process");
            $("#"+k_m_s+"_module_status").parent().parent().parent().addClass("notice-module");         
          }
          else{
            $("#"+k_m_s+"_module_status").addClass("right-img"); 
          }
        </script>
        <?php 
          $flagQM='';
          $flagM='';
          $newflag='';
          $newflagQ='';
          $NUFlag='';
      } //$k foreach end ?>
      </div>
    </div>

  </div>
  <div class="right-cover-area">
    <div id="content">
      <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <?php

            //start - updated by lakhan
            $itt = 0;
            $tabIdArr = array("red","orange","yellow","green");
            foreach($chapters as $key){

                    if( $pagenum ){

                      if( $pagenum == ($key->pagenum) ){  $active = "active"; }else{  $active = ""; }

                    }else{
                        if($itt == 0){
                        $active = "active";
                        }
                        else{
                        $active = "";   
                        }
          
                    } 


                    ?>
                            <li class="<?=$active ?>"><a id="<?php echo "tabAnchor_".$active?>" href="#<?=$tabIdArr[$itt]?>" data-toggle="tab">
                              <?=format_string($key->title)?>
                              </a></li>
                            <?php 
                    $itt++; 
            }

            //end - updated by lakhan


            ?>
      </ul>
      <div id="my-tab-content" class="tab-content">
        <?php
                      

        //start - updated by lakhan
            $itt = 0;
            $tabIdArr = array("red","orange","yellow","green");
            foreach($chapters as $key2){

                    if( $pagenum ){

                      if( $pagenum == ($key2->pagenum) ){  $active = "active"; }else{  $active = ""; }

                    }else{

                        if($itt == 0){
                        $active = "active";
                        }
                        else{
                        $active = "";   
                        }
          
                    }

                        $chapter = $DB->get_record('book_chapters', array('id' => $key2->id, 'bookid' => $book->id, 'hidden' => 0));

                    ?>
                  <div class="tab-pane  <?=$active?> <?php if($chapter->contenttype == "1") echo "descriptionTab".$chapter->contenttype; ?>" id="<?=$tabIdArr[$itt]?>">

        <!-- end - updated by lakhan -->


          <h1>
            <?=format_string($key2->title)?>
          </h1>
          <div class="right-second-heading"> <span class="main-text">
            <?=format_string($book->name)?>
            </span> <span class="main-time">
            <?=$sel_book_time?>
            </span> </div>
          <?php

                        $chaptertext = file_rewrite_pluginfile_urls($chapter->content, 'pluginfile.php', $context->id, 'mod_book', 'chapter', $chapter->id);
                        echo format_text($chaptertext, $chapter->contentformat, array('noclean'=>true, 'overflowdiv'=>true, 'context'=>$context));
                        ?>
        </div>
        <?php 
              $itt++; 
              } 
            ?>
      </div>
    </div>
  </div>
</div>
<?php if(isset($_GET['pagenum'])){ ?>
<script type="text/javascript">

var prevSearchedKey = localStorage.getItem("searchedKey");
var pagenum = "<?=$pagenum?>";

jQuery.fn.highlight = function(pat) {
 function innerHighlight(node, pat) {
  var skip = 0;
  if (node.nodeType == 3) {
   var pos = node.data.toUpperCase().indexOf(pat);
   pos -= (node.data.substr(0, pos).toUpperCase().length - node.data.substr(0, pos).length);
   if (pos >= 0) {
    var spannode = document.createElement('span');
    spannode.className = 'highlight';
    var middlebit = node.splitText(pos);
    var endbit = middlebit.splitText(pat.length);
    var middleclone = middlebit.cloneNode(true);
    spannode.appendChild(middleclone);
    middlebit.parentNode.replaceChild(spannode, middlebit);
    skip = 1;
   }
  }
  else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
   for (var i = 0; i < node.childNodes.length; ++i) {
    i += innerHighlight(node.childNodes[i], pat);
   }
  }
  return skip;
 }
 return this.length && pat && pat.length ? this.each(function() {
  innerHighlight(this, pat.toUpperCase());
 }) : this;
};

jQuery.fn.removeHighlight = function() {
 return this.find("span.highlight").each(function() {
  this.parentNode.firstChild.nodeName;
  with (this.parentNode) {
   replaceChild(this.firstChild, this);
   normalize();
  }
 }).end();
};
/*End highlight plugin*/
console.log(localStorage.getItem("data-desc") + "  Really ");
//$("#my-tab-content div div").highlight(localStorage.getItem("data-desc"));
$("#my-tab-content div div").highlight(localStorage.getItem("data-desc"));

</script>
<?php }?>


<!--Test serarch area--> 
<!-- <script src="http://code.jquery.com/jquery-2.1.0.js" type="text/javascript"></script> --> 
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script--> 
<script type="text/javascript" src="<?php echo $CFG->wwwroot ?>/mod/book/sample/js/jquery.1.7.1.js"></script>
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/jquery.textselect.min.js" type="text/javascript"></script> 
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/jquery.scrollTo.min.js" type="text/javascript"></script> 
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/jquery.search.js" type="text/javascript"></script>  
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/nav-script.js"></script> 

<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/modernizr.js"></script> 


<?php if($course->id != 25){ ?>
<?php include_once("social_js.php");?>
<?php }?>


<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<!-- Modal -->
<?php if (!isguestuser() && $flag != 1) { ?>
<!-- Modal Start --> 
<!-- 1069 Start -->
        <?php

        $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$course->id,'criteriatype'=>8),'','id, course, courseinstance');
        if(!empty($coursesData)) {
          $flagCrt2=2;
          foreach($coursesData as $keyCD) {
          // Course first level checking completion
            if($keyCD->courseinstance != '') {
              $courseinstance=$keyCD->courseinstance;
            }          
          
            $sql32 = "SELECT id,category FROM {course} WHERE id = $courseinstance";
            $rs132 = $DB->get_record_sql($sql32);
        
            //Level 2 Submission Restriction start  
            
            $sql0m421 = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$USER->id.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course ='.$courseinstance;
            $completionParent = $DB->get_record_sql($sql0m421);
            if($completionParent->cntcert > 0){
              $flagCrt2 = 1;
            }
          }
          // AND combination section start
          $sqlCCC = 'SELECT ccc.* FROM {course_completion_aggr_methd} ccam JOIN {course_completion_criteria} ccc ON ccam.course=ccc.course WHERE ccc.course =' . $course->id . ' AND ccc.course != 42 AND ccam.criteriatype = 8 AND ccam.method = 1';
          $cccRes = $DB->get_records_sql($sqlCCC);
          if($cccRes) {
            foreach ($cccRes as $cccObj) {
              if($cccObj->courseinstance != '') {        
                $sqlmCCC = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$USER->id.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course = '.$cccObj->courseinstance;
                $sqlmCCCRes = $DB->get_record_sql($sqlmCCC);
                if($sqlmCCCRes->cntcert == 0){
                  $flagCrt2 = 2;
                  $getInCompPreCourseId[] = $cccObj->courseinstance;
                }
              }
            }
          } // AND combination section end (1069 still continue)
          if($flagCrt2 != 1){

            $certificate_in_process = 0;
            if(empty($_SESSION['once_per_login_pre_book'.$course->id])){
             
              include_once("../../course/prereq_comp_lesson.php");
              $_SESSION['once_per_login_pre_book'.$course->id] = 2;
            }         
          }
        }

        ?>
<!-- 1069 End -->
<?php
if($certificate_in_process !=0){
 
//$chk_course_completions2 = $DB->get_record('course_completions', array('userid'=>$USER->id, 'course' => $course->id), "id, timecompleted, reaggregate");
//if($chk_course_completions2->reaggregate != '' && $chk_course_completions2->reaggregate != 0){
  
  //echo "Call Popup ".$course->id;
  if(empty($_SESSION['once_per_login_seventh'.$course->id])){

      $studentDashboardURL = $CFG->wwwroot.'/my/'; 
    ?>
      <div class="modal fade" id="memberModalCong" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
              <h4 class="modal-title" id="memberModalLabel" style="color:green;">Congratulations!</h4>
            </div>
            <div class="modal-body">
              <?php if($course->id == 28 && 28 == $course->id)
              { 
                $landingPageUrl= $CFG->wwwroot."/mod/page/view.php?id=560";
                ?>
                <p><b>Congratulations, you have completed the Q-SYS Quickstart Tutorials.</b></p>
                <p>You have successfully completed all of the current Q-SYS Quickstart content! Be on the lookout for future Quickstart lessons. If you are interested in furthering your Q-SYS training, feel free to check out some of our new courses on the Q-SYS Training page.</p>
                <p><a  data="1" id="get_cert" href="<?=$landingPageUrl;?>">Go to Q-SYS Training page</a></p>
              <?php }else{ ?>
                <p><b>Congratulations, you have completed <?= $course->fullname;?>.</b></p>
                <p>On behalf of the entire QSC Training and Education department, we would like to congratulate you on the successful completion of <?= $course->fullname;?>! Your certificate may take a few minutes to generate. Once it has, you will be able to download it on your student dashboard.</p>
                <p><a  data="1" id="get_cert" href="<?=$studentDashboardURL;?>">Go to student dashboard</a></p>
                <?php } ?>
            </div>
        </div>
      </div>
    </div>
<?php
 } // end session
//}
}
?>
<!--               End- Feature Request: Course Completion Pop-up Nav       -->

<?php 
//Get course assignment
$getAssignData = $DB->get_record_sql('SELECT id FROM mdl_assign WHERE course = ? AND type=? order by id desc', array( $course->id, "online" ));

//Get course assignment grade find by userid and assignmentid
$resultAssignGrade = $DB->get_record_sql('SELECT * FROM mdl_assign_grades WHERE userid = ? AND assignment = ? order by id desc', array( $USER->id, $getAssignData->id ));
$getGradeData = $DB->get_record_sql('SELECT gradepass FROM mdl_grade_items WHERE courseid = ? AND itemmodule = ?', array( $course->id, 'assign' ));
//echo "$getGradeData->gradepass <= $resultAssignGrade->grade"." -- ".$assignURL;
//exit("Succaa");
if(empty($resultAssignGrade) && !empty($assignURL)){ 

  if(empty($_SESSION['once_per_login_first_'.$course->id]) && $classroomtypeflag == 0){
    
  $_SESSION['once_per_login_first'.$course->id]=2;

//For alert message in between 30 days submit design from two places this message can be send i.e quiz and book
  $getAssignNoti = $DB->get_record('course_user_assign_notifications', array('course_id' => $course->id, 'user_id'=>$USER->id), "assign_notification");
  if($flag != 1 && ($getAssignNoti->assign_notification==0 || empty($getAssignNoti))){
    $userObj = $DB->get_records('user', array('id' => $USER->id), '', "*");
                $expiredThirtyDaysTime = date("F d, Y", strtotime("+1 month"));
    $from="qsctraining@qscaudio.com";
    //$subject="Completing your ".format_string($course->fullname)." Certification"; 
    $subject = "Final Exam Reminder: ".format_string($course->fullname);


                $messagetext="Testing";
                $messagehtml="Dear ".ucfirst($userObj[$USER->id]->firstname).",";
                $messagehtml.="<br><br>";

                $messagehtml.="You have just completed all of the necessary assessments for ".format_string($course->fullname)." and now it’s time to complete your final exam.  Please download the exam prompt below along with any accompanying files. Be sure to follow the directions carefully. <br><br>";
//CCC start
    $sql61 = "SELECT * FROM {assign} where course=".$course->id." and type='online'";
    $rs61 = $DB->get_record_sql($sql61);

    $cmAssignNoti = $DB->get_record('course_modules', array('course'=>$course->id, 'module'=>1,'visible'=>1,'instance'=>$rs61->id), 'id,added');

    //CCC ends
//$cmAssignNoti = $DB->get_record('course_modules', array('course'=>$course->id,'module'=>1,'visible'=>1), "id,added");
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
You will have until <b>$expiredThirtyDaysTime</b> to complete this exam. Once completed, you will be able to login to <a href='".$CFG->wwwroot."/login'>training.qsc.com</a>  and submit your final exam for review.
<br>
If you fail to submit your final exam within this time frame you will be forced to retake all of the online assessments associated with this course.
<br>
<b>Please only submit your own work; do not submit anything based on the work of other students. If we have reason to suspect that any portion of your submission is not original your certification will be voided and you will not be allowed to retake the course.</b>
<br>
Good luck with your exam! If you have any questions or need any assistance feel free to contact <a href='mailto:qsc.training@qsc.com'>qsc.training@qsc.com</a>.
<br>
Cheers,
The QSC Training & Education Team
<a href='".$CFG->wwwroot."'>training.qsc.com</a>";


    $messagetext=$messagehtml;
    
    $messagehtml = text_to_html($messagehtml, false, false, true);
    $userObj[$USER->id]->mailformat = 1; 
    if(empty($certificateIssueTimeLogObj)){
      if(email_to_user($userObj[$USER->id],$from, $subject, $messagetext, $messagehtml)){
          // Update mdl_course assign_notification
        $coursew->course_id = $course->id;
        $coursew->user_id = $USER->id;
        $coursew->timeduration = time();
        $coursew->assign_notification = 1;
        $DB->insert_record('course_user_assign_notifications', $coursew);

        $userObj[$USER->id]->email = 'sameer.chourasia@beyondkey.com';
    $subject2 = $subject." LIVE ".$USER->id;
        email_to_user($userObj[$USER->id],$from, $subject2, $messagetext, $messagehtml);

  //$userObj[$USER->id]->email = "anupam.garg@beyondkey.com";
    //          email_to_user($userObj[$USER->id],$from, $subject, $messagetext, $messagehtml);
  }
    }
  }
?>
  <!-- 1069 start -->
  <?php

  if($_SESSION['proceed_restrict'] == 1){

  ?>
  <!-- 1069 end -->
    <!-- Modal Start For Exit Exam Design Page-->
    <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            <h4 class="modal-title" id="memberModalLabel">
              <?=get_string('headingExitExamPopUp', 'book')?>
              ...</h4>
          </div>
          <div class="modal-body">
            <p style="text-align:justify;">
              <?=get_string('summaryExitExamPopUp', 'book')?>
              <!-- Need to Update Code -->
            </p>
          </div>
          <div class="modal-footer">
            <!-- Need to disable button -->
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="location.href = '<?=$assignURL?>';"><?=get_string('cproceedfinal','book')?></button>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function () {
      $('#memberModal').modal('show');
      $('.proceed-exam').show();
      $('.disableproceed').remove();
    console.log("etinng");
    });
    </script>
  <?php
  //1069 start
  } else{ ?>
    <script type="text/javascript">
    $(document).ready(function () {
      $('.disableproceed').remove();
    });
    </script>
    <?php
  }
  //1069 end
}
elseif($classroomtypeflag == 1){
  ?>
<!-- Modal Start For New Certificate PDF Generation Page-->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel" style="color:green;"><?=get_string('cdowncert','book')?></h4>
      </div>
      <div class="modal-body">
        <p><a target="_blank" data="1" id="get_cert" href="<?=$certificateURL?>"><?=get_string('cgetcert','book')?></a></p>                
      </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
      $('.final-exam').show();    
      $('.withoutassign-final-exam').show();
      //--             Start- Feature Request: Course Completion Pop-up Nav          --//
      $('#memberModalCong').modal('show');
$('#memberModalPCourses').modal('show');
      //--             End- Feature Request: Course Completion Pop-up Nav          --//
      $('#memberModal').modal('show');
      $('.disableproceed').remove();
      console.log("Testing4625");
    });
</script>
<?php
}
else{
  
  ?>
<script type="text/javascript">
    $('.proceed-exam').show();
    console.log("Testing465");
    $('.disableproceed').remove();
  </script>
<?php
}
}
elseif($getGradeData->gradepass <= $resultAssignGrade->grade  && !empty($assignURL)){ 

  if(empty($_SESSION['once_per_login_second'.$course->id])){
    
     if(empty($certificateTimeLogObj)){
      //exit("Succaaa21");
    $_SESSION['once_per_login_second'.$course->id] = 2;
?>
<!-- Modal Start For Certificate PDF Generation Page-->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel" style="color:green;">Good Design...</h4>
      </div>
      <div class="modal-body">
        <p><a target="_blank" data="2" id="get_cert" href="<?=$certificateURL?>"><?=get_string('cgetcert','book')?></a></p>
        <p id="p_cert">OR</p>
        <!--  ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  -->

        <!-- <p>Click the button below to open your exit exam page for looking provided feedback.</p> -->
        <p>Click the button below to open your exit exam page and view the provided feedback.</p>
       <!--  ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  -->

      </div>
      <div class="modal-footer"> 
        <!--button type="button" class="btn btn-primary" data-dismiss="modal" onclick="location.href = '<?=$assignURL?>';">Proceed to the Exit exam page</button--> 
        <!--  ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  -->
        <!-- <a class="btn" href="<?=$assignURL?>" target="_blank">Proceed to the Exit exam page</a>  -->
        <a class="btn" href="<?=$assignURL?>" target="_blank">Proceed to the Exit Exam page</a> 
        <!--  ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  -->
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

    $('#memberModal').modal('show');
    //--             Start- Feature Request: Course Completion Pop-up Nav          --//
    $('#memberModalCong').modal('show');
    $('#memberModalPCourses').modal('show');
    //--             End- Feature Request: Course Completion Pop-up Nav          --//
    $('.final-exam').show();
    $('.final-exam-onprocess').hide();
    $('.disableproceed').remove();

});
</script>

<?php 
  $_SESSION['once_per_login_seventh'.$course->id] = 2;
}
elseif(!empty($certificateTimeLogObj) && $renewFLAG != 1){
  //Condition for newly updated lesson and quiz completion( means $renewFLAG!=1 ) Start
$_SESSION['once_per_login_second'.$course->id] = 2;
  
?>

<?php if($certificateIssueTimeLogObj->timeexpired > time() || empty($certificateIssueTimeLogObj)){ ?>
<!-- Modal Start For New Certificate PDF Generation Page-->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel" style="color:green;"><?=get_string('cfinalexamcomp','book')?></h4>
      </div>
      <div class="modal-body">
<?php
if($course_completions_data->reaggregate != 0){ 
?>
<p><?=get_string('cgeneratecert','book')?></p>
<?php } 
elseif($certificateIssueTimeLogObj->timeexpired > time() && ($certificateIssueTimeLogObj->timeexpired > $certificateTimeLogObj->timecompletion || $certificateIssueTimeLogObj->timecreated < $certificateTimeLogObj->timecompletion)){
?>
        <p><?=get_string('crenewcourse','book')?></p>
  <p><a target="_blank" id="get_cert" href="<?=$certificateURL?>"><?=get_string('creprintcert','book')?></a></p>
<?php } else{ ?>
        <p><?=get_string('ccongcourse','book')?></p>

        <p><a target="_blank" data="3" id="get_cert" href="<?=$certificateURL?>"><?=get_string('cgetcert','book')?></a></p>

<?php
}
?>



      </div>
  </div>
</div>
<?php
}
?>
<script type="text/javascript">
    $(document).ready(function () {
$('.final-exam').show();
$('.final-exam-onprocess').hide();
    $('#memberModal').modal('show');
$('.disableproceed').remove();
});
</script>

<?php
  //exit("I am here");
} //Condition for newly updated lesson and quiz completion Ends
} //endif once_per_login_second
else{
  //if(empty($certificateTimeLogObj)){
  ?>
<script type="text/javascript">
    $('.final-exam').show();
    //--             Start- Feature Request: Course Completion Pop-up Nav          --//
      $('#memberModalCong').modal('show');
      $('#memberModalPCourses').modal('show');
    //--             End- Feature Request: Course Completion Pop-up Nav          --//
    $('.final-exam-onprocess').hide();
    $('.disableproceed').remove();
  </script>
<?php
//}
 $_SESSION['once_per_login_seventh'.$course->id] = 2;
}
}
elseif($getGradeData->gradepass > $resultAssignGrade->grade && !empty($assignURL) && $classroomtypeflag == 0){
if(empty($_SESSION['once_per_login_third'.$course->id])){
  $_SESSION['once_per_login_third'.$course->id] = 2;
?>
<!-- Modal Start For Bad Design Redirect on Exit Exam Page-->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel" style="color:red;">Design...</h4>
      </div>
      <div class="modal-body">
        <p style="text-align:justify;">
          <?=get_string('summaryExitExamPopUp', 'book')?>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="location.href = '<?=$assignURL?>';"><?=get_string('cproceedfinal','book')?></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

    $('#memberModal').modal('show');
$('.final-exam-onprocess').show();
$('.disableproceed').remove();

});
</script>
<?php
}
else{
  ?>
<script type="text/javascript">
    $('.final-exam-onprocess').show();
$('.disableproceed').remove();
  </script>
<?php
}
}
elseif(empty($resultAssignGrade) && empty($assignURL) && $renewFLAG != 1 && !empty($certificateTimeLogObj)){
if(empty($_SESSION['once_per_login_thirds'.$course->id])){
  $_SESSION['once_per_login_thirds'.$course->id] = 2;
?>
<?php if($certificateIssueTimeLogObj->timeexpired > time()){ ?>
<!-- Modal Start For New Certificate PDF Generation Page-->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel" style="color:green;"><?=get_string('cdownnewcert','book')?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo get_string('cdownnewcertcontent','book',$course->fullname);?></p>
        <p><a target="_blank" data="4" id="get_cert" href="<?=$certificateURL?>"><?=get_string('cgetcert','book')?></a></p>                
      </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
      $('.final-exam').show();    
      $('.withoutassign-final-exam').show();
      //--             Start- Feature Request: Course Completion Pop-up Nav          --//
      $('#memberModalCong').modal('show');
      $('#memberModalPCourses').modal('show');
      //--             End- Feature Request: Course Completion Pop-up Nav          --//
      $('#memberModal').modal('show');
      $('.disableproceed').remove();
    });
</script>
<?php }?>
<?php
}
else{
  ?>
<script type="text/javascript">
    $('.final-exam').show();    $('.withoutassign-final-exam').show();
$('.disableproceed').remove();
  </script>
<?php
}
}
else{ //certificateTimeLogObj 
  ?>
<script type="text/javascript">
    //$('.final-exam').show();
     //--             Start- Feature Request: Course Completion Pop-up Nav          --//
    $('#memberModalCong').modal('show');
    $('#memberModalPCourses').modal('show');
    //--             End- Feature Request: Course Completion Pop-up Nav          --//
    $('.disableproceed').remove();
  </script>
<?php
  $_SESSION['once_per_login_seventh'.$course->id] = 2;  
}
}
elseif(!empty($certificateTimeLogObj) && $renewFLAG == 1){
//Condition for newly updated lesson and quiz added start. This PopUp Shown only one month before of previous certificate expiration.
  $keyCertVal = $DB->get_record('simplecertificate', array('course'=>$course->id), "certexpirydateinyear");
  $certExpDurationDate = $keyCertVal->certexpirydateinyear;
  if($certificateIssueTimeLogObj->timeexpired == 0 || $certificateIssueTimeLogObj->timeexpired == ''){
    $certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
  }
  else{
    $certExpiredDate = $certificateIssueTimeLogObj->timeexpired;
  }
  //$certExpiredDate = strtotime($certExpDurationDate." years", $certificateTimeLogObj->timecompletion);
//RCP start
$beforeCertExpiredThirtyDaysTime = strtotime("-1 month",$certExpiredDate);
  //$beforeCertExpiredThirtyDaysTime = $certExpiredDate - 2592000; //Get one month before date to the expired date
//RCP end
  $todayTime = time(); // get current date

  if($beforeCertExpiredThirtyDaysTime <= $todayTime){
    //This PopUp Shown only one month before of previous certificate expiration.
  ?>
  <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:pink;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel"><?=get_string('crenewcert','book')?></h4>
      </div>
      <div class="modal-body first-part renew-exam">
        <p style="text-align:justify;">

        <?php // $date_renew=date("m/d/Y",$certificateTimeLogObj->timecompletion); ?>
        <?php 
//Certificate Date code start
$date_renew=date("m/d/Y",$certificateIssueTimeLogObj->timeexpired); 
//Certificate Date code end
?>
        <?=get_string('renewexam','book',$date_renew)?>        
        </p>
      </div>
    </div>
  </div>
</div>   
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#memberModal').modal('show');
$('.disableproceed').remove();
});
</script>
 <?php
  }
else {
    if(empty($_SESSION['once_per_login_fourths'.$course->id])){
      $_SESSION['once_per_login_fourths'.$course->id] = 2;
    ?>
    <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:pink;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel"><?=get_string('newmodadd','book')?></h4>
      </div>
      <div class="modal-body first-part renew-exam">
        <?php $date_newmodupdate_desc = date("m/d/Y",$certExpiredDate); ?>
        <?=get_string('newmodupdatedesc','book',$date_newmodupdate_desc)?>
      </div>
    </div>
  </div>
</div>   
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {

    $('#memberModal').modal('show');
$('.disableproceed').remove();
});
</script>
<?php
  } // end session
 } //end else
//Condition for newly updated lesson and quiz added ends
}
?>

<!-- End for modal box -->
<?php
    echo $OUTPUT->footer();
?>
<style type="text/css">
#block-region-side-pre{
display: none;
}
::-moz-selection {
background-color: #3496c7 ;
color: #FFF;
}
/* Works in Safari */
::selection {
background-color: #3496c7 ;
color: #FFF; 
} 
</style>

<?php  
if($USER){
  ?>
<script type="text/javascript">
  $("#disableproceed").show();
console.log("Testing-1989");
  $("#searchContent").append($("#disableproceed").html());
console.log("Testing-2989");
  $("#disableproceed").remove();
</script>
<?php
}
?>
<script type="text/javascript">
$("#region-main").removeClass("htmlcreation");
$("#region-main").addClass("htmlnewcreation");
$("#region-main").addClass("new-lessong-page");
</script> 

<!--For expand and Collapse--> 
<script type="text/javascript">
         
 $(".titleTrigger").click(function(e){
    e.preventDefault();
$(this).toggleClass("active").next().slideToggle("fast");
return false;
});

////CLOSE LINK
//$(".closeIt").click(function(e){
// e.preventDefault();
//$(this).closest(".toggleContent").slideUp("fast");
//$(this).closest(".toggleContent").removeClass("active");
//$(this).closest(".titleTrigger").removeClass("active");
// }); 

//Cust by Pawan start
  $(".titleTrigger").removeClass("active");
  $(".firstItem").removeClass("firstItem");
  $(".toggleContent").css("display", "none");
  $(".toggleContent").slideUp("fast");
  $(".selected-part").parent().toggleClass("active");
  $(".active").slideDown("fast");
//Cust by Pawan end
//expand all
$(".collapseAll").click(function(e){
    e.preventDefault();
    $(".titleTrigger").addClass("active");
  $(".toggleContent").addClass("active");
    $(".toggleContent").slideDown("fast"); 
});
//collapse all

$(".expandAll").click(function(e){
    e.preventDefault();
    $(".titleTrigger").removeClass("active");
  $(".firstItem").removeClass("firstItem"); 
  $(".toggleContent").removeClass("active");
    $(".toggleContent").slideUp("fast");
});
</script> 
<script type="text/javascript">
$(document).ready(function () {
    $("#clicky, .toggleContent li a").click(function () {
        if ($(window).width() < 768) {
            if ($("#slide").is(':visible')) {
                $("#slide").animate({ width: 'hide' }, 'normal');
            }
            else {
                $("#slide").animate({ width: 'show' }, 'normal');

            }
        }
    });

}); 
</script> 

<script type="text/javascript">
$("#clicky, .toggleContent li a").click(function() {
   $(this).toggleClass("close_cls");
});
</script>
<style type="text/css">
  body > *{
    box-sizing: inherit !important;
  }
</style>
<script type="text/javascript" src="<?php echo $CFG->wwwroot ?>/mod/book/src/sharer.js"></script> 
<script type="text/javascript" >
      $(document).ready(function(){
        $('.sharer').sharer();
      });
$(".selected-part").focus();
</script> 
   
    
<script>
$(document).ready(function(){
var bookID = "<?=$_GET['id']?>";
     /* $("html, body").animate({
        scrollTop: $("#searchContent").offset().top -20
    }, 100);*/
    
  /*  $('#searchContent').animate({
        scrollTop: $("#act_"+bookID).offset().top -20
    }, 100);*/
//   $("#act_"+bookID).focus();

});
</script>     
        
<!--For Popup issue-->  
<script>
$(document).ready(function(){
    $("button.close").click(function(){
       $("#memberModal").css({'opacity':'0'});
    });

    //$("#act_222").focus();
     // $("#pact_10").focus();
});
</script>
<!--End Popup issue-->

<!-- Certificate Date Code start -->
<?php
if(!empty($certificateIssueTimeLogObj)){
  $certexpire = get_string("certexpire","book");
  $cdownnewcert = get_string("cdownnewcert","book");
  $a=0;

      $cmcDataSQL = 'SELECT count(cmc.id) as CMC FROM mdl_course_modules cm JOIN mdl_course_modules_completion cmc ON cmc.coursemoduleid = cm.id WHERE cm.course ='.$course->id.' and cmc.userid='.$USER->id.' and cmc.completionstate != 0 and (cm.module=3 OR cm.module=16) and cm.visible=1';
      
      $cmcDataRes = $DB->get_record_sql($cmcDataSQL);

      $cmDataSQL = 'SELECT count(cm.id) as CM FROM mdl_course_modules cm WHERE cm.visible=1 and cm.course ='.$course->id.
      ' AND (cm.module=3 OR cm.module=16)';
      $cmDataRes = $DB->get_record_sql($cmDataSQL);
     


?>

<?php
  if(!empty($course_completions_data) && $certificateIssueTimeLogObj->timeexpired < time() && $certificateIssueTimeLogObj->timecreated < $certificateTimeLogObj->timecompletion){   
$a=1; 
//RCP start
  ?>
  <script>
  $(document).ready(function(){
      $(".download-certificate-link a").text('<?=$creprintcert?>');
  });
  </script>
<?php 
//RCP end
} 
if(empty($course_completions_data) && ($certificateIssueTimeLogObj->timeexpired < time() || $certificateIssueTimeLogObj->timeexpired < $certificateTimeLogObj->timecompletion)){ ?>
  <script>
  $(document).ready(function(){
      $(".congrts-text").remove();
      $(".download-certificate-link").html('<strong>You have completed new module. Once you reached on certificate expiration date your new certificate will be generated..</strong>');
  });
  </script>
  <?php } ?>

<?php
  if($course_completions_data->reaggregate != 0){
  ?>
  <script>
  $(document).ready(function(){
      /* ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  */
     
      //$(".congrts-text").html("<strong>Certificate download link is in process. Please refresh page again with in 10-15 minutes.</strong>");
      $(".congrts-text").html("<strong>A download link containing your certificate is in process. Please refresh the page after a few minutes.</strong>");

    /* ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  */
            $(".download-certificate-link").hide();
  });
  </script>
<?php } 
  elseif($a==0 && $certificateIssueTimeLogObj->status ==0 && $certificateIssueTimeLogObj->timeexpired < time() && $certificateIssueTimeLogObj->timeexpired > $certificateTimeLogObj->timecompletion && $certificateIssueTimeLogObj->timecreated > $certificateTimeLogObj->timecompletion){    

?>
  <script>
  $(document).ready(function(){
      $(".withoutassign-final-exam").addClass('renew-exam').removeClass('final-exam').html('<div class="titleTrigger active"><h3><?=get_string('cgeneratecert','book')?></h3></div>');
  });
  </script>
<?php } elseif($certificateIssueTimeLogObj->timecompletion > time() && ($certificateIssueTimeLogObj->timeexpired > $certificateTimeLogObj->timecompletion || $certificateIssueTimeLogObj->timecreated < $certificateTimeLogObj->timecompletion)){     
  //New module added and completed case
?>
  <script>
  $(document).ready(function(){
      $(".congrts-text").html('<?=get_string("crenewcourse","book")?>');
      $(".download-certificate-link a").text('<?=get_string("creprintcert","book")?>');
  });
  </script>
<?php } 
  if($cmcDataRes->cmc == $cmDataRes->cm && !empty($course_completions_data) && $course_completions_data->timecompleted == NULL){    
  //New module completion and just before re-aggregate case status
?>
  <script>
  $(document).ready(function(){
      
  /* ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  */

      //$(".congrts-text").html('<strong>Certificate download link is in process. Please refresh page again with in 5-10 minutes.</strong>');

      $(".congrts-text").html('<strong>A download link containing your certificate is in process. Please refresh the page after a few minutes.</strong>');

  /* ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  */      

      $(".download-certificate-link").hide();
  });
  </script>
<?php } 
if($certificateIssueTimeLogObj->timeexpired < time() && $course_completions_data->reaggregate == 0 && $certificateIssueTimeLogObj->timeexpired > $certificateTimeLogObj->timecompletion){
?>
<script>
$(document).ready(function(){
      $(".download-certificate-link").html("<span style='color:red;'>Your certificate expired</span>");
});
</script>
<?php } 
?>


<?php } ?>
<!-- Certificate Date Code end -->

<!-- RCP start -->
<?php
if($_SESSION['flagNewModuleAdded'] == 1){
?>
<script>
$(document).ready(function(){
      $("#newmoduleaddedflag").html("You can download your current certificate.");
});
</script>
<?php } 
$_SESSION['flagNewModuleAdded']=0;
?>
<!-- RCP end -->
<?php 
//56 Est NM Start
if($_SESSION['NM'] == 4){
    if(empty($_SESSION['once_per_login_fifth'.$course->id])){
        $_SESSION['once_per_login_fifth'.$course->id] = 2;
  $scertificateObj = $DB->get_record_sql('SELECT id FROM mdl_simplecertificate WHERE course = ? ', array( $course->id ));

  $certIssueObj = $DB->get_record_sql('SELECT id, timecreated, timeexpired, timecreatedclassroom, haschange, status FROM mdl_simplecertificate_issues WHERE userid = ? AND certificateid = ? AND timecreated is NOT NULL order by id desc', array( $USER->id, $scertificateObj->id ));
  if(empty($certIssueObj)){
    //Get course assignment
    if($course->id == 7){
        //exit("Success");

      $ggDataRes = $DB->get_record_sql('SELECT gradepass FROM mdl_grade_items WHERE courseid = ? AND itemmodule = ? AND iteminstance = ?', array(7, 'assign', 10));
      $gradePass = $ggDataRes->gradepass;

      $agDataSQL = 'SELECT count(ag.id) as countagrades FROM mdl_assign_grades ag WHERE grade <= '.$gradePass.' and userid='.$USER->id.' and assignment IN (10,18)';
      $agDataRes = $DB->get_record_sql($agDataSQL);
      $rs71 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=10 order by id desc limit 0,1 ");
    }else{
      $getAssignData = $DB->get_record_sql('SELECT id FROM mdl_assign WHERE course = ? AND type=? order by id desc', array( $course->id, "online" ));
      $getGradeDataObj = $DB->get_record_sql('SELECT gradepass FROM mdl_grade_items WHERE courseid = ? AND itemmodule = ?', array( $course->id, 'assign'));
      $gradePass = $getGradeDataObj->gradepass;

      $agDataSQL = 'SELECT count(ag.id) as countagrades FROM mdl_assign_grades ag WHERE grade >= '.$gradePass.' and userid='.$USER->id.' and assignment ='.$getAssignData->id;
      $agDataRes = $DB->get_record_sql($agDataSQL);
      $rs71 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=$getAssignData->id order by id desc limit 0,1 ");
    }


?>
  <div class="modal fade" id="memberModalNM" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:pink;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="memberModalLabel">EEK!</h4>
      </div>
      <div class="modal-body">
    <?php
    $eekflag=0;
    if($agDataRes->countagrades > 0){
      $eekflag=0;
    ?>
    We've recently updated some of the training curriculum and we want to make sure you learn it on your way to getting certified. Not to worry though, just return to the course and watch these new modules. If you have any questions, simply e-mail <a href="mailto:qsc.training@qsc.com">qsc.training@qsc.com</a>.
    <?php }else{ ?>
    <?php if(!empty($rs71)) { $eekflag=0; ?>
    We've recently updated some of the training curriculum and we want to make sure you learn it on your way to getting certified. Not to worry though, just return to the course and watch these new modules before submitting your next exit exam. Look for the new modules that are missing the green checkmark. If you have any questions, simply e-mail <a href="mailto:qsc.training@qsc.com">qsc.training@qsc.com</a>.
    <?php }else{ $eekflag=1;} ?>
    <?php } ?>
</div>
    </div>
  </div>
</div>   
<script src="<?php echo $CFG->wwwroot ?>/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
      var eekflag = "<?=$eekflag?>";
      if(eekflag == 0){
        //console.log("<?=$_SESSION['NM']?>");
        $('#memberModalNM').modal('show');
      }

});
</script>
<?php 
  }
   }
} 
$_SESSION['NM'] = 0; 
//56 Est NM end
?>

<?php
//US #3819 start
if($validFlag == 1){
  if(empty($_SESSION['once_per_login_admin_reset'.$course->id])){             
     $_SESSION['once_per_login_admin_reset'.$course->id] = 2;
            
?>
<div class="modal fade" id="resetLessonModal" tabindex="-1" role="dialog" aria-labelledby="resetLessonModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <p>One or more of the lessons in the course have been reset and require you to view them again.</p>    
      </div>    
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('#resetLessonModal').modal('show');      
  });
</script>
<?php
  }
}
//US #3819 end
?>

<style type="text/css">
.no-overflow{
  overflow: auto !important;
  height: auto !important;
}
</style>
