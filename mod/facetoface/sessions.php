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

$id = optional_param('id', 0, PARAM_INT); // Course Module ID.
$f = optional_param('f', 0, PARAM_INT); // facetoface Module ID.
$s = optional_param('s', 0, PARAM_INT); // facetoface session ID.
$c = optional_param('c', 0, PARAM_INT); // Copy session.
$d = optional_param('d', 0, PARAM_INT); // Delete session.
$confirm = optional_param('confirm', false, PARAM_BOOL); // Delete confirmation.

$nbdays = 1; // Default number to show.

$session = null;
if ($id && !$s) {
    if (!$cm = $DB->get_record('course_modules', array('id' => $id))) {
        print_error('error:incorrectcoursemoduleid', 'facetoface');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('error:coursemisconfigured', 'facetoface');
    }
    if (!$facetoface = $DB->get_record('facetoface', array('id' => $cm->instance))) {
        print_error('error:incorrectcoursemodule', 'facetoface');
    }
} else if ($s) {
    if (!$session = facetoface_get_session($s)) {
        print_error('error:incorrectcoursemodulesession', 'facetoface');
    }
    if (!$facetoface = $DB->get_record('facetoface', array('id' => $session->facetoface))) {
        print_error('error:incorrectfacetofaceid', 'facetoface');
    }
    if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
        print_error('error:coursemisconfigured', 'facetoface');
    }
    if (!$cm = get_coursemodule_from_instance('facetoface', $facetoface->id, $course->id)) {
        print_error('error:incorrectcoursemoduleid', 'facetoface');
    }

    $nbdays = count($session->sessiondates);
} else {
    if (!$facetoface = $DB->get_record('facetoface', array('id' => $f))) {
        print_error('error:incorrectfacetofaceid', 'facetoface');
    }
    if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
        print_error('error:coursemisconfigured', 'facetoface');
    }
    if (!$cm = get_coursemodule_from_instance('facetoface', $facetoface->id, $course->id)) {
        print_error('error:incorrectcoursemoduleid', 'facetoface');
    }
}

require_course_login($course);
$errorstr = '';
$context = context_course::instance($course->id);

$userRoles = array();
$roles = get_user_roles($context, $USER->id);
foreach ($roles as $role) {
    $userRoles[] = $role->shortname;
}

$hasregionaladminrole = 0;
$hasinstructorrole = 0;
if(in_array('regionaladmin', $userRoles)){
    $hasregionaladminrole = 1;
}elseif(in_array('qscinstructor', $userRoles) || in_array('repinstructor', $userRoles)){
    $hasinstructorrole = 1;
}
?>
<style>
#page.container-fluid {
    padding: 0 20px !important;
}
</style>
<link href="/mod/facetoface/styles.css" type="text/css" rel="stylesheet">
<?php
$modulecontext = context_module::instance($cm->id);
require_capability('mod/facetoface:editsessions', $context);
// US #5635 : Task 5842  
$editsession_cap = has_capability('mod/facetoface:editsessions', $context);
$returnurl = "$CFG->wwwroot/course/view.php?id=$course->id";
$isadmin = is_siteadmin($USER);
// US #5635 : Task 5842 Alter the condition
if (!$isadmin && empty($editsession_cap)) {
    if ($_SESSION['ccertstepscomp'] == 'certified') {
        redirect($returnurl);
    }
/*    $cmOnlineVerObj = $DB->get_records('course_modules', array('course'=>$course->id, 'module'=>3),'id ASC','id');
                $OVFlag=0;
    if(!empty($cmOnlineVerObj)){
        foreach ($cmOnlineVerObj as $valOVerObj) {
            # code...
            $cmcOnlineVerObj = $DB->get_records('course_modules_completion', array('userid'=>$USER->id,'coursemoduleid' => $valOVerObj->id,'completionstate' => 1),'id ASC', 'id');
            if(!empty($cmcOnlineVerObj)){
                redirect($returnurl);
            }
        }
    }*/
# check code using certification condition for student not attend session if he is already certified...
        $keyCertVal = $DB->get_record('simplecertificate', array('course'=>$course->id), "id");
        $cmcOnlineVerObj = $DB->get_record('simplecertificate_issues', array('userid'=>$USER->id,'certificateid' => $keyCertVal->id),'id');
        if(!empty($cmcOnlineVerObj)){
            redirect($returnurl);
        }
}


