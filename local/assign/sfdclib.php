<?php
require_once($CFG->dirroot.'/oauth_callback.php');
//echo $sfdcflag; exit;
//if($sfdcflag==0){
require_once($CFG->dirroot.'/user/lib.php');

function removeCourseForBlankCert($rbcVal){
    global $USER;
    $access_token = $_SESSION['access_token'];
    $instance_url = $_SESSION['instance_url'];
    $query = "SELECT Contact__c,course_lms_id__c,Course_Module_Status__c,course_version_type__c,Course__c,CreatedById,Id FROM Course__c WHERE Contact__c = '".$rbcVal->cid."' AND Name = '".$rbcVal->cname."' AND Course_Module_Status__c = null";
    //SELECT Contact__c,course_lms_id__c,Course_Module_Status__c,course_version_type__c,Course__c,CreatedById,Id FROM Course__c WHERE Contact__c = '0030L00001jIB2X' AND Name = 'Q-SYS Level 1 Training' AND Course_Module_Status__c = null

    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
    $json_response = get_sfdc_curl($query, $url, $access_token);
    $response = json_decode($json_response, true);
    $cntRec = count($response['records']);
    

    echo $course_sfdc_id=$response['records'][0]['Id'];
    echo "\n";
    if($course_sfdc_id)
        sfdc_delete_course_by_Id($course_sfdc_id);
    return true;
}
    //Display user detail by id
    //Additional buggy
    function dup_cert_sfdc($username,$course_sfdc_id) {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT Id,contact_sfdc_id__c,LastModifiedById,LastModifiedDate FROM Certificate__c WHERE email__c = '".$username."' AND course_sfdc_id__c = '".$course_sfdc_id."'";
        //$query = "SELECT Id,LastActivityDate,LastModifiedById,LastModifiedDate,LastReferencedDate,LastViewedDate FROM Certificate__c WHERE email__c = 'sameer.chourasia@beyondkey.com' AND course_sfdc_id__c = 'a0U0y000003a89B'";

        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        
        $response = json_decode($json_response, true);
        $cntRec = count($response['records']);
        if($cntRec == 2){
            foreach ($response['records'] as $val) {
                # code...
                $lastModifiedDateArr['id'][] = $val['Id'];
                $lastModifiedDateArr['lmd'][] = $val['LastModifiedDate'];
                $lastModifiedDateArr['csic'][] = $val['contact_sfdc_id__c'];
                
            }

            if($lastModifiedDateArr['lmd'][0] > $lastModifiedDateArr['lmd'][1]){
                $cert_details_arr['id'] = $lastModifiedDateArr['id'][1];
                $cert_details_arr['lmd'] = $lastModifiedDateArr['lmd'][1];
                $cert_details_arr['csic'] = $lastModifiedDateArr['csic'][1];
            }
            else{
                $cert_details_arr['id'] = $lastModifiedDateArr['id'][0];
                $cert_details_arr['lmd'] = $lastModifiedDateArr['lmd'][0];
                $cert_details_arr['csic'] = $lastModifiedDateArr['csic'][0];
            }
            
           // echo "Test ".$cntRec; print_r($response['records']); print_r($cert_details_arr); exit;     
            return $cert_details_arr;
        }
        //echo "Test ".$cntRec; print_r($response['records']); exit;        
    }
