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
 * Version information
 *
 * @package    tool
 * @subpackage mergeusers
 * @author     Nicolas Dunand <Nicolas.Dunand@unil.ch>
 * @author     Mike Holzer
 * @author     Forrest Gaston
 * @author     Juan Pablo Torres Herrera
 * @author     Jordi Pujol-Ahulló, Sred, Universitat Rovira i Virgili
 * @author     John Hoopes <hoopes@wisc.edu>, University of Wisconsin - Madison
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../../config.php');

global $CFG;
global $DB;
global $PAGE;
global $SESSION;

// Report all PHP errors
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

require_once($CFG->libdir . '/blocklib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/accesslib.php');
require_once($CFG->libdir . '/weblib.php');

require_once('./index_form.php');
require_once(__DIR__ . '/lib/autoload.php');

require_login();
require_capability('tool/mergeusers:mergeusers', context_system::instance());

admin_externalpage_setup('tool_mergeusers_merge');

// Get possible posted params
$option = optional_param('option', null, PARAM_TEXT);
if (!$option) {
    if (optional_param('clearselection', false, PARAM_TEXT)) {
        $option = 'clearselection';
    } else if (optional_param('mergeusers', false, PARAM_TEXT)) {
        $option = 'mergeusers';
    }
}

// Define the form
$mergeuserform = new mergeuserform();
$renderer = $PAGE->get_renderer('tool_mergeusers');

$data = $mergeuserform->get_data();

//may abort execution if database not supported, for security
$mut = new MergeUserTool();
// Search tool for searching for users and verifying them
$mus = new MergeUserSearch();