$PAGE->set_cm($cm);
$PAGE->set_url('/mod/facetoface/sessions.php', array('f' => $f));

$PAGE->set_cm($cm);
$PAGE->set_url('/mod/facetoface/sessions.php', array('f' => $f));

if($hasregionaladminrole || $hasinstructorrole){
    $returnurl = "view.php?f=$facetoface->id&crole=instructor";
}else{
    $returnurl = "view.php?f=$facetoface->id";
}


$editoroptions = array(
    'noclean'  => false,
    'maxfiles' => EDITOR_UNLIMITED_FILES,
    'maxbytes' => $course->maxbytes,
    'context'  => $modulecontext,
);


// Handle deletions.
if ($d and $confirm) {
    if (!confirm_sesskey()) {
        print_error('confirmsesskeybad', 'error');
    }

    //US #2020 start
    //To delete user's session enrollment from sfdc
    $courseId = $course->id;
    require_once($CFG->dirroot . '/lib/sfdclib.php');
     $sqlFacetofaceSessionsByUser = "SELECT ds.signupid, f.name, d.userid, ds.superceded, ds.statuscode, s.id AS sessionid, s.duration, sd.timestart, sd.timefinish FROM mdl_facetoface f JOIN mdl_facetoface_sessions s ON s.facetoface = f.id JOIN mdl_facetoface_sessions_dates sd ON sd.sessionid = s.id JOIN mdl_facetoface_signups d ON d.sessionid = s.id JOIN mdl_facetoface_signups_status ds ON ds.signupid = d.id WHERE s.id = ".$session->id." AND ds.superceded =0";

    $sqlFacetofaceSessionsByUserResArr = $DB->get_records_sql($sqlFacetofaceSessionsByUser);
   // echo "<pre>"; print_r($sqlFacetofaceSessionsByUserResArr);
    foreach($sqlFacetofaceSessionsByUserResArr as $sqlFacetofaceSessionsByUserRes){
        $sqlUserSFDCData2 = "SELECT u.id, u.username
                                FROM {user} u WHERE u.id = $sqlFacetofaceSessionsByUserRes->userid";
        $sqlSFDCUserObjData2 = $DB->get_record_sql($sqlUserSFDCData2);

        $sfdc_contact_id = show_contact_id($sqlSFDCUserObjData2->username); 
        if($sfdc_contact_id){
            $show_sfdc_course = sfdc_show_courses($sfdc_contact_id, $courseId);
            if($show_sfdc_course){
                $course_sfdc_id = $show_sfdc_course['records'][0]['Id'];
                $classroomcouse_sfdc_id1 = show_classroom_by_course_id($course_sfdc_id);
                $session_sfdc_ids = show_all_session_by_classroom_sfdc_id($classroomcouse_sfdc_id1);

                $fsd = $DB->get_records('facetoface_session_data', array('sessionid' => $session->id));
                // Update course instructor name
                $session_Instructor = "";
                foreach ($fsd as $fkey) {
                    if($fkey->fieldid == 6) $sn = $title = $fkey->data;
                }
               // echo $sqlFacetofaceSessionsByUserRes->userid; echo "\n";
                //echo $sn; echo "\n";
               // echo $sqlFacetofaceSessionsByUserRes->sessionid; 
               // echo "\n";
                $tsc = $sqlFacetofaceSessionsByUserRes->timestart; 
               // echo "\n";
                $tec = $sqlFacetofaceSessionsByUserRes->timefinish; 
                //echo "\n";
                $session_start_date = date("Y-m-d",$tsc); 
               // echo "\n";
                $session_end_date = date("Y-m-d",$tec); 
                //echo "\n";
                foreach ($session_sfdc_ids as $sessionDetailsValue) {
                    # code...
                   // echo "\n -------------- \n";
                   // echo $sessionDetailsValue['Id'];
                   // echo "\n";
                   // echo $sessionDetailsValue['Name'];
                   // echo "\n";
                    
                    $tsc_salesforce = $sessionDetailsValue['session_start_date__c'];
                    if($tsc_salesforce){
                        $tsc_sfdc_arr = explode("T",$tsc_salesforce);
                        $tsc_sfdc_date = $tsc_sfdc_arr[0];
                    }
                   // echo "\n";
                    $tec_salesforce = $sessionDetailsValue['session_end_date__c'];
                    if($tec_salesforce){
                        $tec_sfdc_arr = explode("T",$tec_salesforce);
                        $tec_sfdc_date = $tec_sfdc_arr[0];
                    }
                    //echo "\n";
                    $sn_sfdc = $sessionDetailsValue['Name'];

                    if($sn == $sn_sfdc && $tsc_sfdc_date == $session_start_date && $tec_sfdc_date == $session_end_date){
                      // echo "Goal";
                       $return_status = sfdc_delete_session_by_Id($sessionDetailsValue['Id']);                                   
                    }
                }
            }
        }
    }
   // exit;
    //US #2020 end

    if (facetoface_delete_session($session)) {

        // Logging and events trigger.
        $params = array(
            'context'  => $modulecontext,
            'objectid' => $session->id
        );
        $event = \mod_facetoface\event\delete_session::create($params);
        $event->add_record_snapshot('facetoface_sessions', $session);
        $event->add_record_snapshot('facetoface', $facetoface);
        $event->trigger();
    } else {

        // Logging and events trigger.
        $params = array(
            'context'  => $modulecontext,
            'objectid' => $session->id
        );
        $event = \mod_facetoface\event\delete_session_failed::create($params);
        $event->add_record_snapshot('facetoface_sessions', $session);
        $event->add_record_snapshot('facetoface', $facetoface);
        $event->trigger();
        print_error('error:couldnotdeletesession', 'facetoface', $returnurl);
    }
    redirect($returnurl);
}

