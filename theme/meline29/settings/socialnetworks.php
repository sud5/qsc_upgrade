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
$temp = new admin_settingpage('theme_meline29_social', get_string('socialheading', 'theme_meline29'));
$temp->add(new admin_setting_heading('theme_meline29_social', get_string('socialheadingsub', 'theme_meline29'), format_text(get_string('socialdesc', 'theme_meline29'), FORMAT_MARKDOWN)));

// Facebook url setting.
$name = 'theme_meline29/facebook';
$title = get_string('facebook', 'theme_meline29');
$description = get_string('facebookdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Flickr url setting.
$name = 'theme_meline29/flickr';
$title = get_string('flickr', 'theme_meline29');
$description = get_string('flickrdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Twitter url setting.
$name = 'theme_meline29/twitter';
$title = get_string('twitter', 'theme_meline29');
$description = get_string('twitterdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Google+ url setting.
$name = 'theme_meline29/googleplus';
$title = get_string('googleplus', 'theme_meline29');
$description = get_string('googleplusdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// LinkedIn url setting.
$name = 'theme_meline29/linkedin';
$title = get_string('linkedin', 'theme_meline29');
$description = get_string('linkedindesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Pinterest url setting.
$name = 'theme_meline29/pinterest';
$title = get_string('pinterest', 'theme_meline29');
$description = get_string('pinterestdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Instagram url setting.
$name = 'theme_meline29/instagram';
$title = get_string('instagram', 'theme_meline29');
$description = get_string('instagramdesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// YouTube url setting.
$name = 'theme_meline29/youtube';
$title = get_string('youtube', 'theme_meline29');
$description = get_string('youtubedesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Skype url setting.
$name = 'theme_meline29/skype';
$title = get_string('skype', 'theme_meline29');
$description = get_string('skypedesc', 'theme_meline29');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$ADMIN->add('theme_meline29', $temp);