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
 * This is built using the Clean template to allow for new theme's using
 * meline29 framework
 *
 *
 * @package   theme_meline29
 * @copyright 2014 Eduardo Ramos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/* Slideshow Widget Settings */
$temp = new admin_settingpage('theme_meline29_slideshow', get_string('slideshowheading', 'theme_meline29'));
$temp->add(new admin_setting_heading('theme_meline29_slideshow', get_string('slideshowheadingsub', 'theme_meline29'), format_text(get_string('slideshowdesc', 'theme_meline29'), FORMAT_MARKDOWN)));

if(isset($_GET['section'])){
    $securl = $_GET['section'];
}

if(!isset($_GET['lang'])){

   $_SESSION['slidelang'] = $lang = 'en';
}else{
   $_SESSION['slidelang'] = $lang = $_GET['lang'];
}
// Toggle Slideshow.
$name = 'theme_meline29/toggleslideshow_'.$lang;
$title = get_string('toggleslideshow', 'theme_meline29');
$description = get_string('toggleslideshowdesc', 'theme_meline29');
$alwaysdisplay = get_string('alwaysdisplay', 'theme_meline29');
$displaybeforelogin = get_string('displaybeforelogin', 'theme_meline29');
$displayafterlogin = get_string('displayafterlogin', 'theme_meline29');
$dontdisplay = get_string('dontdisplay', 'theme_meline29');
$default = '0';
$choices = array('1' => $alwaysdisplay, '2' => $displaybeforelogin, '3' => $displayafterlogin, '0' => $dontdisplay);
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Slideshow height:
$name = 'theme_meline29/slideshowheight_'.$lang;
$title = get_string('slideshowheight', 'theme_meline29');
$description = '';
$default = '300px';
$choices = array(
    'auto' => get_string('auto', 'theme_meline29')
);
for($i = 100; $i <= 500; $i+=25){
    $choices[$i.'px'] = $i.'px';
}
$temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

// Image sizing:
$name = 'theme_meline29/slideshowsizingmode_'.$lang;
$title = get_string('slideshowsizingmode', 'theme_meline29');
$description = '';
$default = 'auto';
$choices = array(
    'auto' => get_string('auto', 'theme_meline29'),
    'height' => get_string('slideshowsizingmode-fullheight', 'theme_meline29'),
    'width' => get_string('slideshowsizingmode-fullwidth', 'theme_meline29')
);
$temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

// Number of slides.
$name = 'theme_meline29/slideshownumber_'.$lang;
$title = get_string('slideshownumber', 'theme_meline29');
$description = '';
$default = 4;
$choices = range(1, 10);
$choices = array_combine($choices, $choices);
$temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

// Slideshow header color
$name = 'theme_meline29/slideshowtitlecolor_'.$lang;
$title = get_string('slideshowtitlecolor', 'theme_meline29');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, '', '#fff', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Slideshow caption color
$name = 'theme_meline29/slideshowcaptioncolor_'.$lang;
$title = get_string('slideshowcaptioncolor', 'theme_meline29');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, '', '#fff', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Slideshow button type
$name = 'theme_meline29/slideshowbuttontype_'.$lang;
$title = get_string('slideshowbuttontype', 'theme_meline29');
$previewconfig = null;
$default = '';

$class_normal = get_string('componentclass-normal', 'theme_meline29');
$class_primary = get_string('componentclass-primary', 'theme_meline29');
$class_success = get_string('componentclass-success', 'theme_meline29');
$class_danger = get_string('componentclass-danger', 'theme_meline29');
$class_link = get_string('componentclass-link', 'theme_meline29');

