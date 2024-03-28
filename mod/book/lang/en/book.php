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
 * Book module language strings
 *
 * @package    mod_book
 * @copyright  2004-2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['modulename'] = 'Book';
$string['modulename_help'] = 'The book module enables a teacher to create a multi-page resource in a book-like format, with chapters and subchapters. Books can contain media files as well as text and are useful for displaying lengthy passages of information which can be broken down into sections.

A book may be used

* To display reading material for individual modules of study
* As a staff departmental handbook
* As a showcase portfolio of student work';
$string['modulename_link'] = 'mod/book/view';
$string['modulenameplural'] = 'Books';
$string['pluginname'] = 'Book';
$string['pluginadministration'] = 'Book administration';

$string['toc'] = 'Table of contents';
$string['chapterandsubchaptersdeleted'] = 'Chapter "{$a->title}" and its {$a->subchapters} subchapters were deleted';
$string['chapterdeleted'] = 'Chapter "{$a->title}" was deleted';
$string['customtitles'] = 'Custom titles';
$string['customtitles_help'] = 'Normally the chapter title is displayed in the table of contents (TOC) AND as a heading above the content.

If the custom titles checkbox is ticked, the chapter title is NOT displayed as a heading above the content. A different title (perhaps longer than the chapter title) may be entered as part of the content.';
$string['chapters'] = 'Chapters';
$string['chaptertitle'] = 'Chapter title';
$string['content'] = 'Content';
$string['deletechapter'] = 'Delete chapter "{$a}"';
$string['editingchapter'] = 'Editing chapter';
$string['eventchaptercreated'] = 'Chapter created';
$string['eventchapterdeleted'] = 'Chapter deleted';
$string['eventchapterupdated'] = 'Chapter updated';
$string['eventchapterviewed'] = 'Chapter viewed';
$string['editchapter'] = 'Edit chapter "{$a}"';
$string['hidechapter'] = 'Hide chapter "{$a}"';
$string['indicator:cognitivedepth'] = 'Book cognitive';
$string['indicator:cognitivedepth_help'] = 'This indicator is based on the cognitive depth reached by the student in a Book resource.';
$string['indicator:cognitivedepthdef'] = 'Book cognitive';
$string['indicator:cognitivedepthdef_help'] = 'The participant has reached this percentage of the cognitive engagement offered by the Book activities during this analysis interval (Levels = No view, View)';
$string['indicator:cognitivedepthdef_link'] = 'Learning_analytics_indicators#Cognitive_depth';
$string['indicator:socialbreadth'] = 'Book social';
$string['indicator:socialbreadth_help'] = 'This indicator is based on the social breadth reached by the student in a Book resource.';
$string['indicator:socialbreadthdef'] = 'Book social';
$string['indicator:socialbreadthdef_help'] = 'The participant has reached this percentage of the social engagement offered by the Book activities during this analysis interval (Levels = No participation, Participant alone)';
$string['indicator:socialbreadthdef_link'] = 'Learning_analytics_indicators#Social_breadth';
$string['movechapterup'] = 'Move chapter up "{$a}"';
$string['movechapterdown'] = 'Move chapter down "{$a}"';
$string['privacy:metadata'] = 'The book activity module does not store any personal data.';
$string['search:activity'] = 'Book - resource information';
$string['search:chapter'] = 'Book - chapters';
$string['showchapter'] = 'Show chapter "{$a}"';
$string['subchapter'] = 'Subchapter';
$string['navimages'] = 'Images';
$string['navoptions'] = 'Available options for navigational links';
$string['navoptions_desc'] = 'Options for displaying navigation on the book pages';
$string['navstyle'] = 'Style of navigation';
$string['navstyle_help'] = '* Images - Icons are used for navigation
* Text - Chapter titles are used for navigation';
$string['navtext'] = 'Text';
$string['navtoc'] = 'TOC Only';
$string['nocontent'] = 'No content has been added to this book yet.';
$string['numbering'] = 'Chapter formatting';
$string['numbering_help'] = '* None - Chapter and subchapter titles have no formatting
* Numbers - Chapters and subchapter titles are numbered 1, 1.1, 1.2, 2, ...
* Bullets - Subchapters are indented and displayed with bullets in the table of contents
* Indented - Subchapters are indented in the table of contents';
$string['numbering0'] = 'None';
$string['numbering1'] = 'Numbers';
$string['numbering2'] = 'Bullets';
$string['numbering3'] = 'Indented';
$string['numberingoptions'] = 'Available options for chapter formatting';
$string['numberingoptions_desc'] = 'Options for displaying chapters and subchapters in the table of contents';
$string['addafter'] = 'Add new chapter';
$string['addafterchapter'] = 'Add new chapter after "{$a->title}"';
$string['previouschapter'] = 'Previous chapter';
$string['confchapterdelete'] = 'Do you really want to delete this chapter?';
$string['confchapterdeleteall'] = 'Do you really want to delete this chapter and all its subchapters?';
$string['top'] = 'top';
$string['navprev'] = 'Previous';
$string['navprevtitle'] = 'Previous: {$a}';
$string['navnext'] = 'Next';
$string['navnexttitle'] = 'Next: {$a}';
$string['navexit'] = 'Exit book';
$string['book:addinstance'] = 'Add a new book';
$string['book:read'] = 'View book';
$string['book:edit'] = 'Edit book chapters';
$string['book:viewhiddenchapters'] = 'View hidden book chapters';
$string['errorchapter'] = 'Error reading chapter of book.';