$customfields = facetoface_get_session_customfields();

$sessionid = isset($session->id) ? $session->id : 0;

$details = new stdClass();
$details->id = isset($session) ? $session->id : 0;
$details->details = isset($session->details) ? $session->details : '';
$details->detailsformat = FORMAT_HTML;
$details = file_prepare_standard_editor($details, 'details', $editoroptions, $modulecontext, 'mod_facetoface', 'session', $sessionid);

$mform = new mod_facetoface_session_form(null, compact('id', 'facetoface', 'f', 's', 'c', 'nbdays', 'customfields', 'course', 'editoroptions'));

if ($mform->is_cancelled()) {
    redirect($returnurl);
}

if ($fromform = $mform->get_data()) { // Form submitted.

    if (empty($fromform->submitbutton)) {
        print_error('error:unknownbuttonclicked', 'facetoface', $returnurl);
    }

    // Pre-process fields.
    if (empty($fromform->allowoverbook)) {
        $fromform->allowoverbook = 0;
    }
    //US 3072 start
    if (empty($fromform->countryrestriction)) {
        $fromform->countryrestriction = 0;
    }
    //US 3072 end
    if (empty($fromform->duration)) {
        $fromform->duration = 0;
    }
    if (empty($fromform->normalcost)) {
        $fromform->normalcost = 0;
    }
    if (empty($fromform->discountcost)) {
        $fromform->discountcost = 0;
    }
    // - - - - - Start-Request: Manager Emails -------//
    if (!empty($fromform->custom_managersemail)) {
        echo $fromform->custom_managersemail = strtolower($fromform->custom_managersemail);
    }
    // - - - - - Start-Request: Manager Emails -------//

    $sessiondates = array();
    for ($i = 0; $i < $fromform->date_repeats; $i++) {
        if (!empty($fromform->datedelete[$i])) {
            continue; // Skip this date.
        }

        if (!empty($fromform->timestart[$i]) and !empty($fromform->timefinish[$i])) {
            $date = new stdClass();
            if($fromform->datetimeknown == 0){
                $date->timestart = "2481889900";
                $date->timefinish = "2481889900";
            }else{
                $date->timestart = $fromform->timestart[$i];
                $date->timefinish = $fromform->timefinish[$i];
            }
            $sessiondates[] = $date;
        }
    }

    $todb = new stdClass();
    $todb->facetoface = $facetoface->id;
//    $todb->datetimeknown = $fromform->datetimeknown;
    $todb->datetimeknown = 1;
     //Task #14433 start
     if($fromform->custom_classroomtype == "Local Classes"){
        unset($fromform->custom_qscinstructorname);
    }else{
        unset($fromform->custom_repinstructorname);
    }
    // Task #14433 end
    $todb->capacity = $fromform->capacity;
    $todb->allowoverbook = $fromform->allowoverbook;
    //US 3072 start
    $todb->countryrestriction = $fromform->countryrestriction;
    //US 3072 end    
    $todb->duration = $fromform->duration;
    $todb->normalcost = $fromform->normalcost;
    $todb->discountcost = $fromform->discountcost;
    if (has_capability('mod/facetoface:configurecancellation', $context)) {
        $todb->allowcancellations = $fromform->allowcancellations;
    }

    $sessionid = null;
    $transaction = $DB->start_delegated_transaction();

    $update = false;
    if (!$c and $session != null) {
        $update = true;
        $sessionid = $session->id;

        $todb->id = $session->id;
        if (!facetoface_update_session($todb, $sessiondates)) {
            $transaction->force_transaction_rollback();

            // Logging and events trigger.
            $params = array(
                'context'  => $modulecontext,
                'objectid' => $session->id
            );
            $event = \mod_facetoface\event\update_session_failed::create($params);
            $event->add_record_snapshot('facetoface_sessions', $session);
            $event->add_record_snapshot('facetoface', $facetoface);
            $event->trigger();
            print_error('error:couldnotupdatesession', 'facetoface', $returnurl);
        }

        // Remove old site-wide calendar entry.
        if (!facetoface_remove_session_from_calendar($session, SITEID)) {
            $transaction->force_transaction_rollback();
            print_error('error:couldnotupdatecalendar', 'facetoface', $returnurl);
        }
    } else {
        if (!$sessionid = facetoface_add_session($todb, $sessiondates)) {
            $transaction->force_transaction_rollback();

            // Logging and events trigger.
            $params = array(
                'context'  => $modulecontext,
                'objectid' => $facetoface->id
            );
            $event = \mod_facetoface\event\add_session_failed::create($params);
            $event->add_record_snapshot('facetoface', $facetoface);
            $event->trigger();
            print_error('error:couldnotaddsession', 'facetoface', $returnurl);
        }
    }

    foreach ($customfields as $field) {
        $fieldname = "custom_$field->shortname";
        if (!isset($fromform->$fieldname)) {
            $fromform->$fieldname = ''; // Need to be able to clear fields.
        }
        //US 3072 start
        if($field->id == 15 and $fromform->countryrestriction == 0){
            $fromform->$fieldname = '';
        }
        //US 3072 end
        if (!facetoface_save_customfield_value($field->id, $fromform->$fieldname, $sessionid, 'session')) {
            $transaction->force_transaction_rollback();
            print_error('error:couldnotsavecustomfield', 'facetoface', $returnurl);
        }
    }

    // Save trainer roles.
    if (isset($fromform->trainerrole)) {
        facetoface_update_trainers($sessionid, $fromform->trainerrole);
    }

    // Retrieve record that was just inserted/updated.
    if (!$session = facetoface_get_session($sessionid)) {
        $transaction->force_transaction_rollback();
        print_error('error:couldnotfindsession', 'facetoface', $returnurl);
    }

    // Update calendar entries.
    facetoface_update_calendar_entries($session, $facetoface);
    if ($update) {

        // Logging and events trigger.
        $params = array(
            'context'  => $modulecontext,
            'objectid' => $session->id
        );
        $event = \mod_facetoface\event\update_session::create($params);
        $event->add_record_snapshot('facetoface_sessions', $session);
        $event->add_record_snapshot('facetoface', $facetoface);
        $event->trigger();
    } else {

        // Logging and events trigger.
        $params = array(
            'context'  => $modulecontext,
            'objectid' => $session->id
        );
        $event = \mod_facetoface\event\add_session::create($params);
        $event->add_record_snapshot('facetoface_sessions', $session);
        $event->add_record_snapshot('facetoface', $facetoface);
        $event->trigger();
    }

    $transaction->allow_commit();

    $data = file_postupdate_standard_editor($fromform, 'details', $editoroptions, $modulecontext, 'mod_facetoface', 'session', $session->id);
    $DB->set_field('facetoface_sessions', 'details', $data->details, array('id' => $session->id));

    redirect($returnurl);
} else if ($session != null) { // Edit mode.

    // Set values for the form.
    $toform = new stdClass();
    $toform = file_prepare_standard_editor($details, 'details', $editoroptions, $modulecontext, 'mod_facetoface', 'session', $session->id);

    if ($session->sessiondates[0]->timestart == "2481889900" && $session->sessiondates[0]->timefinish == "2481889900") {
        $toform->datetimeknown =0; 
    }
    else {
        $toform->datetimeknown = (1 == $session->datetimeknown);
    }

    $toform->capacity = $session->capacity;
    $toform->allowoverbook = $session->allowoverbook;
    //US 3072 start
    $toform->countryrestriction = $session->countryrestriction;
    //US 3072 end    
    $toform->duration = $session->duration;
    $toform->normalcost = $session->normalcost;
    $toform->discountcost = $session->discountcost;
    if (has_capability('mod/facetoface:configurecancellation', $context)) {
        $toform->allowcancellations = $session->allowcancellations;
    }

    if ($session->sessiondates) {
        $i = 0;
        foreach ($session->sessiondates as $date) {
            $idfield = "sessiondateid[$i]";
            $timestartfield = "timestart[$i]";
            $timefinishfield = "timefinish[$i]";
            $toform->$idfield = $date->id;
            $toform->$timestartfield = $date->timestart;
            $toform->$timefinishfield = $date->timefinish;
            $i++;
        }
    }

    foreach ($customfields as $field) {
        $fieldname = "custom_$field->shortname";
        $toform->$fieldname = facetoface_get_customfield_value($field, $session->id, 'session');
    }

    $mform->set_data($toform);
}

