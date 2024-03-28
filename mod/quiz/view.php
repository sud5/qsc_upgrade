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
 * This page is the entry page into the quiz UI. Displays information about the
 * quiz to students and teachers, and lets students see their previous attempts.
 *
 * @package   mod_quiz
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_quiz\access_manager;
use mod_quiz\output\renderer;
use mod_quiz\output\view_page;
use mod_quiz\quiz_attempt;
use mod_quiz\quiz_settings;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/quiz/locallib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/course/format/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or ...
$q = optional_param('q',  0, PARAM_INT);  // Quiz ID.

if ($id) {
    $quizobj = quiz_settings::create_for_cmid($id, $USER->id);
} else {
    $quizobj = quiz_settings::create($q, $USER->id);
}
$quiz = $quizobj->get_quiz();
$cm = $quizobj->get_cm();
$course = $quizobj->get_course();

// Check login and get context.
require_login($course, false, $cm);
$isadmin = is_siteadmin($USER);
    if(!$isadmin){

                //US #3820 start
        // echo $quiz->id;
        // exit; 

        $sqlQuiz1 = "SELECT * FROM {quiz} where id=".$cm->instance;
        $rsQuiz1 = $DB->get_record_sql($sqlQuiz1);     
        
        if($rsQuiz1->attempts == 0){
            $sqlQuizOverride1 = "SELECT * FROM {quiz_overrides} where quiz=".$cm->instance." and userid = ".$USER->id;
            $rsQuizOverride1 = $DB->get_record_sql($sqlQuizOverride1);
            if(!empty($rsQuizOverride1)){
                $overrideid1 = $rsQuizOverride1->id;
                $DB->delete_records('quiz_overrides', array('id' => $overrideid1));
            }
        }
        //US #3820 end
        // ----- customization_Naveen_Start --------//
        /* Fixed Issue By Sameer on SQL Query */ 
        $course_version= $course->course_version;
        if($course_version == 'inclassroom_quiz')
        {
            $facetoface2 = $DB->get_record('facetoface', array('course' => $course->id));
            if(!empty($facetoface2))
            {
              // $ftof_signups_query2 = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $USER->id AND fse.facetoface = $facetoface2->id";
                $ftof_signups_query7 = "SELECT fse.*, fs.userid, fs.id as signupid FROM {facetoface_sessions} fse JOIN {facetoface_signups} fs ON fs.sessionid = fse.id WHERE fs.userid =". $USER->id ." AND fse.facetoface = $facetoface2->id order by fs.id desc";
                $ftof_signups_queryRS = $DB->get_record_sql($ftof_signups_query7);
                if(!empty($ftof_signups_queryRS)){
                    $sqlSCI_213 = "SELECT fss.* FROM {facetoface_signups_status} fss where fss.signupid = $ftof_signups_queryRS->signupid AND fss.superceded= 0 order by fss.id desc";
                    $issueCert_213_ObjData = $DB->get_record_sql($sqlSCI_213);
                    
                    if($issueCert_213_ObjData->statuscode != 70 && $issueCert_213_ObjData->statuscode == 60 )
                    {
                        redirect('/my/');
                    }
                }
                else{
                    redirect('/my/');
                }
            }else{
                redirect('/my/');
            }
            
        }
        // ----- customization_Naveen_End --------//

        //NM start
        //Observation Classroom Version 
    if($course->id==7 || $course->id==22){
        
        $assignGrades = $DB->get_record_sql("select * from {assign_grades} where userid=".$USER->id." and (assignment=10 || assignment=18 || assignment=11 || assignment=13) and grade >= 70 limit 0,1");
//NM end
        if(!empty($assignGrades)){

            $quizGrades = $DB->get_record_sql("select * from {quiz_grades} where userid=".$USER->id." and quiz=".$cm->instance." limit 0,1");
            if(!empty($quizGrades)){
                $quizAttempts = $DB->get_record_sql("select * from {quiz_attempts} where userid=".$USER->id." and quiz=".$cm->instance." limit 0,1");
                if(empty($quizAttempts) && $course->id == 7){
                    redirect('/mod/book/view.php?id=28',"By default, system assigned passing mark for this assessment because of you have successfully completed the classroom version OR your's profile merge with old profile for this course.",5);
                }
                elseif(empty($quizAttempts) && $course->id == 22){
                    redirect('/mod/book/view.php?id=249',"By default, system assigned passing mark for this assessment because of you have successfully completed the classroom version OR your's profile merge with old profile for this course.",5);
                }
            }
        }
    }

     //US 1069 Task Start 
    if($course->category != 0) {   
        $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$course->id,'criteriatype'=>8),'','id, course, courseinstance');        
        if($coursesData){
            $flagAccessLevel2 = 2;
            foreach($coursesData as $keyCD) {   
                if($keyCD->courseinstance != '') {              
                    $sqlm00421 = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$USER->id.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course = '.$keyCD->courseinstance;
                    $sqlm00421Res = $DB->get_record_sql($sqlm00421);
                    if($sqlm00421Res->cntcert > 0){
                       $flagAccessLevel2 = 1;
                    }
                }
            }
        }
        // AND combination
        $sqlCCC = 'SELECT ccc.* FROM {course_completion_aggr_methd} ccam JOIN {course_completion_criteria} ccc ON ccam.course=ccc.course WHERE ccc.course =' . $course->id . ' AND ccc.course != 42 AND ccam.criteriatype = 8 AND ccam.method = 1';
        $cccRes = $DB->get_records_sql($sqlCCC);
        if($cccRes) {
            foreach ($cccRes as $cccObj) {
                if($cccObj->courseinstance != '') {        
                    $sqlmCCC = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid ='.$USER->id.' AND (sci.timeexpired > '.time().' || sci.timeexpired=99) AND sc.course = '.$cccObj->courseinstance;
                    $sqlmCCCRes = $DB->get_record_sql($sqlmCCC);
                    if($sqlmCCCRes->cntcert == 0){
                       $flagAccessLevel2 = 2;
                       $getInCompPreCourseId[] = $cccObj->courseinstance;
                    }
                }
            }
        }

        if($flagAccessLevel2 == 2) {
            echo $OUTPUT->header();
            include_once("../../course/prereq_comp_for_quiz_online_course.php");
        ?>
            <div class="modal-body page-four4">
               <p>
                  Assessments and exams for this course are only available once you have completed your certification in: <?=$liCourseList?>
                </p>
                <div class="final-button-classroom">
                  <div class="returendashboard-btn"> <a href="/my">Return to Dashboard</a> </div>                  
                </div>
            </div>
            <?php
            echo $OUTPUT->footer();
            ?>
            <style type="text/css">
                .final-button-classroom div {
                 display: inline-block;
                 margin-top: 15px;
                }
                .page-four4 .returendashboard-btn a {
                 background: #0070c0 none repeat scroll 0 0 !important;
                 border: medium none !important;
                 color: white;
                 padding: 6px 14px;
                }
               .page-four4 .returendashboard-btn a:hover {
                 background: #333 none repeat scroll 0 0 !important;
                }
            </style>
        <?php
            exit;
        }
    }    
    //US 1069 Task End
    
	if(!isset($_REQUEST['waiton'])){
        // ----- customization_Naveen_Start --------//
        if($course->course_version !== 'inclassroom_quiz')
        {
        // ----- customization_Naveen_End --------//
            //NM start
            if(empty($assignGrades)){
    		$facetofaceObj = $DB->get_record_sql('SELECT f.id,fsi.sessionid,fss.signupid,fss.statuscode FROM `mdl_facetoface` as f JOIN mdl_facetoface_sessions as fs ON f.id = fs.facetoface JOIN mdl_facetoface_signups as fsi ON fs.id=fsi.sessionid JOIN mdl_facetoface_signups_status as fss ON fsi.id=fss.signupid WHERE f.course=? AND fsi.userid = ? order by fss.timecreated desc LIMIT 0,1', array($course->id, $USER->id));
    	//echo "<pre>";print_r($facetofaceObj); exit;
    	    	if(!empty($facetofaceObj) && ($facetofaceObj->statuscode == 70 || $facetofaceObj->statuscode == 60)){
    		    redirect('/mod/facetoface/wait.php?f='.$facetofaceObj->id.'&mod=quiz&id='.$id);
    	    	}
            }
            //NM end
        // ----- customization_Naveen_Start --------//
        }
        // ----- customization_Naveen_End --------//
	}
    }