$choices = array(
    '' => $class_normal, 
    'uk-button-primary' => $class_primary, 
    'uk-button-success' => $class_success,
    'uk-button-danger' => $class_danger,
    'uk-button-link' => $class_link
);
$setting = new admin_setting_configselect($name, $title, '', $default, $choices); 
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Toggle slideshow autoplay
$name = 'theme_meline29/slideshowautoplay_'.$lang;
$title = get_string('slideshowautoplay', 'theme_meline29');
$description = '';
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Slideshow animation
$name = 'theme_meline29/slideshowanimation_'.$lang;
$title = get_string('slideshowanimation', 'theme_meline29');
$description = '';
$default = 'swipe';
$choices = array(
    'fade',
    'scroll',
    'scale',
    'swipe',
    'slice-down',
    'slice-up',
    'slice-up-down',
    'fold',
    'puzzle',
    'boxes',
    'boxes-reverse',
    'random-fx',
);
$choices = array_combine($choices, array_map(function($choice){
    return get_string('slideshowanimation-'.$choice, 'theme_meline29');
}, $choices));


$setting = new admin_setting_configselect($name, $title, '', $default, $choices); 
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Slideshow caption color
$name = 'theme_meline29/slideshowarrowscolor_'.$lang;
$title = get_string('slideshowarrowscolor', 'theme_meline29');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, '', '#fff', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Toggle slideshow ken burns effect
$name = 'theme_meline29/slideshowkenburns_'.$lang;
$title = get_string('slideshowkenburns', 'theme_meline29');
$description = '';
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$addSlideSettings = function($i, &$temp) {
    $lang = $_SESSION['slidelang'];
    //This is the descriptor for Slide i
    $name = "theme_meline29/slide{$i}info_".$lang;
    $params = new stdClass();
    $params->n = $i;
    $heading = get_string("slideheader", 'theme_meline29', $params);
    $setting = new admin_setting_heading($name, $heading, '');
    $temp->add($setting);

    // Title.
    $name = "theme_meline29/slide{$i}_".$lang;
    $title = get_string('slidetitle', 'theme_meline29');
    $setting = new admin_setting_configtext($name, $title, '', '');
    $default = '';
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Image.
    $name = "theme_meline29/slide{$i}image_".$lang;
    $title = get_string('slideimage', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, '', "slide{$i}image_".$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    // Caption.
    $name = "theme_meline29/slide{$i}caption_".$lang;
    $title = get_string('slidecaption', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // URL.
    $name = "theme_meline29/slide{$i}url_".$lang;
    $title = get_string('slideurl', 'theme_meline29');
    $setting = new admin_setting_configtext($name, $title, '', '', PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // URL text
    $name = "theme_meline29/slide{$i}urltext_".$lang;
    $title = get_string('slideurltext', 'theme_meline29');
    $setting = new admin_setting_configtext($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Caption placement
    $name = "theme_meline29/slide{$i}captionplacement_".$lang;
    $title = get_string('slidecaptionplacement', 'theme_meline29');
    $default = 'center';
    $choices = array(
        'center' => get_string('componentplacement-center', 'theme_meline29'),
        'top' => get_string('componentplacement-top', 'theme_meline29'),
        'bottom' => get_string('componentplacement-bottom', 'theme_meline29'),
        'left' => get_string('componentplacement-left', 'theme_meline29'),
        'right' => get_string('componentplacement-right', 'theme_meline29'),
    );
    $setting = new admin_setting_configselect($name, $title, '', $default, $choices); 
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
};

/**
 * Add slides
 */
$numberofslides = get_config('theme_meline29', 'slideshownumber');
if(!$numberofslides){
    $numberofslides = 4;
}
foreach (range(1, $numberofslides) as $i) {
    $addSlideSettings($i, $temp);
}

$ADMIN->add('theme_meline29', $temp);
?>
<script type="text/javascript" src="/mod/book/sample/js/jquery.1.7.1.js"></script>
<script src="/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<script type="text/javascript">$(document).ready(function () {
    var lang = "<?=$lang?>";
    var securl = "<?=$securl?>";
    if(securl == "theme_meline29_slideshow"){
    var str_url = "settings.php?section=theme_meline29_slideshow&lang="+lang;
        $("#adminsettings").attr("action",str_url);
    }
    console.log("Test12356");
});
</script>