if ($c) {
    $heading = get_string('copyingsession', 'facetoface', $facetoface->name);
} else if ($d) {
    $heading = get_string('deletingsession', 'facetoface', $facetoface->name);
} else if ($id || $f) {
    $heading = get_string('addingsession', 'facetoface', $facetoface->name);
} else {
    $heading = get_string('editingsession', 'facetoface', $facetoface->name);
}

$pagetitle = format_string($facetoface->name);


$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

echo $OUTPUT->box_start();
echo $OUTPUT->heading($heading);

if (!empty($errorstr)) {
    echo $OUTPUT->container(html_writer::tag('span', $errorstr, array('class' => 'errorstring')), array('class' => 'notifyproblem'));
}

if ($d) {
    $viewattendees = has_capability('mod/facetoface:viewattendees', $context);
    facetoface_print_session($session, $viewattendees);
    $optionsyes = array('sesskey' => sesskey(), 's' => $session->id, 'd' => 1, 'confirm' => 1);
echo "<div class='session_confirm'>";
    echo $OUTPUT->confirm(get_string('deletesessionconfirm', 'facetoface', format_string($facetoface->name)),
        new moodle_url('sessions.php', $optionsyes),
        new moodle_url($returnurl));
echo "</div>";
} else {
    $mform->display();
}

