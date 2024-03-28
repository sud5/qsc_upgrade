<?php
require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot .'/mod/facetoface/lib.php');
function enrol_get_my_courses_profile_new($fields = NULL, $sort = 'visible DESC,sortorder ASC', $limit = 0,$userid) {
    global $DB;

    // Guest account does not have any courses
    if (isguestuser() or !isloggedin()) {
        return(array());
    }

    $basefields = array('id', 'category', 'sortorder',
                        'shortname', 'fullname', 'idnumber',
                        'startdate', 'visible',
                        'groupmode', 'groupmodeforce', 'cacherev');

    if (empty($fields)) {
        $fields = $basefields;
    } else if (is_string($fields)) {
        // turn the fields from a string to an array
        $fields = explode(',', $fields);
        $fields = array_map('trim', $fields);
        $fields = array_unique(array_merge($basefields, $fields));
    } else if (is_array($fields)) {
        $fields = array_unique(array_merge($basefields, $fields));
    } else {
        throw new exception('Invalid $fileds parameter in enrol_get_my_courses()');
    }
    if (in_array('*', $fields)) {
        $fields = array('*');
    }

    $orderby = "";
    $sort    = trim($sort);
    if (!empty($sort)) {
        $rawsorts = explode(',', $sort);
        $sorts = array();
        foreach ($rawsorts as $rawsort) {
            $rawsort = trim($rawsort);
            if (strpos($rawsort, 'c.') === 0) {
                $rawsort = substr($rawsort, 2);
            }
            $sorts[] = trim($rawsort);
        }
        $sort = 'c.'.implode(',c.', $sorts);
        $orderby = "ORDER BY $sort";
    }

    $wheres = array("c.id <> :siteid");
    $params = array('siteid'=>SITEID);

    $coursefields = 'c.' .join(',c.', $fields);
    $ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
    $ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
    $params['contextlevel'] = CONTEXT_COURSE;
    $wheres = implode(" AND ", $wheres);

    //note: we can not use DISTINCT + text fields due to Oracle and MS limitations, that is why we have the subselect there
    $sql = "SELECT $coursefields $ccselect
              FROM {course} c
              JOIN (SELECT DISTINCT e.courseid
                      FROM {enrol} e
                      JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = :userid)
                     WHERE ue.status = :active AND e.status = :enabled AND ue.timestart < :now1 AND (ue.timeend = 0 OR ue.timeend > :now2)
                   ) en ON (en.courseid = c.id)
           $ccjoin
             WHERE $wheres
          $orderby";
    $params['userid']  = $userid;
    $params['active']  = ENROL_USER_ACTIVE;
    $params['enabled'] = ENROL_INSTANCE_ENABLED;
    $params['now1']    = round(time(), -2); // improves db caching
    $params['now2']    = $params['now1'];

    $courses = $DB->get_records_sql($sql, $params, 0, $limit);

    // preload contexts and check visibility
    foreach ($courses as $id=>$course) {
        context_helper::preload_from_record($course);
        if (!$course->visible) {
            if (!$context = context_course::instance($id, IGNORE_MISSING)) {
                unset($courses[$id]);
                continue;
            }
            if (!has_capability('moodle/course:viewhiddencourses', $context)) {
                unset($courses[$id]);
                continue;
            }
        }
        $courses[$id] = $course;
    }

    //wow! Is that really all? :-D

    return $courses;
}
$enrolledCourses = enrol_get_my_courses_profile($fields = NULL, $sort = 'visible DESC,sortorder ASC', $limit = 0,$user->id);
$courseArr = array_keys($enrolledCourses);
$kin1 =implode(',', $courseArr);

   $courseArrSql = "SELECT * FROM {course_completions} WHERE userid =$user->id order by timeenrolled";
   $courseArrRs = $DB->get_record_sql($courseArrSql);

   if($courseArrRs){
    if($courseArrRs->timestarted == 0 && $courseArrRs->timeenrolled == 0 && $courseArrRs->timecompleted > 0){
        $courseArrRs->timestarted=2;
        $courseArrRs->timeenrolled=2;
        $DB->update_record('course_completions',$courseArrRs);
    }    
   }
//US #824 start
   $courseArrSqls = "SELECT * FROM {course_completions} WHERE userid =$user->id and timestarted=0 and timeenrolled > 0 order by timeenrolled desc";
   $courseArrRss = $DB->get_records_sql($courseArrSqls);
   foreach ($courseArrRss as $keyRss) {
       # code...
        $keyRss->timestarted=$keyRss->timeenrolled;       
        $DB->update_record('course_completions',$keyRss);
   }
   //US #824 end
