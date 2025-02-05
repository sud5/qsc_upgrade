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
 * Local plugin "assign" - View page
 *
 * @package    local_assign
 * @copyright  2022 @shiva
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php.
// @codingStandardsIgnoreStart
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require(__DIR__ . '/../../config.php');
// @codingStandardsIgnoreEnd

// Include lib.php.
require_once(__DIR__ . '/lib.php');

// Globals.
global $PAGE;

// Get plugin config.
$localassignconfig = get_config('local_assign');

// Require login if the plugin or Moodle is configured to force login.
if ($localassignconfig->forcelogin == assign_FORCELOGIN_YES ||
        ($localassignconfig->forcelogin == assign_FORCELOGIN_GLOBAL && $CFG->forcelogin)) {
    require_login();
}

// View only with /static/ URL.
if ($localassignconfig->apacherewrite == true) {
    if (strpos($_SERVER['REQUEST_URI'], '/static/') > 0 || strpos($_SERVER['REQUEST_URI'], '/static/') === false) {
        die;
    }
}


// Get requested page's name.
$page = required_param('page', PARAM_ALPHANUMEXT);

// Put together document file names.
$filename = "$page.html";

// Fetch context.
$context = \context_system::instance();

// Get filearea.
$fs = get_file_storage();

// Get document from filearea.
$file = $fs->get_file($context->id, 'local_assign', 'documents', 0, '/', $filename);

// If no file is found, quit with error message.
if (!$file) {
    print_error('pagenotfound', 'local_assign');
}

// Get file content.
$filecontents = $file->get_content();

// Import the document, load DOM.
$staticdoc = new DOMDocument();
$staticdoc->loadHTML($filecontents);


// Set page url.
if ($localassignconfig->apacherewrite == true) {
    $PAGE->set_url('/static/'.$page.'.html');
} else {
    $PAGE->set_url('/local/assign/view.php?page='.$page);
}

// Set page context.
$PAGE->set_context(context_system::instance());

// Set page layout.
$PAGE->set_pagelayout('standard');


// Extract page's first h1 (if present).
if (!empty($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue)) {
    $firsth1 = format_string($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue);
} else {
    $firsth1 = $page;
}

// Extract page title (if present).
if (!empty($staticdoc->getElementsByTagName('title')->item(0)->nodeValue)) {
    $title = format_string($staticdoc->getElementsByTagName('title')->item(0)->nodeValue);
} else {
    $title = $page;
}

// Extract style tag in head (if present) and insert into HTML head.
if (!empty($staticdoc->getElementsByTagName('style')->item(0)->nodeValue)) {
    $style = $staticdoc->getElementsByTagName('style')->item(0)->nodeValue;
    $CFG->additionalhtmlhead = $CFG->additionalhtmlhead.'<style>'.$style.'</style>';
}


// Set page title.
if ($localassignconfig->documenttitlesource == assign_TITLE_H1) {
    $PAGE->set_title($firsth1);
} else if ($localassignconfig->documenttitlesource == assign_TITLE_HEAD) {
    $PAGE->set_title($title);
} else {
    $PAGE->set_title($title);
}

// Set page heading.
if ($localassignconfig->documentheadingsource == assign_TITLE_H1) {
    $PAGE->set_heading($firsth1);
} else if ($localassignconfig->documentheadingsource == assign_TITLE_H1) {
    $PAGE->set_heading($title);
} else {
    $PAGE->set_heading($title);
}

// Set page navbar.
if ($localassignconfig->documentnavbarsource == assign_TITLE_H1) {
    $PAGE->navbar->add($firsth1);
} else if ($localassignconfig->documentnavbarsource == assign_TITLE_HEAD) {
    $PAGE->navbar->add($title);
} else {
    $PAGE->navbar->add($title);
}

echo $OUTPUT->header();

// Get html code.
$html = $staticdoc->saveHTML();

// Remove everything except body tag content from html.
$startcut = strpos($html, '<body>') + 6;
$stopcut = strpos($html, '</body>') - $startcut;
$html = substr($html, $startcut, $stopcut);

// Print html code.
if ($localassignconfig->processfilters == assign_PROCESSFILTERS_YES &&
        $localassignconfig->cleanhtml == assign_CLEANHTML_YES) {
    echo format_text($html, FORMAT_HTML, array('trusted' => false, 'noclean' => false, 'filter' => true));
} else if ($localassignconfig->processfilters == assign_PROCESSFILTERS_YES &&
        $localassignconfig->cleanhtml == assign_CLEANHTML_NO) {
    echo format_text($html, FORMAT_HTML, array('trusted' => true, 'noclean' => true, 'filter' => true));
} else if ($localassignconfig->processfilters == assign_PROCESSFILTERS_NO &&
        $localassignconfig->cleanhtml == assign_CLEANHTML_YES) {
    echo format_text($html, FORMAT_HTML, array('trusted' => false, 'noclean' => false, 'filter' => false));
} else if ($localassignconfig->processfilters == assign_PROCESSFILTERS_NO &&
        $localassignconfig->cleanhtml == assign_CLEANHTML_NO) {
    echo format_text($html, FORMAT_HTML, array('trusted' => true, 'noclean' => true, 'filter' => false));
} else { // This should not happen.
    echo $html;
}

echo $OUTPUT->footer();
