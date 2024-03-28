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
 * @copyright  2015-2019 Jeremy Hopkins (Coventry University)
 * @copyright  2015-2019 Fernando Acedo (3-bits.com)
 * @copyright  2017-2019 Manoj Solanki (Coventry University)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

// Let's go to include first the common header file.
require_once(dirname(__FILE__) . '/includes/header.php');

$PAGE->requires->js_init_code('
require([\'jquery\'], function($){
    $("img#action-menu-10-menubar").attr("src","/theme/adaptable/pix/edit_menu.svg");    
});
', true);

// If page is Grader report don't show side post.
if (($PAGE->pagetype == "grade-report-grader-index") ||
    ($PAGE->bodyid == "page-grade-report-grader-index")) {
    $left = true;
    $hassidepost = false;
} else {
    $left = $PAGE->theme->settings->blockside;
    $hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
}
$regions = theme_adaptable_grid($left, $hassidepost);
if ($PAGE->pagetype == "mod-book-view") {
    $regions['content'] = "col-12";
}


if(!is_siteadmin($USER)){
    ?>
    <style type="text/css">
section .header {
    display: none;
}
</style>
<?php
}
?>
<link href="/theme/adaptable/style/frontpage.css" type="text/css" rel="stylesheet">
<?php
// And now we go to create the main layout.
$left = $PAGE->theme->settings->blockside;
if (($PAGE->theme->settings->frontpageuserblocksenabled) || (is_siteadmin($USER))) {
    $hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
} else {
    $hassidepost = false;
}

$regions = theme_adaptable_grid($left, $hassidepost);

// Let's include the images slider if enabled.
if (!empty($PAGE->theme->settings->sliderenabled)) {
    echo $OUTPUT->get_frontpage_slider();
}

// And let's show Infobox 1 if enabled.
if (!empty($PAGE->theme->settings->infobox)) {
    if (!empty($PAGE->theme->settings->infoboxfullscreen)) {
        echo '<div id="theinfo">';
    } else {
        echo '<div id="theinfo" class="container">';
    }
    echo '<div class="row">';
    echo $OUTPUT->get_setting('infobox', 'format_html');
    echo '</div>';
    echo '</div>';
}

// If Marketing Blocks are enabled then let's show them.
if (!empty($PAGE->theme->settings->frontpagemarketenabled)) {
    echo $OUTPUT->get_marketing_blocks();
}

if (!empty($PAGE->theme->settings->frontpageblocksenabled)) { ?>
    <div id="frontblockregion" class="container">
        <div class="row">
            <?php echo $OUTPUT->get_block_regions(); ?>
        </div>
    </div>
    <?php
}

// And finally let's show the Infobox 2 if enabled.
if (!empty($PAGE->theme->settings->infobox2)) {
    if (!empty($PAGE->theme->settings->infoboxfullscreen)) {
        echo '<div id="theinfo2">';
    } else {
        echo '<div id="theinfo2" class="container">';
    }
    echo '<div class="row">';
    echo $OUTPUT->get_setting('infobox2', 'format_html');
    echo '</div>';
    echo '</div>';
}

// The main content goes here.
?>



<div class="container outercont" style="padding-top: 45px;">
    <div id="page-content" class="row<?php echo $regions['direction'];?>">
        <div id="page-navbar" class="col-12">
            <nav class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></nav>
        </div>

        <div id="region-main-box" class="<?php echo $regions['content'];?>">
            <section id="region-main">
            <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            echo $OUTPUT->course_content_footer();
            ?>
            </section>
        </div>
        <?php
        if ($hassidepost) {
            echo $OUTPUT->blocks('side-post', $regions['blocks'].' d-print-none ');
        }
        ?>
    </div>

<?php

// Let's show the hidden blocks region ONLY for administrators.
if (is_siteadmin()) {
?>
    <div class="hidden-blocks">
        <div class="row">

        <?php
        if (!empty($PAGE->theme->settings->coursepageblocksliderenabled) ) {
            echo $OUTPUT->get_block_regions('customrowsetting', 'news-slider-', '12-0-0-0');
        }

        if (!empty($PAGE->theme->settings->coursepageblockactivitybottomenabled)) {
            echo $OUTPUT->get_block_regions('customrowsetting', 'course-section-', '12-0-0-0');
        }

        if (!empty($PAGE->theme->settings->tabbedlayoutcoursepage)) {
            echo $OUTPUT->get_block_regions('customrowsetting', 'course-tab-one-', '12-0-0-0');
            echo $OUTPUT->get_block_regions('customrowsetting', 'course-tab-two-', '12-0-0-0');
        }

        if (!empty($PAGE->theme->settings->tabbedlayoutdashboard)) {
            echo $OUTPUT->get_block_regions('customrowsetting', 'my-tab-one-', '12-0-0-0');
            echo $OUTPUT->get_block_regions('customrowsetting', 'my-tab-two-', '12-0-0-0');
        }

        ?>

          <h3><?php echo get_string('frnt-footer', 'theme_adaptable') ?></h3>
            <?php
            echo $OUTPUT->blocks('frnt-footer', 'col-10');
            ?>
        </div>
    </div>

    <?php
}else{
    ?>
<style type="text/css">.outercont {
    display: none;
}</style>
    <?php
}
?>
</div>
<?php
// And to finish, we include the common footer file.
require_once(dirname(__FILE__) . '/includes/footer.php');
?>
<script type="text/javascript">
    $("#inst367154").addClass("container-fluid");
    $(".title div ").css("display","inline-block");
    $(".title div ").css("width","100%");
</script>

<script type="text/javascript">$('aside').parent('div').removeClass('container row my-1 col-md-12 block-region-front block-region block_html block mb-3 content block_action notitle no-overflow d-none d-lg-block');</script>

<script type="text/javascript">$('div').removeClass('container row block-region-front block-region block_html block mb-3 content block_action notitle no-overflow d-none d-lg-block');
$('section').removeClass('container row block-region-front block-region block_html block mb-3 content block_action notitle no-overflow');

$('.h-100').addClass('row');

$(document).ready(function(){
//$('#block-region-frnt-market-d').parent().closest("div").("display","inline-flex");
$('#block-region-frnt-market-d').css("background","#1f1f1f none repeat scroll 0 0 !important");
});
$('#frontpage-available-course-list').hide();
$('#block-region-frnt-market-d').css({
    "background": "#1f1f1f none repeat scroll 0 0 !important",
    "max-width": "100%",
    "width": "100% !important",
    "padding": "0 !important"
});
</script>
<style type="text/css">
    
    #block-region-frnt-market-d {
    background: #1f1f1f !important;
    max-width: 100%;
    width: 100% !important;
    padding: 0 !important;
    display: flex;
    padding-left: 15px !important;
    justify-content: center;
    white-space: inherit;
}

#frontblockregion {
    background-color: transparent;
    margin-bottom: 0;
    margin-top: 0;
}
.moodle-actionmenu{
    position: relative;
}

#region-main-box{display: none;}

@media (max-width: 767px){
.third-blend-all-six-box .blend-box-one p {
    font-size: 16px;
}
.fourth-blend-all-six-box p, .third-blend-all-six-box .blend-box-one p {
    font-size: 16px;
    line-height: inherit;
}

#page-footer {
 margin: 0 !important;
}

}

@media (max-width: 570px){
.fourth-blend-all-six-box p {
    font-size: 16px;
}
}
</style>

<?php 
if(is_siteadmin($USER)){
    ?>
    <script type="text/javascript">
        $('#inst367156').removeClass('hidden');
         $('#inst5').removeClass('hidden');


          $(".toggle-display img").attr('src','/theme/adaptable/pix/edit_menu.svg');
         $(".editing_move").html('');
         
    </script>
    
    <?php

}else{
    ?>
    <script type="text/javascript">
        $("#region-main-box").removeClass("col-9");
        $("#region-main-box").addClass("col-12");
     </script>
    <?php
}
?>