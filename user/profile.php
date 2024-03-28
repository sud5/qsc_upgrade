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
 * Public Profile -- a user's public profile page
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - users can add any blocks they want
 * - the administrators can define a default site public profile for users who have
 *   not created their own public profile
 *
 * This script implements the user's view of the public profile, and allows editing
 * of the public profile.
 *
 * @package    core_user
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot . '/my/lib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->libdir.'/filelib.php');
//require_once($CFG->libdir . '/sfdclib.php');

$userid         = optional_param('id', 0, PARAM_INT);
$edit           = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off.
$reset          = optional_param('reset', null, PARAM_BOOL);

$PAGE->set_url('/user/profile.php', array('id' => $userid));
//
$PAGE->requires->css('/user/custom.css',true);

if($USER->usertype != "mainadmin" && $USER->usertype != "admin" && $USER->usertype != "graderasadmin" && $_SESSION['crole'] != 'instructor'){
    if(!empty($_REQUEST['id'])){
      //update_certificate_details($_REQUEST['id'],'profile');
    }
}else{
     if(!empty($_REQUEST['id'])){
     // update_certificate_details_profile($_REQUEST['id'],'profile');
        //echo "test please ignore";
//         update_certificate_details($_REQUEST['id'],'profile');
      }

}


if (!empty($CFG->forceloginforprofiles)) {
    require_login();
    if (isguestuser()) {
        $PAGE->set_context(context_system::instance());
        echo $OUTPUT->header();
        echo $OUTPUT->confirm(get_string('guestcantaccessprofiles', 'error'),
                              get_login_url(),
                              $CFG->wwwroot);
        echo $OUTPUT->footer();
        die;
    }
} else if (!empty($CFG->forcelogin)) {
    require_login();
}
//4635 add crole cond
if($USER->usertype != "mainadmin" && $USER->usertype != "admin" && $USER->usertype != "graderasadmin" && $_SESSION['crole'] != 'instructor'){
    // if($userid != $USER->id)
    // {
    //    redirect(new moodle_url('/my')); 
    // }
}
$userid = $userid ? $userid : $USER->id;       // Owner of the page.
if ((!$user = $DB->get_record('user', array('id' => $userid))) || ($user->deleted)) {
    $PAGE->set_context(context_system::instance());
    echo $OUTPUT->header();
    if (!$user) {
        echo $OUTPUT->notification(get_string('invaliduser', 'error'));
    } else {
        echo $OUTPUT->notification(get_string('userdeleted'));
    }
    echo $OUTPUT->footer();
    die;
}

$currentuser = ($user->id == $USER->id);
$context = $usercontext = context_user::instance($userid, MUST_EXIST);
//4635 add crole cond
$crole = $_SESSION['crole'];
if (!user_can_view_profile($user, null, $context) && $_SESSION['crole'] != 'instructor') {

    // Course managers can be browsed at site level. If not forceloginforprofiles, allow access (bug #4366).
    $struser = get_string('user');
    $PAGE->set_context(context_system::instance());
    $PAGE->set_title("$SITE->shortname: $struser");  // Do not leak the name.
    $PAGE->set_heading($struser);
    $PAGE->set_pagelayout('mypublic');
    $PAGE->set_url('/user/profile.php', array('id' => $userid));
    $PAGE->navbar->add($struser);
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('usernotavailable', 'error'));
    echo $OUTPUT->footer();
    exit;
}

// Get the profile page.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PUBLIC)) {
    print_error('mymoodlesetup');
}

$PAGE->set_context($context);
$PAGE->set_pagelayout('mypublic');
$PAGE->set_pagetype('user-profile');

// Set up block editing capabilities.
if (isguestuser()) {     // Guests can never edit their profile.
    $USER->editing = $edit = 0;  // Just in case.
    $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :).
} else {
    if ($currentuser) {
        $PAGE->set_blocks_editing_capability('moodle/user:manageownblocks');
    } else {
        $PAGE->set_blocks_editing_capability('moodle/user:manageblocks');
    }
}

// Start setting up the page.
$strpublicprofile = get_string('publicprofile');

$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title(fullname($user).": $strpublicprofile");
$PAGE->set_heading(fullname($user));

if (!$currentuser) {
    $PAGE->navigation->extend_for_user($user);
    if ($node = $PAGE->settingsnav->get('userviewingsettings'.$user->id)) {
        $node->forceopen = true;
    }
} else if ($node = $PAGE->settingsnav->get('dashboard', navigation_node::TYPE_CONTAINER)) {
    $node->forceopen = true;
}
if ($node = $PAGE->settingsnav->get('root')) {
    $node->forceopen = false;
}


