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
 * Display the course home page.
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_course
 */

require_once('../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/completionlib.php');

redirect_if_major_upgrade_required();

$id = optional_param('id', 0, PARAM_INT);
$name = optional_param('name', '', PARAM_TEXT);
$edit = optional_param('edit', -1, PARAM_BOOL);
$hide = optional_param('hide', 0, PARAM_INT);
$show = optional_param('show', 0, PARAM_INT);
$duplicatesection = optional_param('duplicatesection', 0, PARAM_INT);
$idnumber = optional_param('idnumber', '', PARAM_RAW);
$sectionid = optional_param('sectionid', 0, PARAM_INT);
$section = optional_param('section', 0, PARAM_INT);
$expandsection = optional_param('expandsection', -1, PARAM_INT);
$move = optional_param('move', 0, PARAM_INT);
$marker = optional_param('marker', -1 , PARAM_INT);
$switchrole = optional_param('switchrole', -1, PARAM_INT); // Deprecated, use course/switchrole.php instead.
//Custom-1
$modchooser  = optional_param('modchooser', -1, PARAM_BOOL);
$return = optional_param('return', 0, PARAM_LOCALURL);

$params = [];
if (!empty($name)) {
    $params = ['shortname' => $name];
} else if (!empty($idnumber)) {
    $params = ['idnumber' => $idnumber];
} else if (!empty($id)) {
    $params = ['id' => $id];
} else {
    throw new \moodle_exception('unspecifycourseid', 'error');
}

$course = $DB->get_record('course', $params, '*', MUST_EXIST);

$urlparams = ['id' => $course->id];

// Sectionid should get priority over section number.
if ($sectionid) {
    $section = $DB->get_field('course_sections', 'section', ['id' => $sectionid, 'course' => $course->id], MUST_EXIST);
}
if ($section) {
    $urlparams['section'] = $section;
}
if ($expandsection !== -1) {
    $urlparams['expandsection'] = $expandsection;
}

$PAGE->set_url('/course/view.php', $urlparams); // Defined here to avoid notices on errors etc.

// Prevent caching of this page to stop confusion when changing page after making AJAX changes.
$PAGE->set_cacheable(false);

context_helper::preload_course($course->id);
$context = context_course::instance($course->id, MUST_EXIST);

// Remove any switched roles before checking login.
if ($switchrole == 0 && confirm_sesskey()) {
    role_switch($switchrole, $context);
}

    if (!empty($USER->id) && $USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $USER->id != 1 && $course->id >1) {

        $clsssroomCourse= $DB->get_record_sql("Select * FROM `mdl_course` WHERE  NOT EXISTS (SELECT * FROM `mdl_course_modules`  WHERE  module IN(3,16) AND mdl_course.id= mdl_course_modules.course ) AND mdl_course.id= ".$course->id);
        if(isset($clsssroomCourse) && !empty($clsssroomCourse)){
            //for exam only course setting
            $clsssroomModuleExist= $DB->get_record_sql("SELECT * FROM `mdl_course_modules`  WHERE  module =27 AND course= ".$course->id);
            if(!empty($clsssroomModuleExist)){
                $insertSql = "INSERT INTO classroom_autoenrolled_delete (user_id, course_id, page) VALUES (".$USER->id.",".$course->id.", 'course_view')";
                $DB->execute($insertSql);
            }
        }
    }
    
require_login($course);

// Switchrole - sanity check in cost-order...
$resetuserallowedediting = false;
if ($switchrole > 0 && confirm_sesskey() &&
    has_capability('moodle/role:switchroles', $context)) {
    // Is this role assignable in this context?
    // Inquiring minds want to know.
    $aroles = get_switchable_roles($context);
    if (is_array($aroles) && isset($aroles[$switchrole])) {
        role_switch($switchrole, $context);
        // Double check that this role is allowed here.
        require_login($course);
    }
    // Reset course page state. This prevents some weird problems.
    $USER->activitycopy = false;
    $USER->activitycopycourse = null;
    unset($USER->activitycopyname);
    unset($SESSION->modform);
    $USER->editing = 0;
    $resetuserallowedediting = true;
}

