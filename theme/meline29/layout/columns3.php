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

if(!isset($_GET['lang'])){
 $_SESSION['slidelangfe']  = $lang = '_en';
 if(!empty($USER) && $USER->id!=0){
    $lang = "_".$USER->lang;
 }
}else{
 $_SESSION['slidelangfe']  = $lang = "_".$_GET['lang'];
}

// Get the HTML for the settings bits.
$html = theme_meline29_get_html_for_settings($OUTPUT, $PAGE, $lang);

// Set default (LTR) layout mark-up for a three column page.
$isadmin = is_siteadmin($USER);
if($isadmin){
	$regionmainbox = 'span9';
	$regionmain = 'span9 pull-right';
}
else{
	$regionmainbox = 'span12';
	$regionmain = 'span12 pull-right';
}

$sidepre = 'span3 desktop-first-column';
$sidepost = 'span3 pull-right';
// Reset layout mark-up for RTL languages.
if (right_to_left()) {
    $regionmainbox = 'span9 pull-right';
    $regionmain = 'span8';
    $sidepre = 'span4 pull-right';
    $sidepost = 'span3 desktop-first-column';
}

// Header Top Phone Number Settings.

$hasphonenumber = (!empty($PAGE->theme->settings->phonenumber));

if (!empty($PAGE->theme->settings->phonenumber)) {
    $phonenumber = $PAGE->theme->settings->phonenumber;
} 

else {
    $phonenumber = ' 011-6782975, 011-8508105';
}
/** Copyright **/

if (!empty($PAGE->theme->settings->copyright)) {
    $hascopyright = $PAGE->theme->settings->copyright;
} 
else {
    $hascopyright = 'www.vidyamantra.com';
}





echo $OUTPUT->doctype() 
?>

<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
<title><?php echo $OUTPUT->page_title(); ?></title>
<link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
<?php echo $OUTPUT->standard_head_html() ?>

<!-- <link rel="stylesheet" type="text/css" href="/mod/book/sample/css/sample.css" > -->
<!--meta name="viewport" content="width=device-width, initial-scale=1.0"-->
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no" />
<meta property="fb:admins" content="100010935516672" />
<meta property="fb:app_id" content="137105766684971" />
<meta property="og:type" content="website" />
<!--  og:type provides a category for Facebook  -->
<meta property="og:title" content="<?php echo $OUTPUT->page_title(); ?>" />
<meta property="og:description" content="" />
<meta property="og:image" content="https://training.qsc.com/pluginfile.php/1/theme_meline29/blocktwoimage/-1/Play_Demo.png" />

<!--Updated by lakhan-->
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

<?php
$isadmin = is_siteadmin($USER);
if($isadmin){
?>
<link href="/theme/meline29/style/admin.css?v=1.2" type="text/css" rel="stylesheet">
<?php if($_SERVER['SCRIPT_NAME'] == '/course/view.php'){ ?>
<!--Drag and drop code start-->
<script src="/theme/meline29/javascript/jquery-ui.js"></script>
<script>
$( document ).ready(function() {
$('.editing .topics ul.section').addClass('sortable');
});
</script>
<script>
  $(function() {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
  });
  </script>
<!--Drag and drop code end-->
<?php
}
?>
<?php
}
if($_SERVER['SCRIPT_NAME'] == '/my/index.php'){
?>
<link href="/theme/meline29/style/dashboard.css?v=1.1" type="text/css" rel="stylesheet">
<?php
}

if($_SERVER['SCRIPT_NAME'] == '/mod/forum/view.php' || $_SERVER['SCRIPT_NAME'] == '/mod/forum/discuss.php'){
?>
<link href="/theme/meline29/style/forum.css" type="text/css" rel="stylesheet">
<?php
}

if($_SERVER['SCRIPT_NAME'] == '/mod/forum/view.php' || $_SERVER['SCRIPT_NAME'] == '/mod/forum/discuss.php'){
?>
<script>
$( document ).ready(function() {
  $( ".forumheaderlist" ).wrap("<div class='table-cover-section'></div>");
});
</script>
<?php
}

if($_SERVER['SCRIPT_NAME'] == '/course/index.php' || $_SERVER['SCRIPT_NAME'] == '/mod/forum/discuss.php'){
?>
<script>
$( document ).ready(function() {
$('body').addClass('trainingpg');
});
</script>
<?php

?>
<link href="/theme/meline29/style/forum.css" type="text/css" rel="stylesheet">
<?php
}


if($_SERVER['SCRIPT_NAME'] == '/mod/forum/post.php' || $_SERVER['SCRIPT_NAME'] == '/mod/forum/discuss.php'){
?>
<link href="/theme/meline29/style/forum.css" type="text/css" rel="stylesheet">
<?php
}

?>
<style type="text/css">

