<?php
//print_r($_REQUEST);die;
require(__DIR__ . '/../../config.php');
global $USER;
//-------- Start Grader role unable to view Exam comments- Naveen - -- - - - - - - - - - --//
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $USER->usertype != 'grader') {
    redirect("/my/");
}
//-------- End Grader role unable to view Exam comments  - -- - - - - - - - - - --//
//require_once($CFG->libdir.'/tablelib.php');
//require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once($CFG->dirroot.'/mod/assign/renderer.php');


$id = required_param('id', PARAM_INT);
$userid = required_param('userid', PARAM_INT);

if($_REQUEST['userid'] != ''){
    $_SESSION['brittuserid'] = $_REQUEST['userid'];// exit;
}

$urlparams = array('id' => $id,
                  'userid' => $userid,
                  'action' => optional_param('action', '', PARAM_TEXT),
                  'rownum' => optional_param('rownum', 0, PARAM_INT),
                  'useridlistid' => optional_param('action', 0, PARAM_INT));

$url = new moodle_url('/local/assign/comment.php', $urlparams);

list ($course, $cm) = get_course_and_cm_from_cmid($id, 'assign');


require_login($course, true, $cm);

$PAGE->set_url('/local/assign/comment.php', array('id' => $id));
$context = context_module::instance($cm->id);

//require_capability('mod/assign:comment', $context);

$assign = new assign($context, $cm, $course);

if($userid == ""){
  if($_REQUEST['userid'] != ''){
$_SESSION['feuserid']=$_REQUEST['userid'];
}
}else{
$_SESSION['feuserid']=$userid;
}

//GradePanel start
//$sqlRole = "SELECT id FROM {role} WHERE shortname='grader'";
$rsRole = $DB->get_record('role', ['shortname'=>'grader'], 'id');

//$sqlRoleAssignment = "SELECT contextid FROM {role_assignments} where userid=".$USER->id." AND roleid=".$rsRole->id;
$rsRoleAssignment = $DB->get_record('role_assignments', ['userid'=>$USER->id, 'roleid'=>$rsRole->id], 'contextid');

if(count($rsRoleAssignment->contextid) > 0){ 
  $flag = 0; 
  $menuflag=1;
}else{
  $menuflag=0;
}
//GradePanel end
//$DB->set_debug(true);
echo $assign->view(optional_param('action', '', PARAM_TEXT));
//$DB->set_debug(false); 
//echo "Call 33"; exit();
?>
<script type="text/javascript">
//GradePanel start
var menuflag = <?=$menuflag?>;
console.log("Test menuflag" + menuflag);
if(menuflag != 0){
$("#actionmenuaction-3").parent().parent().hide();
}
$(".submissionstatustable").hide();
$(".feedback").hide();
//GradePanel end
</script>
