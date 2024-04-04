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
 * Lists the course categories
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package course
 */

require_once("../config.php");
require_once($CFG->dirroot. '/course/lib.php');
require_once($CFG->libdir. '/coursecatlib.php');

$categoryid = optional_param('categoryid', 0, PARAM_INT); // Category id
$site = get_site();

if ($CFG->forcelogin) {
    require_login();
}

$heading = $site->fullname;
$category = $categoryid;
if($categoryid == 4){
  $categoryid = 0;
  $PAGE->set_url('/course/index.php');
    $PAGE->set_context(context_system::instance());
}
if ($categoryid) {
    $category = core_course_category::get($categoryid); // This will validate access.
    $PAGE->set_category_by_id($categoryid);
    $PAGE->set_url(new moodle_url('/course/index.php', array('categoryid' => $categoryid)));
    $PAGE->set_pagetype('course-index-category');
    $heading = $category->get_formatted_name();
} else if ($category = core_course_category::user_top()) {
    // Check if there is only one top-level category, if so use that.
    $categoryid = $category->id;
    $PAGE->set_url('/course/index.php');
    if ($category->is_uservisible() && $categoryid) {
        $PAGE->set_category_by_id($categoryid);
        $PAGE->set_context($category->get_context());
        if (!core_course_category::is_simple_site()) {
            $PAGE->set_url(new moodle_url('/course/index.php', array('categoryid' => $categoryid)));
            $heading = $category->get_formatted_name();
        }
    } else {
        $PAGE->set_context(context_system::instance());
    }
    $PAGE->set_pagetype('course-index-category');
} else {
    throw new moodle_exception('cannotviewcategory');
}

$PAGE->set_pagelayout('coursecategory');
$PAGE->set_primary_active_tab('home');
$PAGE->add_body_class('limitedwidth');
$courserenderer = $PAGE->get_renderer('core', 'course');

$PAGE->set_heading($heading);
//Custom-2 start
//$content = $courserenderer->course_category($categoryid);
$content = $courserenderer->course_category($category);
//Custom-2 end
//$content = $courserenderer->course_category($categoryid);

$PAGE->set_secondary_active_tab('categorymain');

