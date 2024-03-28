<link rel="stylesheet" href="https://devtraining2.qsc.com/theme/meline29/style/main.css" type="text/css">
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
 * This file is the entry point to the assign module. All pages are rendered from here
 *
 * @package   mod_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
use context_course;
use \local_assign\locallib as assignobj;
$id = required_param('id', PARAM_INT);

list ($course, $cm) = get_course_and_cm_from_cmid($id, 'assign');

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

require_capability('mod/assign:view', $context);

$assign = new assign($context, $cm, $course);
if($_REQUEST['userid'] != ''){
    $_SESSION['brittuserid'] = $_REQUEST['userid'];// exit;
}
$urlparams = array('id' => $id,
                  'action' => optional_param('action', '', PARAM_ALPHA),
                  'rownum' => optional_param('rownum', 0, PARAM_INT),
                  'userid' => optional_param('userid', 0, PARAM_INT), // added by lakhan
                  'useridlistid' => optional_param('useridlistid', $assign->get_useridlist_key_id(), PARAM_ALPHANUM));

$url = new moodle_url('/mod/assign/view.php', $urlparams);
//custom codes starts
$courseAssignCNTSQL = 'SELECT count(id) as cntexam FROM {assign} WHERE course ='.$course->id;
$courseAssignCNTRes = $DB->get_record_sql($courseAssignCNTSQL);

$courseAssignSQL = 'SELECT id FROM {assign} WHERE course ='.$course->id.' and type ="classroom"' ;
$courseAssignRes = $DB->get_record_sql($courseAssignSQL);
if (isguestuser()) {
    //Submission Feedback code start
    $_SESSION['backURLs']=$_SERVER['REQUEST_URI'];
    //Submission Feedback code end
    redirect(new moodle_url('/login/'));
}
//Task #2186 start
$sess_key_RA = $course->id."_restrict_access";
if ($_SESSION[$sess_key_RA] == 1 && empty(is_siteadmin($USER))) {
  $getCMObjh = $DB->get_record('course_modules', array('id' => $id), '*');
  $rsh71 = $DB->get_record_sql("SELECT status FROM {assign_submission} asu where asu.userid=$USER->id AND asu.assignment=$getCMObjh->instance and (status = 'submitted' OR status='reopened') order by id desc limit 0,1 ");
  if(empty($rsh71)){
    redirect(new moodle_url('/my/'));
  }
}

//Task #2186 end
//Grade Panel start
$grader = assignobj::check_roleuser($USER->id,$course->id,'grader');
//Grade Panel end
//PP2 start
//prev-start
//die("hsi");
if($USER->id != 2 && !$grader && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $USER->usertype != 'grader' ){
//prev-end
    //logic midify
//    assignobj::complition_check($USER->id,$course->id);
}

//PP2 end
// We cheat a bit here in assuming that viewing the last page means the user viewed the whole book.
if (!isguestuser()) {
   $completion = new completion_info($course);
   $completion->set_module_viewed($cm);
}
assignobj::sections_view($cm);
//GetCategory
$category = $DB->get_record('course_categories', array('id'=>$course->category), 'id, name', MUST_EXIST);
$moduleDetailObj = $DB->get_records('course', array('category'=>$course->category), '', "id, fullname");
$facetoface = $DB->get_record('facetoface', array('course' => $course->id));
//CCC start
if (!empty($facetoface)) {
  # code...
  $ftof_signups_query = "SELECT fse.*,fs.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $USER->id AND fse.facetoface = $facetoface->id";

  $ftofSignUpObj = $DB->get_records_sql($ftof_signups_query, [], 0, 1);
  if(!empty($ftofSignUpObj)){
    $classroomSignUpSet = 1;

  } else{
    $classroomSignUpSet = 0;
  }
}else{
  $classroomSignUpSet = 0;
}
$pflag=0;
//CCC ends
assignobj::facetofacecheck(0,$course,$moduleDetailObj,$facetoface);
if (!empty($facetoface)) {
  # code...
  $ftof_exam_query = "SELECT cm.id, cm.instance, a.type FROM {course_modules} as cm JOIN {assign} as a ON cm.instance = a.id WHERE cm.id = $id AND a.type = 'classroom'";
  $examExistObjData = $DB->get_record_sql($ftof_exam_query);
}

if (!empty($examExistObjData) && $pflag == 1 && !is_siteadmin($USER)) {
   redirect(new moodle_url('/course/view.php',['id'=>$course->id]));
}
assignobj::examdata($examExistObjData,$facetoface,$course,$USER,$cm);
if(assignobj::check_roleuser($USER->id,$course->id,'grader')){
    $flag = 0;
    $menuflag=1;
}else{
    $menuflag=0;
}
//Submission Feedback Code start
if(empty(assignobj::check_roleuser($USER->id,$course->id,'grader'))){  // start grader check
    $flagReviewData = assignobj::salesforce_err($USER,$course,$id,$flag,$menuflag,$pflag);
} // End for grader check
//Submission Feedback Code end
//Customization for fetching gradepass value start
$inst = $cm->instance;
$gpQuery = "SELECT id,gradepass FROM {grade_items} WHERE iteminstance = $inst AND courseid = $course->id";
$gradeItemObjData = $DB->get_record_sql($gpQuery);
//echo "<pre>"; print_r($gradeItemObjData); exit;
if($gradeItemObjData){
   $gradePass = $gradeItemObjData->gradepass;
}else{
  $gradePass = 0;
}
$errorStr = get_string("allowederrors","assign");
//Customization for fetching gradepass value end
//custom codes ends

$PAGE->set_url($url);

// Update module completion status.
$assign->set_module_viewed();

// Apply overrides.
$assign->update_effective_access($USER->id);

// Get the assign class to
// render the page.
echo $assign->view(optional_param('action', '', PARAM_ALPHA));
//-----------customm changes modify----------------//
echo "<div id='hiddenComments' style='display:none'>".$_SESSION['fchtml']."</div>";
// - - - - -Start -Feature Request: "Speed Text" Buttons Nav - - - - - --//
echo "<div id='hiddenSpeedText' style='display:none'><div id='fitem_id_speedtext' class='fitem fitem_ftext'><div class='fitemtitle'></div><div class='felement ftext'>".$_SESSION['sthtml']."</div></div></div>";
// - - - - -End -Feature Request: "Speed Text" Buttons Nav - - - - - --//
echo assignobj::scripts_load($flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass);
if(!is_siteadmin($USER)){
    echo html_writer::script("$('#id_cancelbutton').remove();");
}

echo assignobj::grader_scripts_load($flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass);


//56 Est NM start
//Start Commented to invalidate the condition LMS to Salesforce Reporting Errors
if($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin'  && $USER->usertype != 'grader'){
    echo assignobj::learner_scripts_load($course,$USER,$flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass);
}
echo assignobj::passfail_scripts_load($course,$USER,$flag,$menuflag,$pflag,$flagReviewData,$errorStr,$gradePass);
//-------custom changes by shiva ends-----------------//
?>
<style type="text/css">
    #page-mod-assign-view .attempthistory .generaltable.mod-assign-history-panel td.lastcol,
    #page-mod-assign-view .submissionsummarytable .generaltable td.lastcol{
        width: auto;
    }
</style>