<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Copyright (C) 2007-2011 Catalyst IT (http://www.catalyst.net.nz)
 * Copyright (C) 2011-2013 Totara LMS (http://www.totaralms.com)
 * Copyright (C) 2014 onwards Catalyst IT (http://www.catalyst-eu.net)
 *
 * @package    mod
 * @subpackage facetoface
 * @copyright  2014 onwards Catalyst IT <http://www.catalyst-eu.net>
 * @author     Stacey Walker <stacey@catalyst-eu.net>
 * @author     Alastair Munro <alastair.munro@totaralms.com>
 * @author     Aaron Barnes <aaron.barnes@totaralms.com>
 * @author     Francois Marier <francois@catalyst.net.nz>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/facetoface/lib.php');

class mod_facetoface_mod_form extends moodleform_mod {

    public function definition() {
        global $CFG;

        $mform =& $this->_form;

        // General.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));

        $this->standard_intro_elements();

        $mform->addElement('text', 'thirdparty', get_string('thirdpartyemailaddress', 'facetoface'), array('size' => '64'));
        $mform->setType('thirdparty', PARAM_NOTAGS);
        $mform->addHelpButton('thirdparty', 'thirdpartyemailaddress', 'facetoface');

        $mform->addElement('checkbox', 'thirdpartywaitlist', get_string('thirdpartywaitlist', 'facetoface'));
        $mform->addHelpButton('thirdpartywaitlist', 'thirdpartywaitlist', 'facetoface');

        $display = array();
        for ($i = 0; $i <= 18; $i += 2) {
            $display[$i] = $i;
        }
        $mform->addElement('select', 'display', get_string('sessionsoncoursepage', 'facetoface'), $display);
        $mform->setDefault('display', 6);
        $mform->addHelpButton('display', 'sessionsoncoursepage', 'facetoface');

        $mform->addElement('checkbox', 'approvalreqd', get_string('approvalreqd', 'facetoface'));
        $mform->addHelpButton('approvalreqd', 'approvalreqd', 'facetoface');

        if (has_capability('mod/facetoface:configurecancellation', $this->context)) {
            $mform->addElement('advcheckbox', 'allowcancellationsdefault', get_string('allowcancellationsdefault', 'facetoface'));
            $mform->setDefault('allowcancellationsdefault', 1);
            $mform->addHelpButton('allowcancellationsdefault', 'allowcancellationsdefault', 'facetoface');
        }

        if (has_capability('mod/facetoface:configurecancellation', $this->context)) {
            $mform->addElement('advcheckbox', 'allowcancellationsdefault', get_string('allowcancellationsdefault', 'facetoface'));
            $mform->setDefault('allowcancellationsdefault', 1);
            $mform->addHelpButton('allowcancellationsdefault', 'allowcancellationsdefault', 'facetoface');
        }

//seo settings start
	$mform->addElement('textarea','meta_keywords', get_string('metakeywords'), null);
        $mform->addHelpButton('meta_keywords', 'metakeywords');
        $mform->setType('meta_keywords', PARAM_RAW);
//seo settings end

//seo settings start
	$mform->addElement('textarea','meta_descriptions', get_string('metadescriptions'), null);
        $mform->addHelpButton('meta_descriptions', 'metadescriptions');
        $mform->setType('meta_descriptions', PARAM_RAW);
//seo settings end


        $mform->addElement('header', 'calendaroptions', get_string('calendaroptions', 'facetoface'));

        $calendaroptions = array(
            F2F_CAL_NONE   => get_string('none'),
            F2F_CAL_COURSE => get_string('course'),
            F2F_CAL_SITE   => get_string('site')
        );
        $mform->addElement('select', 'showoncalendar', get_string('showoncalendar', 'facetoface'), $calendaroptions);
        $mform->setDefault('showoncalendar', F2F_CAL_COURSE);
        $mform->addHelpButton('showoncalendar', 'showoncalendar', 'facetoface');

        $mform->addElement('advcheckbox', 'usercalentry', get_string('usercalentry', 'facetoface'));
        $mform->setDefault('usercalentry', true);
        $mform->addHelpButton('usercalentry', 'usercalentry', 'facetoface');

        $mform->addElement('text', 'shortname', get_string('shortname'), array('size' => 32, 'maxlength' => 32));
        $mform->setType('shortname', PARAM_TEXT);
        $mform->addHelpButton('shortname', 'shortname', 'facetoface');
        $mform->addRule('shortname', null, 'maxlength', 32);

        // Request message.
        $mform->addElement('header', 'request', get_string('requestmessage', 'facetoface'));
        $mform->addHelpButton('request', 'requestmessage', 'facetoface');

        $mform->addElement('text', 'requestsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('requestsubject', PARAM_TEXT);
        $mform->setDefault('requestsubject', get_string('setting:defaultrequestsubjectdefault', 'facetoface'));
        $mform->disabledIf('requestsubject', 'approvalreqd');

        $mform->addElement('textarea', 'requestmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('requestmessage', get_string('setting:defaultrequestmessagedefault', 'facetoface'));
        $mform->disabledIf('requestmessage', 'approvalreqd');

        $mform->addElement('textarea', 'requestinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('requestinstrmngr', get_string('setting:defaultrequestinstrmngrdefault', 'facetoface'));
        $mform->disabledIf('requestinstrmngr', 'approvalreqd');

         // ----- customization_Naveen_Start --------//
        // Session Confirm Roster Message for Manager/instructor.
        $mform->addElement('header', 'resconfirmroster', get_string('resconfirmrostermessage', 'facetoface'));
        $mform->addHelpButton('resconfirmroster', 'resconfirmrostermessage', 'facetoface');

        $mform->addElement('text', 'resconfirmrostersubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('resconfirmrostersubject', PARAM_TEXT);
        $mform->setDefault('resconfirmrostersubject', get_string('setting:defaultresconfirmrostersubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'resconfirmrostermsg', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('resconfirmrostermsg', get_string('setting:defaultresconfirmrostermessagedefault', 'facetoface'));
        // ----- customization_Naveen_End --------//

        // Session Cancelled by Admin message.
        $mform->addElement('header', 'rescancelbyadmin', get_string('rescancelbyadminmessage', 'facetoface'));
        $mform->addHelpButton('rescancelbyadmin', 'rescancelbyadminmessage', 'facetoface');

        $mform->addElement('text', 'rescancelbyadminsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('rescancelbyadminsubject', PARAM_TEXT);
        $mform->setDefault('rescancelbyadminsubject', get_string('setting:defaultrescancelbyadminsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'rescancelbyadminmsg', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('rescancelbyadminmsg', get_string('setting:defaultrescancelbyadminmessagedefault', 'facetoface'));

        // Clear waitlistmail by Admin message. US #6665
        $mform->addElement('header', 'clearwaitlist', get_string('clearwaitlistadminmessage', 'facetoface'));
        $mform->addHelpButton('rescancelbyadmin', 'clearwaitlistadminmessage', 'facetoface');

        $mform->addElement('text', 'clearwaitlistsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('clearwaitlistsubject', PARAM_TEXT);
        $mform->setDefault('clearwaitlistsubject', get_string('setting:defaultclearwaitlistsubject', 'facetoface'));

        $mform->addElement('textarea', 'clearwaitlistmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('clearwaitlistmessage', get_string('setting:defaultclearwaitlistmessage', 'facetoface'));
        

        // Local Training message.
        $mform->addElement('header', 'localtraining', get_string('localtrainingmessage', 'facetoface'));
        $mform->addHelpButton('localtraining', 'localtrainingmessage', 'facetoface');

        $mform->addElement('text', 'confirmationloctrnsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('confirmationloctrnsubject', PARAM_TEXT);
        $mform->setDefault('confirmationloctrnsubject', get_string('setting:defaultconfirmationsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'confirmationloctrnmsg', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('confirmationloctrnmsg', get_string('setting:defaultconfirmationmessagedefault', 'facetoface'));

        $mform->addElement('textarea', 'confirmationloctrnmgr', get_string('email:instrloctrnmngr', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('confirmationloctrnmgr', get_string('setting:defaultconfirmationinstrmngrdefault', 'facetoface'));

        // Confirmation message.
        $mform->addElement('header', 'confirmation', get_string('confirmationmessage', 'facetoface'));
        $mform->addHelpButton('confirmation', 'confirmationmessage', 'facetoface');

        $mform->addElement('text', 'confirmationsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('confirmationsubject', PARAM_TEXT);
        $mform->setDefault('confirmationsubject', get_string('setting:defaultconfirmationsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'confirmationmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('confirmationmessage', get_string('setting:defaultconfirmationmessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagerconfirmation', get_string('emailmanager', 'facetoface'));
        $mform->addHelpButton('emailmanagerconfirmation', 'emailmanagerconfirmation', 'facetoface');

        $mform->addElement('textarea', 'confirmationinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->addHelpButton('confirmationinstrmngr', 'confirmationinstrmngr', 'facetoface');
        $mform->disabledIf('confirmationinstrmngr', 'emailmanagerconfirmation');
        $mform->setDefault('confirmationinstrmngr', get_string('setting:defaultconfirmationinstrmngrdefault', 'facetoface'));

        // Added by Priya for US #6050

        // Template 1 --> 3 weeks before Reserved and prerequisite not certified Confirmation Message.
        $mform->addElement('header', 'confirmation', get_string('prerequisiterequired', 'facetoface'));
        $mform->addHelpButton('prerequisiterequired', 'prerequisiterequired', 'facetoface');

        $mform->addElement('text', 'prereqconfirmsub', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('prereqconfirmsub', PARAM_TEXT);
       
        $mform->addElement('textarea', 'prerequisiterequired', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->addElement('checkbox', 'prereqconfrmflag', get_string('emailuser', 'facetoface'));

        $mform->addHelpButton('prereqconfrmflag', 'prereqconfrmflag', 'facetoface');

        $mform->disabledIf('prerequisiterequired', 'prereqconfrmflag');

        // END of 3 weeks before Reserved and prerequisite not certified Confirmation Message.

    
        // Template 2 --> 3 weeks before waitlisted and prerequisite not certified Confirmation Message.
        $mform->addElement('header', 'confirmation', get_string('prerequisitewaitlisted', 'facetoface'));
        
        $mform->addHelpButton('prerequisitewaitlisted', 'prerequisitewaitlisted', 'facetoface');

        $mform->addElement('text', 'prewaitconfrmsub', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('prewaitconfrmsub', PARAM_TEXT);

        $mform->addElement('textarea', 'prerequisitewaitlisted', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');

        $mform->addElement('checkbox', 'waitlistedconfrmflag', get_string('emailuser', 'facetoface'));

        $mform->addHelpButton('waitlistedconfrmflag', 'waitlistedconfrmflag', 'facetoface');

        $mform->disabledIf('prerequisitewaitlisted', 'waitlistedconfrmflag');
            
        // END of 3 weeks before waitlisted and prerequisite not certified Confirmation Message.

        // Template 3 --> E-mail to any managers for both reserved seats and waitlisted.
        
        $mform->addElement('header', 'confirmation', get_string('managerprerequisitemessage', 'facetoface'));
        
        $mform->addHelpButton('managerprerequisitemessage', 'managerprerequisitemessage', 'facetoface');

        $mform->addElement('text', 'mgrpreconfsub', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('mgrpreconfsub', PARAM_TEXT);

        $mform->addElement('textarea', 'managerprerequisitemessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');

        $mform->addElement('checkbox', 'mgrprereqconfrmflag', get_string('emailmanager', 'facetoface'));

        $mform->addHelpButton('mgrprereqconfrmflag', 'mgrprereqconfrmflag', 'facetoface');

        $mform->disabledIf('managerprerequisitemessage', 'mgrprereqconfrmflag');
            
        // END of E-mail to any managers for both reserved seats and waitlisted.

        // Template 4 --> 2 weeks before WARNING – Prerequisites incomplete Message.
        $mform->addElement('header', 'confirmation', get_string('warningmessage', 'facetoface'));
        
        $mform->addHelpButton('warningmessage', 'warningmessage', 'facetoface');

        $mform->addElement('text', 'warningmessagesubject', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('warningmessagesubject', PARAM_TEXT);

        $mform->addElement('textarea', 'warningmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');          

        $mform->addElement('checkbox', 'warningmsgconfrmflag', get_string('emailuser', 'facetoface'));

        $mform->addHelpButton('warningmsgconfrmflag', 'warningmsgconfrmflag', 'facetoface');

        $mform->disabledIf('warningmessage', 'warningmsgconfrmflag');

        // END of 2 weeks before WARNING – Prerequisites incomplete Message.


        // Template 5 --> 2 weeks before WARNING – Prerequisites incomplete Message to manager.
        $mform->addElement('header', 'confirmation', get_string('managerwarningmessage', 'facetoface'));
        
        $mform->addHelpButton('managerwarningmessage', 'managerwarningmessage', 'facetoface');

        $mform->addElement('text', 'managerwarningmessagesubject', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('managerwarningmessagesubject', PARAM_TEXT);

        $mform->addElement('textarea', 'managerwarningmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');          

        $mform->addElement('checkbox', 'mgrwarningmsgconfrmflag', get_string('emailmanager', 'facetoface'));

        $mform->addHelpButton('mgrwarningmsgconfrmflag', 'mgrwarningmsgconfrmflag', 'facetoface');

        $mform->disabledIf('managerwarningmessage', 'mgrwarningmsgconfrmflag');

        // END of 2 weeks before WARNING – Prerequisites incomplete Message to manager.

        // Template 6 --> less than 2 weeks before WARNING – Prerequisites incomplete Message.
        $mform->addElement('header', 'confirmation', get_string('msglessthantwoweeks', 'facetoface'));
        
        $mform->addHelpButton('msglessthantwoweeks', 'msglessthantwoweeks', 'facetoface');

        $mform->addElement('text', 'lessthantwoweekssubject', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('lessthantwoweekssubject', PARAM_TEXT);

        $mform->addElement('textarea', 'msglessthantwoweeks', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');          

        $mform->addElement('checkbox', 'lessthantwoweeksconfrmflag', get_string('emailuser', 'facetoface'));

        $mform->addHelpButton('lessthantwoweeksconfrmflag', 'lessthantwoweeksconfrmflag', 'facetoface');

        $mform->disabledIf('msglessthantwoweeks', 'lessthantwoweeksconfrmflag');

        // END of less than 2 weeks before WARNING – Prerequisites incomplete Message.


        // Template 7 --> Less than 2 weeks before WARNING – Prerequisites incomplete Message to manager.
        $mform->addElement('header', 'confirmation', get_string('mgrmsglessthantwoweeks', 'facetoface'));
        
        $mform->addHelpButton('mgrmsglessthantwoweeks', 'mgrmsglessthantwoweeks', 'facetoface');

        $mform->addElement('text', 'mgrlessthantwoweekssubject', get_string('email:subject', 'facetoface'), array('size' => '55'));

        $mform->setType('mgrlessthantwoweekssubject', PARAM_TEXT);

        $mform->addElement('textarea', 'mgrmsglessthantwoweeks', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');          

        $mform->addElement('checkbox', 'mgrlessthantwoweeksconfrmflag', get_string('emailmanager', 'facetoface'));

        $mform->addHelpButton('mgrlessthantwoweeksconfrmflag', 'mgrlessthantwoweeksconfrmflag', 'facetoface');

        $mform->disabledIf('mgrmsglessthantwoweeks', 'mgrlessthantwoweeksconfrmflag');

        // END of Less than 2 weeks before WARNING – Prerequisites incomplete Message to manager.


        // END of Added by Priya for US #6050


        // ----- customization_Sameer_Start --------//
        //For Automated email Template 24 hours after the end of training session email sent to the attended students
        $mform->addElement('header', 'attendedautomatedemail', get_string('attendedautomatedemail', 'facetoface'));
        $mform->addHelpButton('resconfirmroster', 'attendedautomatedemailmsg', 'facetoface');

        $mform->addElement('text', 'attendedautomatedemailsub', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('attendedautomatedemailsub', PARAM_TEXT);
        $mform->setDefault('attendedautomatedemailsub', get_string('setting:defaultattendedautomatedemailsubdefault', 'facetoface'));

        $mform->addElement('textarea', 'attendedautomatedemailmsg', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('attendedautomatedemailmsg', get_string('setting:defaultattendedautomatedemailmsgdefault', 'facetoface'));
        // ----- customization_Sameer_End --------//

        //For Automated email Template about to inform to the students on the session cancellation
        //Task 2061 start
        $mform->addElement('header', 'notattendedautomatedemail', get_string('notattendedautomatedemail', 'facetoface'));
        $mform->addHelpButton('resconfirmroster', 'notattendedautomatedemailmsg', 'facetoface');

        $mform->addElement('text', 'notattendedautomatedemailsub', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('notattendedautomatedemailsub', PARAM_TEXT);
        $mform->setDefault('notattendedautomatedemailsub', get_string('setting:defaultnotattendedautomatedemailsubdefault', 'facetoface'));

        $mform->addElement('textarea', 'notattendedautomatedemailmsg', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('notattendedautomatedemailmsg', get_string('setting:defaultnotattendedautomatedemailmsgdefault', 'facetoface'));
        //Task 2061 end
        // ----- customization_Sameer_End --------//

        // Reminder message.
        $mform->addElement('header', 'reminder', get_string('remindermessage', 'facetoface'));
        $mform->addHelpButton('reminder', 'remindermessage', 'facetoface');

        $mform->addElement('text', 'remindersubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('remindersubject', PARAM_TEXT);
        $mform->setDefault('remindersubject', get_string('setting:defaultremindersubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'remindermessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('remindermessage', get_string('setting:defaultremindermessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagerreminder', get_string('emailmanager', 'facetoface'));
        $mform->addHelpButton('emailmanagerreminder', 'emailmanagerreminder', 'facetoface');

        $mform->addElement('textarea', 'reminderinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="4" cols="150"');
        $mform->addHelpButton('reminderinstrmngr', 'reminderinstrmngr', 'facetoface');
        $mform->disabledIf('reminderinstrmngr', 'emailmanagerreminder');
        $mform->setDefault('reminderinstrmngr', get_string('setting:defaultreminderinstrmngrdefault', 'facetoface'));

        $reminderperiod = array();
        for ($i = 1; $i <= 20; $i += 1) {
            $reminderperiod[$i] = $i;
        }
        $mform->addElement('select', 'reminderperiod', get_string('reminderperiod', 'facetoface'), $reminderperiod);
        $mform->setDefault('reminderperiod', 2);
        $mform->addHelpButton('reminderperiod', 'reminderperiod', 'facetoface');

      // Prerequisite message. *************
        //US #6050 start
        $mform->addElement('header', 'prerequisite', get_string('prerequisitemessage', 'facetoface'));
        $mform->addHelpButton('prerequisite', 'prerequisitemessage', 'facetoface');

        $mform->addElement('text', 'prerequisitesubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('prerequisitesubject', PARAM_TEXT);
         $mform->disabledIf('prerequisitesubject', 'emailmanagerlearnerprerequisite');
        $mform->setDefault('prerequisitesubject', get_string('setting:defaultprerequisitesubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'prerequisitemessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
         $mform->disabledIf('prerequisitemessage', 'emailmanagerlearnerprerequisite');
        $mform->setDefault('prerequisitemessage', get_string('setting:defaultprerequisitemessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagerlearnerprerequisite', get_string('emailmanagerlearner', 'facetoface'));
        $mform->addHelpButton('emailmanagerlearnerprerequisite', 'emailmanagerlearnerprerequisite', 'facetoface');

        $mform->addElement('textarea', 'prerequisiteinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="4" cols="150"');
        $mform->addHelpButton('prerequisiteinstrmngr', 'prerequisiteinstrmngr', 'facetoface');
        $mform->disabledIf('prerequisiteinstrmngr', 'emailmanagerlearnerprerequisite');
        $mform->setDefault('prerequisiteinstrmngr', get_string('setting:defaultprerequisiteinstrmngrdefault', 'facetoface'));
        //US #6050 end

        // Waitlisted message.
        $mform->addElement('header', 'waitlisted', get_string('waitlistedmessage', 'facetoface'));
        $mform->addHelpButton('waitlisted', 'waitlistedmessage', 'facetoface');

        $mform->addElement('text', 'waitlistedsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('waitlistedsubject', PARAM_TEXT);
        $mform->setDefault('waitlistedsubject', get_string('setting:defaultwaitlistedsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'waitlistedmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('waitlistedmessage', get_string('setting:defaultwaitlistedmessagedefault', 'facetoface'));

	//Classroom feature start
        // Automatically enrolled from the Wailisted message.
        $mform->addElement('header', 'confirmfromwaitlisted', get_string('confirmfromwaitlistedmessage', 'facetoface'));
        $mform->addHelpButton('confirmfromwaitlisted', 'confirmfromwaitlistedmessage', 'facetoface');

        $mform->addElement('text', 'confirmfromwaitlistedsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('confirmfromwaitlistedsubject', PARAM_TEXT);
        $mform->addElement('textarea', 'confirmfromwaitlistedmsg', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        //Classroom feature end

        // Cancellation message.
        $mform->addElement('header', 'cancellation', get_string('cancellationmessage', 'facetoface'));
        $mform->addHelpButton('cancellation', 'cancellationmessage', 'facetoface');

        $mform->addElement('text', 'cancellationsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('cancellationsubject', PARAM_TEXT);
        $mform->setDefault('cancellationsubject', get_string('setting:defaultcancellationsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'cancellationmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="10" cols="150"');
        $mform->setDefault('cancellationmessage', get_string('setting:defaultcancellationmessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagercancellation', get_string('emailmanager', 'facetoface'));
        $mform->addHelpButton('emailmanagercancellation', 'emailmanagercancellation', 'facetoface');

        $mform->addElement('textarea', 'cancellationinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="4" cols="150"');
        $mform->addHelpButton('cancellationinstrmngr', 'cancellationinstrmngr', 'facetoface');
        $mform->disabledIf('cancellationinstrmngr', 'emailmanagercancellation');
        $mform->setDefault('cancellationinstrmngr', get_string('setting:defaultcancellationinstrmngrdefault', 'facetoface'));

        $features = new stdClass;
        $features->groups = false;
        $features->groupings = false;
        $features->groupmembersonly = false;
        $features->outcomes = false;
        $features->gradecat = false;
        $features->idnumber = true;
        $this->standard_coursemodule_elements($features);

        $this->add_action_buttons();
    }

    public function data_preprocessing(&$defaultvalues) {

        // Fix manager emails.
        if (empty($defaultvalues['confirmationinstrmngr'])) {
            $defaultvalues['confirmationinstrmngr'] = null;
        } else {
            $defaultvalues['emailmanagerconfirmation'] = 1;
        }

        if (empty($defaultvalues['reminderinstrmngr'])) {
            $defaultvalues['reminderinstrmngr'] = null;
        } else {
            $defaultvalues['emailmanagerreminder'] = 1;
        }

        //US #6050 start
        if (empty($defaultvalues['prerequisiteinstrmngr'])) {
            $defaultvalues['prerequisiteinstrmngr'] = null;
        } else {
            $defaultvalues['emailmanagerlearnerprerequisite'] = 1;
        }
        //US #6050 end

        if (empty($defaultvalues['cancellationinstrmngr'])) {
            $defaultvalues['cancellationinstrmngr'] = null;
        } else {
            $defaultvalues['emailmanagercancellation'] = 1;
        }
    }
}
