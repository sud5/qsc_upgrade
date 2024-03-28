<?php
require_once('../../config.php');
global $CFG, $PAGE, $USER, $DB;
require_once($CFG->libdir.'/tablelib.php');
require_once("$CFG->libdir/formslib.php");

class speedtext_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB,$PAGE,$COURSE;
        $mform = $this->_form; // Don't forget the underscore! 
        $cid = $this->_customdata['cid'];
        $userid = $this->_customdata['userid'];
        //Select Speed text count
        $course_id = optional_param('cid', '0', PARAM_INT);
        $course =  $DB->get_record('course', array('id'=>$course_id), '*', MUST_EXIST);
		//$stext_sql= $DB->get_records_sql("SELECT * FROM {speed_text} WHERE user_id='".$USER->id."' AND course_id= '".$course_id."'   ORDER BY id");
        $stext_sql = $DB->get_records('speed_text',['user_id'=>$USER->id, 'course_id'=>$course_id]);
		$Stotalcount = count($stext_sql);
		$speedListOption ="<option Value=''> Select No of Speed Text </option>";
        //Task #2062
	    for($i=1;$i < (21 - $Stotalcount);$i++) {
	        	$speedListOption.="<option>".$i."</option>";
	        }
        $html = '<div class="coursegroupsettingsform clearfix">
					<fieldset>
						<div class="clearer"><!-- --></div>
						<div class="form-item clearfix">
							 <div class="form-label">
						    <label>Number of Speed Text <span style="color:red;">*</span></label>
						    </div>			  
						  <div class="form-setting">
						  	<div class="form-text">
						  		<div class="dropdownV">
							  		<select name="cgroupid" id="countSpeedText" >
							  			'.$speedListOption.'
							  		</select>
						  		</div>
						  	</div>
			                <!-- Task #2062 -->
			                <p> Limit up to 20 </p>	  
						</div>
						<span id="errMessage" ></span> 
						<input type="hidden" name="cid" value="'.$cid.'">
						<input type="hidden" name="userid" value="'.$userid.'"> 			  
						</div>
						<div id="Append_input">
						</div>			
					</fieldset>
				</div>';

        $mform->addElement('html', $html); // Add elements to your form.
        //$this->add_action_buttons('true','Submit');
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}