// If course is hosted on an external server, redirect to corresponding
// url with appropriate authentication attached as parameter.
if (file_exists($CFG->dirroot . '/course/externservercourse.php')) {
    include($CFG->dirroot . '/course/externservercourse.php');
    if (function_exists('extern_server_course')) {
        if ($externurl = extern_server_course($course)) {
            redirect($externurl);
        }
    }
}

require_once($CFG->dirroot.'/calendar/lib.php'); // This is after login because it needs $USER.

// Must set layout before gettting section info. See MDL-47555.
$PAGE->set_pagelayout('course');
$PAGE->add_body_class('limitedwidth');

if ($section && $section > 0) {

    // Get section details and check it exists.
    $modinfo = get_fast_modinfo($course);
    $coursesections = $modinfo->get_section_info($section, MUST_EXIST);

    // Check user is allowed to see it.
    if (!$coursesections->uservisible) {
        // Check if coursesection has conditions affecting availability and if
        // so, output availability info.
        if ($coursesections->visible && $coursesections->availableinfo) {
            $sectionname = get_section_name($course, $coursesections);
            $message = get_string('notavailablecourse', '', $sectionname);
            redirect(course_get_url($course), $message, null, \core\output\notification::NOTIFY_ERROR);
        } else {
            // Note: We actually already know they don't have this capability
            // or uservisible would have been true; this is just to get the
            // correct error message shown.
            require_capability('moodle/course:viewhiddensections', $context);
        }
    }
}

// Fix course format if it is no longer installed.
$format = course_get_format($course);
$course->format = $format->get_format();

$PAGE->set_pagetype('course-view-' . $course->format);
$PAGE->set_other_editing_capability('moodle/course:update');
$PAGE->set_other_editing_capability('moodle/course:manageactivities');
$PAGE->set_other_editing_capability('moodle/course:activityvisibility');
if (course_format_uses_sections($course->format)) {
    $PAGE->set_other_editing_capability('moodle/course:sectionvisibility');
    $PAGE->set_other_editing_capability('moodle/course:movesections');
}

// Preload course format renderer before output starts.
// This is a little hacky but necessary since
// format.php is not included until after output starts.
$renderer = $format->get_renderer($PAGE);

if ($resetuserallowedediting) {
    // Ugly hack.
    unset($PAGE->_user_allowed_editing);
}

if (!isset($USER->editing)) {
    $USER->editing = 0;
}
if ($PAGE->user_allowed_editing()) {
    if (($edit == 1) && confirm_sesskey()) {
        $USER->editing = 1;
        // Redirect to site root if Editing is toggled on frontpage.
        if ($course->id == SITEID) {
            redirect($CFG->wwwroot .'/?redirect=0');
        } else if (!empty($return)) {
            redirect($CFG->wwwroot . $return);
        } else {
            $url = new moodle_url($PAGE->url, ['notifyeditingon' => 1]);
            redirect($url);
        }
    } else if (($edit == 0) && confirm_sesskey()) {
        $USER->editing = 0;
        if (!empty($USER->activitycopy) && $USER->activitycopycourse == $course->id) {
            $USER->activitycopy = false;
            $USER->activitycopycourse = null;
        }
        // Redirect to site root if Editing is toggled on frontpage.
        if ($course->id == SITEID) {
            redirect($CFG->wwwroot .'/?redirect=0');
        } else if (!empty($return)) {
            redirect($CFG->wwwroot . $return);
        } else {
            redirect($PAGE->url);
        }
    }

    if (has_capability('moodle/course:sectionvisibility', $context)) {
        if ($hide && confirm_sesskey()) {
            set_section_visible($course->id, $hide, '0');
            redirect($PAGE->url);
        }

        if ($show && confirm_sesskey()) {
            set_section_visible($course->id, $show, '1');
            redirect($PAGE->url);
        }
    }

    if (
        !empty($section) && !empty($coursesections) && !empty($duplicatesection)
        && has_capability('moodle/course:update', $context) && confirm_sesskey()
    ) {
        $newsection = $format->duplicate_section($coursesections);
        redirect(course_get_url($course, $newsection->section));
    }

    if (!empty($section) && !empty($move) &&
            has_capability('moodle/course:movesections', $context) && confirm_sesskey()) {
        $destsection = $section + $move;
        if (move_section_to($course, $section, $destsection)) {
            if ($course->id == SITEID) {
                redirect($CFG->wwwroot . '/?redirect=0');
            } else {
                if ($format->get_course_display() == COURSE_DISPLAY_MULTIPAGE) {
                    redirect(course_get_url($course));
                } else {
                    redirect(course_get_url($course, $destsection));
                }
            }
        } else {
            echo $OUTPUT->notification('An error occurred while moving a section');
        }
    }
} else {
    $USER->editing = 0;
}

