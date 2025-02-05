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
 * Moodle frontpage.
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// if($_SERVER['HTTPS']!="on")
// {
//  $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//  header("Location:$redirect");
// }


if (!file_exists('./config.php')) {
    header('Location: install.php');
    die;
}

require_once('config.php');
require_once($CFG->dirroot .'/course/lib.php');
require_once($CFG->libdir .'/filelib.php');

redirect_if_major_upgrade_required();
if (defined('BEHAT_SITE_RUNNING') && BEHAT_SITE_RUNNING) {
    $wantsurl    = optional_param('wantsurl', '', PARAM_LOCALURL);   // Overrides $SESSION->wantsurl if given.
    if ($wantsurl !== '') {
        $SESSION->wantsurl = (new moodle_url($wantsurl))->out(false);
    }
}
if (!empty($SESSION->has_timed_out)) {
    $session_has_timed_out = true;
    unset($SESSION->has_timed_out);
} else {
    $session_has_timed_out = false;
}
// if($adminIdentification != 'admin' && !isset($_REQUEST['username'])){
//     if( (!isset($USER->admin)) && (!is_siteadmin()) && ($_SESSION['REALUSER']->auth != 'manual') ){ // added condition for loginas for admin
//         if($_COOKIE[ACTIVE_SESSION] != 'Y'){
//             // require_once($CFG->dirroot . '/auth/googleoauth2/classes/provider/qscid.php');
//             // // Load the provider plugin.
//             // $provider = new provideroauth2qscid();
//             // $authurl = $provider->getAuthorizationUrl();
//             // redirect($authurl);   
//             if (isset($SESSION->wantsurl)) {
//             $urltogo = $SESSION->wantsurl;
//         } else {
//             $urltogo = $CFG->wwwroot.'/';
//         }
//       //  echo $urltogo;
//         unset($SESSION->wantsurl); //exit("test");
//         redirect($urltogo);
//              //   redirect(new moodle_url('/'));   
//             }
//         }
// }
$urlparams = array();
if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MY) && optional_param('redirect', 1, PARAM_BOOL) === 0) {
    $urlparams['redirect'] = 0;
}
$PAGE->set_url('/', $urlparams);
$PAGE->set_course($SITE);
$PAGE->set_other_editing_capability('moodle/course:update');
$PAGE->set_other_editing_capability('moodle/course:manageactivities');
$PAGE->set_other_editing_capability('moodle/course:activityvisibility');

// Prevent caching of this page to stop confusion when changing page after making AJAX changes.
$PAGE->set_cacheable(false);

if ($CFG->forcelogin) {
    require_login();
} else {
    user_accesstime_log();
}

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());

// If the site is currently under maintenance, then print a message.
if (!empty($CFG->maintenance_enabled) and !$hassiteconfig) {
    print_maintenance_message();
}

if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
}

// if (get_home_page() != HOMEPAGE_SITE) {
//     // Redirect logged-in users to My Moodle overview if required.
//     $redirect = optional_param('redirect', 1, PARAM_BOOL);
//     if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
//         set_user_preference('user_home_page_preference', HOMEPAGE_SITE);
//     } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MY) && $redirect === 1) {
//         redirect($CFG->wwwroot .'/my/');
//     } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_USER)) {
//         $frontpagenode = $PAGE->settingsnav->find('frontpage', null);
//         if ($frontpagenode) {
//             $frontpagenode->add(
//                 get_string('makethismyhome'),
//                 new moodle_url('/', array('setdefaulthome' => true)),
//                 navigation_node::TYPE_SETTING);
//         } else {
//             $frontpagenode = $PAGE->settingsnav->add(get_string('frontpagesettings'), null, navigation_node::TYPE_SETTING, null);
//             $frontpagenode->force_open();
//             $frontpagenode->add(get_string('makethismyhome'),
//                 new moodle_url('/', array('setdefaulthome' => true)),
//                 navigation_node::TYPE_SETTING);
//         }
//     }
// }

// Trigger event.
course_view(context_course::instance(SITEID));

// If the hub plugin is installed then we let it take over the homepage here.
if (file_exists($CFG->dirroot.'/local/hub/lib.php') and get_config('local_hub', 'hubenabled')) {
    require_once($CFG->dirroot.'/local/hub/lib.php');
    $hub = new local_hub();
    $continue = $hub->display_homepage();
    // Function display_homepage() returns true if the hub home page is not displayed
    // ...mostly when search form is not displayed for not logged users.
    if (empty($continue)) {
        exit;
    }
}

$PAGE->set_pagetype('site-index');
$PAGE->set_docs_path('');
$PAGE->set_pagelayout('frontpage');
$editing = $PAGE->user_is_editing();
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);
$courserenderer = $PAGE->get_renderer('core', 'course');
echo $OUTPUT->header();

