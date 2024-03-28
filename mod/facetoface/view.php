<style type="text/css">
    #region-main-box #region-main {
    border: 1px solid #ddd !important;
}
.container {
    display: inline-block;
}
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
 * Copyright (C) 2007-2011 Catalyst IT (http://www.catalyst.net.nz)
 * Copyright (C) 2011-2013 Totara LMS (http://www.totaralms.com)
 * Copyright (C) 2014 onwards Catalyst IT (http://www.catalyst-eu.net)
 *
 * @package    mod
 * @subpackage facetoface
 * @copyright  2014 onwards Catalyst IT <http://www.catalyst-eu.net>
 * @author     Stacey Walker <stacey@catalyst-eu.net>
 * @author     Alastair Munro <alastair.munro@totaralms.com>
 * @author     Aaron Barnes <aaron.barnes@totaralms.com>
 * @author     Francois Marier <francois@catalyst.net.nz>
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once('lib.php');
require_once('renderer.php');
//-- -- - - - - - - Start - Feature Request: Auto-Unenrolled Students   --//
require_once("$CFG->dirroot/enrol/locallib.php");
//-- -- - - - - - - End - Feature Request: Auto-Unenrolled Students   --//
global $DB, $OUTPUT, $USER;

$id = optional_param('id', 0, PARAM_INT); // Course Module ID.
$f = optional_param('f', 0, PARAM_INT); // Facetoface ID.
$location = optional_param('location', '', PARAM_TEXT); // Location.
$region = optional_param('region', '', PARAM_TEXT); // Region.
$session_type = "Public";
$download = optional_param('download', '', PARAM_ALPHA); // Download attendance.
//59 Est Instructor Feature start
$crole = $_REQUEST['crole'];

//US #824 start 
$_SESSION['cntEnrol'] = 0;
//US #824 end
//59 Est Instructor Feature end

if ($id) {
    if (!$cm = $DB->get_record('course_modules', array('id' => $id))) {
        print_error('error:incorrectcoursemoduleid', 'facetoface');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('error:coursemisconfigured', 'facetoface');
    }
    if (!$facetoface = $DB->get_record('facetoface', array('id' => $cm->instance))) {
        print_error('error:incorrectcoursemodule', 'facetoface');
    }
} else if ($f) {
    if (!$facetoface = $DB->get_record('facetoface', array('id' => $f))) {
        print_error('error:incorrectfacetofaceid', 'facetoface');
    }
    if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
        print_error('error:coursemisconfigured', 'facetoface');
    }
    if (!$cm = get_coursemodule_from_instance('facetoface', $facetoface->id, $course->id)) {
        print_error('error:incorrectcoursemoduleid', 'facetoface');
    }
} else {
    print_error('error:mustspecifycoursemodulefacetoface', 'facetoface');
}

$context = context_module::instance($cm->id);
$PAGE->set_url('/mod/facetoface/view.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_pagelayout('standard');
// US #5635 
$viewattendees_cap = has_capability('mod/facetoface:viewattendees', $context);
$editsessions_cap = has_capability('mod/facetoface:editsessions', $context);

$coursecontext = context_course::instance($course->id);
$roles = get_user_roles($coursecontext, $USER->id);
$userRoles = array();
foreach ($roles as $role) {
    $userRoles[] = $role->shortname;
}

$hasregionaladminrole = 0;
if($USER->usertype == 'regionaladmin'){
    $hasregionaladminrole = 1;
} else if(in_array('regionaladmin', $userRoles)){
    $hasregionaladminrole = 1;
}

//59 Est Instructor Feature start
if ($crole == "instructor" && !$hasregionaladminrole) {
    $fieldid = $DB->get_field('facetoface_session_field', 'id', array('shortname' => 'managersemail'));
    if ($fieldid) {
        $sqlSessData = "SELECT fsd.id FROM {facetoface_session_data} fsd 
                        JOIN {facetoface_sessions} fs ON fsd.sessionid = fs.id 
                        JOIN {facetoface} f ON f.id = fs.facetoface
                        where f.id=$f and fsd.fieldid=" . $fieldid . " AND fsd.data LIKE '%" . $USER->username . "%' order by id desc LIMIT 0,1";
        $rsSessData = $DB->get_record_sql($sqlSessData);
        if (!$rsSessData) {
            redirect("/my", "You hit wrong URL");
        }
    }
}
//59 Est Instructor Feature end


if (!empty($download)) {
    require_capability('mod/facetoface:viewattendees', $context);
    facetoface_download_attendance($facetoface->name, $facetoface->id, $location, $download);
    exit();
}
if (!empty($USER->id) && $USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $USER->id != 1) {
    $clsssroomCourse = $DB->get_record_sql("Select * FROM `mdl_course` WHERE  NOT EXISTS (SELECT * FROM `mdl_course_modules`  WHERE  module IN(3,16) AND mdl_course.id= mdl_course_modules.course ) AND mdl_course.id= " . $course->id);
    if (isset($clsssroomCourse) && !empty($clsssroomCourse)) {
        $insertSql = "INSERT INTO classroom_autoenrolled_delete (user_id, course_id, page) VALUES (" . $USER->id . "," . $course->id . ", 'facetoface_view')";
        $DB->execute($insertSql);
    }
}

require_course_login($course, true, $cm);

require_capability('mod/facetoface:view', $context);

// Logging and events trigger.
$params = array(
    'context' => $context,
    'objectid' => $facetoface->id
);
//Classroom Feature start
?>
<script type="text/javascript" src="/mod/book/sample/js/jquery.1.7.1.js"></script>
<script src="/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<!-- <input type="hidden" id="refreshed" value="no">
<script type="text/javascript">
    onload=function(){
    var e=document.getElementById("refreshed");
    if(e.value=="no")e.value="yes";
    else{e.value="no"; location.reload();} 
    }
</script>  -->
<?php
//Classroom Feature end
//PP2 start 
//PP2 end
$event = \mod_facetoface\event\course_module_viewed::create($params);
$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('facetoface', $facetoface);
$event->trigger();

$title = $course->shortname . ': ' . format_string($facetoface->name);

$PAGE->set_title($title);
$PAGE->set_heading($course->fullname);

$pagetitle = format_string($facetoface->name);

$f2frenderer = $PAGE->get_renderer('mod_facetoface');

$completion = new completion_info($course);
if (isset($cm) && !empty($cm)) {
    $moduleId = $cm->module;
    $moduleName = $cm->modname;
    if ($moduleId != 27 && 27 != $moduleId) {
        $completion->set_module_viewed($cm);
    }
}
//-- -- - - - - - - Start - Feature Request: Auto-Unenrolled Students -Nav  --//
//Task 2177 start
$ajax_flag = 0;
//Task 2177 end
//US 5635 : condition update
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $USER->id != 1 && !($editsessions_cap)) {
    if (isset($cm) && !empty($cm)) {
        $moduleId = $cm->module;
        $moduleName = $cm->modname;
        if ($moduleId == 27 && 27 == $moduleId && $course->id != 7) {
            $sqlSCertificate = $DB->get_record_sql("SELECT sci.id FROM mdl_simplecertificate_issues as sci LEFT JOIN mdl_simplecertificate as sc ON sc.id= sci.certificateid WHERE sc.course =" . $course->id . " AND sci.userid = " . $USER->id);
            if (empty($sqlSCertificate)) {

                $sqlSSignup = "SELECT DISTINCT d.id AS ssignup_ids
                FROM {facetoface} f
                JOIN {facetoface_sessions} s ON s.facetoface = f.id
                JOIN {facetoface_signups} d ON d.sessionid = s.id
                WHERE f.id = ? AND d.userid = ?";

                $recordsSSignup = $DB->get_records_sql($sqlSSignup, array($facetoface->id, $USER->id));
                if (empty($recordsSSignup)) {
                    //Task 2177 start Add variable 
                    $unenrol_flag_return = unenrolled_classroom_session($course->id, $USER, 0);
                    $ajax_flag = 1;
                    if ($unenrol_flag_return !== 5) {
                        //Task 2177 end Add variable
                        $sqlDel = "DELETE FROM {course_completions} WHERE userid =" . $USER->id . " AND course =" . $course->id;
                        $sqlDelRes = $DB->execute($sqlDel);
                        //Task 2177 start
                    }
                    //Task 2177 end
                } else {
                    $recordsSSignupId = "";
                    foreach ($recordsSSignup as $SSvalue) {
                        $recordsSSignupId .= $SSvalue->ssignup_ids . ",";
                    }
                    $recordsSSignupId = rtrim($recordsSSignupId, ",");

                    $sqlSessionStatus = $DB->get_record_sql("SELECT id, statuscode FROM {facetoface_signups_status} WHERE signupid IN (" . $recordsSSignupId . ") AND superceded = 0  ORDER BY timecreated DESC");

                    if (!empty($sqlSessionStatus)) {

                        $SS_statuscode = $sqlSessionStatus->statuscode;
                        if ($SS_statuscode == 10 || $SS_statuscode == 20 || $SS_statuscode == 30 || $SS_statuscode == 40 || $SS_statuscode == 50 || $SS_statuscode == 80 || $SS_statuscode == 90 || $SS_statuscode == 100) {
                            //Task 2177 start Add variable 
                            $unenrol_flag_return = unenrolled_classroom_session($course->id, $USER, 0);
                            $ajax_flag = 1;
                            if ($unenrol_flag_return !== 5) {
                                //Task 2177 end Add variable
                                $sqlDel = "DELETE FROM {course_completions} WHERE userid =" . $USER->id . " AND course =" . $course->id;
                                $sqlDelRes = $DB->execute($sqlDel);
                                //US #824 start add variable.
                                $flagUnenroll = 1;
                                //Task 2177 start
                            }
                            //Task 2177 end
                        }
                    }
                }
            }
        }
    }
}
//-- -- - - - - - - End - Feature Request: Auto-Unenrolled Students -Nav  --//

echo $OUTPUT->header();

//US #824 start loop update code
if ($USER != 1) {
    if (empty($cm->visible) and $flagUnenroll != 1 and!has_capability('mod/facetoface:viewemptyactivities', $context)) {
        notice(get_string('activityiscurrentlyhidden'));
    }
} else {
    if (empty($cm->visible) and!has_capability('mod/facetoface:viewemptyactivities', $context)) {
        notice(get_string('activityiscurrentlyhidden'));
    }
}
echo $OUTPUT->box_start();
?>
<link href="/theme/meline29/style/classroom.css?v=1.6" type="text/css" rel="stylesheet">
<!--One Page Work Start Here-->
<div class="classroom-whole-section page-two1">


    <?php
    $flags = "1kqwe";
//PP2 start 
    if (isset($flags)) {
        //PP2 end
        $courseAssignCNTSQL = 'SELECT count(id) as cntexam FROM {assign} WHERE course =' . $course->id;
        $courseAssignCNTRes = $DB->get_record_sql($courseAssignCNTSQL);
        //echo "<pre>"; print_r($courseAssignCNTRes->cntexam); exit;

        $courseAssignSQL = 'SELECT id FROM {assign} WHERE course =' . $course->id . ' and type ="classroom"';
        $courseAssignRes = $DB->get_record_sql($courseAssignSQL);

        if (!empty($courseAssignRes) && $courseAssignCNTRes->cntexam == 1) {

            //59 Est Instructor Feature start
            if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $crole != "instructor") {

                //1069 changes and delete previous code from here which is coming the above condition
                //Add new code
                $flagAccessLevel2 = 0;
                $coursesDataSQL = 'SELECT * FROM {course_completion_criteria} WHERE course =' . $course->id . ' AND criteriatype = 8 order by courseinstance desc';
                $coursesData = $DB->get_records_sql($coursesDataSQL);
                if ($coursesData) {
                    foreach ($coursesData as $keyCD) {
                        if ($keyCD->courseinstance != '') {
                            $sqlm00421 = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid =' . $USER->id . ' AND (sci.timeexpired > ' . time() . ' || sci.timeexpired=99) AND sc.course = ' . $keyCD->courseinstance;
                            $sqlm00421Res = $DB->get_record_sql($sqlm00421);
                            if ($sqlm00421Res->cntcert > 0) {
                                $flagAccessLevel2 = 1;
                            }
                        }
                    }
                }

                //1069 start AND combination
                $sqlCCC = 'SELECT ccc.* FROM {course_completion_aggr_methd} ccam JOIN {course_completion_criteria} ccc ON ccam.course=ccc.course WHERE ccc.course =' . $course->id . ' AND ccc.course != 42 AND ccam.criteriatype = 8 AND ccam.method = 1 order by ccam.id desc';
                $cccRes = $DB->get_records_sql($sqlCCC);
                if (!empty($cccRes)) {
                    foreach ($cccRes as $cccObj) {
                        if ($cccObj->courseinstance != '') {
                            $sqlmCCC = 'SELECT count(sci.id) cntcert FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid=sc.id WHERE sci.userid =' . $USER->id . ' AND (sci.timeexpired > ' . time() . ' || sci.timeexpired=99) AND sc.course = ' . $cccObj->courseinstance;
                            $sqlmCCCRes = $DB->get_record_sql($sqlmCCC);
                            if ($sqlmCCCRes->cntcert == 0) {
                                $flagAccessLevel2 = 0;
                                $getInCompPreCourseId[] = $cccObj->courseinstance;
                            }
                        }
                    }
                }
                if ($flagAccessLevel2 == 0) {
                    //----------- Prerequisite Notice Content Nav  - - - - - - - -//
                    //- Start- Feature Request: Limit the Prereq Warning Message- Customized by Naveen- - - -//
                    if (empty($_SESSION['once_per_login_pre' . $course->id])) {
                        include_once("../../course/prereq_comp.php");
                        $_SESSION['once_per_login_pre' . $course->id] = 2;
                    }
                    //- End- Feature Request: Limit the Prereq Warning Message- Customized by Naveen- - - -//
                    //----------- Prerequisite Notice Content Nav  - - - - - - - -//
                }
                //Level 2 Submission Restriction end
                //1069 end changes and delete previous code from here which is coming the above condition
            }
        }
        //PP2 start
    }

    if ($facetoface->intro) {
        echo $OUTPUT->box_start('generalbox', 'description');
        echo format_module_intro('facetoface', $facetoface, $cm->id);
        echo $OUTPUT->box_end();
    } else {
        echo html_writer::empty_tag('br');
    }

    //59 Est Instructor Feature Start
    if (!is_siteadmin($USER) && $crole != "instructor") {
        //59 Est Instructor Feature End  
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                //alert("test"); 
                $("#region-main").removeClass('span9');
                $("#region-main").addClass('span12');
            });
        </script>
        <?php
    } else {
        $cls = "resize-col";
    }
    ?>
    <div class="class-room-sessions <?= $cls ?>">
        <?php
