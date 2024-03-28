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
 * Local plugin "assign" - Version file
 *
 * @package    local_assign
 * @copyright  2022 @shiva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_assign';
$plugin->version  = 2021051702;    // The current module version (Date: YYYYMMDDXX).
$plugin->requires = 2021051100;    // Requires this Moodle version.
$plugin->maturity = MATURITY_STABLE;             // This version's maturity level.

/*$plugin->version = 2017092900.6;
$plugin->release = 'v3.2-r9';
$plugin->requires = 2016120500;
$plugin->maturity = MATURITY_STABLE;*/