// Toggle the editing state and switches.
if ($PAGE->user_allowed_editing()) {
    if ($reset !== null) {
        if (!is_null($userid)) {
            if (!$currentpage = my_reset_page($userid, MY_PAGE_PUBLIC, 'user-profile')) {
                print_error('reseterror', 'my');
            }
            redirect(new moodle_url('/user/profile.php', array('id' => $userid)));
        }
    } else if ($edit !== null) {             // Editing state was specified.
        $USER->editing = $edit;       // Change editing state.
    } else {                          // Editing state is in session.
        if ($currentpage->userid) {   // It's a page we can edit, so load from session.
            if (!empty($USER->editing)) {
                $edit = 1;
            } else {
                $edit = 0;
            }
        } else {
            // For the page to display properly with the user context header the page blocks need to
            // be copied over to the user context.
            if (!$currentpage = my_copy_page($userid, MY_PAGE_PUBLIC, 'user-profile')) {
                print_error('mymoodlesetup');
            }
            $PAGE->set_context($usercontext);
            $PAGE->set_subpage($currentpage->id);
            // It's a system page and they are not allowed to edit system pages.
            $USER->editing = $edit = 0;          // Disable editing completely, just to be safe.
        }
    }

    // Add button for editing page.
    $params = array('edit' => !$edit, 'id' => $userid);

    $resetbutton = '';
    $resetstring = get_string('resetpage', 'my');
    $reseturl = new moodle_url("$CFG->wwwroot/user/profile.php", array('edit' => 1, 'reset' => 1, 'id' => $userid));

    if (!$currentpage->userid) {
        // Viewing a system page -- let the user customise it.
        $editstring = get_string('updatemymoodleon');
        $params['edit'] = 1;
    } else if (empty($edit)) {
        $editstring = get_string('updatemymoodleon');
        $resetbutton = $OUTPUT->single_button($reseturl, $resetstring);
    } else {
        $editstring = get_string('updatemymoodleoff');
        $resetbutton = $OUTPUT->single_button($reseturl, $resetstring);
    }

    $url = new moodle_url("$CFG->wwwroot/user/profile.php", $params);
    $button = $OUTPUT->single_button($url, $editstring);
    $PAGE->set_button($resetbutton . $button);

} else {
    $USER->editing = $edit = 0;
}
// - - - - - - - - - - Start -PrivateCoursePhase2  NAV for Course info not sync on his profile
$getCourseCompList = $DB->get_records_sql("SELECT course,timeenrolled,timestarted FROM  {course_completions} WHERE  userid =$userid AND timeenrolled = 0 AND timestarted = 0");
if(isset($getCourseCompList) && !empty($getCourseCompList)){
    foreach ($getCourseCompList as $courseCompList) {
        $timeenrolled = $courseCompList->timeenrolled;
        $timestarted = $courseCompList->timestarted;
        $courseId = $courseCompList->course;
        if(!empty($courseId))
        {
            $courseModuleCompletion = $DB->get_record_sql("SELECT cmc.timemodified, cmc.coursemoduleid , cm.course FROM  {course_modules_completion} cmc LEFT JOIN mdl_course_modules cm ON cm.id = cmc.coursemoduleid WHERE cmc.userid =$userid AND cm.course = $courseId ORDER BY cmc.timemodified ASC LIMIT 0,1");
            if(!empty($courseModuleCompletion)){
                $timemodified = $courseModuleCompletion->timemodified;
                $updateCourseComplition = "UPDATE {course_completions} SET timeenrolled = '".$timemodified."' , timestarted = '".$timemodified."' WHERE userid =$userid AND course= $courseId";
                $DB->execute($updateCourseComplition);
               
            }
        }
    }
}
// - - - - - - - - - - End -PrivateCoursePhase2  NAV

// Trigger a user profile viewed event.
$queryState = "SELECT state_id, state_name, state_abbr FROM {us_state} where state_abbr = '".$user->state."'";
    $dataState = $DB->get_record_sql($queryState);
if($dataState){
    $user->state = $dataState->state_name;

}
profile_view($user, $usercontext);

// TODO WORK OUT WHERE THE NAV BAR IS!
echo $OUTPUT->header();
//require_once($CFG->libdir . '/sfdclib.php');
$suserids = $userid;
//update_certificate_details($userid);
echo '<div class="userprofile myprofilesetP">';