//835006540
   if($kin1){
        $courseArrSql = "SELECT course as id FROM {course_completions} WHERE course IN ($kin1) and userid =$user->id AND (timestarted NOT IN ( 0, 1 ) or timeenrolled > 0 ) and timestarted NOT IN ( 0, 1 ) order by timeenrolled";
        $courseArrRs = $DB->get_records_sql($courseArrSql);
    }
 // New code update - 21 - Feb -18 - end



    if(isset($courseArrRs) && count($courseArrRs) && !empty($kin1)){
   
            $courseArr = array_keys($courseArrRs);  
            $kin1 =implode(',', $courseArr);


            //$courseSql = "SELECT id, fullname, shortname, category FROM {course} WHERE id IN($kin1) and category NOT IN(0,14) and visible=1";     
            //$courseRs = $DB->get_records_sql($courseSql);
       $roleAssignSql = "SELECT id FROM mdl_role_assignments WHERE userid =$user->id AND contextid=1 and roleid=11";
   $roleAssignRs = $DB->get_record_sql($roleAssignSql);
    // ----- customization_Sameer_Start {course version field set} --------//
    if(!empty($roleAssignRs)){
        $courseSql = "SELECT id, fullname, shortname, category, course_version FROM {course} WHERE id IN($kin1) and category NOT IN(0,1,14)";
    }
    else{
        $courseSql = "SELECT id, fullname, shortname, category, course_version FROM {course} WHERE id IN($kin1) and category NOT IN(0,14) and visible=1";
    }
    // ----- customization_Sameer_End {course version field set} --------//
            $courseRs = $DB->get_records_sql($courseSql);
            
            ?>
<!-- <div class="fiealdsearch" id=><input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search"></div> -->
<div class="fixheight_TEST">
<?php
    echo "<ul id='myUL'>";
        foreach ($courseRs as $rsKey => $rsValue) {
            $userEnrolledStatus42 = $userEnrolledStatus = '';
            // -- - - - - - -- - -- --  -- - Start -Feature Request: Old Student Exams-  NAV -- - -  -- - - - - - - -- - -//
                        $examFileLink ="";
                            //if($user->id== 835003818){
                            
                                $component = 'assignsubmission_file';
                                $filearea = 'submission_files';
                                
                                    $sqlAssigSubQuery = "SELECT s.id,s.status, s.timemodified, cm.id as course_modules_id  FROM {assign_submission} s LEFT JOIN {course_modules} cm ON s.assignment = cm.instance AND cm.module =1
                                        WHERE cm.course = ".$rsValue->id." AND s.userid = ".$user->id." AND s.status='submitted'   AND s.status IS NOT NULL  AND s.status != 'new' ORDER BY s.timemodified DESC LIMIT 0,1";
                                
                                $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery);
                                if(!empty($sqlAssigSubData)){
                                    // this is other assign submission id that is comming from main query
                                    $submissionid = $sqlAssigSubData->id;
                                    $context = context_module::instance($sqlAssigSubData->course_modules_id);

                                    //print_r($rsStudentRecord);die;
                                    $fs = get_file_storage();
                                    $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);

                                    //print_r($dir);
                                    //print_r($files);
                                    //$j = 0;
                                    foreach ($dir['files'] as $file) {

                                        //print_r($file);
                                        $file->portfoliobutton = '';

                                        $filename = $file->get_filename();
                                        $url = "";
                                        $ext = pathinfo($filename, PATHINFO_EXTENSION);

                                        if( $ext == 'pdf'){ 
                                                $url = $OUTPUT->pix_url('pdfsmall', 'theme');
                                                
                                        }elseif( $ext == 'doc' || $ext == 'docx'){ 
                                                $url = $OUTPUT->pix_url('word', 'theme');
                                                
                                        }elseif( $ext == 'xlsx' || $ext == 'csv'){ 
                                                $url = $OUTPUT->pix_url('excel', 'theme');
                                                
                                        }elseif( $ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){ 
                                                $url = $OUTPUT->pix_url('image', 'theme');
                                               
                                        }elseif( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
                                                $url = $OUTPUT->pix_url('otherfile', 'theme');   
                                        }  
                                        //$examFileLink.="<img class='icon' alt='".$filename."' title='".$filename."' src=".$url.">";
                                        $path = '/' .
                                                $context->id .
                                                '/' .
                                                $component .
                                                '/' .
                                                $filearea .
                                                '/' .
                                                $file->get_itemid() .
                                                $file->get_filepath() .
                                                $file->get_filename();
                                        $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
                                        $file_view =$filename;
                                        if(strlen($filename) > 20){
                                            $file_view =substr($filename,0,20).'...';
                                        }
                                        $examFileLink.= "<li style='list-style-type: none; margin-left: 0px;'>".html_writer::link($url, $file_view,array('title' => $filename))."</li>";
                                    }
                            }
                        //}
// -- - - - - - -- - -- --  -- - End -Feature Request: Old Student Exams-  NAV -- - -  -- - - - - - - -- - -//
         ?>