//Upcoming Session dynamic function
        $regions = get_region($facetoface->id, $session_type);
        if (count($regions) > 0) {
            echo html_writer::start_tag('form', array('action' => 'view.php', 'method' => 'get', "id" => "resionsForm", 'class' => "mform"));
            echo html_writer::start_tag('div', array("class" => "dropdownV"));
            echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'f', 'value' => $facetoface->id));
            if (isset($_REQUEST['as'])) {
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'as', 'value' => $_REQUEST['as']));
            }
            if (isset($_REQUEST['crole'])) {
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'crole', 'value' => $_REQUEST['crole']));
            }
            if (isset($_REQUEST['sc'])) {
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sc', 'value' => $_REQUEST['sc']));
            }
            echo html_writer::select($regions, 'region', $region, '');
            echo html_writer::end_tag('div') . html_writer::end_tag('form');
        }

        //Upcoming Session dynamic function
        print_session_list($course->id, $facetoface->id, $region, false);   // add $resion variable.
// - - - - - - - - End -Feature Request: In-Classroom Location Filter -Nav - - - - -- //
        ?>
    </div>
</div>  
<!--One Page Work Ends Here-->
<?php
echo $OUTPUT->box_end();
echo $OUTPUT->footer($course);

function print_session_list($courseid, $facetofaceid, $location, $flag = false) {
    global $CFG, $USER, $DB, $OUTPUT, $PAGE;
    //US #824
    $totalSessEnrolled = 0;

    $f2frenderer = $PAGE->get_renderer('mod_facetoface');

    $timenow = time();

    $context = context_course::instance($courseid);
    $viewattendees = has_capability('mod/facetoface:viewattendees', $context);
    $editsessions = has_capability('mod/facetoface:editsessions', $context);

    $bookedsession = null;
    $submissions = facetoface_get_user_submissions($facetofaceid, $USER->id);
    if ($submissions) {
        //US #824
        $bookedsession = $submissions;
    }
    //US #824 start
    $bookedsessionArr = array();
    if (is_array($bookedsession) || is_object($bookedsession)) {
    foreach ($bookedsession as $keyBS) {
        $bookedsessionArr[] = $keyBS;
    }
    }
    $bookedsession = $bookedsessionArr;
    //US #824 end
    $customfields = facetoface_get_session_customfields();

    $upcomingarray = array();
    $previousarray = array();
    $upcomingtbdarray = array();
    $ongoingsession = array();
    //US #5635 : Task 5842 get loggedin userrole
    $userRoles = array();
    $roles = get_user_roles($context, $USER->id);
    foreach ($roles as $role) {
        $userrole = $role->name;
        $userRoles[] = $role->shortname;
    }

    $hasregionaladminrole = 0;
    $hasinstructorrole    = 0;
    if($USER->usertype == 'regionaladmin'){
        $hasregionaladminrole = 1;
    }else if(in_array('regionaladmin', $userRoles)){
        $hasregionaladminrole = 1;
    }else if(in_array('qscinstructor', $userRoles) || in_array('repinstructor', $userRoles)){
        $hasinstructorrole = 1;
    }

    if ($sessions = facetoface_get_sessions($facetofaceid, $location,null, null, $userRoles)) {

        foreach ($sessions as $session) {
            $flagPrev = 0;

            $sessionstarted = false;
            $sessionfull = false;
            $sessionwaitlisted = false;
            $isbookedsession = false;

            $sessiondata = $session;
            $sessiondata->bookedsession = $bookedsession;

            // Add custom fields to sessiondata.
            $customdata = $DB->get_records('facetoface_session_data', array('sessionid' => $session->id), '', 'fieldid, data');
            $sessiondata->customfielddata = $customdata;

            // Is session waitlisted.
            if (!$session->datetimeknown) {
                $sessionwaitlisted = true;
            }

            // Check if session is started.
            $sessionstarted = facetoface_has_session_started($session, $timenow);
            if ($session->datetimeknown && $sessionstarted && facetoface_is_session_in_progress($session, $timenow)) {
                $sessionstarted = true;
                //PP1 start
                $ongoingsession[] = $sessiondata;
                $flagPrev = 1;
                //PP1 end
            } else if ($session->datetimeknown && $sessionstarted) {
                $sessionstarted = true;
            }

            // Put the row in the right table.
            if ($sessionstarted && $flagPrev == 0) {
                $previousarray[] = $sessiondata;
            } else if ($sessionwaitlisted) {
                $upcomingtbdarray[] = $sessiondata;
            } elseif ($flagPrev == 0) { // Normal scheduled session.
                $upcomingarray[] = $sessiondata;
            }
        }
    }
    if ($flag) {
        return $upcomingarray;
    }

    // Upcoming sessions.
    echo "<h4 id='resionsFormh4'>" . get_string('upcomingsessions', 'facetoface') . "</h4>";
    //Grader as a Manager Code Start
    if (empty($upcomingarray) && empty($upcomingtbdarray) && empty($ongoingsession)) {
        //Grader as a Manager Code Start
        if (!empty($location)) {
            print_string('noupcomingInRegion', 'facetoface');
        } else {
            print_string('noupcoming', 'facetoface');
        }
    } else {
        $upcomingarray = array_merge($upcomingarray, $upcomingtbdarray, $ongoingsession);
        $isadmin = is_siteadmin($USER);
        //59 Est Instructor Feature Start 
        //US 5635: Condition update

        if ((!$isadmin && $_REQUEST['crole'] != "instructor" && !($editsessions) && $USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' && $USER->usertype != 'sessioncreator') || ($editsessions &&  in_array('qscinstructor', $userRoles) && $_REQUEST['crole'] != "instructor") ) {

            if (isset($_REQUEST['as'])) {
                if ($_REQUEST['as'] == 'private') {
                    $as = "Private";
                    if ($_REQUEST['sc'] != '') {
                        $sc = $_REQUEST['sc'];
                    } else {
                        $sc = "";
                    }
                } else {
                    $as = "Public";
                }
            } else {
                $as = "Public";
            }
            //US #824
            foreach ($upcomingarray as $upcomingVal) {
                $clogob = '<p><img src="https://dev.training.qsc.com/mod/facetoface/no-image-icon.png" alt="" style="vertical-align:text-bottom;margin:0 .5em;" class="img-responsive" height="100" width="180"><br /></p>';
                $accessType = $upcomingVal->customfielddata[11]->data;

                if ($upcomingVal->customfielddata[12])
                    $secCode = $upcomingVal->customfielddata[12]->data;
                else {
                    $secCode = "";
                }
                if (($as == $accessType && $secCode == $sc)) {
                    # code...            
                    $timestartStr = $upcomingVal->sessiondates[0]->timestart;
                    $timefinishStr = $upcomingVal->sessiondates[0]->timefinish;
                    $datestartchk = date('d m y', $timestartStr);
                    $dateendchk = date('d m y', $timefinishStr);
                    /* Customization for date formate start by - pawan 27Nov */
                    require("session_date_format.php");
                    /* Customization for date formate end by - pawan 27Nov  */

                    $timestart = date('h:ia', $timestartStr);
                    $timefinish = date('h:ia', $timefinishStr);
                    $ctext = $upcomingVal->cdetails[1];

                    $clogo = str_replace(".png", ".png?f=1", $ctext, $count);
                    if ($count > 0)
                        $clogob = $clogo;
                    $clogo = str_replace(".jpg", ".jpg?f=1", $ctext, $count);
                    if ($count > 0)
                        $clogob = $clogo;
                    $clogo = str_replace(".jpeg", ".jpeg?f=1", $ctext, $count);
                    if ($count > 0)
                        $clogob = $clogo;
                    $clogo = str_replace(".gif", ".gif?f=1", $ctext, $count);
                    if ($count > 0)
                        $clogob = $clogo;
                    ?>
                                                                <!-- Customization start   -->
                                                                <div class="page2_second-section pg_one"> 
                    <?= $clogob ?>
                                                                    <div class="address-details-classroom">
                                                                        <span class="intitutionname"><?= $upcomingVal->customfielddata[6]->data ?></span> 
                    <?php
                    if ($upcomingVal->sessiondates[0]->timestart == "2481889900" && $upcomingVal->sessiondates[0]->timefinish == "2481889900") {
                        $sessionDateTimeFlag = 0;
                    } else {
                        $sessionDateTimeFlag = 1;
                    }
                    if ($sessionDateTimeFlag == 1) {
                        if ($dateend != '' && $sessionDateTimeFlag == 1) {
                            ?>
                                                                                                <span class="day-date-classroom"><span style="margin-right:44px;"><?php echo strtoupper(get_string('date', 'facetoface')) . ":" ?></span><?= $datestart ?>- <?= $dateend ?></span>
                        <?php } elseif ($sessionDateTimeFlag == 1) { ?>
                                                                                                <span class="day-date-classroom"><span style="margin-right:42px;"><?php echo strtoupper(get_string('date', 'facetoface')) . ":" ?></span><?= $datestart ?></span>
                        <?php } ?>
                                                                                    <span class="time-classroom"><span style="margin-right:48px;"><?php echo strtoupper(get_string('time', 'facetoface')) . ":" ?></span><?= $timestart ?> - <?= $timefinish ?></span> 

                                                                                    <span class ='time-classroom'><span style="margin-right:16px;"><?php echo strtoupper(get_string('time_length', 'facetoface')) . " : " ?></span>
                        <?php echo $upcomingVal->customfielddata[10]->data; ?></span>
                    <?php } ?>                    
                                                                        <span class="address-full"><span style="margin-right:10px;"><?php echo strtoupper(get_string('location', 'facetoface')) . ":" ?></span><?= $upcomingVal->customfielddata[1]->data ?></span>
                                                                        <br>  
                                                                        <span class="address-full"><span style="margin-right:15px;"><?php echo strtoupper(get_string('address', 'facetoface')) . ":" ?></span><?= $upcomingVal->customfielddata[2]->data ?></span>                      

                                                                    </div>

                                                                    <div class="reserve-btn-seats">

                                                                        <!-- Customization end   -->


                    <?php
                    $timenow = time();
                    $isbookedsession = false;
                    $sessionstarted = false;
                    $bookedsession = $upcomingVal->bookedsession;
                    //US #824 start
                    $_SESSION['cntEnrol'] = count($upcomingarray[0]->bookedsession);
                    foreach ($upcomingVal->bookedsession as $bookedVal) {
                        # code...
                        if ($upcomingVal->id == $bookedVal->sessionid) {
                            $isbookedsession = 2;
                            $signupstatus = facetoface_get_status($bookedVal->statuscode);
                            $status = get_string('status_' . $signupstatus, 'facetoface');
                        }
                    }

                    //US #824 end
                    $status = get_string('bookingopen', 'facetoface');
//PP1 start
                    $sessionInProgressFlag = 0;
//PP1 end
                    if ($upcomingVal->datetimeknown && facetoface_has_session_started($upcomingVal, $timenow) && facetoface_is_session_in_progress($upcomingVal, $timenow)) {
//PP1 start
                        $sessionInProgressFlag = 1;
//PP1 end
                        $status = get_string('sessioninprogress', 'facetoface');
                        $sessionstarted = true;
                    } else if ($upcomingVal->datetimeknown && facetoface_has_session_started($upcomingVal, $timenow)) {
                        $status = get_string('sessionover', 'facetoface');
                        $sessionstarted = true;
                    } else if ($bookedsession && $upcomingVal->id == $bookedsession->sessionid) {
                        //US #824 comment below code
                        // $signupstatus = facetoface_get_status($bookedsession->statuscode);
                        // $status = get_string('status_' . $signupstatus, 'facetoface');
                        // $isbookedsession = 2;
                    } else if ($signupcount >= $upcomingVal->capacity) {
                        $status = get_string('bookingfull', 'facetoface');
                        $sessionfull = true;
                    }
                    $options = '';
                    if ($viewattendees && $_REQUEST['crole'] == 'instructor') {
                        $options .= html_writer::link('attendees.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('attendees', 'facetoface'), array('title' => get_string('seeattendees', 'facetoface'))) . " ";
                    }

                    // Customization Sameer
                    //CCC start
                    $sql61 = "SELECT * FROM {assign} where course=$courseid and type='classroom'";
                    $rs61 = $DB->get_record_sql($sql61);
                    if (!empty($rs61)) {
                        $sql612 = "SELECT * FROM {assign_submission} where assignment=$rs61->id and userid=$USER->id and status='submitted'";
                        $rs612 = $DB->get_record_sql($sql612);
                    }
                    $cmObj = $DB->get_record('course_modules', array('course' => $courseid, 'module' => 1, 'instance' => $rs61->id), '*');
                    //CCC ends
                    
                    $cmcObj = $DB->get_record('course_modules_completion', array('userid' => $USER->id, 'coursemoduleid' => $cmObj->id, 'completionstate' => 2), '*');
                    $isadmin = is_siteadmin($USER);
                    if (!$isadmin) {
                        $cmOnlineVerObj = $DB->get_records('course_modules', array('course' => $courseid, 'module' => 3), 'id ASC', 'id');
                        $OVFlag = 0;
                        # check code using certification...
                        $keyCertVal = $DB->get_record('simplecertificate', array('course' => $courseid), "id");
                        $cmcOnlineVerObj = $DB->get_record('simplecertificate_issues', array('userid' => $USER->id, 'certificateid' => $keyCertVal->id), 'id');
                        if (empty($cmcOnlineVerObj)) {
                            $OVFlag = 1;
                        }
                    }
                    // ----- customization_Naveen_Start -------//

                    $cmAssessment = "";
                    $quizAttempedCount = 0;
                    $finishedAttempedCount = 0;
                    $courseData = $DB->get_record('course', array('id' => $courseid));
                    if (!empty($courseData)) {
                        $course_version = $courseData->course_version;
                        if (!empty($course_version) && ($course_version == 'inclassroom_quiz')) {
                            $sql61 = "SELECT * FROM {quiz} where course=$courseid";
                            $rs61 = $DB->get_record_sql($sql61);
                            $cmObj = $DB->get_record('course_modules', array('course' => $courseid, 'module' => 16, 'instance' => $rs61->id), '*');
                            //CCC ends

                            $cmcObj = $DB->get_record('course_modules_completion', array('userid' => $USER->id, 'coursemoduleid' => $cmObj->id, 'completionstate' => 2), '*');
                            $cmAssessment = $DB->get_record('course_modules', array('course' => $courseid, 'module' => 16, 'visible' => 1), 'id,added,instance');
                            if (!empty($cmAssessment)) {
                                $quiz_id = $cmAssessment->instance;
                                $quizAttempts = $DB->get_records_sql("SELECT * FROM mdl_quiz_attempts WHERE quiz = ? AND userid = ? AND (state IN ('finished','overdue') || state IN ('inprogress','abandoned')) ORDER BY attempt ASC", array($quiz_id, $USER->id));
                                if (!empty($quizAttempts)) {
                                    foreach ($quizAttempts as $qakey => $qavalue) {
                                        if (($qavalue->state == 'finished') || $qavalue->state == 'overdue')
                                            $finishedAttempedCount++;
                                    }
                                    $rs6122 = count($quizAttempts);
                                }
                            }
                        }
                    }
                    // ----- customization_Naveen_end -------//
                    //US #824 update logical cond
                    if (($OVFlag == 0 || $_SESSION[$courseid]['ccertstepscomp'] == 'certified') && empty($cmcObj) && count($upcomingVal->bookedsession) > 2) {
                        $options = '<a title="Already access online version" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">' . get_string('alreadyonline', 'facetoface') . '</a>';
                        if (isguestuser()) {
                            $options = html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('reserve', 'facetoface'), array('id' => "res_" . $upcomingVal->id));
                        }
                    } else {
                        //US #824 change in if cond
                        if (($isbookedsession == 2 && empty($cmcObj)) || ($isbookedsession == 2 && !empty($cmcObj))) {
                            if (!empty($rs612) && $cmcObj->completionstate != 2) {
                                $options = '<a title="Already submit classroom exam" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">' . get_string('gradinginprogress', 'facetoface') . '</a>';
                            }
                            // ----- customization_Naveen_Start -------//
                            elseif (!empty($rs6122) && $cmcObj->completionstate != 2) {
                                $options = '<a title="Already submit quiz attempt" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">' . get_string('notapplicable', 'facetoface') . '</a>';
                            } // ----- customization_Naveen_End -------//
                            else {
                            //PP1 start
                                $sessionInProgressFlag = 0;
                            //PP1 end

                                $options .= html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('moreinfo', 'facetoface'), array('title' => get_string('moreinfo', 'facetoface'))) . " ";

                                //US #824 add if cond and else structure
                                if ($upcomingVal->roll_call_status != 1) {
                                    $options .= html_writer::link('cancelsignup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('cancelbooking', 'facetoface'), array('title' => get_string('cancelbooking', 'facetoface')));
                                } else {
                                    $options .= '<a title="Not Applicable" class="notapp" style="display:none;background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">' . get_string('notapplicable', 'facetoface') . '</a>';
                                }
                            }
                        } else if (!$sessionstarted and count($upcomingVal->bookedsession) < 2) { //US #824 change and clause
                            //Classroom Feature start
                            if ($upcomingVal->allowoverbook == 1) {
                                $signupcount = facetoface_get_num_attendees($upcomingVal->id, MDL_F2F_STATUS_APPROVED);
                                $availableStats = $upcomingVal->capacity - $signupcount;
                                if ($availableStats <= 0) {
                                    $options .= html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('reserve_in_waitlist', 'facetoface'), array('id' => "res_" . $upcomingVal->id));
                                } else {
                                    $options .= html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('reserve', 'facetoface'), array('id' => "res_" . $upcomingVal->id));
                                }
                            } else {
                                $options .= html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('reserve', 'facetoface'), array('id' => "res_" . $upcomingVal->id));
                            }
                            //Classroom Feature end
                            //Customization for Import Feature
                            //BUG 4337 add condition bookedsession in last
                            if (!empty($cmcObj) && $cmcObj->completionstate == 2 && count($upcomingVal->bookedsession) >= 2) {
                                $options = '<a title="Not Applicable" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">Already certified</a>';
                            }
                        }//PP1 start
                        else if ($sessionInProgressFlag != 0) {
                            if (!$bookedsession) {
                                //Classroom Feature start
                                $options .= html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('reserve', 'facetoface'), array('id' => "res_" . $upcomingVal->id));
                                //Classroom Feature end
                            }
                            //Customization for Import Feature
                            if (!empty($cmcObj) && $cmcObj->completionstate == 2) {
                                $options = '<a title="Already certified" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">Already certified</a>';
                            }
                        }//PP1 end
                        //US #824 start
                        if (empty($options) && $_SESSION['cntEnrol'] == 2) {
                            $cntSess++;
                            $options = html_writer::link('signup.php?s=' . $upcomingVal->id . '&backtoallsessions=' . $upcomingVal->facetoface, get_string('reserve', 'facetoface'), array('id' => "res_" . $upcomingVal->id, 'class' => 'resapp', 'style' => 'display:none'));
                            $options .= '<a title="Not Applicable" class="notapp" style="display:none;background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">' . get_string('notapplicable', 'facetoface') . '</a>';
                        } elseif ($sessionstarted) {
                            $options = '<a title="Not Applicable" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">' . get_string('notapplicable', 'facetoface') . '</a>';
                        }
                        //US #824 end
                        //US #824 commented above code start
                        // if (empty($options)) {
                        //     //                $options = get_string('none', 'facetoface');
                        //     $options = '<a title="Not Applicable" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">'.get_string('notapplicable', 'facetoface').'</a>';
                        // }
                        //US #824 commented above code end
                    }
                    ?>
                    <?php
                    //US 3009 start
                    $sqlFSH = 'SELECT id, flag FROM {facetoface_session_hold} WHERE session_id=' . $upcomingVal->id;
                    $fshObjData = $DB->get_record_sql($sqlFSH);
                    if (!empty($fshObjData)) {
                        if ($fshObjData->flag == 1) {
                            $options = '<a title="Session Full" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">Session Full</a>';
                        }
                    }
                    //US 3009 end
                    //US 3010 start
                    if ($upcomingVal->allowoverbook == 0) {
                        $signupcount = facetoface_get_num_attendees($upcomingVal->id, MDL_F2F_STATUS_APPROVED);
                        $availableStats = $upcomingVal->capacity - $signupcount;
                        if ($availableStats <= 0) {
                            $options = '<a title="Session Full" style="background:#ececec none repeat scroll 0 0;color:gray !important;" href="javascript:void(0);">Session Full</a>';
                        }
                    }
                    //US 3010 end
                    ?>
                    <?= $options ?>
                    <?php 
if($upcomingVal->customfielddata[5]->data){
$signupcount = facetoface_get_num_attendees($upcomingVal->id, MDL_F2F_STATUS_APPROVED);
$stats = $upcomingVal->capacity - $signupcount;
            if ($viewattendees) {
                //$stats = $signupcount . ' / ' . $session->capacity . 'Seats Left';
        $stats = max(0, $stats). ' '.get_string('seats_left', 'facetoface');   ////Task 2369, converted hardcoded message to dynamic.
            } else {
                $stats = max(0, $stats). ' '.get_string('seats_left', 'facetoface'); ////Task 2369, converted hardcoded message to dynamic.
            }

            $cntSeats = max(0, $stats);
            if($cntSeats == 0){
                $_SESSION['cntSeats'] = 2;
            }
            else{
                $_SESSION['cntSeats'] = 1;
            }
?>
      <p><?php 
        //Task #4476 start
        if($fshObjData->flag!=1){
            echo $stats;
            //$fshObjData->flag=0;
        }
        //Task #4476 end
      ?></p>
    <?php 
//Classroom Feature start
if($stats == 0){ ?>
<!-- Modal -->
<script>
var sessId = "<?=$upcomingVal->id?>";
$("#res_"+sessId).removeAttr("href").attr({'href':'javascript:void(0);','data-toggle':"modal",'data-target':"#myModal_"+sessId});
</script>

<div id="myModal_<?=$upcomingVal->id?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <?php
include("waitlist_popup.php");
?>
      </div>
    </div>
  </div>
</div>
        
    <?php } 
//Classroom Feature end
      } ?>
                                                                    </div>
                                                                </div> 
                    <?php
                }//endif
            }//end foreach