$SESSION->fromdiscussion = $PAGE->url->out(false);


if ($course->id == SITEID) {
    // This course is not a real course.
    redirect($CFG->wwwroot .'/?redirect=0');
}
    $completion = new completion_info($course);
    if ($completion->is_enabled()) {
        $PAGE->requires->string_for_js('completion-alt-manual-y', 'completion');
        $PAGE->requires->string_for_js('completion-alt-manual-n', 'completion');

        //$PAGE->requires->js_init_call('M.core_completion.init');
    }

// Determine whether the user has permission to download course content.
$candownloadcourse = \core\content::can_export_context($context, $USER);

// We are currently keeping the button here from 1.x to help new teachers figure out
// what to do, even though the link also appears in the course admin block.  It also
// means you can back out of a situation where you removed the admin block.
if ($PAGE->user_allowed_editing()) {
    $buttons = $OUTPUT->edit_button($PAGE->url);
    $PAGE->set_button($buttons);
}

$editingtitle = '';
if ($PAGE->user_is_editing()) {
    // Append this to the page title's lang string to get its equivalent when editing mode is turned on.
    $editingtitle = 'editing';
}

// If viewing a section, make the title more specific.
if ($section && $section > 0 && course_format_uses_sections($course->format)) {
    $sectionname = get_string('sectionname', "format_$course->format");
    $sectiontitle = get_section_name($course, $section);
    $PAGE->set_title(
        get_string(
            'coursesectiontitle' . $editingtitle,
            'moodle',
            ['course' => $course->fullname, 'sectiontitle' => $sectiontitle, 'sectionname' => $sectionname]
        )
    );
} else {
    $PAGE->set_title(get_string('coursetitle' . $editingtitle, 'moodle', ['course' => $course->fullname]));
}

// Add bulk editing control.
$bulkbutton = $renderer->bulk_editing_button($format);
if (!empty($bulkbutton)) {
    $PAGE->add_header_action($bulkbutton);
}

$PAGE->set_heading($course->fullname);
//echo $OUTPUT->header();

