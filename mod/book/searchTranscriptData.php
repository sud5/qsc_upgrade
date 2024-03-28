<?php

require(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

// Include lib.php
require_once(dirname(__FILE__) . '/lib.php');

$id        = optional_param('id', 0, PARAM_INT);        // Course Module ID
$bid       = optional_param('b', 0, PARAM_INT);         // Book id
$chapterid = optional_param('chapterid', 0, PARAM_INT); // Chapter ID
$edit      = optional_param('edit', -1, PARAM_BOOL);    // Edit mode
$searchtext = $_REQUEST['searchTextData'];
//$searchtext = optional_param('searchTextData', '0', 'PARAM_TEXT');    // Search text - parma can 'PARAM_MULTILANG'

//echo "<pre>";
//print_r($searchtext);
//exit("Success");
//print_r($_REQUEST['courseid']);
// ============================================search==============================================================



// start - updated for private course
// $queryQSYS = "SELECT course.id as courseid, coursecat.id as coursecatid
// FROM mdl_course_categories coursecat
// JOIN mdl_course course ON coursecat.id = course.category
// WHERE (coursecat.id=3 OR coursecat.parent=3) AND course.visible=1";

if(isloggedin()){
    if(!has_capability('moodle/course:viewhiddencourses', $PAGE->context)) {
          $queryQSYS = "SELECT course.id as courseid, coursecat.id as coursecatid FROM mdl_course_categories coursecat JOIN mdl_course course ON coursecat.id = course.category WHERE (coursecat.id=3 OR coursecat.parent=3) AND course.visible=1";

    }else{
          $queryQSYS = "SELECT course.id as courseid, coursecat.id as coursecatid FROM mdl_course_categories coursecat JOIN mdl_course course ON coursecat.id = course.category WHERE (coursecat.id=3 OR coursecat.parent=3)";
    }
}else{
          $queryQSYS = "SELECT course.id as courseid, coursecat.id as coursecatid FROM mdl_course_categories coursecat JOIN mdl_course course ON coursecat.id = course.category WHERE (coursecat.id=3 OR coursecat.parent=3) AND course.visible=1";

}
// start - updated for private course




$qsysResult = $DB->get_records_sql($queryQSYS);

foreach($qsysResult as $keyQSYS){
$courseQSYSidArr[] = $keyQSYS->courseid;
}

$glueQSYSCourseId = implode(",", $courseQSYSidArr);
//echo "<pre>";
//print_r($glueQSYSCourseId);
//exit("Success");


$searchResultHTML = "";
//$st = $searchtext;
// echo $st;die;


$st = addslashes($searchtext);
//$st = str_ireplace('-',' ',$searchtext);
if(strpos($searchtext, "-")){
  $st = substr($searchtext, 0, strpos($searchtext, "-"));
}
//$st = $searchtext;
// echo "<pre>";
// print_r($st);
// exit(" Success");

// Updated By Sameer for QSYS
if(in_array($_REQUEST['courseid'], $courseQSYSidArr)){
  $queryTranscript = "SELECT bk.id, bk_chapter.id AS chapterid, bk.course, bk.name, bk_chapter.bookid, bk_chapter.pagenum, bk_chapter.title, bk_chapter.content, bk_chapter.contenttype, crs_mod.id AS course_module_id, crs_mod.instance, crs_mod.module
  FROM mdl_book bk 
  JOIN mdl_book_chapters bk_chapter ON bk.id = bk_chapter.bookid
  JOIN mdl_course_modules crs_mod ON bk.id = crs_mod.instance 
  WHERE crs_mod.module=3 AND bk.course IN (".$glueQSYSCourseId.") AND bk_chapter.title = 'Video Transcript' AND bk_chapter.content LIKE '%".$st."%' order by chapterid";

  //SELECT * FROM product_table WHERE product_name = REPLACE($keyword,'-','') or product_name LIKE '%$keyword%'
}else{

$queryTranscript = "SELECT bk_chapter.id AS chapterid, bk.id, bk.course, bk.name, bk_chapter.bookid, bk_chapter.pagenum, bk_chapter.title, bk_chapter.content, bk_chapter.contenttype, crs_mod.id AS course_module_id, crs_mod.instance
FROM mdl_book bk 
JOIN mdl_book_chapters bk_chapter ON bk.id = bk_chapter.bookid
JOIN mdl_course c ON c.id = bk.course
JOIN mdl_course_modules crs_mod ON bk.id = crs_mod.instance 
WHERE crs_mod.module=3 AND bk.course=".$_REQUEST['courseid']." AND bk_chapter.title = 'Video Transcript' AND bk_chapter.content LIKE '%".$st."%' order by chapterid";

}
//echo $queryTranscript; exit;
$moduleBookChaptersContent = $DB->get_records_sql($queryTranscript);

    // echo "<pre>";
    // print_r($moduleBookChaptersContent);
    // die;

$matchCount = 0;
$bookIDARR2 = array();
$bookIDARR = array();
if(count($moduleBookChaptersContent) > 0){

  foreach($moduleBookChaptersContent as $chaptersColumns){
    if(isset($bookIDARRCNT)){
      if(!in_array($chaptersColumns->id, $bookIDARRCNT)){
         $searchResultHTML .= '</ul>';
        $searchResultHTML .= "</div>";
      }
    }
    
    if(preg_match("/".$searchtext."/i", strip_tags($chaptersColumns->content) ))
    {
    $bookIDARRCNT[] = $chaptersColumns->id;
    $chaptersColumns->content = str_replace("%2F", "/", $chaptersColumns->content);
    }
    // DOM
    $dom = new domDocument;
    $dom->loadXML($chaptersColumns->content);
    $xpath = new DOMXPath($dom);
    //$tbody = $dom->getElementsByTagName('tbody')->item(0);

    $xpath_resultset =  $xpath->query("//div[@class='caption-line']");

    
    if(!in_array($chaptersColumns->id, $bookIDARR)){
        if(format_string($chaptersColumns->title) == 'Video Transcript'){ //Updated 06Feb2017

          if(preg_match("/".$searchtext."/i", strip_tags($chaptersColumns->content) ))
          {
            $bookIDARR[] = $chaptersColumns->id;
            $searchResultHTML .= "<div class='first-part'><div class='titleTrigger'>
                                        <h3>".format_string($chaptersColumns->name)."</h3></div>";
            $searchResultHTML .= '<ul class="toggleContent">';                                            
          }
      }
    }  
   
    //$htmlString = $dom->saveHTML($xpath_resultset->item(1)); // debug tool for HTML          
    $tab_title = format_string($chaptersColumns->title);        

// echo $chaptersColumns->title;
// die;

    if(format_string($chaptersColumns->title) == 'Video Transcript'){
      foreach($xpath_resultset as $divString){
        //$divString->lastChild->nodeValue = str_replace("/", "%2F", $divString->lastChild->nodeValue);
        if(preg_match("/".$searchtext."/i", strip_tags($divString->textContent) )){
          //$divString->lastChild->nodeValue = str_replace("%2F", "/", $divString->lastChild->nodeValue);
          //$searchtext = str_replace("%2F", "/", $searchtext);

          $firstChild_nodeValue = substr(trim($divString->textContent), 0, 4);
          $lastChild_nodeValue = substr(trim($divString->textContent), 4);

          $searchResultHTM = "<strong>".$tab_title." - ".$firstChild_nodeValue ."</strong> : <a data-desc='' style='display:inline;' class='searchedResultLink' data-time='".$lastChild_nodeValue."' data-caption-line-time='".$firstChild_nodeValue."'  data-pagenum='".$chaptersColumns->pagenum."' data-pagenums='2' href='/mod/book/view.php?id=".$chaptersColumns->course_module_id."&pagenum=".$chaptersColumns->pagenum."'>".$divString->textContent."....</a>";

          $searchResultHTML .= "<li style='list-style-type:unset !important;'>".utf8_decode($searchResultHTM)."</li>";
          $matchCount++;                                 
        }
      }
    }/*else{   //Updated 06Feb2017
      if(preg_match("/".$searchtext."/i", strip_tags($chaptersColumns->content) ))
      {
        $arr = preg_split("/".$searchtext."/i", strip_tags(trim($chaptersColumns->content)));
        //echo "<pre>";
        //  print_r($arr);
        $arr = explode(" ".$searchtext." ",strip_tags($chaptersColumns->content));
        //print_r($arr);
        count($arr);
        $c = count($arr);

        foreach($arr as $value){
          $value = $value ." ". $searchtext;
          $fs = explode(".",$value);
          $i=0;
          
          //array_pop($fs);
          foreach($fs as $fsvalue){
            if(preg_match("/".$searchtext."/i", strip_tags($fsvalue) )){
          //exit;
              $as = strlen($fsvalue);
             // echo $c ." -- ".$i;
              //if($c > $i){
                
                if($as > 10){
                  $searchResultHTM = substr($fsvalue,0,$as)."...";
                }else{
                  $searchResultHTM = $fsvalue."...";
                }

              if(" $searchtext..."!=$searchResultHTM){
//print_r($fsvalue);
                $searchResultHTM = "<a style='display:inline;' data-desc=' ".$searchtext." ' class='searchedResultLinktwo' data-pagenum='".$chaptersColumns->pagenum."' href='/mod/book/view.php?id=".$chaptersColumns->course_module_id."&pagenum=".$chaptersColumns->pagenum."'>".format_string($searchResultHTM)."</a>";
                $searchResultHTML .= "<li style='list-style-type:unset !important;'><strong>".format_string($chaptersColumns->title)."</strong> : ".trim($searchResultHTM)."</li>";
                $matchCount++;
              }
              
              //}
            }
            //echo $as;
            //echo "<br>";
            $i++;
          }
          
        }

        //$searchResultHTM = "<strong>".format_string($chaptersColumns->title)."</strong> : ".format_string($chaptersColumns->content)."....";

        //$searchResultHTML .= "<li style='list-style-type:unset !important;'>".utf8_decode($searchResultHTM)."</li>";
        
        
      }
    }//end else
    */
  }//end foreach
}//end if
if($matchCount>0){
  echo $searchResultHTML."<div><input id='hidSearchtextInput' type='hidden' value='".format_string($searchtext)."'></div>";
}else{
  echo "No Result Found";
}

// echo "<pre>";
// print_r($bookIDARR);
// print_r($bookIDARR2);
// exit;
