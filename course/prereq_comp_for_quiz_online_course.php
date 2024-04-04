<?php
//1069 start ALL/ANY
$liCourseList = "<ul style='padding-left:30px;'>";
if(isset($getInCompPreCourseId)){  
  $multiCourseItt = 0;
  foreach ($getInCompPreCourseId as $keyCourseId) {
    $courseObjData = $DB->get_record('course', array('id'=>$keyCourseId),'fullname');

    $courseAssignCNTSQL = 'SELECT count(id) as cntexam FROM {assign} WHERE course =' . $keyCourseId;
    $courseAssignCNTRes = $DB->get_record_sql($courseAssignCNTSQL);
        
    $courseAssignSQL = 'SELECT id FROM {assign} WHERE course =' . $keyCourseId . ' and type ="classroom"';
    $courseAssignRes = $DB->get_record_sql($courseAssignSQL);

    if($multiCourseItt > 0)
      $multiCourseName .= ', ';

    if (!empty($courseAssignRes) && $courseAssignCNTRes->cntexam == 1) {
      $courseF2FSQL = 'SELECT id FROM {facetoface} WHERE course =' . $keyCourseId;
      $courseF2FRes = $DB->get_record_sql($courseF2FSQL);
      $liCourseList .= '<li><a href="/mod/facetoface/view.php?f='.$courseF2FRes->id.'">'.format_string($courseObjData->fullname).'</a></li>';
    }
    else
      $liCourseList .= '<li><a href="/course/view.php?id='.$keyCourseId.'">'.format_string($courseObjData->fullname).'</a></li>';
    $multiCourseName .= format_string($courseObjData->fullname);
    $multiCourseItt++;
    $multiCourseFlag = 2;
  }
}
else {
  $courseObjData = $DB->get_record('course', array('id'=>$keyCD->courseinstance),'fullname');
  //Task #2189 Change Pop-up For Quantum Course start
  if($course->id == 87){
    $liCourseList = '<li><a href="/course/view.php?id=7">Q-SYS Level 1 Training</a></li>';
  }else{ //Task #2189 Change Pop-up For Quantum Course end
    $liCourseList = '<li><a href="/course/view.php?id='.$keyCD->courseinstance.'">'.format_string($courseObjData->fullname).'</a></li>';
  //Task #2189 Change Pop-up For Quantum Course start 
  }
  //Task #2189 Change Pop-up For Quantum Course end
  $multiCourseFlag=1;
  $multiCourseName = format_string($courseObjData->fullname);
}
$liCourseList .= "</ul>";

?>