if ($USER->editing == 1) {

    // MDL-65321 The backup libraries are quite heavy, only require the bare minimum.
    require_once($CFG->dirroot . '/backup/util/helper/async_helper.class.php');

    if (async_helper::is_async_pending($id, 'course', 'backup')) {
        echo $OUTPUT->notification(get_string('pendingasyncedit', 'backup'), 'warning');
    }
}

        //Custom-4
     $isadmin = is_siteadmin($USER);
    if(!$isadmin){
      //1069 Code Start
      $courseAssignSQL = 'SELECT id FROM {assign} WHERE course ='.$course->id.' and type ="classroom"';
      $courseAssignRes = $DB->get_record_sql($courseAssignSQL);
      //For Online Courses 
        if(empty($courseAssignRes)) {
            // Checking with course completion criteria and get course first level id for checking completion
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
        }
        //1069 start AND combination
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
        //1069 end   
    }
    
    //1069 Online COurses Restriction for Prerequisite End 
    echo $OUTPUT->header();
    ?>
    <link href="/theme/meline29/style/classroom.css" type="text/css" rel="stylesheet">
    <?php if($course->category == 4){
?>
<!-- <h3><span style="color:red;font-size:20px;">Website will be under maintenance on May 22, 2022 at 11:00 PM PT - May 23, 2022 at 1:00 AM PT</span></h3> -->
<?php
    }
    //Custom-4 end
    if ($completion->is_enabled()) {
        // This value tracks whether there has been a dynamic change to the page.
        // It is used so that if a user does this - (a) set some tickmarks, (b)
        // go to another page, (c) clicks Back button - the page will
        // automatically reload. Otherwise it would start with the wrong tick
        // values.
        echo html_writer::start_tag('form', array('action'=>'.', 'method'=>'get'));
        echo html_writer::start_tag('div');
        echo html_writer::empty_tag('input', array('type'=>'hidden', 'id'=>'completion_dynamic_change', 'name'=>'completion_dynamic_change', 'value'=>'0'));
        echo html_writer::end_tag('div');
        echo html_writer::end_tag('form');
    }

// Course wrapper start.
echo html_writer::start_tag('div', ['class' => 'course-content']);

// Make sure that section 0 exists (this function will create one if it is missing).
course_create_sections_if_missing($course, 0);

// Get information about course modules and existing module types.
// format.php in course formats may rely on presence of these variables.
$modinfo = get_fast_modinfo($course);
$modnames = get_module_types_names();
$modnamesplural = get_module_types_names(true);
$modnamesused = $modinfo->get_used_module_names();
$mods = $modinfo->get_cms();
$sections = $modinfo->get_section_info_all();

// CAUTION, hacky fundamental variable defintion to follow!
// Note that because of the way course fromats are constructed though
// inclusion we pass parameters around this way.
$displaysection = $section;

    //Custom-5 start
    //Customization sameer for course page module heading links redirect to lesosn page
    if(isset($_GET['id'])){
        foreach($modinfo->sections as $val){
            //$urlIdGetParamArr[]=$val[0];    
        $cmIdObj = $DB->get_record('course_modules', array('id'=>$val[0],'deletioninprogress'=>0), 'module,section');
            if($cmIdObj->module == 1) $modName[] = "assign";
            if($cmIdObj->module == 3) $modName[] = "book";
            if($cmIdObj->module == 15) $modName[] = "page";
            if($cmIdObj->module == 16) $modName[] = "quiz";
            if($cmIdObj->module != 1 && $cmIdObj->module != 3 && $cmIdObj->module != 15 && $cmIdObj->module != 16) $modName[] = 0;
            $cmSecObj = $DB->get_record('course_sections', array('id'=>$cmIdObj->section), 'sequence');
            if($cmSecObj->sequence != ''){
                $arrSeq = explode(",", $cmSecObj->sequence);
                if(!empty($arrSeq)){
                    $urlIdGetParamArr[]=$arrSeq[0];
                }
                else{
                    $urlIdGetParamArr[]=$cmSecObj->sequence;
                }
            }
            else{
                if($course->id == 128){ $modName[] = "book"; $urlIdGetParamArr[]='1971';}else{                $urlIdGetParamArr[]=0;}
            }       
        }
        $encIdGetParams = json_encode($urlIdGetParamArr);
        $encIdGetmodNameParams = json_encode($modName);
    }
?>
    
<?php
//Custom-5 end

// Include course AJAX.
include_course_ajax($course, $modnamesused);

// Include the actual course format.
require($CFG->dirroot .'/course/format/'. $course->format .'/format.php');
// Content wrapper end.

