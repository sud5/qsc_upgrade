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
 * @package   ign local
 * @subpackage ass
 * @copyright  2022
 * @author     bk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$reset  = optional_param('reset', null, PARAM_BOOL);
require_login();
//require_once($CFG->dirroot . '/local/assign/filter_form.php');
//require_once($CFG->libdir . '/accesslib.php');
//require_once($CFG->libdir . '/tablelib.php');
//require_once($CFG->libdir . '/gradelib.php');
//require_once($CFG->libdir . '/portfoliolib.php');
//require_once($CFG->libdir . '/modinfolib.php');
//require_once($CFG->libdir . '/datalib.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->dirroot . '/mod/assign/gradingtable.php');
require_once($CFG->dirroot . '/mod/assign/grader_id_dropdown.php');
//For live training
//require_once($CFG->dirroot . '/mod/facetoface/lib.php');
//lakhan-start -- modify by shiva
// Start update - dashboard_admin_grader - prevv
if ($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin') {
    redirect(new moodle_url('/local/assign/dashboard_grader.php'));
} elseif ($USER->usertype == 'mainadmin' || is_siteadmin($USER)) {
    // Do Nothing
} else {
    redirect(new moodle_url('/login/index.php?id=admin'));
}
// End update - dashboard_admin_grader - prev
//
//$PAGE->requires->jquery();
//$PAGE->requires->jquery('ui');
//$PAGE->requires->js('/local/assign/js/select2.min.js', true);
//$PAGE->requires->css('/local/assign/js/select2.min.css', true);

require_once($CFG->dirroot . '/my/lib.php');

//redirect_if_major_upgrade_required();

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
$PAGE->set_url('/local/assign/dashboard_admin_grader_new.php', $params);
$PAGE->set_pagelayout('mydashboard');
//$PAGE->set_pagetype('assign-index');
$PAGE->set_title('Dashboard');
$PAGE->set_heading($header);
$PAGE->requires->css('/local/assign/js/datatable.min.css');
$PAGE->requires->js('/local/assign/js/datatable.js',true);
$PAGE->requires->js('/local/assign/js/dtable.js');
$PAGE->requires->css('/local/assign/styles.css');
$PAGE->navbar->add('');
//$PAGE->requires->css(new moodle_url('https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css'));
//$PAGE->requires->js(new moodle_url('https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'), true);


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
    $reseturl = new moodle_url("/my/index.php", array('edit' => 1, 'reset' => 1));

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
                    where fsd.fieldid=" . $fieldid . " AND fsd.data LIKE '%" . $USER->username . "%' order by fs.timemodified desc";
    $rsSessData = $DB->get_records_sql($sqlSessData);
}

if ($rsSessData) {
    $_SESSION['instructorrole_breadcrumb_flag'] = 1;
} else {
    $_SESSION['instructorrole_breadcrumb_flag'] = 0;
}
//Grader as a Manager Code End

echo $OUTPUT->header();

if (!isguestuser()) {
    $enrolledCourses = enrol_get_my_courses();
    $obj = new local_assign\locallib();
    echo $obj->load_assign_welcome_view($rsSessData);
    $url = new moodle_url('/my');


//lakhan-end
    ?>
    <!-- Notification area for grader dashboard -->
    <div class="certification_in_progress notification-panel">
        <h4>Notifications</h4>
        <div class="table-view tabel_base_cls">
            <table id='studentAdminDataTable' class="table-bordered graderpg_cls gradenew_cls">
                <thead>
                <tr>
                    <th id="col0"><?php echo get_string('grader_studentname', 'local_assign'); ?></th>
                    <th id="col1" width="150"><?php echo get_string('company', 'local_assign'); ?></th>
                    <th id="col2"><?php echo get_string('grader_status', 'local_assign'); ?></th>
                    <th id="col3"><?php echo get_string('grader_coursename', 'local_assign'); ?></th>
                    <th id="col4"><?php echo get_string('grader_grade_view', 'local_assign'); ?></th>
                    <th id="col5" class="sorting"><?php echo get_string('grader_last_modified', 'local_assign'); ?></th>
                    <th id="col6"><?php echo get_string('grader_file_submissions', 'local_assign'); ?></th>
                    <th id="col7" class="sorting"><?php echo get_string('grader_grader', 'local_assign'); ?></th>

                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <?php
    //echo $obj->filter();
    echo html_writer::script('$("#fgroup_id_buttonar").hide();');


} else {
    redirect("$CFG->wwwroot/");
}

//echo $OUTPUT->custom_block_region('content');
echo $OUTPUT->footer();
?>

    <div class="overlay" style="display: none">

        <div id="loading-img"></div>
    </div>
    <style type="text/css">
        #loading-img {
            background: url(https://devtraining.qsc.com/mod/assign/4V0b.gif) center center no-repeat;
            height: 100%;
            z-index: 200;
        }

        .overlay {
            background: #e9e9e9;
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            opacity: 0.5;
            height: 100%;
            width: 100%;
        }
    </style>
    <?php


//echo html_writer::script('function grader_dropdown(obj) {
//                            var graderVal = obj.value;
//                             //console.log(graderVal);
//                            $.ajax({
//                                 url: "' . $CFG->wwwroot . '/local/assign/process.php?action=graderdropdown",
//                                 type: "post",
//                                 data: {grader_val: graderVal},
//                                 success: function(response) {
//                                    console.log(response);
//                                 }
//                            });
//
//                            var ddClass = $(this).parent().attr("class");
//                            var rowArray = ddClass.split("td_dropdown_");
//                            if( $.trim($(this).find("option:selected").text()) == "Administrator"){
//                                $("#button_"+rowArray[1]).attr("class","enable_link");
//                            }else{
//                                $("#button_"+rowArray[1]).attr("class","disable_link");
//                            }
//
//                        }
//                        $(".ftoggler").click(function() {
//                            //$("#fgroup_id_buttonar").show();
//                              var value = $(this).find("a[aria-expanded]").attr("aria-expanded");
//                             // alert(value);
//                              if (value == "true") {
//                                  $("#fgroup_id_buttonar").hide();
//                              } else {
//                                  $("#fgroup_id_buttonar").show();
//                              }
//                        });');


echo '<style>
th#col4 {
    background-image: none !important;
    cursor:pointer !important;
}
th#col8 {
    background-image: none !important;
    cursor:pointer !important;
}
.studentAdminDataTable{
    display: block;
    max-width: 100%;
    overflow: auto;
    float: left;
    margin-top: 10px;
}
</style>';
