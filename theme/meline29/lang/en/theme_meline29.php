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
 * This is built using the meline29 template to allow for new theme's using
 * meline29 framework
 *
 *
 * @package   theme_meline29
 * @copyright 2014 Eduardo Ramos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* Core */
$string['configtitle'] = 'meline29';
$string['pluginname'] = 'Meline29';
$string['choosereadme'] = 'Theme built with meline29. It includes an advanced Visual Style Manager';


/* General */
$string['logout'] = 'Logout';
$string['register'] = 'Register';
$string['firsttime'] = 'You are a new user?';
$string['geneicsettings'] = 'General Settings';

$string['themebgc']='Theme Color';
$string['themebgcdesc']='This sets the link and button color for the theme.';

$string['themehoverbgc']='Theme Hover Color';
$string['themehoverbgcdesc']='This sets theme link and button color on mouse hover for the theme.';

$string['footnote'] = 'Footnote';
$string['footnotedesc'] = 'Whatever you add to this textarea will be displayed in the footer throughout your Moodle site.';

$string['logo'] = 'Logo';
$string['logodesc'] = 'Please upload your custom logo here if you want to add it to the header.<br>If you upload a logo it will replace the standard icon and name that was displayed by default.';

$string['copyright'] = 'Copyright';
$string['copyrightdesc'] = 'The name of your organisation.';
$string['showmoodledocs'] = 'Show "Moodle Docs for this page" section in the footer';

$string['siteicon'] = 'Site Icon';
$string['siteicondesc'] = 'Do not have a logo? Enter the name of the icon you wish to use. List is <a href="http://www.getmeline29.com/docs/icon.html" target="_blank">here</a>. Just enter what is after the "uk-icon-". ';

$string['alwaysdisplay'] = 'Always Show';
$string['displaybeforelogin'] = 'Show before login only';
$string['displayafterlogin'] = 'Show after login only';
$string['dontdisplay'] = 'Never Show';

$string['componentclass-normal'] = 'Normal';
$string['componentclass-primary'] = 'Primary';
$string['componentclass-success'] = 'Success';
$string['componentclass-danger'] = 'Danger';
$string['componentclass-link'] = 'Link';

$string['componentplacement-center'] = 'Center';
$string['componentplacement-top'] = 'Top';
$string['componentplacement-bottom'] = 'Bottom';
$string['componentplacement-left'] = 'Left';
$string['componentplacement-right'] = 'Right';

/* CustomMenu */
$string['custommenuheading'] = 'Custom Menu';
$string['custommenuheadingsub'] = 'Add additional functionality to your custommenu.';
$string['custommenudesc'] = 'Settings here allow you to add new dynamic functionality to the custommenu (also refered to as Dropdown menu)';

$string['mydashboardinfo'] = 'Custom User Dashboard';
$string['mydashboardinfodesc'] = 'Displays a list of common functions used by users.';
$string['displaymydashboard'] = 'Display Dashboard';
$string['displaymydashboarddesc'] = 'Display Dashboard of user links in the Custom Menu';

$string['displaysitename'] = 'Display site name';
$string['mycoursesinfo'] = 'Dynamic Enrolled Courses List';
$string['mycoursesinfodesc'] = 'Displays a dynamic list of enrolled courses to the user.';
$string['displaymycourses'] = 'Display enrolled courses';
$string['displaymycoursesdesc'] = 'Display enrolled courses for users in the Custom Menu';
$string['displaymycoursesmode'] = 'Enrolled courses display mode';
$string['displaymycoursesmodedesc'] = 'This setting defines how enrolled courses are displayed in the Custom Menu';
$string['displayloggedusermode'] = 'Logged user display mode';
$string['displayloggedusermodedesc'] = 'This setting defines how the logged user is displayed in the Custom Menu';
$string['displayloggedusermodecomplete'] = 'Complete info';
$string['displayloggedusermodeshort'] = 'Short info';
$string['displayloggedusermodeonlylogout'] = 'Only logout';
$string['displayloggedusermodehide'] = 'Hide';
$string['courselist'] = 'Display simple courses list';
$string['onlytoplevelhierarchy'] = 'Display courses lists under their top level category';
$string['fullhierarchy'] = 'Display courses in complete categories hierarchy';

