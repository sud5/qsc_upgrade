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
    require_once($CFG->dirroot . '/mod/facetoface/lib.php');
    ?>
<?php
    // Face-to-face session ID.
    $s = required_param('s', PARAM_INT);
    
    $takeattendance=0;
    //$takeattendance = optional_param('takeattendance', false, PARAM_BOOL); // Take attendance.
    $takeattendance2 = optional_param('takeattendance', false, PARAM_BOOL); // Take attendance.
    $cancelform = optional_param('cancelform', false, PARAM_BOOL); // Cancel request.
    $backtoallsessions = optional_param('backtoallsessions', 0, PARAM_INT); // Face-to-face activity to return to.
    
    // Load data.
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
        print_error('error:incorrectcoursemodule', 'facetoface');
    }

     // Task #2314 start 
    //Getting list of countries
    $listOfCountries = get_string_manager()->get_list_of_countries(true);
    // Task #2314 end 

    
    // Load attendees.
    $attendees = facetoface_get_attendees($session->id);
    
    $attendeesIdArr =  array();
    foreach ($attendees as $key => $value) {
        $attendeesIdArr[$key] = $key;
    }
    // Load cancellations.
    $cancellations = facetoface_get_cancellations($session->id);
    
    
    /*
     * Capability checks to see if the current user can view this page
     *
     * This page is a bit of a special case in this respect as there are four uses for this page.
     *
     * 1) Viewing attendee list
     *   - Requires mod/facetoface:viewattendees capability in the course
     *
     * 2) Viewing cancellation list
     *   - Requires mod/facetoface:viewcancellations capability in the course
     *
     * 3) Taking attendance
     *   - Requires mod/facetoface:takeattendance capabilities in the course
     */
    $context = context_course::instance($course->id);
    $contextmodule = context_module::instance($cm->id);
    require_course_login($course);
    
    $roles = get_user_roles($context, $USER->id);
    $userRoles = array();
    foreach ($roles as $role) {
        $userRoles[] = $role->shortname;
    }

    $hasregionaladminrole = 0;
    if(in_array('regionaladmin', $userRoles)){
        $hasregionaladminrole = 1;
    }

    // Actions the user can perform.
    $canviewattendees = has_capability('mod/facetoface:viewattendees', $context);
    //59 Est Instrcutor Feature Start
    if($_REQUEST['crole'] == "instructor"){
        $_SESSION['crole'] = "instructor";
        $customdata = $DB->get_records('facetoface_session_data', array('sessionid' => $session->id), '', 'fieldid, data');
        $sessiondatains->customfielddata = $customdata;
        //echo "<pre>"; print_r($sessiondatains); exit("Success");
        if (strpos($sessiondatains->customfielddata[3]->data, $USER->username) !== false) {
            $canviewattendees=1;
        } elseif ($hasregionaladminrole){
            $canviewattendees=1;
        } else{
            $canviewattendees='';
        }     
    }
    //59 Est Instructor Feature End

    $cantakeattendance=0;
    $cantakeattendance2 = has_capability('mod/facetoface:takeattendance', $context);
    //$cantakeattendance = has_capability('mod/facetoface:takeattendance', $context);
    $canviewcancellations = has_capability('mod/facetoface:viewcancellations', $context);
    //59 Est Instrcutor Feature Start
    if($_REQUEST['crole'] == "instructor"){
        $customdata = $DB->get_records('facetoface_session_data', array('sessionid' => $session->id), '', 'fieldid, data');
        $sessiondatains->customfielddata = $customdata;
        //echo "<pre>"; print_r($sessiondatains); exit("Success");
        if (strpos($sessiondatains->customfielddata[3]->data, $USER->username) !== false) {
            $canviewcancellations=1;
        } elseif ($hasregionaladminrole){
            $canviewcancellations=1;
        } else{
            $canviewcancellations='';
        }     
    }
    //59 Est Instructor Feature End
    $canviewsession = $canviewattendees || $cantakeattendance || $canviewcancellations;
    $canapproverequests = false;
    
    $requests = array();
    $declines = array();
    
    // If a user can take attendance, they can approve staff's booking requests.
    if ($cantakeattendance) {
        $requests = facetoface_get_requests($session->id);
    }
    
    // If requests found (but not in the middle of taking attendance), show requests table.
    if ($requests && !$takeattendance) {
        $canapproverequests = true;
    }
    
    // Check the user is allowed to view this page.

    if (!$canviewattendees && !$cantakeattendance && !$canapproverequests && !$canviewcancellations) {
        if ($form = data_submitted()) {
            if($form->confirm_roster){
                $flagpermission=1;
            }
            else{
                print_error('nopermissions', '', "{$CFG->wwwroot}/mod/facetoface/view.php?id={$cm->id}", get_string('view'));
            }
        }
        else{
            //exit("Suc12");
            print_error('nopermissions', '', "{$CFG->wwwroot}/mod/facetoface/view.php?id={$cm->id}", get_string('view'));
        }
    }
    
    // Check user has permissions to take attendance.
    if ($takeattendance && !$cantakeattendance) {
       // exit("Suc123");
        print_error('nopermissions', '', '', get_capability_string('mod/facetoface:takeattendance'));
    }
    
    
    /*
     * Handle submitted data
     */
    if ($form = data_submitted()) {
        //exit("Suc1eee2");
        if (!confirm_sesskey()) {
            print_error('confirmsesskeybad', 'error');
        }
    
        $return = "{$CFG->wwwroot}/mod/facetoface/attendees.php?s={$s}&backtoallsessions={$backtoallsessions}";
    
        if ($cancelform) {
            redirect($return);
        } else if (!empty($form->requests)) {
    
            // Approve requests.
            if ($canapproverequests && facetoface_approve_requests($form)) {
    
                // Logging and events trigger.
                $params = array(
                    'context'  => $contextmodule,
                    'objectid' => $session->id
                );
                $event = \mod_facetoface\event\approve_requests::create($params);
                $event->add_record_snapshot('facetoface_sessions', $session);
                $event->add_record_snapshot('facetoface', $facetoface);
                $event->trigger();
            }
    
            redirect($return);
        } else if ($takeattendance) {
            if (facetoface_take_attendance($form)) {
    
                // Logging and events trigger.
                $params = array(
                    'context'  => $contextmodule,
                    'objectid' => $session->id
                );
                $event = \mod_facetoface\event\take_attendance::create($params);
                $event->add_record_snapshot('facetoface_sessions', $session);
                $event->add_record_snapshot('facetoface', $facetoface);
                $event->trigger();
            } else {
    
                // Logging and events trigger.
                $params = array(
                    'context'  => $contextmodule,
                    'objectid' => $session->id
                );
                $event = \mod_facetoface\event\take_attendance_failed::create($params);
                $event->add_record_snapshot('facetoface_sessions', $session);
                $event->add_record_snapshot('facetoface', $facetoface);
                $event->trigger();
            }
              redirect($return.'&takeattendance=1');
        }
        // ----- customization_Naveen_Start -------//
        elseif(isset($form->confirm_roster)){
          $sessionId = $form->s;
          //BUG 2533 start
            $ctime = time();
            // if($sessionId == 1370){
            //    echo "<pre>"; print_r($session); print_r($cancellations); print_r($attendees); print_r($facetoface); 
            //    exit("Success");
            // }
            //Task #2061 start
            //this scenario use to get future enrollment of users in session who enrolled in current session. If current and future enrollment occurs then remove entry from the future session.
            //$featurestartdate = strtotime('-5 days', strtotime('2020-08-23'));
            //BUG 2533 replacement in time
            $featurestartdate = time();
            if(!empty($facetoface->notattendedautomatedemailsub))
                $confirmRSessCancelSubject = $facetoface->notattendedautomatedemailsub;  // email Subject
            else
                $confirmRSessCancelSubject = "Please add subject";  // email Subject
            $confirmRSessCancelMsg = $facetoface->notattendedautomatedemailmsg;
            //Bug 2383 start replace previous code under this foreach loop 
            // foreach ($cancellations as $attendeesKey) {
            //BUG 2533 change foreach loop for getting current sessions' users enrolled in session
            foreach ($attendees as $attendeesKey) {
                $getCancelledUserDetails = "select id, username, country, email from mdl_user where id =".$attendeesKey->id;
                $rsUserRecArr = $DB->get_record_sql($getCancelledUserDetails);
                $attendeesKey->username = $rsUserRecArr->username;
                
                //BUG #2533 replacement in the below query
                #bug 2980 add filter for same classroom 
                $ftof_signups_query7 = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id JOIN mdl_facetoface_sessions_dates sd ON sd.sessionid=fse.id  WHERE sd.timestart > $featurestartdate  AND sd.timestart!=2481889900 and fse.facetoface = $facetoface->id and fs.userid = $attendeesKey->id AND fss.superceded=0 AND fss.statuscode != 10 AND fse.id != $sessionId  order by fss.id desc limit 0,1";

                $ftof_signups_result7Obj = $DB->get_record_sql($ftof_signups_query7);

                if($attendeesKey->statuscode == 60){
                    if( empty($ftof_signups_result7Obj) ){
                        $ftof_signups_result7Obj = new stdClass();
                    }
                    $ftof_signups_result7Obj->id=$sessionId;
                }elseif($attendeesKey->statuscode == MDL_F2F_STATUS_BOOKED){ // US #17926 start
                    $signup = $DB->get_record('facetoface_signups', array('sessionid' => $sessionId, 'userid' => $attendeesKey->id)); 
                    if ($signup) {
                        facetoface_update_signup_status($signup->id, $attendeesKey->statuscode, $attendeesKey->id, '', null, 1);
                    }
                } // US #17926 end

                $ftof_signups_result7 = $ftof_signups_result7Obj;
                //foreach( $ftof_signups_result7Obj as $ftof_signups_result7) {
                    #bug 2980 add condition for same classroom 
                    if( !empty($ftof_signups_result7->id) && $ftof_signups_result7->roll_call_status == 0) {
                        
                        //Current session details
                        $customfielddata = facetoface_get_customfielddata($sessionId);
                        if ((!empty($customfielddata['location']) && trim($customfielddata['location']->data) != '') || (!empty($customfielddata['Location']) && trim($customfielddata['Location']->data) != '')) {
                            $locationstr = ($customfielddata['location']->data)?$customfielddata['location']->data:$customfielddata['Location']->data;
                        }
                        //Cancelled users' session id
                        $ncustomfielddata = facetoface_get_customfielddata($ftof_signups_result7->id);
                        if ((!empty($ncustomfielddata['location']) && trim($ncustomfielddata['location']->data) != '') || (!empty($ncustomfielddata['Location']) && trim($ncustomfielddata['Location']->data) != '')) {
                            $nlocationstr = ($ncustomfielddata['Location']->data)?$ncustomfielddata['Location']->data:$ncustomfielddata['location']->data;
                        }
                        //get session cancelled for the relevant users
                        $cancelSess = facetoface_get_session($ftof_signups_result7->id);
                        // Delete/cancel other sessions
                        if (facetoface_user_cancel($ftof_signups_result7, $attendeesKey->id, true)) {
                            //exit;

                            // Sent an email to inform the users about this process
                            if(!empty($attendeesKey))
                            {
                                if ($fromaddress = get_config('noreply@qsc.com', 'facetoface_fromaddress')) {
                                    $from = new stdClass();
                                    $from->maildisplay = true;
                                    $from->email = $fromaddress;
                                } else {
                                    $from = null;
                                }
                                //Adjust macro in email body //Need to check this 
                                $msg = $confirmRSessCancelMsg;

                                $sessiondate = userdate($session->sessiondates[0]->timestart, get_string('strftimedate'));
                                $nsessiondate = userdate($cancelSess->sessiondates[0]->timestart, get_string('strftimedate'));
                                $userFirstSessionTime = $session->sessiondates[0]->timestart;
                                $userSecondSessionTime = $cancelSess->sessiondates[0]->timestart;

                                $msg=str_replace(get_string('placeholder:facetofacename','facetoface'),$facetoface->name,$msg);
                                $msg=str_replace(get_string('placeholder:firstname','facetoface'),$attendeesKey->firstname,$msg);
                                $msg=str_replace(get_string('placeholder:lastname','facetoface'),$attendeesKey->lastname,$msg);
                                $msg = str_replace(get_string('placeholder:sessiondate','facetoface'),$sessiondate,$msg);
                                $msg = str_replace('[nsessiondate]',$nsessiondate,$msg);

                                //Need to find location
                                $msg = str_replace('[session:Location]', $locationstr, $msg);
                                $msg = str_replace('[nsession:Location]', $nlocationstr, $msg);
                                $confirmRSessCancelMsg1 = $msg;
                                $mngrObj = new stdClass();
                                $mngrObj->mailformat = 1;
                                $attendeesKey->mailformat = 1;
                                //foreach($attendees as $attendeesObj){
                                    //if($attendeesObj->statuscode == 70){
                                        $posthtml = $confirmRSessCancelMsg1;
                                        $mngrObj->email = $attendeesKey->username;                                       
                                        
                                        if (!email_to_user($mngrObj, $from, $confirmRSessCancelSubject, $confirmRSessCancelMsg1, $posthtml)) {
                                            return 'error:cannotsendconfirmationuser';
                                        }
                                        //$confirmRSessCancelMsg='';
                                        $insert_header = "INSERT INTO mdl_facetoface_cancel_session_student_email_log (session_id, email) VALUES (".$sessionId.",'".$mngrObj->email."')";
                                        $DB->execute($insert_header);
                                    //}
                               // }//end for each                               
                            }
                        }
                    }
             // }                  
            }
            //Bug 2383 end replace previous code under this foreach loop 
            //BUG 2533 end
            //exit("Successxxx");
            //Task #2061 end

            $sqlUpdate_rollcall = "update {facetoface_sessions} set roll_call_status = 1, roll_call_modified_by = ".$USER->id." where id = ".$sessionId."";
            $DB->execute($sqlUpdate_rollcall);
            //set flag
            // $sqlSessEmailData = "SELECT usf.id, usf.userid, usf.lastaccess
            //                     FROM {facetoface_session_email_flag} fsef WHERE fsef.sessid = $sessionId and flag=1 order by id desc LIMIT 0,1";
            // $sqlSessEmailObjData = $DB->get_record_sql($sqlSessEmailData);
            $confirmRSubject = $facetoface->attendedautomatedemailsub;  // email Subject
            $confirmRMSG = $facetoface->attendedautomatedemailmsg;
            $ctime=time();
            if($session){
                foreach ($session->sessiondates as $date) { 
                    //if session time+24hrs is less then confired roster current time (SessionTime+24hours < timenow confirmed roaster = msg will be sending to users in case of confirmed roster)
                    $pt_time = strtotime(date("Y-m-d h:i:s")); //based on server
                    $ctime = time();
                    $timedifference = $ctime-$pt_time; 
                    $onedaylater = $date->timestart - $timedifference + 60*60*24;    
                    if ($onedaylater < $ctime) {
                        /* $attendees = facetoface_get_attendees($sessionid); //already calling from above*/
                        if(!empty($attendees))
                        {
                            if ($fromaddress = get_config('noreply@qsc.com', 'facetoface_fromaddress')) {
                                $from = new stdClass();
                                $from->maildisplay = true;
                                $from->email = $fromaddress;
                            } else {
                                $from = null;
                            }
                            $mngrObj = new stdClass();
                            $mngrObj->mailformat = 1;
                            $attendeesObj = new stdClass();
                            $attendeesObj->mailformat = 1;
                            foreach($attendees as $attendeesObj){
                                if($attendeesObj->statuscode == MDL_F2F_STATUS_BOOKED){
                                    $posthtml = $confirmRMSG;
                                    $mngrObj->email = $attendeesObj->username;
                                    if (!email_to_user($mngrObj, $from, $confirmRSubject, $confirmRMSG, $posthtml)) {
                                        return 'error:cannotsendconfirmationuser';
                                    }
                                    $insert_header = "INSERT INTO mdl_facetoface_session_student_email_log (session_id, email) VALUES (".$sessionId.",'".$mngrObj->email."')";
                                    $DB->execute($insert_header);
                                }
                            }//end for each
                            $insert_header = "INSERT INTO `mdl_facetoface_session_email_flag` (`session_id`, `flag`) VALUES (".$sessionId.", 2000)";
                            $DB->execute($insert_header);
                        }
                    } 
                }
            }

            if($_SESSION['crole'] == 'instructor'){
                redirect("/mod/facetoface/view.php?f=".$facetoface->id."&crole=instructor","Roster confirmed!  Thank you!!!");
            }
            else{
                redirect($return,"Roster confirmed!  Thank you!");
            }
        }
        // ----- customization_Naveen_End -------//
    }
    
    
    /*
     * Print page header
     */
    
    // Logging and events trigger.
    $params = array(
        'context'  => $contextmodule,
        'objectid' => $session->id
    );
    $event = \mod_facetoface\event\attendees_viewed::create($params);
    $event->add_record_snapshot('facetoface_sessions', $session);
    $event->add_record_snapshot('facetoface', $facetoface);
    $event->trigger();
    
    $pagetitle = format_string($facetoface->name);
    
    $PAGE->set_url('/mod/facetoface/attendees.php', array('s' => $s));
    $PAGE->set_context($context);
    $PAGE->set_cm($cm);
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title($pagetitle);
    $PAGE->set_heading($course->fullname);
    
    echo $OUTPUT->header();
    
    /*
     * Print page content
     */
    
    // If taking attendance, make sure the session has already started.
    if ($takeattendance && $session->datetimeknown && !facetoface_has_session_started($session, time())) {
        $link = "{$CFG->wwwroot}/mod/facetoface/attendees.php?s={$session->id}";
        print_error('error:canttakeattendanceforunstartedsession', 'facetoface', $link);
    }
    
    echo $OUTPUT->box_start();
    echo $OUTPUT->heading(format_string($facetoface->name));
    ?>