// If there was a custom option submitted (by custom form) then use that option
// instead of main form's data
if (!empty($option)) {
    switch ($option) {
        // one or two users are selected: save them into session.
        case 'saveselection':
            //get and verify the userids from the selection form usig the verify_user function (second field is column)
            list($olduser, $oumessage) = $mus->verify_user(optional_param('olduser', null, PARAM_INT), 'id');
            list($newuser, $numessage) = $mus->verify_user(optional_param('newuser', null, PARAM_INT), 'id');

            if ($olduser === null && $newuser === null) {
                $renderer->mu_error(get_string('no_saveselection', 'tool_mergeusers'));
                exit(); // end execution for error
            }

            if (empty($SESSION->mut)) {
                $SESSION->mut = new stdClass();
            }

            // Store saved selection in session for display on index page, requires logic to not overwrite existing session
            //   data, unless a "new" old, or "new" new is specified
            // If session old user already has a user and we have a "new" old user, replace the sesson's old user
            if (empty($SESSION->mut->olduser) || !empty($olduser)) {
                $SESSION->mut->olduser = $olduser;
            }

            // If session new user already has a user and we have a "new" new user, replace the sesson's new user
            if (empty($SESSION->mut->newuser) || !empty($newuser)) {
                $SESSION->mut->newuser = $newuser;
            }

            $step = (!empty($SESSION->mut->olduser) && !empty($SESSION->mut->newuser)) ?
                    $renderer::INDEX_PAGE_CONFIRMATION_STEP :
                    $renderer::INDEX_PAGE_SEARCH_STEP;

            echo $renderer->index_page($mergeuserform, $step);
            break;

        // remove any of the selected users to merge, and search for them again.
        case 'clearselection':
            $SESSION->mut = null;

            // Redirect back to index/search page for new selections or review selections
            $redirecturl = new moodle_url('/admin/tool/mergeusers/index.php');
            redirect($redirecturl, null, 0);
            break;

        // proceed with the merging and show results.
        case 'mergeusers':
            // Verify users once more just to be sure.  Both users should already be verified, but just an extra layer of security
            list($fromuser, $oumessage) = $mus->verify_user($SESSION->mut->olduser->id, 'id');
            list($touser, $numessage) = $mus->verify_user($SESSION->mut->newuser->id, 'id');
            if ($fromuser === null || $touser === null) {
                $renderer->mu_error($oumessage . '<br />' . $numessage);
                break; // break execution for error
            }

            // Merge the users
            $log = array();
            $success = true;
            list($success, $log, $logid) = $mut->merge($touser->id, $fromuser->id);
            
                        if($success == true){
                //To update user new profile in SFDC start - customo
                $sqlUserSFDCData = "SELECT usf.id, usf.userid, usf.lastaccess
                FROM {user_sfdc} usf WHERE usf.userid = ".$SESSION->mut->newuser->id." order by id desc LIMIT 0,1";
                $sqlSFDCUserObjData = $DB->get_record_sql($sqlUserSFDCData);
                if(empty($sqlSFDCUserObjData)){
                    $insert_header = "INSERT INTO `mdl_user_sfdc` (`userid`, `lastaccess`) VALUES (".$SESSION->mut->newuser->id.", '888')";
//echo "<pre>"; print_r($SESSION); exit;
                    $DB->execute($insert_header);
                }
                else{
                    $update_header2 = "update mdl_user_sfdc set lastaccess = '888' where mdl_user_sfdc.id = ".$sqlSFDCUserObjData->id;
                    $DB->execute($update_header2);
                }
                //To update user new profile in SFDC end - customo

                //Delete old user profile from SFDC start - customo
                $sqlUserSFDCData = "SELECT usf.id, usf.userid, usf.lastaccess
                FROM {user_sfdc} usf WHERE usf.userid = ".$SESSION->mut->olduser->id." order by id desc LIMIT 0,1";
                $sqlSFDCUserObjData = $DB->get_record_sql($sqlUserSFDCData);
                if(empty($sqlSFDCUserObjData)){
                    $insert_header = "INSERT INTO `mdl_user_sfdc` (`userid`, `lastaccess`) VALUES (".$SESSION->mut->olduser->id.", '10101')";
                    $DB->execute($insert_header);
                }
                else{
                    $update_header2 = "update mdl_user_sfdc set lastaccess = '10101' where mdl_user_sfdc.id = ".$sqlSFDCUserObjData->id;
                    $DB->execute($update_header2); 
                }
                //Delete old user profile from SFDC end - customo

 //Delete user old certificate data from DB and Insert into History table start - Nav
                $sqlUserSCDCData = "SELECT count(usi.id) as certi_count, usi.certificateid, usi.timeexpired FROM {simplecertificate_issues} usi WHERE usi.userid = ".$SESSION->mut->newuser->id." Group by usi.certificateid order by id asc ";
                $sqlSCFUserObjData = $DB->get_records_sql($sqlUserSCDCData);
                if(isset($sqlSCFUserObjData) && !empty($sqlSCFUserObjData))
                {
                    foreach ($sqlSCFUserObjData as $resObj) {
                            if($resObj->certi_count >1)
                            {
                                $sqlCERTIFCData = "SELECT  * FROM {simplecertificate_issues} usi WHERE usi.userid = ".$SESSION->mut->newuser->id." AND usi.certificateid =".$resObj->certificateid." order by usi.timeexpired ASC LIMIT 0,1 ";
                                $sqlCERTIFCObjData = $DB->get_record_sql($sqlCERTIFCData);
                                if(!empty($sqlCERTIFCObjData)){

                                    //- - - - - - - - - Course ID -- - - - - - -//
                                    $sqlSCFData = "SELECT  course,certexpirydateinyear FROM {simplecertificate} WHERE id =".$resObj->certificateid." order by id asc LIMIT 0,1 ";
                                    $sqlSCFObjData = $DB->get_record_sql($sqlSCFData);
                                     $courseId= $sqlSCFObjData->course;
                                    $certExpiryDateInYear = $sqlSCFObjData->certexpirydateinyear;
                                    //- - - - - - - - - Course ID -- - - - - - -//
                                    $newUserSCIRenewedDate = "";
                                    $oldUserSCIRenewedDate = "";

                                    $RenewedDateAfterMerge = "";
                                    $certifiedDate    ="";
                                    $certifiedExpired = "";
                                    $sqlCERTIFCNewData = "SELECT  * FROM {simplecertificate_issues} usin WHERE usin.userid = ".$SESSION->mut->newuser->id." AND usin.certificateid =".$resObj->certificateid." order by usin.timeexpired DESC LIMIT 0,1 ";
                                    $sqlSCFNEWUserObjData = $DB->get_record_sql($sqlCERTIFCNewData);
                                    //echo "<pre>"; print_r($sqlCERTIFCNewData);
                                    if(isset($sqlSCFNEWUserObjData) && !empty($sqlSCFNEWUserObjData))
                                    {

                                        $certifiedDate    = $sqlSCFNEWUserObjData->timecreated;
                                        $certifiedExpired = $sqlSCFNEWUserObjData->timeexpired;
                                        //echo "Created Date". $certifiedDate. "Expired date ".$certifiedExpired." <br>";
                                        // ------------------New User Renewed Date  - - - - - - -//
                                        $sqlSCIL = 'SELECT * FROM {simplecertificate_issue_logs} scil where userid = '.$SESSION->mut->newuser->id.' and courseid = '.$courseId.' AND timecompletion IS NOT NULL  order by timecompletion desc limit 0,1';
                                        $sqlSCILObj = $DB->get_record_sql($sqlSCIL);
                                        if($sqlSCILObj && $courseId!=42){
                                            $renTimeComp = $sqlSCILObj->timecompletion;
                                            $cExpDateStr = strtotime($certExpiryDateInYear." years",$renTimeComp);
                                            if(($renTimeComp != $certifiedDate) && ($certifiedExpired ==$cExpDateStr) && !empty($renTimeComp)){
                                                $newUserSCIRenewedDate = $renTimeComp;
                                            }
                                        }
                                        // ------------------Old User Renewed Date  - - - - - - -//
                                        $sqlSCILold = 'SELECT * FROM {simplecertificate_issue_logs} scil where userid = '.$SESSION->mut->olduser->id.' and courseid = '.$courseId.' AND timecompletion IS NOT NULL  order by timecompletion desc limit 0,1';
                                        //echo "OLD =".$sqlSCILold;
                                        $sqlSCILObjold = $DB->get_record_sql($sqlSCILold);
                                        //echo "<pre> <hr>"; print_r($sqlSCILObjold);
                                        if($sqlSCILObjold && $courseId!=42){
                                            $renTimeComp_old = $sqlSCILObjold->timecompletion;
                                            $cExpDateStr = strtotime($certExpiryDateInYear." years",$renTimeComp_old);
                                            if(($renTimeComp_old != $certifiedDate) && ($certifiedExpired ==$cExpDateStr) && !empty($renTimeComp_old)){
                                                $oldUserSCIRenewedDate = $renTimeComp_old;
                                            }
                                        }
                                    }
                                   //echo "<pre>";
                                   //echo "certifiedDate = ".$certifiedDate."<br> ";
                                   //echo "newUserSCIRenewedDate = ".$newUserSCIRenewedDate."oldUserSCIRenewedDate = ".$oldUserSCIRenewedDate."<br> ";
                                    if(!empty($newUserSCIRenewedDate) && !empty($oldUserSCIRenewedDate)){
                                        if($newUserSCIRenewedDate > $oldUserSCIRenewedDate)
                                            {
                                               $RenewedDateAfterMerge =  $newUserSCIRenewedDate;
                                            }
                                        elseif($oldUserSCIRenewedDate > $newUserSCIRenewedDate)
                                            {
                                                $RenewedDateAfterMerge =  $oldUserSCIRenewedDate;
                                            }
                                    }elseif(empty($newUserSCIRenewedDate) && !empty($oldUserSCIRenewedDate)){
                                            $RenewedDateAfterMerge =  $oldUserSCIRenewedDate;
                                    }elseif(!empty($newUserSCIRenewedDate) && empty($oldUserSCIRenewedDate)){
                                            $RenewedDateAfterMerge =  $newUserSCIRenewedDate;
                                    }else{
                                        $cExpDateStr34 = strtotime("-".$certExpiryDateInYear." years",$certifiedExpired);
                                        $cExpDateStr35 = strtotime("-".$certExpiryDateInYear." years",$cExpDateStr34);
                                        if($certifiedDate <= $cExpDateStr35)
                                        {
                                           $RenewedDateAfterMerge= $cExpDateStr34;
                                        }
                                    }
                                    
                                    //echo "Renewed Date : ".$RenewedDateAfterMerge;
                                   
                                    $responce_I= $DB->insert_record('simplecertificate_issues_history', $sqlCERTIFCObjData);
                                   if(isset($responce_I) &&($responce_I> 0)){
                                        
                                        if(isset($courseId) && !empty($courseId))
                                        {
                                           $DB->delete_records('simplecertificate_issue_logs', array('courseid'=>$courseId, "userid"=>$SESSION->mut->newuser->id));
                                           $DB->delete_records('simplecertificate_issue_change_log', array('courseid'=>$courseId, "userid"=>$SESSION->mut->newuser->id)); 
                                        }
                                        $simplecertificate_issues_id= $sqlCERTIFCObjData->id;
                                        $DB->delete_records('simplecertificate_issues', array('id'=>$simplecertificate_issues_id, "userid"=>$SESSION->mut->newuser->id));

                                        $sqlCERTRenewedObjLogData1 = new stdClass();
                                        $sqlCERTRenewedObjLogData1->courseid = $courseId;
                                        $sqlCERTRenewedObjLogData1->userid = $SESSION->mut->newuser->id;
                                        $sqlCERTRenewedObjLogData1->timecompletion = $sqlSCFNEWUserObjData->timecreated;
                                        $sqlCERTRenewedObjLogData1->timeexpired = $sqlSCFNEWUserObjData->timeexpired;
                                        $responce_SILog1= $DB->insert_record('simplecertificate_issue_logs', $sqlCERTRenewedObjLogData1);
                                        
                                        if(isset($RenewedDateAfterMerge) && !empty($RenewedDateAfterMerge)){
                                            $sqlCERTRenewedObjLogData = new stdClass();
                                            $sqlCERTRenewedObjLogData->courseid = $courseId;
                                            $sqlCERTRenewedObjLogData->userid = $SESSION->mut->newuser->id;
                                            $sqlCERTRenewedObjLogData->timecompletion = $RenewedDateAfterMerge;
                                            //echo "Success <pre>"; print_r($sqlCERTRenewedObjLogData);
                                            $responce_SILog= $DB->insert_record('simplecertificate_issue_logs', $sqlCERTRenewedObjLogData);
                                        }
                                        
                                        $coursesModulesSQL = "SELECT * FROM {course_completions} WHERE course =".$courseId." AND userid=".$SESSION->mut->newuser->id." order by timeenrolled limit 0,1";
                                              $coursesModulesSQLRS = $DB->get_record_sql($coursesModulesSQL);
                                            if(!empty($coursesModulesSQLRS)){
                                                $coursesModulesSQLRS->timecompleted = $sqlSCFNEWUserObjData->timecreated;
                                                $DB->update_record('course_completions',$coursesModulesSQLRS);
                                            }
                                   }
                                }
                                //echo $resObj->certi_count." certificate ID ".$resObj->certificateid."<br>";
                                //echo "Success <pre>"; print_r($resObj);
                            }
                            elseif($resObj->certi_count == 1){
                                //- - - - - - - - - Course ID -- - - - - - -//
                                    $sqlSCFData = "SELECT  course,certexpirydateinyear FROM {simplecertificate} WHERE id =".$resObj->certificateid." order by id asc LIMIT 0,1 ";
                                    $sqlSCFObjData = $DB->get_record_sql($sqlSCFData);
                                    $courseId= $sqlSCFObjData->course;
                                    $certExpiryDateInYear = $sqlSCFObjData->certexpirydateinyear;
                                    //- - - - - - - - - Course ID -- - - - - - -//

                                    $RenewedDateAfterMerge = "";
                                    $sqlCERTIFCNewData = "SELECT  * FROM {simplecertificate_issues} usin WHERE usin.userid = ".$SESSION->mut->newuser->id." AND usin.certificateid =".$resObj->certificateid." order by usin.timeexpired DESC LIMIT 0,1 ";
                                    $sqlSCFNEWUserObjData = $DB->get_record_sql($sqlCERTIFCNewData);
                                    //echo "<pre>"; print_r($sqlCERTIFCNewData);
                                    if(isset($sqlSCFNEWUserObjData) && !empty($sqlSCFNEWUserObjData))
                                    {

                                        $certifiedDate    = $sqlSCFNEWUserObjData->timecreated;
                                        $certifiedExpired = $sqlSCFNEWUserObjData->timeexpired;
                                        //echo "Created Date". $certifiedDate. "Expired date ".$certifiedExpired." <br>";
                                        // ------------------New User Renewed Date  - - - - - - -//
                                        $sqlSCIL = 'SELECT * FROM {simplecertificate_issue_logs} scil where userid = '.$sqlSCFNEWUserObjData->userid.' and courseid = '.$courseId.' AND timecompletion IS NOT NULL  order by timecompletion desc limit 0,1';
                                        $sqlSCILObj = $DB->get_record_sql($sqlSCIL);
                                        if($sqlSCILObj && $courseId!=42){
                                            $renTimeComp = $sqlSCILObj->timecompletion;
                                            $cExpDateStr = strtotime($certExpiryDateInYear." years",$renTimeComp);
                                            if(($renTimeComp != $certifiedDate) && ($certifiedExpired ==$cExpDateStr) && !empty($renTimeComp)){
                                                $RenewedDateAfterMerge = $renTimeComp;
                                            }
                                            if(!empty($RenewedDateAfterMerge)){
                                                $sqlCERTRenewedObjLogData = new stdClass();
                                                $sqlCERTRenewedObjLogData->courseid = $courseId;
                                                $sqlCERTRenewedObjLogData->userid = $SESSION->mut->newuser->id;
                                                $sqlCERTRenewedObjLogData->timecompletion = $RenewedDateAfterMerge;
                                                //echo "Success <pre>"; print_r($sqlCERTRenewedObjLogData);
                                                $responce_SILog= $DB->insert_record('simplecertificate_issue_logs', $sqlCERTRenewedObjLogData);
                                            }
                                        }
                                    }
                            }
                            unset($RenewedDateAfterMerge); 
                        //}
                    }
                    //echo "<pre>"; print_r($sqlCERTRenewedObjLogData); 
                    //exit; unset($sqlSCFUserObjData); //free memory
                }
                //Delete user old certificate data from DB and Insert into History table end - Nav
                //die(" die delete");
                
            }

            // reset mut session
            $SESSION->mut = null;

            // render results page
            echo $renderer->results_page($touser, $fromuser, $success, $log, $logid);
            break;

        // we have both users to merge selected, but we want to change any of them.
        case 'searchusers':
            echo $renderer->index_page($mergeuserform, $renderer::INDEX_PAGE_SEARCH_STEP);
            break;

        // we have both users to merge selected, and in the search step, we
        // want to proceed with the merging of the currently selected users.
        case 'continueselection':
            echo $renderer->index_page($mergeuserform, $renderer::INDEX_PAGE_CONFIRMATION_STEP);
            break;

        // ops!
        default:
            $renderer->mu_error(get_string('invalid_option', 'tool_mergeusers'));
            break;
    }
// Any submitted data?
} else if ($data) {
    // If there is a search argument use this instead of advanced form
    if (!empty($data->searchgroup['searcharg'])) {

        $search_users = $mus->search_users($data->searchgroup['searcharg'], $data->searchgroup['searchfield']);
        $user_select_table = new UserSelectTable($search_users, $renderer);

        echo $renderer->index_page($mergeuserform, $renderer::INDEX_PAGE_SEARCH_AND_SELECT_STEP, $user_select_table);

        // only run this step if there are both a new and old userids
    } else if (!empty($data->oldusergroup['olduserid']) && !empty($data->newusergroup['newuserid'])) {
        //get and verify the userids from the selection form usig the verify_user function (second field is column)
        list($olduser, $oumessage) = $mus->verify_user($data->oldusergroup['olduserid'], $data->oldusergroup['olduseridtype']);
        list($newuser, $numessage) = $mus->verify_user($data->newusergroup['newuserid'], $data->newusergroup['newuseridtype']);

        if ($olduser === null || $newuser === null) {
            $renderer->mu_error($oumessage . '<br />' . $numessage);
            exit(); // end execution for error
        }
        // Add users to session for review step
        if (empty($SESSION->mut)) {
            $SESSION->mut = new stdClass();
        }
        // - - - - - - - - - - Start -PrivateCoursePhase2  NAV
        //$SESSION->mut->olduser = $olduser;
        //$SESSION->mut->newuser = $newuser;

        if($olduser->deleted ==0 && $olduser->suspended ==0){
            $SESSION->mut->olduser = $olduser;
            $SESSION->mut->newuser = $newuser;

        }else{
            $renderer->mu_error("<h3> Removed user already merged and/or deleted.</h3>");
            exit(); // end execution for error
        }
        // - - - - - - - - - - End -PrivateCoursePhase2  NAV

        echo $renderer->index_page($mergeuserform, $renderer::INDEX_PAGE_SEARCH_AND_SELECT_STEP);
    } else {
        // simply show search form.
        echo $renderer->index_page($mergeuserform, $renderer::INDEX_PAGE_SEARCH_STEP);
    }
} else {
    // no form submitted data
    echo $renderer->index_page($mergeuserform, $renderer::INDEX_PAGE_SEARCH_STEP);
}
?>
<script>
    $(document).ready(function(){
        $("#id_oldusergroup_olduserid").attr("readonly","readonly");
        $("#id_oldusergroup_olduserid").css("background-color","white");
        $("#id_oldusergroup_olduserid").css("cursor","text");
        $('#id_oldusergroup_olduserid').on('focus',function(){
            $("#id_oldusergroup_olduserid").removeAttr("readonly");
        });

    });
</script>