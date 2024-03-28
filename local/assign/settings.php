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
 * Local plugin "assign" - Settings
 *
 * @package    local_assign
 * @copyright  2022 shiva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE;

/*if ($hassiteconfig) {
    // Add new category to site admin navigation tree.
    $ADMIN->add('root', new admin_category('local_assign',
            get_string('pluginname', 'local_assign', null, true)));


    // Create new documents page.
    $page = new admin_settingpage('local_assign_documents',
            get_string('documents', 'local_assign', null, true));

    if ($ADMIN->fulltree) {
        // Create document filearea widget.
        $page->add(new \local_assign\admin_setting_assignstoredfile('local_assign/documents',
                get_string('documents', 'local_assign', null, true),
                get_string('documents_desc', 'local_assign', null, true),
                'documents',
                0,
                array('maxfiles' => -1, 'accepted_types' => '.html')));
    }

}
*/