<script src="/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<?php
    if ($canviewsession) {
        echo facetoface_print_session($session, true);
    }
    
    /*
     * Print attendees (if user able to view)
     */
    if ($canviewattendees || $cantakeattendance) {
        if ($takeattendance) {
            $heading = get_string('takeattendance', 'facetoface');
        } else {
            $heading = get_string('attendees', 'facetoface');
        }
    
        echo $OUTPUT->heading($heading);
    
        if (empty($attendees)) {
            echo $OUTPUT->notification(get_string('nosignedupusers', 'facetoface'));
        } else {
            if ($takeattendance) {
                $attendeesurl = new moodle_url('attendees.php', array('s' => $s, 'takeattendance' => '1'));
                echo html_writer::start_tag('form', array('action' => $attendeesurl, 'method' => 'post'));
                echo html_writer::tag('p', get_string('attendanceinstructions', 'facetoface'));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => $USER->sesskey));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 's', 'value' => $s));
                echo html_writer::empty_tag('input', array('type' => 'hidden', ' name' => 'backtoallsessions', 'value' => $backtoallsessions)) . '</p>';
    
                // Prepare status options array.
                $statusoptions = array();
                foreach ($MDL_F2F_STATUS as $key => $value) {
                    if ($key <= MDL_F2F_STATUS_BOOKED) {
                        continue;
                    }
    
                    $statusoptions[$key] = get_string('status_'.$value, 'facetoface');
                }
            }
    
            $table = new html_table();
            $table->head = array(get_string('name'));
            $table->summary = get_string('attendeestablesummary', 'facetoface');
            $table->align = array('left');
            $table->size = array('100%');
            $table->attributes['class'] = 'generaltable attendeestable';
    
            if ($takeattendance) {
                $table->head[] = get_string('currentstatus', 'facetoface');
                $table->align[] = 'center';
                $table->head[] = get_string('attendedsession', 'facetoface');
                $table->align[] = 'center';
            } else {
                if (!get_config(null, 'facetoface_hidecost')) {
                    $table->head[] = get_string('cost', 'facetoface');
                    $table->align[] = 'center';
                    if (!get_config(null, 'facetoface_hidediscount')) {
                        $table->head[] = get_string('discountcode', 'facetoface');
                        $table->align[] = 'center';
                    }
                }
            //Customization Start by Sameer
                $table->head[] = get_string('company','facetoface');
                $table->align = array('left');
                $table->size = array('10%');

                //Task #2314 start
                $table->head[] = get_string('country','facetoface');
                $table->align = array('left');
                $table->size = array('10%');
                //Task #2314 end
    
                $table->head[] = get_string('email','facetoface');
                $table->size = array('10%');
                $table->align = array('left');
    
                $table->align = array('left');
                $table->head[] = get_string('dietheading','facetoface');
                $table->size = array('20%');
                $table->align = array('left');
    
    //Classroom Signup Code start
                $table->head[] = get_string('status','facetoface');
                $table->size = array('20%');
                $table->align = array('left');
    
    // Addition Certification Columns Start
    //if($facetoface->course == 42){
    // Addition Certification Columns end
    
    //change-1

    $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$facetoface->course,'criteriatype'=>8),'','id, course, courseinstance');