echo $OUTPUT->box_end();
echo $OUTPUT->footer($course);

     /* Customization start by */
$inside = get_string('inside_qsc','facetoface');
$local = get_string("local_classess","facetoface");
$outside = get_string("outside_qsc","facetoface");
	
/* Customization end by */
//echo "<pre>"; print_r($course); exit;
$cc = $course->category;
?>
<script>
var s = "<?=$s?>";
var inside = "<?=$inside?>";
var local = "<?=$local?>";
var outside = "<?=$outside?>";
var course_category = "<?=$cc?>";
$('#fitem_id_datedelete_0').remove();
if(s != 0){
//$('#fitem_id_capacity').hide();
}
else{
$('#id_custom_classroomtype').html('<option value="Inside QSC">'+inside+'</option><option value="Local Classes">'+local+'</option><option value="Outside QSC">'+outside+'</option>');
}

<?php 
if(($hasregionaladminrole || $hasinstructorrole) && !$s){ ?>
    $('#id_custom_managersemail').val('<?php echo $USER->email ?>');
<?php } ?>
//$('#id_custom_classroomtype').attr('readonly','true');

if(course_category == '4' || course_category == '1'){
//console.log("Checking "+course_category);
$('#id_custom_leveltype').val('Level 1');
//$('#id_custom_leveltype').attr('disabled',true);
$("#id_custom_leveltype option[value='Level 1']").attr('selected','selected');
$("#id_custom_leveltype option[value='Level 2']").remove();

//$('#id_custom_classroomtype').html('<option value="Inside QSC">'+inside+'</option><option value="Local Classes">'+local+'</option>');
$("#id_custom_classroomtype option[value='Outside QSC']").remove();
}