$context = $quizobj->get_context();
require_capability('mod/quiz:view', $context);

// Cache some other capabilities we use several times.
$canattempt = has_capability('mod/quiz:attempt', $context);
$canreviewmine = has_capability('mod/quiz:reviewmyattempts', $context);
$canpreview = has_capability('mod/quiz:preview', $context);

// Create an object to manage all the other (non-roles) access rules.
$timenow = time();
$accessmanager = new access_manager($quizobj, $timenow,
        has_capability('mod/quiz:ignoretimelimits', $context, null, false));

// Trigger course_module_viewed event and completion.
quiz_view($quiz, $course, $cm, $context);

// Initialize $PAGE, compute blocks.
$PAGE->set_url('/mod/quiz/view.php', ['id' => $cm->id]);

// Create view object which collects all the information the renderer will need.
$viewobj = new view_page();
$viewobj->accessmanager = $accessmanager;
$viewobj->canreviewmine = $canreviewmine || $canpreview;

// Get this user's attempts.
$attempts = quiz_get_user_attempts($quiz->id, $USER->id, 'finished', true);
$lastfinishedattempt = end($attempts);
$unfinished = false;
$unfinishedattemptid = null;
if ($unfinishedattempt = quiz_get_user_attempt_unfinished($quiz->id, $USER->id)) {
    $attempts[] = $unfinishedattempt;

    // If the attempt is now overdue, deal with that - and pass isonline = false.
    // We want the student notified in this case.
    $quizobj->create_attempt_object($unfinishedattempt)->handle_if_time_expired(time(), false);

    $unfinished = $unfinishedattempt->state == quiz_attempt::IN_PROGRESS ||
            $unfinishedattempt->state == quiz_attempt::OVERDUE;
    if (!$unfinished) {
        $lastfinishedattempt = $unfinishedattempt;
    }
    $unfinishedattemptid = $unfinishedattempt->id;
    $unfinishedattempt = null; // To make it clear we do not use this again.
}
$numattempts = count($attempts);

