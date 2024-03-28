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

$k = 0;
 $layer1 = 0;
  $layer2 = 0;
   $layer3 = 0;

   $privateCourseArr = array();

for ($h = 0; $h < count($hashArr); $h++) {
 $linebreakArr = preg_split('/$\R?^/m', trim($hashArr[$h]));    

 for ($l = 0; $l < count($linebreakArr); $l++) {
  $oblickarr = explode("|", $linebreakArr[$l], 2);







// start - updated for private course
if(count($oblickarr) > 1 && $h == 0){

    if(stristr($oblickarr[1], 'course') !== FALSE){
      $linkArray = explode("?",$oblickarr[1]);
      $linkNameIdArray = explode("=",$linkArray[1]);

      //1-course check
      if(strtolower($linkNameIdArray[0]) != "categoryid"){
        //if course link contails #section
        if(stristr($linkNameIdArray[0], '#') !== FALSE){
            $sectionArray = explode("#",$linkNameIdArray[0]);
            $courseId = $sectionArray[0];

        }else{
            $courseId = $linkNameIdArray[1];
        }

        $courseData = $DB->get_record('course', array('id' => $courseId));

        //2

        // PrivateCoursePhase2 start sameer - to show the private course on associated private user's top navigation section in the site
                    if (!$courseData->visible) {
                     
                        $userGroupSQL = $DB->get_records_sql("SELECT ctp.courseid FROM `mdl_course_tags_pc` as ctp  JOIN `mdl_user_tags_pc` as utp ON ctp.tagid= utp.tagid WHERE utp.userid = '".$USER->id."' AND ctp.courseid= '".$courseData->id."'  LIMIT 0,1");
                      if (isloggedin()){
                        //- - - -Start -comment PrivateCoursePhase2  NAV
                          if(!empty($userGroupSQL))
                            {
                              $privateCourseArr[] = $courseData->id;
                            }
                          //- - - -End -comment PrivateCoursePhase2  NAV
                          // if(!has_capability('moodle/course:viewhiddencourses', $PAGE->context) && (!$courseData->visible)) {
                          //   if(stristr($linebreakArr[$l], "Online Training") == FALSE){
                          //     if($USER->id == '835007904' ){
                          //     echo $linebreakArr[$l];
                          //     }
                          //     $linebreakArr[$l] = "";  
                          //   }
                          // }
                          if (!has_capability('moodle/course:viewhiddencourses', $context = context_course::instance($courseId))) {
                              if (stristr($linebreakArr[$l], "ONLINE COURSES") == FALSE) {
                                  $linebreakArr[$l] = "";
                              }
                          }
                      }else{
                          // if(!$courseData->visible){
                          //   if(stristr($linebreakArr[$l], "Online Training") == FALSE){
                          //     if($USER->id == '835007904' ){
                          //       echo $linebreakArr[$l];
                          //     }
                          //       $linebreakArr[$l] = "";    
                          //     }
                            
                          // }
                        if (stristr($linebreakArr[$l], "ONLINE COURSES") == FALSE) {
                          $linebreakArr[$l] = "";
                        }
                      }
                    }

        
      }//End-course check condition

     
    } 

}
// end - updated for private course

        // start - - - - - -comment PrivateCoursePhase2  NAV
        if (count($oblickarr) > 1 && $h == 1) {
            //print_r($oblickarr[1]);
           
            if (stristr($oblickarr[1], 'facetoface') !== FALSE) {
                $linkArray       = explode("?", $oblickarr[1]);
                $linkNameIdArray = explode("=", $linkArray[1]);
                
                //1 - course case
                if (strtolower($linkNameIdArray[0]) != "categoryid") {
                    //if course link contails #section
                    if (stristr($linkNameIdArray[0], '#') !== FALSE) {
                        $sectionArray = explode("#", $linkNameIdArray[0]);
                        $cmId     = $sectionArray[0];
                    } else {
                        $cmId = $linkNameIdArray[1];
                    }
                     $facetofaceData = $DB->get_record('course_modules', array(
                         'id' => $cmId
                     ));
                    if(empty($facetofaceData))
                    {
                      $facetofaceId= $cmId;
                      $facetofaceData = $DB->get_record('facetoface', array(
                         'id' => $facetofaceId
                      ));
                    }
                     if(!empty($facetofaceData)){
                      $courseIdF = $facetofaceData->course;
                     $courseDataFace = $DB->get_record('course', array(
                         'id' => $courseIdF
                     ));
                    if (!$courseDataFace->visible) {
                        //2
                        if (isloggedin()) {

                            if (!has_capability('moodle/course:viewhiddencourses', $context = context_course::instance($courseIdF))) {
                                if (stristr($oblickarr[1], "ONLINE COURSES") == FALSE) {
                                    $linebreakArr[$l] = "";
                                }
                            }
                        } else {
                            if (!$courseDataFace->visible) {
                                if (stristr($oblickarr[1], "ONLINE COURSES") == FALSE) {
                                    $linebreakArr[$l] = "";
                                }
                            }
                        }
                    }
                  } 
                } // for course case end
            }
            
        }
        // end - - - - - -comment PrivateCoursePhase2  NAV



  $doubledasharr = explode("--", $linebreakArr[$l], 2);

  $theeDasharr = explode("---", $linebreakArr[$l], 2);

  $dasharr = explode("-", $linebreakArr[$l], 2);

  if (!empty($theeDasharr[1])) {     

   $oblickarr = explode("|", $theeDasharr[1], 2);

            $menus[$h][1][$subname][$subname1][] = $theeDasharr[1];  //******* FOR LAYER 3 ******

           } 
           elseif (!empty($doubledasharr[1])) {                

            $oblickarr = explode("|", $doubledasharr[1], 2);
            $subname1 = $oblickarr[0];

            $menus[$h][1][$subname][] = $doubledasharr[1];          
           }        
           elseif (!empty($dasharr[1])) {          
            $oblickarr = explode("|", $dasharr[1], 2);
            $subname = $oblickarr[0];                  
            $menus[$h][1][] = $dasharr[1];            
            $k++;
           }
           else {
            $oblickarr = explode("|", $linebreakArr[$l], 2);          
            $menus[$h][] = $linebreakArr[$l];           
           }
          }
         }
         $currentlang = current_language();
         $langs = get_string_manager()->get_list_of_translations();

         if (isloggedin() && (($USER->auth == "googleoauth2") || (isset($USER->admin)) || (is_siteadmin())))
          {
            // echo $currentlang." <br/>";
            // echo $USER->lang." <br/>";
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
              //echo "<pre>"; print_r($menus);
              for ($r = 0; $r < count($menus); $r++) {
               $oblickarrs = explode("|", $menus[$r][0], 2);
               ?>
               <li <?php
               if (count($menus[$r][1]) > 0) { ?>data-nav-subdivision<?php
               } ?>>
               <a href="<?php echo $oblickarrs[1] ?>" <?php
               if (count($menus[$r][1]) > 0) { ?>data-subdivision-trigger<?php
               }else{ ?>class="no-need" <?php }?>><?php echo $oblickarrs[0] ?>
               <?php
               if (count($menus[$r][1]) > 0) { ?>
               <b class="caret new-caret"></b>
               <?php
              } ?>
             </a>
             <?php
             if (count($menus[$r][1]) > 0) { ?>
             <div class="nav-section banner-block-tertiary" data-subdivision-menu>
              <div class="container-fluid container-full">
               <div class="container-indent">
                <nav class="nav-section-container container-fluid">
                 <ul class="nav-section-nested-list">
                  <?php
                  $layer1 = $menus[$r][1];
                  for ($r1 = 0; $r1 < count($layer1); $r1++) {
                   if (!is_array($layer1[$r1])) {
                    $oblickarrs1 = explode("|", $layer1[$r1], 2);               
                    if(!empty($oblickarrs1[0])){   
                     ?>          
                     <li class="indented-lightly" <?php if(count($layer1[$oblickarrs1[0]]) > 0){ ?>data-nav-subsection<?php }?> ><a href="<?php echo $oblickarrs1[1] ?>" <?php if(count($layer1[$oblickarrs1[0]]) > 0){ ?>data-subsection-trigger<?php }?>><?php echo $oblickarrs1[0] ?></a>
                      <?php
                     }
                    }
                    if (is_array($layer1[$oblickarrs1[0]])) {
                     echo '<ul class="nav-section-nested-list data-subsection-menu">';   
                     $layer2 = $layer1[$oblickarrs1[0]];           
                     for ($r2 = 0; $r2 < count($layer2); $r2++) {
                      $oblickarrs2 = explode("|", $layer2[$r2], 2);                                                              
                      if(!empty($oblickarrs2[0])){
                       ?>
                       <li class="indented-lightly" <?php if(count($layer2[$oblickarrs2[0]]) > 0){ ?>data-nav-subsection<?php }?> ><a href="<?php echo $oblickarrs2[1] ?>" <?php if(count($layer2[$oblickarrs2[0]]) > 0){ ?>data-subsection-trigger<?php }?>><?php echo $oblickarrs2[0] ?></a>
                        <ul class="nav-section-nested-list" data-subsection-menu>
                         <?php    
                   // echo "<pre>"; print_r($menus[0][1][$oblickarrs1[0]][$oblickarrs2[0]]);
                         $layer3 = $menus[0][1][$oblickarrs1[0]][$oblickarrs2[0]];                        
                         
                          for ($r3=0; $r3 < count($layer3) ; $r3++) { 

                           $oblickarrs3 = explode("|", $layer3[$r3],2);
                           if (is_array($layer3)) {
                           if(!empty($oblickarrs3[0])){
                            ?>
                            <li class="indented-lightly"><a href="<?php echo $oblickarrs3[1] ?>"><?php echo $oblickarrs3[0] ?></a></li>
                            <?php
                           }
                          }
                         }
                         ?>
                        </ul>
                       </li>

                       <?php
                      }
                } //end for
                echo '</ul>';

            } //end if
            ?>
           </li>
           <?php
        } //end for
        ?>
       </ul>
      </nav>
     </div>
    </div>
   </div>
   <?php
    } //end if count
    ?>
   </li>
   <?php
  } 
if ($CFG->langmenu == 1) {
  //- - -Start -Language Toggle Button/Field  -Naveen -------//
      $languageName= "";
        foreach ($langs as $langtype => $langname) {
          if($langtype == $currentlang)
            {
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

        foreach($langs as $langtype => $langname) {
         /*if ($rt == 0) { ?>
         <li><a href="<?php
         echo new moodle_url($this->page->url, array(
          'lang' => 'en'
          )); ?>">English (en)</a> </li>
          <?php
          }
          if ($langtype != 'en') { 
          */?>
          <li><a href="<?php
          echo new moodle_url($this->page->url, array(
           'lang' => $langtype
           )); ?>"><?php echo $langname ?></a> </li>
          <?php
         // } 
          ?>
    <?php
    $rt++;
    } 
  }//empty langmenu
?>
</ul>
</nav>
</div>
</div>
</div>
</li>
 <li>
    <?php if($_GET['id']!=1881){ ?>
    <a href="/mod/page/view.php?id=1881"><strong>PRO AUDIO TRAINING - CLICK HERE</strong></a>
<?php }else if($_GET['id']==1881){ ?>
        <a href="/qsys.php"><strong>Q-SYS TRAINING - CLICK HERE</strong></a>
<?php } ?>

</li>
</ul>
</nav>
<?php /********************************MOBILE DEVICE ************************************************/ ?>

<nav class="nav-nested invisible-hamburger" data-nav-nested-dropdown> <a href="#" class="nav-nested-trigger" data-dropdown-trigger>Menu</a>
 <ul class="nav-division-nested" data-dropdown-menu>
  <?php

  for ($r = 0; $r < count($menus); $r++) {
   $oblickarrs = explode("|", $menus[$r][0], 2);
   ?>
   <li id="bu-nav<?php echo $r
   ?>" data-nav-nested-dropdown class="division-professional <?php if (count($menus[$r][1]) > 0){ echo 'drop-menu'; } ?>">
   <a href="<?php echo $oblickarrs[1] ?>" data-dropdown-trigger><?php echo $oblickarrs[0] ?></a>
   <ul class="nav-subdivision-nested" data-dropdown-menu>
    <?php
    for ($r1 = 0; $r1 < count($menus[$r][1]); $r1++) {
     if (!is_array($menus[$r][1][$r1])) {
      $oblickarrs1 = explode("|", $menus[$r][1][$r1], 2); ?>
      <?php
      if (!empty($oblickarrs1[0])) { ?>
      <li <?php
      $layer2 = $menus[$r][1][$oblickarrs1[0]];
      if (count($layer2) > 0) { ?>class="has-sections"<?php
    } ?> data-nav-nested-dropdown> <a href="<?php echo $oblickarrs1[1] ?>" <?php
  if (count($layer2) > 0) { ?>data-dropdown-trigger <?php
  } ?>><?php echo  str_replace("▸", "",$oblickarrs1[0]); ?></a>
  <?php
            } //endif

            if (is_array($layer2)) {
             echo '<ul class="nav-section-nested" data-dropdown-menu>';
             for ($r2 = 0; $r2 < count($layer2); $r2++) {
              $oblickarrs2 = explode("|", $layer2[$r2], 2);
if(!empty($oblickarrs2[0])){
              ?>
             
          
              <li <?php if(count($layer2[$oblickarrs2[0]]) > 0){ ?>class="has-subsection"<?php }?>data-nav-nested-dropdown ><a href="<?php echo $oblickarrs2[1] ?>" <?php if(count($layer2[$oblickarrs2[0]]) > 0){ ?>data-dropdown-trigger<?php }?>><?php echo str_replace("▸", "", $oblickarrs2[0]); ?></a>

                  <ul class="nav-section-nested bgc" data-dropdown-menu>
                  
                    <?php
                    
                    $layer3 = $layer2[$oblickarrs2[0]];                        
                    
                    for ($r3=0; $r3 < count($layer3) ; $r3++) { 

                     $oblickarrs3 = explode("|", $layer3[$r3],2);
                     if (is_array($layer3)) {
                       if(!empty($oblickarrs3[0])){
                        ?>                      

                        <li <?php if(count($layer3[$oblickarrs3[0]]) > 0){ ?>class="has-subsection"<?php }?>data-nav-nested-dropdown ><a href="<?php echo $oblickarrs3[1] ?>" <?php if(count($layer3[$oblickarrs3[0]]) > 0){ ?>data-dropdown-trigger<?php }?>><?php echo str_replace("▸", " ", $oblickarrs3[0]); ?></a></li>
                        <?php
                      }
                    }
                  }
                  ?>
                </ul>
              </li>


              <?php
                } //end for
}
                echo '</ul>';

                // $r1++;

            } //end if
            ?>
          </li>
          <?php
        } //end if
        ?>
        <?php
    } //end for
    ?>
  </ul>
</li>
<?php
} //end for
if ($CFG->langmenu == 1) {
?>
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
   <li class="" data-nav-nested-dropdown><a class="no-need-icon" href="<?php
   echo new moodle_url($this->page->url, array(
    'lang' => 'en'
    )); ?>">English (en)</a></li>
<?php
    } //end if
    ?>
    <?php
    if ($langtype != 'en') { ?>
    <li class="" data-nav-nested-dropdown><a class="no-need-icon" href="<?php
    echo new moodle_url($this->page->url, array(
     'lang' => $langtype
     )); ?>" ><?php echo $langname ?></a></li>
<?php
} ?>
<?php
$rt++;
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
<script src="/theme/meline29/javascript/modernizr.js"></script> 
<script src="/moodle42/theme/meline29/javascript/nav-script.js"></script> 
<!--End CSS and Script For New Navigation-->