if(course_category == '16'){
//console.log("Checking "+course_category);
$('#id_custom_leveltype').val('Level 2');
$("#id_custom_leveltype option[value='Level 2']").attr('selected','selected');
$("#id_custom_leveltype option[value='Level 1']").remove();

//$('#id_custom_classroomtype').html('<option value="Inside QSC">'+inside+'</option><option value="Outside QSC">'+outside+'</option>');
//$("#id_custom_classroomtype option[value='Local Classes']").remove();
//TRP start
$("#id_custom_classroomtype option[value='Outside QSC']").remove();
//TRP end
}



/*if($('#id_custom_leveltype').val() == 'Level 1'){
	$('#id_custom_promocode').val('');
	$('#fitem_id_custom_promocode').hide();
}
else{
 	$('#fitem_id_custom_promocode').show();
}*/

/*$('#id_custom_leveltype').load(function(){
    //alert("Test"+this.value);
    $('#id_custom_classroomtype').removeAttr('readonly');
    if(this.value == 'Level 2'){
        $('#id_custom_classroomtype').html('<option value="Inside QSC">'+inside+'</option><option value="Outside QSC">'+outside+'</option>');        
        $('#fitem_id_custom_promocode').show();
    }
    else if(this.value == 'Level 1'){
        $('#id_custom_classroomtype').html('<option value="Inside QSC">'+inside+'</option><option value="Local Classes">'+local+'</option>');    
        $('#id_custom_promocode').val('');
	$('#fitem_id_custom_promocode').hide();
    }
    else{
        $('#id_custom_classroomtype').html('<option value="Inside QSC">'+inside+'</option><option value="Local Classes">'+local+'</option><option value="Outside QSC">'+outside+'</option>');
        $('#id_custom_promocode').val('');
	$('#fitem_id_custom_promocode').hide();           
    }
});*/

//US 3072 start
$( "#fitem_id_custom_repinstructorname, #fitem_id_custom_qscinstructorname" ).insertBefore( $( "#fitem_id_custom_title" ) );
$( "#fitem_id_custom_countries" ).insertAfter( $( "#fitem_id_countryrestriction" ) );
$('#fitem_id_custom_countries').hide();
//var countryRes = $("#id_countryrestriction").val();
$("#id_countryrestriction").change(function() {
    if(this.checked) {
        //Do stuff
        $('#fitem_id_custom_countries').show();
        //alert("show");
    }else{
        $('#fitem_id_custom_countries').hide();
    }
});

