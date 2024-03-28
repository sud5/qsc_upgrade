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
// GNU General Public License for meline29 details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle's Clean theme, an example of how to make a Bootstrap theme
 *
 * DO NOT MODIFY THIS THEME!
 * COPY IT FIRST, THEN RENAME THE COPY AND MODIFY IT INSTEAD.
 *
 * For full information about creating Moodle themes, see:
 * http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   theme_clean
 * @copyright 2013 Moodle, moodle.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$cur_lang = current_language();

if(!isset($_GET['lang'])){
 $_SESSION['slidelangfe']  = $lang = '_en';
 if(!empty($USER) && $USER->id!=0){
    $lang = "_".$USER->lang;
 }
}else{
 $_SESSION['slidelangfe']  = $lang = "_".$_GET['lang'];
}

if($lang == "_en" || $_SESSION['slidelangfe'] == "_en"){
 $_SESSION['slidelangfe']  = $lang = "_".$cur_lang;
}

//echo $lang." ghiyt";

// Get the HTML for the settings bits.
$html = theme_meline29_get_html_for_settings($OUTPUT, $PAGE, $lang);

// Set default (LTR) layout mark-up for a three column page.
$regionmainbox = 'span9';
$regionmain = 'span9 pull-right';
$sidepre = 'span3 desktop-first-column';
$sidepost = 'span3 pull-right';
// Reset layout mark-up for RTL languages.
if (right_to_left()) {
    $regionmainbox = 'span9 pull-right';
    $regionmain = 'span9';
    $sidepre = 'span3 pull-right';
    $sidepost = 'span3 desktop-first-column';
}

// Header Top Phone Number Settings.
// echo "<pre>";
// print_r($PAGE->theme->settings);
// exit;
$hasphonenumber = (!empty($PAGE->theme->settings->phonenumber));

if (!empty($PAGE->theme->settings->phonenumber)) {
    $phonenumber = $PAGE->theme->settings->phonenumber;
} 

else {
    $phonenumber = ' 011-6782975, 011-8508105';
}

//Banner settings
$showhidebanner = (empty($PAGE->theme->settings->showhidebanner) ||$PAGE->theme->settings->showhidebanner < 1) ? 0 : 1;
$showhidewelcomeblock = (empty($PAGE->theme->settings->{'showhidewelcomeblock'.$lang}) ||$PAGE->theme->settings->{'showhidewelcomeblock'.$lang} < 1) ? 0 : 1;
$showhideblockone = (empty($PAGE->theme->settings->showhideblockone) ||$PAGE->theme->settings->showhideblockone < 1) ? 0 : 1;
$showhideblocktwo = (empty($PAGE->theme->settings->showhideblockone) ||$PAGE->theme->settings->showhideblocktwo < 1) ? 0 : 1;
$showhideblockthree = (empty($PAGE->theme->settings->showhideblockone) ||$PAGE->theme->settings->showhideblockthree < 1) ? 0 : 1;
$showhideblockfour = (empty($PAGE->theme->settings->showhideblockone) ||$PAGE->theme->settings->showhideblockfour < 1) ? 0 : 1;
$showhideblockfive = (empty($PAGE->theme->settings->showhideblockone) ||$PAGE->theme->settings->showhideblockfive < 1) ? 0 : 1;
$showhideblocksix = (empty($PAGE->theme->settings->showhideblockone) ||$PAGE->theme->settings->showhideblocksix < 1) ? 0 : 1;


$hasbannercaption = (!empty($PAGE->theme->settings->bannercaption));
$hasbannerbtnonetxt = (!empty($PAGE->theme->settings->bannerbtnonetxt));
$hasbannerbtnoneurl = (!empty($PAGE->theme->settings->bannerbtnonetxt));
$hasbannerbtntwotxt = (!empty($PAGE->theme->settings->bannerbtntwotxt));
$hasbannerbtntwourl = (!empty($PAGE->theme->settings->bannerbtntwotxt));

if (!empty($PAGE->theme->settings->bannercaption)) {
    $bannercaption = $PAGE->theme->settings->bannercaption;
} 

else {
    $bannercaption = 'ONLINE EDUCATION More Than 1000 COURSES Available';
}

if (!empty($PAGE->theme->settings->bannerbtnonetxt)) {
    $bannerbtnonetxt = $PAGE->theme->settings->bannerbtnonetxt;
} 

else {
    $bannerbtnonetxt = 'Lorem Ipsum'/* 'ONLINE EDUCATION More Than 1000 COURSES Available' */;
}

if (!empty($PAGE->theme->settings->bannerbtnoneurl)) {
    $bannerbtnoneurl = $PAGE->theme->settings->bannerbtnoneurl;
} 

else {
    $bannerbtnoneurl = '#';
}

if (!empty($PAGE->theme->settings->bannerbtntwotxt)) {
    $bannerbtntwotxt = $PAGE->theme->settings->bannerbtntwotxt;
} 

else {
    $bannerbtntwotxt = 'Lorem Ipsum';
}

if (!empty($PAGE->theme->settings->bannerbtntwourl)) {
    $bannerbtntwourl = $PAGE->theme->settings->bannerbtntwourl;
} 

else {
    $bannerbtntwourl = '#';
}
//Welcome block settings

$haswelcometitle = (!empty($PAGE->theme->settings->{'welcometitle'.$lang}));
$haswelcomecontent = (!empty($PAGE->theme->settings->{'welcomecontent'.$lang}));
$hasfeatureoneimage = (!empty($PAGE->theme->settings->{'featureoneimage'.$lang}));

if (!empty($PAGE->theme->settings->{'welcometitle'.$lang})) {
    $welcometitle = $PAGE->theme->settings->{'welcometitle'.$lang};
} 

else {
    $welcometitle = 'Lorem Ipsum';
}

if (!empty($PAGE->theme->settings->{'welcomecontent'.$lang})) {
    $welcomecontent = $PAGE->theme->settings->{'welcomecontent'.$lang};
} 

else {
    $welcomecontent = 'Lorem ipsum lacus ut eniascet ingerto aliiqt es site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen lusto dolor ltissim comes cuml ascet. Eismod ictor ut ligulate lusto dolor.';
}

if (!empty($PAGE->theme->settings->{'featureoneimage'.$lang})) {
   $welcomecontentimage = $PAGE->theme->setting_file_url("featureoneimage{$lang}", "featureoneimage{$lang}");
} else {
   $welcomecontentimage = $OUTPUT->pix_url('img1', 'theme');
}


// Main Content Blocks Settings.

// Block One

$hasblockoneimage = (!empty($PAGE->theme->settings->{'hasblockoneimage'.$lang}));
$hasblockonetitle = (!empty($PAGE->theme->settings->{'blockonetitle'.$lang}));
$hasblockonelinktxt = (!empty($PAGE->theme->settings->{'blockonelinktxt'.$lang}));
$hasblockonelinkurl = (!empty($PAGE->theme->settings->{'blockonelinkurl'.$lang}));
$hasimageonehovertitle = (!empty($PAGE->theme->settings->{'imageonehovertitle'.$lang}));
$hasimageonehovercontent = (!empty($PAGE->theme->settings->{'imageonehovercontent'.$lang}));

if (!empty($PAGE->theme->settings->{'blockoneimage'.$lang})) {
   $blockoneimage = $PAGE->theme->setting_file_url("blockoneimage{$lang}", "blockoneimage{$lang}");
} else {
    $blockoneimage = $OUTPUT->pix_url('img1', 'theme');
}

if (!empty($PAGE->theme->settings->{'blockonetitle'.$lang})) {
    $blockonetitle = $PAGE->theme->settings->{'blockonetitle'.$lang};
} 

else {
    $blockonetitle = 'Available Courses';
}

if (!empty($PAGE->theme->settings->{'blockonelinktxt'.$lang})) {
    $blockonelinktxt = $PAGE->theme->settings->{'blockonelinktxt'.$lang};
} 

else {
    $blockonelinktxt = 'See All Courses';
}

if (!empty($PAGE->theme->settings->{'blockonelinkurl'.$lang})) {
    $blockonelinkurl = $PAGE->theme->settings->{'blockonelinkurl'.$lang};
} 

else {
    $blockonelinkurl = '#';
}

if (!empty($PAGE->theme->settings->{'imageonehovertitle'.$lang})) {
    $imageonehovertitle = $PAGE->theme->settings->{'imageonehovertitle'.$lang};
} 

else {
    $imageonehovertitle = 'Lorem Tpsum Text.';
}

if (!empty($PAGE->theme->settings->{'imageonehovercontent'.$lang})) {
    $imageonehovercontent = $PAGE->theme->settings->{'imageonehovercontent'.$lang};
} 

else {
    $imageonehovercontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site.';
}

