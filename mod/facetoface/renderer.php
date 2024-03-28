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
defined('MOODLE_INTERNAL') || die();

class mod_facetoface_renderer extends plugin_renderer_base {

    /**
     * Builds session list table given an array of sessions
     */
    //59 Est Instructor Feature Start
    public function print_session_list_table($customfields, $sessions, $viewattendees, $editsessions, $crole = NULL, $psflag = 0, $userRoles = []) {
        //59 Est Instructor Feature End
        //Task 4023
        global $CFG, $DB, $USER;

        $output = '';

        $hasregionaladminrole = 0;
        if($USER->usertype == 'regionaladmin'){
            $hasregionaladminrole = 1;
        } else if(in_array('regionaladmin', $userRoles)){
            $hasregionaladminrole = 1;
        }

        $tableheader = array();
        foreach ($customfields as $field) {
            //US 3072 start add 15 field condition
            //Task 4023 add field id 4 and 5
            if ($field->id != 10 && $field->id != 4 && $field->id != 5 && $field->id != 8 && $field->id != 9 && $field->id != 3 && $field->id != 15 && $field->id != 18) {
                //US 3072 end add 15 field condition
                if (!empty($field->showinsummary)) {
                    //US #913 add below if cond
                    if ($field->name != "Promocode") {
                        $tableheader[] = format_string($field->name);
                    }
                }
            }
            //Task 4023
            if ($field->id == 8) {
                $tableheader[] = "Instructor";
            }
            // //Task 4023
            // if($field->id == 9){
            //     $tableheader[] = "Rep Instructor";
            // }
        }
        /* US #3952 add order param */
        $tableheader[] = "";
        /* US #3952 add order param */
        $tableheader[] = get_string('date', 'facetoface');
        $tableheader[] = get_string('time', 'facetoface');
        if ($viewattendees) {
            $tableheader[] = get_string('capacity', 'facetoface');
        } else {
            $tableheader[] = get_string('seatsavailable', 'facetoface');
        }


        //Task 2060
        $tableheader[] = "Roster";
        //US #913 comment below line
        // $tableheader[] = get_string('status', 'facetoface');
        $tableheader[] = get_string('options', 'facetoface');

        $timenow = time();

        $table = new html_table();
        $table->summary = get_string('previoussessionslist', 'facetoface');
        $table->head = $tableheader;
        $table->data = array();
        //59 Est Instructor Feature Start
        global $USER;
        //59 Est Instructor Feature end
        foreach ($sessions as $session) {


            //59 Est Instructor Feature Start
            if (strpos($session->customfielddata[3]->data, $USER->username) !== false) {
                $flagA = 1;
            } else {
                $flagA = 2;
            }

            if($hasregionaladminrole){
                $flagA = 1;
            }

            //Grader as a Manager Code Start
            //US 5635 condition update.
            
            if (($crole == "instructor" && $flagA == 1) || ($USER->id == 2 || ($USER->usertype == 'mainadmin' || ($USER->usertype == 'graderasadmin' && $crole != "instructor") || ($editsessions && $crole != "instructor")))) {
                //Grader as a Manager Code End
                //59 Est Instructor Feature End

                $isbookedsession = false;
                $bookedsession = $session->bookedsession;
                $sessionstarted = false;
                $sessionfull = false;

                $sessionrow = array();

                // Custom fields.
                $customdata = $session->customfielddata;

                foreach ($customfields as $field) {
                    //US #913 add below logic
                    //US 3072 start
                    //Task 4023 add field id 4 and 5
                    if ($field->id != 10 && $field->id != 4 && $field->id != 5 && $field->id != 8 && $field->id != 9 && $field->id != 3 && $field->id != 15 && $field->id != 18 && $field->name != "Promocode") {
                        //US 3072 end
                        if (empty($field->showinsummary)) {
                            continue;
                        }

                        if (empty($customdata[$field->id])) {
                            $sessionrow[] = '&nbsp;';
                        } else {
                            if (CUSTOMFIELD_TYPE_MULTISELECT == $field->type) {
                                $sessionrow[] = str_replace(CUSTOMFIELD_DELIMITER, html_writer::empty_tag('br'), format_string($customdata[$field->id]->data));
                            } else {
                                $sessionrow[] = format_string($customdata[$field->id]->data);
                            }
                        }
                    }

                    //Task 4023 start
                    /* US #3952 change added start */
                    $instructorsName = '';
                    /* US #3952 change added end */

                    if ($field->id == 8 || $field->id == 9) {
                        $instructorsNames = explode("##SEPARATOR##", $customdata[$field->id]->data);
                        //check for rep
                        if (empty($instructorsNames)) {
                            $instructorsNames = explode("##SEPARATOR##", $customdata[$field->id]->data);
                        }

                        if (!empty($instructorsNames)) {
                            for ($i = 0; $i < count($instructorsNames); $i++) {
                                $instructorsName .= trim($instructorsNames[$i]);

                                if (!empty($instructorsName)) {
                                    if ($i < count($instructorsNames) - 1) {
                                        $instructorsName .= ", ";
                                    }
                                }
                            }
                        } else {
                            $instructorsName = "";
                        }
                        /* US #3952 change in the below if cond start */
                        if (!empty($instructorsName) && $flagArr[8] != 1){
                            $sessionrow[] = format_string($instructorsName);
                            $flagArr[$field->id] = 1;
                        } else {
                            $flagArr[$field->id] = 0;
                        }
                    }
                    /* US #3952 change in the below code start */
                    if (empty($flagArr[8]) && empty($flagArr[9]) && $field->id == 9) {
                        $sessionrow[] = "N/A";
                    }

                    if ($field->id == 9) {
                        $flagArr[8] = 0;
                    }
                    /* US #3952 change in the below code end */
                    //Task 4023 end
                }
                if (isset($session->customfielddata[5]->data) || $session->datetimeknown) {
                    if ($session->datetimeknown) {
                        $allsessiondates = '';
                        $allsessiontimes = '';
                        foreach ($session->sessiondates as $date) {
                            if (!empty($allsessiondates)) {
                                $allsessiondates .= html_writer::empty_tag('br');
                            }
                            $allsessiondates .= userdate($date->timestart, get_string('strftimedate'));

                            if ($date->timestart == "2481889900" && $date->timefinish == "2481889900") {

                                $allsessiondates = '';
                                $allsessiontimes = '';
                            }


                            if (!empty($allsessiontimes)) {
                                $allsessiontimes .= html_writer::empty_tag('br');
                            }

                            $allsessiontimes .= date("h:i A", $date->timestart) . ' - ' . date("h:i A", $date->timefinish);

                            if ($date->timestart == "2481889900" && $date->timefinish == "2481889900") {

                                $allsessiondates = '';
                                $allsessiontimes = '';
                            }
                        }
                    } else {
                        $allsessiondates = "";
                        $allsessiontimes = "";
                        $sessionwaitlisted = true;
                    }
                    /* US #3952 add order param */
                    $sessionrow[] = $date->timestart;
                    /* US #3952 add order param */
                    $sessionrow[] = $allsessiondates;
                    $sessionrow[] = $allsessiontimes;

                    // Capacity.
                    $signupcount = facetoface_get_num_attendees($session->id, MDL_F2F_STATUS_APPROVED);
                    $stats = $session->capacity - $signupcount;
                    if ($viewattendees) {
                        $stats = $signupcount . ' / ' . $session->capacity;
                    } else {
                        $stats = max(0, $stats);
                    }
                    $sessionrow[] = $stats;
                } else {
                    if ($allsessiondates == '') {
                        $sessionrow[] = '&nbsp;';
                    } else {
                        $sessionrow[] = $allsessiondates;
                    }
                    if ($allsessiontimes == '') {
                        $sessionrow[] = '&nbsp;';
                    } else {
                        $sessionrow[] = $allsessiontimes;
                    }
                    if ($stats == '') {
                        $sessionrow[] = '&nbsp;';
                    } else {
                        $sessionrow[] = $stats;
                    }
                }
                // Status.
                //US #913 commented below code
                /* $status  = get_string('bookingopen', 'facetoface');
                  if ($session->datetimeknown && facetoface_has_session_started($session, $timenow) && facetoface_is_session_in_progress($session, $timenow)) {
                  $status = get_string('sessioninprogress', 'facetoface');
                  $sessionstarted = true;
                  } else if ($session->datetimeknown && facetoface_has_session_started($session, $timenow)) {
                  $status = get_string('sessionover', 'facetoface');
                  $sessionstarted = true;
                  } else if ($bookedsession && $session->id == $bookedsession->sessionid) {
                  $signupstatus = facetoface_get_status($bookedsession->statuscode);
                  $status = get_string('status_' . $signupstatus, 'facetoface');
                  $isbookedsession = true;
                  } else if ($signupcount >= $session->capacity) {
                  $status = get_string('bookingfull', 'facetoface');
                  $sessionfull = true;
                  }

                  $sessionrow[] = $status; */
                //US #913 commented abov code
                // Options.
                $options = '';
                if ($editsessions && !$hasregionaladminrole) {
                    if ($crole == 'instructor') {
                        $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'crole' => 'instructor')), new pix_icon('t/edit', get_string('edit', 'facetoface')), null, array('title' => get_string('editsession', 'facetoface'))) . ' ';
                        $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'c' => 1, 'crole' => 'instructor')), new pix_icon('t/copy', get_string('copy', 'facetoface')), null, array('title' => get_string('copysession', 'facetoface'))) . ' ';
                        $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'd' => 1, 'crole' => 'instructor')), new pix_icon('t/delete', get_string('delete', 'facetoface')), null, array('title' => get_string('deletesession', 'facetoface'))) . ' ';
                    } else {
                        $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id)), new pix_icon('t/edit', get_string('edit', 'facetoface')), null, array('title' => get_string('editsession', 'facetoface'))) . ' ';
                        $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'c' => 1)), new pix_icon('t/copy', get_string('copy', 'facetoface')), null, array('title' => get_string('copysession', 'facetoface'))) . ' ';
                        $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'd' => 1)), new pix_icon('t/delete', get_string('delete', 'facetoface')), null, array('title' => get_string('deletesession', 'facetoface'))) . ' ';
                    }
                    $options .= html_writer::empty_tag('br');
                } elseif ($editsessions && $hasregionaladminrole){
                    $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'crole' => 'instructor')), new pix_icon('t/edit', get_string('edit', 'facetoface')), null, array('title' => get_string('editsession', 'facetoface'))) . ' ';
                    $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'c' => 1, 'crole' => 'instructor')), new pix_icon('t/copy', get_string('copy', 'facetoface')), null, array('title' => get_string('copysession', 'facetoface'))) . ' ';
                    $options .= $this->output->action_icon(new moodle_url('sessions.php', array('s' => $session->id, 'd' => 1, 'crole' => 'instructor')), new pix_icon('t/delete', get_string('delete', 'facetoface')), null, array('title' => get_string('deletesession', 'facetoface'))) . ' ';
                    $options .= html_writer::empty_tag('br');
                }
                //59 Est Instructor Feature Start
                if ($viewattendees || $crole == 'instructor') {
                    if ($crole == 'instructor') {
                        $options .= html_writer::link('attendees.php?s=' . $session->id . '&backtoallsessions=' . $session->facetoface . '&crole=instructor', get_string('attendees', 'facetoface'), array('title' => get_string('seeattendees', 'facetoface'))) . html_writer::empty_tag('br');
                    } else {
                        $options .= html_writer::link('attendees.php?s=' . $session->id . '&backtoallsessions=' . $session->facetoface, get_string('attendees', 'facetoface'), array('title' => get_string('seeattendees', 'facetoface'))) . html_writer::empty_tag('br');
                    }
                }
            
            

                if (isset($session->allowcancellations)) {
                        $options .= html_writer::link('cancelsignup.php?s=' . $session->id . '&backtoallsessions=' . $session->facetoface, get_string('cancelbooking', 'facetoface'), array('title' => get_string('cancelbooking', 'facetoface')));
                    
                } else if (!$sessionstarted and !$bookedsession) {
                    $options .= html_writer::link('signup.php?s=' . $session->id . '&backtoallsessions=' . $session->facetoface, get_string('signup', 'facetoface'));
                }

                if (empty($options)) {
                    $options = get_string('none', 'facetoface');
                }
                //Task 2060
                $featurestartdate = strtotime('-5 days', strtotime('2020-08-23'));

                if ($session->roll_call_status == 1 && $date->timestart != "2481889900" && $featurestartdate <= $date->timestart) {
                    $sessionrow[] = "Confirmed";
                } elseif ($session->roll_call_status == 0 && $date->timestart != "2481889900" && $featurestartdate <= $date->timestart) {
                    $sessionrow[] = "Unconfirmed";
                } else {
                    $sessionrow[] = "N/A";
                }
                
                $sessionrow[] = $options;

                $row = new html_table_row($sessionrow);

                // Set the CSS class for the row.
                if ($sessionstarted) {
                    $row->attributes = array('class' => 'dimmed_text');
                } else if ($isbookedsession) {
                    $row->attributes = array('class' => 'highlight');
                } else if ($sessionfull) {
                    $row->attributes = array('class' => 'dimmed_text');
                }

                //PP1 start
                if (facetoface_is_session_in_progress($session, $timenow)) {
                    $row->attributes = array('class' => '');
                }
                //PP1 end
                // Add row to table.
                $table->data[] = $row;
            }
        }

        $output .= html_writer::table($table);

        return $output;
    }
}