$string['mycoursetitle'] = 'Terminology';
$string['mycoursetitledesc'] = 'Change the terminology for the "My Courses" link in the dropdown menu';
$string['mycourses'] = 'My Courses';
$string['myunits'] = 'My Units';
$string['mymodules'] = 'My Modules';
$string['myclasses'] = 'My Classes';
$string['mysubjects'] = 'My Subjects';
$string['allcourses'] = 'All Courses';
$string['allunits'] = 'All Units';
$string['allmodules'] = 'All Modules';
$string['allclasses'] = 'All Classes';
$string['allsubjects'] = 'All Subjects';
$string['noenrolments'] = 'You have no current enrolments';

/* My Dashboard custommenu dropdown */
$string['mydashboard'] = 'My Dashboard';


/* Frontpage Settings */
$string['frontcontentheading'] = 'Frontpage Settings';
$string['frontcontentheadingsub'] = 'Change what features you wish enabled on your moodle front page';
$string['frontcontentdesc'] = 'This adds a custom content area at the top of the frontpage main box for your own custom content';

$string['usefrontcontent'] = 'Enable Frontpage content';
$string['usefrontcontentdesc'] = 'If enabled this will display tat the top of the frontpage main box.';

$string['frontcontentarea'] = 'Frontpage Content';
$string['frontcontentareadesc'] = 'Whatever is typed into this box will display across the full width of the page inbetween the Slideshow and the Marketing spots ';

$string['frontpagemiddleblocks'] = 'Enable Frontpage Middle Blocks';
$string['frontpagemiddleblocksdesc'] = 'If enabled this will display 3 new block locations just under the frontpage content and 2 additional full-width block locations abover and under the 3 blocks';

$string['combolistshowonlyenrrolled'] = 'Show only enrolled courses in the combo list';
$string['combolistshowonlyenrrolleddesc'] = 'If checked, when using combo list in the front page, only enrolled categories and courses will be shown';

/* Header Top Phone Number Settings */

$string['topphonenumber'] = 'Theme Header Top Phone Number Settings.';

$string['phonenumber'] = 'Header Top Phone Number'; 
$string['phonenumberdesc'] = 'Enter phone number for helping users';

/* Banner Settings */

$string['banner'] = 'Frontpage Banner Settings When You Are Not Logged In.';

$string['showhidebanner'] = 'Display Banner';
$string['showhidebannerdesc'] = 'Show OR hide your banner';

$string['bannercaption'] = 'Banner: Description'; 
$string['bannercaptiondesc'] = 'Enter the description for banner';

$string['bannerbtnonetxt'] = 'Button One Text';
$string['bannerbtnonetxtdesc'] = 'Enter the text for first button';

$string['bannerbtnoneurl'] = 'Button One Url';
$string['bannerbtnoneurldesc'] = 'Enter the url for first button';

$string['bannerbtntwotxt'] = 'Button Two Text';
$string['bannerbtntwotxtdesc'] = 'Enter the text for second button';

$string['bannerbtntwourl'] = 'Button Two Url';
$string['bannerbtntwourldesc'] = 'Enter the url for second button';

/* Slideshow */
$string['slideshowheading'] = 'Frontpage Slideshow';
$string['slideshowheadingsub'] = 'Dynamic Slideshow for the frontpage';
$string['slideshowdesc'] = 'This creates a dynamic slideshow of up to 4 slides for you to promote important elements of your site.';

$string['slideshowheight'] = 'Slideshow height';
$string['slideshowsizingmode'] = 'Slide sizing mode';

$string['auto'] = 'Auto';

$string['slideshowsizingmode-fullwidth'] = 'Use full width';
$string['slideshowsizingmode-fullheight'] = 'Use full height';