// Block Two
// echo "<pre>";
// print_r($PAGE->theme->settings);
$hasblocktwoimage = (!empty($PAGE->theme->settings->{'hasblocktwoimage'.$lang}));
$hasblocktwotitle = (!empty($PAGE->theme->settings->{'blocktwotitle'.$lang}));
$hasblocktwolinktxt = (!empty($PAGE->theme->settings->{'blocktwolinktxt'.$lang}));
$hasblocktwolinkurl = (!empty($PAGE->theme->settings->{'blocktwolinkurl'.$lang}));
$hasimagetwohovertitle = (!empty($PAGE->theme->settings->{'imagetwohovertitle'.$lang}));
$hasimagetwohovercontent = (!empty($PAGE->theme->settings->{'imagetwohovercontent'.$lang}));

if (!empty($PAGE->theme->settings->{'blocktwoimage'.$lang})) {      
   $blocktwoimage = $PAGE->theme->setting_file_url("blocktwoimage{$lang}", "blocktwoimage{$lang}");
} else {
    $blocktwoimage = $OUTPUT->pix_url('img2', 'theme');
}

if (!empty($PAGE->theme->settings->{'blocktwotitle'.$lang})) {
    $blocktwotitle = $PAGE->theme->settings->{'blocktwotitle'.$lang};
} 

else {
    $blocktwotitle = 'Your Messages';
}

if (!empty($PAGE->theme->settings->{'blocktwolinktxt'.$lang})) {
    $blocktwolinktxt = $PAGE->theme->settings->{'blocktwolinktxt'.$lang};
} 

else {
    $blocktwolinktxt = 'See Your Messages';
}

if (!empty($PAGE->theme->settings->{'blocktwolinkurl'.$lang})) {
    $blocktwolinkurl = $PAGE->theme->settings->{'blocktwolinkurl'.$lang};
} 

else {
    $blocktwolinkurl = '#';
}

if (!empty($PAGE->theme->settings->{'imagetwohovertitle'.$lang})) {
    $imagetwohovertitle = $PAGE->theme->settings->{'imagetwohovertitle'.$lang};
} 

else {
    $imagetwohovertitle = 'Lorem Ipsum Text.';
}

if (!empty($PAGE->theme->settings->{'imagetwohovercontent'.$lang})) {
    $imagetwohovercontent = $PAGE->theme->settings->{'imagetwohovercontent'.$lang};
} 

else {
    $imagetwohovercontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site.';
}

// Block Three

$hasblockthreeimage = (!empty($PAGE->theme->settings->{'hasblockthreeimage'.$lang}));
$hasblockthreetitle = (!empty($PAGE->theme->settings->{'blockthreetitle'.$lang}));
$hasblockthreelinktxt = (!empty($PAGE->theme->settings->{'blockthreelinktxt'.$lang}));
$hasblockthreelinkurl = (!empty($PAGE->theme->settings->{'blockthreelinkurl'.$lang}));
$hasimagethreehovertitle = (!empty($PAGE->theme->settings->{'imagethreehovertitle'.$lang}));
$hasimagethreehovercontent = (!empty($PAGE->theme->settings->{'imagethreehovercontent'.$lang}));

if (!empty($PAGE->theme->settings->{'blockthreeimage'.$lang})) {
   $blockthreeimage = $PAGE->theme->setting_file_url("blockthreeimage{$lang}", "blockthreeimage{$lang}");
} else {
    $blockthreeimage = $OUTPUT->pix_url('img3', 'theme');
}

if (!empty($PAGE->theme->settings->{'blockthreetitle'.$lang})) {
    $blockthreetitle = $PAGE->theme->settings->{'blockthreetitle'.$lang};
} 

else {
    $blockthreetitle = 'Your Profile';
}

if (!empty($PAGE->theme->settings->{'blockthreelinktxt'.$lang})) {
    $blockthreelinktxt = $PAGE->theme->settings->{'blockthreelinktxt'.$lang};
} 

else {
    $blockthreelinktxt = 'See Your Profile';
}

if (!empty($PAGE->theme->settings->{'blockthreelinkurl'.$lang})) {
    $blockthreelinkurl = $PAGE->theme->settings->{'blockthreelinkurl'.$lang};
} 

else {
    $blockthreelinkurl = '#';
}

if (!empty($PAGE->theme->settings->{'imagethreehovertitle'.$lang})) {
    $imagethreehovertitle = $PAGE->theme->settings->{'imagethreehovertitle'.$lang};
} 

else {
    $imagethreehovertitle = 'Lorem Ipsum Text.';
}

if (!empty($PAGE->theme->settings->{'imagethreehovercontent'.$lang})) {
    $imagethreehovercontent = $PAGE->theme->settings->{'imagethreehovercontent'.$lang};
} 

else {
    $imagethreehovercontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site.';
}

// Block Four

$hasblockfourimage = (!empty($PAGE->theme->settings->{'hasblockfourimage'.$lang}));
$hasblockfourtitle = (!empty($PAGE->theme->settings->{'blockfourtitle'.$lang}));
$hasblockfourlinktxt = (!empty($PAGE->theme->settings->{'blockfourlinktxt'.$lang}));
$hasblockfourlinkurl = (!empty($PAGE->theme->settings->{'blockfourlinkurl'.$lang}));
$hasimagefourhovertitle = (!empty($PAGE->theme->settings->{'imagefourhovertitle'.$lang}));
$hasimagefourhovercontent = (!empty($PAGE->theme->settings->{'imagefourhovercontent'.$lang}));

if (!empty($PAGE->theme->settings->{'blockfourimage'.$lang})) {
   $blockfourimage = $PAGE->theme->setting_file_url("blockfourimage{$lang}", "blockfourimage{$lang}");
} else {
    $blockfourimage = $OUTPUT->pix_url('img4', 'theme');
}

if (!empty($PAGE->theme->settings->{'blockfourtitle'.$lang})) {
    $blockfourtitle = $PAGE->theme->settings->{'blockfourtitle'.$lang};
} 

else {
    $blockfourtitle = 'Custom Box.1';
}

if (!empty($PAGE->theme->settings->{'blockfourlinktxt'.$lang})) {
    $blockfourlinktxt = $PAGE->theme->settings->{'blockfourlinktxt'.$lang};
} 

else {
    $blockfourlinktxt = 'Custom Box Link';
}

if (!empty($PAGE->theme->settings->{'blockfourlinkurl'.$lang})) {
    $blockfourlinkurl = $PAGE->theme->settings->{'blockfourlinkurl'.$lang};
} 

else {
    $blockfourlinkurl = '#';
}

if (!empty($PAGE->theme->settings->{'imagefourhovertitle'.$lang})) {
    $imagefourhovertitle = $PAGE->theme->settings->{'imagefourhovertitle'.$lang};
} 

else {
    $imagefourhovertitle = 'Lorem Ipsum Text.';
}

if (!empty($PAGE->theme->settings->{'imagefourhovercontent'.$lang})) {
    $imagefourhovercontent = $PAGE->theme->settings->{'imagefourhovercontent'.$lang};
} 

else {
    $imagefourhovercontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site.';
}

// Block Five

$hasblockfiveimage = (!empty($PAGE->theme->settings->{'hasblockfiveimage'.$lang}));
$hasblockfivetitle = (!empty($PAGE->theme->settings->{'blockfivetitle'.$lang}));
$hasblockfivelinktxt = (!empty($PAGE->theme->settings->{'blockfivelinktxt'.$lang}));
$hasblockfivelinkurl = (!empty($PAGE->theme->settings->{'blockfivelinkurl'.$lang}));
$hasimagefivehovertitle = (!empty($PAGE->theme->settings->{'imagefivehovertitle'.$lang}));
$hasimagefivehovercontent = (!empty($PAGE->theme->settings->{'imagefivehovercontent'.$lang}));

if (!empty($PAGE->theme->settings->{'blockfiveimage'.$lang})) {
   $blockfiveimage = $PAGE->theme->setting_file_url("blockfiveimage{$lang}", "blockfiveimage{$lang}");
} else {
    $blockfiveimage = $OUTPUT->pix_url('img5', 'theme');
}

if (!empty($PAGE->theme->settings->{'blockfivetitle'.$lang})) {
    $blockfivetitle = $PAGE->theme->settings->{'blockfivetitle'.$lang};
} 

else {
    $blockfivetitle = 'Custom Box.2';
}

if (!empty($PAGE->theme->settings->{'blockfivelinktxt'.$lang})) {
    $blockfivelinktxt = $PAGE->theme->settings->{'blockfivelinktxt'.$lang};
} 

else {
    $blockfivelinktxt = 'Custom Box Link';
}

