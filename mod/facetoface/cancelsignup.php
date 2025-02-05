<link href="/theme/meline29/style/classroom.css" type="text/css" rel="stylesheet">
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
//-- -- - - - - - - Start - Feature Request: Auto-Unenrolled Students   --//
require_once("$CFG->dirroot/enrol/locallib.php");
require_once($CFG->dirroot."/lib/enrollib.php");
require_once("$CFG->dirroot/lib/sfdclib.php");
//-- -- - - - - - - End - Feature Request: Auto-Unenrolled Students   --//

$s = required_param('s', PARAM_INT); // Facetoface session ID.
$confirm = optional_param('confirm', false, PARAM_BOOL);
$backtoallsessions = optional_param('backtoallsessions', 0, PARAM_INT);

if (!$session = facetoface_get_session($s)) {
    print_error('error:incorrectcoursemodulesession', 'facetoface');
}
//if (!$session->allowcancellations) {
//    print_error('error:cancellationsnotallowed', 'facetoface');
//}
if (!$facetoface = $DB->get_record('facetoface', array('id' => $session->facetoface))) {
    print_error('error:incorrectfacetofaceid', 'facetoface');
}
if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
    print_error('error:coursemisconfigured', 'facetoface');
}
if (!$cm = get_coursemodule_from_instance("facetoface", $facetoface->id, $course->id)) {
    print_error('error:incorrectcoursemoduleid', 'facetoface');
}

require_course_login($course);
$context = context_course::instance($course->id);
$contextmodule = context_module::instance($cm->id);
require_capability('mod/facetoface:view', $context);

// Customization Sameer start
$returnurl = "$CFG->wwwroot/course/view.php?id=$course->id";
$isadmin = is_siteadmin($USER);
//US #824 comment code end
//US #824 start
$facetofaceCSCObj = $DB->get_record_sql('SELECT count(fsi.sessionid) as cntsess FROM `mdl_facetoface` as f JOIN mdl_facetoface_sessions as fs ON f.id = fs.facetoface JOIN mdl_facetoface_signups as fsi ON fs.id=fsi.sessionid JOIN mdl_facetoface_signups_status as fss ON fsi.id=fss.signupid WHERE fss.statuscode =70 and fss.superceded=0 and f.course=? AND fsi.userid = ? order by fss.timecreated desc', array($course->id, $USER->id));
if(!empty($facetofaceCSCObj)){
    $cntEnrollSession = $facetofaceCSCObj->cntsess;
}
//US #824 end
//CCC start
$sql61 = "SELECT * FROM {assign} where course=$course->id and type='classroom'";
$rs61 = $DB->get_record_sql($sql61);
// Customization Sameer end
if ($backtoallsessions) {
    $returnurl = "$CFG->wwwroot/mod/facetoface/view.php?f=$backtoallsessions";
}

$mform = new mod_facetoface_cancelsignup_form(null, compact('s', 'backtoallsessions'));
if ($mform->is_cancelled()) {
    redirect($returnurl);
}

if ($fromform = $mform->get_data()) { // Form submitted.

    if (empty($fromform->submitbutton)) {
        print_error('error:unknownbuttonclicked', 'facetoface', $returnurl);
    }

    $timemessage = 4;

    $errorstr = '';
    if (facetoface_user_cancel($session, false, false, $errorstr, $fromform->cancelreason)) {

        // Logging and events trigger.
        $params = array(
            'context'  => $contextmodule,
            'objectid' => $session->id
        );
        $event = \mod_facetoface\event\cancel_booking::create($params);
        $event->add_record_snapshot('facetoface_sessions', $session);
        $event->add_record_snapshot('facetoface', $facetoface);
        $event->trigger();

        $message = get_string('bookingcancelled', 'facetoface');
        if ($session->datetimeknown || $session->capacity == 10000) {
            $error = facetoface_send_cancellation_notice($facetoface, $session, $USER->id);
            if (empty($error)) {
                if (($session->datetimeknown || $session->capacity == 10000) && $facetoface->cancellationinstrmngr) {
                   // $message .= html_writer::empty_tag('br') . html_writer::empty_tag('br') . get_string('cancellationsentmgr', 'facetoface');
                } else {
                    $message .= html_writer::empty_tag('br') . html_writer::empty_tag('br') . get_string('cancellationsent', 'facetoface');
                }
            } else {
                print_error($error, 'facetoface');
            }
        }
        //-- -- - - - - - - Start - Feature Request: Auto-Unenrolled Students   --//
//        $sfdcDelete = 0;
         //US #824 add if cond and start curly braces
        if($cntEnrollSession == 1){
            $resps = unenrolled_classroom_session($course->id, $USER);
        }
        // die('hello');
        //US #824 add if cond end curly braces         
            
        //-- -- - - - - - - End - Feature Request: Auto-Unenrolled Students   --//
        $currenturl = "$CFG->wwwroot/my";
        redirect("$CFG->wwwroot/my");
        // redirect($currenturl, $message, $timemessage);
    } else {

        // Logging and events trigger.
        $params = array(
            'context'  => $contextmodule,
            'objectid' => $session->id
        );
        $event = \mod_facetoface\event\cancel_booking_failed::create($params);
        $event->add_record_snapshot('facetoface_sessions', $session);
        $event->add_record_snapshot('facetoface', $facetoface);
        $event->trigger();

        redirect($returnurl, $errorstr, $timemessage);
    }

    redirect($returnurl);
}

$pagetitle = format_string($facetoface->name);

$PAGE->set_cm($cm);
$PAGE->set_url('/mod/facetoface/cancelsignup.php', array('s' => $s, 'backtoallsessions' => $backtoallsessions, 'confirm' => $confirm));

$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

$heading = get_string('cancelbookingfor', 'facetoface', $facetoface->name);

$viewattendees = has_capability('mod/facetoface:viewattendees', $context);
$signedup = facetoface_check_signup($facetoface->id);

echo $OUTPUT->box_start();
// Add custom fields to sessiondata.
$customdata = $DB->get_records('facetoface_session_data', array('sessionid' => $session->id), 'fieldid, data');
if($customdata[4]->data == "Level 1"){
?>
    <div class="classroom-head-with-logo"> <img src="/theme/meline29/pix/classroom/Level1-Classroom-Logo.png"/> </div>
<?php
}elseif($customdata[4]->data == "Level 2"){
?>
    <div class="classroom-head-with-logo"> <img src="/theme/meline29/pix/classroom/level_2_heading_logo.png"/> </div>
<?php
}
//echo $OUTPUT->heading($heading);

if ($signedup) {
    facetoface_print_session($session, $viewattendees);
?>
<div class="cancel_form_wrapper">
<?php
    $mform->display();
?>
</div>
<?php
} else {
    print_error('notsignedup', 'facetoface', $returnurl);
}

echo $OUTPUT->box_end();
echo $OUTPUT->footer($course);
// // Customization Sameer start
// }
// // Customization Sameer end
?>
<script>
    $('#id_general').removeClass('collapsible');
    $('.fdescription.required').remove();

$(document).ready(function(){
    $("#id_cancelreason").attr("readonly","readonly");
    $("#id_cancelreason").css("background-color","white");
    $("#id_cancelreason").css("cursor","text");
    $('#id_cancelreason').on('focus',function(){
        $("#id_cancelreason").removeAttr("readonly");
    });    
});

</script>