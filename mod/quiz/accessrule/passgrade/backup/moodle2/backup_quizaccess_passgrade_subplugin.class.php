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
 * Backup code for the quizaccess_honestycheck plugin.
 *
 * @package   quizaccess_passgrade
 * @copyright 2013 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once($CFG->dirroot . '/mod/quiz/backup/moodle2/backup_mod_quiz_access_subplugin.class.php');

defined('MOODLE_INTERNAL') || die();


/**
 * Provides the information to backup the passgrade quiz access plugin.
 *
 * If this plugin is requires, a single
 * <quizaccess_passgrade><passgrade>1</passgrade></quizaccess_passgrade> tag
 * will be added to the XML in the appropriate place. Otherwise nothing will be
 * added. This matches the DB structure.
 */
class backup_quizaccess_passgrade_subplugin extends backup_mod_quiz_access_subplugin {

    protected function define_quiz_subplugin_structure() {

        // Create XML elements.
        $subplugin = $this->get_subplugin_element();
        $subplugin_wrapper = new backup_nested_element($this->get_recommended_name());
        $subplugin_table_settings = new backup_nested_element('quizaccess_passgrade',
                null, array('passgrade'));

        // Connect XML elements into the tree.
        $subplugin->add_child($subplugin_wrapper);
        $subplugin_wrapper->add_child($subplugin_table_settings);

        // Set source to populate the data.
        $subplugin_table_settings->set_source_table('quizaccess_passgrade',
                array('quizid' => backup::VAR_ACTIVITYID));

        return $subplugin;
    }
}