if (!empty($PAGE->theme->settings->{'blockfivelinkurl'.$lang})) {
    $blockfivelinkurl = $PAGE->theme->settings->{'blockfivelinkurl'.$lang};
} 

else {
    $blockfivelinkurl = '#';
}

if (!empty($PAGE->theme->settings->{'imagefivehovertitle'.$lang})) {
    $imagefivehovertitle = $PAGE->theme->settings->{'imagefivehovertitle'.$lang};
} 

else {
    $imagefivehovertitle = 'Lorem Ipsum Text.';
}

if (!empty($PAGE->theme->settings->{'imagefivehovercontent'.$lang})) {
    $imagefivehovercontent = $PAGE->theme->settings->{'imagefivehovercontent'.$lang};
} 

else {
    $imagefivehovercontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site.';
}

// Block Six

$hasblocksiximage = (!empty($PAGE->theme->settings->{'hasblocksiximage'.$lang}));
$hasblocksixtitle = (!empty($PAGE->theme->settings->{'blocksixtitle'.$lang}));
$hasblocksixlinktxt = (!empty($PAGE->theme->settings->{'blocksixlinktxt'.$lang}));
$hasblocksixlinkurl = (!empty($PAGE->theme->settings->{'blocksixlinkurl'.$lang}));
$hasimagesixhovertitle = (!empty($PAGE->theme->settings->{'imagesixhovertitle'.$lang}));
$hasimagesixhovercontent = (!empty($PAGE->theme->settings->{'imagesixhovercontent'.$lang}));

if (!empty($PAGE->theme->settings->{'blocksiximage'.$lang})) {
   $blocksiximage = $PAGE->theme->setting_file_url("blocksiximage{$lang}", "blocksiximage{$lang}");
} else {
    $blocksiximage = $OUTPUT->pix_url('img6', 'theme');
}

if (!empty($PAGE->theme->settings->{'blocksixtitle'.$lang})) {
    $blocksixtitle = $PAGE->theme->settings->{'blocksixtitle'.$lang};
} 

else {
    $blocksixtitle = 'Custom Box.3';
}

if (!empty($PAGE->theme->settings->{'blocksixlinktxt'.$lang})) {
    $blocksixlinktxt = $PAGE->theme->settings->{'blocksixlinktxt'.$lang};
} 

else {
    $blocksixlinktxt = 'Custom Box Link';
}

if (!empty($PAGE->theme->settings->{'blocksixlinkurl'.$lang})) {
    $blocksixlinkurl = $PAGE->theme->settings->{'blocksixlinkurl'.$lang};
} 

else {
    $blocksixlinkurl = '#';
}

if (!empty($PAGE->theme->settings->{'imagesixhovertitle'.$lang})) {
    $imagesixhovertitle = $PAGE->theme->settings->{'imagesixhovertitle'.$lang};
} 

else {
    $imagesixhovertitle = 'Lorem Ipsum Text.';
}

if (!empty($PAGE->theme->settings->{'imagesixhovercontent'.$lang})) {
    $imagesixhovercontent = $PAGE->theme->settings->{'imagesixhovercontent'.$lang};
} 

else {
    $imagesixhovercontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site.';
}

// Feature Block Settings.

$showhidethemefeature = (empty($PAGE->theme->settings->{'showhidethemefeature'.$lang}) ||$PAGE->theme->settings->{'showhidethemefeature'.$lang} < 1) ? 0 : 1;


$hasfeatureblocktopcontent = (!empty($PAGE->theme->settings->featureblocktopcontent));

if (!empty($PAGE->theme->settings->featureblocktopcontent)) {
    $featureblocktopcontent = $PAGE->theme->settings->featureblocktopcontent;
} 

else {
    $featureblocktopcontent = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum site amet eismod ictor ut .';
}

// Block One

$hasfeatureoneimage = (!empty($PAGE->theme->settings->hasfeatureoneimage));
$hasfeatureonetitle = (!empty($PAGE->theme->settings->featureonetitle));
$hasfeatureonecontent = (!empty($PAGE->theme->settings->featureonecontent));

if (!empty($PAGE->theme->settings->{"featureoneimage".$lang})) {
   $featureoneimage = $PAGE->theme->setting_file_url("featureoneimage{$lang}", "featureoneimage{$lang}");
} else {
    $featureoneimage = $OUTPUT->pix_url('1', 'theme');
}

if (!empty($PAGE->theme->settings->featureonetitle)) {
    $featureonetitle = $PAGE->theme->settings->featureonetitle;
} 

else {
    $featureonetitle = 'EEASY TO CUSTOMIZE';
}

if (!empty($PAGE->theme->settings->featureonecontent)) {
    $featureonecontent = $PAGE->theme->settings->featureonecontent;
} 

else {
    $featureonecontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
}

// Block Two

$hasfeaturetwoimage = (!empty($PAGE->theme->settings->hasfeaturetwoimage));
$hasfeaturetwotitle = (!empty($PAGE->theme->settings->featuretwotitle));
$hasfeaturetwocontent = (!empty($PAGE->theme->settings->featuretwocontent));

if (!empty($PAGE->theme->settings->featuretwoimage)) {
   $featuretwoimage = $PAGE->theme->setting_file_url('featuretwoimage', 'featuretwoimage');
} else {
    $featuretwoimage = $OUTPUT->pix_url('2', 'theme');
}

if (!empty($PAGE->theme->settings->featuretwotitle)) {
    $featuretwotitle = $PAGE->theme->settings->featuretwotitle;
} 

else {
    $featuretwotitle = 'FULLY RESPONSIVE';
}

if (!empty($PAGE->theme->settings->featuretwocontent)) {
    $featuretwocontent = $PAGE->theme->settings->featuretwocontent;
} 

else {
    $featuretwocontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
}

// Block Three

$hasfeaturethreeimage = (!empty($PAGE->theme->settings->hasfeaturethreeimage));
$hasfeaturethreetitle = (!empty($PAGE->theme->settings->featurethreetitle));
$hasfeaturethreecontent = (!empty($PAGE->theme->settings->featurethreecontent));

if (!empty($PAGE->theme->settings->featurethreeimage)) {
   $featurethreeimage = $PAGE->theme->setting_file_url('featurethreeimage', 'featurethreeimage');
} else {
    $featurethreeimage = $OUTPUT->pix_url('3', 'theme');
}

if (!empty($PAGE->theme->settings->featurethreetitle)) {
    $featurethreetitle = $PAGE->theme->settings->featurethreetitle;
} 

else {
    $featurethreetitle = 'DEDICATED SUPPORT';
}

if (!empty($PAGE->theme->settings->featurethreecontent)) {
    $featurethreecontent = $PAGE->theme->settings->featurethreecontent;
} 

else {
    $featurethreecontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
}

// Block Four

$hasfeaturefourimage = (!empty($PAGE->theme->settings->hasfeaturefourimage));
$hasfeaturefourtitle = (!empty($PAGE->theme->settings->featurefourtitle));
$hasfeaturefourcontent = (!empty($PAGE->theme->settings->featurefourcontent));

if (!empty($PAGE->theme->settings->featurefourimage)) {
   $featurefourimage = $PAGE->theme->setting_file_url('featurefourimage', 'featurefourimage');
} else {
    $featurefourimage = $OUTPUT->pix_url('4', 'theme');
}

if (!empty($PAGE->theme->settings->featurefourtitle)) {
    $featurefourtitle = $PAGE->theme->settings->featurefourtitle;
} 

else {
    $featurefourtitle = 'CREATIVE DESIGN';
}

if (!empty($PAGE->theme->settings->featurefourcontent)) {
    $featurefourcontent = $PAGE->theme->settings->featurefourcontent;
} 

else {
    $featurefourcontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen.';
}

// Site News Block Settings.

$hassitenewsblocktitle = (!empty($PAGE->theme->settings->sitenewsblocktitle));
$hassitenewsblockcontent = (!empty($PAGE->theme->settings->sitenewsblockcontent));

if (!empty($PAGE->theme->settings->sitenewsblocktitle)) {
    $sitenewsblocktitle = $PAGE->theme->settings->sitenewsblocktitle;
}
else {
    $sitenewsblocktitle = 'SITE NEWS';
}

if (!empty($PAGE->theme->settings->sitenewsblockcontent)) {
    $sitenewsblockcontent = $PAGE->theme->settings->sitenewsblockcontent;
}
else {
    $sitenewsblockcontent = '“Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.”';
}

// News Event Block Setting.

$showhideeventsblock = (empty($PAGE->theme->settings->showhideeventsblock) ||$PAGE->theme->settings->showhideeventsblock < 1) ? 0 :


