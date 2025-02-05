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
 * User select table util file
 *
 * @package    tool
 * @subpackage mergeusers
 * @author     Nicolas Dunand <Nicolas.Dunand@unil.ch>
 * @author     Mike Holzer
 * @author     Forrest Gaston
 * @author     Juan Pablo Torres Herrera
 * @author     Jordi Pujol-Ahulló, Sred, Universitat Rovira i Virgili
 * @author     John Hoopes <hoopes@wisc.edu>, Univeristy of Wisconsin - Madison
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $CFG;

// require needed library files
require_once($CFG->dirroot . '/lib/clilib.php');
require_once(__DIR__ . '/autoload.php');
require_once($CFG->dirroot . '/lib/outputcomponents.php');

/**
 * Extend the html table to provide a build function inside for creating a table
 * for user selecting.
 *
 * @author  John Hoopes <hoopes@wisc.edu>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class UserSelectTable extends html_table implements renderable
{

    /** @var tool_mergeusers_renderer Renderer to show user info. */
    protected $renderer;

    /**
     * Call parent construct
     *
     * @param array $users
     * @param tool_mergeusers_renderer $renderer
     *
     */
    public function __construct($users, $renderer)
    {
        parent::__construct();
        $this->renderer = $renderer;
        $this->buildtable($users);
    }

    /**
     * Build the user select table using the extension of html_table
     *
     * @param array $users array of user results
     *
     */
    protected function buildtable($users)
    {
        global $DB,$CFG;
        // Reset any existing data
        $this->data = array();

        $this->id = 'merge_users_tool_user_select_table';
        $this->attributes['class'] = 'generaltable boxaligncenter';

        $columns = array(
            'col_select_olduser' => get_string('olduser', 'tool_mergeusers'),
            'col_master_newuser' => get_string('newuser', 'tool_mergeusers'),
            'col_userid' => 'Id',
            'col_username' => get_string('user'),
            'col_email' => get_string('email'),
        );

        $this->head = array_values($columns);
        $this->colclasses = array_keys($columns);

        foreach ($users as $userid => $user) {
            $row = array();
            $spanclass = ($user->suspended) ? ('usersuspended') : ('');
            //Mergeuser customo start
            if($spanclass==''){
                $sqlMergeUser = "SELECT * FROM `mdl_tool_mergeusers` mtm JOIN mdl_user u ON mtm.fromuserid=u.id where mtm.touserid=$userid";
                $rsMergeUser = $DB->get_record_sql($sqlMergeUser);
//                if(empty($rsMergeUser)){
                    $row[] = html_writer::empty_tag('input', array('type' => 'radio', 'name' => 'olduser', 'value' => $userid, 'id' => 'olduser' . $userid));
                    $row[] = html_writer::empty_tag('input', array('type' => 'radio', 'name' => 'newuser', 'value' => $userid, 'id' => 'newuser' . $userid));
                    $row[] = html_writer::tag('span', $user->id, array('class' => $spanclass));
                    $row[] = html_writer::tag('span', $this->renderer->show_user($user->id, $user), array('class' => $spanclass));
                    $row[] = html_writer::tag('span', $user->username, array('class' => $spanclass));
  //              }
            }
            //Mergeuser customo end
        $this->data[] = $row;
        }
    }
}