$string['slideshownumber'] = 'Number of slides';

$string['toggleslideshow'] = 'Toggle Slideshow display';
$string['toggleslideshowdesc'] = 'Choose if you wish to hide or show the Slideshow.';

$string['hideonphone'] = 'Slideshow on Mobiles';
$string['hideonphonedesc'] = 'Choose if you wish to have the slideshow shown on mobiles or not';
$string['readmore'] = 'Read More';

$string['slideheader'] = 'Slide {$a->n}';

$string['slidetitle'] = 'Slide Title';
$string['slideimage'] = 'Slide Image';
$string['slidecaption'] = 'Slide Caption';
$string['slideurl'] = 'Slide Button Link';
$string['slideurltext'] = 'Slide Button Text';
$string['slidecaptionplacement'] = 'Slide caption placement';

$string['slideshowtitlecolor'] = 'Slide Title Color';
$string['slideshowcaptioncolor'] = 'Slide Caption Color';
$string['slideshowarrowscolor'] = 'Navigation Arrows Color';
$string['slideshowbuttontype'] = 'Slide Button Type';

$string['slideshowautoplay'] = 'Enable autoplay';
$string['slideshowanimation'] = 'Slideshow animation';
$string['slideshowkenburns'] = 'Enable ken burns effect';

$string['slideshowanimation-fade'] = 'Fade';
$string['slideshowanimation-scroll'] = 'Scroll';
$string['slideshowanimation-scale'] = 'Scale';
$string['slideshowanimation-swipe'] = 'Swipe';
$string['slideshowanimation-slice-down'] = 'Slice down';
$string['slideshowanimation-slice-up'] = 'Slice up';
$string['slideshowanimation-slice-up-down'] = 'Slice up & down';
$string['slideshowanimation-fold'] = 'Fold';
$string['slideshowanimation-puzzle'] = 'Puzzle';
$string['slideshowanimation-boxes'] = 'Boxes';
$string['slideshowanimation-boxes-reverse'] = 'Boxes reverse';
$string['slideshowanimation-random-fx'] = 'Random effect';

/* Welcome Block Settings */

$string['welcome'] = '3rd blade structure';

$string['showhidewelcomeblock'] = 'Display 3rd blade';
$string['showhidewelcomeblockdesc'] = 'Show OR hide your banner';

$string['welcometitle'] = 'Third Blade Title';
$string['welcometitledesc'] = 'Enter the text for first button';

$string['welcomecontent'] = 'Third Blade: Content'; 
$string['welcomecontentdesc'] = 'Enter the description for banner';

$string['welcomecontentimage'] = 'Image';
$string['welcomecontentimagedesc'] = 'Enter the description for 3rd blade structure';

/* Main Content Blocks Settings */

/* Block ONE */

$string['mainblocks'] = 'Learning made easy.';
$string['mainblocksdesc'] = 'Enter your welcome heading';

$string['showhideblockone'] = 'Display Block One';
$string['showhideblockonedesc'] = 'Show OR hide block one';

$string['blockoneimage'] = 'Block One Image';
$string['blockoneimagedesc'] = 'Drag and drop your image for block one';

$string['blocktitle'] = 'Heading';
$string['blocktitledesc'] = '';

$string['blockonetitle'] = 'Block One Title';
$string['blockonetitledesc'] = 'Enter block one title';

$string['blockonelinktxt'] = 'Block One Link Text'; 
$string['blockonelinktxtdesc'] = 'Enter block one link text';

$string['blockonelinkurl'] = 'Block One Link URL'; 
$string['blockonelinkurldesc'] = 'Enter block one link URL';

$string['imageonehovertitle'] = 'Image One Hover Title'; 
$string['imageonehovertitledesc'] = 'Enter block one image hover title';

$string['imageonehovercontent'] = 'Image One Hover Content'; 
$string['imageonehovercontentdesc'] = 'Enter block one image hover content';

/* Block TWO */

$string['showhideblocktwo'] = 'Display Block two';
$string['showhideblocktwodesc'] = 'Show OR hide block two';

