<?php
    include_once("grader_id_dropdown.php");

     // - - - - - - - - - - Start -PrivateCoursePhase2  NAV  for private course grader display and hide.
    $userGroupCourseList = $SESSION->userPrivateCourseArr;
    //$DB->set_debug(true);
    if(!empty($userGroupCourseList)){
        $removedPrivateC =implode($userGroupCourseList , ',');
        $privateGroupcSql =$DB->get_records_sql("SELECT  id FROM mdl_course WHERE id NOT IN (".$removedPrivateC.") AND visible = 0"); 
    }else{
       $privateGroupcSql =$DB->get_records_sql("SELECT  id FROM mdl_course WHERE visible = 0"); 
    }
     
    if(($USER->id != $user->id) && ($USER->id == '2')){ 
             
    $sqlUser_AssignGrader_AssignSubmission = "SELECT s.timemodified AS timesubmitted, u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS
    STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
    FROM mdl_user u 
    LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
    LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
    LEFT JOIN mdl_course c ON c.id = cm.course
    LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
    WHERE u.id != 5 AND u.id != 2 AND u.id = $user->id AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new'   GROUP BY c.id";  
    
    //$DB->set_debug(true);
    //$privateGroupcSql =$DB->get_records_sql("SELECT  id FROM mdl_course WHERE id NOT IN (SELECT DISTINCT cgmap.cid FROM mdl_course_group_mapping cgmap JOIN mdl_user_privatecourse  upc ON cgmap.cgid = upc.cgid WHERE upc.userid ='".$USER->id."' AND  cgmap.course_map_flag =0) AND visible = 0");
 
    //$DB->set_debug(false);
    
    //$privateGroupcSql =$DB->get_records_sql("SELECT  mcou.id FROM mdl_course mcou LEFT JOIN (SELECT DISTINCT cgmap.cid as cid FROM mdl_course_group_mapping cgmap JOIN mdl_user_privatecourse  upc ON cgmap.cgid = upc.cgid WHERE upc.userid ='".$USER->id."' AND  cgmap.course_map_flag =0) cgmap ON mcou.id != cgmap.cid WHERE mcou.visible = 0");
    //$DB->set_debug(false);
            // if(!empty($privateGroupcSql)){
            //     $privateCourseArr = array_keys($privateGroupcSql);  
            // }
      
    }else{
        // - - - - - - - - - - End -PrivateCoursePhase2  NAV

        $roleAssignSql = "SELECT id FROM mdl_role_assignments WHERE userid =$user->id AND contextid=1 and roleid=11";
        $roleAssignRs = $DB->get_record_sql($roleAssignSql);
        if(!empty($roleAssignRs))
        {
            // - - - - - - - - - - Start -PrivateCoursePhase2  NAV for private course grader display and hide.
            $pCAnd = "";
            if(!empty($privateGroupcSql)){
                $unmapPCList = array_keys($privateGroupcSql);
                $unmapPC = implode($unmapPCList, ',');
                $pCAnd = "AND c.id NOT IN (".$unmapPC.")";
            }

          $sqlUser_AssignGrader_AssignSubmission = "SELECT s.timemodified AS timesubmitted, u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS
        STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
        FROM mdl_user u 
        LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
        LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
        LEFT JOIN mdl_course c ON c.id = cm.course
        LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
        WHERE u.id != 5 AND u.id != 2 AND u.id = $user->id AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new'  ".$pCAnd."  GROUP BY c.id";
        // - - - - - - - - - - End -PrivateCoursePhase2  NAV 
        }
        else{
          $sqlUser_AssignGrader_AssignSubmission = "SELECT s.timemodified AS timesubmitted, u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.imagealt, u.email, u.id AS userid, s.status AS
        STATUS , s.id AS submissionid, s.timecreated AS firstsubmission, s.attemptnumber AS attemptnumber, s.latest latest_submission, s.assignment AS assignnment_id, cm.id AS course_modules_id, cm.course AS course_id, cm.instance AS assignmentid, c.fullname AS course_name, g.id AS gradeid, g.grade AS grade, g.timemodified AS timemarked, g.timecreated AS firstmarked, g.attemptnumber
        FROM mdl_user u 
        LEFT JOIN mdl_assign_submission s ON u.id = s.userid AND u.deleted != 1
        LEFT JOIN mdl_course_modules cm ON s.assignment = cm.instance AND cm.module =1
        LEFT JOIN mdl_course c ON c.id = cm.course
        LEFT JOIN mdl_assign_grades g ON u.id = g.userid AND g.assignment = cm.instance AND g.attemptnumber = s.attemptnumber
        WHERE u.id != 5 AND u.id != 2 AND u.id = $user->id AND s.status IS NOT NULL AND s.latest =1 AND s.status != 'new' and c.visible = 1 GROUP BY c.id";    
        }
    }

    $rsStudentRecordArray = $DB->get_records_sql($sqlUser_AssignGrader_AssignSubmission);
    $rsStudentRecordCommentObj = clone (object)$rsStudentRecordArray;
    $rsStudentRecordArray = (array)$rsStudentRecordArray;
     // - - - - - - - - - - Start -PrivateCoursePhase2  NAV  for private course grader display and hide.
    // if(!empty($roleAssignRs) && isset($privateCourseArr) && !empty($privateCourseArr) && $USER->id == $user->id)
    // {
    //     $rsStudentRecordArray1 = (array)$rsStudentRecordArray;
    //     foreach ($rsStudentRecordArray1 as $key => $rsStudentData) {

    //         if(in_array($rsStudentData->course_id, $privateCourseArr)){
    //             unset($rsStudentRecordArray1[$key]);
    //         }
    //     }
    //     $rsStudentRecordArray = (array)$rsStudentRecordArray1;
    // }else{
    //     $rsStudentRecordArray = (array)$rsStudentRecordArray;
    // }
    // - - - - - - - - - - End -PrivateCoursePhase2  NAV 
    ?>
