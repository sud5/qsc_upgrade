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

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once('lib.php');
global $DB, $OUTPUT, $USER;

$f = required_param('f', PARAM_INT); // Facetoface session ID.
$modnames = $_REQUEST['mod'];
$backtoallsessions = optional_param('backtoallsessions', 0, PARAM_INT);


if (!$facetoface = $DB->get_record('facetoface', array('id' => $f))) {
    print_error('error:incorrectfacetofaceid', 'facetoface');
}
if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
    print_error('error:coursemisconfigured', 'facetoface');
}
if (!$cm = get_coursemodule_from_instance("facetoface", $facetoface->id, $course->id)) {
    print_error('error:incorrectcoursemoduleid', 'facetoface');
}

require_course_login($course, true, $cm);
$context = context_course::instance($course->id);
$contextmodule = context_module::instance($cm->id);
require_capability('mod/facetoface:view', $context);

$returnurl = "$CFG->wwwroot/course/view.php?id=$course->id";
if ($backtoallsessions) {
    $returnurl = "$CFG->wwwroot/mod/facetoface/view.php?f=$backtoallsessions";
}

$pagetitle = format_string($facetoface->name);

$PAGE->set_cm($cm);
//$PAGE->set_url('/mod/facetoface/signup.php', array('s' => $s, 'backtoallsessions' => $backtoallsessions));

$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);

    echo $OUTPUT->header();
    echo '<link href="/theme/meline29/style/classroom.css" type="text/css" rel="stylesheet">';
    //echo facetoface_thankyou_page();
    ?>
    <div class="classroom-whole-section page-four4" style="padding:15px;">
      <div class=""> <span class="rg-sucess-msg" style="margin-bottom:1px;"><?php echo get_string('waithead', 'facetoface');?></span> <br><span class="">
<?php echo get_string('waitdesc', 'facetoface');?>
</span> <br><br>
        <div class="final-button-classroom" align="center">
          <div class="returendashboard-btn"> <a href="/mod/<?=$modnames?>/view.php?waiton=1&id=<?=$_REQUEST['id']?>">Continue</a> </div>
          <!--div class="editregs-btn"> <a href="javascript:void(0)">Edit Registration</a> </div-->
        </div>
      </div>
    </div>
    <?php
    echo $OUTPUT->footer($course);
?>
<script>
$(document).ready(function () {
$("#region-main-box").removeAttr('id');
});
</script>
<style>
.classroom-whole-section.page-four4{
margin: 5px 0 0 0px !important;
}
</style>