$string['blocktwoimage'] = 'Block Two Image';
$string['blocktwoimagedesc'] = 'Drag and drop your image for block two';

$string['blocktwotitle'] = 'Block Two Title';
$string['blocktwotitledesc'] = 'Enter block two title';

$string['blocktwolinktxt'] = 'Block Two Link Text'; 
$string['blocktwolinktxtdesc'] = 'Enter block two link text';

$string['blocktwolinkurl'] = 'Block Two Link URL'; 
$string['blocktwolinkurldesc'] = 'Enter block two link URL';

$string['imagetwohovertitle'] = 'Image Two Hover Title'; 
$string['imagetwohovertitledesc'] = 'Enter block two image hover title';

$string['imagetwohovercontent'] = 'Image Two Hover Content'; 
$string['imagetwohovercontentdesc'] = 'Enter block two image hover content';

/* Block THREE */

$string['mainblocks'] = 'Frontpage Main Content Blocks Settings.';
$string['mainblocksdesc'] = 'Enter your welcome heading';

$string['showhideblockthree'] = 'Display Block Three';
$string['showhideblockthreedesc'] = 'Show OR hide block three';

$string['blockthreeimage'] = 'Block Three Image';
$string['blockthreeimagedesc'] = 'Drag and drop your image for block three';

$string['blockthreetitle'] = 'Block Three Title';
$string['blockthreetitledesc'] = 'Enter block three title';

$string['blockthreelinktxt'] = 'Block Three Link Text'; 
$string['blockthreelinktxtdesc'] = 'Enter block three link text';

$string['blockthreelinkurl'] = 'Block Three Link URL'; 
$string['blockthreelinkurldesc'] = 'Enter block three link URL';

$string['imagethreehovertitle'] = 'Image Three Hover Title'; 
$string['imagethreehovertitledesc'] = 'Enter block three image hover title';

$string['imagethreehovercontent'] = 'Image Three Hover Content'; 
$string['imagethreehovercontentdesc'] = 'Enter block four image hover content';

/* Block FOUR */

$string['showhideblockfour'] = 'Display Block Four';
$string['showhideblockfourdesc'] = 'Show OR hide block four';

$string['blockfourimage'] = 'Block Four Image';
$string['blockfourimagedesc'] = 'Drag and drop your image for block four';

$string['blockfourtitle'] = 'Block Four Title';
$string['blockfourtitledesc'] = 'Enter block four title';

$string['blockfourlinktxt'] = 'Block Four Link Text'; 
$string['blockfourlinktxtdesc'] = 'Enter block four link text';

$string['blockfourlinkurl'] = 'Block Four Link URL'; 
$string['blockfourlinkurldesc'] = 'Enter block four link URL';

$string['imagefourhovertitle'] = 'Image Four Hover Title'; 
$string['imagefourhovertitledesc'] = 'Enter block four image hover title';

$string['imagefourhovercontent'] = 'Image Four Hover Content'; 
$string['imagefourhovercontentdesc'] = 'Enter block four image hover content';

/* Block FIVE */

$string['showhideblockfive'] = 'Display Block Five';
$string['showhideblockfivedesc'] = 'Show OR hide block five';

$string['blockfiveimage'] = 'Block Five Image';
$string['blockfiveimagedesc'] = 'Drag and drop your image for block five';

$string['blockfivetitle'] = 'Block Five Title';
$string['blockfivetitledesc'] = 'Enter block five title';

$string['blockfivelinktxt'] = 'Block Five Link Text'; 
$string['blockfivelinktxtdesc'] = 'Enter block five link text';

$string['blockfivelinkurl'] = 'Block Five Link URL'; 
$string['blockfivelinkurldesc'] = 'Enter block five link URL';

$string['imagefivehovertitle'] = 'Image Five Hover Title'; 
$string['imagefivehovertitledesc'] = 'Enter block five image hover title';

