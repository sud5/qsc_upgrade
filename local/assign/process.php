<?php
// This file is part of the Contact Form plugin for Moodle - http://moodle.org/
//
// Contact Form is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Contact Form is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Contact Form.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This plugin for Moodle is used to send emails through a web form.
 *
 * @package    local_assign
 * @copyright  s@
 * @author     shivagoud
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
//require_once($CFG->libdir.'/accesslib.php');
//require_once($CFG->libdir.'/tablelib.php');
//require_once($CFG->libdir.'/gradelib.php');
//require_once($CFG->libdir.'/portfoliolib.php');
//require_once($CFG->libdir.'/modinfolib.php');
//require_once($CFG->libdir.'/datalib.php');
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once($CFG->dirroot.'/mod/assign/gradingtable.php');
//require_once($CFG->dirroot.'/mod/assign/submission/file/locallib.php');
$action = optional_param('action',  '',  PARAM_RAW);//userid
//
$filter = optional_param('filter','',PARAM_RAW);
$filter_data = json_decode($filter);
$sort = 'lastaccess';
$dir = "ASC";
$requestData = $_REQUEST;
//print_r($requestData);die;
$data = array();
$cdata = array();
$finaldata = array();
// $filter_data = new stdClass();
// if ($requestData['columns'][0]['search'] != "" && $requestData['columns'][0]['search'] != null){
//     $filter_data->category =$requestData['columns'][0]['search']['value'] ;
// }
// if ($requestData['columns'][1]['search'] != "" && $requestData['columns'][1]['search'] != null){
//     $filter_data->poolname =$requestData['columns'][1]['search']['value'] ;
// }
// if ($requestData['columns'][2]['search'] != "" && $requestData['columns'][2]['search'] != null){
//     $filter_data->poolno =$requestData['columns'][2]['search']['value'] ;
// }
// if ($requestData['columns'][3]['search'] != "" && $requestData['columns'][3]['search'] != null){
//     $filter_data->duplicated =$requestData['columns'][3]['search']['value'] ;
// }
$requestDatacount=array();
//
global $OUTPUT,$DB,$PAGE;
//$PAGE->requires->css('/local/assign/style.css',true);
if($action == 'graderallocation'){
  $obj = new local_assign\locallib();

  $rsStudentRecordArray = $obj->ajax_data($requestData,null);
  $rsStudentRecordCommentObj = clone (object)$rsStudentRecordArray;
  $rsStudentRecordArray = (array)$rsStudentRecordArray;
  $courseIdArray = [];
  $courseModulesIdArray = [];

  $total_responses = $obj->ajax_data($requestData,1);

    $i=1;
    $coursecontext = "";
    $coursecontextsecond = "";
    $en = "";
    if(count((array)$rsStudentRecordArray)>0){
        $rowCount = 1;
        foreach($rsStudentRecordArray  as $rsStudentRecord){
            
            //
            $row = array();
            $coursecontext = context_course::instance($rsStudentRecord->course_id);
            if(is_enrolled($coursecontext, $rsStudentRecord->id, '', false)){ 
                //coment area
                $courseIdArray[$rsStudentRecord->course_id] = $rsStudentRecord->course_name;
                $avatar = new user_picture($rsStudentRecord);
                $avatar->courseid = $rsStudentRecord->course_id;
                $avatar->link = true;
                $avtar = $OUTPUT->render($avatar);
                $name = $rsStudentRecord->firstname." ".$rsStudentRecord->lastname;
                $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentRecord->id,'course'=>$rsStudentRecord->course_id));
                // row 1
                $row[] = $avtar.''.html_writer::link($profileurl, $name);
                //row2    
                $row[] = $rsStudentRecord->institution;
                //row3
                $row[] = $OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecord->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecord->status));
                //row4
                if($rsStudentRecord->course_visible){
                    $row[] = format_string($rsStudentRecord->course_name);
                }else{
                     // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                    $row[] = "<span style='text-decoration: none;'>".format_string($rsStudentRecord->course_name)."</span>";
                    // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                }
                //row5
                //pre- Added for applying class disable link if grader is admin
                $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                $dropdownArray = $obj->grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);
                //print_object($dropdownArray);die;
                //pre-  Added for applying class disable link if grader is admin
                if($rsStudentRecord->course_modules_id != ''){
                    //$context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_r($context);
                    $params = array('id' => $rsStudentRecord->course_modules_id,'action'=>'grade','userid'=>$rsStudentRecord->userid,'rownum'=>0);
                    $gradetUrl = new moodle_url("/mod/assign/view.php", $params);
                    if(trim($dropdownArray[1]) == 'Administrator'){
                        $class = 'enable_link';
                        $gradetUrl = $gradetUrl;
                    }else{ 
                        $disable = 'disabled';
                        $gradetUrl = "#";
                    }    
                    $row[] = "<a style='padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;' id='button_".$rowCount."' class='".$class."' href=".$gradetUrl." ".$disable.">Grade</a>";

                }else{
                   $row[] = "Submission not came";
                } 
                //row 6
                $row[] = userdate($rsStudentRecord->timesubmitted);
                //row 7hidden td
                $row[] = $rsStudentRecord->timesubmitted;
                // row 8         
                if($rsStudentRecord->course_modules_id != ''){   
                    $component = 'assignsubmission_file';
                    $filearea = 'submission_files';
                    //$submissionid = $rsStudentRecord->submissionid;
                    //$submissionid = 4090; //104
                    //if($rsStudentRecord->status == 'reopened'){
                    $sqlAssigSubQuery = "SELECT id,status, timemodified FROM {assign_submission} WHERE assignment = ".$rsStudentRecord->assignmentid." AND userid = ".$rsStudentRecord->userid." AND status='submitted' ORDER BY timemodified DESC LIMIT 0,1";
                    //echo $sqlAssigSubQuery;
                    $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery);
                    //print_r($sqlAssigSubData);
                    // this is other assign submission id that is comming from main query
                    $submissionid = $sqlAssigSubData->id;
                    $context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_object($rsStudentRecord);
                    //echo 's';
                    $fs = get_file_storage();
                    $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);
                    if($dir['files']){
                        foreach ($dir['files'] as $file) {
                            $file->portfoliobutton = '';
                            $filename = $file->get_filename();
                            if($filename == '.'){
                               continue;
                            }
                            $url = "";
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            //print_object($file);
                            if($ext == 'pdf'){ 
                                $url = $OUTPUT->pix_url('pdfsmall', 'theme');    
                            }else if($ext == 'doc' || $ext == 'docx'){ 
                                $url = $OUTPUT->pix_url('word', 'theme');
                                        
                            }else if($ext == 'xlsx' || $ext == 'csv'){ 
                                $url = $OUTPUT->pix_url('excel', 'theme');
                                        
                            }else if($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){ 
                                $url = $OUTPUT->pix_url('image', 'theme');
                                       
                            }else if( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
                                $url = $OUTPUT->pix_url('otherfile', 'theme');   
                            }  
                            $image =  "<img class='icon' alt='".$filename."' title='".$filename."' src=".$url.">";
                            $path = '/' .$context->id .'/' .$component .'/' .$filearea .
                                        '/' .$file->get_itemid() .$file->get_filepath() .$file->get_filename();
                            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
                            $filename = $file->get_filename();
                            //print_r($filename);
                            if(is_null($filename)){
                               $row[] = '&nbsp;';
                            }else{
                               $row[] = $image.''.html_writer::link($url, substr($filename,0,10).'...',array('title' => $filename)).''."<br/>";
                            }
                        }
                        unset($dir);
                        //$row [] = 'a';
                    }else{
                        $row[] = '&nbsp;';
                    }
                    
                }else{
                    $row[] = "File not found";
                }
                //row 9
                $row[] = $dropdownArray[0];
                //row 10
                //$row[] = $dropdownArray[1];
                
            }
            $data[] = $row;
            //$cdata[] = $row;
            $i++;
        } //close studnet sub loop
        // start comment loop
        $rsStudentRecordCommentArray = (array)$rsStudentRecordCommentObj;
        foreach($rsStudentRecordCommentArray as $rsStudentCommentRecord){
            $row1 = array();
            $coursecontextsecond = context_course::instance($rsStudentCommentRecord->course_id);
            if(is_enrolled($coursecontextsecond, $rsStudentCommentRecord->userid, '', false)){
                $context = context_module::instance($rsStudentCommentRecord->course_modules_id);
                $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE mc.userid=".$rsStudentCommentRecord->userid." AND mc.contextid=".$context->id." ORDER BY mc.timecreated DESC LIMIT 0,1";
                $rsComments = $DB->get_record_sql($sqlAssignSubmission);
                if($rsComments->id > 0){
                    //comment rows started
                    $avatar = new user_picture($rsStudentCommentRecord);
                    $avatar->courseid = $rsStudentCommentRecord->course_id;
                    $avatar->link = true;
                    $avtar =  $OUTPUT->render($avatar);
                    
                    $name = $rsStudentCommentRecord->firstname." ".$rsStudentCommentRecord->lastname;
                    $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentCommentRecord->id,'course'=>$rsStudentCommentRecord->course_id)); // $COURSE->id
                    //row1
                    $row1[] =  $avtar." ".html_writer::link($profileurl, $name);
                    //row2
                    $row1[] = $rsStudentCommentRecord->institution;
                    //row 3
                    $row1[] = "New Comment";
                    //row 4
                    if($rsStudentCommentRecord->course_visible){
                        $row1[] = format_string($rsStudentCommentRecord->course_name);
                    }else{
                        // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                        $row1[] = "<span style='text-decoration: none;'>".format_string($rsStudentCommentRecord->course_name)."</span>";
                        // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                    }
                    //row 5
                    $params = array('id' => $rsStudentCommentRecord->course_modules_id,'userid'=>$rsStudentCommentRecord->userid,'action'=>'comment','sesskey'=>'','page'=>'0');
                    $commentUrl = new moodle_url("/local/assign/comment.php", $params);
                    $row1[] = '<a style="padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;" href="'.$commentUrl.'">View</a>';
                    //row 6
                    $row1[] = userdate($rsComments->timecreated);
                    //for 7 hidden td
                    $row1[] = $rsComments->timecreated;
                    //row 8
                    $row1[] = '&nbsp;';
                    // row 9
                    $row1[] = '&nbsp;';
                    //  row10
                    //$row1[] = '&nbsp;';
                                    
                }else{
                    continue;
                }
            }
           $data[] = $row1;
        }
    }else{
        echo "<h4> No Notification </h4>";
    }
    $finaldata = $data;
    $finaldata = array_filter($finaldata);
    if(count($finaldata) > $requestData['length'] && $requestData['length'] !=-1){
        //echo $requestData['length'];
        //echo 'ddddddd';
        $val = count($finaldata) - $requestData['length'];
        array_splice($finaldata,$requestData['length']);
    }
    //if($requestData['length'] !=-1){
        usort($finaldata, function ($a, $b) {
         return $b[6] - $a[6];
        });
    //}
   
    //print_object($finaldata);
    //die;
    if($requestData['length'] !=-1){
        $iTotal = $total_responses;
    }else{
        
        $iTotal = count($finaldata);
    }
