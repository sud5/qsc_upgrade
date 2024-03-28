<?php

$courseArrSql = "SELECT e.courseid as id FROM {user_enrolments} ue JOIN {enrol} e ON ue.enrolid = e.id WHERE ue.userid =".$user->id;
$courseArrRs = $DB->get_records_sql($courseArrSql);
if(isset($courseArrRs) && count($courseArrRs)){
    $courseArr = array_keys($courseArrRs);  
    $kin1 =implode(',', $courseArr);   
   // echo $USER->timezone." count ".$USER->country."--".$USER->timezone; exit;

    if($USER->country == "US" && $USER->timezone == "Asia/Kolkata"){
   date_default_timezone_set('America/Los_Angeles');
}else{
   date_default_timezone_set($USER->timezone); 
}


    $roleAssignSql = "SELECT id FROM mdl_role_assignments WHERE userid =$user->id AND contextid=1 and roleid=11";
    $roleAssignRs = $DB->get_record_sql($roleAssignSql);

    if(!empty($roleAssignRs)){
        // $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) and category NOT IN(0,1,14)";
        // $courseRs = $DB->get_records_sql($courseSql);
        // - - - - - - - - - - Start -PrivateCoursePhase2  NAV for private course certificate display and hide.
        if($USER->id == '2'){
            $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) and category NOT IN(0,14)";
            $courseRs = $DB->get_records_sql($courseSql);
        }else{
            $privateGroupcSql =$DB->get_records_sql("SELECT DISTINCT id FROM mdl_course  WHERE visible = 0");
            
            if(!empty($privateGroupcSql)){

                $userGroupCourseList = $SESSION->userPrivateCourseArr;
                if($userGroupCourseList){
                    $removedPrivateC =implode($userGroupCourseList , ',');
                    $hideUserGroupCourseList = $DB->get_records_sql("SELECT DISTINCT id FROM mdl_course  WHERE id NOT IN(".$removedPrivateC.") AND visible = 0");
                    $privateCourseArr = array_keys($hideUserGroupCourseList);

                    $removedPrivateC =implode($privateCourseArr, ',');
                    if(!empty($removedPrivateC))
                    {
                        $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) AND id NOT IN($removedPrivateC) AND category NOT IN(0,14) ";  
                    }else{
                        $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1)  AND category NOT IN(0,14)";
                    }
                }else{
                    $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) AND category NOT IN(0,1,14)";
                    // $userGroupSQLPrivateR = $DB->get_records_sql("SELECT ctp.courseid FROM `mdl_course_tags_pc` as ctp  JOIN `mdl_user_tags_pc` as utp ON ctp.tagid= utp.tagid WHERE utp.userid = '".$USER->id."' LIMIT 0,1");
                    // $privateCourseArr = array_keys($userGroupSQLPrivateR);
                    // $removedPrivateCourse =implode($privateCourseArr, ',');
                    // if(!empty($removedPrivateCourse)){
                    //  $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) AND id NOT IN ($removedPrivateCourse)  AND category NOT IN(0,14)";
                    // }else{
                    //     $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) AND category NOT IN(0,14)";
                    // }
                }  
            }else{
                $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) AND visible = 1 AND category NOT IN(0,14)";
            }
            //$DB->set_debug(true);
            $courseRs = $DB->get_records_sql($courseSql);
            //$DB->set_debug(false);
            
        }
       // - - - - - - - - - - End -PrivateCoursePhase2  NAV 
    }else{
        $courseSql = "SELECT id, fullname, shortname FROM {course} WHERE id IN($kin1) and visible = 1";
        $courseRs = $DB->get_records_sql($courseSql);
    }

    ?>

    <div class="fixheight">
    <?php
        //echo "<pre>"; print_r($courseRs);
        echo "<ul id='certificateStatusUL'>";        
            foreach ($courseRs as $rsKey => $rsValue) { 
                $isCertified = 'No';
//Lifetime code start
                $certLifetimeObj = $DB -> get_record_sql('SELECT course FROM mdl_simplecertificate WHERE course = '.$rsValue->id.' and certexpirydateinyear = 99 order by id desc limit 0,1');

                    if($certLifetimeObj->course){
                         $sqlSCI_2 = 'SELECT sci.id, sci.certificateid, sci.timecreated FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id WHERE sc.course = '.$rsValue->id.' AND sci.userid='.$user->id;
        
                        $issueCert_2_ObjData = $DB->get_record_sql($sqlSCI_2);
                        
                        if(!empty($issueCert_2_ObjData)){
                            $isCertified = '<dd>'.date('F d, Y',$issueCert_2_ObjData->timecreated).' - Lifetime'.'</dd>';
                        }
                        else if(empty($issueCert_2_ObjData)){
                        
                            $sqlm41 = 'SELECT id, userid, timecompletion
                                  FROM {simplecertificate_issue_logs}
                                 WHERE courseid='.$rsValue->id.' and userid ='.$user->id.' AND timecompletion IS NOT NULL order by id desc limit 0,1';
                        
                            $rs4 = $DB->get_record_sql($sqlm41);
                            if(!empty($rs4)){
                                $isCertified = '<dd>'.date('F d, Y',$rs4->timecompletion).' - Lifetime'.'</dd>';   
                            }
                            else{
                                $isCertified = 'No';
                            }     
                        }
                   /* if($rsValue->id === '42'){
                        $sqlSCI_2 = 'SELECT sci.id, sci.certificateid, sci.timecreated FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id WHERE sc.course = 42 AND sci.userid='.$user->id;
        
                        $issueCert_2_ObjData = $DB->get_record_sql($sqlSCI_2);
                        
                        if(!empty($issueCert_2_ObjData)){
                            $isCertified = '<dd>'.date('F d, Y',$issueCert_2_ObjData->timecreated).' - Lifetime'.'</dd>';
                        }
                        else if(empty($issueCert_2_ObjData)){
                        
                            $sqlm41 = 'SELECT id, userid, timecompletion
                                  FROM {simplecertificate_issue_logs}
                                 WHERE courseid=42 and userid ='.$user->id.' AND timecompletion IS NOT NULL order by id desc limit 0,1';
                        
                            $rs4 = $DB->get_record_sql($sqlm41);
                            if(!empty($rs4)){
                                $isCertified = '<dd>'.date('F d, Y',$rs4->timecompletion).' - Lifetime'.'</dd>';   
                            }
                            else{
                                $isCertified = 'No';
                            }     
                        }*/
                    //Lifetime code end
                    }
                    else{
                        /* Level 1 certified start */
                        $sql = "SELECT id as courseinstance FROM {course} c WHERE id = $rsValue->id order by id";
                        $rs = $DB->get_records_sql($sql);
                        $userid = $user->id;
                        if($rs){
                            $flagrs=0;
                            foreach($rs as $rskey){
                                if($flagrs == 0){
                        
                                    $sqlSCI_1 = 'SELECT sci.id, sci.certificateid, sci.timecreated, sci.timeexpired FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id WHERE sc.course = '.$rsValue->id.' AND sci.userid='.$user->id;
                        
                                    $issueCert_1_ObjData = $DB->get_record_sql($sqlSCI_1);
                        
                                    if(!empty($issueCert_1_ObjData)){ 
                                        // - - - - - - Start -Reporting error case II --  --  -//
                                        if($issueCert_1_ObjData->timeexpired == 99){
                                           $isCertified = '<dd>'.date('F d, Y',$issueCert_1_ObjData->timecreated).' - Lifetime'.'</dd>';  ; 
                                        }
                                        elseif($issueCert_1_ObjData->timeexpired < time()){  
                                           $isCertified = '<dd>Expired</dd>'; 
                                           $flagrs=2;
                                        } 
                                        else{   
                                            $isCertified = '<dd>'.date('F d, Y',$issueCert_1_ObjData->timecreated).' - '.date('F d, Y',$issueCert_1_ObjData->timeexpired).'</dd>';
                                            $flagrs=2;
                                        }
                                        // - - - - - End- -Reporting error case II --  --  -//
                                    }
                                   /* else{
                                        $sqlSIL = 'SELECT id, notification, timecompletion FROM {simplecertificate_issue_logs} WHERE userid='.$userid.' AND courseid='.$rskey->courseinstance.' AND timecompletion != "" order by timecompletion limit 0,1';
                                            $silObjData = $DB->get_record_sql($sqlSIL);
                                        if($silObjData){                    
                                            $sqlSCI_log = 'SELECT sc.certexpirydateinyear FROM {simplecertificate} sc WHERE sc.course ='.$rsValue->id;                    
                                            $issueCert_log_ObjData = $DB->get_record_sql($sqlSCI_log);
                                            $certexpirydateinyear = $issueCert_log_ObjData->certexpirydateinyear;
                                            $certexpirydate = strtotime('+'.$certexpirydateinyear.'years', $silObjData->timecompletion);
                        
                                            $isCertified = '<dd>'.date('F d, Y',$silObjData->timecompletion).' - '.date('F d, Y',$certexpirydate).'</dd>';
                                            $flagrs=2;
                                        }
                                        else{
                                            $flagrs=0;
                                            $isCertified = 'No';
                                        }
                                    } */
                                }
                            }
                        } 
                    }
                    if($isCertified != 'No'){
                    $certFlag = true;
                        ?>
                        <li class="contentnode">
                            <dl>
                                <dt>
                                <?php 
                                    echo format_string($rsValue->fullname);  ////Fixed bug #2419 by calling format_string function before displaying the course name.
                                ?>
                                </dt>
                                <dd><?php echo $isCertified;?></dd>
                            </dl>
                        </li>
                    <?php
                    }
       }
        if(!$certFlag){
            echo '<div class="table-view tabel_base_cls">Not Found</div>';
        }
        echo '</ul></div>';

    }
    else{
        echo '<div class="table-view tabel_base_cls">Not Found</div>';
    }
  ?>

    <script type="text/javascript">
       function certificateStatusFunction() {
            // Declare variables
            var input, filter, ul, li, a, i;
            input = document.getElementById('certificateStatusInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById("certificateStatusUL");
            li = ul.getElementsByTagName('li');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("dt")[0];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
