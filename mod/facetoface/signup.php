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
 * ALTER TABLE `mdl_facetoface_signups` ADD `who_refer_you_title` TEXT NOT NULL AFTER `notificationtype`, ADD `terms` TINYINT NOT NULL AFTER `who_refer_you_title`, ADD `diet_restriction` VARCHAR(20) NOT NULL AFTER `terms`, ADD `who_refer_you_desc` TEXT NOT NULL AFTER `diet_restriction`;
 * ALTER TABLE `mdl_facetoface_signups` CHANGE `who_refer_you_title` `who_refer_you_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `terms` `terms` TINYINT(4) NULL, CHANGE `diet_restriction` `diet_restriction` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `who_refer_you_desc` `who_refer_you_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
 * ALTER TABLE `mdl_facetoface_signups` ADD `zipcode` VARCHAR(255) NULL AFTER `who_refer_you_desc`, ADD `promocode` VARCHAR(255) NULL AFTER `zipcode`, ADD `qsysonevalidate` TINYINT NULL AFTER `promocode`;
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once('lib.php');
require_once('renderer.php');
require_once('../../lib/csvlib.class.php');
//-- -- - - - - - - Start - Feature Request: Auto-Unenrolled Students   --//
require_once("$CFG->dirroot/enrol/locallib.php");
//-- -- - - - - - - End - Feature Request: Auto-Unenrolled Students   --// 

$s = required_param('s', PARAM_INT); // Facetoface session ID.
$backtoallsessions = optional_param('backtoallsessions', 0, PARAM_INT);

if (!$session = facetoface_get_session($s)) {
    print_error('error:incorrectcoursemodulesession', 'facetoface');
}
if (!$facetoface = $DB->get_record('facetoface', array('id' => $session->facetoface))) {
    print_error('error:incorrectfacetofaceid', 'facetoface');
}
if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
    print_error('error:coursemisconfigured', 'facetoface');
}
if (!$cm = get_coursemodule_from_instance("facetoface", $facetoface->id, $course->id)) {
    print_error('error:incorrectcoursemoduleid', 'facetoface');
}

require_course_login($course, true, $cm);
$context = context_course::instance($course->id);
$contextmodule = context_module::instance($cm->id);
require_capability('mod/facetoface:view', $context);

$returnurl = "$CFG->wwwroot/course/view.php?id=$course->id&y=check";
$isadmin = is_siteadmin($USER);

//Bug 2370 start
$bookedsession = $bookedsessionArr = array();
if ($submissions = facetoface_get_user_submissions($facetoface->id, $USER->id)) {
    $bookedsession = $submissions;
}
foreach ($bookedsession as $keyBS) {
    $bookedsessionArr[] = $keyBS;
}
$bookedsession = $bookedsessionArr;
if (count($bookedsession) >= 2) {
    $smsg = "You are already enrolled in two sessions.";
    redirect($returnurl, $smsg, 4);
}
//Bug 2370 end
//US 3072 start
$flagCountryRestrict = 0;
if ($records = $DB->get_record('facetoface_session_data', array('fieldid' => 15, 'sessionid' => $session->id))) {
    if (!empty($records->data)) {
        $countryArr = explode("##SEPARATOR##", $records->data);
        $countries = get_string_manager()->get_list_of_countries(false);
        foreach ($countryArr as $record) {
            if ($record == $USER->country) {
                $flagCountryRestrict = 1;
            } else {
                $countryStr .= "<li>" . $countries[$record] . "</li>";
            }
        }
    } else {
        $flagCountryRestrict = 2;
    }
} else {
    $flagCountryRestrict = 2;
}

//Show popup if country restrict flag is zero
if ($flagCountryRestrict == 0 && !isguestuser()) {

    $smsg = "This session is only accessible to residents of: <ul style='margin-left:20px;'>" . $countryStr . "</ul> Please register for another training session.";
    $returnurl = "$CFG->wwwroot/mod/facetoface/view.php?f=$facetoface->id";
    redirect($returnurl, $smsg, 10);
}
//US 3072 end
//US #824 changes
if (!$isadmin && !isguestuser() && $_SESSION['cntEnrol'] == 2) {

    if ($_SESSION[$course->id]['ccertstepscomp'] == 'certified') {
        redirect($returnurl);
    }
# check code using certification condition for student not attend session if he is already certified...
    $keyCertVal = $DB->get_record('simplecertificate', array('course' => $course->id), "id");
    $cmcOnlineVerObj = $DB->get_record('simplecertificate_issues', array('userid' => $USER->id, 'certificateid' => $keyCertVal->id), 'id');
    if (!empty($cmcOnlineVerObj)) {
        redirect($returnurl);
    }
}

