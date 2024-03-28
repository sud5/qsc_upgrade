<?php
require_once('../../config.php');
global $CFG, $PAGE, $USER, $DB;
//require_once($CFG->libdir.'/tablelib.php');
require_once('./speedtext_form.php');
use html_writer;
use \local_assign\locallib as callobj;
require_login();
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin') {
    redirect("/");
}
$msg = optional_param('msg', '', PARAM_TEXT);
$course_id = optional_param('cid', '0', PARAM_INT);
$course =  $DB->get_record('course', array('id'=>$course_id), '*', MUST_EXIST);
$userId = $USER->id;
//view params
$smsg = '';
$uemsg = '';
$page       = optional_param('page', 0, PARAM_INT);
$deactivate = optional_param('lock', 0, PARAM_INT);
$sortby     = optional_param('sort', 'name', PARAM_ALPHA);
$sorthow    = optional_param('dir', 'ASC', PARAM_ALPHA);
$confirm    = optional_param('confirm', false, PARAM_BOOL);
$delete     = optional_param('delete', 0, PARAM_INT);
$archive    = optional_param('archive', 0, PARAM_INT);
$msg        = optional_param('msg', '', PARAM_TEXT);
$smsg        = optional_param('smsg', '', PARAM_TEXT);

$PAGE->requires->jquery();
$PAGE->requires->jquery('ui');
$PAGE->set_context(context_course::instance($course_id));
$PAGE->set_pagelayout('incourse');
$title = get_string('addspeedtext','local_assign');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->js('/local/assign/js/speedtext.js');
/*$coursenode = $PAGE->navigation->find($course_id, navigation_node::TYPE_COURSE);
$thingnode = $coursenode->add(get_string('addspeedtext','local_assign'), new moodle_url('/local/assign/speedtext.php?cid='.$course_id.''));
$thingnode->make_active();*/

$PAGE->navbar->add($course->shortname, new moodle_url('/course/view.php', ['id'=>$course_id]));
$PAGE->navbar->add($title);
$PAGE->set_url('/local/assign/speedtext.php?cid='.$course_id);

echo $OUTPUT->header();
$actional_url = new moodle_url('/local/assign/speedtext.php?action=submit');
$mform = new speedtext_form($actional_url,['cid'=>$course_id,'userid'=>$USER->id]);

if(!empty($_REQUEST['comment_text'])){
    $data = $_REQUEST;
      //callobj::store_data($data);
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
            if(!empty($fordb->button_lable) && !empty($fordb->comment_text) && !$DB->record_exists('speed_text',['button_lable'=>$fordb->button_lable, 'user_id'=> $fordb->user_id, 'course_id'=> $fordb->course_id])){
                $newid = $DB->insert_record('speed_text', $fordb);
            }
        }
        redirect(new moodle_url('/local/assign/speedtext.php',array('cid'=>$data['cid'])));
    } else {
       $msg = 'yes';
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
    if (!in_array($sortby, array('name', 'status'))) {
        $sortby = 'name';
    }
    if ($sorthow != 'ASC' and $sorthow != 'DESC') {
            $sorthow = 'ASC';
    }

    if ($page < 0) {
            $page = 0;
    }

    //$tag_sql= $DB->get_records_sql("SELECT * FROM {speed_text} WHERE user_id='".$userId."' AND course_id = '".$course_id."' ORDER BY id");
    //$totalcount = count($tag_sql);
    $totalcount = $DB->count_records('speed_text', ['user_id'=>$userId, 'course_id' => $course_id]);
    if ($totalcount > 0) {
                
        //echo '<input class="form-submit" type="submit" name="add_submit" value="Map Selected Students">';
        echo $OUTPUT->heading(get_string('noofst', 'local_assign').$totalcount, 4);
        //Task #2062
        $view_per_page = '20';
        //$myurl= explode('smsg', $_SERVER['REQUEST_URI']);
        $htmlpagingbar = $OUTPUT->paging_bar($totalcount, $page, $view_per_page, $PAGE->url);

        $table = new html_table();
        $table->attributes['class'] = 'collection';
        $s_no = "S No.";
        
        $sortbyname = get_string('blabel','local_assign');
        $comment_text = get_string('comment', 'local_assign');
        // 'Action',
        $table->head = array(
                get_string('action'),
                $sortbyname,
                $comment_text
            );
        $table->colclasses = array('action_column','name');
        $i= $page * $view_per_page;
        //$tagview_sql= $DB->get_records_sql("SELECT * FROM {speed_text}  WHERE user_id ='".$userId."' AND course_id = '".$course_id."' ORDER BY id LIMIT $i, $view_per_page");
        $tagview_sql = $DB->get_records('speed_text', ['user_id'=>$userId,'course_id'=>$course_id], 'id ASC', '*', $i,$view_per_page);
        foreach ($tagview_sql as $b) {
            $i++;
            $name = $b->button_lable;
            $comment_text = $b->comment_text;
            $action_url = html_writer::link(new moodle_url('/local/assign/edit_stext.php', array('id'=>$b->id)), get_string('edit'));

            //$row = array($action_url,$name, $comment_text);
            $row = array($action_url, $name, $comment_text);
            $table->data[] = $row;
        }

       $htmltable = html_writer::table($table);
       
       echo '<span class="pull-right">'.$htmlpagingbar.'</span>' . $htmltable .'<span class="pull-right">'. $htmlpagingbar.'</span>';
    }
}
echo $OUTPUT->footer();