//Additional buggy
    function dup_exam_sfdc($username,$course_sfdc_id) {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT Id,contact__c,LastModifiedById,LastModifiedDate FROM Exam__c WHERE course_sfdc_id__c = '".$course_sfdc_id."'";
        //$query = "SELECT Id,LastActivityDate,LastModifiedById,LastModifiedDate,LastReferencedDate,LastViewedDate FROM Certificate__c WHERE email__c = 'sameer.chourasia@beyondkey.com' AND course_sfdc_id__c = 'a0U0y000003a89B'";

        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        
        $response = json_decode($json_response, true);
        $cntRec = count($response['records']);
        if($cntRec == 2){
            foreach ($response['records'] as $val) {
                # code...
                $lastModifiedDateArr['id'][] = $val['Id'];
                $lastModifiedDateArr['lmd'][] = $val['LastModifiedDate'];
                $lastModifiedDateArr['csic'][] = $val['contact__c'];
                
            }

            if($lastModifiedDateArr['lmd'][0] > $lastModifiedDateArr['lmd'][1]){
                $exam_details_arr['id'] = $lastModifiedDateArr['id'][1];
                $exam_details_arr['lmd'] = $lastModifiedDateArr['lmd'][1];
                $exam_details_arr['csic'] = $lastModifiedDateArr['csic'][1];
            }
            else{
                $exam_details_arr['id'] = $lastModifiedDateArr['id'][0];
                $exam_details_arr['lmd'] = $lastModifiedDateArr['lmd'][0];
                $exam_details_arr['csic'] = $lastModifiedDateArr['csic'][0];
            }
            
           // echo "Test ".$cntRec; print_r($response['records']); print_r($cert_details_arr); exit;     
            return $exam_details_arr;
        }
        //echo "Test ".$cntRec; print_r($response['records']); exit;        
    }



    //65 Est start
    function show_module_completion_status($userid, $courseid) {
        //course_module_status__c   
        global $USER,$DB;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
        
        $coursemoduleArr = "3,16,15";
        $sectionDataSql = "SELECT cs.id, cs.sequence FROM {course_sections} cs JOIN {course_modules} cm ON cs.id = cm.section WHERE cm.course = $courseid AND cm.module IN($coursemoduleArr) GROUP BY cm.section";
        $sectionDataRs = $DB->get_records_sql($sectionDataSql);

        $cmIdsArr = array(); $sectionSeqCnt = $moduleCompleted = 0;
        foreach ($sectionDataRs as $key) {
            $sequence = $key->sequence;
            //$sectionSeqCnt = count(explode(",", $sequence));
            //$cmIdsArr[$key->id] = explode(",", $sequence);
            //echo $sectionSeqCnt."<br>";

            $completeCoursesModulesDataRes = array();

            /*$completeCoursesModulesDataSQL = "SELECT cmc.id,cmc.userid,cmc.coursemoduleid FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =$rsValue->id and cmc.userid= $user->id AND cmc.coursemoduleid IN($sequence)";*/

            $completeCoursesModulesDataSQL = "SELECT cmc.id,cmc.userid,cmc.coursemoduleid FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =$courseid and cmc.userid= $userid AND cmc.coursemoduleid IN($sequence) AND cmc.completionstate != 3";

            $completeCoursesModulesDataRes = $DB->get_records_sql($completeCoursesModulesDataSQL);

            $sectionSeqCntRs = array();
            $sectionSeqCntSql = "SELECT id FROM  `mdl_course_modules` WHERE section = $key->id and visible = 1";
            $sectionSeqCntRs = $DB->get_records_sql($sectionSeqCntSql);
            if(count($completeCoursesModulesDataRes) >= count($sectionSeqCntRs)){
                $moduleCompleted++;
            }

        }

        $sqlSC = "SELECT sc.id, sc.name, sc.course, sc.certexpirydateinyear
                      FROM {simplecertificate} sc where sc.course=$courseid";
        $certCompNotiObjData = $DB->get_record_sql($sqlSC);

        if($certCompNotiObjData->certexpirydateinyear != 99){
            $totalcompletedmodules = $moduleCompleted.' out of '.count($sectionDataRs).' modules completed ';
            return $totalcompletedmodules;
        }
        
    }

    // sfdc_unique_id = contact_id
    // echo $access_token = $_SESSION['access_token'];
    // echo "<br>";
    // echo $instance_url = $_SESSION['instance_url'];

    //Display user detail by id
    //62 Est start
    function show_contact_id_by_activeflag($username) {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, email from Contact where email='".$username."' AND lmsmergeflag__c != 'inactive'";
    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        
        $response = json_decode($json_response, true);
        //echo "<pre>"; print_r($response); exit;
        return $response;
    }

    function find_missing_region($username) {
        global $USER, $DB;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, email from Contact where email='".$username."' AND Region__c = ''";
    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        
        $response = json_decode($json_response, true);
        if(isset($response['records'][0])){
            $update_headerss2 = "update mdl_user set region_info_flag = 0 where username='".$username."'";
            $DB->execute($update_headerss2);
        }
        else{
            $update_headerss2 = "update mdl_user set region_info_flag = 1 where username='".$username."'";
            $DB->execute($update_headerss2);
        }
        //echo "<pre>"; print_r($response); exit;
        return $response;
    }


    function sfdc_update_emea_region_contact($sfdcuniqueid) {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
//echo "<pre>22sss22"; print_r($sfdc_contact_id_arr); 
        //echo "sfdc_contact_id $sfdc_contact_id<br/><br/>"; die;
      // foreach($sfdc_contact_id_arr['records'] as $key){
            $id = $sfdcuniqueid;
            $url = "$instance_url/services/data/v20.0/sobjects/Contact/$id";              
            
            $content = json_encode(array("Region__c"=>"EMEA"));

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array("Authorization: OAuth $access_token",
                        "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

            $json_response = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);
        //}

        //$json_response = set_sfdc_curl($content, $url, $access_token);

       // $response = json_decode($json_response, true);

        //$id = $response["id"];

        //echo "New record id $id<br/><br/>";die;
        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        return $status;

    }


   function sfdc_update_inactive_flag_contact($sfdc_contact_id_arr) {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
//echo "<pre>22sss22"; print_r($sfdc_contact_id_arr); 
        //echo "sfdc_contact_id $sfdc_contact_id<br/><br/>"; die;
       foreach($sfdc_contact_id_arr['records'] as $key){
            $id = $key['Id'];
            $url = "$instance_url/services/data/v20.0/sobjects/Contact/$id";              
            
            $content = json_encode(array("lmsmergeflag__c"=>"inactive"));

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array("Authorization: OAuth $access_token",
                        "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

            $json_response = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);
        }

        //$json_response = set_sfdc_curl($content, $url, $access_token);

       // $response = json_decode($json_response, true);

        //$id = $response["id"];

        //echo "New record id $id<br/><br/>";die;
        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        return $status;

    }
    //62 Est end


    function get_sfdc_unique_id(){
        //      echo "<pre>";
        //      print_r($_SESSION);
        //      exit("Success");
        global $DB;
        if(!empty($_SESSION['phpCAS']['user'])){
            // Get User Details from SSO 
            $baseuri = $DB->get_record('config_plugins', array('plugin' => 'auth/cas','name'=>'baseuri'), 'value', MUST_EXIST);

            $ssohostname = $DB->get_record('config_plugins', array('plugin' => 'auth/cas','name'=>'hostname'), 'value', MUST_EXIST);
            $port = $DB->get_record('config_plugins', array('plugin' => 'auth/cas','name'=>'port'), 'value', MUST_EXIST);

            $url = 'https://'.$ssohostname->value.':'.$port->value.'/'.$baseuri->value.'/rest/user/getUserDetails';  
            $objectData = '{"email":"'.$_SESSION['phpCAS']['user'].'"}';

            $objAccInfo = sso_curl($url,$objectData);
            //return $objAccInfo->SfdcUniqueId;
             // echo "<pre>";
             // print_r($url);
             // print_r($objAccInfo);
             // exit("Success");
            //$r = json_encode($objAccInfo);        
        }
    }


    function get_sfdc_curl($query, $url, $access_token){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token"));

        $json_response = curl_exec($curl);
        curl_close($curl);

        return $json_response;
    }

    function set_sfdc_curl($content, $url, $access_token){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 201 ) {
            die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        
        curl_close($curl);

        return $json_response;
    }

    //Delete course details by Id - customo
    //56 est start
    function sfdc_delete_course_by_Id($id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Course__c/$id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        //echo $status." Test Delete Course"; //print_r($json_response); //exit("I AM In Deleted COntact section");
        if ( $status != 204 ) {
            return $status;
        }
        curl_close($curl);
        //exit("DeleteCourse");
        return true;
    }
    //Delete course details end - customo
    
    // -- - --  - Start-Waitlisted Users not removed from Past/Expired Sessions - Naveen --------//
    // Waitlisted Users Classroom info removed from Past/Expired Sessions.    
    function sfdc_delete_classroom_by_course_classroom_id($id)
    {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Classroom__c/$id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        //$response = json_decode($json_response, true);
        //$id = $response["id"];
        return true;
    }

     //Update course Type (classroom to online)
    function sfdc_update_repfirm($id, $repfirm) {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Contact/$id";
        $content = json_encode(array("rep_firm__c"=>$repfirm)); 
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"]; //return true
        return $id;        
    }

     //Update course Type (classroom to online)
    function sfdc_update_course_type($id, $course_type) {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Course__c/$id";
        $content = json_encode(array("course_version_type__c"=>$course_type)); 
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"]; //return true
        return $id;        
    }
    
    // -- - --  - End-Waitlisted Users not removed from Past/Expired Sessions - Naveen --------//
    
    //Display course detail by contact id and course LMS id
    function show_course_by_lms_contact_ids($course_lms_id, $useremail) {
        $contact_id = show_contact_id($useremail);
        if(!empty($contact_id)){
            $access_token = $_SESSION['access_token'];
            $instance_url = $_SESSION['instance_url'];

            $query = "SELECT id from Course__c where course_lms_id__c='$course_lms_id' and contact__c='$contact_id'";
            
            $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
            $json_response = get_sfdc_curl($query, $url, $access_token);    

            $response = json_decode($json_response, true);   

            foreach ((array) $response['records'] as $record) {
               // exit("IfCourse");
                return $record['Id'];
            }    
        }
        else{
            //exit("ElseCourse");
            return false;
        }
    }
    //56 est end

    //Display course details
    function sfdc_show_courses($contact_id, $course_id) {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
        $query = "SELECT name,id,contact__c,course_lms_id__c,course_version_type__c from Course__c where contact__c='$contact_id' AND course_lms_id__c='$course_id'";
       
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);
        //echo "<pre>";print_r($response['records']);exit("HEREEE");
        return $response;
        //$total_size = $response['totalSize'];
    //    echo "SHOW COURSE LIST<br><br>";
      //  echo "$total_size record(s) returned<br/><br/>";
        //echo "<table width = 70% border = '2'><th>CONTACT ID</th><th>COURSE ID</th><th>COURSE-LMS ID</th><th>COURSE TYPE</th>";
    //    foreach ((array) $response['records'] as $record) { 
    //
      //  echo "<tr><td>". $record['Contact__c'] . "</td><td>" .$record['Name'] . "</td><td>" . $record['course_lms_id__c'] . "</td><td>" . $record['course_version_type__c'] . "</td></tr>";

        // }
        //echo "</table><hr>";
    }

    //Add course details
    function sfdc_create_course($contact_id, $course_name, $course_lms_id, $course_type,$dot,$mod_status='') {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Course__c/";

        ////Bug #2419 start
        $tempCourseName = getCourseNameInEnglish($course_lms_id);
        if($tempCourseName!=''){
            $course_name = $tempCourseName;
        }
        ////Bug #2419 end
        
	$course_name = substr(htmlspecialchars($course_name), 0, 75);
	if(strlen($course_name) >= 75){
		$course_name .= "....";
	}
/*echo "\n New coursename id $course_name \n";
echo "\n New coursename id $course_lms_id \n";

echo "\n New coursename id $course_type \n";
echo "\n New coursename id $dot \n";
 exit;*/
	//customo
	$course_name = str_replace("TWO","2",$course_name);
	$course_name = str_replace("Q-SYS Level 2 Classroom Training","Q-SYS Level 2 Training",$course_name);
    $course_name = str_replace("&amp;amp;","&",$course_name);

	$course_name = str_replace("&amp;","&",$course_name);
	//$course_name = str_replace("&amp;amp;","&",$course_name);

        $content = json_encode(array("contact__c"=>$contact_id,"name" => $course_name, "course_lms_id__c"=>$course_lms_id,"course_version_type__c"=>$course_type,"date_of_training__c"=>$dot,"Course_Module_Status__c"=>$mod_status)); 
        
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        //echo "New record id $id<br/><br/>";

        return $id;
    }

    //Update course details
    function sfdc_update_course($id, $contact_id, $course_name, $course_lms_id, $course_type,$dot,$mod_status='') {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Course__c/$id";
//customo
        ////Bug #2419 start
        $tempCourseName = getCourseNameInEnglish($course_lms_id);
        if($tempCourseName!=''){
            $course_name = $tempCourseName;
        }
        ////Bug #2419 end

	$course_name = str_replace("TWO","2",$course_name);

	$course_name = str_replace("Course Certificate","Q-SYS Level 2 Training",$course_name);
	$course_name = str_replace("Q-SYS Level 2 Classroom Training","Q-SYS Level 2 Training",$course_name);
//echo $course_name." ------------------ ".$dot;
	$course_name = str_replace("&amp;amp;","&",$course_name);
        $course_name = str_replace("&amp;","&",$course_name);

//exit;
        

	$content = json_encode(array("name" => $course_name,"course_version_type__c"=>$course_type,"date_of_training__c"=>$dot,"Course_Module_Status__c"=>$mod_status)); 
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"]; //return true
        return $id;        
    }

    //Update course details with Session Instructor name
    function sfdc_update_course_instructor($id, $contact_id, $course_name, $course_lms_id, $course_type,$dot, $session_Instructor = '') {

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Course__c/$id";
    //customo
        ////Bug #2419 start
        $tempCourseName = getCourseNameInEnglish($course_lms_id);
        if($tempCourseName!=''){
            $course_name = $tempCourseName;
        }
        ////Bug #2419 end
    
        $course_name = str_replace("TWO","2",$course_name);

        $course_name = str_replace("Course Certificate","Q-SYS Level 2 Training",$course_name);
        $course_name = str_replace("Q-SYS Level 2 Classroom Training","Q-SYS Level 2 Training",$course_name);
    //echo $course_name." ------------------ ".$dot;
        $course_name = str_replace("&amp;amp;","&",$course_name);
            $course_name = str_replace("&amp;","&",$course_name);

    //exit;
    $session_Instructor_name ="";    
    if(!empty($session_Instructor)){
        $session_Instructor_name= str_replace("##SEPARATOR##",", ", $session_Instructor);
        $session_Instructor_name =ltrim($session_Instructor_name, ", ");
    }
    $content = json_encode(array("name" => $course_name,"course_version_type__c"=>$course_type, 'Instructor_Name__c'=>$session_Instructor_name)); 
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"]; //return true
        return $id;        
    }

    //Display user detail by id
    function show_contact_id($useremail=null) {
    global $USER,$DB;
    // echo "<pre>";
    //           print_r($USER);
    //           print_r($_SESSION);
    //           exit("Success");
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        if($useremail == null){
            $useremaillms = $USER->username;            
            $useremailsfdc = $_SESSION['phpCAS']['user'];
        }
        else{
            $useremaillms = $useremail;
         $_SESSION['phpCAS']['user'] = $useremailsfdc = $useremail;
        }


    //start - updated by lakhan for QSCID
    $queryGetId = $DB->get_record_sql("SELECT sfdcuniqueid FROM {user} where username='".$useremaillms."'");

    //print_r($queryGetId); die;
    if(empty($queryGetId->sfdcuniqueid)){

        //if($useremailsfdc == $useremaillms){
        if($useremaillms){
        //end   
            $query = "SELECT id from Contact where email='$useremaillms'";
        
            $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
            $json_response = get_sfdc_curl($query, $url, $access_token);
            
            $response = json_decode($json_response, true);
    //echo "<pre>"; print_r($response); exit;
            foreach ((array) $response['records'] as $record) {
                return $record['Id'];
            }
        }

     }else{
        $query = "SELECT id from Contact where id='$queryGetId->sfdcuniqueid'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        
        $response = json_decode($json_response, true);
        if(!empty($response['records'])){
            return $queryGetId->sfdcuniqueid;
        } 
     }
    // end - qscid


    }

    //Display user detail by id
    function show_contacts() {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, email from Contact";
    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        
        $response = json_decode($json_response, true);
        //echo "<pre>"; print_r($response); exit;
        return $response;
    }

    //Display course detail by id
    function show_course_id($course_lms_id, $instance_url, $access_token) {
        
        $query = "SELECT id from Course__c where course_lms_id__c='$course_lms_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   

        foreach ((array) $response['records'] as $record) {
        	return $record['Id'];
        }    
    }

    //Display course detail by id
    //New task
    function show_courseBy_id($course_lms_id) {
        global $USER;
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
        $query = "SELECT id, contact__c from Course__c where Id='$course_lms_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   
        //echo "<pre>";print_r($response); exit("Succa1");

        
            return $response;
        
    }

    //Display onlinecourse detail by id
    function show_onlinecourse_by_course_id($course_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
        $query = "SELECT id from online_course__c where course_sfdc_id__c='$course_sfdc_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   

        foreach ((array) $response['records'] as $record) {
            return $record['Id'];
        }    
    }

    //Add onlinecourse details
    function sfdc_create_onlinecourse($course_sfdc_id, $date_of_training){

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/online_course__c/";

        $content = json_encode(array("course_sfdc_id__c"=>$course_sfdc_id,"date_of_training__c" => $date_of_training));   
        
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        //echo "New record id $id<br/><br/>";

        return $id;
    }

    //Display classroomcourse detail by id
    function show_classroom_by_course_id($course_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Classroom__c where course_sfdc_id__c='$course_sfdc_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   
        //echo "<pre>";print_r($response); exit("Succa1");
        foreach ((array) $response['records'] as $record) {
            return $record['Id'];
        }    
    }

     //Get all session details by classroom sfdc id
    //US #2020 add function
    function show_all_session_by_classroom_sfdc_id($classroom_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id,session_start_date__c,session_end_date__c, name from Session__c where classroom_sfdc_id__c='$classroom_sfdc_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   
       // echo "<pre>";print_r($response);// exit("Succa1");
        //US #824 changes comment below for loop
        // foreach ((array) $response['records'][0] as $record) {
        //     return $record['Id'];
        // }
        //US #824 start
        $tsa = $response['records'];
        return $tsa;
    }

   

    //Add classroomcourse details
    function sfdc_create_classroomcourse($course_sfdc_id, $classroom_name=null){

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Classroom__c/";

        $content = json_encode(array("course_sfdc_id__c"=>$course_sfdc_id,"classroom_name__c" => $classroom_name));   
        
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        //echo "New record id $id<br/><br/>";

        return $id;
    }

    //Display session detail by classroom sfdc id
    function show_session_by_classroom_sfdc_id($classroom_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Session__c where classroom_sfdc_id__c='$classroom_sfdc_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   
        // echo "<pre>";print_r($response);// exit("Succa1");
        //US #824 changes comment below for loop
        // foreach ((array) $response['records'][0] as $record) {
        //     return $record['Id'];
        // }
        //US #824 start
        $tsa = $response['records'][0]['Id'];
        return $tsa;
    }
