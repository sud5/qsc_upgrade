<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/quiz/locallib.php');
require_once($CFG->dirroot . '/question/editlib.php');
global $CFG, $PAGE, $USER, $DB;
$id = optional_param('cmid', 0, PARAM_INT); // Course Module ID, or ...
$q = optional_param('q',  0, PARAM_INT);  // Quiz ID.


if ($id) {
    if (!$cm = get_coursemodule_from_id('quiz', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }
} else {
    if (!$quiz = $DB->get_record('quiz', array('id' => $q))) {
        print_error('invalidquizid', 'quiz');
    }
    if (!$course = $DB->get_record('course', array('id' => $quiz->course))) {
        print_error('invalidcourseid');
    }
    if (!$cm = get_coursemodule_from_instance("quiz", $quiz->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
}
$courseId= "";
$smsg ="";
$gerror ="";
$gerror2 ="";
$gerror3 = "";
$usernoterror ="";
$usernoterrorauto ="";
$quizPassGrade = "";

$username_select = "";
$grade_input = "";
if(isset($cm)){
    $courseId= $cm->course;
}else{
    $courseId= $quiz->course;
}
$context = context_module::instance($cm->id);
$courseSection = $DB->get_record_sql("SELECT cs.name, cm.instance FROM mdl_course_modules  cm LEFT JOIN mdl_course_sections cs ON (cm.course = cs.course AND cm.section = cs.id) WHERE cm.id= '".$id."' LIMIT 0,1 ");
$courseModule ="";
$quizId ="";
if(!empty($courseSection)){
    $courseModule = $courseSection->name;
    $quizId =$courseSection->instance;;
}
require_capability('mod/quiz:view', $context);
if ($USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin') {
    redirect("/");
}
$msg = optional_param('msg', '', PARAM_TEXT);
if (!empty($_POST['submit'])) {
    
    $grade = trim($_POST['assesment_grade']);
    $quizid      = $_REQUEST['quizid'];
    $username = trim($_POST['userid']);
    $unameArr = explode("(", $username);
    $unameArr1 = explode(")", $unameArr[1]);
 
   
    $strEmail = $unameArr1[0];
    //echo "<pre>"; var_dump($strEmail); 
    if (!empty($strEmail) ) {
        $userData =$DB->get_record_sql("SELECT id FROM mdl_user WHERE username = '".$strEmail."' AND deleted != 1 LIMIT 0,1");
        if(isset($userData) && !empty($userData)){
                $userid= $userData->id;
                $quizPassGradeObj = $DB->get_record('quizaccess_passgrade', array('quizid' => $quizid), '*');
                if(!empty($quizPassGradeObj)){
                    $quizPassGrade = $quizPassGradeObj->passgrade;
                }
                if($grade < $quizPassGrade)
                {
                    $username_select = $username;
                    $grade_input = $grade;

                    if($quizPassGrade ==100)
                        $gerror ="yes";
                    else
                        $gerror2 ="yes";
                }elseif($grade > 100){
                    $username_select = $username;
                    $grade_input = $grade;
                    $gerror3 ="yes";
                }else
                {
                  $grade = number_format($grade,5);
                  $quizgradesObj = $DB->get_record('quiz_grades', array('quiz' => $quizid, 'userid' => $userid), '*');
                    if(empty($quizgradesObj)){
                        $cmdqObj = $DB->get_record('course_modules_completion', array('coursemoduleid' => $id, 'userid' => $userid), '*');
                            if(empty($cmdqObj)){
                                $datacmcq->coursemoduleid = $id;
                                $datacmcq->timemodified = time();   
                                $datacmcq->completionstate = 1;
                                $datacmcq->userid = $userid;
                                $datacmcq->viewed = 0;
                                $DB->insert_record('course_modules_completion', $datacmcq);
                                unset($datacmcq);

                                $quizGradeData = get_coursemodule_from_id('quiz', $id, 0, false, MUST_EXIST);
                                $dataqg->quiz = $quizGradeData->instance;
                                $dataqg->timemodified = time();
                                $dataqg->grade = $grade;
                                $dataqg->userid = $userid;
                                $DB->insert_record('quiz_grades', $dataqg);
                                unset($dataqg);
                                $smsg = "yes";
                            } //start for Test
                            else{
                                $quizGradeData = get_coursemodule_from_id('quiz', $id, 0, false, MUST_EXIST);
                                $dataqg->quiz = $quizGradeData->instance;
                                $dataqg->timemodified = time();
                                $dataqg->grade = $grade;
                                $dataqg->userid = $userid;
                                $DB->insert_record('quiz_grades', $dataqg);
                                unset($dataqg);
                                $smsg = "yes";
                                }
                            
                            //End for Test
                    }else{
                        $quiz_grade_id = $quizgradesObj->id; 
                        $quiz_id = $quizgradesObj->quiz; 
                        $timemodified = time();
                           $responce = $DB->execute("UPDATE mdl_quiz_grades SET grade ='".$grade."', timemodified = '".$timemodified."' WHERE id= '".$quiz_grade_id."'");
                            $cmdqObj2 = $DB->get_record('course_modules_completion', array('coursemoduleid' => $id, 'userid' => $userid), '*');
                            if(empty($cmdqObj2)){
                                $datacmcq->coursemoduleid = $id;
                                $datacmcq->timemodified = time();   
                                $datacmcq->completionstate = 1;
                                $datacmcq->userid = $userid;
                                $datacmcq->viewed = 0;
                                $DB->insert_record('course_modules_completion', $datacmcq);
                                unset($datacmcq);

                                //$DB->set_debug(true);
                                $quizAttemptsObj = $DB->get_record('quiz_attempts', array('quiz' => $quiz_id, 'userid' => $userid), '*');
                                if(!empty($quizAttemptsObj)){
                                    $DB->execute("UPDATE mdl_quiz_attempts SET state ='finished', timemodified = '".$timemodified."', sumgrades= '".$grade."' WHERE id= '".$quizAttemptsObj->id."'");
                                }
                                //$DB->set_debug(false);
                            }else{
                                //$DB->set_debug(true);
                                $DB->execute("UPDATE mdl_course_modules_completion SET completionstate ='1', timemodified = '".$timemodified."' WHERE id= '".$cmdqObj2->id."'");
                                $quizAttemptsObj = $DB->get_record('quiz_attempts', array('quiz' => $quiz_id, 'userid' => $userid), '*');
                                if(!empty($quizAttemptsObj)){
                                    $DB->execute("UPDATE mdl_quiz_attempts SET state ='finished', timemodified = '".$timemodified."', sumgrades= '".$grade."' WHERE id= '".$quizAttemptsObj->id."'");
                                }
                                //$DB->set_debug(false);
                            }
                        $smsg = "yes";
                    }
                }
    } else{
        $usernoterror ="yes";
    }
}else{
    $usernoterrorauto ="yes";
}
}

$url = new moodle_url('/mod/quiz/manuallypass.php', array('cmid' => $id));  

     //$users = $DB->get_records_sql("SELECT u.id, u.firstname, u.lastname, u.username FROM {user} u where u.deleted=0 and u.firstname!='' ORDER BY u.firstname, u.lastname");


$PAGE->set_context(context_system::instance());
//$PAGE->set_pagelayout('admin');
$PAGE->set_pagelayout('admin');
//$PAGE->set_pagetype('mod-quiz-edit');
$PAGE->set_title('Manually Pass Assessment');
$PAGE->set_heading('Manually Pass Assessment');
list($thispageurl, $contexts, $cmid, $cm, $quiz, $pagevars) =  question_edit_setup('editq', '/mod/quiz/manuallypass.php', true);

//print_r($thispageurl);
$PAGE->set_url($thispageurl);
echo $OUTPUT->header();
$pageHeading = "Manually Pass Assessment: ".$courseModule;
echo '<div class="coursegroupsettingsform clearfix">';
echo $OUTPUT->heading($pageHeading);
    if(!empty($smsg) && ($smsg =="yes")){
        echo $OUTPUT->notification("Student assessment passed successfully.", "notifysuccess");
    }
    if(!empty($gerror) && ($gerror =="yes")){
        echo $OUTPUT->notification("Assessment Passing Grade should be 100, please change the assigned grade.");
    }if(!empty($gerror2) && ($gerror2 =="yes")){
        echo $OUTPUT->notification("Assessment Passing Grade should be more than or equal to ".$quizPassGrade.", please change the assigned grade.");
    }if(!empty($gerror3) && ($gerror3 =="yes")){
        echo $OUTPUT->notification("Assessment Passing Grade should not be more than 100, please change the assigned grade.");
    }
    if(!empty($usernoterror) && ($usernoterror =="yes")){
        echo $OUTPUT->notification("Student not exist /deleted in our system.");;
    }
    if(!empty($usernoterrorauto) && ($usernoterrorauto =="yes")){
        echo $OUTPUT->notification("Student not select in autocomplete list.");;
    }
?>
<style type="text/css">
    .buttonCenter{
        margin-left: 20%;
    }
    .alert {
        padding: 12px 20px 12px 20px;
    }
    #cgform label
    {
        cursor: initial !important;
    }
    .coursegroupsettingsform input[type="text"], .coursegroupsettingsform select {
        font-size: 12px;
        height: 30px;
        box-sizing: border-box;
        width: 50% !important;
    }
    .yui3-aclist-content
    {
        height:400px;
        width:147% !important;
        overflow-y: scroll;
    }
    #id_grade{
        margin-left:5px;
    }
</style>
<form method="post" action="" id='cgform'>

    <div class="coursegroupsettingsform clearfix">
        <?php
            if (!empty($msg)) {
                echo $OUTPUT->notification(".");
            }
        ?>
        <fieldset>
            <div class="clearer"><!-- --></div>
            <div class="form-item clearfix">
                <div class="form-label">
                    <label>User Name <span class="helptooltip2" ><a href="Javascript:void(0);" title="Please type enrolled user name or email address." ><img src="<?= $CFG->wwwroot;?>/theme/image.php/meline29/core/1560147926/help" alt="Help Assessment Grade" class="iconhelp" ></a></span><span style="color: red;">*</span></label>
                </div>              
                <div class="form-setting">
                <div class="form-text defaultsnext">
                    <input type="hidden" size="35" name="quizid" value="<?= $quizId; ?>"> 
                    <input type="text" size="35" style="width:80%; margin-left:5px;" value="<?= $username_select;?>"  autocomplete="off"  id="id_userid"  name="userid" required="required" placeholder="Auto Search by User's Name/Email.">
                </div>
              </div>             
            </div>
            <div class="form-item clearfix">
                <div class="form-label">
                    <label >Assessment Grade <span class="helptooltip2" ><a href="Javascript:void(0);" title="Please assign Grade from 1 to 100 as per Grade to pass setting." ><img src="<?= $CFG->wwwroot;?>/theme/image.php/meline29/core/1560147926/help" alt="Help Assessment Grade" class="iconhelp" ></a></span><span style="color: red;">*</span></label>
                </div>              
                <div class="form-setting">
                <div class="form-text ">
                    <input type="text" size="10"  id="id_grade" value="<?= $grade_input; ?>" onkeypress="return isNumberKey(event)" name="assesment_grade" autocomplete="off"  required="required" placeholder="Passing Grade">
                    <span id="error_num" style="display: none; color:red;"> <b> &nbsp; Only numeric value allowed. </b></span>
                </div>
              </div>            
            </div>          
        </fieldset>
        <div class="form-buttons">        
            <div class="buttonCenter"><input class="form-submit" type="submit" name="submit" value="Submit"></div>
        </div>
    </div>
</form>
</div>
<?php
echo $OUTPUT->footer();
?>
<script>
YUI().use('array-extras', 'autocomplete', 'autocomplete-filters', 'autocomplete-highlighters', function (Y) {
  function locateModules(response) {
    var results = (response && response.data && response.data.results) || [];

    return results;
  }
    var loader = new Y.Loader();
  //console.log(locateModules); after 2 character auto complete list with ajax JSON Limit 100. 
  Y.one('#id_userid').plug(Y.Plugin.AutoComplete, {
    resultHighlighter: 'phraseMatch',
    resultListLocator: locateModules,
    minQueryLength: 2,
    alwaysShowContainer :true,
    maxResultsDisplayed :20,
    doBeforeLoadData:loader,
    maxCacheEntries:500,
    resultTextLocator: 'name',
    source: '<?= $CFG->wwwroot; ?>/mod/quiz/getUserList.php?q={query}&course=<?= $courseId; ?>&count=100&format=json',
    render: true,
        on : {
                select : function(e) {
                    var txttag = e.result.text;
                    var quizid = "<?= $quizId ?>";
                        $.ajax({
                                url: "<?= $CFG->wwwroot; ?>/mod/quiz/get_assessmentgrade_ajax.php?quizid="+quizid+"&gname="+txttag,
                                method: 'POST',
                                beforeSend: function(){   $('#id_grade').val('Checking associated grade for this user (if any)...');},
                                success: function(data){     
                                    if(data){               
                                        $('#id_grade').val(data);
                                    }
                                    else{
                                        $('#id_grade').val("");
                                        $("#id_grade").attr("placeholder", "Please enter grade.");                                        
                                    }
                                }
                            });
                        }
                    }
  });

  Y.on('scroll', function(e) {     
     console.log(window.scrollY);
});
});

 function isNumberKey(evt)
       {
          var x = document.getElementById("error_num");
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57)){
                x.style.display = "block";
                return false;
            }else{
                 x.style.display = "none";
                return true;
            }

          
       }
</script>