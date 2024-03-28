<?php
require_once('../../config.php');
global $CFG, $PAGE, $USER, $DB;
require_once($CFG->libdir.'/tablelib.php');
require_once("$CFG->libdir/formslib.php");

class filter_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB,$PAGE,$COURSE;
        $mform = $this->_form; // Don't forget the underscore! 
        $cid = $this->_customdata['cid'];
        $userid = $this->_customdata['userid'];
        //
        $mform->addElement('header', 'general', 'Filters');
        // student name
        
        $mform->addElement('text', 'sname', 'Student First Name', array('size'=>'35'));
        //
        $mform->setType('sname', PARAM_TEXT);
        //
        $mform->addElement('text', 'institution', 'Company', array('size'=>'35'));
        //status
        $mform->addElement('select', 'status', 'Status', array('0'=>'---Select---','submitted'=>'Submitted for grading','reopened'=>'Revisions requested by Instructor'));
        //
       //$mform->addRule('sname', null, 'required', null, 'client');
        //$mform->addRule('sname', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        //course name
        $courses = $DB->get_records('course');
        $cdata = array();
        foreach($courses as $course){
            $cdata[0] = '---Select Course---';
            $cdata[$course->id] = $course->fullname;
        }
        
        //
        //grader name
        $sql = "SELECT id FROM {role} WHERE shortname='grader'";
        $roleobj = $DB->get_record_sql($sql);
        $rsql = "SELECT userid FROM {role_assignments} where  roleid=".$roleobj->id."
                GROUP BY userid";
        $graderusers  = $DB->get_records_sql($rsql);
        $grader = array();
        foreach($graderusers as $users){
            $grader[0] = '---Select Grader---';
            $grader[2] = 'Adminstrator';
           $grader[$users->userid] = ucwords($DB->get_field('user','firstname',['id'=>$users->userid]).' '.$DB->get_field('user','lastname',['id'=>$users->userid]));
        }
        //
        if(is_siteadmin($USER)){
            $mform->addElement('select','grader','Grader Name',$grader);
        }
        
        //
        $mform->addElement('select','course','Course Name',$cdata);
        //
        $mform->addElement('date_selector', 'submission', 'Submission Date',array('startyear' => 2005, 'stopyear'  => 2040,'timezone'  => 99,'optional'  => true));
        $this->add_action_buttons('true','Add Filter'); 
        $mform->setExpanded('general', false); 
        //$mform->closeHeader('fgroup_id_buttonar');//fgroup_id_buttonar
        /*$mform->addElement('hidden','closeheader','Course Name');
        $mform->closeHeaderBefore('closeheader');*/
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}