if ($backtoallsessions) {
    $returnurl = "$CFG->wwwroot/mod/facetoface/view.php?f=$backtoallsessions";
}

$pagetitle = format_string($facetoface->name);

$PAGE->set_cm($cm);
$PAGE->set_url('/mod/facetoface/signup.php', array('s' => $s, 'backtoallsessions' => $backtoallsessions));

$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);
?>
<link rel="stylesheet" type="text/css" href="/mod/facetoface/styles.css"/>
<?php
// Guests can't signup for a session, so offer them a choice of logging in or going back.
if (isguestuser()) {
    ?>
    <link href="/theme/meline29/style/classroom.css?v=1.6" type="text/css" rel="stylesheet">

    <?php
    require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');

    $loginurl = $CFG->wwwroot . '/login/index.php';
    if (!empty($CFG->loginhttps)) {
        $loginurl = str_replace('http:', 'https:', $loginurl);
    }

    echo $OUTPUT->header();
    echo "<div id='notice' class='box generalbox'>";
    echo $out = html_writer::tag('h5', get_string('guestsno', 'facetoface')) .
    html_writer::tag('p', get_string('continuetologin', 'facetoface'));
    echo "<div class='hrefclss'></div>";
    echo "<div class='hrefclss'><a href='" . $CFG->wwwroot . "'>Cancel</a></div>";
    echo "</div>";
    $_SESSION['reservation_class'] = $backtoallsessions;
    echo $OUTPUT->footer();
    ?>
    <script type="text/javascript">
        var hrefcls = $(".path-mod-facetoface a.singinprovider:first").attr('href');

        $(".hrefclss:first").html("<a href='" + hrefcls + "'> Continue </a>");
    </script>   
    <?php
    exit();
}

$manageremail = false;
if (get_config(null, 'facetoface_addchangemanageremail')) {
//25 Aug Cust
    $manageremail = facetoface_get_manageremail($s);
//Make dynamic admin email address
//    $manageremail = "lms@mailinator.com";
}

$showdiscountcode = ($session->discountcost > 0);

$viewattendees = has_capability('mod/facetoface:viewattendees', $context);
$sessDetails = facetoface_signup_session($course->id, $session, $viewattendees);
$flags = $_SESSION['signupform'];

$session_timestartform = $session->sessiondates[0]->timestart;
$mform = new mod_facetoface_signup_form(null, compact('s', 'backtoallsessions', 'manageremail', 'showdiscountcode', 'flags', 'session_timestartform'));

if ($mform->is_cancelled()) {
    redirect($returnurl);
}

