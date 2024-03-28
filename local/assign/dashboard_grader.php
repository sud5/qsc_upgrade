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
 * My Moodle -- a user's personal dashboard
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - only the user can see their own dashboard
 * - users can add any blocks they want
 * - the administrators can define a default site dashboard for users who have
 *   not created their own dashboard
 *
 * This script implements the user's view of the dashboard, and allows editing
 * of the dashboard.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

//require_once($CFG->libdir.'/tablelib.php');
//require_once($CFG->libdir.'/gradelib.php');
//require_once($CFG->libdir.'/portfoliolib.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');


//lakhan-start
// $sqlRole = "SELECT id FROM {role} WHERE shortname='grader'";
// $rsRole = $DB->get_record_sql($sqlRole);

// $sqlRoleAssignment = "SELECT contextid FROM {role_assignments} where userid=".$USER->id." AND roleid=".$rsRole->id;
// $rsRoleAssignment = $DB->get_record_sql($sqlRoleAssignment);

// $authFlag = 0;
if ($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin') {
    // Do Nothing
} elseif ($USER->usertype == 'mainadmin') {
    redirect(new moodle_url('/local/assign/dashboard_admin_grader_new.php'));
} else {
    redirect(new moodle_url('/login/index.php?id=admin'));
}


// $assignGradingTableObj = new assign_grading_table();
// print_r($assignGradingTableObj);
// die;

//Customization for existing/new users first time redirection start

// if(empty($_SESSION['auth_existings'])){
//     $sql61 = "SELECT lastlogin FROM {user} where id=".$USER->id;
//     $rs61 = $DB->get_record_sql($sql61);
// //echo "<pre>"; print_r($_SESSION['auth_existings']); exit;
//     if($rs61->lastlogin == 0){   
//  redirect(new moodle_url($CFG->wwwroot.'/user/edit.php'));
//     }
// }
//Customization for existing/new users first time redirection end

require_once($CFG->dirroot . '/my/lib.php');

redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$reset = optional_param('reset', null, PARAM_BOOL);

require_login();


$hassiteconfig = has_capability('moodle/site:config', context_system::instance());
if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect(new moodle_url('/admin/index.php'));
}

$strmymoodle = get_string('myhome');

if (isguestuser()) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = null;
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :)
    $header = "$SITE->shortname: $strmymoodle (GUEST)";
    $pagetitle = $header;

} else {        // We are trying to view or edit our own My Moodle page
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $header = fullname($USER);
    $pagetitle = $strmymoodle;
}

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

// Start setting up the page
$params = array();
$PAGE->set_context($context);
$PAGE->set_url('/my/index.php', $params);
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_pagetype('my-index');
$PAGE->requires->css(new moodle_url('https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css'));
$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'), true);
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);

if (!isguestuser()) {   // Skip default home page for guests
    if (get_home_page() != HOMEPAGE_MY) {
        if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
            set_user_preference('user_home_page_preference', HOMEPAGE_MY);
        } else if (!empty($CFG->defaulthomepage) && $CFG->defaulthomepage == HOMEPAGE_USER) {
            $frontpagenode = $PAGE->settingsnav->add(get_string('frontpagesettings'), null, navigation_node::TYPE_SETTING, null);
            $frontpagenode->force_open();
            $frontpagenode->add(get_string('makethismyhome'), new moodle_url('/my/', array('setdefaulthome' => true)),
                navigation_node::TYPE_SETTING);
        }
    }
}