//US #824 start
        //Display session detail by classroom sfdc id
    function show_session_by_classroom_sfdc_id_221($classroom_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Session__c where classroom_sfdc_id__c='$classroom_sfdc_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);  
        $tsa = $response['records'][1]['Id']; 
        // echo "<pre>";print_r($tsa);// exit("Succa122");
        // foreach ((array) $response['records'][1] as $record) {
        //     print_r($record); 
        //    // return $record['Id'];
        // }
        return $tsa;
       // exit;
    }
    //US #824 end

    //Add classroomcourse session details
    function sfdc_create_classroom_session($classroom_sfdc_id, $classroom_type=null,$duration,$level_type,$location,$manager_email,$session_start_date,$session_end_date,$sn,$street){
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Session__c/";

$sn = substr(htmlspecialchars($sn), 0, 75);
	if(strlen($sn) >= 75){
		$sn .= "....";
	}

        $content = json_encode(array("classroom_sfdc_id__c"=>$classroom_sfdc_id,"classroom_type__c" => $classroom_type,"duration__c"=>$duration,"level_type__c"=>$level_type,"location__c"=> $location,"manager_email__c"=>$manager_email,"session_start_date__c"=>$session_start_date,"session_end_date__c"=>$session_end_date,"street__c"=>$street,"Name"=>$sn));

        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        //echo "New record id $id<br/><br/>";
        return $id;        
    }


    //Update classroomcourse session details
    function sfdc_update_classroom_session($id, $classroom_type=null,$duration,$level_type,$location,$manager_email,$session_start_date,$session_end_date,$sn,$street){
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Session__c/$id";

$sn = substr(htmlspecialchars($sn), 0, 75);
	if(strlen($sn) >= 75){
		$sn .= "....";
	}

        $content = json_encode(array("classroom_type__c" => $classroom_type,"duration__c"=>$duration,"level_type__c"=>$level_type,"location__c"=> $location,"manager_email__c"=>$manager_email,"session_start_date__c"=>$session_start_date,"session_end_date__c"=>$session_end_date,"street__c"=>$street,"Name"=>$sn));
//         echo "\n Testid \n";
// //         echo $id;
// //         echo "\n";
// // print_r($content);
// // exit;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"]; //return true
        return $id;
    }

    //Display session detail by classroom sfdc id
    //Commented the below function US #2020 start
    // function show_registration_by_session_sfdc_id($session_sfdc_id) {
    //     $access_token = $_SESSION['access_token'];
    //     $instance_url = $_SESSION['instance_url'];

    //     $query = "SELECT id from classroom_cancel_registration__c where session_sfdc_id__c='$session_sfdc_id'";
        
    //     $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
    //     $json_response = get_sfdc_curl($query, $url, $access_token);    

    //     $response = json_decode($json_response, true);   
    //     //echo "<pre>";print_r($response); exit("Succa1");
    //     foreach ((array) $response['records'] as $record) {
    //         return $record['Id'];
    //     }
    // }
    //Commented the above function US #2020 end


    //Add users contact details
    function sfdc_create_users_contact($fname,$lname,$email,$phone,$comp,$address_one,$address_two=NULL,$city,$state,$country,$zip=NULL,$tz=NULL){

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Contact/";
 
        $street = $address_one.' '.$address_two;
       
        $content = json_encode(array("FirstName"=>$fname,"LastName" => $lname,"Email"=>$email,"Phone"=>$phone,"Company__c"=> $comp,"Time_Zone__c"=>$tz,"MailingStreet"=>$street,"MailingCity"=>$city,"MailingState"=>$state,"MailingCountry"=>$country,"MailingPostalCode"=>$zip));
        // $content = json_encode(array("FirstName"=>$fname,"LastName" => $lname,"Email"=>$username,"Additional_Email__c"=>$email,"Phone"=>$phone,"Company__c"=> $comp,"MailingStreet"=>$street,"MailingCity"=>$city,"MailingState"=>$state,"MailingCountry"=>$country,"MailingPostalCode"=>$zip,"Time_Zone__c"=>$tz,'Region__c'=>$region,'Sub_region__c'=>$sub_region,"rep_firm__c"=>$rep_firm));
        
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        //echo "New record id $id<br/><br/>";

        return $id;
    }

//Delete contact details - customo
    function sfdc_delete_contact($id) {

         $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Contact/$id";


        //$content = json_encode(array("Name"=>$module_exam_name,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_time_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status));
        //echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

echo $status." Test Delete Contact"; //print_r($json_response); //exit("I AM In Deleted COntact section");
        if ( $status != 204 ) {
            return $status;
        }
        curl_close($curl);
//        $response = json_decode($json_response, true);
  //      $id = $response["id"];

        return true;
    }