$pri_course_fullname='';
 

    foreach($coursesData as $keyCDVal){

          $keyCD1 = $keyCDVal;
          $primaryCourseSQL = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$keyCDVal->courseinstance;
          $primaryCourseRes = $DB->get_record_sql($primaryCourseSQL); 
          $pri_course_category = $primaryCourseRes->category;
          $pri_course_fullname = format_string($primaryCourseRes->fullname);  
             
    }
            if($keyCD1){
                $sqlcourseData = "SELECT id,course,courseinstance FROM {course_completion_criteria} WHERE course=".$facetoface->course." and criteriatype =8 order by id limit 0,1";
                  $rescourseData = $DB->get_record_sql($sqlcourseData); 


$primaryCourseSQL2 = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$rescourseData->courseinstance;
          $primaryCourseRes2 = $DB->get_record_sql($primaryCourseSQL2); 
  $pri_course_fullname2 = format_string($primaryCourseRes2->fullname); 
                $table->head[] = $pri_course_fullname2;
                $table->size = array('20%');
                $table->align = array('left');

                $courseSQL = 'SELECT id,category,fullname FROM {course} WHERE id ='.$facetoface->course;
                $courseRes = $DB->get_record_sql($courseSQL);
                $course_fullname = format_string($courseRes->fullname);

                $table->head[] = $course_fullname;
                $table->size = array('20%');
                $table->align = array('left');
            }
            else{
                $courseSQL = 'SELECT id,category,fullname FROM {course} WHERE id ='.$facetoface->course;
                $courseRes = $DB->get_record_sql($courseSQL);
                $course_fullname = format_string($courseRes->fullname);

                $table->head[] = $course_fullname;
                $table->size = array('20%');
                $table->align = array('left');
            }
    
    // Addition Certification Columns end
    
    //Classroom Signup Code end
    
                $levelFlag = 0;
                foreach ($attendees as $attendeeFlag) {
                    if($attendeeFlag->data == "Level 2"){
                        $levelFlag = 1;
                    }
                }
    
                if($levelFlag == 1){
                    $table->head[] = get_string('levelheading','facetoface');
                    $table->size = array('20%');
                }
                
            if($_SESSION['ctype']==1){
                    $table->head[] = get_string('attendance', 'facetoface');
                    $table->align[] = 'center';
            }
            //Customization End by Sameer
            } ?>