$flagZipCode = 0;
$errorPromoFlag = 0;
$_SESSION['localSalInfo'] = '';
$redirectFlag = 0;
$isadmin = is_siteadmin($USER);
if ($fromform = $mform->get_data()) { // Form submitted.
    $returnurltp = "$CFG->wwwroot/mod/facetoface/thankyou.php?s=$s&backtoallsessions=$backtoallsessions";
    if ($flags['Classroom Type'][1] == "Local Classes" && $flags['Level Type'][1] == "Level 1" && $session->sessiondates[0]->timestart == "2481889900" && $session->sessiondates[0]->timefinish == "2481889900") {
        if (!empty($fromform->zipcode)) {
            $getRecordIdQuery = "SELECT mdr.id FROM `mdl_data` as md join (mdl_data_records as mdr JOIN mdl_data_content as mdc ON mdr.id= mdc.recordid) ON md.id = mdr.dataid WHERE mdc.content='" . $fromform->zipcode . "' AND mdc.content !=0";

            $zipRecordIdData = $DB->get_records_sql($getRecordIdQuery);
            if (!empty($zipRecordIdData)) {
                $flagZipCodes = 1;
                foreach ($zipRecordIdData as $zipRecordIdDataVal) {
                    $zipQuery = "SELECT mdc.* FROM mdl_data_content as mdc WHERE mdc.recordid=" . $zipRecordIdDataVal->id;
                    $zipLocalSalData = $DB->get_records_sql($zipQuery);
                    if (!empty($zipLocalSalData)) {
                        //Additional Functionality for local sales rep. email cms
                        foreach ($zipLocalSalData as $zipLocalSalDataVal) {
                            # code...
                            if ($zipLocalSalDataVal->fieldid == 2) {
                                $localSalEmailArr['RepFirm'][] = ($zipLocalSalDataVal->content) ? $zipLocalSalDataVal->content : '';
                            }
                            if ($zipLocalSalDataVal->fieldid == 3) {
                                $localSalEmailArr['ContactEmail'][] = ($zipLocalSalDataVal->content) ? $zipLocalSalDataVal->content : '';
                            }
                            if ($zipLocalSalDataVal->fieldid == 4) {
                                $localSalEmailArr['CC1'][] = ($zipLocalSalDataVal->content) ? $zipLocalSalDataVal->content : '';
                            }
                            if ($zipLocalSalDataVal->fieldid == 5) {
                                $localSalEmailArr['CC2'][] = ($zipLocalSalDataVal->content) ? $zipLocalSalDataVal->content : '';
                            }
                            if ($zipLocalSalDataVal->fieldid == 7) {
                                $localSalEmailArr['CC3'][] = ($zipLocalSalDataVal->content) ? $zipLocalSalDataVal->content : '';
                            }
                        } //end foreach $zipLocalSalData
                        $_SESSION['localSalInfo'] = $localSalEmailArr;
                    }
                }//end foreach
            }//end if zipRecordIdData
            else {
                $flagZipCode = 2;
            }
        }// end if $fromform->zipcode        
    }//end if classroom Type

    //Filter promocode
    if ($fromform->promocode == htmlspecialchars_decode($flags['Promocode'][1])) {
        $errorPromoFlag = 0;
    } else {
        $errorPromo = "Please enter correct promocode";
        $errorPromoFlag = 1;
    }
    // Server Validate for zipcode and promocode 
    if ($errorPromoFlag == 0 && $flagZipCode == 0) {
        if (empty($fromform->submitbutton)) {
            print_error('error:unknownbuttonclicked', 'facetoface', $returnurl);
        }
        // Get signup type.
        if (!$session->datetimeknown) {
            $statuscode = MDL_F2F_STATUS_WAITLISTED;
        } else if (facetoface_get_num_attendees($session->id) < $session->capacity) {
            // Save available.
            $statuscode = MDL_F2F_STATUS_BOOKED;    
        } else {
            $statuscode = MDL_F2F_STATUS_WAITLISTED;
        }
        //US 3009 start
        $sqlFSH = 'SELECT id, flag FROM {facetoface_session_hold} WHERE session_id=' . $session->id;
        $fshObjData = $DB->get_record_sql($sqlFSH);
        if (!empty($fshObjData)) {
            if ($fshObjData->flag == 1) {
                print_error('sessionisfull', 'facetoface', $returnurl);
            }
        }
        //US 3009 end
        if (!facetoface_session_has_capacity($session, $context) && (!$session->allowoverbook)) {
            print_error('sessionisfull', 'facetoface', $returnurl);
        } /* US #824 changes for commenting this code
          else if (facetoface_get_user_submissions($facetoface->id, $USER->id)) {
          } */ else if ($manageremail == '') {
            print_error('error:manageremailaddressmissing', 'facetoface', $returnurl);
        } else if ($submissionid = facetoface_user_signup($session, $facetoface, $course, $fromform->discountcode, $fromform->notificationtype, $statuscode)) {

            // Logging and events trigger.
            $params = array(
                'context' => $contextmodule,
                'objectid' => $session->id
            );
            $event = \mod_facetoface\event\signup_success::create($params);
            $event->add_record_snapshot('facetoface_sessions', $session);
            $event->add_record_snapshot('facetoface', $facetoface);
            $event->trigger();

            $message = get_string('bookingcompleted', 'facetoface');
            if ($session->datetimeknown && $facetoface->confirmationinstrmngr) {
                $message .= html_writer::empty_tag('br') . html_writer::empty_tag('br') . get_string('confirmationsentmgr', 'facetoface');
            } else {
                $message .= html_writer::empty_tag('br') . html_writer::empty_tag('br') . get_string('confirmationsent', 'facetoface');
            }

            $timemessage = 4;

            if ($isadmin) {
                redirect($returnurl, $message, $timemessage);
            } else {
                redirect($returnurltp);
            }
        } else {
            // Logging and events trigger.
            $params = array(
                'context' => $contextmodule,
                'objectid' => $session->id
            );
            $event = \mod_facetoface\event\signup_failed::create($params);
            $event->add_record_snapshot('facetoface_sessions', $session);
            $event->add_record_snapshot('facetoface', $facetoface);
            $event->trigger();

            print_error('error:problemsigningup', 'facetoface', $returnurl);
        }
        $errorPromoFlag = 0;
        $flagZipCode = 0;
        unset($SESSION['localSalInfo']);
        if ($isadmin) {
            redirect($returnurl);
        } else {
            redirect($returnurltp);
        }
    }
    
}