echo html_writer::end_tag('div');
   //Custom-6 start
    if($flagAccessLevel2 == 2){
        //1069 start
        //----------- Prerequisite Notice Content - - - - - - - -//
        if(empty($_SESSION['once_per_login_pre_course'.$course->id])) {
            include_once("prereq_comp_for_online_course.php");
            $_SESSION['once_per_login_pre_course'.$course->id] = 2; 
        }
        //----------- Prerequisite Notice Content - - - - - - - -//
        // 1069 end
    }
    //Custom-6 end
// Trigger course viewed event.
// We don't trust $context here. Course format inclusion above executes in the global space. We can't assume
// anything after that point.
course_view(context_course::instance($course->id), $section);

// If available, include the JS to prepare the download course content modal.
if ($candownloadcourse) {
    $PAGE->requires->js_call_amd('core_course/downloadcontent', 'init');
}

// Load the view JS module if completion tracking is enabled for this course.
$completion = new completion_info($course);
if ($completion->is_enabled()) {
    $PAGE->requires->js_call_amd('core_course/view', 'init');
}

echo $OUTPUT->footer();

$isadmin = is_siteadmin($USER);
if (!(!isloggedin() or isguestuser())) { ?>
    <script>
$('#section-0').children('.content').children('.summary').children('.no-overflow').children('p').children('a').find('img').attr('src','./qsys_register_header_fpo.gif').hide();
$('#section-0').children('.content').children('.summary').children('.no-overflow').children('a').find('img').attr('src','./qsys_register_header_fpo.gif').hide();
$(".contentafterlink").remove();
//$('a').children('img').attr('src','./qsys_register_header_fpo.gif').hide();
    </script> 
    <?php
}
    ?>
<script>
//Customization for course page module heading links redirect to lesosn page
var isAdmins = "<?=$isadmin?>";
if(isAdmins){
$(".contentafterlink div div").remove();
}
var courseid = "<?=$course->id?>";


var urlIdGetParamArr = [];
urlIdGetParamArr = <?=$encIdGetParams?>;
urlIdGetParamModNameArr = <?=$encIdGetmodNameParams?>;
console.log(urlIdGetParamArr);
        $("h3.sectionname a").each(function(i){
                //console.log("Testing " + i);
                //console.log(urlIdGetParamArr[i]); 
if(urlIdGetParamArr[i-1] == 186)
                $(this).attr("href","/mod/book/view.php?id=249");
else if(urlIdGetParamArr[i-1] == 187)
    $(this).attr("href","/mod/book/view.php?id=126");
else if(urlIdGetParamArr[i-1] == 126)
    $(this).attr("href","/mod/book/view.php?id=131");
else if(urlIdGetParamArr[i-1] == 131)
    $(this).attr("href","/mod/book/view.php?id=133");
else if(urlIdGetParamArr[i-1] == 178)
    $(this).attr("href","/mod/quiz/view.php?id=177");
else if(urlIdGetParamArr[i-1] == 75)
    $(this).attr("href","/mod/book/view.php?id=100");
else
    $(this).attr("href","/mod/"+urlIdGetParamModNameArr[i-1]+"/view.php?id="+urlIdGetParamArr[i-1]);
if(courseid == 7){
    $(this).attr("href","/mod/"+urlIdGetParamModNameArr[i]+"/view.php?id="+urlIdGetParamArr[i]);
}
if(urlIdGetParamModNameArr[i-1] == 0){
    $(this).attr("href","/mod/"+urlIdGetParamModNameArr[i]+"/view.php?id="+urlIdGetParamArr[i]);
}

if(courseid==128){
$("#show_h3_1 span a").attr("href","/mod/book/view.php?id=1971");
}                
if(courseid==128){
$("#show_h3_7 span a").attr("href","/mod/book/view.php?id=2089");
}      
    if(i==0){$(this).attr("href","javascript:void(0);");}
    if(isAdmins == ""){
        $('.section .img-text').hide();
        if($(this).html() == "Exit" || $(this).html() == "Final Exam" && courseid != 57){
        $("#section-"+i).remove();
        }
        if(courseid == 57){
        $("#section-2").remove();
        }
        if($(this).html() == "Booking"){
        $(this).attr("href","/mod/reservation/view.php?id="+urlIdGetParamArr[i-1]);
        }
    }
        });
