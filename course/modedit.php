
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
* Adds or updates modules in a course using new formslib
*
* @package    moodlecore
* @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once("../config.php");
require_once("lib.php");
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/plagiarismlib.php');
require_once($CFG->dirroot . '/course/modlib.php');

$add    = optional_param('add', '', PARAM_ALPHANUM);     // Module name.
$update = optional_param('update', 0, PARAM_INT);
$return = optional_param('return', 0, PARAM_BOOL);    //return to course/view.php if false or mod/modname/view.php if true
$type   = optional_param('type', '', PARAM_ALPHANUM); //TODO: hopefully will be removed in 2.0
$sectionreturn = optional_param('sr', null, PARAM_INT);
$beforemod = optional_param('beforemod', 0, PARAM_INT);


    //Custom-1 start        
// - -- - --- - - Start- Reporting Errors Case-II for Certificate Update Functions -        
function update_certificate_details($certificate_id, $expirydateinyear)     
{       
    global $USER,$DB,$CFG;      
        $certObjData = $DB->get_record('simplecertificate',array('id'=>$certificate_id),'id,name,course,certexpirydateinyear');     
    if(!empty($certObjData))        
    {       
        $SavedExpiryYear = $certObjData->certexpirydateinyear;      
        $courseId = $certObjData->course;       
        if(($SavedExpiryYear  != $expirydateinyear) && ($SavedExpiryYear  < $expirydateinyear))     
        {       
            //$courseid = $certObjData->course;     
            $certExpDurationDate = $expirydateinyear;       
            $sqlSCIUsersL = 'SELECT scil.id,scil.certificateid,scil.userid,scil.timecreated,scil.timeexpired FROM {simplecertificate_issues} scil LEFT JOIN {user} us ON scil.userid= us.id where scil.certificateid = '.$certificate_id.' AND us.deleted = 0  ORDER BY scil.userid';       
            $certUserListData = $DB->get_records_sql($sqlSCIUsersL);        
            //echo count($certUserListData);        
            $expity_record= array();        
            $issue_change_record= array();      
            $issue_logs_record= array();        
            $sc_count = count($certUserListData);       
            $i =0;      
                foreach ($certUserListData as $keyCertVal)      
                {       
                    //observation-1 start       
                    $userid = $keyCertVal->userid;      
                    if($i < 501) {      
                        // Certificate Business Logic       
                        $sqlSCIL = 'SELECT id,userid, courseid, MAX(timecompletion) as timecompletion FROM {simplecertificate_issue_logs} scil where userid IN ('.$userid.') and courseid = '.$courseId.' AND timecompletion is not null group by userid, courseid LIMIT 0,1';      
                        $sqlSCILObjData = $DB->get_record_sql($sqlSCIL);        
                            
                        if($certExpDurationDate == 99 || $courseId == 42){  
                            if($sqlSCILObjData){    
                                        $texp = $certExpDurationDate;   
                                        if($keyCertVal->timeexpired != 99){ 
                                            $update_header = "UPDATE {simplecertificate_issues} SET haschange=1, timeexpired = ".$texp." where id = $keyCertVal->id";   
                                            $DB->execute($update_header);   
                                            $update_header_log = "INSERT INTO {simplecertificate_issue_logs} (userid,timecompletion,timeexpired,courseid) values (".$sqlSCILObjData->userid.",".$sqlSCILObjData->timecompletion.",0,".$courseId.")";    
                                            $DB->execute($update_header_log);   
                                                
                                        // - - - - -- - - - For SFDC Certificate expiry update cron  -- - - //  
                                            
                                            $insert_header4 = "INSERT INTO {simplecertificate_expiry_change_log} (userid, courseid, timecreated, timeexpired, sfdc_flag) VALUES (".$sqlSCILObjData->userid.",".$courseId.",".$sqlSCILObjData->timecompletion.",0,0)";   
                                            $DB->execute($insert_header4);  
                                        }               
                            }   
                        } //observation-1 end   
                        else{   
                            if($sqlSCILObjData){    
                                //foreach($sqlSCILObj as $sqlSCILObjData){  
                                    $certExpiredDate = strtotime($certExpDurationDate." years", $sqlSCILObjData->timecompletion);   
                                    $texp = $certExpiredDate;       
                                        $t = strtotime(date("d-m-Y h:i:s"));    
                                        // - - - - -Condition is only for  1 month Expring and expired certificate update   
                                        if(($texp >= $keyCertVal->timeexpired) && ($keyCertVal->timeexpired != 99) && !empty($keyCertVal->timeexpired)){    
                                           $update_header = "UPDATE {simplecertificate_issues} SET haschange=1, timeexpired = ".$texp." where id = $keyCertVal->id";    
                                            $DB->execute($update_header);   
                                            $issue_logs = new stdClass();   
                                            $issue_logs->userid = $sqlSCILObjData->userid;  
                                            $issue_logs->courseid = $courseId;  
                                            $issue_logs->timecompletion = $sqlSCILObjData->timecompletion;  
                                            $issue_logs->timeexpired = $texp;   
                                            // echo "6";    
                                            // echo "\n";   
                                            $issue_change_log = new stdClass(); 
                                            $issue_change_log->userid = $sqlSCILObjData->userid;    
                                            $issue_change_log->courseid = $courseId;    
                                            $issue_change_log->timecreated = $sqlSCILObjData->timecompletion;   
                                            $issue_change_log->timeexpired = $texp; 
                                            $issue_change_log->sfdc_flag = 0;   
                                            // - - - - -- - - - For SFDC Certificate expiry update cron  -- - - //      
                                            $expiry_change_log = new stdClass();    
                                            $expiry_change_log->userid = $sqlSCILObjData->userid;   
                                            $expiry_change_log->courseid = $courseId;   
                                            $expiry_change_log->timecreated = $sqlSCILObjData->timecompletion;  
                                            $expiry_change_log->timeexpired = $texp;    
                                            $expiry_change_log->sfdc_flag = 0;  
                                            $expity_record[]= $expiry_change_log;   
                                            $issue_change_record[]= $issue_change_log;  
                                            $issue_logs_record[]= $issue_logs;  
                                            unset($expiry_change_log);  
                                            unset($issue_change_log);   
                                            unset($issue_logs); 
                                        }   
                               // }//end foreach    
                                    
                            }   
                        }   
                    }else{  
                        //$DB->set_debug(true); 
                        $insert_schedul = "INSERT INTO {expiry_change_scheduled} (userid, courseid,certificateid, old_certexpirydateinyear, new_certexpirydateinyear, scheduled_flag) VALUES (".$userid.",".$courseId.",".$certificate_id.",".$SavedExpiryYear.",".$expirydateinyear.",0)"; 
                            $DB->execute($insert_schedul);  
                        //$DB->set_debug(false);    
                            break;  
                    }   
                    $i++;   
                }   
                //$DB->set_debug(true); 
                if(!empty($expity_record)){ 
                    $DB->insert_records('simplecertificate_expiry_change_log', $expity_record); 
                }   
                if(!empty($issue_change_record)){   
                    $DB->insert_records('simplecertificate_issue_change_log', $issue_change_record);    
                }   
                if(!empty($issue_logs_record)){ 
                    $DB->insert_records('simplecertificate_issue_logs', $issue_logs_record);    
                }   
                    
                $SqlUpdateFlag = "UPDATE {simplecertificate} SET sfdc_update_flag = 1 WHERE id = '".$certificate_id."'";    
                $DB->execute($SqlUpdateFlag);   
                //$DB->set_debug(false);        
        }   
    }   
    return 0;   
}   
$editParams = 0;    
// -- - - - - - - End- Reporting Errors Case-II for Certificate Update Functions -  
//prev-start    
$imagefrombookmod = optional_param('imagefrombookmod','', PARAM_ALPHA); // param to identify the form values from book/mod_form and submitted by admin user 
    if($imagefrombookmod == "imagefrombookmod"){    
    $USER->imagefrombookmod = $imagefrombookmod;    
}   
//prev-end  
//Custom-1 end