if ($user->description && !isset($hiddenfields['description'])) {
    echo '<div class="desVIew"><span class="rdMoreB">Read More</span><div class="showViewinner"><div class="addCancel">+</div><div class="description">';
    if (!empty($CFG->profilesforenrolledusersonly) && !$currentuser && !$DB->record_exists('role_assignments', array(
        'userid' => $user->id
    ))) {
        echo get_string('profilenotshown', 'moodle');
    } else {
        $user->description = file_rewrite_pluginfile_urls($user->description, 'pluginfile.php', $usercontext->id, 'user',
                                                          'profile', null);
        echo format_text($user->description, $user->descriptionformat);
    }
    echo '</div></div></div>';
}

echo $OUTPUT->custom_block_region('content');
 /*<!-- User profile start -->*/

echo '<div class="profile_img_section">
        '.$OUTPUT->user_picture($user, array('size' => 150, 'class' => 'welcome_userpicture')).'  
    </div>';

 /* <!-- User profile end -->*/
// Render custom blocks.
$renderer = $PAGE->get_renderer('core_user', 'myprofile');
$tree = core_user\output\myprofile\manager::build_tree($user, $currentuser);
echo $renderer->render($tree);
?>
<div class="profile_tree1">
    <div class="row">
        <!-- Training Status block start -->
        <section class=" node_category">
            <h3>Training Status</h3>
            <div class="setparraneeD">
                <div class="inputsetD"><input type="text" id ="trainingStatusInput" onkeyup="trainingStatusFunction()" placeholder="Search.."></div>
                <?php require_once("user_training_status.php");?>
            </div>
        </section>
        <!-- Training Status block end -->
        <!-- Certifications block start -->
        <section class="node_category">
            <h3>Certifications</h3>
            <div class="setparraneeD1">
                <div class="inputsetD"><input type="text" id ="certificateStatusInput" onkeyup="certificateStatusFunction()" placeholder="Search..">
                </div>
                <?php require_once("user_certifications.php");?>
            </div>
        </section>
        <!-- Certifications block end -->
    </div>
    <div class="row">
        <!-- Grader block start -->
        <section class="node_category">
            <h3>Grader</h3>
            <div class="setparraneeD2">
                <div class="inputsetD"><input type="text" id ="graderStatusInput" onkeyup="graderStatusFunction()" placeholder="Search.."></div>
                <?php require_once("user_grader.php"); ?>
            </div>
        </section>
        <!-- Grader block end -->
            <!-- Admin Grader Notes block start -->
            <?php require_once("user_admin_grader_note.php");?>
            <!-- Admin Grader Notes block end -->

    </div>

</div>


<?php
echo '</div>';  // Userprofile class.

//echo "<pre>";
//print_r($USER);
$sqlRole = "SELECT id FROM {role} WHERE shortname='grader'";
$rsRole = $DB->get_record_sql($sqlRole);

$sqlRoleAssignment = "SELECT contextid FROM {role_assignments} where userid=".$user->id." AND roleid=".$rsRole->id;
$rsRoleAssignment = $DB->get_record_sql($sqlRoleAssignment);
if(count($rsRoleAssignment->contextid) > 0){
    $user->usertype="graderasadmin";
}
//print_r($user);

echo $OUTPUT->footer();