$hasneweventstxt = (!empty($PAGE->theme->settings->neweventstxt));
$hasneweventslinktxt = (!empty($PAGE->theme->settings->neweventslinktxt));
$hasneweventslinkurl = (!empty($PAGE->theme->settings->neweventslinkurl));

if (!empty($PAGE->theme->settings->neweventstxt)) {
    $neweventstxt = $PAGE->theme->settings->neweventstxt;
} 

else {
    $neweventstxt = 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT. LOREM IPSUM SITE ……';
}

if (!empty($PAGE->theme->settings->neweventslinktxt)) {
    $neweventslinktxt = $PAGE->theme->settings->neweventslinktxt;
} 

else {
    $neweventslinktxt = 'NEW EVENTS';
}

if (!empty($PAGE->theme->settings->neweventslinkurl)) {
    $neweventslinkurl = $PAGE->theme->settings->neweventslinkurl;
} 

else {
    $neweventslinkurl = '#';
}

// Testimonials Block Setting.

$showhidetestimonialsblock = (empty($PAGE->theme->settings->{"showhidetestimonialsblock".$lang}) ||$PAGE->theme->settings->{"showhidetestimonialsblock".$lang} < 1) ? 0 :

$hastestimonialblocktitle = (!empty($PAGE->theme->settings->{"testimonialblocktitle".$lang}));
$hastestimonialblockcontent = (!empty($PAGE->theme->settings->{"testimonialblockcontent".$lang}));

if (!empty($PAGE->theme->settings->{"testimonialblocktitle".$lang})) {
    $testimonialblocktitle = $PAGE->theme->settings->{"testimonialblocktitle".$lang};
} 

else {
    $testimonialblocktitle = 'Education Experts Says';
}

if (!empty($PAGE->theme->settings->{"testimonialblockcontent".$lang})) {
    $testimonialblockcontent = $PAGE->theme->settings->{"testimonialblockcontent".$lang};
} 

else {
    $testimonialblockcontent = '“Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.”';
}
 
// Block One.

$hastestimonialonecontent = (!empty($PAGE->theme->settings->{"testimonialonecontent".$lang}));
$hastestimonialoneuserpic = (!empty($PAGE->theme->settings->{"testimonialoneuserpic".$lang}));
$hastestimonialoneuser = (!empty($PAGE->theme->settings->{"testimonialoneuser".$lang}));
$hastestimonialoneuserroll = (!empty($PAGE->theme->settings->{"testimonialoneuserroll".$lang}));

if (!empty($PAGE->theme->settings->{"testimonialonecontent".$lang})) {
    $testimonialonecontent = $PAGE->theme->settings->{"testimonialonecontent".$lang};
} 

else {
    $testimonialonecontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
}

if (!empty($PAGE->theme->settings->{"testimonialoneuserpic".$lang})) {
   $testimonialoneuserpic = $PAGE->theme->setting_file_url("testimonialoneuserpic{$lang}", "testimonialoneuserpic{$lang}");
} else {
    //$testimonialoneuserpic = $OUTPUT->pix_url('t1', 'theme');
}

if (!empty($PAGE->theme->settings->{"testimonialoneuser".$lang})) {
    $testimonialoneuser = $PAGE->theme->settings->{"testimonialoneuser".$lang};
} 

else {
    $testimonialoneuser = 'Jonathan Doe';
}

if (!empty($PAGE->theme->settings->{"testimonialoneuserroll".$lang})) {
    $testimonialoneuserroll = $PAGE->theme->settings->{"testimonialoneuserroll".$lang};
} 

else {
    $testimonialoneuserroll = 'User Roll';
}

// Block Two.

$hastestimonialtwocontent = (!empty($PAGE->theme->settings->testimonialtwocontent));
$hastestimonialtwouserpic = (!empty($PAGE->theme->settings->testimonialtwouserpic));
$hastestimonialtwouser = (!empty($PAGE->theme->settings->testimonialtwouser));
$hastestimonialtwouserroll = (!empty($PAGE->theme->settings->testimonialtwouserroll));

if (!empty($PAGE->theme->settings->testimonialtwocontent)) {
    $testimonialtwocontent = $PAGE->theme->settings->testimonialtwocontent;
} 

else {
    $testimonialtwocontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
}

if (!empty($PAGE->theme->settings->testimonialtwouserpic)) {
   $testimonialtwouserpic = $PAGE->theme->setting_file_url('testimonialtwouserpic', 'testimonialtwouserpic');
} else {
    $testimonialtwouserpic = $OUTPUT->pix_url('t1', 'theme');
}

if (!empty($PAGE->theme->settings->testimonialtwouser)) {
    $testimonialtwouser = $PAGE->theme->settings->testimonialtwouser;
} 

else {
    $testimonialtwouser = 'Jonathan Doe';
}

if (!empty($PAGE->theme->settings->testimonialtwouserroll)) {
    $testimonialtwouserroll = $PAGE->theme->settings->testimonialtwouserroll;
} 

else {
    $testimonialtwouserroll = 'User Roll';
}

// Block Three.

$hastestimonialtwocontent = (!empty($PAGE->theme->settings->testimonialthreecontent));
$hastestimonialthreeuserpic = (!empty($PAGE->theme->settings->testimonialthreeuserpic));
$hastestimonialthreeuser = (!empty($PAGE->theme->settings->testimonialthreeuser));
$hastestimonialthreeuserroll = (!empty($PAGE->theme->settings->testimonialthreeuserroll));

if (!empty($PAGE->theme->settings->testimonialthreecontent)) {
    $testimonialthreecontent = $PAGE->theme->settings->testimonialthreecontent;
} 

else {
    $testimonialthreecontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
}

if (!empty($PAGE->theme->settings->testimonialthreeuserpic)) {
   $testimonialthreeuserpic = $PAGE->theme->setting_file_url('testimonialthreeuserpic', 'testimonialthreeuserpic');
} else {
    $testimonialthreeuserpic = $OUTPUT->pix_url('t1', 'theme');
}

if (!empty($PAGE->theme->settings->testimonialthreeuser)) {
    $testimonialthreeuser = $PAGE->theme->settings->testimonialthreeuser;
} 

else {
    $testimonialthreeuser = 'Jonathan Doe';
}

if (!empty($PAGE->theme->settings->testimonialthreeuserroll)) {
    $testimonialthreeuserroll = $PAGE->theme->settings->testimonialthreeuserroll;
} 

else {
    $testimonialthreeuserroll = 'User Roll';
}

// Block Four.

$hastestimonialfourcontent = (!empty($PAGE->theme->settings->testimonialfourcontent));
$hastestimonialfouruserpic = (!empty($PAGE->theme->settings->testimonialfouruserpic));
$hastestimonialfouruser = (!empty($PAGE->theme->settings->testimonialfouruser));
$hastestimonialfouruserroll = (!empty($PAGE->theme->settings->testimonialfouruserroll));

if (!empty($PAGE->theme->settings->testimonialfourcontent)) {
    $testimonialfourcontent = $PAGE->theme->settings->testimonialfourcontent;
} 

else {
    $testimonialfourcontent = 'Lorem ipsum site amet eismod ictor ut ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum site amet eismod ligulate ameti dapibus ticdu nt mtsen. Lorem ipsum ameti dapibus ticdu nt mtsen.';
}

if (!empty($PAGE->theme->settings->testimonialfouruserpic)) {
   $testimonialfouruserpic = $PAGE->theme->setting_file_url('testimonialfouruserpic', 'testimonialfouruserpic');
} else {
    $testimonialfouruserpic = $OUTPUT->pix_url('t1', 'theme');
}

if (!empty($PAGE->theme->settings->testimonialfouruser)) {
    $testimonialfouruser = $PAGE->theme->settings->testimonialfouruser;
} 

else {
    $testimonialfouruser = 'Jonathan Doe';
}

if (!empty($PAGE->theme->settings->testimonialfouruserroll)) {
    $testimonialfouruserroll = $PAGE->theme->settings->testimonialfouruserroll;
} 

else {
    $testimonialfouruserroll = 'User Roll';
}

/** Copyright **/

if (!empty($PAGE->theme->settings->copyright)) {
    $hascopyright = $PAGE->theme->settings->copyright;
} 
else {
    $hascopyright = 'www.qsc.com';
}

/** Contact Form Setting **/

$showhidecontactfrm = (empty($PAGE->theme->settings->showhidecontactfrm) ||$PAGE->theme->settings->showhidecontactfrm < 1) ? 0 : 1;

