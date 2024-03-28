<style> 
    #block-region-content{ display:none; }   
</style>
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

require_once(dirname(__FILE__) . '/../config.php');
require_once($CFG->libdir.'/accesslib.php');
require_once($CFG->libdir.'/tablelib.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->libdir.'/portfoliolib.php');
require_once($CFG->libdir.'/modinfolib.php');
require_once($CFG->libdir.'/datalib.php');
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once($CFG->dirroot.'/mod/assign/gradingtable.php');
require_once($CFG->dirroot.'/mod/assign/grader_id_dropdown.php');

//lakhan-start

// Start update - dashboard_admin_grader - prev
//    if($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin'){
//        redirect(new moodle_url($CFG->wwwroot.'/my/dashboard_grader.php'));
//    }elseif($USER->usertype == 'mainadmin'){
//        // Do Nothing
//    }else{
//        redirect(new moodle_url($CFG->wwwroot.'/login/index.php?id=admin'));
//    }
// End update - dashboard_admin_grader - prev


//Customization for existing/new users first time redirection end

require_once($CFG->dirroot . '/my/lib.php');

redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$reset  = optional_param('reset', null, PARAM_BOOL);

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
// $PAGE->blocks->add_region('content');
// $PAGE->set_subpage($currentpage->id);
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
                print_error('reseterror', 'my');
            }
            redirect(new moodle_url('/my'));
        }
    } else if ($edit !== null) {       // Editing state was specified
        $USER->editing = $edit;        // Change editing state
    } else {                           // Editing state is in session
        if ($currentpage->userid) {    // It's a page we can edit, so load from session
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
    $resetstring = get_string('resetpage', 'my');
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

    $url = new moodle_url("$CFG->wwwroot/my/index.php", $params);
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
                    where fsd.fieldid=".$fieldid." AND fsd.data LIKE '%".$USER->username."%' order by fs.timemodified desc";
  $rsSessData = $DB->get_records_sql($sqlSessData);
}

if($rsSessData)
  $_SESSION['instructorrole_breadcrumb_flag'] = 1;
else
  $_SESSION['instructorrole_breadcrumb_flag'] = 0;
//Grader as a Manager Code End

echo $OUTPUT->header();

if (!isguestuser()) { 
//echo $OUTPUT->custom_block_region();
$enrolledCourses = enrol_get_my_courses();

?>

<!--Dashboard design start-->
<div class="newdashboard_design">
  <div class="newdashboard_welcome_section">
    <div class="profile_img_section"> <?php echo $OUTPUT->user_picture($USER, array('size' => 100, 'class' => 'welcome_userpicture')); ?></div>
    <div class="profile_details_section">
      
      <h3><?=get_string('welcomeback','local_assign', $USER->firstname);?></h3>
       <!-- Grader as a Manager Code Start -->
      <div class="three_tabs_newdashboard">
        <ul>
          <li><a href="/user/profile.php?id=<?=$USER->id?>" title="<?=get_string('user');?> <?=get_string('profile');?>"><?=get_string('user');?> <?=get_string('profile');?></a>  <span>|</span></li>
          <li><a href="/grade/report/overview/index.php" title="<?=get_string('grades');?>"><?=get_string('grades');?></a>  <span>|</span></li>
          <?php 
          //59 Est Instructor Feature start
          //change-2 start
          if($rsSessData){
            $slashCnt = 0;
            foreach ($rsSessData as $rsSessDataKey) {        
              if($rsSessDataKey){
              ?>
              <li><a href="/mod/facetoface/view.php?f=<?=$rsSessDataKey->facetofaceid?>&crole=instructor" title="<?=get_string('classroom_enrollments','my');?>"><?php echo format_string($rsSessDataKey->name);?> Enrollments</a>
              <?php         
                $slashCnt++;
                if(count($rsSessData) > $slashCnt) echo "<span>|</span></li>";
                else echo "</li>";                
              }                       
            }
          }//end if $rsSeesData
          else{
          ?>
          <li><a href="/user/preferences.php" title="<?=get_string('preferences');?>"><?=get_string('preferences');?></a></li>
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

$sqlUser_AssignGrader_AssignSubmission = "SELECT  rand() as counter, u.id, s.timemodified AS timesubmitted, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.institution, u.middlename, u.alternatename, u.imagealt, u.email, u.country, u.id AS userid, s.status AS STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
FROM mdl_user u 
LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
LEFT JOIN mdl_course c ON c.id = cm.course
LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
WHERE u.id != 5 AND u.id != 2 AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ORDER BY g.timemodified DESC LIMIT 0, 100";

$rsStudentRecordArray = $DB->get_records_sql($sqlUser_AssignGrader_AssignSubmission);

$rsStudentRecordCommentObj = clone (object)$rsStudentRecordArray;

$rsStudentRecordArray = (array)$rsStudentRecordArray;
$courseIdArray = [];
$courseModulesIdArray = [];


//lakhan-end
?>
<!-- Notification area for grader dashboard -->
<div class="certification_in_progress notification-panel">
    <h4>Notifications</h4>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script> 
        <script type="text/javascript">
            $(document).ready(function(){
                $('#studentAdminDataTable').DataTable({                       
                        "pageLength": 25,
                        "lengthMenu": [[3,15, 25, 50, -1], [3,15, 25, 50, "All"]],
                        "aoColumnDefs": [
                          { "iDataSort": 6, "aTargets": [ 5 ] }
                        ],
                        "columns": [
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            {"bVisible":false,"searchable": false },
                            null,
                            {"searchable": false,"orderable": false, },    
                            {"searchable": true },            
                        ],
                        "order": [[ 6,'desc']],
                        "search": {
                            "smart": true,
                        },
						"bSortClasses" : false, 
                        "orderClasses": false,
                        "bAutoWidth" : false,
                        "bProcessing": true,
                        "bDeferRender": true,
                }); 
                //var table = $('#studentAdminDataTable').DataTable();
                //table.order( [ 5, 'desc' ] ).draw();
            });
        </script>
    <div class="table-view tabel_base_cls">
        <table id='studentAdminDataTable'  class="table-bordered graderpg_cls gradenew_cls">
            <thead>
                <tr>
                   <th id="col0"><?php echo get_string('grader_studentname'); ?></th>
                    <th id="col1" width="50" ><?php echo get_string('country'); ?></th>
                    <!-- <th id="col1" width="150" ><?php // echo get_string('company'); ?></th> -->
                    <th id="col2"><?php echo get_string('grader_status'); ?></th>
                    <th id="col3"><?php echo get_string('grader_coursename'); ?></th>
                    <th id="col4"><?php echo get_string('grader_grade_view'); ?></th>
                    <th id="col5" class="sorting"><?php echo get_string('grader_last_modified'); ?></th> 
                    <th id="col6" class=""></th>
                    <th id="col7"><?php echo get_string('grader_file_submissions'); ?></th> 
                    <th id="col8" class="sorting"><?php echo get_string('grader_grader'); ?></th>   
                    <th id="col9" class="date-time-col-hide"></th>                                  
                </tr>
            </thead>
            <tbody>
                 <?php
		    $coursecontext = "";
		    $coursecontextsecond = "";
		    $en = "";
                    if(count((array)$rsStudentRecordArray)>0){
                        $rowCount = 1;
                        foreach($rsStudentRecordArray  as $rsStudentRecord){

			$coursecontext = context_course::instance($rsStudentRecord->course_id);
			if(is_enrolled($coursecontext, $rsStudentRecord->id, '', false)){ 

                        $courseIdArray[$rsStudentRecord->course_id] = $rsStudentRecord->course_name;
                ?>
                    <tr>

                        <td>
                            <span>
                                <?php 
				    //echo $en;
                                    $avatar = new user_picture($rsStudentRecord);
                                    $avatar->courseid = $rsStudentRecord->course_id;
                                    $avatar->link = true;
                                    echo $OUTPUT->render($avatar);
                                ?>
                                <!-- </a> -->
                            </span>
                            <span>
                                    <?php 
                                         $name = $rsStudentRecord->firstname." ".$rsStudentRecord->lastname;
                                         $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentRecord->id,'course'=>$rsStudentRecord->course_id)); // $COURSE->id
                                         echo " ".html_writer::link($profileurl, $name);
                                        //echo "<a href='javascript:void(0)'>".format_string($name)."</a>";
                                    ?>
                            </span>
                        </td>
                        <td >
                            <span> 
                                <?php //echo $rsStudentRecord->institution;?>
                                <?php
                                $countries = get_string_manager()->get_list_of_countries("true","en");
                                $userCountryName = "";
                                foreach($countries as $countryAbbr => $countryName){
                                    if(strtolower($countryAbbr) == strtolower($rsStudentRecord->country)){
                                            $userCountryName = $countryName;
                                    }
                                }
                                ?>
                                <?php echo $userCountryName;?>
                            </span>
                        </td>
                        <td class="grading-comment">
                            <span> <?php echo $OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecord->status, 'assign'),
                                array('class'=>'submissionstatus' .$rsStudentRecord->status));?>
                            </span>
                        </td>
                        <td class="grading-course">
                            <span> 
                                <?php 
                                if($rsStudentRecord->course_visible){
                                    echo format_string($rsStudentRecord->course_name);
                                }else{
                                     // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                                    echo "<span style='text-decoration: none;'>".format_string($rsStudentRecord->course_name)."</span>";
                                    // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                                }
                                 ?>
                            </span> 
                        </td>
                        <td class="actn-btns">
                            <?php 


                                //pre-  Added for applying class disable link if grader is admin
                                $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                 
                                $dropdownArray = grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);
                                //pre-  Added for applying class disable link if grader is admin


                                if($rsStudentRecord->course_modules_id != ''){

                                    $params = array('id' => $rsStudentRecord->course_modules_id,'action'=>'grade','userid'=>$rsStudentRecord->userid,'rownum'=>0);
                                    $gradetUrl = new moodle_url("$CFG->wwwroot/mod/assign/view.php", $params);
                            ?>
                                <a id='button_<?=$rowCount?>' <?php if(trim($dropdownArray[1]) == 'Administrator'){ echo "class='enable_link'";}else{ echo "class='disable_link'";}?> href="<?php echo $gradetUrl;?>">Grade</a>

                                <?php }else{ echo "Submission not came";} ?>
                        </td>
                        <td class="date-time">
                            <span> <?php 
                            echo userdate($rsStudentRecord->timesubmitted);
                            ?></span>
                        </td>
                        <td class="">
                            <span> <?php 
                           // echo date("Ymd",$rsStudentRecord->timesubmitted);
                             echo $rsStudentRecord->timesubmitted;
                            ?></span>
                        </td>
                        <td class="files">
                                <?php 
                                require_once($CFG->dirroot.'/mod/assign/submission/file/locallib.php');
                                if($rsStudentRecord->course_modules_id != ''){
                                
                                global $DB;

                                $component = 'assignsubmission_file';
                                $filearea = 'submission_files';

                                    $sqlAssigSubQuery = "SELECT id,status, timemodified FROM {assign_submission} WHERE assignment = ".$rsStudentRecord->assignmentid." AND userid = ".$rsStudentRecord->userid." AND status='submitted' ORDER BY timemodified DESC LIMIT 0,1";
                                $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery);
                                 $submissionid = $sqlAssigSubData->id;



                                $context = context_module::instance($rsStudentRecord->course_modules_id);
                                $fs = get_file_storage();
                                $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);

                                foreach ($dir['files'] as $file) {

                                    //print_r($file);
                                    $file->portfoliobutton = '';

                                    $filename = $file->get_filename();
     				    $url = "";
                                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                                    if( $ext == 'pdf'){ 
                                            $url = $OUTPUT->pix_url('pdfsmall', 'theme');
                                            
                                    }elseif( $ext == 'doc' || $ext == 'docx'){ 
                                            $url = $OUTPUT->pix_url('word', 'theme');
                                            
                                    }elseif( $ext == 'xlsx' || $ext == 'csv'){ 
                                            $url = $OUTPUT->pix_url('excel', 'theme');
                                            
                                    }elseif( $ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){ 
                                            $url = $OUTPUT->pix_url('image', 'theme');
                                           
                                    }elseif( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
                                            $url = $OUTPUT->pix_url('otherfile', 'theme');   
				    }  
				    echo "<img class='icon' alt='".$filename."' title='".$filename."' src=".$url.">";
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
                                    echo html_writer::link($url, substr($filename,0,10).'...',array('title' => $filename));
                                    echo "<br/>";
                                }
                                ?>
                                <?php }else{ echo "File not found";}?>
                        </td>
                        <td class='td_dropdown_<?=$rowCount?>' >
                        <?php 
                            echo $dropdownArray[0]; 
                        ?>
                        </td>
                        <td class="date-time-col-hide"><?php echo $dropdownArray[1]; ?></td>
                    </tr>

                <?php
                    $rowCount++;
                    }
}

                    $rsStudentRecordCommentArray = (array)$rsStudentRecordCommentObj;
                    //echo "<pre>"; print_r($rsStudentRecordCommentArray); exit;
                    foreach($rsStudentRecordCommentArray as $rsStudentCommentRecord){
                        //if($rsStudentCommentRecord->assignmentid != ''){
			$coursecontextsecond = context_course::instance($rsStudentCommentRecord->course_id);
			if(is_enrolled($coursecontextsecond, $rsStudentCommentRecord->userid, '', false)){

                             $context = context_module::instance($rsStudentCommentRecord->course_modules_id);

                            $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE mc.userid=".$rsStudentCommentRecord->userid." AND mc.contextid=".$context->id." ORDER BY mc.timecreated DESC LIMIT 0,1";

                        $rsComments = $DB->get_record_sql($sqlAssignSubmission);

                        if($rsComments->id > 0){
                ?>
                    <tr>

                        <td>
                             <span>
                                <?php 
                                    $avatar = new user_picture($rsStudentCommentRecord);
                                    $avatar->courseid = $rsStudentCommentRecord->course_id;
                                    $avatar->link = true;
                                    echo $OUTPUT->render($avatar);
                                ?>
                            </span>
                            <span>
                                    <?php 
                                         $name = $rsStudentCommentRecord->firstname." ".$rsStudentCommentRecord->lastname;
                                         $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentCommentRecord->id,'course'=>$rsStudentCommentRecord->course_id)); // $COURSE->id
                                         echo " ".html_writer::link($profileurl, $name);
                                    ?>
                            </span>
                        </td>
                        <td >
                            <span> 
                                <?php //echo $rsStudentCommentRecord->institution;?>
                                <?php
                                $countries = get_string_manager()->get_list_of_countries("true","en");
                                $userCountryName = "";
                                foreach($countries as $countryAbbr => $countryName){
                                    if(strtolower($countryAbbr) == strtolower($rsStudentCommentRecord->country)){
                                            $userCountryName = $countryName;
                                    }
                                }
                                ?>
                                <?php echo $userCountryName;?>
                            </span>
                        </td>
                        <td class="grading-comment">
                            <span> 
                                <?php
                                // Note: records are comming with assign_submission_id OR itemid are belongs to the submissions having enclosed files;
                                echo "New Comment";
                                ?>
                            </span>
                        </td>
                        <td class="grading-course">
                            <span> 
                                <?php 
                                if($rsStudentCommentRecord->course_visible){
                                    echo format_string($rsStudentCommentRecord->course_name);
                                }else{
                                    // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                                    echo "<span style='text-decoration: none;'>".format_string($rsStudentCommentRecord->course_name)."</span>";
                                    // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                                }
                                ?>
                            </span> 
                        </td>
                        <td class="actn-btns">
                            <?php
                                $params = array('id' => $rsStudentCommentRecord->course_modules_id,'userid'=>$rsStudentCommentRecord->userid,'action'=>'comment','sesskey'=>'','page'=>'0');
                                $commentUrl = new moodle_url("$CFG->wwwroot/local/assign/comment.php", $params);
                            ?>
                            <a href="<?php echo $commentUrl;?>">View</a>
                        </td>
                        <td class="date-time">
                            <span> 
                            <?php 
                             echo userdate($rsComments->timecreated);
                            ?>                                
                            </span>
                        </td>
                        <td class="">
                            <span> 
                            <?php 
                             echo $rsComments->timecreated;
                            ?>                                
                            </span>
                        </td>
                        <td class="actn-btns"></td>
                        <td class="actn-btns"></td>     
                        <td class="date-time-col-hide"></td>    
                    </tr>

                    <?php
                }// end if rscomment
                    //} //End - if end ifassignmentid
}
                }   //End - foreach
                //$rsStudentRecordArray->close();

                    }else{
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
<?php }else{
    redirect("$CFG->wwwroot/");
    }?>