<li class="contentnode">
    <dl>
        <dt>
        <?php 
        echo format_string($rsValue->fullname); ////Fixed bug #2419 by calling format_string function before displaying the course name.
        ?>
        </dt> 
        <?php

        // Module status - start
            if(($rsValue->category === '4') || ($rsValue->category === '3')){
                $coursemoduleArr = "3,16,15";
                $sectionDataSql = "SELECT cs.id, cs.sequence, cm.course FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsValue->id AND cm.module IN($coursemoduleArr) GROUP BY cm.section";
                $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                //$sectionDataRs['checku']=1298;
                
            }
            elseif ($rsValue->category === '1') {

                if($rsValue->id == 22){
                    $coursemoduleArr = "16";
                            $sectionDataSql = "SELECT cs.id, cs.sequence, cm.course FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsValue->id AND cm.module IN($coursemoduleArr) GROUP BY cm.section";
                            $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                }
                else{                
                    $coursemoduleArr = "16";
                            $sectionDataSql = "SELECT cs.id, cs.sequence, cm.course FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsValue->id AND cm.module IN($coursemoduleArr) GROUP BY cm.section";
                            $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                }
               

            }
            elseif ($rsValue->category === '17') {
                $coursemoduleArr = "3,16";
                $sectionDataSql = "SELECT cs.id, cs.sequence, cm.course FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsValue->id AND cm.module IN($coursemoduleArr) GROUP BY cm.section";
                $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                
            } 
            elseif ($rsValue->category === '2') {
                $coursemoduleArr = "25";
                $sectionDataSql = "SELECT cs.id, cs.sequence, cm.course FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsValue->id AND cm.module NOT IN($coursemoduleArr) GROUP BY cm.section";
                $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                
            }            
            else{
                $coursemoduleArr = "1,25,27";
                $sectionDataSql = "SELECT cs.id, cs.sequence, cm.course FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $rsValue->id and cm.module NOT IN($coursemoduleArr) GROUP BY cm.section";
                $sectionDataRs = $DB->get_records_sql($sectionDataSql);
                
            }            
           // echo "<pre>";
           // print_r($sectionDataRs);
            $cmIdsArr = array(); $sectionSeqCnt = $moduleCompleted = 0;
            foreach ($sectionDataRs as $key) {
                $sequence = $key->sequence;
                //$sectionSeqCnt = count(explode(",", $sequence));
                //$cmIdsArr[$key->id] = explode(",", $sequence);
                //echo $sectionSeqCnt."<br>";

                $completeCoursesModulesDataRes = array();

                /*$completeCoursesModulesDataSQL = "SELECT cmc.id,cmc.userid,cmc.coursemoduleid FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =$rsValue->id and cmc.userid= $user->id AND cmc.coursemoduleid IN($sequence)";*/

                //US #3819 add admin_reset_flag
                $completeCoursesModulesDataSQL = "SELECT cmc.id,cmc.userid,cmc.coursemoduleid FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =$rsValue->id and cmc.userid= $user->id AND cmc.coursemoduleid IN($sequence) AND (cmc.completionstate != 3 and cmc.completionstate !=0)";
                // $completeCoursesModulesDataSQL = "SELECT cmc.id,cmc.userid,cmc.coursemoduleid FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =$rsValue->id and cmc.userid= $user->id AND cmc.coursemoduleid IN($sequence) AND cmc.completionstate != 3";

                $completeCoursesModulesDataRes = $DB->get_records_sql($completeCoursesModulesDataSQL);

                $sectionSeqCntRs = array();
                $sectionSeqCntSql = "SELECT id FROM  `mdl_course_modules` WHERE section = $key->id and visible = 1";
                $sectionSeqCntRs = $DB->get_records_sql($sectionSeqCntSql);
        
        //if(count($completeCoursesModulesDataRes) == $sectionSeqCnt){
            if(count($completeCoursesModulesDataRes) >= count($sectionSeqCntRs)){
                    if(!empty($sectionSeqCntRs)){
                        $moduleCompleted++;
                    }
                }

            }

            //change-1
            $courseAssignCNTSQL = 'SELECT count(id) as cntexam FROM {assign} WHERE course ='.$rsValue->id;
            $courseAssignCNTRes = $DB->get_record_sql($courseAssignCNTSQL);
            //echo "<pre>"; print_r($courseAssignCNTRes->cntexam); exit;

            $courseAssignSQL = 'SELECT id FROM {assign} WHERE course ='.$rsValue->id.' and type ="classroom"' ;
            $courseAssignRes = $DB->get_record_sql($courseAssignSQL);
            
            //for 'exam only' course setting
            $clsssroomCourse= $DB->get_record_sql("Select * FROM `mdl_course` WHERE  NOT EXISTS (SELECT * FROM `mdl_course_modules`  WHERE  module IN(3,16) AND mdl_course.id= mdl_course_modules.course ) AND mdl_course.id= ".$rsValue->id);

            // ----- customization_Sameer_Start (Apply if condition only) --------//
            if($rsValue->course_version == NULL || $rsValue->course_version == "normal"){
                if(!empty($courseAssignRes) && $courseAssignCNTRes->cntexam == 1){
                    $totalcompletedmodules = '';
                } //for exam only course setting
                elseif(!empty($clsssroomCourse)){
                    $totalcompletedmodules = '';
                }
                else{
                    echo $totalcompletedmodules = '<dd style="color:red" class="setredDflt">'.$moduleCompleted.' out of '.count($sectionDataRs).' modules completed '.'</dd>';
                }
            }
           // ----- customization_Sameer_End --------//

            // ----- customization_Sameer_Start (Add Classroom Details complete code structure) --------//
            if($rsValue->course_version !== NULL){
                $sqlSCI_212 = 'SELECT sci.id, sci.certificateid, sci.timecreated, sci.timeexpired
                              FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id
                             WHERE sc.course = '.$rsValue->id.' AND sci.userid='.$user->id;
                    
                $issueCert_212_ObjData = $DB->get_record_sql($sqlSCI_212);
                if(empty($issueCert_212_ObjData)){
                    $facetoface7 = $DB->get_record('facetoface', array('course' => $rsValue->id ));
                    if(!empty($facetoface7)) {
                         //echo "Test2";
                        //US #824 start changes on query and add for each loop
                        // $ftof_signups_query7 = "SELECT fse.*, fs.userid, fs.id as signupid FROM {facetoface_sessions} fse JOIN {facetoface_signups} fs ON fs.sessionid = fse.id WHERE fs.userid =". $user->id ." AND fse.facetoface = $facetoface7->id order by fs.id desc";
                        // $ftof_signups_result7 = $DB->get_record_sql($ftof_signups_query7);
                        $ftof_signups_query7 = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $user->id AND fss.superceded=0 AND fse.facetoface = $facetoface7->id AND (fss.statuscode = 70 OR fss.statuscode = 60) order by fss.id asc";
                        $ftof_signups_result7Obj = $DB->get_records_sql($ftof_signups_query7);
                        //US #824
                        $ijk=0;
                        foreach( $ftof_signups_result7Obj as $ftof_signups_result7){
                        //US #824 end changes on query and add for each loop
                         // print_r($ftof_signups_result7);
                        if(!empty($ftof_signups_result7)){
                            //US #824 start
                                if($ijk > 0)
                                    echo "<br>===============<br>";
                                //US #824 end
                            $s7 = $ftof_signups_result7->id;

                            $session7 = facetoface_get_session($s7);
                            $sessDetails7 = facetoface_signup_session( $rsValue->id , $session7, true);
                            //echo count($sessDetails7); exit("call 22");
                            if($sessDetails7['ts'][1][0]->timestart != "2481889900"){ 
                                $userEnrolledStatus = '<br><dd>Title: '.$sessDetails7[6][1].'</dd>';        
                                //-- Start-Edit Status Training Box Info-Customized by Naveen --//
                                //US #3072 start changes
                                if($sessDetails7[17][0] == "Date"){
                                    $userEnrolledStatus .= '<dd>Dates: '.$sessDetails7[17][1].'</dd>';
                                }else{               
                                    $userEnrolledStatus .= '<dd>Dates:'.$sessDetails7[16][1].'</dd>';
                                }
                                //US #3072 end changes
                                //-- End-Edit Status Training Box Info-Customized by Naveen --//
                                $userEnrolledStatus .= '<dd>'.get_string('location', 'facetoface').': '.$sessDetails7[1][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                                $userEnrolledStatus .= '<dd>'.get_string('address', 'facetoface').': '.$sessDetails7[2][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                                if($sessDetails7[5][1] == 'Local Classess'){
                                    $instructorName7 = ($sessDetails7[9][1])?$sessDetails7[9][1]: ' None';
                                    $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                                }
                                else{
                                    $instructorName7 = ($sessDetails7[8][1])?$sessDetails7[8][1]: ' None';
                                    $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                                }                          
                            }
                            else{
                                $userEnrolledStatus = '<dd>Title: '.$sessDetails7[6][1].'</dd>'; 
                                $userEnrolledStatus .= '<dd>Dates: Not Declare date of this classroom</dd>';
                                $userEnrolledStatus .= '<dd>'.get_string('location', 'facetoface').': '.$sessDetails7[1][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                                $userEnrolledStatus .= '<dd>'.get_string('address', 'facetoface').': '.$sessDetails7[2][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                                if($sessDetails7[5][1] == 'Local Classess'){
                                    $instructorName7 = ($sessDetails7[9][1])?$sessDetails7[9][1]: ' None';
                                    $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                                }
                                else{
                                    $instructorName7 = ($sessDetails7[8][1])?$sessDetails7[8][1]: ' None';
                                    $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                                }                          
                            }

                            $sqlSCI_213 = "SELECT fss.* 
                              FROM {facetoface_signups_status} fss where fss.signupid = $ftof_signups_result7->signupid
                             AND fss.superceded= 0 order by fss.id desc";
                    
                            $issueCert_213_ObjData = $DB->get_record_sql($sqlSCI_213);
                           // print_r($issueCert_213_ObjData);

                            if($issueCert_213_ObjData->statuscode == 60){
                                $userEnrolledStatus .= '<dd><strong>'.get_string('waiting', 'my').'</strong></dd>';
                            }
                            echo $userEnrolledStatus;
                            $userEnrolledStatus = '';
                                //US #824 start
                                $ijk++;  
                                //US #824 end                             
                            }
                        //US #824 start
                        }
                        //US #824 end
                    }
                }
            }
            // ----- customization_Sameer_End --------//
        // Module status - end 
//US #824 if cond
            if($courseAssignCNTRes->cntexam == 2 && !empty($ijk) == 0){

                // Lelel 1 training In-Class start
                $facetoface7 = $DB->get_record('facetoface', array('course' => $rsValue->id ));
                if(!empty($facetoface7)){
                   //US #824 start changes in query and added foreach loop
                 $ftof_signups_query7 = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $user->id AND fss.superceded=0 AND fse.facetoface = $facetoface7->id AND (fss.statuscode = 70 OR fss.statuscode = 60) order by fss.id asc";
                  $ftof_signups_result7Obj = $DB->get_records_sql($ftof_signups_query7);
                //  echo count($ftof_signups_result7Obj); print_r($ftof_signups_result7Obj); exit("call 22");
                  //US #824 add below variable
                  $jik=0;
                  foreach($ftof_signups_result7Obj as $ftof_signups_result7){
                    //US #824 end changes in query and added foreach loop
                     //US #824 start
                     if($jik > 0)
                        echo "<br>=======<br>";
                   //US #824 end
                  
                if(!empty($ftof_signups_result7)){
                    $s7 = $ftof_signups_result7->id;
                    $session7 = facetoface_get_session($s7);
                    $sessDetails7 = facetoface_signup_session( $rsValue->id , $session7, true);
                    
                    if($sessDetails7['ts'][1][0]->timestart != "2481889900"){ 
                        $userEnrolledStatus = '<br><dd>Title: '.$sessDetails7[6][1].'</dd>';
                        //-- Custom Message - Nav --//        
                         //US #3072 start changes
                        if($sessDetails7[17][0] == "Date"){
                            $userEnrolledStatus .= '<dd>Dates: '.$sessDetails7[17][1].'</dd>';
                        }else{               
                            $userEnrolledStatus .= '<dd>Dates:'.$sessDetails7[16][1].'</dd>';
                        }
                        //US #3072 end changes
                        $userEnrolledStatus .= '<dd>'.get_string('location', 'facetoface').': '.$sessDetails7[1][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        $userEnrolledStatus .= '<dd>'.get_string('address', 'facetoface').': '.$sessDetails7[2][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        if($sessDetails7[5][1] == 'Local Classess'){
                            $instructorName7 = ($sessDetails7[9][1])?$sessDetails7[9][1]: ' None';
                            $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                        }
                        else{
                            $instructorName7 = ($sessDetails7[8][1])?$sessDetails7[8][1]: ' None';
                            $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                        }                          
                    }
                    else{
                        $userEnrolledStatus = '<dd>Title: '.$sessDetails7[6][1].'</dd>'; 
                        $userEnrolledStatus .= '<dd>Enrolled: Not Declare date of this classroom</dd>';
                        $userEnrolledStatus .= '<dd>'.get_string('location', 'facetoface').': '.$sessDetails7[1][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        $userEnrolledStatus .= '<dd>'.get_string('address', 'facetoface').': '.$sessDetails7[2][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        if($sessDetails7[5][1] == 'Local Classess'){
                            $instructorName7 = ($sessDetails7[9][1])?$sessDetails7[9][1]: ' None';
                            $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                        }
                        else{
                            $instructorName7 = ($sessDetails7[8][1])?$sessDetails7[8][1]: ' None';
                            $userEnrolledStatus .= '<dd>Instructor: '.$instructorName7.'</dd>';
                        }                          
                    }
                    echo $userEnrolledStatus;
                            $userEnrolledStatus = '';
                                //US #824 start
                                $jik++;  
                                //US #824 end  
                }
                 //US #824 start changes in query and added foreach loop
                }
                //US #824 end changes in query and added foreach loop               
              }
                
                //$rsUserAssign = array();
                //echo $rsValue->id ." finding1";
                $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='classroom'";
                $rsUserAssign = $DB->get_record_sql($sql);

        /*if(empty($rsUserAssign)){
            $sql = "SELECT * FROM {assign} where course=7";
                    $rsUserAssign = $DB->get_record_sql($sql);
        }*/
                
                $sqlSCI_1 = 'SELECT sci.id, sci.certificateid, sci.timecreated, sci.timeexpired
                          FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id
                         WHERE sc.course = '.$rsValue->id.' AND sci.userid='.$user->id;
                
                $issueCert_1_ObjData = $DB->get_record_sql($sqlSCI_1);
                
              // change LMS Reporting Error start
                 if($courseAssignCNTRes->cntexam == 1){
                                        $sql = "SELECT * FROM {assign} where course=$rsValue->id";
                                        $rsUserAssign = $DB->get_record_sql($sql);
                                        if($rsUserAssign){
                                            $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                                            $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);

                                            $sqlUserAssignGradeStatus = "SELECT grade, assignment FROM `mdl_assign_grades` WHERE `assignment` = $rsUserAssign->id and grade >= 70 AND userid = $user->id";
                                            $rsUserAssignGradeStatus = $DB->get_record_sql($sqlUserAssignGradeStatus);

                                            if($rsUserAssignGradeStatus){
                                                $userEnrolledStatus = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                                $flagrs=2;
                                            }
                                        }
                                    }
                                    elseif($courseAssignCNTRes->cntexam == 2){
                                        $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='classroom'";
                                        $rsUserAssign = $DB->get_record_sql($sql);
                                        if($rsUserAssign){
                                            //echo "Level classroom ".$rsValue->id;
                                            $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                                            $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);

                                            $sqlUserAssignGradeStatus = "SELECT grade, assignment FROM `mdl_assign_grades` WHERE `assignment` = $rsUserAssign->id and grade >= 70 AND userid = $user->id";
                                            $rsUserAssignGradeStatus = $DB->get_record_sql($sqlUserAssignGradeStatus);

                                            if($rsUserAssignGradeStatus){
                                                $userEnrolledStatus = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                                $flagrs=2;
                                            }
                                        }
                                        if(!empty($flagrs)!=2){
                                           // echo "Level online ".$rsValue->id;
                                            $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='online'";
                                            $rsUserAssign = $DB->get_record_sql($sql);
                                            if($rsUserAssign){
                                                $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                                                $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);

                                                $sqlUserAssignGradeStatus = "SELECT grade, assignment FROM `mdl_assign_grades` WHERE `assignment` = $rsUserAssign->id and grade >= 70 AND userid = $user->id";
                                                $rsUserAssignGradeStatus = $DB->get_record_sql($sqlUserAssignGradeStatus);

                                                if($rsUserAssignGradeStatus){
                                                    $userEnrolledStatus = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                                    $flagrs=2;
                                                }
                                            }
                                        }
                                    }
                                     // change LMS Reporting Error end
                //echo $rsValue->id ." finding5";
                                                  // change LMS Reporting Error start if condition
                if(!empty($issueCert_1_ObjData) && $flagrs!=2){
                    $userEnrolledStatus .= "<dt class ='grading-green'>Passing Score!</dt>";

                }//               // change LMS Reporting Error start
                elseif($flagrs!=2){ // change LMS Reporting Error start if condition
                    //Her changes
                    $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='classroom'";
                    $rsUserAssign = $DB->get_record_sql($sql);

                    if(empty($rsUserAssign->id)){
                        $rsUserAssignStatus = '';
                    } else {
                        $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                        $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);
                    }
                    
                    if(empty($rsUserAssignStatus)){
                        $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='online'";
                            $rsUserAssign = $DB->get_record_sql($sql);
            
                        $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                        $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);
                    }

                    if(!empty($rsUserAssignStatus)){
                        $userEnrolledStatus .= '<dd><div>'.$OUTPUT->container(get_string('submissionstatus_' . $rsUserAssignStatus->status, 'assign'),array('class'=>'submissionstatus' .$rsUserAssignStatus->status)).'</div></dd>';
                        if($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin' || $USER->usertype == 'mainadmin'){
                            $sqlCourseModule = "SELECT id FROM `mdl_course_modules` WHERE `course` = $rsValue->id and module = 1 and instance =".$rsUserAssignStatus->assignment;
                            $rsCourseModule = $DB->get_record_sql($sqlCourseModule);
                
                            $params = array('id' => $rsCourseModule->id,'action'=>'grade','userid'=>$user->id,'rownum'=>0);
                            $gradetUrl = new moodle_url("$CFG->wwwroot/mod/assign/view.php", $params);
                
                            $userEnrolledStatus .= "<dt class = 'actn-btns'><a href='$gradetUrl' >Grade</a></dt>";
                        }
                    }
                }
                echo $userEnrolledStatus;
                // -- - - - - - -- - -- --  -- - Start -Feature Request: Old Student Exams-  NAV -- - -   -- - -//
                echo "<dt style='margin-top:5px;'>".$examFileLink."</dt>";
                // -- - - - - - -- - -- --  -- - End -Feature Request: Old Student Exams-  NAV -- - -  -- - - - -//
                // Lelel 1 training In-Class end
            }
            elseif(!empty($courseAssignRes) && $courseAssignCNTRes->cntexam == 1 && empty($s7)) { //US #824 change if cond logical operator
                // Lelel 2 training start
            
                $facetoface42 = $DB->get_record('facetoface', array('course' => $rsValue->id));
                if(!empty($facetoface42)){
                 //US #824 start changes in query and added foreach loop
                  $ftof_signups_query42 = "SELECT fse.*,fss.statuscode,fss.signupid FROM {facetoface_sessions} fse JOIN ({facetoface_signups} fs JOIN {facetoface_signups_status} fss ON fs.id=fss.signupid) ON fs.sessionid = fse.id WHERE fs.userid = $user->id AND fss.superceded=0 AND fse.facetoface = $facetoface42->id AND (fss.statuscode = 70 OR fss.statuscode = 60) order by fss.id asc";
                  $ftof_signups_result42Obj = $DB->get_records_sql($ftof_signups_query42);
                  //US #824 start
                  //echo "<pre>"; print_r($ftof_signups_result42Obj); exit("call 23");
                  $jk=0;
                  //US #824 end
                  foreach ($ftof_signups_result42Obj as $ftof_signups_result42) {
                    //US #824 end changes in query and added foreach loop
                    //US #824 start
                     if($jk > 0)
                        echo "<br>=======<br>";
                   //US #824 end
                    //US 824 if cond changes
                if(!empty($ftof_signups_result42) && $s7 != $ftof_signups_result42->id){
                    $s42 = $ftof_signups_result42->id;
                    $session42 = facetoface_get_session($s42);
                    $sessDetails42 = facetoface_signup_session($rsValue->id, $session42, true);
            
                    if($sessDetails42['ts'][1][0]->timestart != "2481889900"){ 
                        $userEnrolledStatus42 = '<br><dd>Title: '.$sessDetails42[6][1].'</dd>';
                        //-- Custom Message - Nav --//        
                        //$userEnrolledStatus42 .= '<dd>Enrolled: '.$sessDetails42[14][1].'</dd>';
                       //US #3072 start changes
                        if($sessDetails42[17][0] == "Date"){
                            $userEnrolledStatus42 .= '<dd>Dates: '.$sessDetails42[17][1].'</dd>';
                        }else{
                            $userEnrolledStatus42 .= '<dd>Dates: '.$sessDetails42[16][1].'</dd>';
                        }      
                        //US #3072 end changes
                        $userEnrolledStatus42 .= '<dd>'.get_string('location', 'facetoface').': '.$sessDetails42[1][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        $userEnrolledStatus42 .= '<dd>'.get_string('address', 'facetoface').': '.$sessDetails42[2][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        if($sessDetails42[5][1] == 'Local Classess'){
                            $instructorName42 = ($sessDetails42[9][1])? $sessDetails42[9][1]:' None';
                            $userEnrolledStatus42 .= '<dd>Instructor: '.$instructorName42.'</dd>';
                        }
                        else{
                            $instructorName42 = ($sessDetails42[8][1])? $sessDetails42[8][1]:' None';
                            $userEnrolledStatus42 .= '<dd>Instructor: '.$instructorName42.'</dd>';
                        } 
                         //US #824 start
                        if($ftof_signups_result42->statuscode == 60){
                            $userEnrolledStatus42 .= '<dd><strong>'.get_string('waiting', 'my').'</strong></dd>';
                        }
                        //US #824 end
                    }
                    else{
                        $userEnrolledStatus42 = '<dd>Title: '.$sessDetails42[6][1].'</dd>'; 
                        $userEnrolledStatus42 .= '<dd>Enrolled: Not Declare date of this classroom</dd>';
                        $userEnrolledStatus42 .= '<dd>'.get_string('location', 'facetoface').': '.$sessDetails42[1][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        $userEnrolledStatus42 .= '<dd>'.get_string('address', 'facetoface').': '.$sessDetails42[2][1].'</dd>'; ////Task 2369, replaced hardcoded text with macro.
                        if($sessDetails42[5][1] == 'Local Classess'){
                            $instructorName42 = ($sessDetails42[9][1])? $sessDetails42[9][1]:' None';
                            $userEnrolledStatus42 .= '<dd>Instructor: '.$instructorName42.'</dd>';
                        }
                        else{
                            $instructorName42 = ($sessDetails42[8][1])? $sessDetails42[8][1]:' None';
                            $userEnrolledStatus42 .= '<dd>Instructor: '.$instructorName42.'</dd>';
                        } 
                    }
                    echo $userEnrolledStatus42;
                            $userEnrolledStatus42 = '';
                                //US #824 start
                                $jk++;  
                                //US #824 end  
                }
                 //US #824 start changes in query and added foreach loop
                }
                //US #824 end changes in query and added foreach loop                
              }
                
                $sqlSCI_2 = 'SELECT sci.id, sci.certificateid, sci.timecreated, sci.timeexpired
                          FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id
                         WHERE sc.course = '.$rsValue->id.' AND sci.userid='.$user->id;
                
                $issueCert_2_ObjData = $DB->get_record_sql($sqlSCI_2);
                
                
                if(!empty($issueCert_2_ObjData)){
                    $userEnrolledStatus42 .= "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";    
                }
                else if(empty($issueCert_2_ObjData)){
                
                    $sqlm42 = 'SELECT id, userid, timecompletion
                          FROM {simplecertificate_issue_logs}
                         WHERE courseid='.$rsValue->id.' and userid ='.$user->id.' AND timecompletion IS NOT NULL order by id desc limit 0,1';
                
                    $rs42 = $DB->get_record_sql($sqlm42);
                    if(!empty($rs42)){
                        $userEnrolledStatus42 .= "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";   
                    } 
                    else{
                        $sql42 = "SELECT * FROM {assign} where course=$rsValue->id and type='classroom'";
                        $rsUserAssign2 = $DB->get_record_sql($sql42);
                
                        $sqlUserAssignStatus42 = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign2->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid =".$user->id;
                        $rsUserAssignStatus42 = $DB->get_record_sql($sqlUserAssignStatus42);    
                
                        if(!empty($rsUserAssignStatus42)){
                            $userEnrolledStatus42 .= '<dd><div>'.$OUTPUT->container(get_string('submissionstatus_' . $rsUserAssignStatus42->status, 'assign'),array('class'=>'submissionstatus' .$rsUserAssignStatus42->status)).'</div></dd>';
                            if($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin' || $USER->usertype == 'mainadmin'){
                                $sqlCourseModule42 = "SELECT id FROM `mdl_course_modules` WHERE `course` =$rsValue->id and instance =".$rsUserAssignStatus42->assignment;
                                $rsCourseModule42 = $DB->get_record_sql($sqlCourseModule42);
                
                                $params = array('id' => $rsCourseModule42->id,'action'=>'grade','userid'=>$user->id,'rownum'=>0);
                                $gradetUrl42 = new moodle_url("$CFG->wwwroot/mod/assign/view.php", $params);
                
                                $userEnrolledStatus42 .= "<dt class = 'actn-btns'><a href='$gradetUrl42'>Grade</a></dt>";
                            }
                        }
                    } 
                }   
                 echo $userEnrolledStatus42;
                 // -- - - - - - -- - -- --  -- - Start -Feature Request: Old Student Exams-  NAV -- - -   -- - -//
                 echo "<dt style='margin-top:5px;'>".$examFileLink."</dt>";
                 // -- - - - - - -- - -- --  -- - Start -Feature Request: Old Student Exams-  NAV -- - -   -- - -//
            }
            else{
            
                $userEnrolledStatus42 = '';
                $sqlUser_AssignGrader_AssignSubmission = "SELECT s.timemodified AS timesubmitted, u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS
            STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, c.visible AS course_visible, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
            FROM mdl_user u 
            LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
            LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
            LEFT JOIN mdl_course c ON c.id = cm.course
            LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
            WHERE cm.course = $rsValue->id AND u.id = $user->id AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' AND (g.grade IS NULL OR g.grade < (SELECT gi.gradepass from mdl_grade_items gi where gi.iteminstance=cm.instance AND gi.itemmodule='assign')) ORDER BY g.timemodified DESC";
            
            //echo $sqlUser_AssignGrader_AssignSubmission; die;
            $rsStudentRecordArray = $DB->get_record_sql($sqlUser_AssignGrader_AssignSubmission);
                   
                    $sql = "SELECT id as courseinstance FROM {course} c WHERE id = $rsValue->id order by id";
                    $rs = $DB->get_records_sql($sql);
                    $userid = $user->id;
                    if($rs){
                        $flagrs=0;
                        foreach($rs as $rskey){
                            if($flagrs == 0){
                                $isCertified = 'No';
                                $sqlSCI_1 = 'SELECT sci.id, sci.certificateid, sci.timecreated, sci.timeexpired FROM {simplecertificate_issues} sci JOIN {simplecertificate} sc ON sci.certificateid = sc.id WHERE sc.course = '.$rsValue->id.' AND sci.userid='.$user->id;
                    
                                $issueCert_1_ObjData = $DB->get_record_sql($sqlSCI_1);
                                
                                //echo $rsValue->id ." finding444 ".$rsUserAssign->id;
                                if(!empty($issueCert_1_ObjData)){

                                    if($issueCert_1_ObjData->timeexpired < time()){
                                       $isCertified = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                       $flagrs=2;
                                    }
                                    else{
                                        $isCertified = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                        $flagrs=2;
                                    }
                                }
                                // change LMS Reporting Error start
                                else{

                                    if($courseAssignCNTRes->cntexam == 1){
                                        $sql = "SELECT * FROM {assign} where course=$rsValue->id";
                                        $rsUserAssign = $DB->get_record_sql($sql);
                                        if($rsUserAssign){
                                            $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                                            $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);

                                            $sqlUserAssignGradeStatus = "SELECT grade, assignment FROM `mdl_assign_grades` WHERE `assignment` = $rsUserAssign->id and grade >= 70 AND userid = $user->id";
                                            $rsUserAssignGradeStatus = $DB->get_record_sql($sqlUserAssignGradeStatus);

                                            if($rsUserAssignGradeStatus){
                                                $isCertified = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                                $flagrs=2;
                                            }
                                        }
                                    }
                                    elseif($courseAssignCNTRes->cntexam == 2){
                                        $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='classroom'";
                                        $rsUserAssign = $DB->get_record_sql($sql);
                                        $flagrsq=1;
                                        if($rsUserAssign){
                                            $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                                            $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);

                                            $sqlUserAssignGradeStatus = "SELECT grade, assignment FROM `mdl_assign_grades` WHERE `assignment` = $rsUserAssign->id and grade >= 70 AND userid = $user->id";
                                            $rsUserAssignGradeStatus = $DB->get_record_sql($sqlUserAssignGradeStatus);

                                            if($rsUserAssignGradeStatus){
                                                $isCertified = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                                $flagrs=2;
                                                $flagrsq=2;
                                            }
                                        }
                                        if($flagrsq==1){
                                            $sql = "SELECT * FROM {assign} where course=$rsValue->id and type='online'";
                                            $rsUserAssign = $DB->get_record_sql($sql);
                                            if($rsUserAssign){
                                                $sqlUserAssignStatus = "SELECT status, assignment FROM `mdl_assign_submission` WHERE `assignment` = $rsUserAssign->id and status IS NOT NULL AND latest =1 AND status != 'new' AND userid = $user->id";
                                                $rsUserAssignStatus = $DB->get_record_sql($sqlUserAssignStatus);

                                                $sqlUserAssignGradeStatus = "SELECT grade, assignment FROM `mdl_assign_grades` WHERE `assignment` = $rsUserAssign->id and grade >= 70 AND userid = $user->id";
                                                $rsUserAssignGradeStatus = $DB->get_record_sql($sqlUserAssignGradeStatus);

                                                if($rsUserAssignGradeStatus){
                                                    $isCertified = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                                    $flagrs=2;
                                                }
                                            }
                                        }
                                    }
                                }
                                // change LMS Reporting Error end
                                /*else{
                                    $sqlSIL = 'SELECT id, notification, timecompletion FROM {simplecertificate_issue_logs} WHERE userid='.$userid.' AND courseid='.$rskey->courseinstance.' AND timecompletion != "" order by timecompletion limit 0,1';
                                        $silObjData = $DB->get_record_sql($sqlSIL);
                                    if($silObjData){                    
                                        $sqlSCI_log = 'SELECT sc.certexpirydateinyear FROM {simplecertificate} sc WHERE sc.course ='.$rsValue->id;                    
                                        $issueCert_log_ObjData = $DB->get_record_sql($sqlSCI_log);
                                        $certexpirydateinyear = $issueCert_log_ObjData->certexpirydateinyear;
                                        $certexpirydate = strtotime('+'.$certexpirydateinyear.'years', $silObjData->timecompletion);
                    
                                        $isCertified = "<dt class ='grading-green' style='width:45%;'>Passing Score!</dt>";
                                        $flagrs=2;
                                    }
                                    else{
                                        $flagrs=0;
                                        $isCertified = 'No';
                                    }
                                }*/
                            }
                        }
                    }
            
                if($isCertified != 'No'){
                    echo $isCertified;
                }
                else{
                    if(!empty($rsStudentRecordArray)){
                        $userEnrolledStatus42 .= '<dd><div>'.$OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecordArray->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecordArray->status)).'</div></dd>';
                        
                        if($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin' || $USER->usertype == 'mainadmin'){
                            $sqlCourseModule42 = "SELECT id FROM `mdl_course_modules` WHERE `course` = $rsValue->id and instance =".$rsStudentRecordArray->assignnment_id;
                            $rsCourseModule42 = $DB->get_record_sql($sqlCourseModule42);
            
                            $params = array('id' => $rsCourseModule42->id,'action'=>'grade','userid'=>$user->id,'rownum'=>0);
                            $gradetUrl42 = new moodle_url("$CFG->wwwroot/mod/assign/view.php", $params);
            
                            $userEnrolledStatus42 .= "<dt class = 'actn-btns'><a href='$gradetUrl42'>Grade</a></dt>";
                        }
                    }
                }
            
                ?>
        <dd><?php echo $userEnrolledStatus42; ?></dd>
        <?php  // -- - - - - - -- - -- --  -- - Start -Feature Request: Old Student Exams-  NAV -- - -   -- - -//
        echo "<dt style='margin-top:5px;' >".$examFileLink."</dt>";
        // -- - - - - - -- - -- --  -- - End -Feature Request: Old Student Exams-  NAV -- - -   -- - -//
         } ?>
    </dl>
</li>
<?php 
    }
     echo '</ul></div>';
    }
    else{
    echo '<div class="table-view tabel_base_cls">Not Found</div>';
    }
    ?>