@font-face { /* where FontName and fontname represents the name of the font you want to add */ font-family: 'Sinkin Sans 400 Regular'; src: url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.eot); src: url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.eot) format('embedded-opentype'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.woff) format('woff'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.ttf) format('truetype'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.svg) format('svg'); font-weight: normal; font-style: normal; }

</style>

</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>
<header role="banner" class="navbar navbar-default<?php echo $html->navbarclass ?> moodle-has-zindex">
  <div class="top-login">
    <div class="container-fluid">
      <div class="hdr-wrap"><a href="<?php echo $CFG->wwwroot; ?>/?redirect=0"><?php echo $OUTPUT->full_header(); ?></a></div>
      <!--    <div class="hdcont"><span>ANY QUESTION?</span> <?php echo $phonenumber ?></div>-->
      
      <div class="top-section-cover">
        <div class="login-link-sectin">
          <div class="login-wrap">
            <?php
             if (isloggedin()) { ?>

<?php if (isguestuser()) { ?>
<?php //echo $OUTPUT->user_menu(); ?>
<div class="mobileicons">
              <ul>
                <!-- <li><a href="<?php //echo SSO_AOUTH2_BASE_URL."/sign_up"?>" title="Register" class="moregister"><?php //echo get_string('register', 'theme_meline29'); ?> </a></li> -->
                <!--li><a href="/login/index.php" title="Login" class="moblogin"> </a> </li-->
                <li><?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?></li>
              </ul>
            </div>
            <div class="logincont">
              <ul class="nav user-pro">
                <!-- <li><a href="<?php //echo SSO_AOUTH2_BASE_URL."/sign_up"?>" title="Register" class="newreg"> <?php //echo get_string('register', 'theme_meline29'); ?></a></li> -->
                <li><?php require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?></li>
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
                <!-- <a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php" class="newreg"><?php //echo get_string('register', 'theme_meline29'); ?></a>  -->
                <a href="www.moodle.org" class="border-right">Help</a> 
                <!-- <a href="<?php //echo new moodle_url('/login/index.php', array('sesskey'=>sesskey())), get_string('login') ?> " > <?php //echo get_string('login') ?></a> -->
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
    </div>
  </div>
  <!-- <div class="flag navbg" style="display:none;"></div>--> 
</header>
<div id="page-wrap">
  <div class="newnav"><?php echo $OUTPUT->full_header23(); ?></div>
  <div id="page" class="container-fluid">
    <div id="page-content" class="row-fluid">
      <div id="region-main-box" class="<?php echo $regionmainbox; ?>">
        <div class="row-fluid">
          <section id="region-main" class="htmlcreation <?php echo $regionmain; ?>">
            <?php
                    
        echo $OUTPUT->course_content_header();
                    echo $OUTPUT->main_content();
                    $query_string = http_build_query($_GET);
                    echo $OUTPUT->course_content_footer();
          
                    ?>
          </section>
          <?php 
if($isadmin){
echo $OUTPUT->blocks('side-pre', $sidepre); 
}

?> </div>
      </div>
      <?php echo $OUTPUT->blocks('side-post', $sidepost); ?> </div>
    <div id="push"></div>
  </div>
</div>
<footer id="page-footer"> 
  <!-- About Block End --> 
  <!--div id="ftr-menu">
    <div class="container-fluid">
      <div class="fmenu"><a href="<?php //echo $CFG->wwwroot; ?>/?redirect=0">Home</a> <a href="#">Privacy</a> </div>
    </div>
  </div-->
  <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
  <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
  <?php
        echo $html->footnote;
        /*echo $OUTPUT->login_info();
        echo $OUTPUT->standard_footer_html();*/






        ?>
</footer>
<?php echo $OUTPUT->standard_end_of_body_html() ?> 
<script type="text/javascript">//Footer Section Start
$(document).ready(function(){
/*      $(window).resize(function(){
          var footerHeight = $('#page-footer').outerHeight();
          var stickFooterPush = $('.push').height(footerHeight);
    
          $('#page-wrap').css({'marginBottom':'-' + footerHeight + 'px'});
        });
    
        $(window).resize();
      });*/
//Footer Section End

// Language Menu Section Start
var catCondition = "<?=$query_string?>";

if(catCondition == "categoryid=4"){
$("#coursesearch").hide();
}

if(catCondition == "categoryid=4&amp;lang=es" || catCondition == "categoryid=4&amp;lang=en" || catCondition == "categoryid=4&amp;lang=zh_cn"){
$("#coursesearch").hide();
}
// Language Menu Section Ends

 
//NavBg section start
$(document).mouseup(function (e)
{
    var container = $(".navbg");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
    }
});

$(".navbg").click(function(){
	$(this).hide();
});


var lengthLI = 0;
var val = 0;



$(".dropdown-submenu").hover(function(){
$(".dropdown-submenu .dropdown-menu").hide();
$(this).find(".dropdown-menu:first").show();

lengthLI = $(this).find("ul.dropdown-menu:last > li").length;
//console.log(lengthLI);
//		
//        val = parseInt(lengthLI) * 25 + 20;
//		console.log(val);
//if ($(window).width() >= 979) {
//        $(".navbg").css("height", val+"px");
//}
/*............*/
});

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

       // lengthLI = $(this).find("ul.dropdown-menu:first > li").length;
//        val = parseInt(lengthLI) * 25 + 15;
//if ($(window).width() >= 979) {
//        $(".navbg").css("height", val+"px");
//}
$(this).find(".navbg").slideDown('slow');
//        $(".flag").slideToggle();		
   } 
});