//US #824 totalSessEnrolled
            $_SESSION['totalSessEnrolled'] = $_SESSION['cntEnrol'] . "_" . $facetofaceid;
            ?>
                                        <script type="text/javascript">
                                            //US #824 start
                                            var totalSessEnrolled = <?php echo $_SESSION['cntEnrol']; ?>;
                                            console.log("gg" + totalSessEnrolled);
                                            if (totalSessEnrolled <= 1) {
                                                $('.resapp').show();
                                            } else {
                                                $('.notapp').show();
                                            }
                                            //US #824 end
                                        </script>
            <?php
            //TRP start
            // - - - -  - - - Start- Feature Request Remove: Local Tranings - - - - -
            if (!isset($_REQUEST['as'])) {
                // - - - -  - - - End- Feature Request Remove: Local Tranings - - - - -
                if ($courseid == 42) {
                    $req_id = 1026;
                    $logo2 = 'Q-SYS_L2-CLASS-choose-180x100.png';
                    // - - - - - Start- New In-Classroom Page Nav - - - courseid is change on live site -//
                } elseif ($courseid == 92) {
                    $logo1 = 'achitect.png';
                    $logo2 = 'achitect.png';
                    $req_id = 1658;
                } elseif ($courseid == 69) {
                    $logo1 = 'Training_touchMixCo.jpg';
                    $logo2 = 'Training_touchMixCo.jpg';
                    $req_id = 1483;
                }// - - - - - End- New In-Classroom Page Nav - - - -//
                else {
                    $req_id = 1025;
                    $logo2 = "qsc_training_level_1_logo.png";
                }
                // - - - - - Start- New In-Classroom Page Nav - - - courseid is change on live site -//
                $sub_desc = get_string('training_request_desc1', 'facetoface');  ////Task 2369, converted hardcoded message to dynamic.
                if ($courseid == 69) {
                    $sub_desc = get_string('training_request_desc2', 'facetoface');  ////Task 2369, converted hardcoded message to dynamic.
                }
                // - - - - - End- New In-Classroom Page Nav - - -  -//
                if ($courseid == 42 || $courseid == 67) {
                    $logo1 = 'Q-SYS_L2-CLASS-Uk-180x100.png';
                    if ($courseid == 67) {
                        $logo1 = 'control_201.png';
                        $logo2 = 'control_201.png';
                        $req_id = 1376;
                    }
                }

                if ($courseid == 86) {
                    $logo1 = 'Q-SYS_TrainingL2_cinema_ course_th.png';
                    $logo2 = 'Q-SYS_TrainingL2_cinema_ course_th.png';
                    $req_id = 1701;
                }

                if ($courseid == 97) {
                    $logo1 = 'badge-level2_for_higher_ed.png';
                    $logo2 = 'badge-level2_for_higher_ed.png';
                    $req_id = 1702;
                }

                if ($courseid == 100) {
                    $logo1 = 'Q-SYS_control_UCI_fundamentals_course_th.png';
                    $logo2 = 'Q-SYS_control_UCI_fundamentals_course_th.png';
                    $req_id = 1712;
                }
                // - - - - - Start- New In-Classroom Page Nav - - - add variable $sub_desc and .'&fid='.$facetofaceid.' ------//
                //Task 2369, converted some hardcoded message to dynamic.
                echo '<div class="class-room-sessions"><span class="intitutionname"><h4><b>' . get_string('training_request_heading', 'facetoface') . '</b></h4></span><div class="page2_second-section pg_one"> <p><img src="/mod/facetoface/' . $logo2 . '" alt="" style="vertical-align:text-bottom;margin:0 .5em;" class="img-responsive" height="100" width="169"><br></p><div class="address-details-classroom"><span class="intitutionname">' . get_string('training_request_sub_heading', 'facetoface') . '</span><span class="address-full">' . $sub_desc . '</span></div><div class="reserve-btn-seats"><a href="/mod/page/view.php?id=' . $req_id . '&fid=' . $facetofaceid . '">' . get_string('submit', 'facetoface') . '</a><p></p></div></div></div>';
                // - - - - - End- New In-Classroom Page Nav - - -  -//
                //TRP end
                // - - - -  - - - Start- Feature Request Remove: Local Tranings - - - - -
            }
            // - - - -  - - - End- Feature Request Remove: Local Tranings - - - - -
        } else {
            echo "<div class='tbl_scroll_1'>";
            
            echo $f2frenderer->print_session_list_table($customfields, $upcomingarray, $viewattendees, $editsessions, $_REQUEST['crole'], 0, $userRoles);
            echo "</div>";
        }
    }//end else
    
        if ($editsessions && ($USER->id == 2 || $USER->usertype == 'mainadmin' || $USER->usertype == 'graderasadmin' || $USER->usertype == 'regionaladmin' || isset($_REQUEST['crole']) == "instructor")) {
        $addsessionlink = html_writer::link(
                        new moodle_url('sessions.php', array('f' => $facetofaceid)),
                        get_string('addsession', 'facetoface')
        );
        echo html_writer::tag('p', $addsessionlink);
    }

    // Previous sessions.
                                            //59 Est Instructor Feature Start
                                            if ((!empty($previousarray) && is_siteadmin($USER)) || ( ($_REQUEST['crole'] == "instructor" || $hasregionaladminrole) && !empty($previousarray))) {
                                                //Classroom Instructor Changes for Previous Seesion Repesentation start (instrcutor condition added)
                                                //changes-121
                                                if (!is_siteadmin($USER) || (isset($_REQUEST['crole']) == "instructor" || $hasregionaladminrole)) {
                                                    //Classroom Instructor Changes for Previous Seesion Repesentation end
                                                    $flagh = 0;
                                                    foreach ($previousarray as $sessionk) {
                                                        if (strpos($sessionk->customfielddata[3]->data, $USER->username) !== false) {
                                                            $flagAk = 1;
                                                            //changes-121
                                                            $flagh = 1;
                                                        } elseif ($hasregionaladminrole){
                                                            $flagAk = 1;
                                                            $flagh = 1;
                                                        } else {
                                                            $flagAk = 2;
                                                        }
                                                    }
                                                } else {
                                                    $flagAk = 1;
                                                }
                                                //changes-121
                                                ?>
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css?v=1.1">
                <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script> 
                <script type="text/javascript">
                /* US #3952 add order param */
                $(document).ready(function () {

                $('.tbl_scroll_1 table.generaltable').DataTable({
                 "pageLength": 10,
                 "ordering": false,
                 "aoColumnDefs": [
                     {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]},
                 ],
                });

                $('.tbl_scroll_2 table.generaltable').DataTable({
                 "pageLength": 10,
                 "aoColumnDefs": [
                     {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 9, 10, 11, 12]},
                     {"iDataSort": 7, "aTargets": [8]}
                 ],
                 "order": [[7, 'desc']],
                 "columnDefs": [
                     //hide the seventh column
                     {"visible": false, "targets": [7]}
                 ]
                 });
                });
                /* US #3952 add order param */
                </script>
        <?php
        if ($flagAk == 1 || $flagh == 1) {
            echo $OUTPUT->heading(get_string('previoussessions', 'facetoface'));
            echo "<div class='tbl_scroll_2'>";
            echo $f2frenderer->print_session_list_table($customfields, $previousarray, $viewattendees, $editsessions, $_REQUEST['crole'], 0, $userRoles);
            echo "</div>";
        }
    }
}