// End settings content.

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
        <head>

        <title><?php echo $OUTPUT->page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
        <?php echo $OUTPUT->standard_head_html() ?>
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no" />
        <meta property="fb:admins" content="100010935516672" />
        <meta property="fb:app_id" content="166381950389300" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="<?php echo $OUTPUT->page_title(); ?>" />
        <meta property="og:description" content="Welcome to QSC Training Learning Made Simple" />
        <meta property="og:image" content="https://training.qsc.com/pluginfile.php/1/theme_meline29/blocktwoimage/-1/Play_Demo.png" />
        <meta property="og:updated_time" content="<?php echo time();?>" />
        <!--"/theme/meline29/style/frontpage.css"-->

        <link href="/theme/meline29/style/frontpage.css?v=1.1" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">
        stLight.options({
            publisher: "e90d21ad-4082-43b3-b7cb-1d176ccd13f3", 
            doNotHash: false, 
            doNotCopy: false, 
            hashAddressBar: false,
            servicePopup: true
        });</script>
   <script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 

  ga('create', 'UA-91768914-1', 'auto');

  ga('send', 'pageview');

 

</script>
<script type="text/javascript">
document.write(unescape("%3Cscript src='https://munchkin.marketo.net/munchkin.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script>Munchkin.init('711-AVJ-377');</script>
<style type="text/css">

@font-face { /* where FontName and fontname represents the name of the font you want to add */ font-family: 'Sinkin Sans 400 Regular'; src: url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.eot); src: url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.eot) format('embedded-opentype'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.woff) format('woff'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.ttf) format('truetype'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.svg) format('svg'); font-weight: normal; font-style: normal; }

</style>
        </head>
        <body <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>
<header role="banner" class="navbar navbar-default<?php echo $html->navbarclass ?> moodle-has-zindex">
          <div class="top-login">
    <div class="container-fluid">
              <div class="hdr-wrap"><a href="<?php echo $CFG->wwwroot; ?>/?redirect=0"> <?php echo $OUTPUT->full_header(); ?> </a></div>
              <div class="top-section-cover">
        <div class="login-link-sectin">
                  <div class="login-wrap">
            <?php
             if (isloggedin()) { ?>

<?php if (isguestuser()) { ?>
<?php //echo $OUTPUT->user_menu(); ?>
<div class="mobileicons">
              <ul>
                <!-- <li><a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php" title="Register" class="moregister"> </a></li> -->
                <!--li><a href="/login/index.php" title="Login" class="moblogin"> </a> </li-->
		        <!-- <li><a href="https://training.qsc.com/login/index.php" title="Login" class="moblogin"> </a> </li> -->
                <li><?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?></li>
              </ul>
            </div>
            <div class="logincont">
              <ul class="nav user-pro">
<!--                 <a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php" title="Register" class="newreg"><?php //echo get_string('register', 'theme_meline29'); ?></a>
                <a href="<?php //echo new moodle_url('/login/index.php', array('sesskey'=>sesskey())), get_string('login') ?> " > <?php //echo get_string('login') ?></a> -->
                <?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?>
              </ul>
            </div>
<?php }else{ ?>
            <?php echo $OUTPUT->user_menu(); ?>
<?php }?>
            <?php
         }else{
      ?>

            <div class="mobileicons">
              <ul>
                <!-- <li><a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php" title="Register" class="moregister"> </a></li> -->
               <!--li><a href="/login/index.php" title="Login" class="moblogin"> </a> </li-->
		      <!-- <li><a href="https://training.qsc.com/login/index.php" title="Login" class="moblogin"> </a> </li> -->
              <li><?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?></li>
              </ul>
            </div>
            <div class="logincont">
              <ul class="nav user-pro">
<!--                 <a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php" title="Register" class="newreg"><?php //echo get_string('register', 'theme_meline29'); ?></a>
                <a href="<?php //echo new moodle_url('/login/index.php', array('sesskey'=>sesskey())), get_string('login') ?> " > <?php //echo get_string('login') ?></a> -->
                <?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?>
              </ul>
            </div>
            <?php } ?>
          </div>
                </div>
        <div class="span9">
                  <?php include_once("includes/top_menu.php");?>
                </div>
      </div>
              
              <!--<div class="hdcont"><span>ANY QUESTION?</span> <?php //echo $phonenumber ?></div>--> 
              
            </div>
  </div>
          <!--<div class="flag navbg" style="display:none;"></div>--> 
        </header>
<div id="banner">
          <div class="container-fluid">
    <?php
            //if (isloggedin()) { ?>
    <?php  if ($showhidebanner) { ?>
    <div id="aditional-frontpage-content"> 
              <!-- Start Slideshow -->
              <?php require_once(dirname(__FILE__).'/includes/slideshow.php'); ?>
              <!-- End Slideshow --> 
            </div>
    <?php } ?>
    <?php //}else{?>
    
    <!--div class="login-wrapp">
	              <div class="span8">
	                <p>ONLINE EDUCATION </br> More Than <span>1000 COURSES</span> Available</p>
	                <div class="bnr-links"><a href="<?php echo $bannerbtnoneurl ?>" class="lnk1"><?php echo $bannerbtnonetxt ?></a> <a href="<?php echo $bannerbtntwourl ?>" class="lnk2"><?php echo $bannerbtntwotxt ?></a></div>
	                
	              </div>
                  <div class="loginbox span4"><?php //include("login_form.php"); ?></div>
                </div-->
    
    <?php //} ?>
  </div>
        </div>
<div id="page-wrap" class="home-page-blend-structure-starts"> 
          <!-- 1st Blade Dynamic Block Starts -->
          <div id="page" class="container-fluid first-blend-area">
    <div class="first-blend-full-section">
<!-- <h2>
	   <span style="color:red;font-size:18px;">Website will be under maintenance on May 22, 2022 at 11:00 PM PT - May 23, 2002 at 1:00 AM PT</span>
    </h2> -->    
    <?php