$string['page-mod-book-x'] = 'Any book module page';
$string['subchapternotice'] = '(Only available once the first chapter has been created)';
$string['subplugintype_booktool'] = 'Book tool';
$string['subplugintype_booktool_plural'] = 'Book tools';

$string['removeallbooktags'] = 'Remove all book tags';
$string['tagarea_book_chapters'] = 'Book chapters';
$string['tagsdeleted'] = 'Book tags have been deleted';
//2.9 version lang variables are added here
$string['displaytime'] = 'Display Time';
$string['headingExitExamPopUp'] = 'One More Step';
//New modules added and Renewal Certification Process - START BY PAWAN
//$string['summaryExitExamPopUp'] = 'Congratulations, you have completed the necessary assessments for this Course. The last thing you need to do is to successfully complete the exit exam for this course.';

$string['summaryExitExamPopUp'] = 'Congratulations, you have completed the necessary assessments for this Course. Your final task is to successfully complete the exit exam for this course.';

//New modules added and Renewal Certification Process - END BY PAWAN

$string['assessment'] = 'Assessment';
$string['cthumbnail'] = 'Current Thumbnail';
$string['ccollapse'] = 'Collapse all';
$string['cexpand'] = 'Expand all';
$string['csearchresult'] = 'Close Search Results';
/* ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  */

//$string['crenewexp'] = 'Its time to renew your certification. One or more modules have changed since you certified on ';

$string['crenewexp'] = 'It’s time to renew your certification. One or more modules have changed since you received your certification on ';

//$string['crenewmodules'] = 'Please complete the new modules with the <span class="span-notice-in-process"></span> icon, including retaking any quizzes.You will not need to resubmit a new final exam design.';
$string['crenewmodules'] = 'Please complete the new modules marked with the <span class="span-notice-in-process"></span> icon. You will not need to resubmit a new final exam design.';

/* ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  */

$string['crenewcert'] = 'Renew Your Certification';
$string['ccongcourse'] = 'Congratulations, you have sucessfully completed this Course';
$string['ccertsteps'] = 'Certification Steps Completed';
$string['cfinalexamcomp'] = 'FINAL EXAM COMPLETED';
$string['cfinalexamfor'] = 'the Final Exam for';
/* ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  */

//$string['cdownloadcert'] = 'Download your Certificate for Completion';
$string['cdownloadcert'] = 'Download your Certificate';

/* ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  */
$string['cdowncert'] = 'Download Certificate';
$string['cdownnewcert'] = 'Download Your new Certificate';
$string['cdownnewcertcontent'] = '<strong>Congratulations, you have completed {$a}.</strong> <br/>On behalf of the entire QSC Training and Education department, we would like to congratulate you on the successful completion of {$a} !'; // added by lakhan for certificate pop-up

$string['cproceedfinal'] = 'Proceed to the Final Exam Page';
/* ======== New modules added and Renewal Certification Process - START BY PAWAN ===================  */
//$string['cgeneratecert'] = 'Generating certificate after refresh same page in between 10-15 minutes, Download link is loading now...';
$string['cgeneratecert'] = 'A download link containing your certificate is in process. Please refresh the page after a few minutes.';
/* ======== New modules added and Renewal Certification Process - END BY PAWAN ===================  */


$string['ccongsasscourse'] = 'Congratulations, you have sucessfully completed the necessary assesments for this Course';
$string['ccongs'] = 'Congratulations,';
$string['cnotcompcourse'] = 'You have not sucessfully completed the Final Exam for this Course';
$string['cfinalexamon'] = 'Final Exam On Process';
$string['cgetcert'] = 'Get Certificate';
$string['newlyupdatedquiz'] = 'Newly Updated Quiz';
$string['testrespass'] = 'Test Result Passed';
$string['testresfail'] = 'Test Result Failed';
$string['newmodadd'] = 'NEW MODULES ADDED';
$string['proceedfinalexam'] = 'Proceed to the Final Exam Page';
$string['proceedtofinalexam'] = 'proceed to final exam';

//New modules added and Renewal Certification Process - START BY PAWAN

/*$string['renewexam'] = 'Its time to renew your certification.<br>
        One or more modules have changed since you certified on <b> "{$a}"</b>.<br>
        Please complete the new modules with the <span class="span-notice-in-process"></span> icon, including retaking any quizzes.<br>
        You will not need to resubmit a new final exam design.';*/

$string['renewexam'] = 'It’s time to renew your certification.<br>
        One or more modules have changed since you received your certification on <b> {$a}</b>.<br>
        Please complete the new modules marked with the <span class="span-notice-in-process"></span> icon.<br>
        You will not need to resubmit a new final exam design.';

//New modules added and Renewal Certification Process - END BY PAWAN

$string['newmodupdatedesc'] = '<p style="text-align:justify;">
One or more modules in this course has been updated.  New modules are signified by a  <span class="star-img"></span> icon. </p>
              <p> When your certification expires on "{$a}", you will be required to complete the updated curriculum.</p>';
$string['disabledproceedexamstatus'] = 'You must first pass all course assessments to proceed';
//Certificate Dates code start
$string['crenewcourse'] = 'If you have completed the new modules, your certificate will be automatically renewed on the day of its expiration.';
$string['certexpire'] = "Your Certificate Expired.";
$string['creprintcert'] = 'Reprint Certificate';
//Certificate Dates code end
