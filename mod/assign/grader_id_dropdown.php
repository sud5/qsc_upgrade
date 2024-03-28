<?php
   
// Start New - dashboard_admin_grader
    function grader_select($user_id,$grader_id,$course_modules_id,$rowCount,$contextid){

    // echo "user_id =".$user_id."-"."grader_id =".$grader_id."-"."course_modules_id =".$course_modules_id."-"."rowCount =".$rowCount;
    // die;
     	global $USER, $DB;
 		  $sqlRole = "SELECT id FROM {role} WHERE shortname='grader'";
 		  $rsRole = $DB->get_record_sql($sqlRole);

      $sqlmdl = 'SELECT * FROM `mdl_context` WHERE `id` ='. (int) $contextid;
      $rsMdl = $DB->get_record_sql($sqlmdl);
      $path = $rsMdl->path;
      $pathCNT = $rsMdl->depth-1;
      $patharray = explode("/",$path);

      $sqlRoleAssignment = "SELECT u.id,u.firstname,u.lastname,u.email,ra.contextid FROM {role_assignments} ra JOIN {user} u ON ra.userid=u.id where ra.contextid=".$patharray[$pathCNT]." and ra.roleid=".$rsRole->id;

	    $graderObjData = $DB->get_records_sql($sqlRoleAssignment);
      //echo "<pre>"; print_r($graderObjData); exit;
      $a = array(0=>"Select");
      $graders = array_merge($a, $graderObjData);
      // return $graders;
      // die;
      // $graders = array(0=>"Select",3333=>$graderObjData[3333]->firstname ." ".$graderObjData[3333]->lastname,2=>"Brenton Melone");
      // echo "<pre>"; print_r($graders); exit;
      $selectcol = "<select name=adminGradingDD_".$rowCount."_".$user_id." id=adminGradingDD_".$rowCount."_".$user_id." class='grader_pulldown'>";

      $selected = "";
      $selectedGrader = "";
      //add admin
      //$selectcol .= "<option value='".2,".$user_id.",".$course_modules_id."' ".$selected.">" . $name . "</option>";


      foreach ($graders as $key=>$values) {
         //if defined $yourselectedGrader
         if($key != 0){
            // $examid = (int) $course_modules_id;
            // $studentid = $user_id;
            $gQuery = "SELECT grader_id FROM {assign_graders} WHERE exam_id = $course_modules_id AND student_id = $user_id";
            $graderObjData = $DB->get_record_sql($gQuery);
                      
                if ($graderObjData->grader_id == $values->id) {
                  //if ($grader_id == $values->id) {
                  $selected = "selected";
                  $selectedGrader = $values->firstname." ".$values->lastname;	
                }else{
                  $selected = "";
                }
             $name = $values->firstname." ".$values->lastname;
             $arr = array((int) $values->id, (int) $user_id, (int) $course_modules_id); // $values->id = Grader id, $user_id = student id
             $val = json_encode($arr);
         }
         else{
              $name = "Administrator";
              $selectedGrader = "Administrator"; 
              $arr = array(2, (int) $user_id, (int) $course_modules_id);
              $val = json_encode($arr);
         }
         $selectcol .= "<option value='".$val."' ".$selected.">" . $name . "</option>";
      }
      $selectcol .= "</select>";
    $arr = array($selectcol,$selectedGrader);
    return $arr;
}