$viewobj->attempts = $attempts;
$viewobj->attemptobjs = [];
foreach ($attempts as $attempt) {
    $viewobj->attemptobjs[] = new quiz_attempt($attempt, $quiz, $cm, $course, false);
}

// Work out the final grade, checking whether it was overridden in the gradebook.
if (!$canpreview) {
    $mygrade = quiz_get_best_grade($quiz, $USER->id);
} else if ($lastfinishedattempt) {
    // Users who can preview the quiz don't get a proper grade, so work out a
    // plausible value to display instead, so the page looks right.
    $mygrade = quiz_rescale_grade($lastfinishedattempt->sumgrades, $quiz, false);
} else {
    $mygrade = null;
}

$mygradeoverridden = false;
$gradebookfeedback = '';

$item = null;

$gradinginfo = grade_get_grades($course->id, 'mod', 'quiz', $quiz->id, $USER->id);
if (!empty($gradinginfo->items)) {
    $item = $gradinginfo->items[0];
    if (isset($item->grades[$USER->id])) {
        $grade = $item->grades[$USER->id];

        if ($grade->overridden) {
            $mygrade = $grade->grade + 0; // Convert to number.
            $mygradeoverridden = true;
        }
        if (!empty($grade->str_feedback)) {
            $gradebookfeedback = $grade->str_feedback;
        }
    }
}