//Delete contact details end - customo

    //Add classroomcourse registration details
    //Add classroomcourse registration details
    //Commented the below functions US #2020 start
    // function sfdc_create_classroom_cancel_registration($session_sfdc_id, $ref_name=null,$tandc,$dr,$rd,$cs,$cr,$zip){

    //     $access_token = $_SESSION['access_token'];
    //     $instance_url = $_SESSION['instance_url'];

    //     $url = "$instance_url/services/data/v20.0/sobjects/classroom_cancel_registration__c/";
 
    //     $content = json_encode(array("session_sfdc_id__c"=>$session_sfdc_id,"reffered_name__c" => $ref_name,"terms_condition__c"=>$tandc,"dietary_restriction__c"=>$dr,"restriction_details__c"=> $rd,"classroom_status__c"=>$cs,"cancel_reason__c"=>$cr,"zipcode__c"=>$zip));
        
    //     $json_response = set_sfdc_curl($content, $url, $access_token);

    //     $response = json_decode($json_response, true);

    //     $id = $response["id"];

    //     //echo "New record id $id<br/><br/>";

    //     return $id;
    // }

    //Delete session details by Id - customo
    //US #2020 add function
    function sfdc_delete_session_by_Id($id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Session__c/$id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        //echo $status." Test DeleteSession"; //print_r($json_response); //exit("I AM In Deleted session section");
        if ( $status != 204 ) {
            return $status;
        }
        curl_close($curl);
        //exit("DeleteSession");
        return true;
    }
    //Delete course details end - customo

    //Update classroomcourse registration details
    // function sfdc_update_classroom_cancel_registration($id, $session_sfdc_id, $ref_name=null,$tandc,$dr,$rd,$cs,$cr,$zip){
        
    //     $access_token = $_SESSION['access_token'];
    //     $instance_url = $_SESSION['instance_url'];

    //     $url = "$instance_url/services/data/v20.0/sobjects/classroom_cancel_registration__c/$id";

    //     $content = json_encode(array("session_sfdc_id__c"=>$session_sfdc_id,"reffered_name__c" => $ref_name,"terms_condition__c"=>$tandc,"dietary_restriction__c"=>$dr,"restriction_details__c"=> $rd,"classroom_status__c"=>$cs,"cancel_reason__c"=>$cr,"zipcode__c"=>$zip));
    //     //echo "<pre>";print_r($content); //exit("Success");

    //     $curl = curl_init($url);
    //     curl_setopt($curl, CURLOPT_HEADER, false);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER,
    //             array("Authorization: OAuth $access_token",
    //                 "Content-type: application/json"));
    //     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    //     $json_response = curl_exec($curl);

    //     $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    //     if ( $status != 204 ) {
    //         die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    //     }
    //     curl_close($curl);
    //     $response = json_decode($json_response, true);
    //     $id = $response["id"]; //return true
    //     return $id;
    // }

    //Display exam detail by course sfdc id
    function show_sfdc_exam($course_sfdc_id,$cm_lms_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Exam__c where course_sfdc_id__c='$course_sfdc_id' AND cm_lms_id__c='$cm_lms_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   
        //echo "<pre>";print_r($response); exit("Succa1");
        foreach ((array) $response['records'] as $record) {
            return $record['Id'];
        }    
    }

    //Display exam detail by course sfdc id
    //New task
    function show_all_sfdc_exam() {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, cm_lms_id__c, course_sfdc_id__c, exam_submission_modified__c from Exam__c where contact__c=''";
        
        $url = "$instance_url/services/data/v41.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        $response = json_decode($json_response, true);

        //echo "<pre>";print_r($response); exit("Succa1");
        return $response;
       /* foreach ((array) $response['records'] as $record) {
            return $record['Id'];
        }*/    
    }

    //Add exam details
  ////Task #2524, added new argument $assignId & $studentId.
    function sfdc_create_exam($course_sfdc_id,$cm_lms_id,$module_exam_name,$exam_grade,$exam_design_attempts,$exam_time_modified,$instructor_feedback,$exam_submission_status="Yes",$sfdc_contact_id,$newformat='', $assignId='', $studentId='') {
        ////Task #2524 start
        $dateLatestGrade = getDateOfLatestGrade($assignId, $studentId);
        ////Task #2524 end
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Exam__c/";
$module_exam_name = substr(htmlspecialchars($module_exam_name), 0, 75);
	if(strlen($module_exam_name) >= 75){
		$module_exam_name .= "....";
	}

/*$instructor_feedback = substr(htmlspecialchars($instructor_feedback), 0, 100);
if(strlen($instructor_feedback) >= 100){
		$instructor_feedback .= "....";
	}*/

        ////Task #2524 start
        $reqData = array("Name"=>$module_exam_name,"course_sfdc_id__c"=>$course_sfdc_id,"cm_lms_id__c"=>$cm_lms_id,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_submission_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status,"contact__c"=>$sfdc_contact_id,"Date_of_Most_Recent_Submission__c"=>$newformat);


        if($dateLatestGrade!=''){
            $reqData['Date_of_Most_Recent_Feedback__c'] = $dateLatestGrade;
        }
        $content = json_encode($reqData);

        ////$content = json_encode(array("Name"=>$module_exam_name,"course_sfdc_id__c"=>$course_sfdc_id,"cm_lms_id__c"=>$cm_lms_id,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_submission_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status,"contact__c"=>$sfdc_contact_id,"Date_of_Most_Recent_Submission__c"=>$newformat));
        ////Task #2524 end

        $json_response = set_sfdc_curl($content, $url, $access_token);
        $response = json_decode($json_response, true);

        $id = $response["id"];



      //  echo "New record id $id<br/><br/>";

        return $id;
    }

//Delete exam details
    function sfdc_delete_exam($id) {
         $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Exam__c/$id";


        //$content = json_encode(array("Name"=>$module_exam_name,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_time_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status));
        //echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        
        curl_close($curl);
//        $response = json_decode($json_response, true);
  //      $id = $response["id"];

        return true;
    }

     function sfdc_delete_cert($id) {
         $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Certificate__c/$id";


        //$content = json_encode(array("Name"=>$module_exam_name,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_time_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status));
        //echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
//        $response = json_decode($json_response, true);
  //      $id = $response["id"];

        return true;
    }

//New task one time function
    function sfdc_update_exam_onetime($id,$module_exam_name,$sfdc_contact_id,$newformat='') {
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Exam__c/$id";
        $module_exam_name = substr(htmlspecialchars($module_exam_name), 0, 75);
    if(strlen($module_exam_name) >= 75){
        $module_exam_name .= "....";
    }

        $content = json_encode(array("Name"=>$module_exam_name,"contact__c"=>$sfdc_contact_id,"Date_of_Most_Recent_Submission__c"=>$newformat));
        //echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//echo "<br>";
        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        //echo $status ." ExamID <br>";
        //exit;
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"];
        return $id;
    }

    ////Task #2524, added new argument $assignId & $studentId.
    function sfdc_update_exam($id, $module_exam_name, $exam_grade,$exam_design_attempts,$exam_time_modified,$instructor_feedback,$exam_submission_status=NULL,$sfdc_contact_id,$newformat='', $assignId='', $studentId='') {
        ////Task #2524 start
        $dateLatestGrade = getDateOfLatestGrade($assignId, $studentId);
        ////Task #2524 end
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Exam__c/$id";

$module_exam_name = substr(htmlspecialchars($module_exam_name), 0, 75);
	if(strlen($module_exam_name) >= 75){
		$module_exam_name .= "....";
	}
/*$instructor_feedback = substr(htmlspecialchars($instructor_feedback), 0, 100);
if(strlen($instructor_feedback) >= 100){
		$instructor_feedback .= "....";
	}*/
        ////Task #2524 start
        $reqData = array("Name"=>$module_exam_name,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_submission_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status,"contact__c"=>$sfdc_contact_id,"Date_of_Most_Recent_Submission__c"=>$newformat);
        if($dateLatestGrade!=''){
            $reqData['Date_of_Most_Recent_Feedback__c'] = $dateLatestGrade;
        }
        $content = json_encode($reqData);
        //// $content = json_encode(array("Name"=>$module_exam_name,"exam_grade__c"=>$exam_grade,"exam_design_attempts__c"=>$exam_design_attempts,"exam_submission_modified__c"=>$exam_time_modified,"instructor_feedback__c"=>$instructor_feedback,"exam_submission_status__c"=>$exam_submission_status,"contact__c"=>$sfdc_contact_id,"Date_of_Most_Recent_Submission__c"=>$newformat));
        ////Task #2524 end
        //echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"];
        return $id;
    }

    //Display classroomcourse detail by id
    function sfdc_show_lessons($course_sfdc_id,$cm_lms_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id,course_sfdc_id__c,cm_lms_id__c from Lesson__c where course_sfdc_id__c='$course_sfdc_id' AND cm_lms_id__c='$cm_lms_id'";
        
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
        $json_response = get_sfdc_curl($query, $url, $access_token);    

        $response = json_decode($json_response, true);   
    //echo "<pre>";print_r($response); exit("Succa1");
        //foreach ($response['records'][0] as $record) {
            return $response['records'][0];
        //}    
    }


    //Add lesson details
    function create_lesson($course_sfdc_id, $lesson_name,$time_modified,$cm_lms_id,$lesson_completion_status) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Lesson__c/";