$head_string = '<span lang="en" class="multilang">Welcome to QSC Training</span>';
$head_string .= '<span lang="it" class="multilang">Welcome to QSC Training</span>';
$head_string .= '<span lang="zh_cn" class="multilang">欢迎参加QSC培训</span>';
$head_string .= '<span lang="pt_br" class="multilang">Bem-vindo ao Treinamento QSC</span>';
$head_string .= '<span lang="es" class="multilang">Bienvenido/a al Entrenamiento de QSC</span>';
$head_string .= '<span lang="fr" class="multilang">Bienvenue sur le site de formation QSC</span>';
$head_string .= '<span lang="de" class="multilang">Willkommen auf der QSC Training-Seite</span>';
    ?>
	<h1 class="custom_h1_homepage"><?php echo format_string($head_string);//echo get_string('welcome_qsc_training');?></h1>
              <h2>
        <?=$blockonetitle?>
      </h2>
              <div class="first-blend-all-six-box">
        <?php  if ($showhideblockone) { ?>
        <div class="blend-box-one new-box">
                  <div class="iconblock"> <img src="<?php echo $blockoneimage; ?>" alt=""> </div>
                  <div class="textblock">
            <h2><?php echo $imageonehovertitle; ?></h2>
            <p><?php echo $imageonehovercontent; ?></p>
          </div>
                </div>
        <?php }?>
        <?php  if ($showhideblocktwo) { ?>
        <div class="blend-box-two new-box">
                  <div class="iconblock"> <img src="<?php echo $blocktwoimage; ?>" alt=""> </div>
                  <div class="textblock">
            <h2><?php echo $imagetwohovertitle; ?></h2>
            <p><?php echo $imagetwohovercontent; ?></p>
          </div>
                </div>
        <?php } ?>
        <?php  if ($showhideblockthree) { ?>
        <div class="blend-box-three new-box">
                  <div class="iconblock"> <img src="<?php echo $blockthreeimage; ?>" alt=""> </div>
                  <div class="textblock">
            <h2><?php echo $imagethreehovertitle; ?></h2>
            <p><?php echo $imagethreehovercontent; ?></p>
          </div>
                </div>
        <?php } ?>
        <?php  if ($showhideblockfour) { ?>
        <div class="blend-box-four new-box">
                  <div class="iconblock"> <img src="<?php echo $blockfourimage; ?>" alt=""> </div>
                  <div class="textblock">
            <h2><?php echo $imagefourhovertitle; ?></h2>
            <p><?php echo $imagefourhovercontent; ?></p>
          </div>
                </div>
        <?php } ?>
        <?php  if ($showhideblockfive) { ?>
        <div class="blend-box-five new-box">
                  <div class="iconblock"> <img src="<?php echo $blockfiveimage; ?>" alt=""> </div>
                  <div class="textblock">
            <h2><?php echo $imagefivehovertitle; ?></h2>
            <p><?php echo $imagefivehovercontent; ?></p>
          </div>
                </div>
        <?php } ?>
        <?php  if ($showhideblocksix) { ?>
        <div class="blend-box-six new-box">
                  <div class="iconblock"> <img src="<?php echo $blocksiximage; ?>" alt=""> </div>
                  <div class="textblock">
            <h2><?php echo $imagesixhovertitle; ?></h2>
            <p><?php echo $imagesixhovercontent; ?></p>
          </div>
                </div>
        <?php } ?>
      </div>
            </div>
    <div style="clear:both"> </div>
  </div>
          <div id="page-content" class="row-fluid" style="display:none;">
    <h2><?php echo get_string('mainblocks','theme_meline29'); ?></h2>
    <!-- box-1 -->
    <?php  if ($showhideblockone) { ?>
    <div class="span4 margin-right">
              <div id="mainwrapper">
        <div id="box-2" class="box"> 
                  <!--a href="<?=$blockonelinkurl?>"><img id="image-2" src="<?php echo $blockoneimage ?>" alt="img1" /></a-->
                  <div style="float:left;"><img style="min-height:16px;height:16px;width:16px;" id="image-2" src="<?php echo $blockoneimage ?>" alt="img1" /></div>
                  <div style="float:right;"><?php echo $imageonehovertitle; ?> <br>
            <?php echo $imageonehovercontent; ?></div>
                </div>
      </div>
              <!-- <div id="box-content">
        <div class="hd">
            <?php echo $imageonehovertitle; ?>
            <?php echo $imageonehovercontent; ?>
        </div>       
      </div>  --> 
            </div>
    <?php } ?>
    
    <!-- box-2 -->
    <?php  if ($showhideblocktwo) { ?>
    <div class="span4 margin-right">
              <div id="mainwrapper">
        <div id="box-2" class="box"> <img id="image-2" src="<?php echo $blocktwoimage ?>" alt="img2" /> <span class="caption full-caption">
          <h3><?php echo $imagetwohovertitle ?></h3>
          <p><?php echo $imagetwohovercontent ?></p>
          </span> </div>
      </div>
              <div id="box-content">
        <div class="hd"><?php echo $blocktwotitle ?></div>
        <div class="lnk"> <a href="<?=$blocktwolinkurl?>" data-modal-id="popup1"> <?php echo $blocktwolinktxt ?></a> </div>
      </div>
            </div>
    <?php } ?>
    
    <!-- box-3 -->
    <?php  if ($showhideblockthree) { ?>
    <div class="span4">
              <div id="mainwrapper">
        <div id="box-2" class="box"> <img id="image-2" src="<?php echo $blockthreeimage ?>" alt="img3" /> <span class="caption full-caption">
          <h3><?php echo $imagethreehovertitle ?></h3>
          <p><?php echo $imagethreehovercontent ?></p>
          </span> </div>
      </div>
              <div id="box-content">
        <div class="hd"><?php echo $blockthreetitle ?></div>
        <div class="lnk"><a href="<?php echo new moodle_url('/user/profile.php', array('sesskey'=>sesskey()))?>"><?php echo $blockthreelinktxt ?></a></div>
      </div>
            </div>
    <?php } ?>
    
    <!-- box-4 -->
    <?php  if ($showhideblockfour) { ?>
    <div class="span4 margin-right">
              <div id="mainwrapper">
        <div id="box-2" class="box"> <img id="image-2" src="<?php echo $blockfourimage ?>" alt="img4" /> <span class="caption full-caption">
          <h3><?php echo $imagefourhovertitle ?></h3>
          <p><?php echo $imagefourhovercontent ?></p>
          </span> </div>
      </div>
              <div id="box-content">
        <div class="hd"><?php echo $blockfourtitle ?></div>
        <div class="lnk"><a href="<?=$blockfourlinkurl?>"><?php echo $blockfourlinktxt ?></a></div>
      </div>
            </div>
    <?php } ?>
    
    <!-- box-5 -->
    <?php  if ($showhideblockfive) { ?>
    <div class="span4 margin-right">
              <div id="mainwrapper">
        <div id="box-2" class="box"> <img id="image-2" src="<?php echo $blockfiveimage ?>" alt="img5" /> <span class="caption full-caption">
          <h3><?php echo $imagefivehovertitle ?></h3>
          <p><?php echo $imagefivehovercontent ?></p>
          </span> </div>
      </div>
              <div id="box-content">
        <div class="hd"><?php echo $blockfivetitle ?></div>
        <div class="lnk"><a href="<?=$blockfivelinkurl?>"><?php echo $blockfivelinktxt ?></a></div>
      </div>
            </div>
    <?php } ?>
    
    <!-- box-6 -->
    <?php  if ($showhideblocksix) { ?>
    <div class="span4">
              <div id="mainwrapper">
        <div id="box-2" class="box"> <img id="image-2" src="<?php echo $blocksiximage ?>" alt="img6" /> <span class="caption full-caption">
          <h3><?php echo $imagesixhovertitle ?></h3>
          <p><?php echo $imagesixhovercontent ?></p>
          </span> </div>
      </div>
              <div id="box-content">
        <div class="hd"><?php echo $blocksixtitle ?></div>
        <div class="lnk"><a href="<?=$blocksixlinkurl?>"><?php echo $blocksixlinktxt ?></a></div>
      </div>
            </div>
    <?php } ?>
    <div class="row-fluid"> 
              <!--
                <section id="region-main" class="<?php echo $regionmain; ?>">
                    <?php
                    echo $OUTPUT->course_content_header();
                    echo $OUTPUT->main_content();
                    echo $OUTPUT->course_content_footer();
                    ?>
                </section>
                --> 
              <?php echo $OUTPUT->blocks('side-pre', $sidepre); ?> </div>
  </div>
          
          <!-- 1st Blade Dynamic Block Ends --> 
          
          <!-- 2nd Blade Dynamic Block Start -->
          <?php

//  if(!isguestuser()){
  global $DB;
// $enrolledCourses = enrol_get_my_courses();
// $courseArr = array_keys($enrolledCourses);
// $kin1 = implode($courseArr, ',');
// print_r($courseArr);
  //echo "<pre>";
 //$sql1 = "SELECT c.fullname, cm.visible, cm.added, cm.course, cm.instance, cm.module, b.id, b.name as lesson_title, LEFT(bc.content, 200) AS lesson_desc FROM {course_modules} as cm, {course} as c , {book} as b, {book_chapters} as bc WHERE c.id = cm.course AND b.id = bc.bookid AND cm.visible = 1 AND cm.modulse = 3 AND cm.instance=b.id AND bc.title LIKE '%Description%' order by cm.added desc";
  
// - - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -//
//$sql1 = "SELECT b.id, b.thumbnailpath, cm.id as cmid, b.name as lesson_title, bc.content AS lesson_desc, cm.instance, cm.section, cs.name, cm.added, c.id as courseid FROM {course_modules} as cm JOIN {book} as b on cm.instance = b.id JOIN {book_chapters} as bc on b.id = bc.bookid JOIN {course_sections} as cs on cm.section = cs.id JOIN {course} as c on cs.course = c.id WHERE c.visible=1 AND cm.visible = 1 AND cm.module = 3 AND bc.title LIKE '%Description%' order by cm.added DESC LIMIT 0,4";

$sql1 = "SELECT b.id, b.thumbnailpath, cm.id as cmid, b.name as lesson_title, bc.content AS lesson_desc, cm.instance, cm.section, cs.name, cm.added, c.id as courseid FROM {course_modules} as cm JOIN {book} as b on cm.instance = b.id JOIN {book_chapters} as bc on b.id = bc.bookid JOIN {course_sections} as cs on cm.section = cs.id JOIN {course} as c on cs.course = c.id WHERE c.visible=1 AND cm.visible = 1 AND cm.module = 3 AND cm.showfrontpage_flag != 0 AND bc.title LIKE '%Description%' order by cm.added DESC LIMIT 0,4";

// - - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -//
 $rs1 = $DB->get_records_sql($sql1);
 //print_r($rs1); exit;
//}
?>
          <?php 
