<div class="span9">
          <link href="/moodle42/theme/meline29/style/main.css?v=1.17" type="text/css" rel="stylesheet">
<?php
global $DB, $SESSION;
//-------- Start Logout User name  - -- - - - - - - - - - --//
$fullname = "";
//-------- End Logout User name  - -- - - - - - - - - - --//

//start - updated for QSCID - SSO
if(isloggedin() && ($USER->auth == "googleoauth2") && (!isset($USER->admin)) && (!is_siteadmin()) && ($_SESSION['REALUSER']->auth != 'manual') && (!isset($_SESSION['REALUSER']->usertype) || (isset($_SESSION['REALUSER']->usertype) && ($_SESSION['REALUSER']->usertype != 'graderasadmin'))  ) ){ // added condition for loginas for admin

    //US #17913 start
    /*if($_SERVER['SCRIPT_NAME'] != '/user/edit.php'){

          if(empty($USER->firstname) || empty($USER->lastname) || empty($USER->email) || empty($USER->phone1) || empty($USER->institution) || empty($USER->address) || empty($USER->city) || empty($USER->country) || empty($USER->state) || empty($USER->zip)){    
           redirect(new moodle_url($CFG->wwwroot.'/user/edit.php'));
        }
    }*/
    //US #17913 end
}
//end - updated for QSCID - SSO

//-------- Start Logout User name  - -- - - - - - - - - - --//
$fullname = $USER->firstname." ".$USER->lastname;
//-------- End Logout User name  - -- - - - - - - - - - --//
//-- -  --Start -Feature Request: Multi-language support on LMS -Nav - - --//
$currentlang = current_language();
$explodLang= "$$$$".$currentlang."$$$$";
$multiLangArr = explode($explodLang, $CFG->custommenuitems);
if(isset($multiLangArr[1]) && !empty($multiLangArr[1]))
{
  $hashArr = explode("#####", $multiLangArr[1]);
}else{
  $d_lang = 'en';
  $multiLangArrDefult = explode("$$$$".$d_lang."$$$$", $CFG->custommenuitems);
  //print_r($multiLangArrDefult[1]);
  $hashArr = explode("#####", $multiLangArrDefult[1]);
}
//-- -  --End -Feature Request: Multi-language support on LMS -Nav - - --//
//$hashArr = explode("#####", $CFG->custommenuitems);