/**
 * Get facetoface locations
 *
 * @param   interger    $facetofaceid
 * @return  array
 */
function get_locations($facetofaceid) {
    global $CFG, $DB;

    $locationfieldid = $DB->get_field('facetoface_session_field', 'id', array('shortname' => 'location'));
    if (!$locationfieldid) {
        return array();
    }

    $sql = "SELECT DISTINCT d.data AS location
              FROM {facetoface} f
              JOIN {facetoface_sessions} s ON s.facetoface = f.id
              JOIN {facetoface_session_data} d ON d.sessionid = s.id
             WHERE f.id = ? AND d.fieldid = ?";

    if ($records = $DB->get_records_sql($sql, array($facetofaceid, $locationfieldid))) {
        $locationmenu[''] = get_string('alllocations', 'facetoface');

        $i = 1;
        foreach ($records as $record) {
            $locationmenu[$record->location] = $record->location;
            $i++;
        }

        return $locationmenu;
    }

    return array();
}

// - - - - - - - - Start-Feature Request: In-Classroom Location Filter -Nav - - - - -- //
/**
 * Get facetoface regions
 *
 * @param   interger    $facetofaceid
 * @return  array
 */
function get_region($facetofaceid, $session_type) {
    global $CFG, $DB, $USER;

    $regionfieldid = $DB->get_field('facetoface_session_field', 'id', array('shortname' => 'region'));
    if (!$regionfieldid) {
        return array();
    }

    if ($records = $DB->get_record('facetoface_session_field', array('shortname' => 'region'))) {
        if (!empty($records->possiblevalues)) {
            $regionmenu[''] = "All Regions";
            $i = 1;
            $regionArr = explode("##SEPARATOR##", $records->possiblevalues);
            //print_r($regionArr); exit;
            foreach ($regionArr as $record) {
                $regionmenu[$record] = $record;
                $i++;
            }

            return $regionmenu;
        }
    }
    return array();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#resionsForm").appendTo("#resionsFormh4");
        $('#menuregion').change(function () {
            var resionsData = $("#menuregion").val();
            $('#resionsForm').submit();
        });
    });
// - - - - - - - - End -Feature Request: In-Classroom Location Filter -Nav - - - - -- //
</script>