if ($redirect == 1) {
    echo $returnurl;
    exit;
}

echo $OUTPUT->header();

$heading = get_string('signupfor', 'facetoface', $facetoface->name);

//US #824 changes add if cond
if ($_SESSION['cntEnrol'] == 2 && $fidhold == $facetoface->id) {
    $signedup = facetoface_check_signup($facetoface->id);
        if ($signedup and $signedup != $session->id) {
        print_error('error:signedupinothersession', 'facetoface', $returnurl);
    }
}

echo $OUTPUT->box_start();
?>
<link href="/theme/meline29/style/classroom.css?v=1.6" type="text/css" rel="stylesheet">
<!--Work Starts Here-->

<!--Two Page Work Start Here-->
<div class="classroom-whole-section page-two2">
<?php
$levelType = '';
if ($flags) {
    $levelType = $flags['Level Type'][1];
    if ($facetoface->id == 17) {
        ?>
            <div class="classroom-head-with-logo"> <img src="/mod/facetoface/Contro_UCI_logo@3x.png"/> </div>
        <?php
    } elseif ($levelType == "Level 1" && $facetoface->id != 14 && $facetoface->id != 18 && $facetoface->id != 10) {
        ?>
            <div class="classroom-head-with-logo"> <img src="/theme/meline29/pix/classroom/Level1-Classroom-Logo.png"/> </div>
        <?php
    } elseif ($levelType == "Level 2") {
        ?>
            <div class="classroom-head-with-logo"> <img src="/theme/meline29/pix/classroom/level_2_heading_logo.png"/> </div>
            <?php
        }
    }
    ?>

    <?php
    $timenow = time();
    if ($session->datetimeknown && facetoface_has_session_started($session, $timenow)) {
//PP1 start
        /* $inprogressstr = get_string('cannotsignupsessioninprogress', 'facetoface');
          $overstr = get_string('cannotsignupsessionover', 'facetoface');

$errorstring = facetoface_is_session_in_progress($session, $timenow) ? $inprogressstr : $overstr; */

        if ($session->timefinish >= $timenow) {
            $errorstring = get_string('sessionover', 'facetoface');
//PP1 end
            echo html_writer::empty_tag('br') . $errorstring;
            echo $OUTPUT->box_end();
            echo $OUTPUT->footer($course);
            exit;
//PP1 start
        }
//PP1 end
    }

//US 3009 start
    $sqlFSH = 'SELECT id, flag FROM {facetoface_session_hold} WHERE session_id=' . $session->id;
    $fshObjData = $DB->get_record_sql($sqlFSH);
    if (!empty($fshObjData)) {
        if ($fshObjData->flag == 1) {
            echo get_string('sessionisfull', 'facetoface', $returnurl);
            echo $OUTPUT->box_end();
            echo $OUTPUT->footer($course);
            exit;
        }
    }