<?php
            foreach ($attendees as $attendee) {
                $data = array();
               //4635 change view.php to profile.php
                $attendeeurl = new moodle_url('/user/profile.php', array('id' => $attendee->id, 'course' => $course->id));

                    $data[] = html_writer::link($attendeeurl, format_string(fullname($attendee)));
                // End US 4635 commented below if cond code and else cond code
                //59 Est Instructor Feature End
           //Course Completions Logics Start
            $courseid = $attendee->course;
            $userid = $attendee->id;
    
    // Addition Certification Columns Start
            //change-3

            $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$course->id,'criteriatype'=>8),'','id, course, courseinstance');

            foreach($coursesData as $keyCDVal){
              $keyCD1 = $keyCDVal;
              $primaryCourseSQL = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$keyCDVal->courseinstance;
              $primaryCourseRes = $DB->get_record_sql($primaryCourseSQL); 
              $pri_course_category = $primaryCourseRes->category;
              $pri_course_fullname = format_string($primaryCourseRes->fullname);
            }
            if($pri_course_category){

                //For single course dependency
               if(count($coursesData) == 1){
                  $sql = "SELECT id as courseinstance FROM {course} c WHERE category = $pri_course_category and id=$primaryCourseRes->id order by id";
                }
                else{
                    $sql = "SELECT id as courseinstance FROM {course} c WHERE category = $pri_course_category and visible=1 order by id";
                                    }
        // Addition Certification Columns end
                $rs = $DB->get_records_sql($sql);
            }
            else{
                  $sql = "SELECT id as courseinstance FROM {course} c WHERE id = $course->id and visible=1 order by id";
                                    //}
        // Addition Certification Columns end
                $rs = $DB->get_records_sql($sql);
            }

            if($rs){
    //Level 2 Submission Restriction Start
            $flagrs=0;

            foreach($rs as $rskey){
                if($flagrs==0){
                        $sqlcc = "SELECT * FROM {course_completions} WHERE userid = $userid AND course = $rskey->courseinstance";
                        $rscc = $DB->get_record_sql($sqlcc);
                        /* Certification level one status for by pawan start */
                        //if(!empty($rscc)){    
            /* Certification level one status for by pawan end */
                                //change-121
                                $primaryCourseSQL = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$rskey->courseinstance;
                                $primaryCourseRes = $DB->get_record_sql($primaryCourseSQL); 
                                $pri_course_category = $primaryCourseRes->category;
                                $pri_course_fullname = format_string($primaryCourseRes->fullname);     
                                $statusCourseComp = "NOT CERTIFIED";

                                //60 Est Start
                                $catCourseArr = [];
                                foreach($rs as $rskey2){
                                    $catCourseArr[] = $rskey2->courseinstance;    
                                }
                                $catCourseStr = implode(",", $catCourseArr);
                                unset($catCourseArr);
                                //60 Est End

                                //Check from certificate logs
                                //60 Est Start
                                $sqlSIL = 'SELECT id, notification, timecompletion, courseid
                                      FROM {simplecertificate_issue_logs}
                                     WHERE userid='.$userid.' AND courseid IN ('.$catCourseStr.') AND timecompletion != "" order by timecompletion desc limit 0,1';
                                $silObjData = $DB->get_record_sql($sqlSIL);
                                if($silObjData){
                                    //Classroom Signup Code start
                                    //Level One Column on Admin Pages Start
                                    $sqlSCIL = 'SELECT sci.timecreated, sci.timeexpired, sc.course
                                          FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc on sc.id=sci.certificateid
                                         WHERE sci.userid='.$userid.' AND sc.course IN ('.$catCourseStr.') AND sci.timecreated != "" order by sci.timecreated desc limit 0,1';
                                         //1
                                //60 Est End
                                    $scilObjData = $DB->get_record_sql($sqlSCIL);
                                    $catCourseStr = '';
                                    //Level One Column on Admin Pages end
                                    $scilObjData = $DB->get_record_sql($sqlSCIL);
                                    //60 Est Start
                                    if(empty($scilObjData)){
                                        $cidforsubmitgradingflag = $silObjData->courseid;
                                        $statusCourseComp = date("m/d/Y",$silObjData->timecompletion);
                                    } 
                                    else{
                                        $cidforsubmitgradingflag = $scilObjData->course;
                                        //60 Est end
                                        //Level One Column on Admin Pages Start
                                        //change -12
                                        if($scilObjData->timeexpired < time() && $scilObjData->timeexpired != 99){
                                            $statusCourseComp = "Expired";
                                            //$statusCourseComp = $rskey->courseinstance;
                                        }
                                        else{
                                            $statusCourseComp = date("m/d/Y",$scilObjData->timecreated);
                                        }
                                        //Level One Column on Admin Pages End                                        
                                    }
                                    if($cidforsubmitgradingflag){
                                        $flagrs=2;
                                        $cidforsubmitgradingflag='';
                                    }
                                    //Classroom Signup Code end
                                }   
            /* Certification level one status for by pawan start */                     
                        /*}
                        else*/
            /* Certification level one status for by pawan end */
        //60 est start
        
                        if($flagrs == 0 || $flagrs == 3){
                            /* Certification level one status for by pawan start */
                            $sqlUser_AssignGrader_AssignSubmission = "SELECT s.timemodified AS timesubmitted, u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS
                            STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
                            FROM mdl_user u 
                            LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
                            LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
                            LEFT JOIN mdl_course c ON c.id = cm.course
                            LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
                            WHERE u.id != 5 AND u.id != 2 AND u.id = $userid AND c.id = $rskey->courseinstance AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ORDER BY g.timemodified DESC";

                            $rsStudentRecordArray = $DB->get_record_sql($sqlUser_AssignGrader_AssignSubmission);
                            
                            if(!empty($rsStudentRecordArray)){
                                $flagrs=2;
                                $statusCourseComp = '<span>'.$OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecordArray->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecordArray->status)).'</span>';
                            }
                            else{
                                //60 est start
                                $primaryCourseSQL = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$rskey->courseinstance; 
                                $primaryCourseRes = $DB->get_record_sql($primaryCourseSQL); 
                                $pri_course_category = $primaryCourseRes->category;
                                  
                                if($cidforsubmitgradingflag == 0 || $cidforsubmitgradingflag == ''){
                                    $statusCourseComp = "NOT CERTIFIED";
                                    $flagrs=0;
                                    $cidforsubmitgradingflag == 1;
                                }                                
                                //60 est end
                            }
                            /* Certification level one status for by pawan end */
                        }
                }
            }//end foreach
            //End - Updated by pawan
    //Level 2 Submission Restriction end
            }
            //Course Completions Logics Ends
                if ($takeattendance) {
    
                    // Show current status.
                    $data[] = get_string('status_'.facetoface_get_status($attendee->statuscode), 'facetoface');
                    $optionid = 'submissionid_'.$attendee->submissionid;
                    $status = $attendee->statuscode;
                    $select = html_writer::select($statusoptions, $optionid, $status);
                    $data[] = $select;
                } else {
                    if (!get_config(null, 'facetoface_hidecost')) {
                        $data[] = facetoface_cost($attendee->id, $session->id, $session);
                        if (!get_config(null, 'facetoface_hidediscount')) {
                            $data[] = $attendee->discountcode;
                        }
                    }
                
                    //Customization Start by Sameer
                    $data[] = $attendee->institution;

                     //Task #2314 start
                   //Country name in DB is saved in ISO format, so converting it to fullname before displaying.
                   $data[] = isset($listOfCountries[$attendee->country])?$listOfCountries[$attendee->country]:'N/A'; 
                   //Task #2314 end
                    $data[] = $attendee->username;
      // Customize start section by Pawan 
    
                    if ($attendee->who_refer_you_desc != NULL) {
    
                        $who_refer = $attendee->who_refer_you_desc;       
    
                        $data[] = "<a href='javascript:void(0);' id ='restrictionPopUpId' class ='restrictionPopUp".$attendee->id."'>".$attendee->diet_restriction."</a>";
                    
                    ?>
<div class="modal fade memberModal<?=$attendee->id?>" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:pink;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="memberModalLabel">RESTRICTION</h4>
            </div>
            <div class="modal-body first-part ">
                <?php echo $who_refer; ?>
            </div>
        </div>
    </div>
</div>
<script>   
    $(document).ready(function(){                    
        var attID = "<?=$attendee->id?>";
        $(".restrictionPopUp"+attID).on('click', function(){                                                                 
          $('.memberModal'+attID).modal('show');
        });
    });                
</script>
<?php
    }
    else{
    // TRP start
        if($attendee->diet_restriction == '' ){
            $data[] = 'None';
        }
        else
        // TRP end   
        $data[] =$attendee->diet_restriction;      
    }
    
    // Customize end section by Pawan
    
    //Classroom Signup Code start
    // US #17926 start
    if($attendee->statuscode == MDL_F2F_STATUS_BOOKED && $attendee->isattended){
        $data[]="<span class ='reserved-seat'>Attended</span>";
    }elseif($attendee->statuscode != 60){
        $data[]="<span class ='reserved-seat'>Reserved - ".++$j."</span>";
    }else
        $data[]="<span class='waitlist' id = '$attendee->id'>Waitlist - ".++$i."</span>";
    // US #17926 end
    // Addition Certification Columns Start
    //60 Est start
$flagStat=0;
    if($flagrs==0){

            //change-32 start
            $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$course->id,'criteriatype'=>8),'','id, course, courseinstance');

            foreach($coursesData as $keyCDVal){
              $keyCD1 = $keyCDVal;
              $primaryCourseSQL = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$keyCDVal->courseinstance;
              $primaryCourseRes = $DB->get_record_sql($primaryCourseSQL); 
              $pri_course_category = $primaryCourseRes->category;
              $pri_course_fullname = format_string($primaryCourseRes->fullname);
            }

             //For single course dependency
            if(count($coursesData) == 1){
                $sqlrepeat = "SELECT id as courseinstance FROM {course} c WHERE category = $pri_course_category and id=$primaryCourseRes->id order by id";
                $rsrepeat = $DB->get_records_sql($sqlrepeat);
            }
            else{
                if($pri_course_category){
                    $sqlrepeat = "SELECT id as courseinstance FROM {course} c where category = $pri_course_category order by id";
                    $rsrepeat = $DB->get_records_sql($sqlrepeat);
                }
            }
            //change-32 end

            $flagStat=0;
            if($rsrepeat){
                $flagStat=0;
                foreach($rsrepeat as $rsrepeatkey){
                    $coursemoduleArr = "3,16,15";
                    $sectionDataSql = "SELECT cs.id, cs.sequence FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsrepeatkey->courseinstance AND cm.module IN($coursemoduleArr) GROUP BY cm.section";
                    $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                    if($sectionDataRs){
                        $cmIdsArr = array(); $sectionSeqCnt = $moduleCompleted = 0;
                        $completeCoursesModulesDataRes = array();
                        foreach ($sectionDataRs as $key) {
                            $sequence = $key->sequence;
                            $completeCoursesModulesDataSQL = "SELECT cmc.id,cmc.userid,cmc.coursemoduleid FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =$rsrepeatkey->courseinstance and cmc.userid= $userid AND cmc.coursemoduleid IN($sequence) AND cmc.completionstate != 3";
                            $completeCoursesModulesDataRes = $DB->get_records_sql($completeCoursesModulesDataSQL);

                            $sectionSeqCntRs = array();
                            $sectionSeqCntSql = "SELECT id FROM  `mdl_course_modules` WHERE section = $key->id and visible = 1";
                            $sectionSeqCntRs = $DB->get_records_sql($sectionSeqCntSql);
                            
                            if(count($completeCoursesModulesDataRes) >= count($sectionSeqCntRs) && count($sectionSeqCntRs)!=0){
                                $moduleCompleted++;
                                $flagStat=1;
                            }
                        }//end for each
                        $moduleCompletedArr['cntCMC'][] = $moduleCompleted;
                        
                        $moduleCompletedArr['cntTotCM'][] = count($sectionDataRs);
                        
                    }//end if sectionDataRs
                }//end foreach rsrepeat 
                if($flagStat != 0){
                   /* if($userid == 835001069){
                        echo $flagStat." Testiee";
                    }*/
                    $cntTotCMKey = array_keys($moduleCompletedArr['cntCMC'], max($moduleCompletedArr['cntCMC']));
                    $cntTotCMKeyInitial = $cntTotCMKey[0];
                    $statusCourseComp = '<dd style="color:red" class="setredDflt">'.max($moduleCompletedArr['cntCMC']).' out of '.$moduleCompletedArr['cntTotCM'][$cntTotCMKeyInitial].' modules completed '.'</dd>';
                    if($statusCourseComp == ''){
                       $statusCourseComp = 'None';
                    }
                    //change-322
                    //$data[] = $statusCourseComp." 22";
                    unset($moduleCompletedArr['cntCMC']);
                    unset($moduleCompletedArr['cntTotCM']);
                   // break;
                }//end if $rskey->courseinstance                 
            }//end if rsrepeat   
            //change-32212
            if($statusCourseComp == ''){
                $statusCourseComp = 'None2';
            }
            //change-3223
            $data[] = $statusCourseComp;             
    }
    else{
        //change-32233
        if($statusCourseComp == ''){
            $statusCourseComp = 'None';
        }
        $data[] = $statusCourseComp;
    }

    //60 Est end

    //change-4
    $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$attendee->course,'criteriatype'=>8),'','id, course, courseinstance');

    foreach($coursesData as $keyCDVal){
      $keyCD1 = $keyCDVal;
      $primaryCourseSQL = 'SELECT id,category,fullname FROM {course} WHERE visible=1 and id ='.$keyCDVal->courseinstance;
      $primaryCourseRes = $DB->get_record_sql($primaryCourseSQL); 
      $pri_course_category = $primaryCourseRes->category;
      $pri_course_fullname = format_string($primaryCourseRes->fullname);
    }

    if($coursesData){
//change-4 end
        $statusCourseCompL2 = "None";
        $sqlSIL = 'SELECT id, notification, timecompletion FROM {simplecertificate_issue_logs} WHERE userid='.$userid.' AND courseid='.$attendee->course.' AND timecompletion != "" order by timecompletion desc limit 0,1';
        $silObjData = $DB->get_record_sql($sqlSIL);
        if($silObjData){
            //change-5
            $sqlSCIL = 'SELECT sci.timecreated FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc on sc.id=sci.certificateid WHERE sci.userid='.$userid.' AND sc.course='.$attendee->course.' AND sci.timecreated != ""';
            $scilObjData = $DB->get_record_sql($sqlSCIL);
            if(empty($scilObjData)){
                $statusCourseCompL2 = date("m/d/Y",$silObjData->timecompletion);
            }
            else{
                $statusCourseCompL2 = date("m/d/Y",$scilObjData->timecreated);
            }
        }
        else{

        /* Certification level one status for by pawan start */
        $sqlUser_AssignGrader_AssignSubmission = "SELECT s.timemodified AS timesubmitted, u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS STATUS, s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
        FROM mdl_user u 
        LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
        LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
        LEFT JOIN mdl_course c ON c.id = cm.course
        LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
        WHERE u.id != 5 AND u.id != 2 AND u.id = $userid AND c.id = ".$attendee->course." AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ORDER BY g.timemodified DESC";

        $rsStudentRecordArray = $DB->get_record_sql($sqlUser_AssignGrader_AssignSubmission);
        
        if(!empty($rsStudentRecordArray)){
            $flagrs=2;
            $statusCourseCompL2 = '<span>'.$OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecordArray->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecordArray->status)).'</span>';
        }
        else{
            $flagrs=0;
            $statusCourseCompL2 = "None";
        }
        /* Certification level one status for by pawan end */
    }
    $data[] = $statusCourseCompL2;
    // Addition Certification Columns end
    }
    //Classroom Signup Code end
    
    if($_SESSION['ctype']==1){
    $data[] = str_replace(' ', '&nbsp;', get_string('status_'.facetoface_get_status($attendee->statuscode), 'facetoface'));
    }       
    //Customization End by Sameer
    }
    $table->data[] = $data;
    }
    
    echo html_writer::table($table);
    
    if ($takeattendance) {
    echo html_writer::start_tag('p');
    echo html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('saveattendance', 'facetoface')));
    echo '&nbsp;' . html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'cancelform', 'value' => get_string('cancel')));
    echo html_writer::end_tag('p') . html_writer::end_tag('form');
    } else {
    
    // Actions.
    print html_writer::start_tag('p');
    if ($cantakeattendance && $session->datetimeknown && facetoface_has_session_started($session, time())) {
    
    // Take attendance.
    $attendanceurl = new moodle_url('attendees.php', array('s' => $session->id, 'takeattendance' => '1', 'backtoallsessions' => $backtoallsessions));
    echo html_writer::link($attendanceurl, get_string('takeattendance', 'facetoface')) . ' - ';
    }
    }
    }
    
    if (!$takeattendance) {
    if (has_capability('mod/facetoface:addattendees', $context) ||
    has_capability('mod/facetoface:removeattendees', $context)) {
    
    // Add/remove attendees.
            $editattendeeslink = new moodle_url('editattendees.php', array('s' => $session->id, 'backtoallsessions' => $backtoallsessions));
            echo html_writer::link($editattendeeslink, get_string('addremoveattendees', 'facetoface')) . ' - ';
    // - --  - - --  - Start-Feature Request: Manager Course Edits  - Nav - - - - ---//
            }elseif($_REQUEST['crole'] == "instructor"){
                 // Add/remove attendees for manager.
                $editattendeeslink = new moodle_url('editattendees.php', array('s' => $session->id, 'backtoallsessions' => $backtoallsessions, 'crole'=>"instructor"));
                echo html_writer::link($editattendeeslink, get_string('addremoveattendees', 'facetoface')) . ' - ';
            }
            // - --  - - --  - Start-Feature Request: Manager Course Edits  - Nav - - - - ---//
    }
    }
    
    // Go back.
    $url = new moodle_url('/course/view.php', array('id' => $course->id));
    if ($backtoallsessions) {
    $url = new moodle_url('/mod/facetoface/view.php', array('f' => $facetoface->id, 'backtoallsessions' => $backtoallsessions));
    }


    //59 Est Instructor Feature Start
    if($_REQUEST['crole'] != "instructor"){
        echo html_writer::link($url, get_string('goback', 'facetoface')) . html_writer::end_tag('p');
    }
    else{
        echo html_writer::link("/my", get_string('goback', 'facetoface')) . html_writer::end_tag('p');
    }
    //59 Est Instructor Feature End
    
      // ----- customization_Naveen_Start -------//
    //Need to fix from the day of deployment date
    $featurestartdate = strtotime('-2 days', strtotime('2020-08-23'));
    if($session){
        foreach ($session->sessiondates as $date) {
            //Resolved bug in naveen's code by sameer
            if ($date->timestart > $featurestartdate && $date->timestart != 2481889900) {                
                $roll_call_status = $session->roll_call_status;
                $action = new moodle_url('attendees.php', array('s' => $s));
               // echo $date->timestart; echo "<br>";
                 //US 3009 start
                $current_time = time();
                if ($date->timestart > $current_time){
                    $cssStrSubmitPos = "float:left;"; // US #6665
                    $formwidth = "width:30%;"; // US #6665
                }
                else{
                    $formwidth = "width:12%;";
                }
                //US 3009 end
                echo html_writer::start_tag('form', array('action' => $action->out(), 'method' => 'post', "style"=> "float: right; width: auto;"));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => $USER->sesskey));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 's', 'value' => $s));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'backtoallsessions', 'value' => $backtoallsessions));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'confirm_roster', 'value' =>"Yes"));
                
                // Start #18864
                if($roll_call_status == 0){
                    echo html_writer::empty_tag('input', array('type' => 'submit', 'style'=>"$cssStrSubmitPos",'value' => get_string('confirm_roster', 'facetoface')));
                }else{
                    echo html_writer::empty_tag('input', array('type' => 'submit', 'value' => 'Confirmed Roster', 'disabled'=>'disabled','style'=>"opacity:0.6 !important; pointer-events: none;background: #000;border: 1px solid;$cssStrSubmitPos")); 
                }
                // End #18864
                
                // US 6665 starts
                $sql_users = "SELECT su.id, su.userid FROM {facetoface_signups} su JOIN {facetoface_signups_status} sus ON su.id = sus.signupid WHERE su.sessionid= $session->id AND sus.statuscode = 60 AND sus.superceded = 0";
                $waitlistusers = $DB->get_records_sql($sql_users);
                if(!empty($waitlistusers)){
                    echo "<input type='button' class='waitlist' style='float: left;' onclick='hold(".$session->id.",".$USER->id.",true);' value='Clear Waitlist' />";

                } else {
                    echo "<input type='button' class='waitlist' style='float: left; opacity:0.6 !important; pointer-events: none;' disabled='disabled' value='Clear Waitlist' />";
                }
                // US 6665 ends
                ?>

                <!-- US 3009 start -->
                <?php 
                //future session validation and mdl_facetoface_session_hold.session_id is 0 = allow, if it is set to 1 = hold 
                $current_time = time();
                if ($date->timestart > $current_time){
                    $sqlFSH = 'SELECT id, flag FROM {facetoface_session_hold} WHERE session_id='.$session->id;
                    $fshObjData = $DB->get_record_sql($sqlFSH);
                    if(empty($fshObjData) || $fshObjData->flag == 0){
                        echo "<input type='button' class='hold' style='float: left;' onclick='hold(".$session->id.",".$USER->id.");' value='Hold Sign-ups' />";
                        echo "<input type='button' class='allow' style='float: left; display:none;' onclick='allow(".$session->id.",".$USER->id.");' value='Allow Sign-ups' />";
                     }else{ 
                        echo "<input type='button' class='allow' style='float: left;' onclick='allow(".$session->id.",".$USER->id.");' value='Allow Sign-ups' />";
                        echo "<input type='button' class='hold' style='float: left; display:none;' onclick='hold(".$session->id.",".$USER->id.");' value='Hold Sign-ups' />";
                    }
                }
                ?>
                <!-- US 3009 end -->
                <?php
                echo html_writer::end_tag('form');    
            }
        }
    }    
    // ----- customization_Naveen_End --------//    
    
    /*
    * Print unapproved requests (if user able to view)
    */
    if ($canapproverequests) {
    echo html_writer::empty_tag('br', array('id' => 'unapproved'));
    if (!$requests) {
    echo $OUTPUT->notification(get_string('noactionableunapprovedrequests', 'facetoface'));
    } else {
    $canbookuser = (facetoface_session_has_capacity($session, $contextmodule) || $session->allowoverbook);
    
    $OUTPUT->heading(get_string('unapprovedrequests', 'facetoface'));
    
    if (!$canbookuser) {
    echo html_writer::tag('p', get_string('cannotapproveatcapacity', 'facetoface'));
    }
    
    
    $action = new moodle_url('attendees.php', array('s' => $s));
    echo html_writer::start_tag('form', array('action' => $action->out(), 'method' => 'post'));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => $USER->sesskey));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 's', 'value' => $s));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'backtoallsessions', 'value' => $backtoallsessions)) . html_writer::end_tag('p');
    
    $table = new html_table();
    $table->summary = get_string('requeststablesummary', 'facetoface');
    $table->head = array(get_string('name'), get_string('timerequested', 'facetoface'),
                get_string('decidelater', 'facetoface'), get_string('decline', 'facetoface'), get_string('approve', 'facetoface'));
    $table->align = array('left', 'center', 'center', 'center', 'center');
    
    foreach ($requests as $attendee) {
    $data = array();
    $attendeelink = new moodle_url('/user/view.php', array('id' => $attendee->id, 'course' => $course->id));
    $data[] = html_writer::link($attendeelink, format_string(fullname($attendee)));
    $data[] = userdate($attendee->timerequested, get_string('strftimedatetime'));
    $data[] = html_writer::empty_tag('input', array('type' => 'radio', 'name' => 'requests['.$attendee->id.']', 'value' => '0', 'checked' => 'checked'));
    $data[] = html_writer::empty_tag('input', array('type' => 'radio', 'name' => 'requests['.$attendee->id.']', 'value' => '1'));
    $disabled = ($canbookuser) ? array() : array('disabled' => 'disabled');
    $data[] = html_writer::empty_tag('input', array_merge(array('type' => 'radio', 'name' => 'requests['.$attendee->id.']', 'value' => '2'), $disabled));
    $table->data[] = $data;
    }
    
    echo html_writer::table($table);
    
    echo html_writer::tag('p', html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('updaterequests', 'facetoface'))));
    echo html_writer::end_tag('form');
    }
    }
    
    /*
    * Print cancellations (if user able to view)
    */
    if (!$takeattendance && $canviewcancellations && $cancellations) {
    
    echo html_writer::empty_tag('br');
    echo $OUTPUT->heading(get_string('cancellations', 'facetoface'));
    
    $table = new html_table();
    $table->summary = get_string('cancellationstablesummary', 'facetoface');
    //US 3008 start add company and country string
    $table->head = array(get_string('name'),get_string('company','facetoface'),get_string('country','facetoface'), get_string('timesignedup', 'facetoface'),
             get_string('timecancelled', 'facetoface'), get_string('cancelreason', 'facetoface'));
    //US 3008 end add company and country string
    $table->align = array('left', 'center', 'center');
    
    foreach ($cancellations as $attendee) {
    $data = array();

    //59 Est Instructor Feature Start
    if($_REQUEST['crole'] != "instructor"){
        $attendeelink = new moodle_url('/user/view.php', array('id' => $attendee->id, 'course' => $course->id));
        $data[] = html_writer::link($attendeelink, format_string(fullname($attendee)));
    }
    else{ 
        $data[] = format_string(fullname($attendee));
    }
    //59 Est Instructor Feature End
    //US 3008 start add company and country values
    $data[] = $attendee->institution;
    $data[] = isset($listOfCountries[$attendee->country])?$listOfCountries[$attendee->country]:'N/A'; 
    //US 3008 end add company and country values
    $data[] = userdate($attendee->timesignedup, get_string('strftimedatetime'));
    $data[] = userdate($attendee->timecancelled, get_string('strftimedatetime'));
    $data[] = format_string($attendee->cancelreason);
    
    
    //Cust start
    
    $data[] = "<a href='javascript:void(0);' id ='cancelPopUpId' class ='cancelPopUp".$attendee->id."'>View All</a>";
    
    ?>
<div class="modal fade memberModal<?=$attendee->id?>" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:pink;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="memberModalLabel"><?php echo format_string(fullname($attendee))." - ".get_string('cancellations', 'facetoface')." Record";?></h4>
            </div>
            <div class="modal-body first-part ">
                <?php
                    //  $sqlFSSrs = $DB->get_records_sql("SELECT note, timecreated from mdl_facetoface_signups_status where createdby = ? AND signupid = ? AND statuscode = '10' order by timecreated DESC",array($attendee->id,$attendee->signupid)); 
                    $sqlFSSrs = $DB->get_records_sql("SELECT id, note, timecreated from mdl_facetoface_signups_status where createdby = ? AND signupid = ? AND statuscode = '10' ORDER BY timecreated DESC",array($attendee->id,$attendee->signupid));
                    
                      echo "<table border ='1'><THEAD><tr><th>".strtoupper(get_string('timecancelled', 'facetoface'))."</th><th>".strtoupper(get_string('cancelreason', 'facetoface'))."</th></tr></THEAD>";  
                    
                      foreach ($sqlFSSrs as $result) {
                    
                          echo "<tr><TBODY><td>".userdate($result->timecreated, get_string('strftimedatetime'))."</td>";
                    
                          if ($result->note == NULL) {
                              echo "<td>".get_string('no_reason', 'facetoface')."</td></tr>";
                          }
                          else{
                              echo "<td class='comment more'>".$result->note."</td></tr>";                       
                          }                     
                      }
                      echo "<TBODY></table>";
                      ?>
            </div>
        </div>
    </div>
</div>
<script>   
    $(document).ready(function(){                    
        var attID = "<?=$attendee->id?>";
        $(".cancelPopUp"+attID).on('click', function(){                                                                 
          $('.memberModal'+attID).modal('show');
      });
    }); 
    
</script>
<?php
    //Cust end
    
            $table->data[] = $data;
        }
        echo html_writer::table($table);
    }
    
    /*
     * Print page footer
     */
    ?>