$("#region-main").removeClass("span9");
    $("#region-main").addClass("span12");
$("#completionprogressid").hide();
$(".editing_highlight").remove();
//$(".editing_showhide").remove();
var htmlcontent = "<h1 class='sectionname custom_seo_head_course'>"+$('#hide_h3_0').html()+"<h1>";
$("#section-0 .content h3").hide();
$("#section-0 .content").prepend(htmlcontent);
//alert("Test");

    </script>
            <style type="text/css">
        .topics .summary .no-overflow p {
    line-height: 20px;
}

.activity-information{
    display: none !important;
}
.topics .summary .no-overflow .threecol h4 {
    line-height: 20px;
}
.course-content ul.topics li {
    margin-bottom: 0 !important;
}


.course-content ul.topics li.something {
 margin-bottom: 20px !important;
 margin-right: 3.4% !important;
 width: 31% !important;
 border-radius: 5px !important;
}
.course-content ul li.section.main {
 border-bottom: 1px solid #ddd;
 margin-top: 25px;
}
.something .sectionname {
 background: transparent;
}
h1.sectionname{
font-size: 23px;
padding: 0px;
}
.sectionname a:focus {
 box-shadow: none;
 background: transparent;
}
#region-main-box.col-12 {
 border: 1px solid #ddd;
 margin-bottom: 30px;
 padding: 0;
}
.course-content .summary .container {
 display: inline-block;
}


#memberModal .modal-content {
 border-radius: 5px;
}
#memberModal {
 max-width: 100%;
 height: auto;
}
#memberModal .modal-header {
 background-color: transparent;
 border-bottom: none !important;
 padding: 0;
}
#memberModal .modal-header {
 background-color: transparent;
 border-bottom: 1px solid #dee2e6 !important;
 padding: 0 0 15px !important;
}
#memberModal button.close {
 position: absolute;
 right: 14px;
 top: 5px;
}

div#memberModal .modal-dialog {
 max-width: 100% !important;
 transform: inherit !important;
 padding: 0 !important;
 margin: 0 !important;
}

.menu[aria-hidden="true"] {
    display: none !important;
}

a.toggle-display.textmenu:after {
    border-left: 1px solid #0b77b8;
    border-top: 1px solid #0b77b8;
    width: 6px;
    content: '';
    height: 6px;
    transform: rotate(224deg);
    margin-top: 11px;
    margin-right: 0px;
    float: right;
    margin-left: 4px;
}

@media (max-width: 767px){
.course-content ul.topics li.something {
 margin-right: 3% !important;
 width: 48% !important;
}
}
@media (max-width: 600px){
.course-content ul.topics li.something {
 margin-right: 0% !important;
 width: 100% !important;
}
}

#page-course-view-topics .modal {
    left: inherit!important;
    margin-left: 0 !important;
    width: 80% !important;
}


#page-course-view-topics .modal.moodle-has-zindex {
    width: 100% !important;
}
.modal-dialog.modchooser.modal-lg {
    max-width: 1000px !important;
}
.modal-header h5.modal-title{
    display: inline-flex;
}
#region-main-box {
    display: flex;
}

.path-course-view #page-second-header{
    display: none !important;
}

.course-content ul.topics li#section-0 .summary {
  margin: 0;
}
    </style>

<script type="text/javascript">
    
//$('.section .img-text').hide();

$(".outercont").removeClass("container");
$(".outercont").addClass("container-fluid");

$('.topics > li:not(:first-child)').addClass('something');