?>
<script type="text/javascript">

    /* Spanish Design changes*/
    $(".profile_tree > section:nth-child(1)").removeClass();
    $(".profile_tree > section:nth-child(1)").addClass("node_category User details");
    // start 4635 add if full logic and added var crole
    var cRole = '<?php echo $crole;?>';
    if(cRole == 'instructor'){
        $(".profile_tree > section:nth-child(1)").attr('style', 'margin-bottom: 10px !important');
    }
    // end 4635 add if full logic
    $(".details").prepend('<br><div class = "userProfile"></div>');
    $( ".profile_img_section" ).insertAfter( ".userProfile" );

    var userRole = '<?php echo $user->usertype;?>';
    var isAdmin = '<?php echo $USER->usertype;?>';
    var flagINSBC = '<?php echo $flagINSBC;?>';
    var adminId = '<?php echo $USER->id;?>';
    var userId = '<?php echo $user->id; ?>';

    $(".badges").parent().parent().parent().parent().parent().remove();
    //4635 add crole cond
    console.log(cRole + "  Crole" );
 
 console.log(adminId +" AdminId");
        console.log(cRole + " cRole");
        console.log(isAdmin + " isAdmin");
        console.log(userRole + " Ugraderasadmin");
         console.log(userId + " userId");
    if((adminId != userId || isAdmin == 'mainadmin' || isAdmin == 'graderasadmin') && userRole != 'graderasadmin' && cRole != 'instructor'){
        $('h3:contains("Privacy and policies")').parent().remove();
        $(".profile_tree > section:nth-child(2)").removeClass();
        $(".profile_tree > section:nth-child(2)").addClass("node_category Administration");

        // START 18468
        $(".profile_tree > section:nth-child(3)").removeClass();
        $(".profile_tree > section:nth-child(3)").addClass("node_category Login activity");
        // END 18468
        
        $( ".profile_tree1" ).insertAfter( ".profile_tree > section:last-child");
    }else if(adminId == 2 && userRole == 'graderasadmin'){
        $('h3:contains("Privacy and policies")').parent().remove();
        $(".profile_tree > section:nth-child(3)").removeClass();
        $(".profile_tree > section:nth-child(3)").addClass("node_category Login activity");
        $( ".profile_tree1" ).insertAfter( ".profile_tree > section:last-child");
    }else{
        // $('h3:contains("Privacy and policies")').parent().remove();
        // START 18468
        $(".profile_tree > section:nth-child(2)").remove();
        // END 18468
        $(".profile_tree > section:nth-child(2)").removeClass();
        $(".profile_tree > section:nth-child(2)").addClass("node_category Login activity");
        // $(".profile_tree > section:nth-child(2)").addClass("node_category Administration");
        $( ".profile_tree1" ).insertAfter( ".profile_tree > section:last-child");
    }

    if(adminId == 2 && adminId == userId){
        $(".profile_tree > section:nth-child(2)").removeClass();
        $(".profile_tree > section:nth-child(2)").addClass("node_category Login activity");
        $('.userprofile').addClass('userprofileP3');
    }

    if(adminId == userId && isAdmin == 'graderasadmin'){
        $(".profile_tree > section:nth-child(2)").removeClass();
        $(".profile_tree > section:nth-child(2)").addClass("node_category Login activity");
        $('.userprofile').addClass('userprofileP3');
        //$(".profile_tree > section:nth-child(1)").show();
    }

    $("#page-user-profile .userprofile.myprofilesetP .profile_tree .node_category.Login.activity").attr('style', 'bottom: -70px !important');
    //$(".Badges").html( " ");
    //$(".Insignias").html( " ");
    $('#page-user-profile').addClass('zoomin');
    $('.outercont').removeClass('container-fluid');
    $('.page-content').removeClass('row flex-row');
    $('#region-main-box').removeClass('col-9');
    $('#region-main').removeClass('span9');
    $('#region-main-box').addClass('span12');
    $('#region-main').addClass('span12');
    $('[role=main]').addClass('container-fluid');

    /* Spanish Design changes end*/

    if (parseInt($(".fixheight_TEST").height())>=240) {

        $(".setparraneeD").addClass("checkheight");
    }

    if (parseInt($(".fixheight").height())>=251) {

        $(".setparraneeD1").addClass("checkheight");
    }

    if (parseInt($(".tabel_base_cls").height())>=251) {

        $(".setparraneeD2").addClass("checkheight");
    }
    if (parseInt($(".description").height())>=65) {

        $(".desVIew").addClass("showdesHeight");
    }

    $(".rdMoreB").click(function(){
        $(".desVIew ").addClass("viewPopupview");
    });
    $(".addCancel").click(function(){
        $(".desVIew ").removeClass("viewPopupview");
    });

    $('.myprofilesetP .node_category.User.details ul li').html(function () {
        return $(this).html().replace(/Edit profile(?=[^>]*<)/ig, "<span class='hit-list' title='Edit profile' >$&</span>");
    });
    $('.hit-list').hide();
    $('.details ul li a').addClass("hit-list");

    $('.table-view.tabel_base_cls').html(function () {
        return $(this).html().replace(/Not Found(?=[^>]*<)/ig, "<span class='notFound'>$&</span>");
    });
</script>
<?php
if($_SESSION['instructorrole_breadcrumb_flag'] == 1){
    $flagINSBC = 1;
}else{
    $flagINSBC = 0;
}
?>
<script type="text/javascript">

    if(adminId != userId || isAdmin == 'mainadmin' || isAdmin == 'graderasadmin' || flagINSBC == 1){
        $('.userprofile').addClass('userprofileP2');
        $('#block-region-side-post').attr('style','height:inherit; display:block');
        $('.userprofile.myprofilesetP').attr('style','margin-top:90px')
    }
</script>
<style>