$title = $course->shortname . ': ' . format_string($quiz->name);
$category = $DB->get_record('course_categories', array('id'=>$course->category), 'id, name', MUST_EXIST);
$PAGE->set_title($title);
$PAGE->set_heading($course->fullname);
if (html_is_blank($quiz->intro)) {
    $PAGE->activityheader->set_description('');
}
$PAGE->add_body_class('limitedwidth');
/** @var renderer $output */
$output = $PAGE->get_renderer('mod_quiz');

// Print table with existing attempts.
if ($attempts) {
    // Work out which columns we need, taking account what data is available in each attempt.
    list($someoptions, $alloptions) = quiz_get_combined_reviewoptions($quiz, $attempts);

    $viewobj->attemptcolumn  = $quiz->attempts != 1;

    $viewobj->gradecolumn    = $someoptions->marks >= question_display_options::MARK_AND_MAX &&
            quiz_has_grades($quiz);
    //$viewobj->markcolumn     = $viewobj->gradecolumn && ($quiz->grade != $quiz->sumgrades);
    $viewobj->overallstats   = $lastfinishedattempt && $alloptions->marks >= question_display_options::MARK_AND_MAX;

    $viewobj->feedbackcolumn = quiz_has_feedback($quiz) && $alloptions->overallfeedback;
}

$viewobj->timenow = $timenow;
$viewobj->numattempts = $numattempts;
$viewobj->mygrade = $mygrade;
$viewobj->moreattempts = $unfinished ||
        !$accessmanager->is_finished($numattempts, $lastfinishedattempt);
$viewobj->mygradeoverridden = $mygradeoverridden;
$viewobj->gradebookfeedback = $gradebookfeedback;
$viewobj->lastfinishedattempt = $lastfinishedattempt;
$viewobj->canedit = has_capability('mod/quiz:manage', $context);
$viewobj->editurl = new moodle_url('/mod/quiz/edit.php', ['cmid' => $cm->id]);

$cmdlinkObj = $DB->get_record_sql('SELECT id, course, module FROM `mdl_course_modules` as cm WHERE cm.course=? AND cm.module = ? LIMIT 0,1', array($course->id, 3));

//Error: Resolve
if($cmdlinkObj->id){
$viewobj->backtocourseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
}
else{
$viewobj->backtocourseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
}

$viewobj->startattempturl = $quizobj->start_attempt_url();

if ($accessmanager->is_preflight_check_required($unfinishedattemptid)) {
    $viewobj->preflightcheckform = $accessmanager->get_preflight_check_form(
            $viewobj->startattempturl, $unfinishedattemptid);
}
$viewobj->popuprequired = $accessmanager->attempt_must_be_in_popup();
$viewobj->popupoptions = $accessmanager->get_popup_options();

// Display information about this quiz.
$viewobj->infomessages = $viewobj->accessmanager->describe_rules();
//if ($quiz->attempts != 1) {
//    $viewobj->infomessages[] = get_string('gradingmethod', 'quiz',
//            quiz_get_grading_option_name($quiz->grademethod));
//}

// Inform user of the grade to pass if non-zero.
if ($item && grade_floats_different($item->gradepass, 0)) {
    $a = new stdClass();
    $a->grade = quiz_format_grade($quiz, $item->gradepass);
    $a->maxgrade = quiz_format_grade($quiz, $quiz->grade);
    $viewobj->infomessages[] = get_string('gradetopassoutof', 'quiz', $a);
}