// Print Section or custom info.
$siteformatoptions = course_get_format($SITE)->get_format_options();
$modinfo = get_fast_modinfo($SITE);
$modnames = get_module_types_names();
$modnamesplural = get_module_types_names(true);
$modnamesused = $modinfo->get_used_module_names();
$mods = $modinfo->get_cms();

if (!empty($CFG->customfrontpageinclude)) {
    include($CFG->customfrontpageinclude);

} else if ($siteformatoptions['numsections'] > 0) {
    if ($editing) {
        // Make sure section with number 1 exists.
        course_create_sections_if_missing($SITE, 1);
        // Re-request modinfo in case section was created.
        $modinfo = get_fast_modinfo($SITE);
    }
    $section = $modinfo->get_section_info(1);
    if (($section && (!empty($modinfo->sections[1]) or !empty($section->summary))) or $editing) {
        echo $OUTPUT->box_start('generalbox sitetopic');

        // If currently moving a file then show the current clipboard.
        if (ismoving($SITE->id)) {
            $stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
            echo '<p><font size="2">';
            echo "$stractivityclipboard&nbsp;&nbsp;(<a href=\"course/mod.php?cancelcopy=true&amp;sesskey=".sesskey()."\">";
            echo get_string('cancel') . '</a>)';
            echo '</font></p>';
        }

        $context = context_course::instance(SITEID);

        // If the section name is set we show it.
        if (!is_null($section->name)) {
            echo $OUTPUT->heading(
                format_string($section->name, true, array('context' => $context)),
                2,
                'sectionname'
            );
        }

        $summarytext = file_rewrite_pluginfile_urls($section->summary,
            'pluginfile.php',
            $context->id,
            'course',
            'section',
            $section->id);
        $summaryformatoptions = new stdClass();
        $summaryformatoptions->noclean = true;
        $summaryformatoptions->overflowdiv = true;

        echo format_text($summarytext, $section->summaryformat, $summaryformatoptions);

        if ($editing && has_capability('moodle/course:update', $context)) {
            $streditsummary = get_string('editsummary');
            echo "<a title=\"$streditsummary\" ".
                 " href=\"course/editsection.php?id=$section->id\"><img src=\"" . $OUTPUT->pix_url('t/edit') . "\" ".
                 " class=\"iconsmall\" alt=\"$streditsummary\" /></a><br /><br />";
        }

        $courserenderer = $PAGE->get_renderer('core', 'course');
        echo $courserenderer->course_section_cm_list($SITE, $section);

        echo $courserenderer->course_section_add_cm_control($SITE, $section->section);
        echo $OUTPUT->box_end();
    }
}
// Include course AJAX.
include_course_ajax($SITE, $modnamesused);

if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
    $frontpagelayout = $CFG->frontpageloggedin;
} else {
    $frontpagelayout = $CFG->frontpage;
}

