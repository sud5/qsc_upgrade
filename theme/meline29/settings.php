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
$settings = null;

defined('MOODLE_INTERNAL') || die;


$ADMIN->add('themes', new admin_category('theme_meline29', 'Meline29'));

// "geneicsettings" settingpage
$temp = new admin_settingpage('theme_meline29_generic', get_string('geneicsettings', 'theme_meline29'));

// Logo file setting.
$name = 'theme_meline29/logo';
$title = get_string('logo', 'theme_meline29');
$description = get_string('logodesc', 'theme_meline29');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// page header background colour setting
    $name = 'theme_meline29/themebgc';
    $title = get_string('themebgc','theme_meline29');
    $description = get_string('themebgcdesc', 'theme_meline29');
    $default = '#4d877f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $temp->add($setting);
    
// Page link colour setting
    $name = 'theme_meline29/themehoverbgc';
    $title = get_string('themehoverbgc','theme_meline29');
    $description = get_string('themehoverbgcdesc', 'theme_meline29');
    $default = '#e47508';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $temp->add($setting);

// Copyright setting.
$name = 'theme_meline29/copyright';
$title = get_string('copyright', 'theme_meline29');
$description = get_string('copyrightdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$temp->add($setting);

if(!isset($_GET['lang'])){
    $lang = '_en';
}else{
    $lang = "_".$_GET['lang'];
}
// Footnote setting.
$name = 'theme_meline29/footnote'.$lang;
$title = get_string('footnote', 'theme_meline29');
$description = get_string('footnotedesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_confightmleditor($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$ADMIN->add('theme_meline29', $temp);


/* Slideshow Widget Settings */
include dirname(__FILE__).'/settings/slideshow.php';

/* Frontpage content settings */
include dirname(__FILE__).'/settings/frontpage.php';

/* Social Networks Settings */
include dirname(__FILE__).'/settings/socialnetworks.php';

?>
<script type="text/javascript" src="/mod/book/sample/js/jquery.1.7.1.js"></script>
<script src="/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<script type="text/javascript">$(document).ready(function () {
    var lang = "<?=$lang?>";
    var securl = "<?=$securl?>";
    if(securl == "theme_meline29_generic"){
        var str_url = "settings.php?section=theme_meline29_generic&lang="+lang;
        $("#adminsettings").attr("action",str_url);
    }
});
</script>