$string['imagefivehovercontent'] = 'Image Five Hover Content'; 
$string['imagefivehovercontentdesc'] = 'Enter block five image hover content';

/* Block SIX */

$string['showhideblocksix'] = 'Display Block Six';
$string['showhideblocksixdesc'] = 'Show OR hide block six';

$string['blocksiximage'] = 'Block Six Image';
$string['blocksiximagedesc'] = 'Drag and drop your image for block six';

$string['blocksixtitle'] = 'Block Six Title';
$string['blocksixtitledesc'] = 'Enter block six title';

$string['blocksixlinktxt'] = 'Block Six Link Text'; 
$string['blocksixlinktxtdesc'] = 'Enter block six link text';

$string['blocksixlinkurl'] = 'Block Six Link URL'; 
$string['blocksixlinkurldesc'] = 'Enter block six link URL';

$string['imagesixhovertitle'] = 'Image Six Hover Title'; 
$string['imagesixhovertitledesc'] = 'Enter block six image hover title';

$string['imagesixhovercontent'] = 'Image Six Hover Content'; 
$string['imagesixhovercontentdesc'] = 'Enter block six image hover content';


/* Theme Features Blocks Settings */

$string['themefeatures'] = 'Theme Features Blocks Settings.';

$string['showhidethemefeature'] = 'Display Theme Feature Block';
$string['showhidethemefeaturedesc'] = 'Show OR hide theme feature block';

$string['featureblocktopcontent'] = 'Feature Block Top Content'; 
$string['featureblocktopcontentdesc'] = 'Enter feature block top content';

/* Block ONE */

$string['featureoneimage'] = 'Feature Block One Image';
$string['featureoneimagedesc'] = 'Drag and drop your image for feature block one';

$string['featureonetitle'] = 'Feature Block One Title';
$string['featureonetitledesc'] = 'Enter block one title';

$string['featureonecontent'] = 'Feature Block One Content'; 
$string['featureonecontentdesc'] = 'Enter feature block one content';

/* Block TWO */

$string['featuretwoimage'] = 'Feature Block Two Image';
$string['featuretwoimagedesc'] = 'Drag and drop your image for feature block two';

$string['featuretwotitle'] = 'Feature Block Two Title';
$string['featuretwotitledesc'] = 'Enter block two title';

$string['featuretwocontent'] = 'Feature Block Two Content'; 
$string['featuretwocontentdesc'] = 'Enter feature block two content';

/* Block THREE */

$string['featurethreeimage'] = 'Feature Block Three Image';
$string['featurethreeimagedesc'] = 'Drag and drop your image for feature block three';

$string['featurethreetitle'] = 'Feature Block Three Title';
$string['featurethreetitledesc'] = 'Enter block three title';

$string['featurethreecontent'] = 'Feature Block Three Content'; 
$string['featurethreecontentdesc'] = 'Enter feature block three content';

/* Block FOUR */

$string['featurefourimage'] = 'Feature Block Four Image';
$string['featurefourimagedesc'] = 'Drag and drop your image for feature block four';

$string['featurefourtitle'] = 'Feature Block Four Title';
$string['featurefourtitledesc'] = 'Enter block four title';

$string['featurefourcontent'] = 'Feature Block Four Content'; 
$string['featurefourcontentdesc'] = 'Enter feature block four content';

/* Site News Block Settings */

$string['sitenewsblock'] = 'Theme Site News Blocks Settings.';

$string['sitenewsblocktitle'] = 'Site News Block Top Title'; 
$string['sitenewsblocktitledesc'] = 'Enter site news block top title';

$string['sitenewsblockcontent'] = 'Site News Block Top content'; 
$string['sitenewsblockcontentdesc'] = 'Enter site news block top content';


/* New Events Block Settings */

$string['newevents'] = 'New Events Block Settings.';

$string['showhideeventsblock'] = 'Display Events Block'; 
$string['showhideeventsblockdesc'] = 'Display events';

$string['neweventstxt'] = 'New Event Block Text';
$string['neweventstxtdesc'] = 'Enter text for new event block';