$( ".mob-menu-text" ).click(function() {
  $( this ).toggleClass( "downarrow" );
});



//$(window).resize(function() {
//  if ($(window).width() < 979) {
//	  
//	 $('.navbg').css({
//    height:'auto',
//    position:'inherit',
//    left:'inherit',
//	width:'inherit',
//	});
//  }  
//});

//Navbg section end
 
$(function () {
   
  // Modal box height dimension start 
  $(window).resize(function() {
      $(".modal-box").css({
          top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
          left: ($(window).width() - $(".modal-box").outerWidth()) / 2
      });
  });
   
  $(window).resize();
 
});




/*-------------------------------------------------------------------- 
 * JQuery Plugin: "EqualHeights" & "EqualWidths"
 * by:  Scott Jehl, Todd Parker, Maggie Costello Wachs (http://www.filamentgroup.com)
 *
 * Copyright (c) 2007 Filament Group
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
 *
 * Description: Compares the heights or widths of the top-level children of a provided element 
    and sets their min-height to the tallest height (or width to widest width). Sets in em units 
    by default if pxToEm() method is available.
 * Dependencies: jQuery library, pxToEm method  (article: http://www.filamentgroup.com/lab/retaining_scalable_interfaces_with_pixel_to_em_conversion/)                
 * Usage Example: $(element).equalHeights();
                    Optional: to set min-height in px, pass a true argument: $(element).equalHeights(true);
 * Version: 2.0, 07.24.2008
 * Changelog:
 *  08.02.2007 initial Version 1.0
 *  07.24.2008 v 2.0 - added support for widths
--------------------------------------------------------------------*/

$.fn.equalHeights = function(px) {
  $(this).each(function(){
    var currentTallest = 0;
    $(this).children().each(function(i){
      if ($(this).height() > currentTallest) { currentTallest = $(this).height(); }
    });
    if (!px && Number.prototype.pxToEm) currentTallest = currentTallest.pxToEm(); //use ems unless px is specified
    // for ie6, set height since min-height isn't supported
    if (typeof(document.body.style.minHeight) === "undefined") { $(this).children().css({'height': currentTallest}); }
    $(this).children().css({'min-height': currentTallest}); 
  });
  return this;
};

// just in case you need it...
$.fn.equalWidths = function(px) {
  $(this).each(function(){
    var currentWidest = 0;
    $(this).children().each(function(i){
        if($(this).width() > currentWidest) { currentWidest = $(this).width(); }
    });
    if(!px && Number.prototype.pxToEm) currentWidest = currentWidest.pxToEm(); //use ems unless px is specified
    // for ie6, set width since min-width isn't supported
    if (typeof(document.body.style.minHeight) === "undefined") { $(this).children().css({'width': currentWidest}); }
    $(this).children().css({'min-width': currentWidest}); 
  });
  return this;
};

/*Sameer added for course listing page box custom top link*/
//alert("<?=$isadmin?>");
var isAdmin = "<?=$isadmin?>";
if(isAdmin == ""){
 $("ul.topics li").each(function(i){

  if(i != 0){
//    console.log($(this).find("h3").html() + i);
    //console.log($(this).find("ul.section.img-text").html() + i);
    //console.log($(this).find(".content").prepend("<ul class='custom_box_top'>"+$(this).find("ul.section.img-text").html()+"</ul>") + i);
    //console.log($(this).find("h3").remove() + i);
    $(this).find("ul.section.img-text").remove();
  }
 });
}
else{
  $("ul.topics li").each(function(i){

  if(i != 0){
  //  console.log($(this).find("h3").html() + i);
    //console.log($(this).find("ul.section.img-text").html() + i);
    //console.log($(this).find(".content").prepend("<ul class='custom_box_top'>"+$(this).find("ul.section.img-text").html()+"</ul>") + i);
    //console.log($(this).find("h3").remove() + i);
    //$(this).find("ul.section.img-text").remove();
  }
 });
}
});
</script> 
<script>
  $(function(){ $('.category-browse-4').equalHeights(); });  