// Toggle the editing state and switches
if (empty($CFG->forcedefaultmymoodle) && $PAGE->user_allowed_editing()) {
    if ($reset !== null) {
        if (!is_null($userid)) {
            require_sesskey();
            if (!$currentpage = my_reset_page($userid, MY_PAGE_PRIVATE)) {
                print_error('reseterror');
            }
            redirect(new moodle_url('/my'));
        }
    } else if ($edit !== null) {             // Editing state was specified
        $USER->editing = $edit;       // Change editing state
    } else {                          // Editing state is in session
        if ($currentpage->userid) {   // It's a page we can edit, so load from session
            if (!empty($USER->editing)) {
                $edit = 1;
            } else {
                $edit = 0;
            }
        } else {
            // For the page to display properly with the user context header the page blocks need to
            // be copied over to the user context.
            if (!$currentpage = my_copy_page($USER->id, MY_PAGE_PRIVATE)) {
                print_error('mymoodlesetup');
            }
            $context = context_user::instance($USER->id);
            $PAGE->set_context($context);
            $PAGE->set_subpage($currentpage->id);
            // It's a system page and they are not allowed to edit system pages
            $USER->editing = $edit = 0;          // Disable editing completely, just to be safe
        }
    }

    // Add button for editing page
    $params = array('edit' => !$edit);

    $resetbutton = '';
    $resetstring = get_string('resetpage');
    $reseturl = new moodle_url("$CFG->wwwroot/my/index.php", array('edit' => 1, 'reset' => 1));

    if (!$currentpage->userid) {
        // viewing a system page -- let the user customise it
        $editstring = get_string('updatemymoodleon');
        $params['edit'] = 1;
    } else if (empty($edit)) {
        $editstring = get_string('updatemymoodleon');
    } else {
        $editstring = get_string('updatemymoodleoff');
        $resetbutton = $OUTPUT->single_button($reseturl, $resetstring);
    }

    $url = new moodle_url("/my/index.php", $params);
    $button = $OUTPUT->single_button($url, $editstring);
    $PAGE->set_button($resetbutton . $button);

} else {
    $USER->editing = $edit = 0;
}

//Grader as a Manager Code Start
$fieldid = $DB->get_field('facetoface_session_field', 'id', array('shortname' => 'managersemail'));
if ($fieldid) {
    $sqlSessData = "SELECT f.id as facetofaceid, fsd.id, f.name FROM {facetoface_session_data} fsd 
                    JOIN {facetoface_sessions} fs ON fsd.sessionid = fs.id 
                    JOIN {facetoface} f ON f.id = fs.facetoface
                    where fsd.fieldid= ? AND {$DB->sql_like('fsd.data','?')} order by fs.timemodified desc";

    $rsSessData = $DB->get_records_sql($sqlSessData, [$fieldid,'%'.$USER->username.'%']);
}

if ($rsSessData)
    $_SESSION['instructorrole_breadcrumb_flag'] = 1;
else
    $_SESSION['instructorrole_breadcrumb_flag'] = 0;
//Grader as a Manager Code End


echo $OUTPUT->header();


