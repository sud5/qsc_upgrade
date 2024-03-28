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
 * Instance add/edit form
 *
 * @package    mod_book
 * @copyright  2004-2011 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__.'/locallib.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_book_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $PAGE,$DB, $USER; //pre

        $mform = $this->_form;

        $config = get_config('book');

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('textarea', 'name', get_string('name'), array('cols'=>'128'));
        $mform->addElement('textarea', 'displaytime', format_string('Display Time'), array('cols'=>'128','placeholder'=>'in seconds'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $this->standard_intro_elements(get_string('moduleintro'));
        // - - --- Start- Feature Request: "Update Help File" Field- Customized by Naveen - -- - -  -//
        $mform->addElement('checkbox', 'update_help_file', get_string('updatehelpfile'));
        $mform->addHelpButton('update_help_file', 'updatehelpfile');
        $mform->addRule('update_help_file', '', 'required', null, 'client');
        $mform->setDefault('update_help_file', 0);
        // - - -- - - End-Feature Request: "Update Help File" Field- Customized by Naveen  - -- - -  -//

        //custom text start
        $mform->addElement('textarea','c_text_beneath_video', 'Custom Text Beneath a Video on Lesson Page', null);
        $mform->addElement('selectyesno', 'custom_text_display_flag', 'Display Custom Text Beneath a Video');
        //custom text end

        //Customization Sameer start
        if (!$bookData = $DB->get_record('book', array('id'=>$PAGE->cm->instance))) {
        $imagevalue = '';
        }else{
            //echo base64_decode($bookData->thumbnailpath); die;
            if($bookData->thumbnailpath != ''){
            //print_r(str_replace("pluginfile.php","draftfile.php",base64_decode($bookData->thumbnailpath)));exit;
            $imagevalue = '<div class="fitem form-group row"><div class="fitemtitle col-md-3 col-form-label d-flex pb-0 pr-md-0"><div class="fstaticlabel">'.get_string('cthumbnail','book').' </div></div>
                <div class="felement fstatic col-md-9 form-inline align-items-start"><img src="'.str_replace("pluginfile.php","draftfile.php",base64_decode($bookData->thumbnailpath)).'" class="userpicture"></div></div>';
            }else{
              $imagevalue = '';
            }
        }

        if($imagevalue != '')
            $mform->addElement('html',$imagevalue);

        $options = array();
        $options['accepted_types'] = array('*');
        $mform->addElement('filepicker', 'lessonthumbfile', "Module thumbnail Image <br> (Minimum size 115x115)", null, $options);
        //$mform->addRule('lessonthumbfile', null, 'required');
        //Customization Sameer end

        //seo settings start
        $mform->addElement('textarea','meta_keywords', get_string('metakeywords'), null);
        $mform->addHelpButton('meta_keywords', 'metakeywords');
        $mform->setType('meta_keywords', PARAM_RAW);
        // - - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -//
        $mform->addElement('selectyesno', 'showfrontpage_flag', get_string('showfrontpage'));
        $mform->addHelpButton('showfrontpage_flag', 'showfrontpage');
        // - - -- - - - - -- End- Feature Request: Frontpage Latest content  - -- - -  -//
        // Appearance.
        $mform->addElement('header', 'appearancehdr', get_string('appearance'));

        $alloptions = book_get_numbering_types();
        $allowed = explode(',', $config->numberingoptions);
        $options = array();
        foreach ($allowed as $type) {
            if (isset($alloptions[$type])) {
                $options[$type] = $alloptions[$type];
            }
        }
        if ($this->current->instance) {
            if (!isset($options[$this->current->numbering])) {
                if (isset($alloptions[$this->current->numbering])) {
                    $options[$this->current->numbering] = $alloptions[$this->current->numbering];
                }
            }
        }
        $mform->addElement('select', 'numbering', get_string('numbering', 'book'), $options);
        $mform->addHelpButton('numbering', 'numbering', 'mod_book');
        $mform->setDefault('numbering', $config->numbering);

        $mform->addElement('static', 'customtitlestext', get_string('customtitles', 'mod_book'));
        $a = "<a href='javascript:void(0);' data-toggle='modal' data-target='#memberModalNM'> View Content</a>";
        $mform->addElement('checkbox', 'customtitles', get_string('customtitles', 'book'));
        $mform->addHelpButton('customtitles', 'customtitles', 'mod_book');
        $mform->setDefault('customtitles', 0);

        //prev-start
        if($USER->usertype == 'graderasadmin' || $USER->usertype == 'mainadmin'){
            $mform->addElement('hidden', 'imagefrombookmod', 'imagefrombookmod');
        }
        //prev-end

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}