if($rs1){ 
    $cntArr = array("one","two","three","four");    
?>
          <div class="container-fluid second-blend-area">
    <div class="second-blend-full-section">
              <h2>Latest Content</h2>
              <div class="second-blend-all-six-box">
        <?php $cntNum=0; foreach($rs1 as $keys){ 
$sql3 = "SELECT * FROM {course} WHERE id = $keys->courseid";
       $rs3 = $DB->get_record_sql($sql3);
       //print_r($rs3); exit; 
// display course overview files
       require_once($CFG->dirroot . '/lib/coursecatlib.php');
        $courseListing = new course_in_list($rs3);
        $contentimages = $contentfiles = '';
        foreach ($courseListing->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                $contentimages .= html_writer::tag('div',
                        html_writer::empty_tag('img', array('src' => $url)),
                        array('class' => 'courseimage'));
            } else {
                $image = $this->output->pix_icon(file_file_icon($file, 24), $file->get_filename(), 'moodle');
                $filename = html_writer::tag('span', $image, array('class' => 'fp-icon')).
                        html_writer::tag('span', $file->get_filename(), array('class' => 'fp-filename'));
                $contentfiles .= html_writer::tag('span',
                        html_writer::link($url, $filename),
                        array('class' => 'coursefile fp-filename-icon'));
            }
        }
        $content = $contentimages. $contentfiles;        
        // display course overview files end


?>
        <div class="blend-box-<?=$cntArr[$cntNum]?> second-sec">
            <div class="blockimg">
            <?php if($keys->thumbnailpath){ ?>
            <a href='/mod/book/view.php?id=<?=$keys->cmid?>' title="<?=format_string($keys->name)?>"><?php echo '<img src="'.str_replace("pluginfile.php","draftfile.php",base64_decode($keys->thumbnailpath)).'?pg=home">';?></a>
                <?php }else{ ?>
                <a href='/mod/book/view.php?id=<?=$keys->cmid?>' title="<?=format_string($keys->name)?>"><img src="/theme/image.php?theme=meline29&amp;component=theme&amp;image=no-images" alt="No Image Found"> </a>
            <?php } ?>
            </div>
                <div class="blocktext">
                    <!-- - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -->
                    <a href='/mod/book/view.php?id=<?=$keys->cmid?>' title="<?=format_string($keys->name)?>">
                        <h2><?=format_string($keys->name)?> </h2>
                    </a>
                    <a href='/mod/book/view.php?id=<?=$keys->cmid?>' title="<?=format_string($keys->lesson_title)?>">
                        <h2 class="subh"><?=format_string($keys->lesson_title)?></h2>
                    </a>
                    <!-- - -- - - - - -- Start- Feature Request: Frontpage Latest content  - -- - -  -->
                <span>Added
                    <?=date("m-d-y",$keys->added)?>
                    </span>
                <p>
                    <?php
                    $lstring = format_string($keys->lesson_desc);
                    if(strlen($lstring)>140)
                    	$lstring = substr($lstring,0,140).'...';
                    ?>
                    <?=$lstring?> 
                      
                </p>
            </div>
            </div>
        <?php $cntNum++;} //end foreach?>
        </div>
        </div>
    </div>
          <?php }?>
          <?php  if ($showhidethemefeature) { ?>
          <div id="about-wrap" style="display:none;">
    <h2> <?php echo $featureblocktopcontent; ?> </h2>
    <div id="about-top">
              <div class="container-fluid"> <?php echo $featureblocktopcontent; ?> </div>
            </div>
    <div id="about" class="container-fluid">
              <div id="page-content" class="row-fluid"> 
        
        <!-- box-1 -->
        
        <div class="span6">
                  <div id="abt-box" class="box-1">
            <div class="abt1-img"> <img id="image-2" src="<?php echo $featureoneimage ?>" alt="img1" /> </div>
            <div class="box-cont">
                      <h3><?php echo $featureonetitle; ?></h3>
                      <p><?php echo $featureonecontent; ?></p>
                    </div>
          </div>
                </div>
        
        <!-- box-2 -->
        
        <div class="span6">
                  <div id="abt-box2" class="box-2">
            <div class="abt2-img"> <img id="image-2" src="<?php echo $featuretwoimage ?>" alt="img2" /> </div>
            <div class="box-cont">
                      <h3><?php echo $featuretwotitle; ?></h3>
                      <p><?php echo $featuretwocontent; ?></p>
                    </div>
          </div>
                </div>
        
        <!-- box-3 -->
        
        <div class="span6">
                  <div id="abt-box3" class="box-3">
            <div class="abt3-img"> <img id="image-2" src="<?php echo $featurethreeimage ?>" alt="img3" /> </div>
            <div class="box-cont">
                      <h3><?php echo $featurethreetitle ?></h3>
                      <p><?php echo $featurethreecontent ?></p>
                    </div>
          </div>
                </div>
        
        <!-- box-4 -->
        
        <div class="span6">
                  <div id="abt-box4" class="box-4">
            <div class="abt4-img"> <img id="image-2" src="<?php echo $featurefourimage ?>" alt="img4" /> </div>
            <div class="box-cont">
                      <h3><?php echo $featurefourtitle ?></h3>
                      <p><?php echo $featurefourcontent ?></p>
                    </div>
          </div>
                </div>
        
        <!-- about box end --> 
      </div>
            </div>
  </div>
          <?php } ?>
          
          <!-- 2nd Blade Dynamic Block End --> 
          
          <!-- 3rd blade structure -->
          <?php  if ($showhidewelcomeblock) { ?>
          <div class="container-fluid third-blend-area">
    <div class="third-blend-full-section">
              <h2><?php echo $welcometitle; ?></h2>
              <div class="third-blend-all-six-box">
        <div class="blend-box-one"> <img src="<?php echo $welcomecontentimage; ?>" alt="">
                  <p><?php echo $welcomecontent; ?></p>
                </div>
      </div>
            </div>
  </div>
          <?php } ?>
          <!-- 3rd blade structure ends --> 
          
          <!-- 4th Blade Block Starts -->
          <?php  if ($showhidetestimonialsblock) { ?>
          <div class="container-fluid fourth-blend-area">
    <div class="fourth-blend-full-section">
              <div class="fourth-blend-all-six-box">
        <div class="blend-box-one">
                  <h2><?php echo $testimonialblocktitle; ?></h2>
                  <p><?php echo $testimonialblockcontent; ?></p>
                </div>
        <div class="blend-box-two"> <img src="<?php echo $testimonialoneuserpic; ?>" alt=""> </div>
      </div>
            </div>
  </div>
          <?php } ?>
          <!-- 4th Blade Block Ends --> 
          
        </div>
<div style="clear:both"></div>
<footer id="page-footer"> 
          
          <!-- About Block End -->
          
          <div id="ftr-menu">
    <div class="container-fluid">
              <div class="fmenu">
                <a href="<?=$CFG->wwwroot?>">Home</a> 
                <!-- <a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php">Register</a>  -->
                <?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?>
                <a href="https://www.qsc.com/privacy-policy/">Privacy Policy</a> 
                <a href="<?php echo $CFG->wwwroot;?>/mod/page/view.php?id=575">Contact Us</a>
            </div>
            </div>
  </div>
          <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
          <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
          <?php
        echo $html->footnote;
        //echo $OUTPUT->login_info();
        //echo $OUTPUT->standard_footer_html();
        ?>
        </footer>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
<style>



.v-center > div {
  display: table-cell;
  vertical-align: middle;
  position: relative;
  top: -10%;
}


.modal-box {
  display: none;
  position: absolute;
  z-index: 1000;
  width: 500px;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
@media (min-width: 32em) {

.modal-box { width:auto;}
}
.modal-body{
    
    
    }
    

.modal-box header,
.modal-box .modal-header {
  padding: 0.5em 1.5em;
  border-bottom: 1px solid #ddd;
    background:#666;
    color:#fff;
}

.modal-box header h3,
.modal-box header h4,
.modal-box .modal-header h3,
.modal-box .modal-header h4 { margin: 0; font-size:20px; }

.modal-box .modal-body { padding: 10px; }

.modal-box footer,
.modal-box .modal-footer {
  padding: 1em;
  border-top: 1px solid #ddd;
  background: rgba(0, 0, 0, 0.02);
  text-align: right;
}

.modal-overlay {
  opacity: 0;
  filter: alpha(opacity=0);
  position: absolute;
  top: 0;
  left: 0;
  z-index: 900;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3) !important;
}

a.close {
  line-height: 1;
  font-size: 2.5em;
  position: absolute;
  top: 1%;
  right: 2%;
  text-decoration: none;
  color: #fff;
}

a.close:hover {
  color: #222;
  -webkit-transition: color 1s ease;
  -moz-transition: color 1s ease;
  transition: color 1s ease;
}
</style>
<div style="margin-top:40%;" id="popup1" class="modal-box">
          <header> <a href="javascript:void(0);" class="js-modal-close close">×</a>
    <h3>See Demo</h3>
  </header>
          <div class="modal-body">
    <p>
              <iframe width="480" height="349" src="https://www.youtube.com/embed/152KkMTxISU" frameborder="0" allowfullscreen></iframe>
            </p>
  </div>
          </footer>
        </div>
<script type="text/javascript">
$( "#sitenews-cont" ).prepend( $( "#site-news-forum" ) );
</script> 
<script>

//$(document).ready(function(){
//  		$(window).resize(function(){
//			    var footerHeight = $('#page-footer').outerHeight();
//			    var stickFooterPush = $('.push').height(footerHeight);
//		
//    			$('#page-wrap').css({'marginBottom':'-' + footerHeight + 'px'});
//		    });
//		
//    		$(window).resize();
//	    });
//		
		
		
//$(document).mouseup(function (e)
//{
  //  var container = $(".navbg");

