<?php
require_once('../../config.php');
global $CFG, $PAGE, $USER, $DB;
require_once($CFG->libdir.'/tablelib.php');
require_once("$CFG->libdir/formslib.php");

class edit_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB,$PAGE,$COURSE;
        $mform = $this->_form; // Don't forget the underscore! 
        $cid = $this->_customdata['cid'];
        $userid = $this->_customdata['userid'];
        //Select Speed text count
        $id = optional_param('id', '0', PARAM_INT);
        $STdata = $DB->get_record_sql("SELECT id, button_lable, comment_text,course_id  FROM {speed_text} WHERE id='" . $id . "'   LIMIT 0,1");
        $cancel_url = $CFG->wwwroot."/local/assign/speedtext.php?cid=".$STdata->course_id;
        $html = '<div class="coursegroupsettingsform_edit clearfix">
					<fieldset>
						<div class="clearer"><!-- --></div>
						<div class="form-item clearfix">
						  <div class="form-label">
						    <label>Button Label</label>
						  </div>			  
						  <div class="form-setting">
						  	<div class="form-text defaultsnext">
						  		<input type="hidden" name="id" value="'.$STdata->id.'">
						  		<input type="text" size="35" name="label_name" id="id_label_name" value="'. $STdata->button_lable.'" autocomplete="off"  placeholder="Please enter Button label">
						  	</div>
			                
						  </div>
			              <span id="errMessage" class="ferror_edit" ></span>			  
						</div>
			            <div class="form-item clearfix">
			              <div class="form-label">
			                <label>Comment Text </label>
			              </div>              
			              <div class="form-setting">
			                <div class="form-text defaultsnext">
			                    <textarea class="scomment" name="comment_text" id="id_comment_text"  cols="70" rows=11>"'.$STdata->comment_text.'"</textarea>
			                </div>
			                
			              </div>
			              <span id="errMessage2" class="ferror" ></span>              
			            </div>			
					</fieldset>
					<div class="form-buttons">
						<div class="buttonCenter_edit"><input class="form-submit" type="submit" id="btnDelete" name="edit" onclick="return validateForm()" value="Update"><a href="'.$cancel_url.'"><input class="form-submit" type="button"  Value ="Cancel"/> </a></div>
					</div>
        
	             </div> ';
        

        $mform->addElement('html', $html); // Add elements to your form.
        //$this->add_action_buttons('true','Submit');  
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}