$lesson_name = mb_substr($lesson_name, 0, 75, "utf-8");
//$lesson_name = substr_unicode($lesson_name, 0, 75);
	if(strlen($lesson_name) >= 75){
		//echo "\n";print_r(urlencode($lesson_name)); echo "\n";
		$lesson_name .= "....";
	}

//echo $lesson_name;echo "\n"; print_r($content); echo "\n";
//$lesson_name = strrpos($lesson_name, "�");

        $content = json_encode(array("course_sfdc_id__c"=>$course_sfdc_id,"name"=>$lesson_name,"lesson_time_modified__c"=>$time_modified,"cm_lms_id__c"=>$cm_lms_id,"lesson_completion_status__c"=>$lesson_completion_status));   
  //    echo $lesson_name; 
//echo "\n";
//echo "\n"; print_r($content); echo "\n";exit("Successss");
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        //echo "New record id $id<br/><br/>";

        return $id;
    }

    //Display assessment detail by id
    function sfdc_show_assessment($course_sfdc_id,$cm_lms_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Assessment__c where course_sfdc_id__c='$course_sfdc_id' AND cm_lms_id__c='$cm_lms_id'";    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
    //echo "<pre>";print_r($response); exit("Succa1");
        //foreach ($response['records'][0] as $record) {
            return $response['records'][0]['Id'];
        //}    
    }

    //Add assessment details
    function create_assessment($course_sfdc_id,$cm_lms_id,$module_quiz_name,$assessment_grade,$assessment_attempts ,$assessment_time_modified) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Assessment__c/";

$module_quiz_name = substr(htmlspecialchars($module_quiz_name), 0, 75);
	if(strlen($module_quiz_name) >= 75){
		$module_quiz_name .= "....";
	}

    $module_quiz_name = str_replace("&amp;amp;","&",$module_quiz_name);
    $module_quiz_name = str_replace("&amp;","&",$module_quiz_name);
    $module_quiz_name = str_replace("&","&",$module_quiz_name);

        $content = json_encode(array("Name"=>$module_quiz_name,"course_sfdc_id__c"=>$course_sfdc_id,"cm_lms_id__c"=>$cm_lms_id,"assessment_grade__c"=>$assessment_grade,"assessment_attempts__c"=>$assessment_attempts,"assessment_time_modified__c"=>$assessment_time_modified));   
      
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

       // echo "New record id $id<br/><br/>";

        return $id;
    }

    //Update assessment details
    function update_assessment($id, $assessment_grade,$module_quiz_name,$assessment_attempts ,$assessment_time_modified) {
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Assessment__c/$id";

$module_quiz_name = substr(htmlspecialchars($module_quiz_name), 0, 75);
	if(strlen($module_quiz_name) >= 75){
		$module_quiz_name .= "....";
	}
    $module_quiz_name = str_replace("&amp;amp;","&",$module_quiz_name);
    $module_quiz_name = str_replace("&amp;","&",$module_quiz_name);
    $module_quiz_name = str_replace("&","&",$module_quiz_name);

        $content = json_encode(array("Name"=>$module_quiz_name,"assessment_grade__c" => $assessment_grade, "assessment_attempts__c" => $assessment_attempts,
         "assessment_time_modified__c" => $assessment_time_modified));
        //echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        return $id;
    }

    //Display certificate detail by id
    function sfdc_show_certificate($course_sfdc_id, $contact_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Certificate__c where course_sfdc_id__c='$course_sfdc_id' AND contact_sfdc_id__c='$contact_id'";    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
    //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0]['Id'])){
            return $response['records'][0]['Id'];
        }
        else{
            return 0;
        }
        //}    
    }

    //Display certificate detail by id
    function sfdc_show_certificate_yes($course_sfdc_id, $contact_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from Certificate__c where certified__c = 'yes' and course_sfdc_id__c='$course_sfdc_id' AND contact_sfdc_id__c='$contact_id'";    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
    //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0]['Id'])){
            return $response['records'][0]['Id'];
        }
        else{
            return 0;
        }
        //}    
    }

   //Display certified status by id
//Certificate Date Code Start
    function sfdc_get_cert_status($course_sfdc_id, $contact_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, certified__c from Certificate__c where course_sfdc_id__c='$course_sfdc_id' AND contact_sfdc_id__c='$contact_id'";    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
    //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0])){
            return $response['records'][0];
        }
        else{
            return 0;
        }
        //}    
    }
//Certificate Date Code End


//Certificate Date Code Start
    function sfdc_get_cert_detail_status($course_sfdc_id, $contact_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, certified__c from Certificate__c where course_sfdc_id__c='$course_sfdc_id' AND contact_sfdc_id__c='$contact_id'";    
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
    //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0])){
$certificate_sfdc_id = $response['records'][0]['Id'];
		$query2 = "SELECT id, certificate_expiration_date__c, certificate_date__c from certificate_detail__c where certificate_sfdc_id__c='$certificate_sfdc_id'";
        	$url2 = "$instance_url/services/data/v20.0/query?q=" . urlencode($query2);
        	$json_response2 = get_sfdc_curl($query2, $url2, $access_token);
		$response2 = json_decode($json_response2, true);
                //echo "<pre>";print_r($response2); exit("Succa1");
		return $response2;            
        }
        else{
            return 0;
        }
        //}    
    }
//Certificate Date Code End

    //Add certificate information
   /* function create_certificate($certificate_name,$certified,$contact_sfdc_id,$course_sfdc_id,$user_email) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Certificate__c/";
	
	

        $certificate_name = substr(htmlspecialchars($certificate_name), 0, 75);
	if(strlen($certificate_name) >= 75){
		$certificate_name .= "....";
	}
	$certificate_name = str_replace("&amp;amp;","&",$certificate_name);
    $certificate_name = str_replace("&amp;","&",$certificate_name);
	
	$certificate_name = str_replace("&","&",$certificate_name);

        $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"course_sfdc_id__c"=>$course_sfdc_id,"certified__c"=>		$certified,"contact_sfdc_id__c"=>$contact_sfdc_id));   
      
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

       // echo "New record id $id<br/><br/>";

        return $id;
    }

   */

    //Add certificate information
    function create_certificate($mod_status,$certificate_name,$certified,$contact_sfdc_id,$course_sfdc_id,$user_email) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Certificate__c/";
    
    

        $certificate_name = substr(htmlspecialchars($certificate_name), 0, 75);
        if(strlen($certificate_name) >= 75){
            $certificate_name .= "....";
        }
        $certificate_name = str_replace("&amp;amp;","&",$certificate_name);
        $certificate_name = str_replace("&amp;","&",$certificate_name);
        
        $certificate_name = str_replace("&","&",$certificate_name);

        //$content = json_encode(array("course_module_status__c"=>$mod_status,"Name"=>$certificate_name,"email__c"=>$user_email,"course_sfdc_id__c"=>$course_sfdc_id,"certified__c"=>     $certified,"contact_sfdc_id__c"=>$contact_sfdc_id));   
        $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"course_sfdc_id__c"=>$course_sfdc_id,"certified__c"=>     $certified,"contact_sfdc_id__c"=>$contact_sfdc_id));
        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

       // echo "New record id $id<br/><br/>";

        return $id;
    }

    //Update Certificate
    //59 Est start
    // - -- - --- - - Start- Reporting Errors Case-II for Certificate Update Functions -
    function update_certificate($mod_status, $id, $certificate_name,$certified,$user_email,$flag=0,$cert_exp_date='',$cert_date='',$ren_status=NULL,$expiry=NULL,$expity_update = NULL) {
        // - -- - --- - - END- Reporting Errors Case-II for Certificate Update Functions -
        //59 Est end
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Certificate__c/$id";
        
        $certificate_name = substr(htmlspecialchars($certificate_name), 0, 75);
        if(strlen($certificate_name) >= 75){
            $certificate_name .= "....";
        }
        $certificate_name = str_replace("&amp;amp;","&",$certificate_name);
        $certificate_name = str_replace("&amp;","&",$certificate_name);
        $certificate_name = str_replace("&","&",$certificate_name);
        // - -- - --- - - Start- Reporting Errors Case-II for Certificate Update Functions -
        if(empty($expity_update)){
            // - -- - --- - - END- Reporting Errors Case-II for Certificate Update Functions -   
            if($flag == 0 && $cert_exp_date != ''){
               $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"certified__c"=>$certified,"Certificate_Expiration_Date_2__c"=>$cert_exp_date,"Certification_Date_2__c"=>$cert_date,"Renewal_Status_2__c"=>$ren_status,"Expires__c"=>$expiry));
            }
            elseif($flag == 0 && $cert_exp_date == '' && $cert_date != ''){
             $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"certified__c"=>$certified,"Certification_Date_2__c"=>$cert_date,"Renewal_Status_2__c"=>$ren_status,"Expires__c"=>$expiry));   
            }
            elseif($flag == 0 && $cert_exp_date == '' && $cert_date == ''){
             $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"certified__c"=>$certified,"Expires__c"=>$expiry));   
            }
            else{
                $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"certified__c"=>$certified));   
            }
         // - -- - --- - - Start- Reporting Errors Case-II for Certificate Update Functions -
        }else{
            $content = json_encode(array("Name"=>$certificate_name,"email__c"=>$user_email,"certified__c"=>$certified,"Certificate_Expiration_Date_2__c"=>$cert_exp_date,"Certification_Date_2__c"=>$cert_date,"Renewal_Status_2__c"=>$ren_status,"Expires__c"=>$expiry));
        }
        // - -- - --- - - END- Reporting Errors Case-II for Certificate Update Functions -
       // echo "<pre>";print_r($content); //exit("Success");

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);

        $response = json_decode($json_response, true);