$string['neweventslinktxt'] = 'New Events Button Link Text';
$string['neweventslinktxtdesc'] = 'Enter new events button link text';

$string['neweventslinkurl'] = 'New Events Button Link URL';
$string['neweventslinkurldesc'] = 'Enter new events button link URL';

/* Testimonials Blocks Settings */

$string['testimonials'] = '4th Blade Structure';

$string['showhidetestimonialsblock'] = 'Display 4th Blade'; 
$string['showhidetestimonialsblockdesc'] = '';

$string['testimonialblocktitle'] = 'Title';
$string['testimonialblocktitledesc'] = '';

$string['testimonialblockcontent'] = 'Content';
$string['testimonialblockcontentdesc'] = '';

/* Block ONE */

$string['testimonialonecontent'] = 'Testimonial One Content.';
$string['testimonialonecontentdesc'] = 'Enter testimonial one content';

$string['testimonialoneuserpic'] = 'Image';
$string['testimonialoneuserpicdesc'] = '';

$string['testimonialoneuser'] = 'Testimonial One User Name';
$string['testimonialoneuserdesc'] = 'Enter testimonial one user name';

$string['testimonialoneuserroll'] = 'Testimonial One User Roll';
$string['testimonialoneuserrolldesc'] = 'Enter testimonial one user roll';

/* Block TWO */

$string['testimonialtwocontent'] = 'Testimonial Two Content.';
$string['testimonialtwocontentdesc'] = 'Enter testimonial two content';

$string['testimonialtwouserpic'] = 'Testimonial Two User Picture';
$string['testimonialtwouserpicdesc'] = 'Drag and drop testimonial two user picture';

$string['testimonialtwouser'] = 'Testimonial Two User Name';
$string['testimonialtwouserdesc'] = 'Enter testimonial two user name';

$string['testimonialtwouserroll'] = 'Testimonial Two User Roll';
$string['testimonialtwouserrolldesc'] = 'Enter testimonial two user roll';

/* Block THREE */

$string['testimonialthreecontent'] = 'Testimonial Three Content.';
$string['testimonialthreecontentdesc'] = 'Enter testimonial three content';

$string['testimonialthreeuserpic'] = 'Testimonial Three User Picture';
$string['testimonialthreeuserpicdesc'] = 'Drag and drop testimonial three user picture';

$string['testimonialthreeuser'] = 'Testimonial Three User Name';
$string['testimonialthreeuserdesc'] = 'Enter testimonial three user name';

$string['testimonialthreeuserroll'] = 'Testimonial Three User Roll';
$string['testimonialthreeuserrolldesc'] = 'Enter testimonial three user roll';

/* Block FOUR */

$string['testimonialfourcontent'] = 'Testimonial Four Content.';
$string['testimonialfourcontentdesc'] = 'Enter testimonial four content';

$string['testimonialfouruserpic'] = 'Display Testimonial Four User Picture';
$string['testimonialfouruserpicdesc'] = 'Drag and drop testimonial four user picture';

$string['testimonialfouruser'] = 'Testimonial Four User Name';
$string['testimonialfouruserdesc'] = 'Enter testimonial four user name';

$string['testimonialfouruserroll'] = 'Testimonial Four User Roll';
$string['testimonialfouruserrolldesc'] = 'Enter testimonial four user roll';

/* Frontpage Contact Form */

$string['contactfrm'] = 'Frontpage Contact Form Settings.';

$string['showhidecontactfrm'] = 'Display Frontpage Contact Form';
$string['showhidecontactfrmdesc'] = 'Show OR hide frontage contact form';

/* Social Networks */
$string['socialheading'] = 'Social Networking';
$string['socialheadingsub'] = 'Engage your users with Social Networking';
$string['socialdesc'] = 'Provide direct links to the core social networks that promote your brand.  These will appear in the header of every page.';
$string['socialnetworks'] = '';
$string['facebook'] = 'Facebook URL';
$string['facebookdesc'] = 'Enter the URL of your Facebook page. (i.e http://www.facebook.com/mycollege)';

