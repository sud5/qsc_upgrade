<style>
.modal-dialog {
    transform: inherit !important;
}
.modal-dialog .modal-content {
    border-radius: 4px !important;
    border: 0 none !important;
}
.modal-header {
    display: block !important;
    border-bottom: 1px solid #e9e9e9 !important;
}
</style><?php
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
 * Book view page
 *
 * @package    mod_book
 * @copyright  2004-2011 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

$id        = optional_param('id', 0, PARAM_INT);        // Course Module ID
$bid       = optional_param('b', 0, PARAM_INT);         // Book id
$chapterid = optional_param('chapterid', 0, PARAM_INT); // Chapter ID
$edit      = optional_param('edit', -1, PARAM_BOOL);    // Edit mode

$pagenum = optional_param('pagenum', 0, PARAM_INT);        //pagenum=1 ID // updated by lakhan
// =========================================================================
// security checks START - teachers edit; students view
// =========================================================================
if ($id) {
    $cm = get_coursemodule_from_id('book', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $book = $DB->get_record('book', array('id'=>$cm->instance), '*', MUST_EXIST);
} else {
    $book = $DB->get_record('book', array('id'=>$bid), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('book', $book->id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $id = $cm->id;
}

require_course_login($course, true, $cm);
$isadmin = is_siteadmin($USER);
//prev-start
    if(!$isadmin && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin'){
    //prev-end
// Checking with course completion criteria and get course first level id for checking completion
if($course->category != 0){
            $coursesData = $DB->get_records('course_completion_criteria', array('course'=>$course->id,'criteriatype'=>8),'','id, course, courseinstance');

 foreach($coursesData as $keyCD){
if($keyCD->courseinstance != ''){
 $sql0m421 = 'SELECT *
                  FROM {course_completions}
                 WHERE course='.$keyCD->courseinstance.' AND userid ='.$USER->id.' AND timecompleted IS NULL';
$completionParent = $DB->get_record_sql($sql0m421);
                    if(is_object($completionParent)){
                    }
}
            }
        }
    }

//prev-start
if((user_has_role_assignment($USER->id,5) != "" || user_has_role_assignment($USER->id,4) != "" || $USER->username == "guest") && $USER->id != 2 && $USER->usertype != 'mainadmin' && $USER->usertype != 'graderasadmin' ){
//prev-end
$facetofaceObj = $DB->get_record_sql('SELECT f.id,fsi.sessionid,fss.signupid,fss.statuscode FROM `mdl_facetoface` as f JOIN mdl_facetoface_sessions as fs ON f.id = fs.facetoface JOIN mdl_facetoface_signups as fsi ON fs.id=fsi.sessionid JOIN mdl_facetoface_signups_status as fss ON fsi.id=fss.signupid WHERE f.course=? AND fsi.userid = ? order by fss.timecreated desc LIMIT 0,1', array($course->id, $USER->id));
//echo "<pre>";print_r($facetofaceObj); exit;
    if(empty($facetofaceObj) || ($facetofaceObj->statuscode != 70 && $facetofaceObj->statuscode != 60)){
        $classroomtypeflag=0;
        require_once('module-lesson-view.php');
    }
    else{
        $rssc3 = $DB->get_record('simplecertificate',array('course'=>$course->id),'id,certexpirydateinyear');
      //echo "<pre>"; print_r($rs3); exit("rs3");
        if($rssc3){
          $sqlsc4 = 'SELECT id, userid, timecreated as timecompletion, timecreatedclassroom
                          FROM {simplecertificate_issues}
                         WHERE certificateid='.$rssc3->id.' AND userid ='.$USER->id.' order by id desc';
          $rssc4 = $DB->get_record_sql($sqlsc4);

//If certificate found means online version should be visible for classroom student
          $cntFlag=0;
          if($rssc4){
            $classroomtypeflag=1;
            require_once('module-lesson-view.php');
            $cntFlag=1;
          }
}
        if($cntFlag == 0)
        require_once('module-lesson-view.php');
    }
}
else{
$context = context_module::instance($cm->id);
require_capability('mod/book:read', $context);

$allowedit  = has_capability('mod/book:edit', $context);
$viewhidden = has_capability('mod/book:viewhiddenchapters', $context);

if ($allowedit) {
    if ($edit != -1 and confirm_sesskey()) {
        $USER->editing = $edit;
    } else {
        if (isset($USER->editing)) {
            $edit = $USER->editing;
        } else {
            $edit = 0;
        }
    }
} else {
    $edit = 0;
}

// read chapters
$chapters = book_preload_chapters($book);

if ($allowedit and !$chapters) {
    redirect('edit.php?cmid='.$cm->id); // No chapters - add new one.
}
// Check chapterid and read chapter data
if ($chapterid == '0') { // Go to first chapter if no given.
    // Trigger course module viewed event.
    book_view($book, null, false, $course, $cm, $context);

    foreach ($chapters as $ch) {
        if ($edit || ($ch->hidden && $viewhidden)) {
            $chapterid = $ch->id;
            break;
        }
        if (!$ch->hidden) {
            $chapterid = $ch->id;
            break;
        }
    }
}

// Prepare header.
$pagetitle = $book->name;
if ($chapter = $DB->get_record('book_chapters', ['id' => $chapterid, 'bookid' => $book->id])) {
    $pagetitle .= ": {$chapter->title}";
}

$PAGE->set_other_editing_capability('mod/book:edit');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);
$PAGE->add_body_class('limitedwidth');

// No content in the book.
if (!$chapterid) {
    $PAGE->set_url('/mod/book/view.php', array('id' => $id));
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('nocontent', 'mod_book'), 'info', false);
} else {
    $PAGE->set_url('/mod/book/view.php', ['id' => $id, 'chapterid' => $chapterid]);
    // The chapter doesnt exist or it is hidden for students.
    if (!$chapter or ($chapter->hidden and !$viewhidden)) {
        $courseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
        throw new moodle_exception('errorchapter', 'mod_book', $courseurl);
    }
    // Add the Book TOC block.
    book_add_fake_block($chapters, $chapter, $book, $cm, $edit);
    book_view($book, $chapter, \mod_book\helper::is_last_visible_chapter($chapter->id, $chapters), $course, $cm, $context);

    echo $OUTPUT->header();
    echo $OUTPUT->heading(format_string($book->name));
    $cminfo = cm_info::create($cm);
    $cmcompletion = \core_completion\cm_completion_details::get_instance($cminfo, $USER->id);
        $activitydates = \core\activity_dates::get_dates_for_module($cminfo, $USER->id);
        echo $OUTPUT->activity_information($cminfo, $cmcompletion, $activitydates);
        if ($book->intro) {
            echo $OUTPUT->box(format_module_intro('book', $book, $cm->id), 'generalbox', 'intro');
        }
        $renderer = $PAGE->get_renderer('mod_book');
    $actionmenu = new \mod_book\output\main_action_menu($cm->id, $chapters, $chapter, $book);
    $renderedmenu = $renderer->render($actionmenu);
    echo $renderedmenu;

    // The chapter itself.
    $hidden = $chapter->hidden ? ' dimmed_text' : null;
    echo $OUTPUT->box_start('generalbox book_content' . $hidden);

    if (!$book->customtitles) {
        if (!$chapter->subchapter) {
            $currtitle = book_get_chapter_title($chapter->id, $chapters, $book, $context);
            echo $OUTPUT->heading($currtitle, 3);
        } else {
            $currtitle = book_get_chapter_title($chapters[$chapter->id]->parent, $chapters, $book, $context);
            $currsubtitle = book_get_chapter_title($chapter->id, $chapters, $book, $context);
            echo $OUTPUT->heading($currtitle, 3);
            echo $OUTPUT->heading($currsubtitle, 4);
        }
    }
    $chaptertext = file_rewrite_pluginfile_urls($chapter->content, 'pluginfile.php', $context->id, 'mod_book',
        'chapter', $chapter->id);
    echo format_text($chaptertext, $chapter->contentformat, ['noclean' => true, 'overflowdiv' => true,
        'context' => $context]);

    echo $OUTPUT->box_end();

    if (core_tag_tag::is_enabled('mod_book', 'book_chapters')) {
        echo $OUTPUT->tag_list(core_tag_tag::get_item_tags('mod_book', 'book_chapters', $chapter->id), null, 'book-tags');
    }
    echo $renderedmenu;
}
echo $OUTPUT->footer();
}
//For navigation test
if($isadmin){ ?>
<script type="text/javascript">
    $("#region-main-box").addClass('col-9');
    $("#region-main-box").removeClass('col-12');
    $(".block_book_toc").addClass('col-3');
    $(".block_book_toc").insertAfter("#region-main-box");
</script>
<?php
	//echo '<script src="/theme/meline29/javascript/nav-script.js"></script>';
} ?>