// /echo "<pre>";print_r($response); exit("Success");
        $id = $response["id"];

        return $id;
    }

    //Display certificate detail by id
    function sfdc_show_certificate_details($certificate_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from certificate_detail__c where certificate_sfdc_id__c='$certificate_sfdc_id'";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
        //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0]['Id'])){
            return $response['records'][0]['Id'];
        }
        else{
            return 0;
        }
        //}    
    }

    //Display certificate detail by id
    function sfdc_show_certificate_details_by_date($certificate_sfdc_id,$cdate,$cedate) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id from certificate_detail__c where certificate_sfdc_id__c='$certificate_sfdc_id' AND certificate_date__c='$cdate' AND certificate_expiration_date__c='$cedate'";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
        //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0]['Id'])){
            return $response['records'][0]['Id'];
        }
        //}    
    }

    //Add user certificate_details information
  //RCP start
    function create_certificate_detail($certificate_date,$certificate_expiration_date,$certificate_sfdc_id,$count_renew_certificate,$expiry,$ren_date){
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/certificate_detail__c/";
        if($certificate_expiration_date == ''){
            $content = json_encode(array("certificate_date__c"=>$certificate_date,"certificate_sfdc_id__c"=>$certificate_sfdc_id,"expiry__c"=>$expiry,"renewal_status__c"=>$ren_date));
        }else{
            $content = json_encode(array("certificate_date__c"=>$certificate_date,"certificate_sfdc_id__c"=>$certificate_sfdc_id,"certificate_expiration_date__c"=>$certificate_expiration_date,"expiry__c"=>$expiry,"renewal_status__c"=>$ren_date));
        }

        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        return $id;
    }

    //Update user certificate details
    function update_certificate_detail($id, $certificate_date,$certificate_expiration_date,$certificate_sfdc_id,$count_renew_certificate,$expiry,$ren_date){
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/certificate_detail__c/$id";
        if($certificate_expiration_date == ''){
            $content = json_encode(array("certificate_date__c"=>$certificate_date,"expiry__c"=>$expiry,"certificate_expiration_date__c"=>NULL,"renewal_status__c"=>$ren_date));
            echo("1");
        }else{
            $content = json_encode(array("certificate_date__c"=>$certificate_date,"certificate_expiration_date__c"=>$certificate_expiration_date,"expiry__c"=>$expiry,"renewal_status__c"=>$ren_date));
            echo $certificate_date; echo "\n";
            echo $certificate_expiration_date; echo "\n";
            echo("12");
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        return $id;
    }
//RCP end

    //Add classroom details
    function create_classroom($course_sfdc_id,$classroom_name,$instance_url, $access_token) {
        $url = "$instance_url/services/data/v20.0/sobjects/classroom__c/";

        $content = json_encode(array("classroom_name__c"=>$classroom_name,"course_sfdc_id__c"=>$course_sfdc_id));   

        $json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        $id = $response["id"];

        echo "New record id $id<br/><br/>";

        return $id;
    }

//start - update username field for email and email field for email_2 - QSCID - SSO
        //59Est start
 function sfdc_update_existing_profile($id,$fname,$lname,$username,$email=NULL,$phone,$comp,$address_one,$address_two=NULL,$city,$state,$country,$zip=NULL,$tz=NULL,$region,$sub_region,$usstates=NULL,$rep_firm=NULL,$imp_flag=0){
    //59 est end
        /* Region Information Flag Settings Start */
        global $USER, $DB;
        /* Region Information Flag Settings End */

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        //echo "sfdc_contact_id $sfdc_contact_id<br/><br/>"; die;
        $url = "$instance_url/services/data/v20.0/sobjects/Contact/$id";
        
        $street = $address_one.' '.$address_two;
        //US 2490 start
        $email = '';
        //US 2490 end
        $content = json_encode(array("FirstName"=>$fname,"LastName" => $lname,"Email"=>$username,"Additional_Email__c"=>$email,"Phone"=>$phone,"Company__c"=> $comp,"Time_Zone__c"=>$tz,'Region__c'=>$region,'Sub_region__c'=>$sub_region,"Mailing_US_State__c"=>$usstates,"rep_firm__c"=>$rep_firm,"MailingStreet"=>$street,"MailingCity"=>$city,"MailingState"=>$state,"MailingCountry"=>$country,"MailingPostalCode"=>$zip));

                // $content = json_encode(array("FirstName"=>$fname,"LastName" => $lname,"Email"=>$username,"Additional_Email__c"=>$email,"Phone"=>$phone,"Company__c"=> $comp,"MailingStreet"=>$street,"MailingCity"=>$city,"MailingState"=>$state,"MailingCountry"=>$country,"MailingPostalCode"=>$zip,"Time_Zone__c"=>$tz,'Region__c'=>$region,'Sub_region__c'=>$sub_region,"rep_firm__c"=>$rep_firm));

        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {

            //to handle sfdc deleted user
            if($status == 404){
                global $USER;
                $sesskey = $USER->sesskey;
                if($imp_flag == 0){
                    redirect($CFG->dirroot . "/login/logout.php?sfdcstatus=404");
                }
            }else{
                die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));    
            }
            //to handle sfdc deleted user
            
        }
        curl_close($curl);

        /* Region Information Flag Settings Start */
        if($region != ''){
            if($USER->id != ''){
                $update_headerss2 = "update mdl_user set region_info_flag = 1 where id=".$USER->id;
                $DB->execute($update_headerss2);
            }
            else{
                $update_headerss2 = "update mdl_user set region_info_flag = 1 where username='".$username."'";
                $DB->execute($update_headerss2);   
            }
        }
        else{
            $update_headerss2 = "update mdl_user set region_info_flag = 0 where username='".$username."'";
            $DB->execute($update_headerss2);
        }
        /* Region Information Flag Settings End */

        //$json_response = set_sfdc_curl($content, $url, $access_token);

        $response = json_decode($json_response, true);

        //$id = $response["id"];

        //echo "New record id $id<br/><br/>";die;

        return $json_response;
    }
    
//end - update username field for email and email field for email_2 - QSCID - SSO


 /* =========== ADD NEW FIELD TO LMS SALESFORCE FEED (Last Access) - START BY PAWAN ================ */
     function sfdc_update_existing_profile_last_access_field($id,$lastaccess){

        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        //echo "sfdc_contact_id $sfdc_contact_id<br/><br/>"; die;
        $url = "$instance_url/services/data/v20.0/sobjects/Contact/$id";
              
        $content = json_encode(array("LastAccess__c"=>$lastaccess));
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {  

            //to handle sfdc deleted user
            if($status == 404){
                global $USER;
                $sesskey = $USER->sesskey;
                redirect($CFG->dirroot . "/login/logout.php?sfdcstatus=404");
            }else{
                die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));    
            }
            //to handle sfdc deleted user
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        return $json_response;
    }

 /* =========== ADD NEW FIELD TO LMS SALESFORCE FEED (Last Access) - END BY PAWAN ================ */