$url = new moodle_url('/course/modedit.php');
$url->param('sr', $sectionreturn);
if (!empty($return)) {
    $url->param('return', $return);
}

if (!empty($add)) {
    $section = required_param('section', PARAM_INT);
    $course  = required_param('course', PARAM_INT);

    $url->param('add', $add);
    $url->param('section', $section);
    $url->param('course', $course);
    $PAGE->set_url($url);

    $course = $DB->get_record('course', array('id'=>$course), '*', MUST_EXIST);
    require_login($course);

    // There is no page for this in the navigation. The closest we'll have is the course section.
    // If the course section isn't displayed on the navigation this will fall back to the course which
    // will be the closest match we have.
    navigation_node::override_active_url(course_get_url($course, $section));

    // MDL-69431 Validate that $section (url param) does not exceed the maximum for this course / format.
    // If too high (e.g. section *id* not number) non-sequential sections inserted in course_sections table.
    // Then on import, backup fills 'gap' with empty sections (see restore_rebuild_course_cache). Avoid this.
    $courseformat = course_get_format($course);
    $maxsections = $courseformat->get_max_sections();
    if ($section > $maxsections) {
        throw new \moodle_exception('maxsectionslimit', 'moodle', '', $maxsections);
    }


    list($module, $context, $cw) = can_add_moduleinfo($course, $add, $section);

    $cm = null;

    $data = new stdClass();
    $data->section          = $section;  // The section number itself - relative!!! (section column in course_sections)
    $data->visible          = $cw->visible;
    $data->course           = $course->id;
    $data->module           = $module->id;
    $data->modulename       = $module->name;
    $data->groupmode        = $course->groupmode;
    $data->groupingid       = $course->defaultgroupingid;
    $data->id               = '';
    $data->instance         = '';
    $data->coursemodule     = '';
    $data->add              = $add;
    $data->return           = 0; //must be false if this is an add, go back to course view on cancel
    $data->sr               = $sectionreturn;

    if (plugin_supports('mod', $data->modulename, FEATURE_MOD_INTRO, true)) {
        $draftid_editor = file_get_submitted_draft_itemid('introeditor');
        file_prepare_draft_area($draftid_editor, null, null, null, null, array('subdirs'=>true));
        $data->introeditor = array('text'=>'', 'format'=>FORMAT_HTML, 'itemid'=>$draftid_editor); // TODO: add better default
    }

    if (plugin_supports('mod', $data->modulename, FEATURE_ADVANCED_GRADING, false)
            and has_capability('moodle/grade:managegradingforms', $context)) {
        require_once($CFG->dirroot.'/grade/grading/lib.php');

        $data->_advancedgradingdata['methods'] = grading_manager::available_methods();
        $areas = grading_manager::available_areas('mod_'.$module->name);

        foreach ($areas as $areaname => $areatitle) {
            $data->_advancedgradingdata['areas'][$areaname] = array(
                'title'  => $areatitle,
                'method' => '',
            );
            $formfield = 'advancedgradingmethod_'.$areaname;
            $data->{$formfield} = '';
        }
    }
    
    if (!empty($type)) { //TODO: hopefully will be removed in 2.0
        $data->type = $type;
    }

    $sectionname = get_section_name($course, $cw);
    $fullmodulename = get_string('modulename', $module->name);

    if ($data->section && $course->format != 'site') {
        $heading = new stdClass();
        $heading->what = $fullmodulename;
        $heading->to   = $sectionname;
        $pageheading = get_string('addinganewto', 'moodle', $heading);
    } else {
        $pageheading = get_string('addinganew', 'moodle', $fullmodulename);
    }
    $navbaraddition = $pageheading;

} else if (!empty($update)) {

    $url->param('update', $update);
    $PAGE->set_url($url);

    // Select the "Edit settings" from navigation.
    navigation_node::override_active_url(new moodle_url('/course/modedit.php', array('update'=>$update, 'return'=>1)));

    // Check the course module exists.
    $cm = get_coursemodule_from_id('', $update, 0, false, MUST_EXIST);

    // Check the course exists.
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

    // require_login
    require_login($course, false, $cm); // needed to setup proper $COURSE

    list($cm, $context, $module, $data, $cw) = can_update_moduleinfo($cm);

    $data->coursemodule       = $cm->id;
    $data->section            = $cw->section;  // The section number itself - relative!!! (section column in course_sections)
    $data->visible            = $cm->visible; //??  $cw->visible ? $cm->visible : 0; // section hiding overrides
    $data->cmidnumber         = $cm->idnumber;          // The cm IDnumber
    $data->groupmode          = groups_get_activity_groupmode($cm); // locked later if forced
    $data->groupingid         = $cm->groupingid;
    $data->course             = $course->id;
    $data->module             = $module->id;
    $data->modulename         = $module->name;
    $data->instance           = $cm->instance;
    $data->return             = $return;
    $data->sr                 = $sectionreturn;
    $data->update             = $update;
    $data->completion         = $cm->completion;
    $data->completionview     = $cm->completionview;
    $data->completionexpected = $cm->completionexpected;
    $data->completionusegrade = is_null($cm->completiongradeitemnumber) ? 0 : 1;
    // - - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -//
    $data->showfrontpage_flag    = $cm->showfrontpage_flag;
    $editParams = $cm->showfrontpage_flag;
    // - - -- - - - - -- End- Feature Request: Frontpage Latest content  - -- - -  -//
    $data->showdescription    = $cm->showdescription;
    if (!empty($CFG->enableavailability)) {
        $data->availabilityconditionsjson = $cm->availability;
    }

    if (plugin_supports('mod', $data->modulename, FEATURE_MOD_INTRO, true)) {
        $draftid_editor = file_get_submitted_draft_itemid('introeditor');
        $currentintro = file_prepare_draft_area($draftid_editor, $context->id, 'mod_'.$data->modulename, 'intro', 0, array('subdirs'=>true), $data->intro);
        $data->introeditor = array('text'=>$currentintro, 'format'=>$data->introformat, 'itemid'=>$draftid_editor);
    }

    if (plugin_supports('mod', $data->modulename, FEATURE_ADVANCED_GRADING, false)
            and has_capability('moodle/grade:managegradingforms', $context)) {
        require_once($CFG->dirroot.'/grade/grading/lib.php');
        $gradingman = get_grading_manager($context, 'mod_'.$data->modulename);
        $data->_advancedgradingdata['methods'] = $gradingman->get_available_methods();
        $areas = $gradingman->get_available_areas();

        foreach ($areas as $areaname => $areatitle) {
            $gradingman->set_area($areaname);
            $method = $gradingman->get_active_method();
            $data->_advancedgradingdata['areas'][$areaname] = array(
                'title'  => $areatitle,
                'method' => $method,
            );
            $formfield = 'advancedgradingmethod_'.$areaname;
            $data->{$formfield} = $method;
        }
    }

    if ($items = grade_item::fetch_all(array('itemtype'=>'mod', 'itemmodule'=>$data->modulename,
                                             'iteminstance'=>$data->instance, 'courseid'=>$course->id))) {
        // add existing outcomes
        foreach ($items as $item) {
            if (!empty($item->gradepass)) {
                $decimalpoints = $item->get_decimals();
                $data->gradepass = format_float($item->gradepass, $decimalpoints);
            }
            if (!empty($item->outcomeid)) {
                $data->{'outcome_'.$item->outcomeid} = 1;
            }
        }

        // set category if present
        $gradecat = false;
        foreach ($items as $item) {
            if ($gradecat === false) {
                $gradecat = $item->categoryid;
                continue;
            }
            if ($gradecat != $item->categoryid) {
                //mixed categories
                $gradecat = false;
                break;
            }
        }
        if ($gradecat !== false) {
            // do not set if mixed categories present
            $data->gradecat = $gradecat;
        }
    }

    $sectionname = get_section_name($course, $cw);
    $fullmodulename = get_string('modulename', $module->name);

    if ($data->section && $course->format != 'site') {
        $heading = new stdClass();
        $heading->what = $fullmodulename;
        $heading->in   = $sectionname;
        $pageheading = get_string('updatingain', 'moodle', $heading);
    } else {
        $pageheading = get_string('updatinga', 'moodle', $fullmodulename);
    }
    $navbaraddition = null;

} else {
    require_login();
    throw new \moodle_exception('invalidaction');
}

