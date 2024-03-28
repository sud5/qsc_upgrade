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

    $temp = new admin_settingpage('theme_meline29_frontcontent', get_string('frontcontentheading', 'theme_meline29'));

    $temp->add(new admin_setting_heading('theme_meline29_topphonenumber', get_string('topphonenumber', 'theme_meline29'), ''));

    // Caption.
    $name = 'theme_meline29/phonenumber';
    $title = get_string('phonenumber', 'theme_meline29');
    $description = get_string('phonenumberdesc', 'theme_meline29');
    $default = ' 011-6782975, 011-8508105';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    
    /**  Display Frontpage Banner **/
 //    $temp->add(new admin_setting_heading('theme_meline29_banner', get_string('banner', 'theme_meline29'), ''));    
 //    $name = 'theme_meline29/showhidebanner';
 //    $title = get_string('showhidebanner','theme_meline29');
 //    $description = get_string('showhidebannerdesc', 'theme_meline29');
 //    $default = 1;
 //    $choices = array(0=>'No', 1=>'Yes');
 //    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
 //    $temp->add($setting);

 //    // Caption.
    
 //    $name = 'theme_meline29/bannercaption';
 //    $title = get_string('bannercaption', 'theme_meline29');
 //    $description = get_string('bannercaptiondesc', 'theme_meline29');
 //    $default = '';
 //    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/bannerbtnonetxt';
	// $title = get_string('bannerbtnonetxt','theme_meline29');
	// $description = get_string('bannerbtnonetxtdesc', 'theme_meline29');
	// $default = 'Lorem Ipsum';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/bannerbtnoneurl';
 //    $title = get_string('bannerbtnoneurl', 'theme_meline29');
 //    $description = get_string('bannerbtnoneurldesc', 'theme_meline29');
 //    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/bannerbtntwotxt';
	// $title = get_string('bannerbtntwotxt','theme_meline29');
	// $description = get_string('bannerbtntwotxtdesc', 'theme_meline29');
	// $default = 'Lorem Ipsum';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/bannerbtntwourl';
 //    $title = get_string('bannerbtntwourl', 'theme_meline29');
 //    $description = get_string('bannerbtntwourldesc', 'theme_meline29');
 //    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
    // Main Content Blocks Settings - 1st Blade Structure.

    $temp->add(new admin_setting_heading('theme_meline29_mainblocks', get_string('mainblocks', 'theme_meline29'), ''));
    if(isset($_GET['section'])){
	    $securl = $_GET['section'];
	}

	if(!isset($_GET['lang'])){
	   $lang = 'en';
	}else{
	   $lang = $_GET['lang'];
	}

    /**  Display Main content Block One **/
    
    $name = 'theme_meline29/blockonetitle_'.$lang;
    $title = get_string('blocktitle','theme_meline29');
    $description = get_string('blocktitledesc', 'theme_meline29');
    $default = 'Learning Made easy';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_meline29/showhideblockone_'.$lang;
    $title = get_string('showhideblockone','theme_meline29');
    $description = get_string('showhideblockonedesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/blockoneimage_'.$lang;
    $title = get_string('blockoneimage', 'theme_meline29');
    $description = get_string('blockoneimagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'blockoneimage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    $name = 'theme_meline29/blockonetitle';
	// $title = get_string('blockonetitle','theme_meline29');
	// $description = get_string('blockonetitledesc', 'theme_meline29');
	// $default = 'Available Courses';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/blockonelinktxt';
	// $title = get_string('blockonelinktxt','theme_meline29');
	// $description = get_string('blockonelinktxtdesc', 'theme_meline29');
	// $default = 'See All Courses';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/blockonelinkurl';
	// $title = get_string('blockonelinkurl','theme_meline29');
	// $description = get_string('blockonelinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
    $name = 'theme_meline29/imageonehovertitle_'.$lang;
	$title = get_string('imageonehovertitle','theme_meline29');
	$description = get_string('imageonehovertitledesc', 'theme_meline29');
	$default = 'Lorem Ipsum.';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
    
    $name = 'theme_meline29/imageonehovercontent_'.$lang;
    $title = get_string('imageonehovercontent', 'theme_meline29');
    $description = get_string('imageonehovercontentdesc', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    /**  Display Manin content Block Two **/
    
    $name = 'theme_meline29/showhideblocktwo_'.$lang;
    $title = get_string('showhideblocktwo','theme_meline29');
    $description = get_string('showhideblocktwodesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/blocktwoimage_'.$lang;
    $title = get_string('blocktwoimage', 'theme_meline29');
    $description = get_string('blocktwoimagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'blocktwoimage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    $name = 'theme_meline29/blocktwotitle';
	// $title = get_string('blocktwotitle','theme_meline29');
	// $description = get_string('blocktwotitledesc', 'theme_meline29');
	// $default = 'Your Messages';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/blocktwolinktxt';
	// $title = get_string('blocktwolinktxt','theme_meline29');
	// $description = get_string('blocktwolinktxtdesc', 'theme_meline29');
	// $default = 'See Your Messages';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/blocktwolinkurl';
	// $title = get_string('blocktwolinkurl','theme_meline29');
	// $description = get_string('blocktwolinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
    $name = 'theme_meline29/imagetwohovertitle_'.$lang;
	$title = get_string('imagetwohovertitle','theme_meline29');
	$description = get_string('imagetwohovertitledesc', 'theme_meline29');
	$default = 'Lorem Ipsum.';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
    
    $name = 'theme_meline29/imagetwohovercontent_'.$lang;
    $title = get_string('imagetwohovercontent', 'theme_meline29');
    $description = get_string('imagetwohovercontentdesc', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    /**  Display Main content Block Three **/

    $name = 'theme_meline29/showhideblockthree_'.$lang;
    $title = get_string('showhideblockthree','theme_meline29');
    $description = get_string('showhideblockthreedesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/blockthreeimage_'.$lang;
    $title = get_string('blockthreeimage', 'theme_meline29');
    $description = get_string('blockthreeimagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'blockthreeimage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    $name = 'theme_meline29/blockthreetitle';
	// $title = get_string('blockthreetitle','theme_meline29');
	// $description = get_string('blockthreetitledesc', 'theme_meline29');
	// $default = 'Your Profile';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/blockthreelinktxt';
	// $title = get_string('blockthreelinktxt','theme_meline29');
	// $description = get_string('blockthreelinktxtdesc', 'theme_meline29');
	// $default = 'See Your Profile';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/blockthreelinkurl';
	// $title = get_string('blockthreelinkurl','theme_meline29');
	// $description = get_string('blockthreelinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
    $name = 'theme_meline29/imagethreehovertitle_'.$lang;
	$title = get_string('imagethreehovertitle','theme_meline29');
	$description = get_string('imagethreehovertitledesc', 'theme_meline29');
	$default = 'Lorem Ipsum.';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
    
    $name = 'theme_meline29/imagethreehovercontent_'.$lang;
    $title = get_string('imagethreehovercontent', 'theme_meline29');
    $description = get_string('imagethreehovercontentdesc', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    /**  Display Manin content Block four **/

    $name = 'theme_meline29/showhideblockfour_'.$lang;
    $title = get_string('showhideblockfour','theme_meline29');
    $description = get_string('showhideblockfourdesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/blockfourimage_'.$lang;
    $title = get_string('blockfourimage', 'theme_meline29');
    $description = get_string('blockfourimagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'blockfourimage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    $name = 'theme_meline29/blockfourtitle';
	// $title = get_string('blockfourtitle','theme_meline29');
	// $description = get_string('blockfourtitledesc', 'theme_meline29');
	// $default = 'Custom Box.1';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/blockfourlinktxt';
	// $title = get_string('blockfourlinktxt','theme_meline29');
	// $description = get_string('blockfourlinktxtdesc', 'theme_meline29');
	// $default = 'Custom Box Link';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/blockfourlinkurl';
	// $title = get_string('blockfourlinkurl','theme_meline29');
	// $description = get_string('blockfourlinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
    $name = 'theme_meline29/imagefourhovertitle_'.$lang;
	$title = get_string('imagefourhovertitle','theme_meline29');
	$description = get_string('imagefourhovertitledesc', 'theme_meline29');
	$default = 'Lorem Ipsum.';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
    
    $name = 'theme_meline29/imagefourhovercontent_'.$lang;
    $title = get_string('imagefourhovercontent', 'theme_meline29');
    $description = get_string('imagefourhovercontentdesc', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    /**  Display Manin content Block five **/

    $name = 'theme_meline29/showhideblockfive_'.$lang;
    $title = get_string('showhideblockfive','theme_meline29');
    $description = get_string('showhideblockfivedesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/blockfiveimage_'.$lang;
    $title = get_string('blockfiveimage', 'theme_meline29');
    $description = get_string('blockfiveimagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'blockfiveimage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    $name = 'theme_meline29/blockfivetitle';
	// $title = get_string('blockfivetitle','theme_meline29');
	// $description = get_string('blockfivetitledesc', 'theme_meline29');
	// $default = 'Custom Box.2';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/blockfivelinktxt';
	// $title = get_string('blockfivelinktxt','theme_meline29');
	// $description = get_string('blockfivelinktxtdesc', 'theme_meline29');
	// $default = 'Custom Box Link';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/blockfivelinkurl';
	// $title = get_string('blockfivelinkurl','theme_meline29');
	// $description = get_string('blockfivelinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
    $name = 'theme_meline29/imagefivehovertitle_'.$lang;
	$title = get_string('imagefivehovertitle','theme_meline29');
	$description = get_string('imagefivehovertitledesc', 'theme_meline29');
	$default = 'Lorem Ipsum.';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
    
    $name = 'theme_meline29/imagefivehovercontent_'.$lang;
    $title = get_string('imagefivehovercontent', 'theme_meline29');
    $description = get_string('imagefivehovercontentdesc', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    /**  Display Manin content Block six **/

    $name = 'theme_meline29/showhideblocksix_'.$lang;
    $title = get_string('showhideblocksix','theme_meline29');
    $description = get_string('showhideblocksixdesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/blocksiximage_'.$lang;
    $title = get_string('blocksiximage', 'theme_meline29');
    $description = get_string('blocksiximagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'blocksiximage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    $name = 'theme_meline29/blocksixtitle';
	// $title = get_string('blocksixtitle','theme_meline29');
	// $description = get_string('blocksixtitledesc', 'theme_meline29');
	// $default = 'Custom Box.3';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/blocksixlinktxt';
	// $title = get_string('blocksixlinktxt','theme_meline29');
	// $description = get_string('blocksixlinktxtdesc', 'theme_meline29');
	// $default = 'Custom Box Link';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/blocksixlinkurl';
	// $title = get_string('blocksixlinkurl','theme_meline29');
	// $description = get_string('blocksixlinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
    $name = 'theme_meline29/imagesixhovertitle_'.$lang;
	$title = get_string('imagesixhovertitle','theme_meline29');
	$description = get_string('imagesixhovertitledesc', 'theme_meline29');
	$default = 'Lorem Ipsum.';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
    
    $name = 'theme_meline29/imagesixhovercontent_'.$lang;
    $title = get_string('imagesixhovercontent', 'theme_meline29');
    $description = get_string('imagesixhovercontentdesc', 'theme_meline29');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    

    // Theme Features Blocks Settings.

 //    $temp->add(new admin_setting_heading('theme_meline29_themefeatures', get_string('themefeatures', 'theme_meline29'), ''));
    
 //    $name = 'theme_meline29/showhidethemefeature';
 //    $title = get_string('showhidethemefeature','theme_meline29');
 //    $description = get_string('showhidethemefeaturedesc', 'theme_meline29');
 //    $default = 1;
 //    $choices = array(0=>'No', 1=>'Yes');
 //    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/featureblocktopcontent';
 //    $title = get_string('featureblocktopcontent', 'theme_meline29');
 //    $description = get_string('featureblocktopcontentdesc', 'theme_meline29');
 //    $default = 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT. LOREM IPSUM SITE AMET EISMOD ICTOR UT. LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT. LOREM IPSUM SITE AMET EISMOD ICTOR UT.';
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    /**  Display Theme Feature Block One **/
    
 //    $name = 'theme_meline29/featureoneimage';
 //    $title = get_string('featureoneimage', 'theme_meline29');
 //    $description = get_string('featureoneimagedesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'featureoneimage');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/featureonetitle';
	// $title = get_string('featureonetitle','theme_meline29');
	// $description = get_string('featureonetitledesc', 'theme_meline29');
	// $default = 'Lorem Ipsum.';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/featureonecontent';
 //    $title = get_string('featureonecontent', 'theme_meline29');
 //    $description = get_string('featureonecontentdesc', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    /**  Display Theme Feature Block Two **/
    
 //    $name = 'theme_meline29/featuretwoimage';
 //    $title = get_string('featuretwoimage', 'theme_meline29');
 //    $description = get_string('featuretwoimagedesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'featuretwoimage');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/featuretwotitle';
	// $title = get_string('featuretwotitle','theme_meline29');
	// $description = get_string('featuretwotitledesc', 'theme_meline29');
	// $default = 'Lorem Ipsum.';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/featuretwocontent';
 //    $title = get_string('featuretwocontent', 'theme_meline29');
 //    $description = get_string('featuretwocontentdesc', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    /**  Display Theme Feature Block Three **/
    
 //    $name = 'theme_meline29/featurethreeimage';
 //    $title = get_string('featurethreeimage', 'theme_meline29');
 //    $description = get_string('featurethreeimagedesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'featurethreeimage');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/featurethreetitle';
	// $title = get_string('featurethreetitle','theme_meline29');
	// $description = get_string('featurethreetitledesc', 'theme_meline29');
	// $default = 'Lorem Ipsum.';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/featurethreecontent';
 //    $title = get_string('featurethreecontent', 'theme_meline29');
 //    $description = get_string('featurethreecontentdesc', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    /**  Display Theme Feature Block Four **/
    
 //    $name = 'theme_meline29/featurefourimage';
 //    $title = get_string('featurefourimage', 'theme_meline29');
 //    $description = get_string('featurefourimagedesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'featurefourimage');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/featurefourtitle';
	// $title = get_string('featurefourtitle','theme_meline29');
	// $description = get_string('featurefourtitledesc', 'theme_meline29');
	// $default = 'Lorem Ipsum.';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
    
 //    $name = 'theme_meline29/featurefourcontent';
 //    $title = get_string('featurefourcontent', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
 //    $description = get_string('featurefourcontentdesc', 'theme_meline29');
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
    
    // Theme Site News Block Top Settings.

 //    $temp->add(new admin_setting_heading('theme_meline29_sitenewsblock', get_string('sitenewsblock', 'theme_meline29'), ''));
    
    
 //    $name = 'theme_meline29/sitenewsblocktitle';
	// $title = get_string('sitenewsblocktitle','theme_meline29');
	// $description = get_string('sitenewsblocktitledesc', 'theme_meline29');
	// $default = 'Site News';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/sitenewsblockcontent';
 //    $title = get_string('sitenewsblockcontent', 'theme_meline29');
 //    $description = get_string('sitenewsblockcontentdesc', 'theme_meline29');
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
  
    
    // Theme News Events Blocks Settings.

 //    $temp->add(new admin_setting_heading('theme_meline29_newevents', get_string('newevents', 'theme_meline29'), ''));
    
 //    $name = 'theme_meline29/showhideeventsblock';
 //    $title = get_string('showhideeventsblock','theme_meline29');
 //    $description = get_string('showhideeventsblockdesc', 'theme_meline29');
 //    $default = 1;
 //    $choices = array(0=>'No', 1=>'Yes');
 //    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
 //    $temp->add($setting);

 //    $name = 'theme_meline29/neweventstxt';
	// $title = get_string('neweventstxt','theme_meline29');
	// $description = get_string('neweventstxtdesc', 'theme_meline29');
	// $default = 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT. LOREM IPSUM SITE ……';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/neweventslinktxt';
	// $title = get_string('neweventslinktxt','theme_meline29');
	// $description = get_string('neweventslinktxtdesc', 'theme_meline29');
	// $default = 'NEW EVENTS';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/neweventslinkurl';
	// $title = get_string('neweventslinkurl','theme_meline29');
	// $description = get_string('neweventslinkurldesc', 'theme_meline29');
	// $default = '#';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);

 //    $name = 'theme_meline29/testimonialoneuserpic';
 //    $title = get_string('testimonialoneuserpic', 'theme_meline29');
 //    $description = get_string('testimonialoneuserpicdesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'testimonialoneuserpic');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    


 // Welcome Block - 3rd Blade Strcuture.

    $temp->add(new admin_setting_heading('theme_meline29_welcome', get_string('welcome', 'theme_meline29'), ''));
    
    $name = 'theme_meline29/showhidewelcomeblock_'.$lang;
    $title = get_string('showhidewelcomeblock','theme_meline29');
    $description = get_string('showhidewelcomeblockdesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/welcometitle_'.$lang;
    $title = get_string('welcometitle','theme_meline29');
    $description = get_string('welcometitledesc', 'theme_meline29');
    $default = 'Welcome.';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // $name = 'theme_meline29/welcomecontentimage';
    // $title = get_string('welcomecontentimage','theme_meline29');
    // $description = get_string('welcomecontentimagedesc', 'theme_meline29');
    // $setting = new admin_setting_configstoredfile($name, $title, $description, 'welcomecontentimage');
    // $setting->set_updatedcallback('theme_reset_all_caches');
    // $temp->add($setting);

    $name = 'theme_meline29/featureoneimage_'.$lang;
    $title = get_string('welcomecontentimage', 'theme_meline29');
    $description = get_string('welcomecontentimagedesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'featureoneimage_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Caption.
    $name = 'theme_meline29/welcomecontent_'.$lang;
    $title = get_string('welcomecontent', 'theme_meline29');
    $description = get_string('welcomecontentdesc', 'theme_meline29');
    $default = 'Lorem ipsum lacus ut eniascet ingerto aliiqt es site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen lusto dolor ltissim comes cuml ascet. Eismod ictor ut ligulate lusto dolor.';
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);







    // Theme Testimonial Blocks Settings - 4th Blade Structure.

    $temp->add(new admin_setting_heading('theme_meline29_testimonials', get_string('testimonials', 'theme_meline29'), ''));
    
    $name = 'theme_meline29/showhidetestimonialsblock_'.$lang;
    $title = get_string('showhidetestimonialsblock','theme_meline29');
    $description = get_string('showhidetestimonialsblockdesc', 'theme_meline29');
    $default = 1;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    
    $name = 'theme_meline29/testimonialblocktitle_'.$lang;
	$title = get_string('testimonialblocktitle','theme_meline29');
	$description = get_string('testimonialblocktitledesc', 'theme_meline29');
	$default = 'Education Experts Says';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$temp->add($setting);
	
	$name = 'theme_meline29/testimonialblockcontent_'.$lang;
    $title = get_string('testimonialblockcontent', 'theme_meline29');    
    $description = get_string('testimonialblockcontentdesc', 'theme_meline29');
    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_meline29/testimonialoneuserpic_'.$lang;
    $title = get_string('testimonialoneuserpic', 'theme_meline29');
    $description = get_string('testimonialoneuserpicdesc', 'theme_meline29');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'testimonialoneuserpic_'.$lang);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
 //    /**  Testimonial One **/
    
 //    $name = 'theme_meline29/testimonialonecontent';
 //    $title = get_string('testimonialonecontent', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
 //    $description = get_string('testimonialonecontentdesc', 'theme_meline29');
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialoneuserpic';
 //    $title = get_string('testimonialoneuserpic', 'theme_meline29');
 //    $description = get_string('testimonialoneuserpicdesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'testimonialoneuserpic');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialoneuser';
	// $title = get_string('testimonialoneuser','theme_meline29');
	// $description = get_string('testimonialoneuserdesc', 'theme_meline29');
	// $default = 'Jonathan Doe';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/testimonialoneuserroll';
	// $title = get_string('testimonialoneuserroll','theme_meline29');
	// $description = get_string('testimonialoneuserrolldesc', 'theme_meline29');
	// $default = 'User Roll';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// /**  Testimonial Two **/
    
 //    $name = 'theme_meline29/testimonialtwocontent';
 //    $title = get_string('testimonialtwocontent', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
 //    $description = get_string('testimonialtwocontentdesc', 'theme_meline29');
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialtwouserpic';
 //    $title = get_string('testimonialtwouserpic', 'theme_meline29');
 //    $description = get_string('testimonialtwouserpicdesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'testimonialtwouserpic');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialtwouser';
	// $title = get_string('testimonialtwouser','theme_meline29');
	// $description = get_string('testimonialtwouserdesc', 'theme_meline29');
	// $default = 'Jonathan Doe';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/testimonialtwouserroll';
	// $title = get_string('testimonialtwouserroll','theme_meline29');
	// $description = get_string('testimonialtwouserrolldesc', 'theme_meline29');
	// $default = 'User Roll';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// /**  Testimonial Three **/
    
 //    $name = 'theme_meline29/testimonialthreecontent';
 //    $title = get_string('testimonialthreecontent', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
 //    $description = get_string('testimonialthreecontentdesc', 'theme_meline29');
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialthreeuserpic';
 //    $title = get_string('testimonialthreeuserpic', 'theme_meline29');
 //    $description = get_string('testimonialthreeuserpicdesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'testimonialthreeuserpic');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialthreeuser';
	// $title = get_string('testimonialthreeuser','theme_meline29');
	// $description = get_string('testimonialthreeuserdesc', 'theme_meline29');
	// $default = 'Jonathan Doe';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/testimonialthreeuserroll';
	// $title = get_string('testimonialthreeuserroll','theme_meline29');
	// $description = get_string('testimonialthreeuserrolldesc', 'theme_meline29');
	// $default = 'User Roll';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// /**  Testimonial Four **/
    
 //    $name = 'theme_meline29/testimonialfourcontent';
 //    $title = get_string('testimonialfourcontent', 'theme_meline29');
 //    $default = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
 //    $description = get_string('testimonialfourcontentdesc', 'theme_meline29');
 //    $setting = new admin_setting_configtextarea($name, $title, $description, '');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialfouruserpic';
 //    $title = get_string('testimonialfouruserpic', 'theme_meline29');
 //    $description = get_string('testimonialfouruserpicdesc', 'theme_meline29');
 //    $setting = new admin_setting_configstoredfile($name, $title, $description, 'testimonialfouruserpic');
 //    $setting->set_updatedcallback('theme_reset_all_caches');
 //    $temp->add($setting);
    
 //    $name = 'theme_meline29/testimonialfouruser';
	// $title = get_string('testimonialfouruser','theme_meline29');
	// $description = get_string('testimonialfouruserdesc', 'theme_meline29');
	// $default = 'Jonathan Doe';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// $name = 'theme_meline29/testimonialfouruserroll';
	// $title = get_string('testimonialfouruserroll','theme_meline29');
	// $description = get_string('testimonialfouruserrolldesc', 'theme_meline29');
	// $default = 'User Roll';
	// $setting = new admin_setting_configtext($name, $title, $description, $default);
	// $temp->add($setting);
	
	// /** Frontpage Contact Form Settings **/

 //    $temp->add(new admin_setting_heading('theme_meline29_contactfrm', get_string('contactfrm', 'theme_meline29'), ''));
    
 //        /**  Display Frontpage Banner **/
 //    $name = 'theme_meline29/showhidecontactfrm';
 //    $title = get_string('showhidecontactfrm','theme_meline29');
 //    $description = get_string('showhidecontactfrmdesc', 'theme_meline29');
 //    $default = 1;
 //    $choices = array(0=>'No', 1=>'Yes');
 //    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
 //    $temp->add($setting);


$ADMIN->add('theme_meline29', $temp);
?>
<script type="text/javascript" src="/mod/book/sample/js/jquery.1.7.1.js"></script>
<script src="/theme/meline29/javascript/bootstrap.min-3.2.0.js" type="text/javascript"></script>
<script type="text/javascript">$(document).ready(function () {
    var lang = "<?=$lang?>";
    var securl = "<?=$securl?>";
    if(securl == "theme_meline29_frontcontent"){
    var str_url = "settings.php?section=theme_meline29_frontcontent&lang="+lang;
        $("#adminsettings").attr("action",str_url);
    }
    console.log("Test12356");
});
</script>