// $isadmin = is_siteadmin($USER);
// if($isadmin){
// echo $OUTPUT->custom_block_region('content');
// }
//else
if (!isguestuser()) {
//echo $OUTPUT->custom_block_region();
$enrolledCourses = enrol_get_my_courses();
$userprofileurl = new moodle_url('/user/profile.php', ['id' => $USER->id]);
$gradereporturl = new moodle_url('/grade/report/overview/index.php');
//echo "<pre>";print_r($enrolledCourses); exit;
?>

<!--Dashboard design start-->
<div class="newdashboard_design">
    <div class="newdashboard_welcome_section">
        <div class="profile_img_section"> <?php echo $OUTPUT->user_picture($USER, array('size' => 100, 'class' => 'welcome_userpicture')); ?></div>
        <div class="profile_details_section">

            <h3><?= get_string('welcomeback', 'local_assign', $USER->firstname); ?></h3>
            <!-- Grader as a Manager Code Start -->
            <div class="three_tabs_newdashboard">
                <ul>
                    <li><a href="<?= $userprofileurl ?>"
                           title="<?= get_string('user'); ?> <?= get_string('profile'); ?>"><?= get_string('user'); ?> <?= get_string('profile'); ?></a>
                        <span>|</span></li>
                    <li><a href="<?= $gradereporturl ?>"
                           title="<?= get_string('grades'); ?>"><?= get_string('grades'); ?></a> <span>|</span></li>
                    <?php
                    //59 Est Instructor Feature start
                    //change-2 start
                    if ($rsSessData) {
                        $slashCnt = 0;
                        foreach ($rsSessData as $rsSessDataKey) {
                            if ($rsSessDataKey) {
                                $f2furl = new moodle_url('/mod/facetoface/view.php', ['f' => $rsSessDataKey->facetofaceid, 'crole' => 'instructor']);
                                ?>
                                <li>
                                <a href="<?= $f2furl ?>"
                                   title="<?= get_string('classroom_enrollments', 'local_assign'); ?>"><?php echo format_string($rsSessDataKey->name); ?>
                                    Enrollments</a>
                                <?php
                                $slashCnt++;
                                if (count($rsSessData) > $slashCnt) echo "<span>|</span></li>";
                                else echo "</li>";
                            }
                        }
                    }//end if $rsSeesData
                    else {
                        $preferenceurl = new moodle_url('/user/preferences.php');
                        ?>
                        <li><a href="<?= $preferenceurl ?>"
                               title="<?= get_string('preferences'); ?>"><?= get_string('preferences'); ?></a></li>
                    <?php }
                    //change-2 end
                    //59 Est Instructor Feature end
                    ?>
                </ul>
            </div>
            <!-- Grader as a Manager Code End -->
        </div>

    </div>


    <!-- Notification area for grader dashboard -->
    <?php
    //lakhan-start


    /*$sqlUser_AssignGrader_AssignSubmission = "SELECT gr.id as agid,u.id,gr.grader_id,u.picture,u.firstname,u.lastname,u.firstnamephonetic,u.lastnamephonetic,u.middlename,u.alternatename,u.imagealt,u.email, u.id as userid, gr.exam_id as course_modules_id, cm.course as course_id, cm.instance as assignmentid, c.fullname as course_name, s.status as status, s.id as assign_submission_id, s.timecreated as firstsubmission, s.timemodified as timesubmitted, s.attemptnumber as attemptnumber, g.id as gradeid, g.grade as grade, g.timemodified as timemarked, g.timecreated as firstmarked
   FROM mdl_assign_graders gr RIGHT JOIN mdl_user u ON u.id = gr.student_id
    LEFT JOIN mdl_course_modules cm ON gr.exam_id = cm.id
    LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND s.latest = 1 AND s.assignment = cm.instance
    LEFT JOIN mdl_course c ON c.id = cm.course
    LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.id is null
    WHERE gr.grader_id=".$USER->id." ORDER BY g.timemodified DESC";*/


    $sqlUser_AssignGrader_AssignSubmission = "SELECT gr.id as agid,u.id,gr.grader_id,u.picture,u.firstname,u.lastname,u.firstnamephonetic,u.lastnamephonetic,u.institution, u.middlename,u.alternatename,u.imagealt,u.email, u.id as userid, gr.exam_id as course_modules_id, cm.course as course_id, cm.instance as assignmentid, c.fullname as course_name, c.visible AS course_visible, s.status as status, s.id as assign_submission_id, s.timecreated as firstsubmission, s.timemodified as timesubmitted, s.attemptnumber as attemptnumber, g.id as gradeid, g.grade as grade, g.timemodified as timemarked, g.timecreated as firstmarked 
                                            FROM {assign_graders} gr 
                                            RIGHT JOIN {user} u ON u.id = gr.student_id AND u.deleted != ? 
                                            LEFT JOIN {course_modules} cm ON gr.exam_id = cm.id AND cm.module = ? 
                                            LEFT JOIN {assign_submission} s ON u.id = s.userid AND s.assignment = cm.instance 
                                            LEFT JOIN {course} c ON c.id = cm.course 
                                            LEFT JOIN {assign_grades} g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber 
                                            WHERE s.status IS NOT NULL AND s.latest = ? AND s.status != ? AND gr.grader_id=? AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from {grade_items} gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ORDER BY s.timemodified DESC";


    //echo $sqlUser_AssignGrader_AssignSubmission;

    //AND g.id IS NULL
    //AND uf.locked IS NULL

    //echo $sqlUser_AssignGrader_AssignSubmission; die;
    $rsStudentRecordArray = $DB->get_records_sql($sqlUser_AssignGrader_AssignSubmission, [1, 1, 1, 'new', $USER->id]);


    /* echo "<pre>";
     print_r($rsStudentRecordArray);
     exit("Success");*/

    $rsStudentRecordCommentObj = (object)$rsStudentRecordArray;

    $rsStudentRecordArray = (array)$rsStudentRecordArray;
    $courseIdArray = [];
    $courseModulesIdArray = [];
    //lakhan-end
    ?>
    <!-- Notification area for grader dashboard -->
    <div class="certification_in_progress notification-panel">
        <h4>Notifications</h4>
        <style type="text/css">.row-fluid .span9 {
                width: 100% !important;
            }</style>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#studentDataTable').DataTable({
                    "pageLength": 15,
                    "lengthMenu": [[3, 15, 25, 50, -1], [3, 15, 25, 50, "All"]],
                    "aoColumnDefs": [
                        {"iDataSort": 6, "aTargets": [5]}
                    ],
                    "columns": [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        {"bVisible": false, "searchable": false},
                        null,
                    ],
                    "order": [[6, 'desc']],
                    "search": {
                        "smart": true,
                    }
                });
                //var table = $('#studentDataTable').DataTable();
                //table.order( [ 5, 'desc' ] ).draw();
                //table.column(5).data().sort();
                //table.column(5).data().sort();
            });
        </script>
        <div class="table-view tabel_base_cls">
            <table id='studentDataTable' class="table-bordered graderpg_cls">
                <thead>
                <tr>
                    <th id="col0"><?php echo get_string('grader_studentname', 'local_assign'); ?></th>
                    <th id="col1" width="150"><?php echo get_string('company', 'local_assign'); ?></th>
                    <th id="col2"><?php echo get_string('grader_status', 'local_assign'); ?></th>
                    <th id="col3"><?php echo get_string('grader_coursename', 'local_assign'); ?></th>
                    <th id="col4"><?php echo get_string('grader_grade_view', 'local_assign'); ?></th>
                    <th id="col5" class="sorting"><?php echo get_string('grader_last_modified', 'local_assign'); ?></th>
                    <th id="col6" class="date-time-col-hide"></th>
                    <th id="col7"><?php echo get_string('grader_file_submissions', 'local_assign'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (count((array)$rsStudentRecordArray) > 0) {
                    foreach ($rsStudentRecordArray as $rsStudentRecord) {

                        $coursecontext = context_course::instance($rsStudentRecord->course_id);
                        if (is_enrolled($coursecontext, $rsStudentRecord->id, '', false)) {

                            $courseIdArray[$rsStudentRecord->course_id] = $rsStudentRecord->course_name;

                            //$courseModulesIdArray[$rsStudentRecord->course_modules_id] = $rsStudentRecord->course_modules_id;

                            // echo format_row($rsStudentRecord);
                            // $gradingtableObj = new assign_grading_table($this);
                            // print_r($gradingtableObj);
                            // print_r($gradingtableObj->perpage); die;

                            // $studentData = $gradingtableObj->col_picture($rsStudentRecord);
                            // print_r($studentData); die;
                            ?>
                            <tr>

                                <td class="user-profile">
                           <span>
                                <!-- <a href="<?php //echo $CFG->wwwroot.'/user/profile.php?id='.$rsStudentRecord->id.'&amp;course='.$rsStudentRecord->course_id
                                ?>"> -->
                                <?php
                                $avatar = new user_picture($rsStudentRecord);
                                $avatar->courseid = $rsStudentRecord->course_id;
                                $avatar->link = true;
                                echo $OUTPUT->render($avatar);
                                ?>
                                <!-- </a> -->
                            </span>
                                    <span>
                                    <?php
                                    $name = $rsStudentRecord->firstname . " " . $rsStudentRecord->lastname;
                                    $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentRecord->id, 'course' => $rsStudentRecord->course_id)); // $COURSE->id
                                    echo " " . html_writer::link($profileurl, $name);
                                    //echo "<a href='javascript:void(0)'>".format_string($name)."</a>";
                                    ?>
                            </span>
                                </td>
                                <td>
                            <span> <?php echo $rsStudentRecord->institution; ?>
                            </span>
                                </td>
                                <td class="grading-comment">
                            <span> <?php echo $OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecord->status, 'assign'),
                                    array('class' => 'submissionstatus' . $rsStudentRecord->status)); ?></span>
                                </td>
                                <td class="actn-btns">
                            <span> 
                                <?php
                                if ($rsStudentRecord->course_visible) {
                                    echo format_string($rsStudentRecord->course_name);
                                } else {
                                    echo "<span style='text-decoration: none;'>" . format_string($rsStudentRecord->course_name) . "</span>";
                                }
                                ?>
                            </span>
                                </td>
                                <td class="actn-btns">
                                    <?php
                                    $context = context_module::instance($rsStudentRecord->course_modules_id);
                                    $params = array('id' => $rsStudentRecord->course_modules_id, 'action' => 'grade', 'userid' => $rsStudentRecord->userid, 'rownum' => 0);
                                    $gradetUrl = new moodle_url("$CFG->wwwroot/mod/assign/view.php", $params);
                                    ?>
                                    <a href="<?php echo $gradetUrl; ?>">Grade</a>
                                </td>
                                <td class="date-time">
                                    <span> <?php echo userdate($rsStudentRecord->timesubmitted); ?></span>
                                </td>
                                <td class="date-time-col-hide">
                            <span> <?php
                                //echo $rsStudentRecord->userid." - ".$rsStudentRecord->course_modules_id;
                                // $contextNew = context_module::instance($rsStudentRecord->course_modules_id);

                                // $gQuery = "SELECT grader_id, timeassigned FROM {assign_graders} WHERE exam_id = $rsStudentRecord->course_modules_id AND student_id = $rsStudentRecord->userid";
                                // $graderObjData = $DB->get_record_sql($gQuery);

                                // if($graderObjData->timeassigned > $rsStudentRecord->timesubmitted){
                                //     echo date("Ymd",$graderObjData->timeassigned);
                                // }else{
                                //echo date("Ymd",$rsStudentRecord->timesubmitted);
                                //}
                                echo $rsStudentRecord->timesubmitted;
                                ?></span>

                                </td>
                                <td class="files">
                                    <?php
                                    require_once($CFG->dirroot . '/mod/assign/submission/file/locallib.php');
                                    global $DB;

                                    //print_r($this->context = context_module Object ( [_id:protected] => 715 [_contextlevel:protected] => 70 [_instanceid:protected] => 251 [_path:protected] => /1/3/406/715 [_depth:protected] => 4 )) ;
                                    $component = 'assignsubmission_file';
                                    $filearea = 'submission_files';
                                    //$submissionid = $rsStudentRecord->assign_submission_id;

                                    //if($rsStudentRecord->status == 'reopened'){

                                    $sqlAssigSubQuery = "SELECT id,status, timemodified FROM {assign_submission} WHERE assignment = ? AND userid = ? AND status = ? ORDER BY timemodified DESC LIMIT 0,1";
                                    $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery, [$rsStudentRecord->assignmentid, $rsStudentRecord->userid, 'submitted']);

                                    //}
                                    // this is other assign submission id that is comming from main query
                                    $submissionid = $sqlAssigSubData->id;

                                    //$submissionid = 4090; //104
                                    $context = context_module::instance($rsStudentRecord->course_modules_id);

                                    $fs = get_file_storage();
                                    $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);
                                    //$files = $fs->get_area_files($context->id, $component, $filearea, $submissionid,'timemodified', false);

                                    foreach ($dir['files'] as $file) {

                                        $file->portfoliobutton = '';

                                        $filename = $file->get_filename();
                                        $url = "";
                                        $ext = pathinfo($filename, PATHINFO_EXTENSION);

                                        if ($ext == 'pdf') {
                                            $url = $OUTPUT->pix_url('pdfsmall', 'theme');

                                        } elseif ($ext == 'doc' || $ext == 'docx') {
                                            $url = $OUTPUT->pix_url('word', 'theme');

                                        } elseif ($ext == 'xlsx' || $ext == 'csv') {
                                            $url = $OUTPUT->pix_url('excel', 'theme');

                                        } elseif ($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg') {
                                            $url = $OUTPUT->pix_url('image', 'theme');

                                        } elseif ($ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php' || $ext == 'js') {
                                            $url = $OUTPUT->pix_url('otherfile', 'theme');
                                        }
                                        echo "<img class='icon' alt='" . $filename . "' title='" . $filename . "' src=" . $url . ">";


                                        $path = '/' .
                                            $context->id .
                                            '/' .
                                            $component .
                                            '/' .
                                            $filearea .
                                            '/' .
                                            $file->get_itemid() .
                                            $file->get_filepath() .
                                            $file->get_filename();
                                        $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
                                        //$filename = $file->get_filename();
                                        echo html_writer::link($url, substr($filename, 0, 10) . '...', array('title' => $filename));
                                        echo "<br>";
                                    }

                                    //print assign::render_area_files_custom($component, $filearea, $submissionid, $context);
                                    //print_r($tempFiles);
                                    ?>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                    $rsStudentRecordCommentArray = (array)$rsStudentRecordCommentObj;

                    foreach ($rsStudentRecordCommentArray as $rsStudentCommentRecord) {

                        $coursecontextsecond = context_course::instance($rsStudentCommentRecord->course_id);
                        if (is_enrolled($coursecontextsecond, $rsStudentCommentRecord->userid, '', false)) {

                            $context = context_module::instance($rsStudentCommentRecord->course_modules_id);

                            //OLD - ALWAYS display latest comment sent by student (if exists)
                            //$sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE mc.userid=".$rsStudentCommentRecord->userid." AND mc.contextid=".$context->id." ORDER BY mc.timecreated DESC LIMIT 0,1";


                            //NEW - fetch latest comment either from student or from grader (if exists)

                            //Changes in Exam Comments
                            // $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE (mc.userid=".$rsStudentCommentRecord->userid." OR mc.userid=".$USER->id.") AND mc.contextid=".$context->id." AND mc.itemid=".$rsStudentCommentRecord->assign_submission_id." ORDER BY mc.timecreated DESC LIMIT 0,1";

                            // $rsComments = $DB->get_record_sql($sqlAssignSubmission);

                            $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated 
                                                    FROM {comments} mc 
                                                    WHERE mc.userid =? AND mc.contextid = ? ORDER BY mc.timecreated DESC LIMIT 0,1";

                            $rsComments = $DB->get_record_sql($sqlAssignSubmission, [$rsStudentCommentRecord->userid, $context->id]);


                            if (($rsComments->id > 0) && ($rsComments->userid != $USER->id)) {
                                ?>
                                </tr>
                                <!-- For new comments -->

                                <td class="user-profile">
                             <span>
                                <!-- <a href="<?php //echo $CFG->wwwroot.'/user/profile.php?id='.$rsStudentRecord->id.'&amp;course='.$rsStudentRecord->course_id
                                ?>"> -->
                                <?php
                                $avatar = new user_picture($rsStudentCommentRecord);
                                $avatar->courseid = $rsStudentCommentRecord->course_id;
                                $avatar->link = true;
                                echo $OUTPUT->render($avatar);
                                ?>
                                <!-- </a> -->
                            </span>
                                    <span>
                                    <?php
                                    $name = $rsStudentCommentRecord->firstname . " " . $rsStudentCommentRecord->lastname;
                                    $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentCommentRecord->id, 'course' => $rsStudentCommentRecord->course_id)); // $COURSE->id
                                    echo " " . html_writer::link($profileurl, $name);
                                    //echo "<a href='javascript:void(0)'>".format_string($name)."</a>";
                                    ?>
                            </span>
                                </td>
                                <td>
                            <span> <?php echo $rsStudentCommentRecord->institution; ?>
                            </span>
                                </td>
                                <td class="grading-comment">
                            <span> 
                                <?php
                                // Note: records are comming with assign_submission_id OR itemid are belongs to the submissions having enclosed files;
                                echo format_string("New Comment");
                                ?>
                            </span>
                                </td>
                                <td class="actn-btns">
                            <span> 
                                <?php
                                if ($rsStudentCommentRecord->course_visible) {
                                    echo format_string($rsStudentCommentRecord->course_name);
                                } else {
                                    echo "<span style='text-decoration: none;'>" . format_string($rsStudentCommentRecord->course_name) . "</span>";
                                }
                                ?>
                            </span>
                                </td>
                                <td class="actn-btns">
                                    <?php
                                    $context = context_module::instance($rsStudentCommentRecord->course_modules_id);
                                    $params = array('id' => $rsStudentCommentRecord->course_modules_id, 'userid' => $rsStudentCommentRecord->userid, 'action' => 'comment', 'sesskey' => '', 'page' => '0');
                                    $commentUrl = new moodle_url("/local/assign/comment.php", $params);
                                    ?>
                                    <a href="<?php echo $commentUrl; ?>">View</a>
                                </td>
                                <td class="date-time">
                                    <span> <?php echo userdate($rsComments->timecreated); ?></span>
                                </td>
                                <td class="date-time-col-hide">
                                    <span> <?php echo $rsComments->timecreated; ?></span>
                                </td>

                                <td class="actn-btns">
                                </td>
                                </tr>
                                <?php
                            } //End - if

                        }   //End - foreach
                    }
                } else {
                    echo "<h4> No Notification </h4>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


    <?php
    //For live training
    require_once($CFG->dirroot . '/mod/facetoface/lib.php');
    ?>

    <!--Dashboard design end-->
    <?php } else {
        redirect("$CFG->wwwroot/");
    } ?>
    <?php //}//end else if design is static
    if ($_GET["design"] == 'menu') {
        ?>
        /***************/
        For Navigation Work
        /****************/
    <?php }
    echo $OUTPUT->custom_block_region('content');
    echo $OUTPUT->footer();

    ?>
    <script>
        var isAdmin = "<?=$isadmin?>";
        if (isAdmin == "") {
            $(document).ready(function () {
                //alert("test");
                $('#block-region-content').hide();
            });


            $(window).load(function () {
                $('#block-region-content').remove();
            });
        }


        // lakhan - start custom
        $("#select_course").change(function () {
            var courseId = this.value;

            if (courseId != '') {
                $.ajax({
                    url: '/mod/assign/get_course_assignment.php',
                    type: 'post',
                    data: {'courseId': courseId},
                    success: function (response) {
                        if (response != '0') {
                            console.log(response);
                            var optionsVal = "<option selected='selected' value=''>Please select exam</option>";
                            $("#select_exam").html("");
                            $("#select_exam").append(optionsVal + response);
                            //$("#select_exam").append(response);
                        }
                    }
                });
            } else {
                $("#select_exam").html("<option value=''>Please select exam</option>");
            }
        });

        $("#actionmenuaction-3").parent().parent().hide();

        $("#select_exam").change(function () {
            var courseModuleId = this.value;

            if (courseModuleId != '') {
                var gradingPanelUrl = $("#gradingPanelUrl").val() + "?id=" + courseModuleId + "&action=grading";
                $("#linkToGradingPanel").attr('href', gradingPanelUrl)
            } else {
                //$("#select_exam").html("<option value=''>Please select exam</option>");
            }
        });

        $(document).on("click", "#col4", function (e) {
            e.preventDefault();
            $("#col5").click();
        });


        // lakhan - end custom

    </script>