//    if (!container.is(e.target) // if the target of the click isn't the container...
  //      && container.has(e.target).length === 0) // ... nor a descendant of the container
    //{
    //    container.hide();
    //}
//});

//$(".navbg").click(function(){
	//$(this).hide();
//});


var lengthLI = 0;
var val = 0;



$(".dropdown-submenu").hover(function(){
$(".dropdown-submenu .dropdown-menu").hide();
$(this).find(".dropdown-menu:first").show();
lengthLI = $(this).find("ul.dropdown-menu:last > li").length;
console.log(lengthLI);
		        		
		//val = parseInt(lengthLI) * 25 + 20;	
//		console.log(val);
//if ($(window).width() >= 979) {
//        $(".navbg").css("height", val+"px");
//} 
/*............*/  
});

/***/    
/****/

$(".dropdown").click(function(e){
$(".dropdown-menu").hide();
    $(this).find(".dropdown-menu:first").show();
	    $(".navbg").hide(); 
	    if($(this).hasClass('open')){
		    $(".navbg").hide(); 
		}
    else{
        var lengthLI = 0;
        var val = 0;

      //  lengthLI = $(this).find("ul.dropdown-menu:first > li").length;
//        val = parseInt(lengthLI) * 25 + 15;
//if ($(window).width() >= 979) {
//        $(".navbg").css("height", val+"px");
//}
	$(this).find(".navbg").slideDown('slow');	
	 //$(".navbg").slideToggle();		
    } 
});

/*$(".dropdown a.dropdown-toggle").click(function(e){
$("#navbg").hide(); 
   if($(this).parent().hasClass('open')){
        $("#navbg").hide(); 
   }
   else{
            var lengthLI = 0;
            var val = 0;
            
            lengthLI = $(this).parent().find("ul:first > li").length;
            val = parseInt(lengthLI) * 25 + 25;
           // console.log(val);
            $("#navbg").css("height", val+"px");
           // console.log(lengthLI);
      
        $("#navbg").slideDown();


         
   }
   
   // var newHeight = $(".dropdown-menu").height();
	//$(newHeight).css("min-height", "100")
       // $("#navbg").height(newHeight);		
//		
	//	var height=$('.dropdown-menu').height();
      //  var newheight = height+100;
       // $('#navbg').css('min-height',newheight+'px') 
});*/

$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

    $('a[data-modal-id]').click(function(e) {
        e.preventDefault();
    $("body").append(appendthis);
    $(".modal-overlay").fadeTo(500, 0.7);
    
    
    //$(".js-modalbox").fadeIn(500);
        var modalBox = $(this).attr('data-modal-id');
        $('#'+modalBox).fadeIn($(this).data());
    });  
  
  
$(".js-modal-close, .modal-overlay").click(function() {
    $(".modal-box, .modal-overlay").fadeOut(500, function() {
        $(".modal-overlay").remove();
    }); 
});
 
$(window).resize(function() {
  $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});

//if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) 
//{
//	if (window.matchMedia("(orientation: portrait)").matches) {
//	$('.navbg').css({
//    height:'auto',
//    position:'inherit',
//    left:'inherit',
//	width:'inherit',
//	}); 	
//	}
//	//if (window.matchMedia("(orientation: portrait)").matches) {
//	//$(".span9.navM").insertAfter(".login-link-sectin");
//	//	
////}
//}

$( ".mob-menu-text" ).click(function() {
  $( this ).toggleClass( "downarrow" );
});


//Script for right click issue

$(".navbg").on("contextmenu",function(){
	$(".dropdown").find(".open").removeClass("open");
	$(this).parent().addClass("open");
});

//End script

});

</script> 
<script>
equalheight = function(container){
var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 $(container).each(function() {

   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;

   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}

$(window).on("load", function() {
  equalheight('.first-blend-all-six-box .new-box');
});

$(window).resize(function(){
  equalheight('.first-blend-all-six-bo .new-box');
});

$(window).on("load", function() {
  equalheight('.second-blend-all-six-box .second-sec');
});

$(window).resize(function(){
  equalheight('.second-blend-all-six-box .second-sec'); 
});

</script> 

<!-- navbg -->
<style>
/*
.rmthis{
display:none !important;
}
.submenusopen{
display:block !important;
}*/
/* US #2050 start */
.alert-box, .confirm-box {
     position: fixed;
     top: 0;
     left: 0;
     align-items: center;
     justify-content: center;
     width: 100%;
     height: 100%;
     z-index: 100000;
     display: flex;
     align-items: flex-start;
  }

  .alert-surface, .confirm-surface {
    opacity: 1;
    visibility: visible;
    margin: 0.5em 1em;
    text-align: center;
    background-color: #fff;
    display: inline-flex;
    flex-direction: column;
    width: calc(100% - 30px);
    max-width: 640px;
    transform: translateY(50px) scale(.8);
    border-radius: 10px;
    border: 1px solid #ccc;
    box-shadow: 5px 5px 20px 0 #666;
    vertical-align: middle;
    padding: 10px;
  }

  .alert-surface p{
    font-size: 14px;
    color: #555;
    margin: 0.5em 1em;
    line-height: 1.5em;
  }
  .alert-surface button{
    display: inline-block;
    line-height: 20px;
    border-radius: 4px;
    width: 65px;
    padding: 10px 15px;
    font-size: 14px;
    margin: auto;

  }

  .alert-backdrop, .confirm-backdrop {
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: rgba(0,0,0,.87);
     opacity: .3;
     z-index: -1;
  }
  /*US #2050 end*/
</style>

</body>
</html>

<?php
//US #2050 start
  if (empty($record->userid) or isguestuser($record->userid)) {
    $flgSess = 0;
      // Ignore guest and not-logged-in timeouts, there is very little risk here.
  } else {
    $flgSess = 1;
  }
  $timeremaining = $CFG->sessiontimeout;
?>
<script>  
// $(document).mousemove(function(event){
//   var timeoutp = "<?=$timeremaining?>";
//   var userid = "<?=$USER->id?>";
//   var username = "<?=$USER->username?>";
//   var flgSess = <?=$flgSess?>;
//   if(flgSess == 1 || flgSess == "1"){
//     console.log("test");
//     $.ajax({url: "/theme/meline29/layout/check_session.php", 
//       success: function(result){
//         //console.log("Inside Ajax "+ result);
//         if(result == "not gotcha"){
//           alert('Your session has timed out! Please log back in to your training account in order to continue your training.');
//           window.location.reload();
//           console.log("Check flag logged-inss "+ result);
//         }else{
//           console.log("Check flag logged-ins "+ result);
//         }
//       }
//     });
//     // flgSess=2;
//     // alert('Your session has timed out! Please log back in to your training account in order to continue your training.');
//   }
// });


$(document).ready(function(){  
  var timeoutp = "<?=$timeremaining?>";
  var userid = "<?=$USER->id?>";
  var username = "<?=$USER->username?>";
  var flgSess = <?=$flgSess?>;
  timeoutp = timeoutp*1000+10;
  console.log("Check timeout limit f " + timeoutp);
  console.log("Username and id "+ userid + " " + username);
  //alert("ittit");
function async_alert() {
     $("body").append("<div class='alert-box'>\
           <div class='alert-surface'>\
              <p><b>Your session has timed out!</b></p><p>Please log back in to your training account in order to continue your training.</p>\
              <button type='button' class='alert-remove'>OK</button>\
           </div>\
           <div class='alert-backdrop'></div>\
        </div>");
     $(".alert-remove").off("click").on("click", function() { $(".alert-box").remove();window.location.reload(); });
  }
  function check_session()
  {
    
    if(flgSess == 1 || flgSess == "1"){
      console.log("test");
      $.ajax({url: "/theme/meline29/layout/check_session.php", 
        success: function(result){
          //console.log("Inside Ajax "+ result);
          if(result == "not gotcha"){
            flgSess=99;
           // alert('Your session has timed out! Please log back in to your training account in order to continue your training.');
            async_alert();
            console.log("Check flag logged-inss "+ result);
          }else{
            console.log("Check flag logged-ins "+ result);
          }
        }
      });
     // flgSess=2;
     // alert('Your session has timed out! Please log back in to your training account in order to continue your training.');
     }
    // else if(flgSess == 2){
    //   console.log("hit more than one time");
    // }
    // else{
    //   console.log("Not logged-in timeinterval done");
    // }
  }

  setInterval(function(){
    check_session();
  }, timeoutp);  //10000 means 80 seconds
  //clearInterval(hadnle);
});
</script>
<!-- //US #2050 end -->