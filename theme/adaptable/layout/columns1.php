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
 * @copyright  2015-2016 Jeremy Hopkins (Coventry University)
 * @copyright  2015-2016 Fernando Acedo (3-bits.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

// Include header.
require_once(dirname(__FILE__) . '/includes/header.php');

?>

<div class="container outercont">
    <?php
        echo $OUTPUT->page_navbar();
    ?>
    <div id="page-content" class="row">
        <div id="region-main-box" class="<?php echo $regions['content'];?>">
            <section id="region-main">
            <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            echo $OUTPUT->activity_navigation();
            echo $OUTPUT->course_content_footer();
            ?>
            </section>
        </div>
    </div>
</div>

<?php
if (empty($PAGE->layout_options['nofooter'])) {
    // Include footer.
    require_once(dirname(__FILE__) . '/includes/footer.php');
} else {
    require_once(dirname(__FILE__) . '/includes/nofooter.php');
}
?>

<?php
if(!is_siteadmin($USER)){
?>
<script type="text/javascript">
    $("#region-main-box").removeClass("col-9");
    $("#region-main-box").addClass("col-12");
</script>
<?php 
} 
?>