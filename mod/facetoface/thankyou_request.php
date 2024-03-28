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
	
    $leveltype = required_param('leveltype', PARAM_INT);
    echo $OUTPUT->header();
    echo '<link href="/theme/meline29/style/classroom.css" type="text/css" rel="stylesheet">';

	$thankyou_message_request = get_string('thankyou_message_request','facetoface');
	$thankyou_message_request = str_replace('LEVEL', 'Level '.$leveltype, $thankyou_message_request);


    echo '<div class="classroom-whole-section page-four4">
      <div class="thumsupimg"> <img src="registration-done-img.png"/> </div>


      <div class="classroom-fourpg-right-part"> <span class="rg-sucess-msg">'.get_string('thankyou_trp_head','facetoface').'</span> <span class="rg-information-msg">'.$thankyou_message_request.'</span>
        <div class="final-button-classroom">
          <div class="returendashboard-btn"> <a href="/my">Return to Dashboard</a> </div>
          <!--div class="editregs-btn"> <a href="javascript:void(0)">Edit Registration</a> </div-->
        </div>
      </div>
    </div>';
    echo $OUTPUT->footer($course);
?>

<script>
$(document).ready(function () {
$("#region-main-box").removeAttr('id');
});
</script>
<style>
/*#page-wrap #page-content {
    padding-top: 123px;
}
}*/
</style>