<script src="/theme/meline29/javascript/jquery-ui.js"></script>
<!-- <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script> -->
<?php
    echo $OUTPUT->box_end();
    echo $OUTPUT->footer($course);
    ?>
<style>
.comment td{
    width: 400px;
}

.morecontent span {
    display: none;
}

body#page-mod-facetoface-attendees .alert-error {
    margin: 0;
}

input.waitlist {
    background: #000 !important;
    margin-right: 5px;
    padding: 0.5rem 1rem;
}
#page-mod-facetoface-attendees .modal-backdrop.fade {
    opacity: 0.8;
}
#page-mod-facetoface-attendees .modal {
    box-shadow: none;
}
.c7{
    display: revert;
}
</style>
<script>
    $(document).ready(function(){                    
     
        var showChar = 100;
        var ellipsestext = "...";
        var moretext = "more";
        var lesstext = "less";
        $('.more').each(function() {
            var content = $(this).html();
    
            if(content.length > showChar) {
    
                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);
    
                var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
    
                $(this).html(html);
            }
    
        });
    
        $(".morelink").click(function(){
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
    }); 
    
        $('.close').on('click', function(){ 
        $('.morelink').show();
    });   
    
    
    }); 
</script>   
<script>    
    $(document).ready(function(){        
        var attendeesIdArr = '<?php echo json_encode($attendeesIdArr);?>';
        $('.reserved-seat').parent().parent().addClass('reserved-user');
        $('.attendeestable tbody tr').each(function(index) {
            var attendeesId = $(this).children("td").children("span").attr("id");
            $(this).attr("id",attendeesId);
        });
        
        $(".attendeestable tbody").sortable( {
            items: 'tr:not(".reserved-user")',
            placeholder: "ui-state-highlight",
            axis: 'y',
            update: function (event, ui) {
                // Task #20393 start
                var selectedIds = [];
                $(this).children().each(function(index) {
                    selectedIds.push($(this).attr("id"));
                    $(this).find('td span.waitlist').html("Waitlist - "+(index+ 1));
                });
                // var data = $(this).sortable('toArray');
                var data = selectedIds;
                // Task #20393 end
                $.ajax({
                    url: '/mod/facetoface/set_attendees_sorting_ajax.php',
                    type: 'POST', 
                    data: {
                         waitlistOrderArr: data,
                         userArr: attendeesIdArr
                    },
                    success: function(response) {
                        console.log("Response "+response);
                    }
                });
            }
      });
    });
    
</script>
<style>
    .c5{ width: 10% !important; }
    tr.ui-state-handle{width: 100% !important;}
    tr.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
</style>

<!-- US 3009 start -->
<script type="text/javascript">
    
function hold(sessionVal,userIdVal, cW) {

    //alert("hold it now");
    $('.allow').show();
    $('.hold').hide();
    $.ajax({
        url: '/mod/facetoface/set_session_allowance_ajax.php',
        type: 'POST', 
        data: {
            sessionid: sessionVal,
            userid: userIdVal,
            clearwaitlist: cW,
            flag: 1
        },
        success: function(response) {
            console.log("Response "+response);
            window.location.reload();
        }
    });
}

function allow(sessionVal,userIdVal){
    //alert("allow it now");
    $('.hold').show();
    $('.allow').hide();
    $.ajax({
        url: '/mod/facetoface/set_session_allowance_ajax.php',
        type: 'POST', 
        data: {
            sessionid: sessionVal,
            userid: userIdVal,
            flag: 0
        },
        success: function(response) {
            console.log("Response "+response);
        }
    });
}
</script>
<!-- US 3009 end -->