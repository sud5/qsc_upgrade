<?php
//1069 start ALL/ANY
if(isset($getInCompPreCourseId)){
  foreach ($getInCompPreCourseId as $keyCourseId) {
    $courseObjData = $DB->get_record('course', array('id'=>$keyCourseId),'fullname');

    $courseAssignCNTSQL = 'SELECT count(id) as cntexam FROM {assign} WHERE course =' . $keyCourseId;
    $courseAssignCNTRes = $DB->get_record_sql($courseAssignCNTSQL);
        
    $courseAssignSQL = 'SELECT id FROM {assign} WHERE course =' . $keyCourseId . ' and type ="classroom"';
    $courseAssignRes = $DB->get_record_sql($courseAssignSQL);

    if (!empty($courseAssignRes) && $courseAssignCNTRes->cntexam == 1) {
      $courseF2FSQL = 'SELECT id FROM {facetoface} WHERE course =' . $keyCourseId;
      $courseF2FRes = $DB->get_record_sql($courseF2FSQL);
      $liCourseList .= '<li><a href="/mod/facetoface/view.php?f='.$courseF2FRes->id.'">'.format_string($courseObjData->fullname).'</a></li>';
    }
    else
      $liCourseList .= '<li><a href="/course/view.php?id='.$keyCourseId.'">'.format_string($courseObjData->fullname).'</a></li>';
  }
}
else {
  $courseObjData = $DB->get_record('course', array('id'=>$keyCD->courseinstance),'fullname');
  $liCourseList = '<li><a href="/course/view.php?id='.$keyCD->courseinstance.'">'.format_string($courseObjData->fullname).'</a></li>';
}
//1069 end
?>
 <!-- Modal Start For Exit Exam Design Page-->

        <div class="modal fade" id="memberModalPCourses" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                  <div class="prereq_comp_page_main">
                    <div class="prereq_comp_page_bottom_full_section" style="margin:10px 0 10px 10px !important;">
                      <h3><strong><?=get_string("prereqone")?>: <?=format_string($course->fullname)?></strong></h3>
                      <div class="bottom_section">
                       
                        <div class="prereq_comp_page_full_right_secion">
                          <!-- PP2 start -->
                          <p>Attendance of a <strong><?=format_string($course->fullname)?></strong> <?=get_string("prereqtwo")?>:</p>
                          <!-- PP2 end -->
                          <ul>
                            <?php 
                              //1069 start omit li tag and make it dynamic
                              echo $liCourseList;
                              //1069 end omit li tag and make it dynamic
                            ?>
                          </ul>                                
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
                

<style>
.prereq_comp_page_bottom_full_section {
    border: 1px solid #dddddd;
    display: inline-block;
    margin: 20px 0;
    width: 100%;
  /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f6f6f6+0,f9f9f9+100 */
background: rgb(246,246,246); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(246,246,246,1) 0%, rgba(249,249,249,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  rgba(246,246,246,1) 0%,rgba(249,249,249,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  rgba(246,246,246,1) 0%,rgba(249,249,249,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f6f6f6', endColorstr='#f9f9f9',GradientType=0 ); /* IE6-9 */

    border-radius: 5px;
  
  padding:20px;
  box-sizing:border-box;
}
.prereq_comp_page_full_left_secion img {
    width: 150px;
}
.prereq_comp_page_full_left_secion {
    float: left;
    margin-right: 2%;
    text-align: center;
    width: 16%;
}
.prereq_comp_page_full_right_secion {
    float: left;
    width: 82%;
}
.prereq_comp_page_main li {
    list-style: outside none none;
}
.prereq_comp_page_top_section h3 {
    font-size: 20px;
}
.prereq_comp_page_brreadcrumb ul li {
    display: inline;
    margin-right: 2px;
}
.prereq_comp_page_brreadcrumb span {
    margin-left: 6px;
}
.prereq_comp_page_brreadcrumb ul li a, .prereq_comp_page_brreadcrumb ul li span {
    color: #555;
}
.prereq_comp_page_bottom_full_section h3 {
   font-size: 20px;
   line-height:normal;
}
.prereq_comp_page_main a:hover
{
color:#0070a8 !important;
}

.prereq_comp_page_main h3
{
color:#0070a8;
 line-height:normal;
}

.bottom_section {
    display: inline-block;
    margin-top: 20px;
    width: 100%;
}

.prereq_comp_page_full_right_secion ul {
    margin: 15px 0;
}
.prereq_comp_page_full_right_secion ul {
    color: #0070a8;
    padding-left: 30px;
}
.prereq_comp_page_main .prereq_comp_page_full_right_secion ul li {
    list-style:disc !important;
    margin: 5px 0;
}
.prereq_comp_page_brreadcrumb {
    margin: 5px 0;
}

@media (max-width:640px)
{
.prereq_comp_page_full_left_secion {
    float: left;
    margin-bottom: 20px;
    margin-left: 0;
    margin-top: 20px;
    text-align: center;
    width: 100%;
}
.prereq_comp_page_full_right_secion {
    float: left;
    width: 100%;
}

.prereq_comp_page_main h3
{
font-size:16px;
}
}
</style>