foreach (explode(',', $frontpagelayout) as $v) {
    switch ($v) {
        // Display the main part of the front page.
        case FRONTPAGENEWS:
            if ($SITE->newsitems) {
                // Print forums only when needed.
                require_once($CFG->dirroot .'/mod/forum/lib.php');

                if (! $newsforum = forum_get_course_forum($SITE->id, 'news')) {
                    print_error('cannotfindorcreateforum', 'forum');
                }

                // Fetch news forum context for proper filtering to happen.
                $newsforumcm = get_coursemodule_from_instance('forum', $newsforum->id, $SITE->id, false, MUST_EXIST);
                $newsforumcontext = context_module::instance($newsforumcm->id, MUST_EXIST);

                $forumname = format_string($newsforum->name, true, array('context' => $newsforumcontext));
                echo html_writer::tag('a',
                    get_string('skipa', 'access', core_text::strtolower(strip_tags($forumname))),
                    array('href' => '#skipsitenews', 'class' => 'skip-block'));

                // Wraps site news forum in div container.
                echo html_writer::start_tag('div', array('id' => 'site-news-forum'));

                if (isloggedin()) {
                    $SESSION->fromdiscussion = $CFG->wwwroot;
                    $subtext = '';
                    if (\mod_forum\subscriptions::is_subscribed($USER->id, $newsforum)) {
                        if (!\mod_forum\subscriptions::is_forcesubscribed($newsforum)) {
                            $subtext = get_string('unsubscribe', 'forum');
                        }
                    } else {
                        $subtext = get_string('subscribe', 'forum');
                    }
                    echo $OUTPUT->heading($forumname);
                    $suburl = new moodle_url('/mod/forum/subscribe.php', array('id' => $newsforum->id, 'sesskey' => sesskey()));
                    echo html_writer::tag('div', html_writer::link($suburl, $subtext), array('class' => 'subscribelink'));
                } else {
                    echo $OUTPUT->heading($forumname);
                }

                forum_print_latest_discussions($SITE, $newsforum, $SITE->newsitems, 'plain', 'p.modified DESC');

                // End site news forum div container.
                echo html_writer::end_tag('div');

                echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipsitenews'));
            }
        break;

        case FRONTPAGEENROLLEDCOURSELIST:
            $mycourseshtml = $courserenderer->frontpage_my_courses();
            if (!empty($mycourseshtml)) {
                echo html_writer::tag('a',
                    get_string('skipa', 'access', core_text::strtolower(get_string('mycourses'))),
                    array('href' => '#skipmycourses', 'class' => 'skip-block'));

                // Wrap frontpage course list in div container.
                echo html_writer::start_tag('div', array('id' => 'frontpage-course-list'));

                echo $OUTPUT->heading(get_string('mycourses'));
                echo $mycourseshtml;

                // End frontpage course list div container.
                echo html_writer::end_tag('div');

                echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipmycourses'));
                break;
            }
            // No "break" here. If there are no enrolled courses - continue to 'Available courses'.

        case FRONTPAGEALLCOURSELIST:
            $availablecourseshtml = $courserenderer->frontpage_available_courses();
            if (!empty($availablecourseshtml)) {
                echo html_writer::tag('a',
                    get_string('skipa', 'access', core_text::strtolower(get_string('availablecourses'))),
                    array('href' => '#skipavailablecourses', 'class' => 'skip-block'));

                // Wrap frontpage course list in div container.
                echo html_writer::start_tag('div', array('id' => 'frontpage-course-list'));

                echo $OUTPUT->heading(get_string('availablecourses'));
                echo $availablecourseshtml;

                // End frontpage course list div container.
                echo html_writer::end_tag('div');

                echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipavailablecourses'));
            }
        break;

        case FRONTPAGECATEGORYNAMES:
            echo html_writer::tag('a',
                get_string('skipa', 'access', core_text::strtolower(get_string('categories'))),
                array('href' => '#skipcategories', 'class' => 'skip-block'));

            // Wrap frontpage category names in div container.
            echo html_writer::start_tag('div', array('id' => 'frontpage-category-names'));

            echo $OUTPUT->heading(get_string('categories'));
            echo $courserenderer->frontpage_categories_list();

            // End frontpage category names div container.
            echo html_writer::end_tag('div');

            echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipcategories'));
        break;

        case FRONTPAGECATEGORYCOMBO:
            echo html_writer::tag('a',
                get_string('skipa', 'access', core_text::strtolower(get_string('courses'))),
                array('href' => '#skipcourses', 'class' => 'skip-block'));

            // Wrap frontpage category combo in div container.
            echo html_writer::start_tag('div', array('id' => 'frontpage-category-combo'));

            echo $OUTPUT->heading(get_string('courses'));
            echo $courserenderer->frontpage_combo_list();

            // End frontpage category combo div container.
            echo html_writer::end_tag('div');

            echo html_writer::tag('span', '', array('class' => 'skip-block-to', 'id' => 'skipcourses'));
        break;

        case FRONTPAGECOURSESEARCH:
            echo $OUTPUT->box($courserenderer->course_search_form('', 'short'), 'mdl-align');
        break;

    }
    echo '<br />';
}
if ($editing && has_capability('moodle/course:create', context_system::instance())) {
    echo $courserenderer->add_new_course_button();
}
echo $OUTPUT->footer();

?>

<style type="text/css">
@media (min-width: 860px){
    .top-section-cover .span9 {
        margin-top: 4px;
        width: auto !important;
        margin-left: 0;
    }
}

.home-page-blend-structure-starts {
    background-color: #eeeeee;
}

.blocktext p {
    margin-top: 0px;
    font-size: 11px;
}
.nav-corporate .banner-block-tertiary {
    top: 54px;
}
.block-footer__main-nav-column-heading {
    display: block;
    font-weight: 600;
    border-bottom: 1px solid;
    font-size: 16px;
}
.container-fluid,
.block-footer__inner.container {
    max-width: 1240px;
}

.home-page-blend-structure-starts .second-blend-area .second-blend-full-section,
.home-page-blend-structure-starts .fourth-blend-area .fourth-blend-full-section,
#page.container-fluid{
    max-width: 1240px !important;
}

.nav.user-pro a, 
.nav.user-pro a.newreg, 
.nav.user-pro a.border-right {
    font-size: 14px;
}

@media (max-width: 1100px){
.nav-corporate > ul li + li {
    margin-left: 0.5em;
}
}

.mobileicons a.singinprovider {
    text-transform: initial !important;
    color: #2e2e2e;
    font-size: 14px;
    padding: 0 !important;
}

.sr-only.sr-only-focusable{
    display: none;
}
</style>