//$iTotal = $total_responses;
$iFilteredTotal = $iTotal;
$output = array(
    "sEcho" => intval($requestData['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => $finaldata
  );
  /*$output = array(
    "draw" => 8,
    "recordsTotal" => $iTotal,
    "recordsFiltered" => $iFilteredTotal,
    "data" => $finaldata
  );*/

  echo json_encode($output);
}
//
if($action == 'ajaxgraderallocation'){
    
    $filterdata = json_decode($_REQUEST['module']);
    //print_r($filterdata->submission->day);die;
    $obj = new local_assign\locallib();

    $rsStudentRecordArray = $obj->filter_ajax_data($requestData,null,$filterdata);
    $rsStudentRecordCommentObj = clone (object)$rsStudentRecordArray;
    $rsStudentRecordArray = (array)$rsStudentRecordArray;
    $courseIdArray = [];
    $courseModulesIdArray = [];
     //print_object($rsStudentRecordArray);die;
    $total_responses = $obj->filter_ajax_data($requestData,1,$filterdata);

    $i=1;
    $coursecontext = "";
    $coursecontextsecond = "";
    $en = "";
    if(count((array)$rsStudentRecordArray)>0){
        $rowCount = 1;
        foreach($rsStudentRecordArray  as $rsStudentRecord){
            
            //
            $row = array();
            $coursecontext = context_course::instance($rsStudentRecord->course_id);
            if(is_enrolled($coursecontext, $rsStudentRecord->id, '', false)){ 
                //coment area
                $courseIdArray[$rsStudentRecord->course_id] = $rsStudentRecord->course_name;
                $avatar = new user_picture($rsStudentRecord);
                $avatar->courseid = $rsStudentRecord->course_id;
                $avatar->link = true;
                $avtar = $OUTPUT->render($avatar);
                $name = $rsStudentRecord->firstname." ".$rsStudentRecord->lastname;
                $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentRecord->id,'course'=>$rsStudentRecord->course_id));
                // row 1
                $row[] = $avtar.''.html_writer::link($profileurl, $name);
                //row2    
                $row[] = $rsStudentRecord->institution;
                //row3
                $row[] = $OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecord->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecord->status));
                //row4
                if($rsStudentRecord->course_visible){
                    $row[] = format_string($rsStudentRecord->course_name);
                }else{
                     // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                    $row[] = "<span style='text-decoration: none;'>".format_string($rsStudentRecord->course_name)."</span>";
                    // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                }
                //skip the loop
                if(is_siteadmin($filterdata->grader)){
                    $gQuery = "SELECT grader_id FROM {assign_graders} WHERE exam_id = $rsStudentRecord->course_modules_id AND student_id = $rsStudentRecord->userid";
                    $graderObjData = $DB->get_record_sql($gQuery);
                    if($graderObjData){
                        continue;
                    }
                }
                //row5
                //pre- Added for applying class disable link if grader is admin
                $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                $dropdownArray = $obj->grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);
                //print_object($dropdownArray);die;
                //pre-  Added for applying class disable link if grader is admin
                if($rsStudentRecord->course_modules_id != ''){
                    //$context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_r($context);
                    $params = array('id' => $rsStudentRecord->course_modules_id,'action'=>'grade','userid'=>$rsStudentRecord->userid,'rownum'=>0);
                    $gradetUrl = new moodle_url("/mod/assign/view.php", $params);
                    if(trim($dropdownArray[1]) == 'Administrator'  && is_siteadmin($USER)){
                        $class = 'enable_link';
                        $gradetUrl = $gradetUrl;
                    }else{ 
                        $disable = 'disabled';
                        $gradetUrl = "#";
                    }    
                    $row[] = "<a style='padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;' id='button_".$rowCount."' class='".$class."' href=".$gradetUrl." ".$disable.">Grade</a>";

                }else{
                   $row[] = "Submission not came";
                } 
                //row 6
                $row[] = userdate($rsStudentRecord->timesubmitted);
                //row 7hidden td
                $row[] = $rsStudentRecord->timesubmitted;
                // row 8         
                if($rsStudentRecord->course_modules_id != ''){   
                    $component = 'assignsubmission_file';
                    $filearea = 'submission_files';
                    //$submissionid = $rsStudentRecord->submissionid;
                    //$submissionid = 4090; //104
                    //if($rsStudentRecord->status == 'reopened'){
                    $sqlAssigSubQuery = "SELECT id,status, timemodified FROM {assign_submission} WHERE assignment = ".$rsStudentRecord->assignmentid." AND userid = ".$rsStudentRecord->userid." AND status='submitted' ORDER BY timemodified DESC LIMIT 0,1";
                    //echo $sqlAssigSubQuery;
                    $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery);
                    //print_r($sqlAssigSubData);
                    // this is other assign submission id that is comming from main query
                    $submissionid = $sqlAssigSubData->id;
                    $context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_object($rsStudentRecord);
                    //echo 's';
                    $fs = get_file_storage();
                    $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);
                    if($dir['files']){
                        foreach ($dir['files'] as $file) {
                            $file->portfoliobutton = '';
                            $filename = $file->get_filename();
                            if($filename == '.'){
                               continue;
                            }
                            $url = "";
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            //print_object($file);
                            if($ext == 'pdf'){ 
                                $url = $OUTPUT->pix_url('pdfsmall', 'theme');    
                            }else if($ext == 'doc' || $ext == 'docx'){ 
                                $url = $OUTPUT->pix_url('word', 'theme');
                                        
                            }else if($ext == 'xlsx' || $ext == 'csv'){ 
                                $url = $OUTPUT->pix_url('excel', 'theme');
                                        
                            }else if($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){ 
                                $url = $OUTPUT->pix_url('image', 'theme');
                                       
                            }else if( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
                                $url = $OUTPUT->pix_url('otherfile', 'theme');   
                            }  
                            $image =  "<img class='icon' alt='".$filename."' title='".$filename."' src=".$url.">";
                            $path = '/' .$context->id .'/' .$component .'/' .$filearea .
                                        '/' .$file->get_itemid() .$file->get_filepath() .$file->get_filename();
                            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
                            $filename = $file->get_filename();
                            //print_r($filename);
                            if(is_null($filename)){
                               $row[] = '&nbsp;';
                            }else{
                               $row[] = $image.''.html_writer::link($url, substr($filename,0,10).'...',array('title' => $filename)).''."<br/>";
                            }
                        }
                        unset($dir);
                        //$row [] = 'a';
                    }else{
                        $row[] = '&nbsp;';
                    }
                    
                }else{
                    $row[] = "File not found";
                }
                //row 9
                $row[] = $dropdownArray[0];
                //row 10
                //$row[] = $dropdownArray[1];
                
            }
            $data[] = $row;
            //$cdata[] = $row;
            $i++;
        } //close studnet sub loop
        // start comment loop
        $rsStudentRecordCommentArray = (array)$rsStudentRecordCommentObj;
        foreach($rsStudentRecordCommentArray as $rsStudentCommentRecord){
            $row1 = array();
            $coursecontextsecond = context_course::instance($rsStudentCommentRecord->course_id);
            if(is_enrolled($coursecontextsecond, $rsStudentCommentRecord->userid, '', false)){
                $context = context_module::instance($rsStudentCommentRecord->course_modules_id);
                $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE mc.userid=".$rsStudentCommentRecord->userid." AND mc.contextid=".$context->id." ORDER BY mc.timecreated DESC LIMIT 0,1";
                $rsComments = $DB->get_record_sql($sqlAssignSubmission);
                if($rsComments->id > 0){
                    //comment rows started
                    $avatar = new user_picture($rsStudentCommentRecord);
                    $avatar->courseid = $rsStudentCommentRecord->course_id;
                    $avatar->link = true;
                    $avtar =  $OUTPUT->render($avatar);
                    
                    $name = $rsStudentCommentRecord->firstname." ".$rsStudentCommentRecord->lastname;
                    $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentCommentRecord->id,'course'=>$rsStudentCommentRecord->course_id)); // $COURSE->id
                    //row1
                    $row1[] =  $avtar." ".html_writer::link($profileurl, $name);
                    //row2
                    $row1[] = $rsStudentCommentRecord->institution;
                    //row 3
                    $row1[] = "New Comment";
                    //row 4
                    if($rsStudentCommentRecord->course_visible){
                        $row1[] = format_string($rsStudentCommentRecord->course_name);
                    }else{
                        // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                        $row1[] = "<span style='text-decoration: none;'>".format_string($rsStudentCommentRecord->course_name)."</span>";
                        // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                    }
                    if(is_siteadmin($filterdata->grader)){
                        $gQuery = "SELECT grader_id FROM {assign_graders} WHERE exam_id = $rsStudentCommentRecord->course_modules_id AND student_id = $rsStudentCommentRecord->userid";
                        $graderObjData = $DB->get_record_sql($gQuery);
                        if($graderObjData){
                            continue;
                        }
                    }
                    //row 5
                    $params = array('id' => $rsStudentCommentRecord->course_modules_id,'userid'=>$rsStudentCommentRecord->userid,'action'=>'comment','sesskey'=>'','page'=>'0');
                    $commentUrl = new moodle_url("/local/assign/comment.php", $params);
                    $row1[] = '<a style="padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;" href="'.$commentUrl.'">View</a>';
                    //row 6
                    $row1[] = userdate($rsComments->timecreated);
                    //for 7 hidden td
                    $row1[] = $rsComments->timecreated;
                    //row 8
                    $row1[] = '&nbsp;';
                    // row 9
                    $row1[] = '&nbsp;';
                    //  row10
                    //$row1[] = '&nbsp;';
                                    
                }else{
                    continue;
                }
            }
           $data[] = $row1;
        }

    }else{
        echo json_encode(array('msg'=>'No Recordsfound'));
    }
    $finaldata = $data;
    $finaldata = array_filter($finaldata);
    if(count($finaldata) > $requestData['length'] && $requestData['length'] !=-1){
        //echo $requestData['length'];
        //echo 'ddddddd';
        $val = count($finaldata) - $requestData['length'];
        array_splice($finaldata,$requestData['length']);
    }
    $order = ucwords($requestData["order"][0]["dir"]);
    if(count($finaldata) < $requestData['length'] && $order == 'Asc'){

        usort($finaldata, function ($a, $b) {
          return ($b[6] <= $a[6]) ? 1 : -1;
         //return $b[6] - $a[6];
        });
    }else{
        usort($finaldata, function ($a, $b) {
          //return ($b[6] <= $a[6]) ? 1 : -1;
         return $b[6] - $a[6];
        });
    }
   
    //print_object($finaldata);
    //die;
    if($requestData['length'] !=-1){
        $iTotal = $total_responses;
    }else{
        
        $iTotal = count($finaldata);
    }
    //$iTotal = $total_responses;
    $iFilteredTotal = $iTotal;
    $output = array(
        "sEcho" => intval($requestData['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => $finaldata
    );
 
    echo json_encode($output);
   
}
//ajax ends

// Grder role view
if($action == 'graderview' && $action != 'graderallocation'){
  $obj = new local_assign\locallib();
  
  $rsStudentRecordArray = $obj->grader_ajax_data($requestData,null);
  $rsStudentRecordCommentObj = clone (object)$rsStudentRecordArray;
  $rsStudentRecordArray = (array)$rsStudentRecordArray;
  $courseIdArray = [];
  $courseModulesIdArray = [];
//print_r($rsStudentRecordArray);die;
  $total_responses = $obj->grader_ajax_data($requestData,1);
//print_r($action);die;
    $i=1;
    $coursecontext = "";
    $coursecontextsecond = "";
    $en = "";
    if(count((array)$rsStudentRecordArray)>0){
        $rowCount = 1;
        foreach($rsStudentRecordArray  as $rsStudentRecord){
            
            //
            $row = array();
            $coursecontext = context_course::instance($rsStudentRecord->course_id);
            if(is_enrolled($coursecontext, $rsStudentRecord->id, '', false)){ 
                //coment area
                $courseIdArray[$rsStudentRecord->course_id] = $rsStudentRecord->course_name;
                $avatar = new user_picture($rsStudentRecord);
                $avatar->courseid = $rsStudentRecord->course_id;
                $avatar->link = true;
                $avtar = $OUTPUT->render($avatar);
                $name = $rsStudentRecord->firstname." ".$rsStudentRecord->lastname;
                $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentRecord->id,'course'=>$rsStudentRecord->course_id));
                // row 1
                $row[] = $avtar.''.html_writer::link($profileurl, $name);
                //row2    
                $row[] = $rsStudentRecord->institution;
                //row3
                $row[] = $OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecord->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecord->status));
                //row4
                if($rsStudentRecord->course_visible){
                    $row[] = format_string($rsStudentRecord->course_name);
                }else{
                     // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                    $row[] = "<span style='text-decoration: none;'>".format_string($rsStudentRecord->course_name)."</span>";
                    // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                }
                //row5
                //pre- Added for applying class disable link if grader is admin
                $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                $dropdownArray = $obj->grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);
                //print_object($dropdownArray);die;
                //pre-  Added for applying class disable link if grader is admin
                if($rsStudentRecord->course_modules_id != ''){
                    //$context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_r($context);
                    $params = array('id' => $rsStudentRecord->course_modules_id,'action'=>'grade','userid'=>$rsStudentRecord->userid,'rownum'=>0);
                    $gradetUrl = new moodle_url("/mod/assign/view.php", $params);
                    
                        $class = 'enable_link';
                        $gradetUrl = $gradetUrl;
                       
                    $row[] = "<a style='padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;' id='button_".$rowCount."' class='".$class."' href=".$gradetUrl." ".$disable.">Grade</a>";

                }else{
                   $row[] = "Submission not came";
                } 
                //row 6
                $row[] = userdate($rsStudentRecord->timesubmitted);
                //row 7hidden td
                $row[] = $rsStudentRecord->timesubmitted;
                // row 8         
                if($rsStudentRecord->course_modules_id != ''){   
                    $component = 'assignsubmission_file';
                    $filearea = 'submission_files';
                    //$submissionid = $rsStudentRecord->submissionid;
                    //$submissionid = 4090; //104
                    //if($rsStudentRecord->status == 'reopened'){
                    $sqlAssigSubQuery = "SELECT id,status, timemodified FROM {assign_submission} WHERE assignment = ".$rsStudentRecord->assignmentid." AND userid = ".$rsStudentRecord->userid." AND status='submitted' ORDER BY timemodified DESC LIMIT 0,1";
                    //echo $sqlAssigSubQuery;
                    $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery);
                    //print_r($sqlAssigSubData);
                    // this is other assign submission id that is comming from main query
                    $submissionid = $sqlAssigSubData->id;
                    $context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_object($rsStudentRecord);
                    //echo 's';
                    $fs = get_file_storage();
                    $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);
                    if($dir['files']){
                        foreach ($dir['files'] as $file) {
                            $file->portfoliobutton = '';
                            $filename = $file->get_filename();
                            if($filename == '.'){
                               continue;
                            }
                            $url = "";
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            //print_object($file);
                            if($ext == 'pdf'){ 
                                $url = $OUTPUT->pix_url('pdfsmall', 'theme');    
                            }else if($ext == 'doc' || $ext == 'docx'){ 
                                $url = $OUTPUT->pix_url('word', 'theme');
                                        
                            }else if($ext == 'xlsx' || $ext == 'csv'){ 
                                $url = $OUTPUT->pix_url('excel', 'theme');
                                        
                            }else if($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){ 
                                $url = $OUTPUT->pix_url('image', 'theme');
                                       
                            }else if( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
                                $url = $OUTPUT->pix_url('otherfile', 'theme');   
                            }  
                            $image =  "<img class='icon' alt='".$filename."' title='".$filename."' src=".$url.">";
                            $path = '/' .$context->id .'/' .$component .'/' .$filearea .
                                        '/' .$file->get_itemid() .$file->get_filepath() .$file->get_filename();
                            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
                            $filename = $file->get_filename();
                            //print_r($filename);
                            if(is_null($filename)){
                               $row[] = '&nbsp;';
                            }else{
                               $row[] = $image.''.html_writer::link($url, substr($filename,0,10).'...',array('title' => $filename)).''."<br/>";
                            }
                        }
                        unset($dir);
                        //$row [] = 'a';
                    }else{
                        $row[] = '&nbsp;';
                    }
                    
                }else{
                    $row[] = "File not found";
                }
                //row 9
                $row[] = $dropdownArray[0];
                //row 10
                //$row[] = $dropdownArray[1];
                
            }
            $data[] = $row;
            //$cdata[] = $row;
            $i++;
        } //close studnet sub loop
        // start comment loop
        $rsStudentRecordCommentArray = (array)$rsStudentRecordCommentObj;
        foreach($rsStudentRecordCommentArray as $rsStudentCommentRecord){
            $row1 = array();
            $coursecontextsecond = context_course::instance($rsStudentCommentRecord->course_id);
            if(is_enrolled($coursecontextsecond, $rsStudentCommentRecord->userid, '', false)){
                $context = context_module::instance($rsStudentCommentRecord->course_modules_id);
                $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE mc.userid=".$rsStudentCommentRecord->userid." AND mc.contextid=".$context->id." ORDER BY mc.timecreated DESC LIMIT 0,1";
                $rsComments = $DB->get_record_sql($sqlAssignSubmission);
                if($rsComments->id > 0){
                    //comment rows started
                    $avatar = new user_picture($rsStudentCommentRecord);
                    $avatar->courseid = $rsStudentCommentRecord->course_id;
                    $avatar->link = true;
                    $avtar =  $OUTPUT->render($avatar);
                    
                    $name = $rsStudentCommentRecord->firstname." ".$rsStudentCommentRecord->lastname;
                    $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentCommentRecord->id,'course'=>$rsStudentCommentRecord->course_id)); // $COURSE->id
                    //row1
                    $row1[] =  $avtar." ".html_writer::link($profileurl, $name);
                    //row2
                    $row1[] = $rsStudentCommentRecord->institution;
                    //row 3
                    $row1[] = "New Comment";
                    //row 4
                    if($rsStudentCommentRecord->course_visible){
                        $row1[] = format_string($rsStudentCommentRecord->course_name);
                    }else{
                        // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                        $row1[] = "<span style='text-decoration: none;'>".format_string($rsStudentCommentRecord->course_name)."</span>";
                        // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                    }
                    //row 5
                    $params = array('id' => $rsStudentCommentRecord->course_modules_id,'userid'=>$rsStudentCommentRecord->userid,'action'=>'comment','sesskey'=>'','page'=>'0');
                    $commentUrl = new moodle_url("/local/assign/comment.php", $params);
                    $row1[] = '<a style="padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;" href="'.$commentUrl.'">View</a>';
                    //row 6
                    $row1[] = userdate($rsComments->timecreated);
                    //for 7 hidden td
                    $row1[] = $rsComments->timecreated;
                    //row 8
                    $row1[] = '&nbsp;';
                    // row 9
                    $row1[] = '&nbsp;';
                    //  row10
                    //$row1[] = '&nbsp;';
                                    
                }else{
                    continue;
                }
            }
           $data[] = $row1;
        }
    }else{
        echo "<h4> No Notification </h4>";
    }
    $finaldata = $data;
    $finaldata = array_filter($finaldata);
    if(count($finaldata) > $requestData['length'] && $requestData['length'] !=-1){
        //echo $requestData['length'];
        //echo 'ddddddd';
        $val = count($finaldata) - $requestData['length'];
        array_splice($finaldata,$requestData['length']);
    }
    $order = ucwords($requestData["order"][0]["dir"]);
    if(count($finaldata) < $requestData['length'] && $order == 'Asc'){

        usort($finaldata, function ($a, $b) {
          return ($b[6] <= $a[6]) ? 1 : -1;
         //return $b[6] - $a[6];
        });
    }else{
        usort($finaldata, function ($a, $b) {
          //return ($b[6] <= $a[6]) ? 1 : -1;
         return $b[6] - $a[6];
        });
    }
   
    //print_object($finaldata);
    //die;
    if($requestData['length'] !=-1){
        $iTotal = $total_responses;
    }else{
        
        $iTotal = count($finaldata);
    }