//var countryrestriction = $("#id_countryrestriction").val();
if ($('input#id_countryrestriction').is(':checked')) {
    $('#fitem_id_custom_countries').show();
}
//US 3072 end

$( "#fitem_id_custom_repinstructorname, #fitem_id_custom_qscinstructorname" ).insertBefore( $( "#fitem_id_custom_title" ) );
//$('#fitem_id_custom_qscinstructorname').show();
//$('#fitem_id_custom_repinstructorname').hide();

var classroomType = $("#id_custom_classroomtype").val();
if(classroomType == 'Local Classes'){
    $('#fitem_id_custom_qscinstructorname').hide();
    $('#fitem_id_custom_repinstructorname').show();
}
else{
    $('#fitem_id_custom_qscinstructorname').show();
    $('#fitem_id_custom_repinstructorname').hide();
}

$('#fitem_id_capacity').show();

$('#id_custom_classroomtype').change(function(){
	if(this.value == 'Local Classes'){
//		$('#fitem_id_capacity').hide();
		//$('#fitem_id_capacity').show();
		$('#fitem_id_custom_qscinstructorname').hide();
		$('#fitem_id_custom_repinstructorname').show();		
	}
	else{
		$('#fitem_id_custom_qscinstructorname').show();
		$('#fitem_id_custom_repinstructorname').hide();
		//$('#fitem_id_capacity').show();		
	}
});


//Customization for Private Classroom Training Session
$('#fitem_id_custom_securitycode').hide();


var astype = $("#id_custom_accesstype").val();
if(astype == "Private"){
$('#fitem_id_custom_securitycode').show();
}
$('#id_custom_accesstype').change(function(){
	if(this.value == 'Private'){
		$('#fitem_id_custom_securitycode').show();	
	}
	else{
		$('#id_custom_securitycode').val('');
		$('#fitem_id_custom_securitycode').hide();
	}
});



 // Customization start 

//$('#id_custom_managersemail').attr("readonly",true);

$('#id_date_add_fields').remove(); 
$('#fitem_id_duration').hide();
$('#fitem_id_custom_leveltype').hide();



$( "#fitem_id_custom_length" ).insertBefore( "#fitem_id_capacity" );

if($("#id_datetimeknown").val()==0){   
    $('#fitem_id_timestart_0').hide();
    $('#fitem_id_timefinish_0').hide();    
    $("#fitem_id_custom_length").hide();   
} 


        var datetimeknown = $("#id_datetimeknown").val();

        if(datetimeknown == '1'){
		$("#fitem_id_custom_length").show();
	}
	else{
		$("#fitem_id_custom_length").prop("disabled",true);    
	}

$(document).ready(function(){
    $("#id_datetimeknown").on('change',function(){
        var datetimeknown = $("#id_datetimeknown").val();
        if(datetimeknown == '1'){
            $("#fitem_id_custom_length").show();  
	    $('#fitem_id_timestart_0').show();
            $('#fitem_id_timefinish_0').show();  

        } 
        else{
            $('#fitem_id_timestart_0').hide();
	    $('#fitem_id_timefinish_0').hide();    
	    $("#fitem_id_custom_length").hide();   
	}
    });

    $( "#fitem_id_custom_classroomtypesfdc" ).insertBefore( $( "#fitem_id_custom_classroomtype" ) );
    
    //Custom message Nav
    $("#fitem_id_custom_CustomMessage").prependTo("#fitem_id_details_editor");
    $("#id_custom_CustomMessage").css("width","450px");
    $("#id_custom_CustomMessage").css("height","200px");
    //Custom message Nav
    // - --  --Start -New Feature Request: Editable Instructor Name Field- - - ----- ----//
    $("#id_custom_classroomtype option[value='']").remove();
    // - --  --Start -New Feature Request: Editable Instructor Name Field- - - ----- ----//
});


// Customization end 

</script>
