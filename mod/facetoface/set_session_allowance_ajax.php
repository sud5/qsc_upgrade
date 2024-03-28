<?php
    require_once('../../config.php');
    require_once('lib.php'); //US 6665

    global $CFG; //US 6665
    
    $sessionid = $_REQUEST['sessionid'];
    $userid = $_REQUEST['userid'];
    $cw = $_REQUEST['clearwaitlist']; //US 6665

    $flag = $_REQUEST['flag'];

    $sqlFSH = 'SELECT id, flag FROM {facetoface_session_hold} WHERE session_id='.$sessionid;
    $fshObjData = $DB->get_record_sql($sqlFSH);

    if(!empty($cw)){
        if(remove_waitlisted_users($sessionid)){
            echo "wait listed users removed";
        }
    }
    
    if(empty($fshObjData)){
        $insert_header = "INSERT INTO mdl_facetoface_session_hold (session_id, user_id, flag) VALUES (".$sessionid.",'".$userid."',".$flag.")";
        $DB->execute($insert_header);
    }
    else{
        $update_header = "UPDATE mdl_facetoface_session_hold SET flag = $flag WHERE id = $fshObjData->id";
        $DB->execute($update_header);
    }