<?php
/**
 * AJAX_SCRIPT - exception will be converted into JSON
 */
define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../config.php');
global $DB, $OUTPUT, $CFG, $PAGE;
require_once($CFG->dirroot.'/mod/assign/grader_id_dropdown.php');
$params = $columns = $totalRecords = $data = array();

$params = $_REQUEST;

require_login();

$PAGE->set_context(context_system::instance());

$columns = array(
    0 => 'fullname',
    1 => 'u.institution',
    2 => 's.status',
    3 => 'course_name',
    4 => 'grade',
    5 => 'timesubmitted',
    6 => 'timesubmitted',
    7 => 'grade'
);

$where_condition = $sqlTot = $sqlRec = "";

if (!empty($params['search']['value'])) {
    $where_condition .= " AND ";
    $where_condition .= " ( CONCAT(u.firstname,' ',u.lastname) LIKE '%" . $params['search']['value'] . "%' ";
    $where_condition .= " OR u.institution LIKE '%" . $params['search']['value'] . "%' OR c.fullname LIKE '%" . $params['search']['value'] . "%' )";
}
$orderby = " ORDER BY ".$columns[$params['order'][0]['column']]." ".$params['order'][0]['dir'];

$sql_query = "SELECT  rand() as counter, u.id, IF(cc.timecreated > 0,cc.timecreated,s.timemodified) AS timesubmitted, cc.commentarea, u.picture, CONCAT(u.firstname,' ',u.lastname) as fullname,u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.institution, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
FROM {user} u
LEFT JOIN {assign_submission} s ON u.id = s.userid AND u.deleted = ?
LEFT JOIN {comments} cc ON cc.userid = s.userid AND cc.itemid = s.id
LEFT JOIN {course_modules} cm ON s.assignment = cm.instance AND cm.module = ?
LEFT JOIN {course} c ON c.id = cm.course
LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
WHERE u.id != ? AND u.id != ? AND s.status IS NOT NULL AND s.latest =? AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from {grade_items} gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";
$sqlTot .= $sql_query;
$sqlRec .= $sql_query;

$sqlTot = "SELECT COUNT(*) FROM {user} u
LEFT JOIN {assign_submission} s ON u.id = s.userid AND u.deleted != ?
LEFT JOIN {comments} cc ON cc.userid = s.userid AND cc.itemid = s.id
LEFT JOIN {course_modules} cm ON s.assignment = cm.instance AND cm.module = ?
LEFT JOIN {course} c ON c.id = cm.course
LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
WHERE u.id != ? AND u.id != ? AND s.status IS NOT NULL AND s.latest = ? AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from {grade_items} gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign'))";

if (isset($where_condition) && $where_condition != '') {

    $sqlTot .= $where_condition;
    $sqlRec .= $where_condition;
}
$sqlRec .= $orderby;

$totalRecords = $DB->count_records_sql($sqlTot, [1,1,5,2,1]);

$queryRecords = $DB->get_records_sql($sqlRec, [0,1,5,2,1], $params['start'], $params['length']);

foreach ($queryRecords as $queryRecord) {
    $context = context_module::instance($queryRecord->course_modules_id);
    //$assigncomment = $DB->get_records('comments', ['userid'=>$queryRecord->userid, 'contextid' => $context->id, 'itemid'=>$queryRecord->submissionid], 'timecreated DESC');
    //$assigncomment = reset($assigncomment);
    $assigncomment = !is_null($queryRecord->commentarea);
    $params = array('id' => $queryRecord->course_modules_id,'action'=>'grade','userid'=>$queryRecord->userid,'rownum'=>0);
    $gradetUrl = new moodle_url("/mod/assign/view.php", $params);

    $dropdownArray = grader_select($queryRecord->userid,$queryRecord->graderId,$queryRecord->course_modules_id,11,$context->id);

    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'assignsubmission_file', 'submission_files', $queryRecord->submissionid,'',false);
    $file = reset($files);
    $fileurl = '';
    if($file){
        $fileurl = moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename(),
        );
        $ext = pathinfo($file->get_filename(), PATHINFO_EXTENSION);

        if( $ext == 'pdf'){
            $pixurl = $OUTPUT->pix_url('pdfsmall', 'theme');

        }elseif( $ext == 'doc' || $ext == 'docx'){
            $pixurl = $OUTPUT->pix_url('word', 'theme');

        }elseif( $ext == 'xlsx' || $ext == 'csv'){
            $pixurl = $OUTPUT->pix_url('excel', 'theme');

        }elseif( $ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){
            $pixurl = $OUTPUT->pix_url('image', 'theme');

        }elseif( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
            $pixurl = $OUTPUT->pix_url('otherfile', 'theme');
        }
        $filename = html_writer::img($pixurl, 'fileicon').substr($file->get_filename(),0,10).'...';
    }
    $avatar = new user_picture($queryRecord);
    $avatar->courseid = $queryRecord->course_id;
    $avatar->link = true;
    $profileurl = new moodle_url('/user/view.php', array('id' => $queryRecord->id,'course'=>$queryRecord->course_id));
    $fullname = $OUTPUT->render($avatar).html_writer::link($profileurl, $queryRecord->fullname);


    if($assigncomment){
        $params = array('id' => $queryRecord->course_modules_id,'userid'=>$queryRecord->userid,'action'=>'comment','sesskey'=>'','page'=>'0');
        $commentUrl = new moodle_url("/local/assign/comment.php", $params);
        $data[] = [
            $fullname,
            $queryRecord->institution,
            get_string('newcomment', 'local_assign'),
            $queryRecord->course_name,
            html_writer::link($commentUrl, get_string('view', 'local_assign'), ['class'=>'btn btn-secondary act-btn']),
            userdate($queryRecord->timesubmitted),
            '',
            ''
        ];
    }
    else{
        $data[] = [
            $fullname,
            $queryRecord->institution,
            $OUTPUT->container(get_string('submissionstatus_' . $queryRecord->status, 'assign'),
                array('class'=>'submissionstatus' .$queryRecord->status)),
            $queryRecord->course_name,
            html_writer::link($gradetUrl, get_string('grade', 'local_assign'), ['class'=>'btn btn-secondary act-btn']),
            userdate($queryRecord->timesubmitted),
            $fileurl?html_writer::link($fileurl, $filename, ['title'=>$file->get_filename()]):'',
            $dropdownArray[0]
        ];
    }

}

$json_data = array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
);

echo json_encode($json_data);