<style>
    .actn-btns a {
    padding: 8px 14px;
    background: #fff;
    border: 1px solid #ccc;
    color: #585858;
    border-radius: 5px;
    font-family: 'Sinkin Sans 400 Regular',arial !important;
    margin-top: 10px !important;
    }
    .actn-btns a:hover {
    color: #fff !important;
    background-color: #0070a8;
    }
    dt.actn-btns {
    margin-top: 20px;
    /* -- - - - - - -- - -- --  -- - Start -Feature Request: Old Student Exams-  NAV -- - -   -- - -*/
    margin-bottom: 15px;
    /* -- - - - - - -- - -- --  -- - End -Feature Request: Old Student Exams-  NAV -- - -   -- - -*/
    }
    .submissionstatusreopened {
        background-color: orange;
        color: #fff;
        display: inline-block;
        margin-top: 5px;
        padding: 5px;
    }
  .grading-green {
    display: inline-block;
    padding: 5px !important;
    font-size: 12px !important;
    margin-top: 5px;
    width: auto !important;
}
#myUL dd {
    color: #555;
}
.submissionstatussubmitted {
    display: inline-block;
    padding: 5px;
    margin-top: 6px;
    font-size: 12px;
}
.setredDflt {
    font-size: 12px !important;
    margin-bottom: 5px;
}
</style>

<script>
    function trainingStatusFunction() {
        // Declare variables
        var input, filter, ul, li, a, i;
        input = document.getElementById('trainingStatusInput');
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
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