// Determine whether a start attempt button should be displayed.
$viewobj->quizhasquestions = $quizobj->has_questions();
$viewobj->preventmessages = [];
if (!$viewobj->quizhasquestions) {
    $viewobj->buttontext = '';

} else {
    if ($unfinished) {
        if ($canpreview) {
            $viewobj->buttontext = get_string('continuepreview', 'quiz');
        } else if ($canattempt) {
            $viewobj->buttontext = get_string('continueattemptquiz', 'quiz');
        }
    } else {
        if ($canpreview) {
            $viewobj->buttontext = get_string('previewquizstart', 'quiz');
        } else if ($canattempt) {
            $viewobj->preventmessages = $viewobj->accessmanager->prevent_new_attempt(
                    $viewobj->numattempts, $viewobj->lastfinishedattempt);
            if ($viewobj->preventmessages) {
                $viewobj->buttontext = '';
            } else if ($viewobj->numattempts == 0) {
                $viewobj->buttontext = get_string('attemptquiz', 'quiz');
            } else {
                $viewobj->buttontext = get_string('reattemptquiz', 'quiz');
            }
        }
    }

    // Users who can preview the quiz should be able to see all messages for not being able to access the quiz.
    if ($canpreview) {
        $viewobj->preventmessages = $viewobj->accessmanager->prevent_access();
    } else if ($viewobj->buttontext) {
        // If, so far, we think a button should be printed, so check if they will be allowed to access it.
        if (!$viewobj->moreattempts) {
            $viewobj->buttontext = '';
        } else if ($canattempt) {
            $viewobj->preventmessages = $viewobj->accessmanager->prevent_access();
            if ($viewobj->preventmessages) {
                $viewobj->buttontext = '';
            }
        }
    }
}

$viewobj->showbacktocourse = ($viewobj->buttontext === '' &&
        course_get_format($course)->has_view_page());

//US #19866 start
$viewobj->showbacktocourse = true;
//US #19866 end
echo $OUTPUT->header();

if (!empty($gradinginfo->errors)) {
    foreach ($gradinginfo->errors as $error) {
        $errortext = new \core\output\notification($error, \core\output\notification::NOTIFY_ERROR);
        echo $OUTPUT->render($errortext);
    }
}

if (isguestuser()) {
    // Guests can't do a quiz, so offer them a choice of logging in or going back.
    echo $output->view_page_guest($course, $quiz, $cm, $context, $viewobj->infomessages, $viewobj);
} else if (!isguestuser() && !($canattempt || $canpreview
          || $viewobj->canreviewmine)) {
    // If they are not enrolled in this course in a good enough role, tell them to enrol.
    echo $output->view_page_notenrolled($course, $quiz, $cm, $context, $viewobj->infomessages, $viewobj);
} else {
    echo $output->view_page($course, $quiz, $cm, $context, $viewobj);
}
//Customization start
echo '<span id="reset_notify"></span>';
?>
<!-- Start Customization for reset quiz attempt -->

    <!-- Modal -->
    <div id="myModal" class="modal hide fade quiz-attempt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?=format_string($category->name)?>: <?=format_string($title)?></h3>
      </div>
      <div class="modal-body">
        <p><span><?=get_string("explanation","quiz")?></span></p>
    <p><textarea id="msg_body" rows="5" cols="123" /></textarea></p>
    <input id="coursemoduleid" type="hidden" value="<?=optional_param('id', null, PARAM_INT);?>">
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=get_string("close","quiz")?></button>
        <button class="btn btn-primary resetexammailsending" id="resetexammailsendings" data-dismiss="modal" aria-hidden="true"><?=get_string("send","quiz")?></button>

      </div>
    </div>
<!-- End Customization for reset quiz attempt -->
<?php
//Customization end
echo $OUTPUT->footer();
?>
<style type="text/css">
    .quizattemptsummary td.c3{
        display: none!important;
    }

    .quizattemptsummary th.c3{
        display: none !important;
    }
    #feedback{
        display: none !important;
    }
    .box .quizattempt{
        text-align: center!important;
    }
</style>
<script>
    $(".quizattemptsummary td.c2").append(" %");
    $(".quizattemptsummary th.c2").append(" (%)");
    $("#region-main").removeClass("span9");
    $("#region-main").addClass("span12");

</script>
