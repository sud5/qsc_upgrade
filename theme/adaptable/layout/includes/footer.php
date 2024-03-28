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
 * Version details
 *
 * @package    theme_adaptable
 * @copyright 2015 Jeremy Hopkins (Coventry University)
 * @copyright 2015-2017 Fernando Acedo (3-bits.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

// Load messages / notifications.
echo $OUTPUT->standard_after_main_region_html();
?>

<footer id="page-footer">

<?php
echo '<div id="course-footer">'.$OUTPUT->course_footer().'</div>';

if ($PAGE->theme->settings->showfooterblocks) {
    echo $OUTPUT->get_footer_blocks();
}

if ($PAGE->theme->settings->hidefootersocial == 1) {
    $socialicons = $OUTPUT->socialicons();
    if (!empty($socialicons)) {
        echo '<div class="container">';
        echo '<div class="row">';
        echo '<div class="col-12 pagination-centered socialicons">';
        echo $socialicons;
        echo '</div></div></div>';
    }
}
?>
    
                <?php
                $footnote = $OUTPUT->get_setting('footnote', 'format_html');
                if (!empty($footnote)) {
                    $footnoteclass .= 'footnote text-center';
                    echo '<div class="'.$footnoteclass.'"><footer class="block-footer">
                    <div class="block-footer__inner container-fluid">
            <div class="block-footer__row">
                <nav class="block-footer__social-nav" aria-label="Social Media Links">
                    <ul class="block-footer__social-nav-list">
                        <li> <a href="https://www.facebook.com/QSCAudioProducts" class="block-footer__social-link" target="_blank" rel="noopener noreferrer"> <span class="screen-reader-text">QSC on Facebook</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.25 32" width="13.25" height="32" role="img">
                                    Facebook logo
                                    <path d="M3.25,29.19245H8.64v-13.5h3.77l.4-4.53H8.64v-2.58c0-1.07.22-1.49,1.25-1.49h2.92v-4.68H9.07c-4,0-5.82,1.76-5.82,5.15v3.6H.45v4.58h2.8Z"></path>
                                </svg>
                            </a> </li>
                        <li> <a href="https://www.linkedin.com/company/qsc-audio-products-llc" class="block-footer__social-link" target="_blank" rel="noopener noreferrer"> <span class="screen-reader-text">QSC on LinkedIn</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.03 32" width="31.03" height="32" role="img">
                                    LinkedIn logo
                                    <rect x="2.07" y="10.55245" width="5.78" height="18.64"></rect>
                                    <path d="M4.93,8.11245A3.44,3.44,0,1,0,1.51006,4.65251L1.51,4.67245a3.43,3.43,0,0,0,3.42,3.44h0"></path>
                                    <path d="M17.09,19.40245c0-2.62,1.21-4.18,3.53-4.18,2.12,0,3.14,1.5,3.14,4.18v9.78h5.76v-11.8c0-5-2.84-7.4-6.8-7.4a6.52,6.52,0,0,0-5.63,3.08v-2.51H11.54v18.63h5.55Z"></path>
                                </svg>
                            </a> </li>
                        <li> <a href="https://twitter.com/qsc" class="block-footer__social-link" target="_blank" rel="noopener noreferrer"> <span class="screen-reader-text">QSC on Twitter</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32.92 32" width="32.92" height="32" role="img">
                                    Twitter logo
                                    <path d="M.6,26.73276a18.41,18.41,0,0,0,10,2.91c12.01,0,18.82-10.21,18.4-19.31a13.07007,13.07007,0,0,0,3.23-3.36,12.91,12.91,0,0,1-3.72,1,6.55,6.55,0,0,0,2.86-3.54,12.77,12.77,0,0,1-4.12,1.58,6.49,6.49,0,0,0-11.06,5.89,18.4,18.4,0,0,1-13.38-6.78,6.51,6.51,0,0,0,2,8.67,6.46,6.46,0,0,1-2.94-.82,6.5,6.5,0,0,0,5.21,6.46,6.54,6.54,0,0,1-2.93.11,6.49,6.49,0,0,0,6.06,4.51,13.06,13.06,0,0,1-9.61,2.68"></path>
                                </svg>
                            </a> </li>
                        <li> <a href="https://www.youtube.com/user/QSCAudioProducts" class="block-footer__social-link" target="_blank" rel="noopener noreferrer"> <span class="screen-reader-text">QSC on YouTube</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34.28 32" width="34.28" height="32" role="img">
                                    YouTube logo
                                    <path d="M14.13,21.98745v-11.55l8.76,5.78Zm18.41-11.33a4.59,4.59,0,0,0-4.54-4.59H6.45a4.59,4.59,0,0,0-4.59,4.59v12.3a4.59,4.59,0,0,0,4.59,4.58H28a4.59,4.59,0,0,0,4.59-4.58Z"></path>
                                </svg>
                            </a> </li>
                        <li> <a href="https://instagram.com/qscaudio" class="block-footer__social-link" target="_blank" rel="noopener noreferrer"> <span class="screen-reader-text">QSC on Instagram</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.53 32" width="29.53" height="32" role="img">
                                    Instagram logo
                                    <path d="M14.83,2.79745c-3.83,0-4.28,0-5.77.08a10.21022,10.21022,0,0,0-3.4.66,6.75,6.75,0,0,0-2.48,1.61,6.91013,6.91013,0,0,0-1.62,2.48,10.49983,10.49983,0,0,0-.65,3.4c-.07,1.49-.08,1.97-.08,5.77s0,4.28.08,5.78a10.49978,10.49978,0,0,0,.65,3.4,6.90991,6.90991,0,0,0,1.62,2.48,6.90987,6.90987,0,0,0,2.48,1.62,10.27,10.27,0,0,0,3.4.64c1.49.07,2,.09,5.77.09s4.28,0,5.78-.09a10.27,10.27,0,0,0,3.4-.64,6.90987,6.90987,0,0,0,2.48-1.62,6.75,6.75,0,0,0,1.61-2.48,9.99983,9.99983,0,0,0,.65-3.4c.07-1.5.09-2,.09-5.78s0-4.28-.09-5.77a9.99988,9.99988,0,0,0-.65-3.4,6.75013,6.75013,0,0,0-1.61-2.48,6.75005,6.75005,0,0,0-2.49-1.61,10.21022,10.21022,0,0,0-3.4-.66c-1.49-.08-1.96-.08-5.77-.08m0,2.52c3.74,0,4.19,0,5.66.08a7.63018,7.63018,0,0,1,2.6.49,4.13,4.13,0,0,1,1.61,1,4.21,4.21,0,0,1,1.05,1.6,7.84023,7.84023,0,0,1,.48,2.61c.07,1.47.08,1.91.08,5.65s0,4.19-.08,5.66a7.7,7.7,0,0,1-.48,2.6,4.59,4.59,0,0,1-2.66,2.66,7.6998,7.6998,0,0,1-2.6.48c-1.47.07-1.92.09-5.66.09s-4.18,0-5.66-.09a7.81957,7.81957,0,0,1-2.6-.48,4.42986,4.42986,0,0,1-1.61-1,4.34,4.34,0,0,1-1-1.62,7.62994,7.62994,0,0,1-.49-2.6c-.06-1.47-.08-1.92-.08-5.66s0-4.18.08-5.65a7.76,7.76,0,0,1,.49-2.61,4.17962,4.17962,0,0,1,2.61-2.6,7.75017,7.75017,0,0,1,2.6-.49c1.48-.06,1.92-.08,5.66-.08"></path>
                                    <path d="M14.83,21.46745a4.67,4.67,0,1,1,4.67-4.67h0a4.66,4.66,0,0,1-4.66,4.66l-.01,0m0-11.86a7.19,7.19,0,1,0,7.17,7.21l0-.01a7.19,7.19,0,0,0-7.18-7.2l-.01,0"></path>
                                    <path d="M24,9.32745a1.68,1.68,0,1,1-1.68-1.68,1.68,1.68,0,0,1,1.68,1.68"></path>
                                </svg>
                            </a> </li>
                        <li>
                            <a href="https://www.glassdoor.com/Overview/Working-at-QSC-EI_IE14255.11,14.htm" class="block-footer__social-link" target="_blank" rel="noopener noreferrer">
                                <span class="screen-reader-text">QSC on Glassdoor</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.35 32" width="22.35" height="32" role="img">
                                    Glassdoor logo
                                    <path d="M17,27.49745H0a4.25,4.25,0,0,0,4.23,4.27l.02005,0H17a4.26,4.26,0,0,0,4.24-4.27v-17.56a.16.16,0,0,0-.15-.16H17.16a.16.16,0,0,0-.16.16Zm0-25.66a4.26,4.26,0,0,1,4.24,4.27991v.00009h-17v17.56a.16.16,0,0,1-.14.16H.16a.16.16,0,0,1-.16-.16v-17.56A4.25,4.25,0,0,1,4.21989,1.83756Q4.235,1.83746,4.25,1.83745Z"></path>
                                </svg>
                            </a></li>
                    </ul>
                </nav>
            </div>
            <div class="block-footer__row"> <a href="https://lnd.qsc.com/SubscribetoQSC.html" class="block-footer__newsletter-link" target="_blank" rel="noopener norefferer"> <span>Sign up for our newsletter</span>
        <svg viewBox="0 0 246.11 162.39" width="64" height="42.29" role="img">
            Envelope
            <path d="M15.82.75C18.43.5 21 0 23.64 0Q122.85 0 222 .1c4.26 0 8.51 1.47 12.77 2.25l-.06 1.47q-12.85 10.69-25.7 21.39Q171 56.82 132.92 88.4c-7.58 6.29-13 6.25-20.31-.23Q65.67 46.59 18.75 5c-1.1-1-2.26-1.88-3.4-2.82zm-5.58 157.4l86.54-70.92c8.37 5.93 14.44 15.23 26.15 15.37s17.91-9.32 26.95-14.83l85.94 70.31-.07 1.7c-4.74.86-9.47 2.43-14.22 2.45-43.3.2-86.61.12-129.91.12-22.65 0-45.31.13-68-.12-4.44 0-8.87-1.59-13.3-2.45zM2 151.81c-.8-5.19-1.9-8.88-1.91-12.58Q-.045 81.285 0 23.35A22.75 22.75 0 016.44 6.88l83.14 73.8zM243.26 9.82c1.09 4.68 2.7 8.46 2.71 12.24q.28 58.95 0 117.88c0 3.35-1.28 6.69-2.19 11.15L157 81.18z"></path>
        </svg>
    </a> </div>
                    '.$footnote.'
                    <div class="block-footer__row">
                <a class="block-footer__logo-link" href="/">
                    <span class="screen-reader-text">QSC Audio Products Homepage</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5241.6 1049.6" width="239.71" height="48" role="img">
                        QSC logo
                        <path d="M1638.4 96l19.2 38.4c6.4 12.8 6.4 32 6.4 51.2v576c0 19.2 0 38.4-6.4 51.2l-19.2 38.4c-6.4 12.8-12.8 19.2-25.6 32-12.8 6.4-19.2 19.2-32 25.6l-38.4 19.2c-12.8 6.4-32 6.4-44.8 12.8-12.8 0-25.6 6.4-38.4 6.4h-160v89.6c0 12.8-12.8 12.8-12.8 12.8h-288s-12.8 0-12.8-12.8v-89.6H268.8c-32 0-51.2-6.4-70.4-12.8-19.2-6.4-38.4-12.8-57.6-25.6-12.8-12.8-32-25.6-44.8-38.4s-25.6-32-32-44.8c-6.4-19.2-19.2-38.4-25.6-57.6-6.4-19.2-12.8-38.4-19.2-64-6.4-19.2-6.4-44.8-12.8-70.4C6.4 608 0 582.4 0 563.2V460.8 384c0-25.6 6.4-51.2 6.4-70.4 6.4-25.6 6.4-44.8 12.8-70.4 6.4-19.2 12.8-44.8 19.2-64 6.4-19.2 12.8-38.4 25.6-57.6s25.6-32 32-44.8c12.8-12.8 25.6-25.6 44.8-38.4s32-19.2 57.6-25.6C217.6 6.4 236.8 0 256 0h1190.4c19.2 0 38.4 0 51.2 6.4 19.2 0 32 6.4 44.8 12.8l38.4 19.2c12.8 6.4 25.6 12.8 32 25.6 12.8 6.4 19.2 19.2 25.6 32zm-217.6 128c0-25.6-19.2-44.8-44.8-44.8H441.6c-25.6-6.4-51.2 19.2-51.2 44.8v460.8c0 25.6 19.2 44.8 44.8 44.8h537.6v-96s0-12.8 12.8-12.8H1280s12.8 0 12.8 12.8v96h83.2c25.6 0 44.8-19.2 44.8-44.8V224zm1926.4 256c0 19.2 6.4 32 12.8 51.2s6.4 32 6.4 51.2v115.2c0 19.2-6.4 38.4-6.4 51.2-6.4 19.2-6.4 32-12.8 44.8L3328 832c-6.4 12.8-19.2 25.6-32 32-12.8 6.4-25.6 19.2-38.4 25.6s-32 12.8-44.8 12.8c-19.2 6.4-38.4 6.4-57.6 6.4H1830.4c-19.2 0-38.4-19.2-38.4-38.4V761.6c0-19.2 19.2-38.4 38.4-38.4H2912c12.8 0 25.6-12.8 25.6-25.6v-128c0-12.8-12.8-25.6-25.6-25.6h-915.2c-19.2 0-38.4 0-57.6-6.4-19.2-6.4-32-6.4-44.8-12.8s-25.6-12.8-38.4-25.6c-12.8-6.4-19.2-19.2-32-32l-19.2-38.4c-6.4-12.8-12.8-32-12.8-44.8-6.4-19.2-6.4-32-6.4-51.2-6.4-19.2-6.4-38.4-6.4-57.6v-57.6c0-19.2 6.4-38.4 6.4-51.2 6.4-19.2 6.4-32 12.8-44.8l19.2-38.4c6.4-12.8 19.2-25.6 32-32 12.8-6.4 25.6-19.2 38.4-25.6s32-12.8 44.8-12.8C1952 0 1971.2 0 1996.8 0h1324.8c19.2 0 38.4 19.2 38.4 38.4v115.2c0 19.2-19.2 38.4-38.4 38.4H2240c-12.8 0-25.6 12.8-25.6 25.6v121.6c0 12.8 12.8 25.6 25.6 25.6h915.2c19.2 0 38.4 0 57.6 6.4 19.2 6.4 32 6.4 44.8 12.8s25.6 12.8 38.4 25.6c12.8 6.4 19.2 19.2 32 32l19.2 38.4zM5024 729.6c12.8 0 25.6 12.8 25.6 25.6v128c0 12.8-12.8 25.6-25.6 25.6H3744c-25.6 0-51.2-6.4-70.4-6.4-19.2-6.4-38.4-12.8-57.6-25.6-19.2-12.8-32-25.6-44.8-38.4s-25.6-32-32-44.8c-6.4-19.2-19.2-38.4-25.6-57.6s-12.8-38.4-19.2-64c-6.4-12.8-6.4-38.4-12.8-64 0-25.6-6.4-51.2-6.4-76.8v-70.4V384c0-25.6 6.4-51.2 6.4-76.8s6.4-44.8 12.8-70.4c6.4-19.2 12.8-44.8 19.2-64s12.8-38.4 25.6-57.6c6.4-19.2 19.2-32 32-44.8 12.8-12.8 25.6-25.6 44.8-38.4s32-19.2 57.6-25.6c12.8 0 38.4-6.4 64-6.4h1280c12.8 0 25.6 12.8 25.6 25.6v128c0 12.8-12.8 25.6-25.6 25.6H3904c-12.8 0-25.6 12.8-25.6 25.6V704c0 12.8 12.8 25.6 25.6 25.6h1120zm179.2-640c6.4 12.8 6.4 19.2 6.4 19.2h-19.2c0-6.4-6.4-12.8-6.4-19.2s-6.4-12.8-12.8-12.8h-6.4v25.6h-19.2V32h25.6c12.8 0 19.2 6.4 25.6 6.4s6.4 6.4 6.4 12.8c0 12.8-6.4 12.8-12.8 19.2 6.4 0 6.4 6.4 12.8 19.2zm-19.2-32c0-6.4 0-12.8-12.8-12.8h-6.4V64h6.4c6.4 0 12.8 0 12.8-6.4zm57.6 12.8c0 38.4-32 70.4-70.4 70.4s-70.4-32-70.4-70.4 32-70.4 70.4-70.4 70.4 32 70.4 70.4zm-19.2 0c0-32-19.2-57.6-51.2-57.6s-57.6 25.6-57.6 57.6 25.6 57.6 57.6 57.6 57.6-25.6 51.2-57.6z"></path>
                    </svg>
                </a>
            </div>
            <div class="block-footer__row">
                <div class="block-footer__colophon"> <small>© 2021 QSC, LLC. All Rights Reserved.</small> <small>QSC LLC\'s trademarks include, but are not limited to, QSC®, Q-SYS®, QSC CINEMA®, Q-SYS REFLECT™, QSC SYSTEM NAVIGATOR™, FLEXAMP®, FLEXIBLE AMPLIFIER SUMMING TECHNOLOGY™, NOW YOU HEAR US®, TOUCHMIX®, POWERLIGHT®, some of which are registered in the U.S. and/or other countries.</small> <small>For a more detailed listing of QSC, LLC\'s trademarks please visit <a href="https://www.qsc.com/trademarks/">https://www.qsc.com/trademarks/</a>.</small> </div>
            </div>
        </div>
    </footer>
</div>
                    </footer></div>';
                }
                if ($PAGE->theme->settings->moodledocs) {
                    echo '<div class="col-md-4 my-md-0 my-2 helplink">';
                    echo $OUTPUT->page_doc_link();
                    echo '</div>';
                }
                ?>
                <div class="col-md-4 my-md-0 my-2">
                    <?php echo $OUTPUT->standard_footer_html(); ?>
                </div>
            
</footer>

<div id="back-to-top"><i class="fa fa-angle-up "></i></div>

<?php
// If admin settings page, show template for floating save / discard buttons.
$templatecontext = [
    'topmargin'   => ($PAGE->theme->settings->stickynavbar ? '35px' : '0'),
    'savetext'    => get_string('savebuttontext', 'theme_adaptable'),
    'discardtext' => get_string('discardbuttontext', 'theme_adaptable')
];
if (strstr($PAGE->pagetype, 'admin-setting')) {
    if ($PAGE->theme->settings->enablesavecanceloverlay) {
        echo $OUTPUT->render_from_template('theme_adaptable/savediscard', $templatecontext);
    }
}
echo '</div>'; // End #page.
echo '</div>'; // End #page-wrapper.
echo $OUTPUT->standard_end_of_body_html();
echo $PAGE->theme->settings->jssection;

// Conditional javascript based on a user profile field.
if (!empty($PAGE->theme->settings->jssectionrestrictedprofilefield)) {
    // Get custom profile field setting. (e.g. faculty=fbl).
    $fields = explode('=', $PAGE->theme->settings->jssectionrestrictedprofilefield);
    $ftype = $fields[0];
    $setvalue = $fields[1];

    // Get user profile field (if it exists).
    require_once($CFG->dirroot.'/user/profile/lib.php');
    require_once($CFG->dirroot.'/user/lib.php');
    profile_load_data($USER);
    $ftype = "profile_field_$ftype";
    if (isset($USER->$ftype)) {
        if ($USER->$ftype == $setvalue) {
            // Match between user profile field value and value in setting.

            if (!empty($PAGE->theme->settings->jssectionrestricteddashboardonly)) {

                // If this is set to restrict to dashboard only, check if we are on dashboard page.
                if ($PAGE->has_set_url()) {
                    $url = $PAGE->url;
                } else if ($ME !== null) {
                    $url = new moodle_url(str_ireplace('/my/', '/', $ME));
                }

                // In practice, $url should always be valid.
                if ($url !== null) {
                    // Check if this is the dashboard page.
                    if (strstr ($url->raw_out(), '/my/')) {
                        echo $PAGE->theme->settings->jssectionrestricted;
                    }
                }
            } else {
                echo $PAGE->theme->settings->jssectionrestricted;
            }
        }
    }
}
echo $OUTPUT->get_all_tracking_methods();
// echo $OUTPUT->dockpanel();
?>
<script type="text/javascript">
    M.util.js_pending('theme_boost/loader');
        require(['theme_boost/loader'], function() {
        M.util.js_complete('theme_boost/loader');
    });
</script>
</body>
</html>