$string['twitter'] = 'Twitter URL';
$string['twitterdesc'] = 'Enter the URL of your Twitter feed. (i.e http://www.twitter.com/mycollege)';

$string['googleplus'] = 'Google+ URL';
$string['googleplusdesc'] = 'Enter the URL of your Google+ profile. (i.e http://plus.google.com/107817105228930159735)';

$string['linkedin'] = 'LinkedIn URL';
$string['linkedindesc'] = 'Enter the URL of your LinkedIn profile. (i.e http://www.linkedin.com/company/mycollege)';

$string['youtube'] = 'YouTube URL';
$string['youtubedesc'] = 'Enter the URL of your YouTube channel. (i.e http://www.youtube.com/mycollege)';

$string['flickr'] = 'Flickr URL';
$string['flickrdesc'] = 'Enter the URL of your Flickr page. (i.e http://www.flickr.com/mycollege)';

$string['skype'] = 'Skype Account';
$string['skypedesc'] = 'Enter the Skype username of your organisations Skype account';

$string['pinterest'] = 'Pinterest URL';
$string['pinterestdesc'] = 'Enter the URL of your Pinterest page. (i.e http://pinterest.com/mycollege)';

$string['instagram'] = 'Instagram URL';
$string['instagramdesc'] = 'Enter the URL of your Instagram page. (i.e http://instagram.com/mycollege)';

/* Mobile Apps */
$string['mobileappsheading'] = 'Mobile Apps';
$string['mobileappsheadingsub'] = 'Link to your App to get your students using Mobiles';
$string['mobileappsdesc'] = 'Have you got a web app on the App Store or Google Play Store?  Provide a link here so your users can grab the apps online';

$string['android'] = 'Android (Google Play)';
$string['androiddesc'] = 'Prove a URL to your mobile App on the Google Play Store.  If you do not have one of your own maybe consider linking to the free official Moodle Mobile app.';

$string['ios'] = 'iPhone/iPad (App Store)';
$string['iosdesc'] = 'Prove a URL to your mobile App on the App Store.  If you do not have one of your own maybe consider linking to the free official Moodle Mobile app.';

/* iOS Icons */
$string['iosicon'] = 'iOS Homescreen Icons';
$string['iosicondesc'] = 'The theme does provide a default icon for iOS, Android and Windows homescreens. You can upload your custom icons if you wish.';

$string['iphoneicon'] = 'iPhone/iPod Touch Icon (Non Retina)';
$string['iphoneicondesc'] = 'Icon should be a PNG file sized 57px by 57px';

$string['iphoneretinaicon'] = 'iPhone/iPod Touch Icon (Retina)';
$string['iphoneretinaicondesc'] = 'Icon should be a PNG file sized 114px by 114px';

$string['ipadicon'] = 'iPad Icon (Non Retina)';
$string['ipadicondesc'] = 'Icon should be a PNG file sized 72px by 72px';

$string['ipadretinaicon'] = 'iPad Icon (Retina)';
$string['ipadretinaicondesc'] = 'Icon should be a PNG file sized 144px by 144px';

/* Google Analytics */
$string['analyticsheading'] = 'Google Analytics';
$string['analyticsheadingsub'] = 'Powerful analytics from Google';
$string['analyticsdesc'] = 'Here you can enable Google Analytics for your moodle site. You will need to sign up for a free account at the Google Analytics site (<a href="http://analytics.google.com" target="_blank">http://analytics.google.com</a>)';

$string['useanalytics'] = 'Enable Google Analytics';
$string['useanalyticsdesc'] = 'Enable or disable Google Analytics functionaliy.';

$string['analyticsid'] = 'Your Tracking ID';
$string['analyticsiddesc'] = 'Enter the provided Tracking ID. Typically formatted like UA-XXXXXXXX-X';