function generateMobSubMenuHTML($menu) {
    $html = '<ul class="nav-subdivision-nested" data-dropdown-menu">';
    foreach ($menu as $item) {
        
        $itemData  = explode("|", $item['label']);
        $itemClass = '';
        $itemLabel = '';
        $itemLink  = '';
        if(isset($itemData[2])){
            $itemClass = $itemData[2];
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }elseif(isset($itemData[1])){
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }else{
            $itemLabel = $itemData[0];
            $itemLink  = '';
        }

        if (!empty($item['children'])) {
            $html .= '<li class="has-sections" data-nav-nested-dropdown >';
            $html .= '<a class="'.$itemClass.'" href="'.$itemLink.'" data-dropdown-trigger>' . $itemLabel . '</a>';
            $html .= generateMobSubMenuHTML($item['children']);
        }else{
            $html .= '<li data-nav-nested-dropdown>';
            $html .= '<a class="'.$itemClass.'" href="'.$itemLink.'">' . $itemLabel . '</a>';
        }

        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

// Function to recursively generate HTML for the menu
function generateMobMenuHTML($menu) {
    $html = '';
    foreach ($menu as $item) {

        $itemData  = explode("|", $item['label']);
        $itemClass = '';
        $itemLabel = '';
        $itemLink  = '';
        if(isset($itemData[2])){
            $itemClass = $itemData[2];
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }elseif(isset($itemData[1])){
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }else{
            $itemLabel = $itemData[0];
            $itemLink  = '';
        }

        if (!empty($item['children'])) {
            $html .= '<li data-nav-nested-dropdown class="division-professional drop-menu">';    
            $html .= '<a class="'.$itemClass.'" href="'.$itemLink.'" data-dropdown-trigger>' . $itemLabel . '</a>';
            $html .= generateMobSubMenuHTML($item['children']);
            $html .= '</li>';
        }else{
            $html .= '<li data-nav-nested-dropdown class="division-professional">';
            $html .= '<a class="no-need '.$itemClass.'" href="'.$itemLink.'">' . $itemLabel . '</a>';
        }
        
        $html .= '</li>';
    }
    
    return $html;
}
//Generate the HTML for the menu


function generateSubMenuHTML($menu) {
    $html = '<ul class="nav-section-nested-list">';
    foreach ($menu as $item) {
        $itemData  = explode("|", $item['label']);
        $itemClass = '';
        $itemLabel = '';
        $itemLink  = '';
        if(isset($itemData[2])){
            $itemClass = $itemData[2];
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }elseif(isset($itemData[1])){
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }else{
            $itemLabel = $itemData[0];
            $itemLink  = '';
        }

        if (!empty($item['children'])) {
            $html .= '<li class="indented-lightly" data-nav-subsection >';
            $html .= '<a class="'.$itemClass.'" href="'.$itemLink.'" data-subsection-trigger>' . $itemLabel . '</a>';
            $html .= generateSubMenuHTML($item['children']);
        }else{
            $html .= '<li class="indented-lightly">';
            $html .= '<a class="'.$itemClass.'" href="'.$itemLink.'">' . $itemLabel . '</a>';
        }

        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

// Function to recursively generate HTML for the menu
function generateMenuHTML($menu) {
    $html = '';
    foreach ($menu as $item) {

        $itemData  = explode("|", $item['label']);
        $itemClass = '';
        $itemLabel = '';
        $itemLink  = '';
        if(isset($itemData[2])){
            $itemClass = $itemData[2];
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }elseif(isset($itemData[1])){
            $itemLabel = $itemData[0];
            $itemLink  = $itemData[1];
        }else{
            $itemLabel = $itemData[0];
            $itemLink  = '';
        }

        if (!empty($item['children'])) {
            $html .= '<li data-nav-subdivision>';    
            $html .= '<a class="'.$itemClass.'" href="'.$itemLink.'" data-subdivision-trigger>' . $itemLabel . '<b class="caret new-caret"></b></a>';
            $html .= '<div class="nav-section banner-block-tertiary" data-subdivision-menu>
                      <div class="container-fluid container-full">
                      <div class="container-indent">
                      <nav class="nav-section-container container-fluid">';
            $html .= generateSubMenuHTML($item['children']);
            $html .= '</nav>
                      </div>
                      </div>
                      </div>';
        }else{
            $html .= '<li>';
            $html .= '<a class="no-need '.$itemClass.'" href="'.$itemLink.'">' . $itemLabel . '</a>';
        }
        
        $html .= '</li>';
    }
    
    return $html;
}
//Generate the HTML for the menu


$privateCourseArr = array();
$menuHTML = '';
$menuMobHTML = '';

for ($h = 0; $h < count($hashArr); $h++) {
 $linebreakArr = preg_split('/$\R?^/m', trim($hashArr[$h]));    

    // Define the menu string
    $menuString = "Home
    About
    Services
    *Web Development
    **PHP
    ***JavaScript
    ***JavaScript1
    ***JavaScript2
    *Graphic Design
    Contact";

    // Explode the menu string into an array of lines
    // $menuItems = explode(PHP_EOL, $menuString);
    
    $menuItems = $linebreakArr;
    // Initialize an array to store the menu structure
    $menu = [];

    // Process each menu item
    foreach ($menuItems as $menuItem) {
        // Trim leading and trailing whitespace
        $menuItem = trim($menuItem);
        
        // If the item contains indentation (indicating a child item), add it to the last parent
        if (strpos($menuItem, "*") !== false) {
            // Explode the item to get its depth
            $parts = explode("*", $menuItem);
            $depth = count($parts) - 1;
            
            // Add the child to the last parent
            $parent = &$menu;
            for ($i = 0; $i < $depth; $i++) {
                $parent = &$parent[count($parent) - 1]['children'];
            }
            $parent[] = ['label' => trim($parts[$depth])];
        } else {
            // If the item doesn't contain indentation, it's a parent item
            $menu[] = ['label' => $menuItem, 'children' => []];
        }
    }

    $menuHTML    .= generateMenuHTML($menu);
    $menuMobHTML .= generateMobMenuHTML($menu);
    
}

$currentlang = current_language();
$langs = get_string_manager()->get_list_of_translations();

if (isloggedin() && (($USER->auth == "googleoauth2") || (isset($USER->admin)) || (is_siteadmin()))){
    if($USER->lang != $currentlang){
        $update_header = "update mdl_user set lang = '".$currentlang."' where id = ".$USER->id;
        $DB->execute($update_header);
        $USER->lang = $currentlang;
    }
}
//  - - - - -Start -comment PrivateCoursePhase2  NAV
$SESSION->userPrivateCourseArr= $privateCourseArr;
// - - - - - -End -comment PrivateCoursePhase2  NAV
?>

<nav class="site-nav">
  <section class="banner-primary">
   <div class="container-fluid container-full"> <a class="logo" href="#"><span class="screen-reader-text"></span></a>
    <nav class="nav-corporate container-indent visible-hamburger">
     <ul>
        <?php

        // Output the menu
        echo $menuHTML;

        if ($CFG->langmenu == 1) {
            //- - -Start -Language Toggle Button/Field  -Naveen -------//
            $languageName= "";
            foreach ($langs as $langtype => $langname) {
              if($langtype == $currentlang){
                  $languageName=$langname; 
                }
            }
            //- - -End-  Language Toggle Button/Field  - -------//
            ?>
          <li data-nav-subdivision><a href="javascript:void(0);" data-subdivision-trigger><?php  //- - -Start-  Language Toggle Button/Field  - Naveen -------//
            if(!empty($languageName)){ echo $languageName;}else{ echo get_string('language'); }
            //- - -End-  Language Toggle Button/Field  - -------//
           ?> <b class="caret new-caret"></b></a>
           <div class="nav-section banner-block-tertiary" data-subdivision-menu>
            <div class="container-fluid container-full">
             <div class="container-indent">
              <nav class="nav-section-container container-fluid">
               <ul class="nav-section-nested-list">
                <?php
                $rt = 0;
                foreach($langs as $langtype => $langname) { ?>
                  <li><a href="<?php echo new moodle_url($this->page->url, array('lang' => $langtype)); ?>"><?php echo $langname ?></a></li>
                  <?php
                  $rt++;
                } ?>
               </ul>
              </nav>
             </div>
            </div>
           </div>
          </li>
        <?php } ?>
       <li>
        <?php if(isset($_GET['id']) && $_GET['id']!=1881){ ?>
        <a href="https://devtraining2.qsc.com/mod/page/view.php?id=1881"><strong>PRO AUDIO TRAINING - CLICK HERE</strong></a>
        <?php }else if(isset($_GET['id']) && $_GET['id']==1881){ ?>
        <a href="https://devtraining2.qsc.com/qsys.php"><strong>Q-SYS TRAINING - CLICK HERE</strong></a>
        <?php } ?>
       </li>
     </ul>
    </nav>  


    <?php /********************************MOBILE DEVICE ************************************************/ ?>
    <nav class="nav-nested invisible-hamburger" data-nav-nested-dropdown> <a href="#" class="nav-nested-trigger" data-dropdown-trigger>Menu</a>
     <ul class="nav-division-nested" data-dropdown-menu>
        <?php
        
        // Output the Mobile menu
        echo $menuMobHTML;
       
        if ($CFG->langmenu == 1) { ?>
            <li id="bu-nav" class="drop-menu" data-nav-nested-dropdown class="division-professional">
                <a href="javascript:void(0);" data-dropdown-trigger><?php  
                //- - -Start-  Language Toggle Button/Field  - Naveen -------//
                if(!empty($languageName)){ echo $languageName;}else{ echo get_string('language'); }
                //- - -End-  Language Toggle Button/Field  - -------// ?>
                </a>
            <ul class="nav-subdivision-nested" data-dropdown-menu>
            <?php
            $rt = 0;
            foreach($langs as $langtype => $langname) {
                if ($rt == 0) { ?>
                    <li class="" data-nav-nested-dropdown>
                        <a class="no-need-icon" href="<?php echo new moodle_url($this->page->url, array('lang' => 'en')); ?>">English (en)</a>
                    </li>
                <?php } //end if ?>
                <?php if ($langtype != 'en') { ?>
                <li class="" data-nav-nested-dropdown>
                    <a class="no-need-icon" href="<?php echo new moodle_url($this->page->url, array('lang' => $langtype)); ?>" ><?php echo $langname ?></a>
                </li>
                <?php } ?>
            <?php $rt++;
            } 
        }//empty mobilelangmenu
        ?>
        </ul>
        </li>
      </ul>
    </nav>
   </div>
  </section>
</nav>
<script>
$('.nav-corporate li a.no-need').on('mouseover', function() {

 var li$ = $('.nav-corporate li');
 li$.removeClass('active');
 var div$ = $('.nav-corporate li div.nav-section');
 div$.css({"display":"none", "height":"auto"});
//  div$.addCSS("height","auto!important");

})

$('.nav-corporate li a').on('mouseover', function() {
//var div$ = $('.nav-corporate li div.nav-section');
//div$.css({"height":"inherit"});
//  div$.addCSS("height","auto!important");
})


// start - updated for private course
$(document).ready(function() {

    $("nav.nav-corporate ul li:first").find("ul.nav-section-nested-list li").each(function( index ) {
      var anchorLabel = $(this).find("a").html();
      //console.log(anchorLabel);
      if(anchorLabel.indexOf("▸") > -1) {
        //alert("String Found");
        //if($(this).find('ul.data-subsection-menu').length() > 0){
        if($(this).find("a").siblings("ul").length == 0){
          $(this).remove();
        }
      }
    });
});
// end - updated for private course


</script> 

<!--CSS and Script For New Navigation-->
<!-- <script src="/theme/meline29/javascript/meline.js"></script>  -->
<!-- <script src="/theme/meline29/javascript/jquery.matchHeight.js"></script>  -->
<script src="/moodle42/theme/meline29/javascript/nav-script.js"></script>
<!--End CSS and Script For New Navigation-->