$pagepath = 'mod-' . $module->name . '-';
if (!empty($type)) { //TODO: hopefully will be removed in 2.0
    $pagepath .= $type;
} else {
    $pagepath .= 'mod';
}
$PAGE->set_pagetype($pagepath);
$PAGE->set_pagelayout('admin');
$PAGE->add_body_class('limitedwidth');


$modmoodleform = "$CFG->dirroot/mod/$module->name/mod_form.php";
if (file_exists($modmoodleform)) {
    require_once($modmoodleform);
} else {
    throw new \moodle_exception('noformdesc');
}

$mformclassname = 'mod_'.$module->name.'_mod_form';
$mform = new $mformclassname($data, $cw->section, $cm, $course);
$mform->set_data($data);

if ($mform->is_cancelled()) {
    if ($return && !empty($cm->id)) {
        $urlparams = [
            'id' => $cm->id, // We always need the activity id.
            'forceview' => 1, // Stop file downloads in resources.
        ];
        $activityurl = new moodle_url("/mod/$module->name/view.php", $urlparams);
        redirect($activityurl);
    } else {
        redirect(course_get_url($course, $cw->section, array('sr' => $sectionreturn)));
    }
} else if ($fromform = $mform->get_data()) {
    // Convert the grade pass value - we may be using a language which uses commas,    
    // rather than decimal points, in numbers. These need to be converted so that   
    // they can be added to the DB. 
    if (isset($fromform->gradepass)) {  
        $fromform->gradepass = unformat_float($fromform->gradepass);    
    }   
    //Custom-2  
  // -- Start Reporting Errors Case-II for Certificate Update Function for update certexpirydateinyear also update accocited certificated user expiry date. priprint_r($fromform->certexpirydateinyear); exit;  
    if (isset($fromform->certexpirydateinyear)) {   
        update_certificate_details($fromform->instance, $fromform->certexpirydateinyear);   
    }   
    // -- End Reporting Errors Case-II  
    //Custom-2 end
 
    // Mark that this is happening in the front-end UI. This is used to indicate that we are able to
    // do regrading with a progress bar and redirect, if necessary.
    $fromform->frontend = true;
    if (!empty($fromform->update)) {
        list($cm, $fromform) = update_moduleinfo($cm, $fromform, $course, $mform);
    } else if (!empty($fromform->add)) {
        $fromform = add_moduleinfo($fromform, $course, $mform);
    } else {
        throw new \moodle_exception('invaliddata');
    }

    if (isset($fromform->submitbutton)) {
        $url = new moodle_url("/mod/$module->name/view.php", array('id' => $fromform->coursemodule, 'forceview' => 1));
        if (!empty($fromform->showgradingmanagement)) {
            $url = $fromform->gradingman->get_management_url($url);
        }
    } else {
        $url = course_get_url($course, $cw->section, array('sr' => $sectionreturn));
    }

    // If we need to regrade the course with a progress bar as a result of updating this module,
    // redirect first to the page that will do this.
    if (isset($fromform->needsfrontendregrade)) {
        $url = new moodle_url('/course/modregrade.php', ['id' => $fromform->coursemodule,
                'url' => $url->out_as_local_url(false)]);
    }

    redirect($url);
    exit;

} else {

    $streditinga = get_string('editinga', 'moodle', $fullmodulename);
    $strmodulenameplural = get_string('modulenameplural', $module->name);

    if (!empty($cm->id)) {
        $context = context_module::instance($cm->id);
    } else {
        $context = context_course::instance($course->id);
    }
    $SFDC_count = 0;
    $sqlSCIECL = 'SELECT count(id) as cert_inprocess FROM {simplecertificate_expiry_change_log}  WHERE courseid='.$course->id.' and expiry_cron_flag = 0';
    $SCECL_obj = $DB->get_record_sql($sqlSCIECL);
    if(!empty($SCECL_obj))
    {
       $SFDC_count = $SCECL_obj->cert_inprocess;
    }

    $PAGE->set_heading($course->fullname);
    $PAGE->set_title($streditinga);
    $PAGE->set_cacheable(false);

    if (isset($navbaraddition)) {
        $PAGE->navbar->add($navbaraddition);
    }
    $PAGE->activityheader->disable();

    echo $OUTPUT->header();
    echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/theme/meline29/javascript/bootstrap.min-3.2.0.js"></script>';

    if (get_string_manager()->string_exists('modulename_help', $module->name)) {
        echo $OUTPUT->heading_with_help($pageheading, 'modulename', $module->name, 'monologo');
    } else {
        echo $OUTPUT->heading_with_help($pageheading, '', $module->name, 'monologo');
    }

    $mform->display();

    echo $OUTPUT->footer();

}
//Custom-3
// - - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -//
    $SqlCourseBook = "SELECT cm.id, b.name as lesson_title FROM {course_modules} as cm JOIN {book} as b on cm.instance = b.id WHERE cm.showfrontpage_flag = 1 ";
    $latestContent = $DB->get_records_sql($SqlCourseBook);
    if(isset($latestContent) && !empty($latestContent)){
        $countLatestContant = count($latestContent);
        $contantLink ="";
        foreach ($latestContent as $key) {
                $contantLink.= "<tr><td><a href='".$CFG->wwwroot."/mod/book/view.php?id=".$key->id."' target='_blank' >".$key->lesson_title."</a></td></tr>";
            }
        $contantLink = rtrim($contantLink,",");
        if(($countLatestContant >= 4 ) && ($editParams == 0)){

    ?>
    <script>
        $(document).ready(function(){
           var viewContent = "<a href='javascript:void(0);' data-toggle='modal' data-target='#memberModalNM'> View Content</a>";
            var linkView = "<?php echo $contantLink;?>";
            var SFDC_count = "<?php echo $SFDC_count; ?>";
            var modelHtml= '<div class="modal fade" id="memberModalNM" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true" style="display:none;">'+
                  '<div class="modal-dialog">'+
                    '<div class="modal-content">'+
                      '<div class="modal-header">'+
                        '<span style="font-size:16px;"> Training Frontpage Content List:<strong> </strong> </span>'+
                      '</div>'+
                      '<div class="modal-body">'+
                        '<table class="collection">'+
                        linkView+
                        '</table>'+
                        '</div>'+
                        '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Close</button></div>'+
                    '</div>'+
                  '</div>'+
                '</div>';
            $("#id_showfrontpage_flag").attr("disabled","disabled");
            $("#id_showfrontpage_flag").parent().append("<p style='color:red;'> To reset the existing Latest Content, click here: "+viewContent+modelHtml);
            if(SFDC_count != 0){
                $("#id_certexpirydateinyear").attr("disabled","disabled");
                $("#id_certexpirydateinyear").after("<span style='color:red;'> This option is in disabled mode, until the current certified users data should be completed.</span>");
            }
        });
    </script>
    <?php
    }
}
// - - -- - - - - -- End- Feature Request: Frontpage Latest content  - -- - -  -//
?>
<script>
    $(document).ready(function(){
        $("#id_thirdparty").attr("readonly","readonly");
        $("#id_thirdparty").css("background-color","white");
        $("#id_thirdparty").css("cursor","text");
        $('#id_thirdparty').on('focus',function(){
            $("#id_thirdparty").removeAttr("readonly");
        });

        // - - --- Start- Feature Request: "Update Help File" Field- Customized by Naveen - -- - -  -//
        $( "input[name='update_help_file']" ).on("change", function(){
            //$("#id_error_update_5fhelp_5ffile").css("display","none");
            $("#id_error_break_update_5fhelp_5ffile").remove();
            $("input[name='update_help_file']").css("margin-top","0px");
        })
        $(".mform").on("submit", function(){
            $("#id_error_break_update_5fhelp_5ffile").remove();
            $("input[name='update_help_file']").css("margin-top","0px");
        })
        // - - --- End- Feature Request: "Update Help File" Field - -- - -  -//
    });
</script>