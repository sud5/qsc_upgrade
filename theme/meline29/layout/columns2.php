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
 * The two column layout.
 *
 * @package   theme_meline29
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

// Set default (LTR) layout mark-up for a two column page (side-pre-only).
$isadmin = is_siteadmin($USER);
if($isadmin){
	$regionmainbox = 'span9';
	$regionmain = 'span9 pull-right';
}
else{
	$regionmainbox = 'span12';
	$regionmain = 'span9';
}

//$regionmain = 'span9 pull-right';
$sidepre = 'span3 desktop-first-column';
// Reset layout mark-up for RTL languages.
if (right_to_left()) {
    $regionmain = 'span9';
    $sidepre = 'span3 pull-right';
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
$isadmin = is_siteadmin($USER);
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
<title><?php echo $OUTPUT->page_title(); ?></title>
<link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
<?php echo $OUTPUT->standard_head_html() ?>
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no" />
<?php
$isadmin = is_siteadmin($USER);
if($isadmin){
?>
<link href="/theme/meline29/style/admin.css?v=1.2" type="text/css" rel="stylesheet">
<?php }?>
<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 

  ga('create', 'UA-91768914-1', 'auto');

  ga('send', 'pageview');

 

</script>
<style type="text/css">

@font-face { /* where FontName and fontname represents the name of the font you want to add */ font-family: 'Sinkin Sans 400 Regular'; src: url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.eot); src: url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.eot) format('embedded-opentype'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.woff) format('woff'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.ttf) format('truetype'), url(/theme/meline29/fonts/SinkinSans-400Regular-webfont.svg) format('svg'); font-weight: normal; font-style: normal; }

</style>

</head>

<body <?php echo $OUTPUT->body_attributes('two-column'); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>
<header role="banner" class="navbar navbar-default<?php echo $html->navbarclass ?> moodle-has-zindex">
  <div class="top-login">
    <div class="container-fluid">
      <div class="hdr-wrap"><a href="<?php echo $CFG->wwwroot; ?>/?redirect=0"><?php echo $OUTPUT->full_header(); ?></a></div>
      <!-- <div class="hdcont"><span>ANY QUESTION?</span> <?php echo $phonenumber ?></div>-->
      
      <div class="top-section-cover">
        <div class="rg login-link-sectin">
          <div class="login-wrap">
            <?php
             if (isloggedin()) { ?>

<?php if (isguestuser()) { ?>
<?php //echo $OUTPUT->user_menu(); ?>
<div class="mobileicons">
              <ul>
                <!-- <li><a href="<?php //echo SSO_AOUTH2_BASE_URL."/sign_up"?>" title="Register" class="moregister"> <?php //echo get_string('register', 'theme_meline29'); ?></a></li> -->
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
              <!--   <a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php" class="newreg"><?php //echo get_string('register', 'theme_meline29'); ?></a>  -->
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
      <section id="region-main" class="<?php echo $regionmain; ?>">
        <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            echo $OUTPUT->course_content_footer();
            ?>
      </section>
      <?php echo $OUTPUT->blocks('side-pre', $sidepre);
        ?> </div>
    <div id="push"></div>
  </div>
</div>
<footer id="page-footer"> 
  
  <!-- About Block End -->
  
  <div id="ftr-menu">
    <div class="container-fluid">
      <div class="fmenu">
        <a href="<?=$CFG->wwwroot?>">Home</a> 
        <!-- <a href="https://sso-01.qsc.com/qscaudio_sso/page/signup?service=<?=$CFG->wwwroot?>/login/index.php">Register</a> -->
        <!-- <?php //require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); auth_googleoauth2_display_buttons(); ?> -->
      </div>
    </div>
  </div>
  <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
  <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
  <?php
        echo $html->footnote;
       /* echo $OUTPUT->login_info();
        echo $OUTPUT->standard_footer_html();*/
        ?>
  <div class="copyright">
    <?php if ($hascopyright) {
            echo '<p class="copy">&copy; '.date("Y").' '.$hascopyright.'</p>';
           } ?>
  </div>
</footer>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
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
		


//NavBg section start
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
console.log(lengthLI);
		
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

//Script for right click issue resolvede

$(".navbg").on("contextmenu",function(){
	$(".dropdown").find(".open").removeClass("open");
   	$(this).parent().addClass("open");
});

//End script

//Navbg section end
</script>
<script>
var isAdmin = "<?=$isadmin?>";
//alert("test");
if(isAdmin == ""){
	$(document).ready(function (){
		$('#inst5').parent().remove();
		$('#dock').hide();

	});
}
</script>

<style type="text/css">
    /*US #2050 start*/
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