function update_certificate_details($userid){
        global $USER,$DB,$CFG;


        $certNotiObjData = $DB->get_records('simplecertificate',null,'','id,name,course,certexpirydateinyear');
        foreach ($certNotiObjData as $keyCertVal) {

            //observation-1 start
            $courseId = $keyCertVal->course;

            // Certificate Business Logic
            $certExpDurationDate = $keyCertVal->certexpirydateinyear;


           
// echo "\n";
//                 print_r($sqlSCILObj);
//                 echo "\n";
$nsql1 = "SELECT * FROM {course_completions} WHERE userid = $userid AND course = $keyCertVal->course order by timeenrolled";
$nrs1 = $DB->get_record_sql($nsql1);
if($nrs1){
            $coursesModulesBookExistSQL = "SELECT cmc.id, cmc.completionstate FROM {course_modules} cm JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id WHERE cm.course =".$keyCertVal->course." and cm.visible=1 and cm.module IN (1, 3,16,15) and cmc.userid=".$userid." order by cmc.completionstate desc limit 0,1";
      $coursesModulesBookExistSQLRS = $DB->get_record_sql($coursesModulesBookExistSQL);
//echo $coursesModulesBookExistSQLRS->completionstate; exit;
      if(!empty($coursesModulesBookExistSQLRS)){
        $nrs1->timestarted = $nrs1->timeenrolled;
        if($coursesModulesBookExistSQLRS->completionstate == 3){
            $nrs1->timecompleted = NULL;
            //Moodle3.11 for passing grade fail should be incomplete course completion
            $nsqlSCIL = 'SELECT id,userid, courseid FROM {simplecertificate_issue_logs} scil where userid = '.$userid.' and courseid = '.$keyCertVal->course.' AND timecompletion is not null';
            $nsqlSCILObj = $DB->get_record_sql($nsqlSCIL);
            if(!empty($nsqlSCILObj)){           
                $params['ncoursieid']=$nsqlSCILObj->courseid;
                $params['nuserid']=$userid;
                $DB->delete_records_select('simplecertificate_issue_logs', '(courseid = :ncoursieid AND userid = :nuserid)', $params);
            }
            $nsqlSCI = 'SELECT id, userid, certificateid, timecreated, timeexpired
                              FROM {simplecertificate_issues} sci                  
                             WHERE certificateid='.$keyCertVal->id.' and userid='.$userid;
            $nissueCertCompNotiObjData = $DB->get_record_sql($nsqlSCI);
            if(!empty($nissueCertCompNotiObjData)){           
                $params['ncertid']=$keyCertVal->id;
                $params['nuserid']=$userid;
                $DB->delete_records_select('simplecertificate_issues', '(certificateid = :ncertid AND userid = :nuserid)', $params);
            }
            $nsqlSCICL2 = 'SELECT * FROM mdl_simplecertificate_issue_change_log where courseid = '.$keyCertVal->course.' AND userid = '.$userid.' order by id desc limit 0,1';
            $nsqlSCICLObj2 = $DB->get_record_sql($nsqlSCICL2);
            if(!empty($nsqlSCICLObj2)){
                $params['ncoursieid']=$nsqlSCICLObj2->courseid;
                $params['nuserid']=$userid;
                $DB->delete_records_select('simplecertificate_issue_change_log', '(courseid = :ncoursieid AND userid = :nuserid)', $params);
            }
        }elseif($coursesModulesBookExistSQLRS->completionstate == 2 && $nrs1->timecompleted == NULL){
            $nrs1->timecompleted = time();
        }

        $DB->update_record('course_completions',$nrs1);

      }
  }

   $sqlSCIL = 'SELECT id,userid, courseid, MAX(timecompletion) as timecompletion FROM {simplecertificate_issue_logs} scil where userid IN ('.$userid.') and courseid = '.$keyCertVal->course.' AND timecompletion is not null group by userid, courseid';
            $sqlSCILObj = $DB->get_records_sql($sqlSCIL);

          if($keyCertVal->certexpirydateinyear == 99 || $keyCertVal->course == 42){
            if($sqlSCILObj){
                foreach($sqlSCILObj as $sqlSCILObjData){
                    $issueCertCompNotiObjData='';
                    $sqlSCI = 'SELECT id, userid, certificateid, timecreated, timeexpired
                              FROM {simplecertificate_issues} sci                  
                             WHERE certificateid='.$keyCertVal->id.' and userid='.$sqlSCILObjData->userid;
                    $issueCertCompNotiObjData = $DB->get_record_sql($sqlSCI);
                    
                    $certExpiredDate = 99;
                    if(empty($issueCertCompNotiObjData) || $issueCertCompNotiObjData == ''){
                            $insert_header = "INSERT INTO mdl_simplecertificate_issues (userid, certificateid, certificatename, status, timecreated, timeexpired) VALUES (".$sqlSCILObjData->userid.", ".$keyCertVal->id.", '".$keyCertVal->name."',0, '".$sqlSCILObjData->timecompletion."', '".$certExpiredDate."')";
                            $DB->execute($insert_header);
                    }
                }       
            }
          } //observation-1 end
          else{

        if($sqlSCILObj){
            /*foreach($sqlSCILObj as $sqlSCILObjData1){
                //echo "010";
                echo "\n";
                echo "Userid - ".$sqlSCILObjData1->userid. " ------- CourseID - " .$sqlSCILObjData1->courseid;
                echo "\n";
                //Update scil flag_update_in_sci 0 to 1
                $update_header2 = "update mdl_simplecertificate_issue_logs set flag_update_in_sci = 1 where id = $sqlSCILObjData1->id and courseid = ".$keyCertVal->course;
                $DB->execute($update_header2);
            }*/
            foreach($sqlSCILObj as $sqlSCILObjData){
                if(!empty($sqlSCILObjData->timecompletion)){
                    
                    // echo "\n";
                    // echo "Userid - ".$sqlSCILObjData->userid. " ------- CourseID - " .$sqlSCILObjData->courseid ." \n Timecompletion - ".$sqlSCILObjData->timecompletion." \n";
                    // echo "\n";
        //      echo "\n";
        //  echo "2";
        //  echo "\n";
                    
                    
                    $sqlSCICL = 'SELECT * FROM mdl_simplecertificate_issue_change_log where courseid = '.$keyCertVal->course.' AND userid = '.$sqlSCILObjData->userid .' and timecreated <='.$sqlSCILObjData->timecompletion.' order by id desc limit 0,1';
                    $sqlSCICLObj = $DB->get_record_sql($sqlSCICL);
                    //echo $sqlSCILObjData->timecompletion;
                }
                            /*echo "3";
                            echo "\n";*/
        //echo "3";
        //  echo "\n";
                $sqlSCICL2 = 'SELECT * FROM mdl_simplecertificate_issue_change_log where courseid = '.$keyCertVal->course.' AND userid = '.$sqlSCILObjData->userid .' order by id desc limit 0,1';
                $sqlSCICLObj2 = $DB->get_record_sql($sqlSCICL2);


                if(!empty($sqlSCICLObj) || empty($sqlSCICLObj2)){
        //echo "4";
        //  echo "\n";
        //          $t = strtotime(date("d-m-Y h:i:s"));
        //$t=time();
                    $sqlSCI = 'SELECT id, userid, certificateid, timecreated, timeexpired
                              FROM {simplecertificate_issues} sci                  
                             WHERE certificateid='.$keyCertVal->id.' and userid='.$sqlSCILObjData->userid;
                    $issueCertCompNotiObjData = $DB->get_record_sql($sqlSCI);
                    if($certExpDurationDate != 0){              
                        $certExpiredDate = strtotime($certExpDurationDate." years", $sqlSCILObjData->timecompletion);
                        if($issueCertCompNotiObjData){
                            if($certExpiredDate <= $issueCertCompNotiObjData->timeexpired){

                                if($keyCertVal->id == 10){
                                    // 13 15 status need to check
                                    $sqlEU = "SELECT u.id, eu.id as euid, u.username, u.firstaccess, u.lastaccess, u.lastlogin, u.currentlogin
                                                FROM  `get_jan_expired_users` eu
                                                    JOIN mdl_user u ON u.id = eu.userid
                                                        WHERE eu.`status` =2 OR eu.status=14
                                                            AND u.id = $userid
                                                            AND u.lastaccess =0 order by eu.userid asc";
                                    $sqlRes = $DB->get_record_sql($sqlEU);
                                    if($sqlRes){
                                        
                                       continue;
                                    }
                                }                                
                            }
                        }
                    }
                    else
                        $certExpiredDate = strtotime("+1 hour", $sqlSCILObjData->timecompletion);
                     $texp = $certExpiredDate;
                   
                    if($issueCertCompNotiObjData){
                        //Update record in SCI.timecreated and SCI.timexpired based on SCIL records for timecompletion
            //echo "5";
            //echo "\n";    
                    //RCP start RECTIFICATION
                    $certExpiredDateInBetweenMonth = strtotime("-1 month", $issueCertCompNotiObjData->timeexpired); 
                    // 30 nov17 <= 15 dec17  && 30 jan18 >= 30 dec17 
                        //echo "5-A-- ".$t;     echo "\n";  
                    //echo $certExpiredDateInBetweenMonth ."<=". $t ."&&". $texp .">=". $issueCertCompNotiObjData->timeexpired;
                        //echo "\n";
                    $t = strtotime(date("d-m-Y h:i:s"));
                    // - -- - --- - - Start- Reporting Errors Case-II  Add  &&
                    if($issueCertCompNotiObjData->timeexpired != 99){ 
                        if($certExpiredDateInBetweenMonth <= $t && $texp >= $issueCertCompNotiObjData->timeexpired && $issueCertCompNotiObjData->timeexpired != 99){
                            // - -- - --- - - End- Reporting Errors Case-II
                            $update_header2 = "update mdl_simplecertificate_issue_logs set flag_update_in_sci = 1 where id = $sqlSCILObjData->id and courseid = ".$keyCertVal->course;
                            $DB->execute($update_header2);
                            $update_header = "update mdl_simplecertificate_issues set haschange=1, timeexpired = ".$texp." where id = $issueCertCompNotiObjData->id";
                            $DB->execute($update_header);

                            $update_header_log = "insert into mdl_simplecertificate_issue_logs (userid,timecompletion,courseid) values (".$sqlSCILObjData->userid.",".$sqlSCILObjData->timecompletion.",".$keyCertVal->course.")";
                            $DB->execute($update_header_log);
                            // echo "6";
                            // echo "\n";
                            $insert_header3 = "INSERT INTO mdl_simplecertificate_issue_change_log (userid, courseid, timecreated, timeexpired, sfdc_flag) VALUES (".$sqlSCILObjData->userid.",".$keyCertVal->course.",".$sqlSCILObjData->timecompletion.",".$texp.",0)";
                            $DB->execute($insert_header3);
                        }
                    }

        }
        else{
//echo "7";
//  echo "\n";
            $sqlSCI2 = 'SELECT id, userid, certificateid, timecreated, timeexpired
                  FROM {simplecertificate_issues} sci                  
                 WHERE certificateid='.$keyCertVal->id.' and userid='.$sqlSCILObjData->userid;
            $issueCertCompNotiObjData2 = $DB->get_record_sql($sqlSCI2);
            if(empty($issueCertCompNotiObjData2)){

        //Insert new record in SCI.timecreated and SCI.timexpired based on SCIL records timecompletion and timexpired
        //automatic download certificate - means we are stored certificate entry in main sci table for all future and existing which has depends on SCIL.flag 
        //echo "8";
        //  echo "\n";  

        $sqlSCILIntial = 'SELECT * FROM {simplecertificate_issue_logs} scil where  courseid = '.$keyCertVal->course.' and userid='.$sqlSCILObjData->userid.' AND timecompletion is not null order by timecompletion asc limit 0,1';
            $sqlSCILObjIntial = $DB->get_record_sql($sqlSCILIntial);

                        $insert_header = "INSERT INTO mdl_simplecertificate_issues (userid, certificateid, certificatename, status, timecreated, timeexpired) VALUES (".$sqlSCILObjData->userid.", ".$keyCertVal->id.", '".$keyCertVal->name."',0, '".$sqlSCILObjIntial->timecompletion."', '".$texp."')";
                            $DB->execute($insert_header);
        //echo "9";
            //echo "\n";
                            $insert_header2 = "INSERT INTO mdl_simplecertificate_issue_change_log (userid, courseid, timecreated, timeexpired, sfdc_flag) VALUES (".$sqlSCILObjData->userid.",".$keyCertVal->course.",'".$sqlSCILObjData->timecompletion."','".$texp."',0)";
                            $DB->execute($insert_header2);

        $update_header2 = "update mdl_simplecertificate_issue_logs set flag_update_in_sci = 1 where id = $sqlSCILObjData->id and courseid = ".$keyCertVal->course;
                $DB->execute($update_header2);

                $update_header_log = "insert into mdl_simplecertificate_issue_logs (userid,timecompletion,courseid) values (".$sqlSCILObjData->userid.",".$sqlSCILObjData->timecompletion.",".$keyCertVal->course.")";
                        $DB->execute($update_header_log);


                        }

                    }
                    
                }
            }//end foreach
        }
        }        
    }
        return 0;
    }