equalheight = function(container){
var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 $(container).each(function() {

   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;

   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}

// $(document).ready(function(){
//   equalheight('.topics .something');
// });


</script>

    <?php 
    if(empty($isadmin)){
?>
<script type="text/javascript">
    $(document).ready(function(){
  equalheight('.topics .something');
 });
    $("#region-main-box").css("max-width","100%");
    $("#region-main-box").css("flex","0 0 100%");
    $("#region-main-box").css("flex","0 0 100%");
$('#inst5').remove();

</script>
<?php
    }else{
        ?>

<script type="text/javascript">

    //  $(".moveup").html('<i class="icon fa fa-arrow-up fa-fw " title="Move up" aria-label="Move up"></i>');
    //  $(".movedown").html('<i class="icon fa fa-arrow-down fa-fw " title="Move down" aria-label="Move Down"></i>');
    // $(".editing_showhide").html('<i class="icon fa fa-eye fa-fw" title="Hide" aria-label="Hide"></i>');
    //  $(".editing_delete").html('<i class="icon fa fa-trash fa-fw" title="Delete Topic" aria-label="Delete Topic"></i>');
    // $(".edit").html('<i class="icon fa fa-cog fa-fw" title="Edit" aria-label="Edit"></i>');
   // $(".toggle-display").hide();
    $( ".actions" ).find( ".toggle-display" ).show();
    
    // $(".toggle-display").html('<i class="icon fa fa-cog fa-fw" title="Edit" aria-label="Edit"></i>');

    $(document).ready(function(){
    //  equalheight('.topics .something');
  // $(".availabilityinfo.ishidden").remove();
     $("div").removeClass("ad-activity-wrapper");
      $("div").removeClass("activityinstance");
      $("img#action-menu-6-menubar").attr("src","/theme/adaptable/pix/edit_menu.svg");
    });
</script>

<style type="text/css">
    .section .activity .actions {
 position: relative !important;
 right: auto !important;
 width: 100% !important;
 display: block !important;
}



ul.topics li.activity:not(:first-child) {
 width: 100%;
}
ul.topics li.activity {
 width: 100%;
 padding: 5px 0 !important;
 border-bottom: 1px dashed #d8dbde !important;
 background-color: transparent !important;
}
.course-content li.section li.activity ul {
 left: 0;
}
.course-content li.section li.activity ul.menu {
 left: 0 !important;
 width: 200px;
 margin: inherit;
 padding: 2px 10px;
}
.course-content li.section li.activity ul.menu li {
 line-height: normal;
}
.section-cm-edit-actions ul.menubar b.caret, .section_action_menu ul.menubar b.caret {
 display: none;
}
.topics .section .activity .mod-indent-outer {
 margin-left: 0 !important;
}



.activity a:focus {
 background: transparent;
 box-shadow: none;
}
.content .section.img-text {
 padding: 0 10px;
}
.course-content li.section li.activity ul.menubar {
 top: 0;
 position: relative;
}
.course-content li.section li.activity ul.menu {
 top: 30px;
}
.section-modchooser .btn {
 padding: 0;
 border: 0 none !important;
}
.section-modchooser .btn:focus, .section-modchooser .btn:hover {
 border: 0 !important;
 box-shadow: none;
 text-decoration: none;
}
.course-content .topics li.section {
 overflow: inherit;
}



.section_action_menu .menu {
 width: 150px !important;
 padding: 5px 10px !important;
 top: 30px;
 right: 10px;
}
.section_action_menu .menu li {
 line-height: normal;
}
.section_action_menu .menubar {
 top: 4px;
 right: 10px;
}
.hidden .sectionname{
    display: none !important;
}

body.editing .topics ul.section .editing_move.moodle-core-dragdrop-draghandle {
    cursor: move;
    z-index: 9;
    top: 7px;
}

.path-course-view #page-second-header {
  display: none !important;
}
</style>

<?php
    }
exit;
    ?>