<?php //}//end else if design is static
if($_GET["design"] == 'menu'){
    ?>
/***************/
For Navigation Work
/****************/
<?php }
echo $OUTPUT->custom_block_region('content');
echo $OUTPUT->footer();

?>

<div class="overlay" style="display: none">

   <div id="loading-img"></div>
</div>
<style type="text/css">
#loading-img {
    background: url(https://dev.training.qsc.com/mod/assign/4V0b.gif) center center no-repeat;
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
<script type='text/javascript'>
    //pre- added for disable link
    $(".grader_pulldown").change(function() {
      var graderVal = this.value;

        $.ajax({
             url: '/mod/assign/set_assigned_grader_ajax.php',
             type: 'post',
             data: {grader_val: graderVal},
             success: function(response) {
                console.log(response);
             }
        });


        var ddClass = $(this).parent().attr('class');
        var rowArray = ddClass.split('td_dropdown_');
        if( $.trim($(this).find("option:selected").text()) == 'Administrator'){
            $("#button_"+rowArray[1]).attr('class','enable_link');
        }else{
            $("#button_"+rowArray[1]).attr('class','disable_link');
        }

       
    });

    $(document).on("click",".disable_link",function(e){
        e.preventDefault();
        alert('Please change Grader assignment to ‘Administrator’ first, to perform grade.');
    });
    //pre- added for disable link

    $(document).on("click","#col4",function(e){
        e.preventDefault();
        $("#col5").click();
    });
    $(document).on("click","#col7",function(e){
        e.preventDefault();
        $("#col8").click();
    });


</script>