//US 3009 end

    $session_timestartform = $session->sessiondates[0]->timestart;
    $mform = new mod_facetoface_signup_form(null, compact('s', 'backtoallsessions', 'manageremail', 'showdiscountcode', 'flags', 'session_timestartform'));
    if ($flags) {
        $classroomType = $flags['Classroom Type'][1];
        $levelType = $flags['Level Type'][1];
//TRP start
        if (($classroomType == "Inside QSC" || $classroomType == "Local Classes") && $levelType == "Level 1") {
//TRP end
            ?>
            <div class="page2_second-section test">
                <h4><?= get_string('signupSubHeading', 'facetoface') ?></h4> 
                <!-- Customization start  -->
                <div class="address-details-classroom"> <span class="intitutionname"><?= $sessDetails[6][1] ?></span>
            <?php
            if ($session->sessiondates[0]->timestart != "2481889900" && $session->sessiondates[0]->timefinish != "2481889900") {
                ?>

                        <span class="day-date-classroom"><span style="margin-right:40px;"><?php echo strtoupper(get_string('date', 'facetoface')) . ":  " ?></span><?= $sessDetails[17][1] ?></span>
                        <span class="time-classroom"><span style="margin-right:45px;"><?php echo strtoupper(get_string('time', 'facetoface')) . ": " ?></span><?= $sessDetails[18][1] ?></span>

                        <span class="time-classroom"><span style="margin-right:17px;"><?php echo strtoupper(get_string('time_length', 'facetoface')) . " :" ?></span>
                    <?php echo $sessDetails[10][1]; ?></span> <?php
        }
        ?>
                    <span class="address-full"><span style="margin-right:7px;"><?php echo strtoupper(get_string('location', 'facetoface')) . ": " ?></span><?= $sessDetails[1][1] ?></span>
                    <br><span class="address-full"><span style="margin-right:11px;"><?php echo strtoupper(get_string('address', 'facetoface')) . ": " ?></span><?= $sessDetails[2][1] ?></span>
                    <?php ////TRP start } //TRP end?>
                </div>
            </div>

        <?php
    } elseif ($levelType == "Level 1" && $session->sessiondates[0]->timestart == "2481889900" && $session->sessiondates[0]->timefinish == "2481889900") {
        ?>
            <span>
                Complete this form and a member of QSC's regional training staff will work with you to schedule a regional training in your area.
                <br><br>
                For a list of all QSC regional sales representatives <a href="http://www.qsc.com/systems/contact-us/" class="simple">click here</a>.
            </span>
                    <?php
                }
                if ($levelType == "Level 2") {
                    ?>
            <div class="page2_second-section">
                <h4><?= get_string('signupSubHeading', 'facetoface') ?></h4>
                    <?php //echo $sessDetails[12][1]; ?>
                <!--div class="address-details-classroom"> <span class="intitutionname"><?= $sessDetails[6][1] ?></span>
                   <span class="day-date-classroom"><?php echo strtoupper(get_string('date', 'facetoface')) . " : " ?><?= $sessDetails[8][1] ?></span>
                   <span class="time-classroom"><?php echo strtoupper(get_string('time', 'facetoface')) . " : " ?><?= $sessDetails[9][1] ?></span>
        <div class ="test">
                        <span class="time-classroom"><?php echo strtoupper(get_string('time_length', 'facetoface')) . " :" ?>
            <?php echo $sessDetails[10][1]; ?></span>
                    
                   <span class="address-full"><?php echo strtoupper(get_string('location', 'facetoface')) . " : " ?><?= $sessDetails[1][1] ?></span>
                   <br><span class="address-full"><?php echo strtoupper(get_string('address', 'facetoface')) . " : " ?><?= $sessDetails[2][1] ?></span>
               </div>

           </div-->

                <div class="address-details-classroom"> <span class="intitutionname"><?= $sessDetails[6][1] ?></span>

            <?php
            if ($session->sessiondates[0]->timestart != "2481889900" && $session->sessiondates[0]->timefinish != "2481889900") {
                ?>

                        <span class="day-date-classroom"><span style="margin-right:40px;"><?php echo strtoupper(get_string('date', 'facetoface')) . ":  " ?></span><?= $sessDetails[17][1] ?></span>
                        <span class="time-classroom"><span style="margin-right:45px;"><?php echo strtoupper(get_string('time', 'facetoface')) . ": " ?></span><?= $sessDetails[18][1] ?></span>

                        <span class="time-classroom"><span style="margin-right:17px;"><?php echo strtoupper(get_string('time_length', 'facetoface')) . " :" ?></span>
            <?php echo $sessDetails[10][1]; ?></span>
                <?php } ?>

                    <span class="address-full"><span style="margin-right:7px;"><?php echo strtoupper(get_string('location', 'facetoface')) . ": " ?></span><?= $sessDetails[1][1] ?></span>
                    <br><span class="address-full"><span style="margin-right:11px;"><?php echo strtoupper(get_string('address', 'facetoface')) . ": " ?></span><?= $sessDetails[2][1] ?></span> </div>
            </div>

            <!-- Customization end  -->
        <?php
        }
    }