<div class="table-view tabel_base_cls">
    <?php
    if(count((array)$rsStudentRecordArray)>0){ ?>
        <table id="graderStatusTable">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Grader</th>
                </tr>
            </thead>
           
                <?php
                    $rowCount = 1;
                    foreach($rsStudentRecordArray  as $rsStudentRecord){ 
                        ?>
                        <tr>
                            <td class="td_course">
                                <?php                       
                                    echo format_string($rsStudentRecord->course_name); ////Fixed bug #2419 by calling format_string function before displaying the course name.
                                ?>
                            </td>
                            <?php
                            if(($USER->usertype == 'mainadmin') && ($user->id != $USER->id)){
                            ?>
                                <!-- Notification area for grader block -->
                                <td class="td_dropdown">
                                    <?php      
                                        $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                                        $dropdownArray = grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);                                    
                                        echo $dropdownArray[0];
                                        ?>
                                </td>
                                <?php
                                    }
                                    else{
                                    ?>
                                <td class="td_dropdown">
                                    <?php      
                                        $contextNew = context_module::instance($rsStudentRecord->course_modules_id);
                                        $dropdownArray = grader_select($rsStudentRecord->userid,$rsStudentRecord->graderId,$rsStudentRecord->course_modules_id,$rowCount,$contextNew->id);                                    
                                        echo $dropdownArray[1];		
                                        ?>
                                </td>
                            <?php
                            }
                            ?>
                        </tr>
                    <?php
                    $rowCount++;
                    }
                    ?>
           
        </table>
        <?php
        }
        else{
            echo '<tr><td colspan="2">Not Found</td></tr>';
        }
        ?>
</div>
<!--  Grader block start -->
<script type='text/javascript'>
    $(document).ready(function(){
        $(".grader_pulldown").change(function() { 
          var end = this.value;
            $.ajax({
                 url: '/local/assign/grader_ajax.php',
                 type: 'post',
                 data: {grader_val: end},
                 success: function(response) {
                     console.log("Response "+response);
                 }
            });
        });
    });

function graderStatusFunction() {
        // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("graderStatusInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("graderStatusTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>

<!--  Grader block end -->
<style>
.table-view {
    margin-bottom: 20px;
}

</style>

