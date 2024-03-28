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

require_once($CFG->dirroot . '/mod/facetoface/lib.php');
require_once($CFG->dirroot . '/lib/formslib.php');

class mod_facetoface_signup_form extends moodleform {

    public function definition() {
        $mform = & $this->_form;
        $classroomType = '';
        $levelType = '';
        $custom_message = '';  // -- - - - -Custom Message View -- Variable - -- //
        if (isset($this->_customdata['flags'])) {
            $classroomType = $this->_customdata['flags']['Classroom Type'][1];
            $levelType = $this->_customdata['flags']['Level Type'][1];
            $promocode = $this->_customdata['flags']['Promocode'][1];
            // -- - - - -Custom Message View  --assign Value  - -- //
            $custom_message = $this->_customdata['flags']['Custom Message'][1];
        }
        global $DB;
        $getUserRecordIdQuery = "SELECT id,email FROM `mdl_user` where id=2";
        $userRecordIdData = $DB->get_record_sql($getUserRecordIdQuery);
        $manageremail = $userRecordIdData->email;

        $showdiscountcode = $this->_customdata['showdiscountcode'];

        $mform->addElement('hidden', 's', $this->_customdata['s']);
        $mform->setType('s', PARAM_INT);

        $mform->addElement('hidden', 'backtoallsessions', $this->_customdata['backtoallsessions']);
        $mform->setType('backtoallsessions', PARAM_INT);

        $mform->addElement('hidden', 'manageremail', $manageremail);
        $mform->setType('manageremail', PARAM_EMAIL);
        if ($showdiscountcode) {
            $mform->addElement('text', 'discountcode', get_string('discountcode', 'facetoface'), 'size="6"');
            $mform->addHelpButton('discountcode', 'discountcodelearner', 'facetoface');
        } else {
            $mform->addElement('hidden', 'discountcode', '');
        }
        $mform->setType('discountcode', PARAM_TEXT);
        $mform->addElement('text', 'who_refer_you_title', get_string('who_refer_you_title', 'facetoface'));
        $mform->setType('who_refer_you_title', PARAM_TEXT);
        //TRP start
        //Task #2190 start if cond additional logic
        if ($this->_customdata['session_timestartform'] != 2481889900 && $this->_customdata['backtoallsessions'] != 14) {
        //TRP end
            if ($levelType == "Level 2") {
                $optionsVTerms = array(
                    1 => 'Yes',
                    2 => 'No, please contact me with clarification.'
                );
                $mform->addElement('select', 'qsysonevalidate', get_string('terms_label', 'facetoface'), $optionsVTerms);
                $mform->addRule('qsysonevalidate', null, 'required', null, 'client');
                $mform->setDefault('qsysonevalidate', 1);
                $level_terms = get_string('terms_2', 'facetoface');
            } else {
                if ($this->_customdata['backtoallsessions'] == 10)
                    $level_terms = get_string('terms_touchmix', 'facetoface');
                elseif ($this->_customdata['backtoallsessions'] == 17)
                    $level_terms = get_string('terms_fundamental', 'facetoface');
                else
                    $level_terms = get_string('terms', 'facetoface');
            }
            $optionsTerms = array(
                null => '&lt;Please Make a Selection&gt;',
                1 => 'I Agree'
            );
            $mform->addElement('select', 'terms', $level_terms, $optionsTerms);
            $mform->addRule('terms', get_string('terms_error', 'facetoface'), 'required', null, 'client');
            if ($this->_customdata['backtoallsessions'] == 10) {
                $level_terms = get_string('terms_touchmix_smartphone', 'facetoface');
                $optionsTerms = array(
                    null => '&lt;Please Make a Selection&gt;',
                    1 => 'I Agree'
                );
                $mform->addElement('select', 'terms_smartphone', $level_terms, $optionsTerms);
                $mform->addRule('terms_smartphone', get_string('terms_error', 'facetoface'), 'required', null, 'client');
            }

            $optionsDietRestriction = array(
                'None' => 'None',
                'Vegan' => 'Vegan',
                'Vegetarian' => 'Vegetarian',
                'Allergies' => 'Allergies',
                'Other' => 'Other'
            );
            $mform->addElement('select', 'diet_restriction', get_string('diet_restriction', 'facetoface'), $optionsDietRestriction);
            $mform->setDefault('diet_restriction', 'None');

            $mform->addElement('text', 'who_refer_you_desc', get_string('who_refer_you_desc', 'facetoface'));
            $mform->setType('who_refer_you_desc', PARAM_TEXT);
        //TRP start
        }
        //TRP end
        if ($promocode != NULL) {
            $mform->addElement('text', 'promocode', get_string('permissioncode_label', 'facetoface'));
            $mform->setType('promocode', PARAM_TEXT);
            $mform->addRule('promocode', get_string('permissioncode_notfound_error', 'facetoface'), 'required', null, 'client');
        }
        // -- - - - -Custom Message View  --  - -- //
        if (!empty($custom_message)) {
            $mform->addElement('html', "<label>" . $custom_message . "<label>");
        }

        $mform->addElement('hidden', 'notificationtype', 3);
        $mform->setType('notificationtype', PARAM_TEXT);

        $this->add_action_buttons(true, get_string('submit-R', 'facetoface'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }

}