?>
            <?php
            if ($signedup) {
                if (!($session->datetimeknown && facetoface_has_session_started($session, $timenow))) {

                    // Cancellation link.
                    $cancellationurl = new moodle_url('cancelsignup.php', array('s' => $session->id, 'backtoallsessions' => $backtoallsessions));
                    echo html_writer::link($cancellationurl, get_string('cancelbooking', 'facetoface'), array('title' => get_string('cancelbooking', 'facetoface')));
                    //echo ' &ndash; ';
                }

                // See attendees link.
                if ($viewattendees) {
                    $attendeesurl = new moodle_url('attendees.php', array('s' => $session->id, 'backtoallsessions' => $backtoallsessions));
                    echo html_writer::link($attendeesurl, get_string('seeattendees', 'facetoface'), array('title' => get_string('seeattendees', 'facetoface')));
                }

                //echo html_writer::empty_tag('br') . html_writer::link($returnurl, get_string('goback', 'facetoface'), array('title' => get_string('goback', 'facetoface')));
                echo html_writer::link($returnurl, get_string('goback', 'facetoface'), array('title' => get_string('goback', 'facetoface')));
            } else if ($manageremail == '') {

                // Don't allow signup to proceed if a manager is required.
                // Check to see if the user has a managers email set.
                echo html_writer::tag('p', html_writer::tag('strong', get_string('error:manageremailaddressmissing', 'facetoface')));
                echo html_writer::empty_tag('br') . html_writer::link($returnurl, get_string('goback', 'facetoface'), array('title' => get_string('goback', 'facetoface')));

            } else if (!has_capability('mod/facetoface:signup', $context)) {
                echo html_writer::tag('p', html_writer::tag('strong', get_string('error:nopermissiontosignup', 'facetoface')));
                echo html_writer::empty_tag('br') . html_writer::link($returnurl, get_string('goback', 'facetoface'), array('title' => get_string('goback', 'facetoface')));
            } else {

                // Signup form.
                echo '<div class="classroom-third-section-form-start">';
                $mform->display();
                echo '</div>';
                ?>

    </div>
    <!--Two Page Work Ends Here--> 

    <?php
    }