$string['analyticsclean'] = 'Send Clean URLs';
$string['analyticscleandesc'] = 'This fantastic feature was created by <a href="https://moodle.org/user/profile.php?id=281671" target="_blank">Gavin Henrick</a> and <a href="https://moodle.org/user/view.php?id=907814" target="_blank">Bas Brands</a> and is implemented in this theme. Rather than standard Moodle URLs the theme will send out clean URLs making it easier to identify the page and provide advanced reporting. More information on using this feature and its uses can be <b><a href="http://www.somerandomthoughts.com/blog/2012/04/18/ireland-uk-moodlemoot-analytics-to-the-front/" target="_blank">found here</a></b>.';

$string['analyticsadmin'] = 'Track Admin Users';
$string['analyticsadmindesc'] = 'Enable to track Admin users as well.';

/********************************************/
/* Visual styles customizer related strings. */

/* Interface */
$string['accept'] = 'Accept';
$string['cancel'] = 'Cancel';
$string['warning_variables_changed'] = 'You are about to change base theme and some variables have different values than default.';
$string['keep_variables'] = 'Keep variable values between themes where possible';
$string['continue_refreshing'] = 'Continue refreshing?';
$string['base_theme'] = 'Base theme';
$string['basic'] = 'Basic';
$string['almost-flat'] = 'Almost flat';
$string['gradient'] = 'Gradient';
$string['refresh'] = 'Refresh';
$string['auto_refresh'] = 'Auto refresh';
$string['save_styles'] = 'Save and use styles';
$string['save_styles_tooltip'] = 'Save and use current style customizations for the actual site';
$string['export_less'] = 'Export LESS';
$string['export_less_tooltip'] = 'Export style customizations into a LESS file';
$string['import_less'] = 'Import LESS';
$string['import_less_tooltip'] = 'Import style customizations from a LESS file';
$string['reset'] = 'Reset';
$string['reset_all'] = 'Reset all';
$string['custom_less'] = 'Use your own custom CSS/LESS code';
$string['custom_less_default'] = 
    'Your custom CSS or LESS code here...
    It will be added at the end of the style sheet';
$string['themedesignerenabled'] = 'Theme designer mode has been enabled';
$string['warning_theme_designer_enabled'] = 'Theme designer mode has been automatically enabled. Make sure to disable theme designer mode for better performance when you are done customizing styles.';
$string['warning_theme_designer_disable'] = 'You can disable it here';
$string['warning_saved_styles_different_theme_version'] = 'It seems that you saved custom styles for this site with an old version of theme meline29';
$string['warning_saved_styles_different_theme_version_action'] = 'Please update the theme styles by going to the customizer and clicking on save. Clear your browser cache to ensure correct behaviour';
$string['page_description'] = 'This page is for customizing the look and feel of your site.';
$string['page_description_sub'] = 'You can configure the logo and many other options here.';

$string['meline29_not_selected'] = 'meline29 Theme is not currently selected.';
$string['meline29_select_link'] = 'Please select it here';
$string['styleswritepermissionsfail'] = 'Error: theme/meline29/styles folder is not writable. Please check that your web server has write permissions in this folder';

/* Javascript (customizer.js) internationalization strings */
$string['js-ok'] = 'Ok';
$string['js-home'] = 'Home';
$string['js-compile-error'] = 'An error happened while building the styles';
$string['js-reset-group'] = 'Reset group values to default';
$string['js-reset-group-confirm'] = 'Reset group <i>{0}</i> variables to default?';
$string['js-reset-var-confirm'] = 'Reset <i>{0}</i> to default?';
$string['js-reset-all-confirm'] = 'Reset ALL groups variables to default?';
$string['js-styles-saved'] = 'Styles saved successfully!';
$string['js-styles-saved-error'] = 'An error happened while saving the styles';
$string['js-font-family-placeholder'] = 'Type your font or list of fonts';
$string['js-externalpage-disallowed'] = 'Going to an external page is not allowed';
$string['js-less-error-help'] = 'Please try to clear your browser cache and make sure that your custom CSS/LESS is correct';


