<?php
require_once('../../config.php');
global $CFG, $PAGE, $USER, $DB;
require_once($CFG->libdir.'/tablelib.php');
require_once('./edit_form.php');
use html_writer;
use \local_assign\locallib as callobj;
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin') {
    redirect("/");
}
$msg='';
$id = required_param('id', PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Manage private course(s) tag');
$PAGE->set_heading('Edit Private Course Tag Name');
$PAGE->set_url('/local/assign/edit_stext.php');
//
$PAGE->requires->jquery();
$PAGE->requires->jquery('ui');

/*$coursenode = $PAGE->navigation->find($course_id, navigation_node::TYPE_COURSE);
$thingnode = $coursenode->add(get_string('addspeedtext','local_assign'), new moodle_url('/local/assign/speedtext.php?cid='.$course_id.''));
$thingnode->make_active();*/
$PAGE->navbar->add(' Edit Speed Text', new moodle_url('/local/assign/edit_stext.php?id='.$id.''));
$PAGE->set_url('/local/assign/edit_stext.php?id='.$id);
echo $OUTPUT->header();
echo $OUTPUT->heading('Edit Speed Text');
$actional_url = new moodle_url('/local/assign/edit_stext.php?action=submit');
$mform = new edit_form($actional_url,['cid'=>$course_id,'userid'=>$USER->id]);

if(!empty($_POST['edit'])) {
    $data = $_REQUEST;
    $button_lable = trim($_POST['label_name']);
    $comment_text = trim($_POST['comment_text']);
    if (!empty($button_lable)) {
            $tag_name = $DB->get_record_sql("SELECT * FROM {speed_text}  where id=".$id);
            if (!empty($tag_name)) {
                $course_id          = $tag_name->course_id;
                $fordb                  = new stdClass();
                $fordb->id              = $id;
                $fordb->button_lable    = $button_lable;
                $fordb->comment_text    = $comment_text;
                
                $newid = $DB->update_record('speed_text', $fordb);
                redirect(new moodle_url('/local/assign/speedtext.php',array('cid'=>$course_id)));           
       
            }
    }
}

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($data = $mform->get_data()) {
    //print_object($data);die;

} else {
  $mform->set_data($toform);
  //displays the form
  $mform->display();

}
echo $OUTPUT->footer();
?>
<script type="text/javascript">

     function validateForm() {      
        var SpeedText = document.getElementById('id_label_name').value;
        var commentSpeedText = document.getElementById('id_comment_text').value;
        if (SpeedText.trim() == '') {
           document.getElementById("errMessage").innerHTML ="Please enter Button label.";
            return false; 
        }
        if (commentSpeedText.trim() == '') {
           document.getElementById("errMessage2").innerHTML ="Please enter Comment Text.";
            return false; 
        }
    }
</script>