echo $OUTPUT->box_end();
echo $OUTPUT->footer($course);
//-- -- - - - - - - Start - Feature Request: Auto-Unenrolled Students -Nav  --//

    if (isset($facetoface) && !empty($course)) {
        $sqlSCertificate = $DB->get_record_sql("SELECT sci.id FROM mdl_simplecertificate_issues as sci LEFT JOIN mdl_simplecertificate as sc ON sc.id= sci.certificateid WHERE sc.course =" . $course->id . " AND sci.userid = " . $USER->id);
        if (empty($sqlSCertificate)) {
            $log_record2->userid = $USER->id;
            $log_record2->course_id = $course->id;
            $log_record2->pageName = "Session Signup";
            $log_record2->logMessage = "Without user simplecertificate record.";
            $log_record2->logTime = time();
            $DB->insert_record('autoenrol_logs', $log_record2);
            $sqlSSignup = "SELECT DISTINCT d.id AS ssignup_ids
              FROM {facetoface} f
              JOIN {facetoface_sessions} s ON s.facetoface = f.id
              JOIN {facetoface_signups} d ON d.sessionid = s.id
             WHERE f.id = ? AND d.userid = ?";
            $recordsSSignup = $DB->get_records_sql($sqlSSignup, array($facetoface->id, $USER->id));
            if (empty($recordsSSignup)) {
                $log_record21 = new stdClass();
                $log_record21->userid = $USER->id;
                $log_record21->course_id = $course->id;
                $log_record21->pageName = "Session Signup";
                $log_record21->logMessage = "Without session call 'unenrolled_classroom_session' with SFDC true.";
                $log_record21->logTime = time();
                $DB->insert_record('autoenrol_logs', $log_record21);

                //Task 2177 start Add variable 
                $unenrol_flag_return = unenrolled_classroom_session($course->id, $USER, 0);
                if ($unenrol_flag_return !== 5) {
                    //Task 2177 end Add variable
                    $sqlDel = "DELETE FROM {course_completions} WHERE userid =" . $USER->id . " AND course =" . $course->id;
                    $sqlDelRes = $DB->execute($sqlDel);
                    //Task 2177 start
                }
                //Task 2177 end
            } else {
                $recordsSSignupId = "";
                $log_record3 = new stdClass();
                $log_record3->userid = $USER->id;
                $log_record3->course_id = $course->id;
                $log_record3->pageName = "Session Signup";
                $log_record3->logMessage = "Enrolled in any session call .";
                $log_record3->logTime = time();
                $DB->insert_record('autoenrol_logs', $log_record3);

                foreach ($recordsSSignup as $SSvalue) {
                    $recordsSSignupId .= $SSvalue->ssignup_ids . ",";
                }
                $recordsSSignupId = rtrim($recordsSSignupId, ",");
                $sqlSessionStatus = $DB->get_record_sql("SELECT id, statuscode FROM {facetoface_signups_status} WHERE signupid IN (" . $recordsSSignupId . ") AND superceded = 0  ORDER BY timecreated DESC");
                if (!empty($sqlSessionStatus)) {

                    $SS_statuscode = $sqlSessionStatus->statuscode;
                    if ($SS_statuscode == 10 || $SS_statuscode == 20 || $SS_statuscode == 30 || $SS_statuscode == 40 || $SS_statuscode == 50 || $SS_statuscode == 80 || $SS_statuscode == 90 || $SS_statuscode == 100) {

                        $log_record4 = new stdClass();
                        $log_record4->userid = $USER->id;
                        $log_record4->course_id = $course->id;
                        $log_record4->pageName = "Session Signup";
                        $log_record4->logMessage = "Enrolled in any session and Session Status is >" . $SS_statuscode;
                        $log_record4->logTime = time();
                        $DB->insert_record('autoenrol_logs', $log_record4); 
                        //Task 2177 start Add variable 
                        $unenrol_flag_return = unenrolled_classroom_session($course->id, $USER, 0);
                        if ($unenrol_flag_return !== 5) {
                            //Task 2177 end Add variable
                            $sqlDel = "DELETE FROM {course_completions} WHERE userid =" . $USER->id . " AND course =" . $course->id;
                            $sqlDelRes = $DB->execute($sqlDel);
                            //Task 2177 start
                        }
                        //Task 2177 end
                    }
                }
            }
        }
    }

//-- -- - - - - - - End - Feature Request: Auto-Unenrolled Students -Nav  --//
    ?>
<script>
    $(".fdescription.required").hide();
    var errorPromoFlag = "<?= $errorPromoFlag ?>";
    if (errorPromoFlag == "1") {
        $('#fitem_id_promocode').append('<span id="id_error_promocode" class="error" tabindex="0"> <?= get_string("permissioncode_notfound_error", "facetoface") ?> </span>');
        $('#id_error_promocode').focus();
        $('#id_qsysonevalidate,#id_terms_smartphone,#id_terms,#id_who_refer_you_title').append('<option value="">I do not agree</option>');
        $(document).ready(function () {
        $("#id_who_refer_you_title").attr("readonly", "readonly");
        $("#id_who_refer_you_title").css("background-color", "white");
        $("#id_who_refer_you_title").css("cursor", "text");
        $("#id_submitbutton").css("margin-left", "0px");

        $('#id_who_refer_you_title').on('focus', function () {
            $("#id_who_refer_you_title").removeAttr("readonly");
        });
    });
</script>
