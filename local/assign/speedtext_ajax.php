<?php
require_once('../../config.php');
$st_id      = $_REQUEST['speedtext_id'];
$userID     = $USER->id;
$select_st = "SELECT * FROM {speed_text} where id = $st_id AND user_id = $userID"; 
$speedtextObjData = $DB->get_record_sql($select_st);
//print_r($speedtextObjData); exit;
if (!$suser = $DB->get_record('user', array('id'=>$userID),"id,username,email,firstname,lastname")) 
{
	cli_error("Can not find user");
}

//Select Speed text

if($speedtextObjData){
	
	echo $speedtextObjData->comment_text;
}