//$iTotal = $total_responses;
$iFilteredTotal = $iTotal;
$output = array(
    "sEcho" => intval($requestData['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => $finaldata
  );
  /*$output = array(
    "draw" => 8,
    "recordsTotal" => $iTotal,
    "recordsFiltered" => $iFilteredTotal,
    "data" => $finaldata
  );*/

  echo json_encode($output);
 
 
}
//ends
if($action == 'ajaxgraderview'){
    
    $filterdata = json_decode($_REQUEST['module']);
    //print_r($filterdata->submission->day);die;
    $obj = new local_assign\locallib();

    $rsStudentRecordArray = $obj->grader_filter_ajax_data($requestData,null,$filterdata);
    $rsStudentRecordCommentObj = clone (object)$rsStudentRecordArray;
    $rsStudentRecordArray = (array)$rsStudentRecordArray;
    $courseIdArray = [];
    $courseModulesIdArray = [];

    $total_responses = $obj->grader_filter_ajax_data($requestData,1,$filterdata);

    $i=1;
    $coursecontext = "";
    $coursecontextsecond = "";
    $en = "";
    if(count((array)$rsStudentRecordArray)>0){
        $rowCount = 1;
        foreach($rsStudentRecordArray  as $rsStudentRecord){
            
            //
            $row = array();
            $coursecontext = context_course::instance($rsStudentRecord->course_id);
            if(is_enrolled($coursecontext, $rsStudentRecord->id, '', false)){ 
                //coment area
                $courseIdArray[$rsStudentRecord->course_id] = $rsStudentRecord->course_name;
                $avatar = new user_picture($rsStudentRecord);
                $avatar->courseid = $rsStudentRecord->course_id;
                $avatar->link = true;
                $avtar = $OUTPUT->render($avatar);
                $name = $rsStudentRecord->firstname." ".$rsStudentRecord->lastname;
                $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentRecord->id,'course'=>$rsStudentRecord->course_id));
                // row 1
                $row[] = $avtar.''.html_writer::link($profileurl, $name);
                //row2    
                $row[] = $rsStudentRecord->institution;
                //row3
                $row[] = $OUTPUT->container(get_string('submissionstatus_' . $rsStudentRecord->status, 'assign'),array('class'=>'submissionstatus' .$rsStudentRecord->status));
                //row4
                if($rsStudentRecord->course_visible){
                    $row[] = format_string($rsStudentRecord->course_name);
                }else{
                     // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                    $row[] = "<span style='text-decoration: none;'>".format_string($rsStudentRecord->course_name)."</span>";
                    // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                }
                //row5
                //pre- Added for applying class disable link if grader is admin
                $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                $dropdownArray = $obj->grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);
                //print_object($dropdownArray);die;
                //pre-  Added for applying class disable link if grader is admin
                if($rsStudentRecord->course_modules_id != ''){
                    //$context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_r($context);
                    $params = array('id' => $rsStudentRecord->course_modules_id,'action'=>'grade','userid'=>$rsStudentRecord->userid,'rownum'=>0);
                    $gradetUrl = new moodle_url("/mod/assign/view.php", $params);
                    
                        $class = 'enable_link';
                        $gradetUrl = $gradetUrl;
                       
                    $row[] = "<a style='padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;' id='button_".$rowCount."' class='".$class."' href=".$gradetUrl." ".$disable.">Grade</a>";

                }else{
                   $row[] = "Submission not came";
                } 
                //row 6
                $row[] = userdate($rsStudentRecord->timesubmitted);
                //row 7hidden td
                $row[] = $rsStudentRecord->timesubmitted;
                // row 8         
                if($rsStudentRecord->course_modules_id != ''){   
                    $component = 'assignsubmission_file';
                    $filearea = 'submission_files';
                    //$submissionid = $rsStudentRecord->submissionid;
                    //$submissionid = 4090; //104
                    //if($rsStudentRecord->status == 'reopened'){
                    $sqlAssigSubQuery = "SELECT id,status, timemodified FROM {assign_submission} WHERE assignment = ".$rsStudentRecord->assignmentid." AND userid = ".$rsStudentRecord->userid." AND status='submitted' ORDER BY timemodified DESC LIMIT 0,1";
                    //echo $sqlAssigSubQuery;
                    $sqlAssigSubData = $DB->get_record_sql($sqlAssigSubQuery);
                    //print_r($sqlAssigSubData);
                    // this is other assign submission id that is comming from main query
                    $submissionid = $sqlAssigSubData->id;
                    $context = context_module::instance($rsStudentRecord->course_modules_id);
                    //print_object($rsStudentRecord);
                    //echo 's';
                    $fs = get_file_storage();
                    $dir = $fs->get_area_tree($context->id, $component, $filearea, $submissionid);
                    if($dir['files']){
                        foreach ($dir['files'] as $file) {
                            $file->portfoliobutton = '';
                            $filename = $file->get_filename();
                            if($filename == '.'){
                               continue;
                            }
                            $url = "";
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            //print_object($file);
                            if($ext == 'pdf'){ 
                                $url = $OUTPUT->pix_url('pdfsmall', 'theme');    
                            }else if($ext == 'doc' || $ext == 'docx'){ 
                                $url = $OUTPUT->pix_url('word', 'theme');
                                        
                            }else if($ext == 'xlsx' || $ext == 'csv'){ 
                                $url = $OUTPUT->pix_url('excel', 'theme');
                                        
                            }else if($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg'){ 
                                $url = $OUTPUT->pix_url('image', 'theme');
                                       
                            }else if( $ext == 'txt' || $ext == 'sys' || $ext == 'qsys' || $ext == 'php'|| $ext == 'js'){
                                $url = $OUTPUT->pix_url('otherfile', 'theme');   
                            }  
                            $image =  "<img class='icon' alt='".$filename."' title='".$filename."' src=".$url.">";
                            $path = '/' .$context->id .'/' .$component .'/' .$filearea .
                                        '/' .$file->get_itemid() .$file->get_filepath() .$file->get_filename();
                            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
                            $filename = $file->get_filename();
                            //print_r($filename);
                            if(is_null($filename)){
                               $row[] = '&nbsp;';
                            }else{
                               $row[] = $image.''.html_writer::link($url, substr($filename,0,10).'...',array('title' => $filename)).''."<br/>";
                            }
                        }
                        unset($dir);
                        //$row [] = 'a';
                    }else{
                        $row[] = '&nbsp;';
                    }
                    
                }else{
                    $row[] = "File not found";
                }
                //row 9
                $row[] = $dropdownArray[0];
                //row 10
                //$row[] = $dropdownArray[1];
                
            }
            $data[] = $row;
            //$cdata[] = $row;
            $i++;
        } //close studnet sub loop
        // start comment loop
        $rsStudentRecordCommentArray = (array)$rsStudentRecordCommentObj;
        foreach($rsStudentRecordCommentArray as $rsStudentCommentRecord){
            $row1 = array();
            $coursecontextsecond = context_course::instance($rsStudentCommentRecord->course_id);
            if(is_enrolled($coursecontextsecond, $rsStudentCommentRecord->userid, '', false)){
                $context = context_module::instance($rsStudentCommentRecord->course_modules_id);
                $sqlAssignSubmission = "SELECT mc.id, mc.contextid, mc.itemid, mc.content, mc.userid, mc.timecreated FROM mdl_comments mc WHERE mc.userid=".$rsStudentCommentRecord->userid." AND mc.contextid=".$context->id." ORDER BY mc.timecreated DESC LIMIT 0,1";
                $rsComments = $DB->get_record_sql($sqlAssignSubmission);
                if($rsComments->id > 0){
                    //comment rows started
                    $avatar = new user_picture($rsStudentCommentRecord);
                    $avatar->courseid = $rsStudentCommentRecord->course_id;
                    $avatar->link = true;
                    $avtar =  $OUTPUT->render($avatar);
                    
                    $name = $rsStudentCommentRecord->firstname." ".$rsStudentCommentRecord->lastname;
                    $profileurl = new moodle_url('/user/view.php', array('id' => $rsStudentCommentRecord->id,'course'=>$rsStudentCommentRecord->course_id)); // $COURSE->id
                    //row1
                    $row1[] =  $avtar." ".html_writer::link($profileurl, $name);
                    //row2
                    $row1[] = $rsStudentCommentRecord->institution;
                    //row 3
                    $row1[] = "New Comment";
                    //row 4
                    if($rsStudentCommentRecord->course_visible){
                        $row1[] = format_string($rsStudentCommentRecord->course_name);
                    }else{
                        // - -  -Start - PrivateCoursePhase2  NAV--- - - - - -
                        $row1[] = "<span style='text-decoration: none;'>".format_string($rsStudentCommentRecord->course_name)."</span>";
                        // - -  -End - PrivateCoursePhase2  NAV--- - - - - -                                    
                    }
                    //row 5
                    $params = array('id' => $rsStudentCommentRecord->course_modules_id,'userid'=>$rsStudentCommentRecord->userid,'action'=>'comment','sesskey'=>'','page'=>'0');
                    $commentUrl = new moodle_url("/local/assign/comment.php", $params);
                    $row1[] = '<a style="padding: 8px 14px; background: #fff; border: 1px solid #ccc; color: #585858; border-radius: 5px;" href="'.$commentUrl.'">View</a>';
                    //row 6
                    $row1[] = userdate($rsComments->timecreated);
                    //for 7 hidden td
                    $row1[] = $rsComments->timecreated;
                    //row 8
                    $row1[] = '&nbsp;';
                    // row 9
                    $row1[] = '&nbsp;';
                    //  row10
                    //$row1[] = '&nbsp;';
                                    
                }else{
                    continue;
                }
            }
           $data[] = $row1;
        }

    }else{
        echo json_encode(array('msg'=>'No Recordsfound'));
    }
    $finaldata = $data;
    $finaldata = array_filter($finaldata);
    if(count($finaldata) > $requestData['length'] && $requestData['length'] !=-1){
        //echo $requestData['length'];
        //echo 'ddddddd';
        $val = count($finaldata) - $requestData['length'];
        array_splice($finaldata,$requestData['length']);
    }
    $order = ucwords($requestData["order"][0]["dir"]);
    if(count($finaldata) < $requestData['length'] && $order == 'Asc'){

        usort($finaldata, function ($a, $b) {
          return ($b[6] <= $a[6]) ? 1 : -1;
         //return $b[6] - $a[6];
        });
    }else{
        usort($finaldata, function ($a, $b) {
          //return ($b[6] <= $a[6]) ? 1 : -1;
         return $b[6] - $a[6];
        });
    }
   
    //print_object($finaldata);
    //die;
    if($requestData['length'] !=-1){
        $iTotal = $total_responses;
    }else{
        
        $iTotal = count($finaldata);
    }
    //$iTotal = $total_responses;
    $iFilteredTotal = $iTotal;
    $output = array(
        "sEcho" => intval($requestData['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => $finaldata
    );
 
    echo json_encode($output);
   
}
//ajax ends
//for grade allocation data storage and send notifications
if($action == 'graderdropdown'){
    //print_r($_REQUEST);die;
    $graderValue    = $_REQUEST['grader_val'];
    $graderValueArr = json_decode($graderValue);
    $grader_id      = $graderValueArr[0];
    $studentid      = $graderValueArr[1];
    $examid         = $graderValueArr[2];
    $flagNetworkError = 0;

    //$select_header = "SELECT * FROM {assign_graders} where exam_id = $examid AND student_id = $studentid";
    $graderPrevObjData = $DB->get_record('assign_graders', ['exam_id'=>$examid,'student_id'=>$studentid]);

    if (!$suser = $DB->get_record('user', array('id'=>$studentid),"id,username,email,firstname,lastname")) {

        $flagNetworkError=1;
    }

    $exam_query = "SELECT cm.id as cmid, c.id, cm.instance, a.course, a.name, c.fullname FROM {course_modules} as cm JOIN {assign} as a ON cm.instance = a.id JOIN {course} as c ON a.course=c.id WHERE cm.id = ?";
    $examExistObjData = $DB->get_record_sql($exam_query, [$examid]);
    $assignmentName = format_string($examExistObjData->name);
    $courseName = format_string($examExistObjData->fullname);

    //Delete into mdl_assign_graders table start
    if($graderPrevObjData){
        if($graderPrevObjData->grader_id != $grader_id && $graderPrevObjData->grader_id!=0){
            //$delete_header = "DELETE FROM `mdl_assign_graders` WHERE exam_id = $examid AND student_id = $studentid";
            //$temp = $DB->execute($delete_header);
            $temp = $DB->delete_records('assign_graders', ['exam_id'=>$examid,'student_id'=>$studentid]);
            //var_dump($temp); die;
            if($temp === true){
                //Insert into mdl_assign_graders table start
//                $insert_header = "INSERT INTO `mdl_assign_graders` (`grader_id`, `exam_id`, `student_id`) VALUES ($grader_id, $examid, $studentid)";
//                $temp1 = $DB->execute($insert_header);
                $graderdata = new stdClass();
                $graderdata->grader_id = $grader_id;
                    $graderdata->exam_id = $examid;
                    $graderdata->student_id = $studentid;
                $temp1 = $DB->insert_record('assign_graders', $graderdata);
            }
        }else{
//             $insert_header = "INSERT INTO `mdl_assign_graders` (`grader_id`, `exam_id`, `student_id`) VALUES ($grader_id, $examid, $studentid)";
//            $temp1 = $DB->execute($insert_header);
            $graderdata = new stdClass();
            $graderdata->grader_id = $grader_id;
            $graderdata->exam_id = $examid;
            $graderdata->student_id = $studentid;
            $temp1 = $DB->insert_record('assign_graders', $graderdata);

        }
    }else{
//        $insert_header = "INSERT INTO `mdl_assign_graders` (`grader_id`, `exam_id`, `student_id`) VALUES ($grader_id, $examid, $studentid)";
//        $temp1 = $DB->execute($insert_header);
        $graderdata = new stdClass();
        $graderdata->grader_id = $grader_id;
        $graderdata->exam_id = $examid;
        $graderdata->student_id = $studentid;
        $temp1 = $DB->insert_record('assign_graders', $graderdata);
    }
    //Delete Mail Sent to Previous Grader (if different grader assigned)

    if($graderPrevObjData){
        if($graderPrevObjData->grader_id != $grader_id && $graderPrevObjData->grader_id!=0){
            //Delete Mail functionality start
            if (!$user = $DB->get_record('user', array('id'=>$graderPrevObjData->grader_id),"id,username,email,firstname,lastname")) {
                //cli_error("Can not find user");
                $flagNetworkError=1;
            }
            $subject = 'Admin removed as a Grader';

            $messagehtml = "Dear $user->firstname,<br><br>
                            Admin removed you as a Grader for a $suser->firstname $suser->lastname to you for $courseName::$assignmentName.
                            <br><br>
                            If you have any issues, questions or concerns, please email <a href='mailto:qsc.training@qsc.com'>qsc.training@qsc.com</a>.<br> <br>
                            Cheers, <br>
                            The QSC Training & Education Team <br>
                            <a href='".$CFG->wwwroot."'>www.training.qsc.com</a>";
            $messagetext = $messagehtml;
            $messagehtml = text_to_html($messagehtml, false, false, true);
            $user->mailformat = 1;  // Always send HTML version as well.
            $from="qsctraining@qscaudio.com";

            if(!email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
                $insert_step4 = "INSERT INTO `log_assign_graders` (`request_step`,`grader_id`,`student_id`,`exam_id`,`request_data`) VALUES ('Step 4','$grader_id','$studentid','$examid','Can not sent email to user from Admin removed as a Grader')";
                $step4 = $DB->execute($insert_step4);
                //cli_error("Can not sent email to user");
                $flagNetworkError=2;
                //echo "error";
            }
        }
    }

    //Insert mail functionality start
    if($grader_id != 0){
        if (!$user = $DB->get_record('user', array('id'=>$grader_id),"id,username,email,firstname,lastname")) {
                //cli_error("Can not find user");
                $flagNetworkError=1;
        }
        try{
            $subject = 'Admin assigned as a Grader';

            $messagehtml = "Dear $user->firstname,<br><br>
        Admin added you as a Grader for a $suser->firstname $suser->lastname to you for $courseName::$assignmentName.
        <br><br>
        So log in to <a href='".$CFG->wwwroot."/login'>www.training.qsc.com</a><br><br>
        If you have any issues, questions or concerns, please email <a href='mailto:qsc.training@qsc.com'>qsc.training@qsc.com</a>.<br> <br>
        Cheers, <br>
        The QSC Training & Education Team <br>
        <a href='".$CFG->wwwroot."'>www.training.qsc.com</a>";
            $messagetext = $messagehtml;
            $messagehtml = text_to_html($messagehtml, false, false, true);
            $user->mailformat = 1;  // Always send HTML version as well.
            $from="qsctraining@qscaudio.com";

            if(!email_to_user($user,$from, $subject, $messagetext, $messagehtml)){
                $insert_step6 = "INSERT INTO `log_assign_graders` (`request_step`,`grader_id`,`student_id`,`exam_id`,`request_data`) VALUES ('Step 6','$grader_id','$studentid','$examid','Can not sent email to user from Admin assigned as a Grader')";
                $step6 = $DB->execute($insert_step6);
                $flagNetworkError=3;
            }
        }catch(exception $e){
            $exception_msg = $e->getMessage();
            $insert_step7 = "INSERT INTO `log_assign_graders` (`request_step`,`grader_id`,`student_id`,`exam_id`,`request_data`) VALUES ('Step 7','$grader_id','$studentid','$examid','$exception_msg')";
            $step7 = $DB->execute($insert_step7);
        }

    }
    //Insert mail functionality end

    //$select_chk = "SELECT * FROM `mdl_assign_graders` where grader_id = $grader_id AND exam_id = $examid AND student_id = $studentid";
    //$graderPrevObjDataChk = $DB->get_record_sql($select_chk);
    $graderPrevObjDataChk = $DB->get_record('assign_graders', ['grader_id'=>$grader_id,'exam_id'=>$examid, 'student_id'=>$studentid]);
    if(empty($graderPrevObjDataChk)){
        $flagNetworkError = 4;
    }
    echo $flagNetworkError;
    die;
//Insert into mdl_assign_graders table ends
}