//59 Est Start
    function show_all_certificates() {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
        
        //Required one flag here
        $query = "SELECT id, course_sfdc_id__c, contact_sfdc_id__c from Certificate__c where contact_sfdc_id__c != '' and flag__c!='1'";
        
        $url = "$instance_url/services/data/v41.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        $response = json_decode($json_response, true);

        //echo "<pre>";print_r($response); exit("Succa1");
        return $response;
    }


function sfdc_show_certificate_details_onetime($certificate_sfdc_id) {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id,certificate_date__c,certificate_expiration_date__c,expiry__c,renewal_status__c from certificate_detail__c where certificate_sfdc_id__c='$certificate_sfdc_id'";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);

        $response = json_decode($json_response, true);
        //echo "<pre>";print_r($response); //exit("Succa1");
        //foreach ($response['records'][0] as $record) {
        if(isset($response['records'][0]['Id'])){
            return $response['records'][0];
        }
        else{
            return 0;
        }
        //}    
    }

    function show_all_exam() {
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $query = "SELECT id, cm_lms_id__c, course_sfdc_id__c, contact__c, exam_submission_modified__c from Exam__c where contact__c!='' and Certificate__c = NULL";
        
        $url = "$instance_url/services/data/v41.0/query?q=" . urlencode($query);
        $json_response = get_sfdc_curl($query, $url, $access_token);
        $response = json_decode($json_response, true);

        //echo "<pre>";print_r($response); exit("Succa1");
        return $response;
       /* foreach ((array) $response['records'] as $record) {
            return $record['Id'];
        }*/    
    }


    //New task one time function
    function sfdc_update_certlookup_in_exam_onetime($id,$sfdc_certificate_id) {
        
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Exam__c/$id";
       
        $content = json_encode(array("Certificate__c"=>$sfdc_certificate_id));
       
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//echo "<br>";
        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        //echo $status ." ExamID <br>";
        //exit;
        curl_close($curl);
        $response = json_decode($json_response, true);
        $id = $response["id"];
        return $id;
    }

     function sfdc_update_certdetails_in_cert_onetime($cert_id,$cert_date,$cert_exp_date,$expiry,$ren_status) {
        
       $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

        $url = "$instance_url/services/data/v20.0/sobjects/Certificate__c/$cert_id";
    
        if($cert_exp_date != ''){
           $content = json_encode(array("Certificate_Expiration_Date_2__c"=>$cert_exp_date,"Certification_Date_2__c"=>$cert_date,"Renewal_Status_2__c"=>$ren_status,"Expires__c"=>$expiry,"flag__c"=>'1'));
        }
        elseif($cert_exp_date == ''){
         $content = json_encode(array("certified__c"=>$certified,"Certification_Date_2__c"=>$cert_date,"Renewal_Status_2__c"=>$ren_status,"Expires__c"=>$expiry,"flag__c"=>'1'));
        }
    
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth $access_token",
                    "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 204 ) {
            die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);

        $response = json_decode($json_response, true);
        // /echo "<pre>";print_r($response); exit("Success");
        $id = $response["id"];

        return $id;
    }

function update_certificatelookup_in_exam($sfdc_certificate_id, $course_sfdc_id,$sfdc_contact_id,$show_sfdc_exam_id=NULL){
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];
        
        if($show_sfdc_exam_id != NULL){
            $id=$show_sfdc_exam_id;
        }else{
            $query = "SELECT id from Exam__c where course_sfdc_id__c='$course_sfdc_id' AND contact__c='$sfdc_contact_id'";
            $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);  
            $json_response = get_sfdc_curl($query, $url, $access_token);    
            $response = json_decode($json_response, true);   
            //echo "<pre>";print_r($response); exit("Succa1");
            foreach ((array) $response['records'] as $record) {
                $id=$record['Id'];            
            } 
        }


        if($id){
            $url = "$instance_url/services/data/v20.0/sobjects/Exam__c/$id";
            $module_exam_name = substr(htmlspecialchars($module_exam_name), 0, 75);
            if(strlen($module_exam_name) >= 75){
                $module_exam_name .= "....";
            }

            $content = json_encode(array("Certificate__c"=>$sfdc_certificate_id));
            //echo "<pre>";print_r($content); //exit("Success");

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array("Authorization: OAuth $access_token",
                        "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

            $json_response = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //echo "<br>";
            if ( $status != 204 ) {
                die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            }

            //echo $status ." ExamID <br>";
            //exit;
            curl_close($curl);

            /*$response = json_decode($json_response, true);
            $id = $response["id"];
            return $id;*/
        }
        return true;
}


//59 Est end


        //Display user detail by id
    function check_contact_id() {
    global $USER,$DB;
    // echo "<pre>";
    //           print_r($USER);
    //           print_r($_SESSION);
    //           exit("Success");
        $access_token = $_SESSION['access_token'];
        $instance_url = $_SESSION['instance_url'];

    //start - updated by lakhan for QSCID
    //$queryGetId = $DB->get_record_sql("SELECT sfdcuniqueid FROM {user} where sfdcuniqueid IS NOT NULL");
        $queryGetIdObj = $DB->get_records_sql("SELECT id,sfdcuniqueid,username FROM {user} where sfdcuniqueid is not null");

    //print_r($queryGetId); die;
        //if($useremailsfdc == $useremaillms){
        foreach ($queryGetIdObj as $queryGetId){
            if($queryGetId){
        //end   
                $query = "SELECT id from Contact where id='$queryGetId->sfdcuniqueid'";
            
                $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
                $json_response = get_sfdc_curl($query, $url, $access_token);
                
                $response = json_decode($json_response, true);
                if(!empty($response['records'])){
                    //echo "<pre> Test1"; print_r($response); exit;
                }
                else{
                    $insert_header = "INSERT INTO cron_sfdc_contact_not_exist (userid, sfdcuniqueid) VALUES (".$queryGetId->id.",'".$queryGetId->sfdcuniqueid."')";
                            $DB->execute($insert_header);
                    echo "\n"; print_r($queryGetId); echo "\n";
                }
    
           /* foreach ((array) $response['records'] as $record) {
                return $record['Id'];
            }*/
            }    
        }
    // end - qscid

return 0;
    }

    /**
     * To fix bug# 2419
     * This function returns english name of the course.
     */
    function getCourseNameInEnglish($courseId){
        $resultName = '';
        if($courseId==42){
            $resultName = 'Q-SYS Level TWO Classroom Training';
        }
        else if($courseId==67){
            $resultName = 'Q-SYS Control 201 Training (Classroom)';
        }
        return $resultName;
    }

    /** ////
     * Returns date of most recent grade given to student in exam.
     */
    function getDateOfLatestGrade($assignId='', $studentId=''){
        global $DB;
        $returnDate = '';
        if($assignId!='' && $studentId!=''){
            $lastGradeSql = "SELECT `timecreated` from {assign_grades} where `userid`=? AND `assignment`=? ORDER BY `attemptnumber` desc limit 0,1";
            $lastGradeObj = $DB->get_record_sql($lastGradeSql, array($studentId, $assignId));
            //echo "Test "; exit;
            if(!empty($lastGradeObj)){
               // $returnDate = date("m/d/Y h:i:s", $lastGradeObj->timecreated);
               $returnDate = date("Y-m-d", $lastGradeObj->timecreated);
                
            }
        }
        return $returnDate;
    }
//}
?>