echo $OUTPUT->header();
echo $OUTPUT->skip_link_target();
//Custom-3 start
if($categoryid == 4){
//Design Starts
?>
<link href="/theme/meline29/style/classroom.css" type="text/css" rel="stylesheet">
<!--Work Starts Here-->
<!--One Page Work Start Here-->
<div class="classroom-whole-section page-two1">
  <div class="classroom-head-with-logo"> <img src="Level1-Classroom-Logo.png"/> </div>
 
 <div class="top_text_section">
 <p><h6><strong>Learn Q-SYS in one sitting</strong></h6>
If you need to learn Q-SYS fast and painlessly, there's no better way than to attend a Level 1 Classroom training. Learn in a small environment and get one-on-one help for certified Q-SYS Trainers.</p>
<p><h6><strong>Hands-on Experience</strong></h6>Each student works on a full Q-SYS workstation with the latest Q-SYS hardware and software.</p>
<p><h6><strong>Conference Room Curriculum</strong></h6>Learn the basics of the software in the context of a real conference room installation. Topics include: Hardware Overview, Software Navigation, Teleconferencing Suite (Acoustic Echo Cancellation,
Softphone, Proper Gain Staging), Basic Advanced User Control Design, Basic Test & Measurement Tools, USB Audio and more.</p>
 </div> 
 
 <div class="infocom_img"> <img src="info_com_logo.png"/> </div>
 
 <div class="for_unittext">
<h6>4 Units of Infocomm CTS Program Credits</h6>
<p>Completion of this class which includes 8 hours of instruction and the successful completion of the final design will entitle students to 4 units of Infocomm CTS program credits.</p>
 </div>
 
<div class="class-room-sessions">
 <h4>Upcoming Classroom Sessions</h4>
  <div class="page2_second-section pg_one">    
    <img src="qsc_training_logo.png">
    <div class="address-details-classroom"> <span class="intitutionname">Q-SYS Level 1 Training Hosted by QSC</span> <span class="day-date-classroom">Tuesday May 24, 2016</span> <span class="time-classroom">9:00am - 5:00pm</span> <span class="address-full">1675 MacArthur Blvd
      Costa Mesa, CA 92626</span> </div>
      
      <div class="reserve-btn-seats">
      <input type="submit" value="Reserve" name="submitbutton">
      <p>2 Seats left</p>
      </div>
  </div>
    <div class="page2_second-section pg_one">    
    <img src="qsc_training_logo.png">
    <div class="address-details-classroom"> <span class="intitutionname">Q-SYS Level 1 Training Hosted by QSC</span> <span class="day-date-classroom">Tuesday May 24, 2016</span> <span class="time-classroom">9:00am - 5:00pm</span> <span class="address-full">1675 MacArthur Blvd
      Costa Mesa, CA 92626</span> </div>
      
      <div class="reserve-btn-seats">
      <input type="submit" value="Reserve" name="submitbutton">
      <p>3 Seats left</p>
      </div>
  </div>
 </div>
 
  
  </div>  
  
<!--One Page Work Ends Here-->
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
<!--Two Page Work Start Here-->
<div class="classroom-whole-section page-two2">
  <div class="classroom-head-with-logo"> <img src="Level1-Classroom-Logo.png"/> </div>
  <div class="page2_second-section">
    <h4>Your Selection:</h4>
    <img src="qsc_training_logo.png">
    <div class="address-details-classroom"> <span class="intitutionname">Q-SYS Level 1 Training Hosted by QSC</span> <span class="day-date-classroom">Tuesday May 24, 2016</span> <span class="time-classroom">9:00am - 5:00pm</span> <span class="address-full">1675 MacArthur Blvd
      Costa Mesa, CA 92626</span> </div>
  </div>
  <div class="classroom-third-section-form-start">
    <form id="" action="">
      <label class="who-refer-you">Who referred you to this event (Name and Company)
        <input type="text">
      </label>
      <label class="training-attendees">Q-SYS Level One Training attendees are required to bring a laptop with the latest version of Q-SYS Designer Software installed prior to training. Youmust also ensure that your laptop has a physical Ethernet port, and that you bring a mouse for your laptop. Do you agree to this requirement?
        <select name="select">
          <option  selected="" value="value1">I agree</option>
          <option value="value2">I do not agree.</option>
        </select>
      </label>
      <label class="training-attendees">A continental breakfast and light lunch is provided. Do you have any special dietary restriction?
        <select name="select">
          <option  selected="" value="value1">None</option>
          <option value="value3">Vegan</option>
          <option value="value3">Vegetarian</option>
          <option value="value3">Allergies</option>
          <option value="value3">Other</option>
        </select>
      </label>
      <label class="who-refer-you">Please explain restriction if applicable.
        <input type="text">
      </label>
    </form>
  </div>
  <div class="submit-btn">
    <input name="submitbutton" value="Submit Registration" type="submit">
  </div>
</div>
<!--Two Page Work Ends Here--> 
<!--Three Page Work Start Here-->
<div class="classroom-whole-section page-two3">
  <div class="classroom-head-with-logo"> <img src="Level1-Classroom-Logo.png"/> </div>
  <div class="clasroom-second-part">
    <p>Complete this form and a member of QSC's regional training staff will work with you to schedule a regional training in your area.</p>
    <p>For a list of all QSC regional sales representatives <a href="">click here.</a></p>
  </div>
  <div class="classroom-third-section-form-start">
    <form id="" action="">
      <label class="who-refer-you">Who referred you to this event (Name and Company)
        <input type="text">
      </label>
      <label class="training-attendees">Q-SYS Level One Training attendees are required to bring a laptop with the latest version of Q-SYS Designer Software installed prior to training. Youmust also ensure that your laptop has a physical Ethernet port, and that you bring a mouse for your laptop. Do you agree to this requirement?
        <select name="select">
          <option  selected="" value="value1">I agree</option>
          <option value="value2">I do not agree.</option>
        </select>
      </label>
      <label class="training-attendees">A continental breakfast and light lunch is provided. Do you have any special dietary restriction?
        <select name="select">
          <option  selected="" value="value1">None</option>
          <option value="value3">Vegan</option>
          <option value="value3">Vegetarian</option>
          <option value="value3">Allergies</option>
          <option value="value3">Other</option>
        </select>
      </label>
      <label class="who-refer-you">Please explain restriction if applicable.
        <input type="text">
      </label>
    </form>
  </div>
  <div class="submit-btn">
    <input name="submitbutton" value="Submit Registration" type="submit" >
  </div>
</div>
<!--Three Page Work Ends Here--> 
<!--Four Page Work Start Here-->
<div class="classroom-whole-section page-four4">
  <div class="thumsupimg"> <img src="registration-done-img.png"/> </div>
  <div class="classroom-fourpg-right-part"> <span class="rg-sucess-msg">Your registration request has been received!</span> <span class="rg-information-msg"><?php echo get_string('thankyou_message','facetoface');?></span>
    <div class="final-button-classroom">
      <div class="returendashboard-btn"> <a href="javascript:void(0)">Return to Dashboard</a> </div>
      <div class="editregs-btn"> <a href="javascript:void(0)">Edit Registration</a> </div>
    </div>
  </div>
</div>
<!--Four Page Work Ends Here--> 
<!--Work Ends Here-->
<?php
//Design Ends
}
else{
    echo $content;
}
//Custom-3 end

// Trigger event, course category viewed.
$eventparams = array('context' => $PAGE->context, 'objectid' => $categoryid);
$event = \core\event\course_category_viewed::create($eventparams);
$event->trigger();

echo $OUTPUT->footer();
