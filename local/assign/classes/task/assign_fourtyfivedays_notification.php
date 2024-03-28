<?php
namespace local_assign\task;
class assign_fourtyfivedays_notification extends \core\task\scheduled_task{

    /**
     * @return string
     */
    public function get_name() {
        return get_string('a45notificaiton', 'local_assign');
    }

    /**
     * @return mixed
     */
    public function execute() {
        global $DB, $CFG;
        // Example:  Student turns in their final exam.  Teacher replies with comments.  Then 45 days go by without additional student/teacher comments or without Teacher changing the certification status to PASS.
        mtrace('cron started');
        $userAssignNotiObj = $DB->get_records('course_user_assign_notifications',array('assign_notification'=>2));
        // echo "<pre>";
        // print_r($userAssignNotiObj); exit;
        /*[id] => 3
                    [user_id] => 5
                    [course_id] => 7
                    [assign_notification] => 1
                    [timeduration] => 1453096175*/
        foreach($userAssignNotiObj as $keyCUAN)
        {
            if (!$user = $DB->get_record('user', array('id'=>$keyCUAN->user_id),"id,username,email,firstname,lastname")) {
                echo("Can not find user");
            }
            else{
                /* echo "<pre>"; print_r($user); exit;
        <pre>stdClass Object
        (
            [id] => 5
            [username] => beyondkeysystem
            [email] => beyondkey@mailinator.com
        )       */

                if (!$course = $DB->get_record('course', array('id'=>$keyCUAN->course_id), "id, fullname")) {
                    echo("Can not find course");
                }
                else{
                    if($course->id != 22){
                        $fullname=format_string($course->fullname);
                        // Today time
                        $to_time = time();
                        $from_time = "$keyCUAN->timeduration"; //18 Jan

                        $fourtyfivedaystimestring = strtotime("+45 day",$from_time);
                        //          $expiredfourtyfiveDaysTime = date("m/d/y", strtotime("+45 day",$from_time));
                        $beforefourtyfiveDaysTime = date("F d, Y", $from_time);
                        if($fourtyfivedaystimestring <= $to_time){
                            //Approaching to student for submission reminder email
                            $from="qsctraining@qscaudio.com";
                            $subject = "Final Exam Reminder: $fullname";
                            $messagehtml="Dear ".ucfirst($user->firstname).",<br>";
                            unset($user->firstname);unset($user->lastname);

                            //CCC start
                            //$sql61 = "SELECT * FROM {assign} where course=".$course->id." and type='online'";
                            //$rs61 = $DB->get_record_sql($sql61);
                            $rs61 = $DB->get_record('assign', ['course'=>$course->id, 'type'=>'online']);
                            //$sql612 = "SELECT * FROM {assign} where course=".$course->id." and type='classroom'";
                            //$rs612 = $DB->get_record_sql($sql612);
                            $rs612 = $DB->get_record('assign', ['course'=>$course->id, 'type'=>'classroom']);

                            if(empty($rs612)){
                                $rs612->id=0;
                            }

                            if(empty($rs61)){
                                $rs61->id=0;
                            }

                            $sqlAssignSub = "SELECT * FROM {assign_submission} where userid=$user->id and assignment IN ($rs612->id, $rs61->id) and timemodified > $from_time order by timemodified desc limit 0,1";
                            $rsAssignSub = $DB->get_record_sql($sqlAssignSub);

                            $cmAssignNoti = $DB->get_record('course_modules', array('course'=>$course->id, 'module'=>1,'visible'=>1,'instance'=>$rs61->id), 'id,added');
                            //CCC ends
                            //$cmAssignNoti = $DB->get_record('course_modules', array('course'=>$course->id,'module'=>1,'visible'=>1), "id,added");

                            $cmAssignCompFlag = $DB->get_record('course_modules_completion', array('userid'=>$user->id,'coursemoduleid'=>$cmAssignNoti->id,'completionstate'=>2), "id");

                            $certNotiObjData = $DB->get_record('simplecertificate',array('course'=>$course->id),'id');

//For existing user checking start
                            $sqlSCI = 'SELECT id, userid, certificateid, timecreated, timeexpired
	                  FROM {simplecertificate_issues} sci                  
	                 WHERE certificateid='.$certNotiObjData->id.' AND userid='.$user->id;
                            $issueCertCompNotiObj = $DB->get_record_sql($sqlSCI);

                            if(empty($cmAssignCompFlag) && empty($issueCertCompNotiObj) && empty($rsAssignSub)){
                                $messagehtml .= "<br>
We haven’t heard from you in a while regarding your $fullname final exam.  Your last exchange with a QSC instructor was on <b>$beforefourtyfiveDaysTime</b>.    <br><br>
                    
                    <a href='".$CFG->wwwroot."/mod/assign/view.php?id=".$cmAssignNoti->id."'>Click here to view your last interaction with your final exam.</a>";
                                $messagehtml .= "<br><br>
                Cheers,<br>
                The QSC Training & Education Team <br>
                <a href='".$CFG->wwwroot."'>training.qsc.com</a>";
                                $messagetext = $messagehtml;
                                $messagehtml = text_to_html($messagehtml, false, false, true);
                                $user->mailformat = 1;  // Always send HTML version as well.
                                if(email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
                                    //Set New Status
                                    $keyCUAN->assign_notification = 3;
                                    //Update status of reminder so the system cannot generate agian same email via CRON
                                    $DB->update_record('course_user_assign_notifications', $keyCUAN);

//                                    $subject=$subject." -LIVE exam- ".$user->id;
//
//                                    $user->email = "sameer.chourasia@beyondkey.com";
//                                    $user->username = "sameer.chourasia@beyondkey.com";
//                                    email_to_user($user,$from, $subject, $messagetext, $messagehtml);

                                    // $user->email = "arpit.parliya@beyondkey.com";
                                    // email_to_user($user,$from, $subject, $messagetext, $messagehtml);

                                    /*     $user->email = "priyanka.manjrekar@beyondkey.com";
                                         email_to_user($user,$from, $subject, $messagetext, $messagehtml);  */
                                } //end if email
                            } //end if compFlag
                        }//end if condition
                    }//end if course NOT 22
                }//end else course
            }//end else user
        }//end foreach
        mtrace('cron ended');
    }
}





