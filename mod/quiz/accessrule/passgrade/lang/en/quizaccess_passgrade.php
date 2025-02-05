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
 * Strings for the quizaccess_passgrade plugin.
 *
 * @package   quizaccess_passgrade
 * @copyright 2013 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Pass grade quiz access rule';
$string['preventpassed'] = 'Require a grade less than';
$string['preventpassed_help'] = 'Students must have a grade less thant the specified grade. This is designed to prevent students who have passed from reattempting the quiz.';
$string['accessprevented'] = 'You have already passed this quiz, and may not make further attempts.';

//Edit Sameer
//$string['failedattentionmessage'] = '<strong>You have failed this assessment too many times.  Please go back and review the videos associated with this quiz.  Then <a href="javascript:void(0);" id="resetexam" data-modal-id="quizresetpopup">click here</a> to contact a Q-SYS Trainer to reset this quiz.<br></strong>';

$string['failedattentionmessage'] = 'You have failed this assessment too many times. You will be blocked from attempting this quiz for one hour. Please use this time to go back and review the videos associated with this quizzes.';