<?php
/*added by @shiva goud@qsc*/
namespace local_assign\task;

use context_module;

/**
 * An example of a scheduled task.
 */
class assign_notification extends \core\task\scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('notification', 'local_assign');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB,$USER,$CFG,$COURSE;
        mtrace('cron started');
        //logic
        $data = $DB->get_records('course_user_assign_notifications',array('assign_notification'=>1));
        $this->cron_job($data);
        mtrace('cron ended');
    }
    /**
     * main fn.
     */
    public function cron_job($data){
        global $DB,$USER,$CFG,$COURSE;
        foreach($data as $keyCUAN){
            if (!$user = $DB->get_record('user', array('id'=>$keyCUAN->user_id,'deleted'=>0,'auth'=>'googleoauth2'),"id,username,email,firstname,lastname")) {
                echo("Can not find user");echo "\n";
            }else{
                if (!$course = $DB->get_record('course', array('id'=>$keyCUAN->course_id), "id, fullname")) {
                    echo("Can not find course"); echo "\n";
                }else{
                    if($course->id != 22){
                        $fullname=format_string($course->fullname);
                        $from_time = "$keyCUAN->timeduration"; 
                        // Today time
                        $to_time = time();
                        
                        $expiredThirtyDaysTime = date("F d, Y", strtotime("+1 month",$from_time));

                        //$expiredfourtyfiveDaysTime = date("m/d/y", strtotime("+45 day",$from_time));

                        //get minutes
                        $min = round(abs($to_time - $from_time) / 60,2); //22 March check

                        //40320 minutes in 28 days
                        if($min >= 40320){
                            echo($user->username);echo "\n";
                            //Approaching to student for submission reminder email
                            $from="qsctraining@qscaudio.com";
                            $subject = "Final Exam Reminder: ".$fullname; 
                            $messagehtml="Dear ".ucfirst($user->firstname).",";
                            unset($user->firstname);unset($user->lastname);
                            $messagehtml.="<br><br>
                            This is just a friendly reminder that you have until <b>$expiredThirtyDaysTime</b> to submit your $fullname final exam.
                            <br>
                            Please download the final exam prompt and any accompanying files that may be necessary for completion.
                            <br>
                            Be sure to follow the directions carefully.
                            <br>";

                            //CCC start
                            //$sql61 = "SELECT * FROM {assign} where course=".$course->id." and type='online'";
                            //$rs61 = $DB->get_record_sql($sql61);
                            $rs61 = $DB->get_record('assign', ['course'=>$course->id, 'type'=>'online']);
                            $cmAssignNoti = $DB->get_record('course_modules', array('course'=>$course->id, 'module'=>1,'visible'=>1,'instance'=>$rs61->id), 'id,added');
                            //CCC ends
                            if($cmAssignNoti){
                                $cmAssignCompFlag = '';
                                $cmAssignCompFlag = $DB->get_record('course_modules_completion', array('userid'=>$user->id,'coursemoduleid'=>$cmAssignNoti->id,'completionstate'=>2), "id");
                                
                                $certNotiObjData = $DB->get_record('simplecertificate',array('course'=>$course->id),'id');

                                $sqlSCI = 'SELECT id, userid, certificateid, timecreated, timeexpired
                                              FROM {simplecertificate_issues} sci                  
                                             WHERE certificateid='.$certNotiObjData->id.' AND userid='.$user->id;
                                $issueCertCompNotiObj = $DB->get_record_sql($sqlSCI);
                                if(empty($cmAssignCompFlag) && empty($issueCertCompNotiObj)){
                                    $context = context_module::instance($cmAssignNoti->id);
                                    $fs = get_file_storage();
                                    $files = $fs->get_area_files($context->id, 'mod_assign', 'introattachment', 0, 'id', false);
                                    $ik=1;
                                    foreach ($files as $file) {
                                        //https://localhost.qsc.com/pluginfile.php/304/mod_assign/introattachment/0/qsc_training_level_one.png?forcedownload=1
                                        $fileurl = \moodle_url::make_pluginfile_url($file->get_contextid(),$file->get_component(), $file->get_filearea(), 0, $file->get_filepath(), $file->get_filename(), true);
                                        $messagehtml.='<a href="'.$fileurl.'>'.$file->get_filename().'</a>';
                                        $messagehtml.='<br>';
                                        $ik++;
                                    }

                                    $messagehtml .= "<br>
                                    If you fail to submit your final design by <b>$expiredThirtyDaysTime</b>, you may be required to complete additional training modules before submitting your exam.
                                    <br><br>
                                    Cheers,
                                    <br>
                                    The QSC Training & Education Team
                                    <br>
                                    <a href='".$CFG->wwwroot."'>dev.training.qsc.com</a>";            
                                    $messagetext = $messagehtml;
                                    $messagehtml = text_to_html($messagehtml, false, false, true);
                                    $user->mailformat = 1;  // Always send HTML version as well.
                                    if(email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
                                        //Set New Status
                                        $keyCUAN->assign_notification = 2;
//                                        $user->email = "sameer.chourasia@beyondkey.com";
//                                        //$subject = $subject."--".$user->id;
//                                        $subject=$subject." -LIVESCHANGE- ".$user->id;
//                                        email_to_user($user,$from, $subject, $messagetext, $messagehtml);
//                                        //Update status of reminder so the system cannot generate agian same email via CRON
                                        echo "\n----\n";
                                        echo "Here"; 
                                        echo "\n----\n";
                                        $update_header = "update {course_user_assign_notifications} set assign_notification = 2 where id = ".$keyCUAN->id;
                                        $DB->execute($update_header);
                                                           
                                    } //end if email
                                }// end flag assign completion set
                            } //end cmAssignNoti
                        }//end if min
                    }//end if course NOT 22
                }//end else course
            }//end else user
        }//end foreach
    }//@fn ends

}//@class ends