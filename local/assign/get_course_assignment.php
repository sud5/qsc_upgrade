<?php
    require_once('../../config.php');
        
    $courseId = $_REQUEST['courseId'];
    //$modinfo = get_fast_modinfo(22);

    //$courseData = $DB->get_records('assign', array('course' => $courseId));
    $sqlCourseModuleAssign = "select cm.id, m.name, a.name, a.type, cm.instance from {modules} m, {course_modules} cm 
    LEFT JOIN {assign} a ON a.id = cm.instance WHERE cm.module=m.id and cm.course = '$courseId' AND cm.module=1" ;// and cm.id in (SELECT cs.sequence FROM {course_sections} cs where cs.course = '$courseId')";

    $courseModuleAssignData = $DB->get_records_sql($sqlCourseModuleAssign);

	$options ="";
    foreach($courseModuleAssignData as $cma){
		if ( (strpos(strtolower($cma->type), 'classroom') !== false) || (strpos(strtolower($cma->type), 'online') !== false) ) {
    		$options .= "<option value='".$cma->id."'>".$cma->name."</option>";
		}
    }

    if($options!=""){
		echo $options;
    }else{
		echo '0';
    }

    die; 
?>