</script>
</body>
</html>
<script>
//Send email to admin for quiz attempt resetting
$(".resetexammailsending").click(function(e){
    $('#reset_notify').html("<span id='sent_msg'>Sending...</span>");
    //alert("test");
    $.post("/mod/quiz/ajax.php",
    {
      msg_body: $("#msg_body").val(),
      coursemoduleid: $("#coursemoduleid").val()
    },
    function(data, status){
      /*if(data == "Sent"){
        $("#reset_notify").html(data);
        console.log("Data: " + data + "\nStatus: " + status);
      }else{
        $("#reset_notify").html(data);
      }*/
      $("#reset_notify").html("<span id='tensecond'>"+data+"</span>"); 
        //alert("Your session is destroyed. Please login again.");
	
		setTimeout(function(){
		$('#tensecond').css('display', 'none');}, 3000);
if(data === 'Your session is destroyed. Please login again.'){
		window.location = "https://training.qsc.com/login/index.php";
	}
		});	
});

$(".modal-remove-text").click(function(){
  $("#msg_body").val("");
  $("#reset_notify").html("");
});

$(".collapsible-actions").hide();
$(".categoryname").click(function(){
  $(".collapsible-actions").show();
});
$("#coursesearch").css("margin-left","-45px");

var isAdmin = "<?=$isadmin?>";
 // $('#inst5').parent().remove();
 //setTimeout(function(){
  //if(isAdmin == ""){
  //$('.dock_left_vertical').remove();

 // }
 /* $(".mod-assign-history-link-closed").addClass(".mod-assign-history-link-open");
  //$(".mod-assign-history-link-closed").removeClass(".mod-assign-history-link-closed");
  $(".mod-assign-history-panel").removeAttr("hidden");
  $(".mod-assign-history-panel").show();*/
 // }, 2000);
if(isAdmin == ""){
//alert("Test33");
	$(document).ready(function (){
		$('#inst5').parent().hide();
		$('#dock').remove();
		$('#dock_item_0').hide();

	});
}
//Exit Exam Customisation
$(".fitem.fitem_feditor").insertAfter(".fitem.fitem_ffilemanager");
if($("td").hasClass("grading-green")){
  $("#assign_passed_msg").show();
}
//console.log("Testtt");
$('.course_category_tree .boxC-wrapper').hide();
$('.course_category_tree .heading-container').hide();

</script>
<script>
$( document ).ready(function() {
$('.topics > li:not(:first-child)').addClass('something');
});
</script>

<!--AJ-->
<script>
var isAdmin = "<?=$isadmin?>";
if(isAdmin == ""){
$(document).ready(function(){
  $("#dock").remove();
  $(".breadcrumb-button").remove();
});
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

$(window).load(function() {
  equalheight('.topics .something');
$("#dock").remove();
});

$(window).resize(function(){
  equalheight('.topics .something');
});
}
</script>
<script type="text/javascript">
$(".navbg").on("contextmenu",function(){
	$(".dropdown").find(".open").removeClass("open");
   	$(this).parent().addClass("open");
});

</script>
<!--End script-->

<!--AJ-->
<style>
.at4-recommended-outer-container .addthis-smartlayers {
  display: none !important;
}

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
    font-size: 18px;
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
</style>
<?php
//US #2050 start
    
  if (!$record = $DB->get_record('sessions', array('sid' => $_COOKIE['MoodleSession']), 'id, userid, timemodified')) {
    $flgSess = 0;
  }

  if (empty($record->userid) or isguestuser($record->userid)) {
    $flgSess = 0;
      // Ignore guest and not-logged-in timeouts, there is very little risk here.
  } else {
    $flgSess = 1;
  }

$sid = $_COOKIE['MoodleSession'];
  if (!$record = $DB->get_record('sessions', array('sid' => $sid), 'id, userid, timemodified')) {   

    if (empty($record->userid) or isguestuser($record->userid)) {
        // Ignore guest and not-logged-in timeouts, there is very little risk here.
        $timeremaining = $CFG->sessiontimeout;
    } else {
        //$timeremaining =  $CFG->sessiontimeout - (time() - $record->timemodified);
      $timeremaining = $CFG->sessiontimeout;
    }
  }
 $timeremaining = $CFG->sessiontimeout;

?>
<script>  
$(document).ready(function(){  
  var timeoutp = "<?=$timeremaining?>";
  var userid = "<?=$USER->id?>";
  var username = "<?=$USER->username?>";
  var flgSess = "<?=$flgSess?>";
  
  timeoutp = timeoutp*1000+10;
  //timeoutp = 3000;
  console.log("Check timeout limit f " + timeoutp);
  console.log("Username and id "+ userid + " " + username);
  console.log("flgsess "+flgSess);
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

            //window.location.reload();
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
