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
 * local thumbnail
 *
 * @package     local_thumbnail
 * @author      Sudhanshu Gupta
 * @copyright   Sudhanshu Gupta<sudhanshug5@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

//echo dirname(__FILE__).'/classes/image_processor.php';die();
require_once(dirname(__FILE__) . '/classes/image_processor.php');

/**
 *
 * Extend local_thumbnail navigation
 *
 * @param global_navigation $nav
 */
function local_thumbnail_extends_navigation(global_navigation $nav) {
    return local_thumbnail_extend_navigation($nav);
}

/**
 *
 * Extend navigation to show the local_thumbnail in the navigation block
 *
 * @param global_navigation $nav
 */
function local_thumbnail_extend_navigation(global_navigation $nav) {
    global $CFG, $DB;
    $context = context_system::instance();
    $pluginname = get_string('pluginname', 'local_thumbnail');
}

/**
 * Options to pass to the filepicker when adding items to a gallery.
 *
 * @param \mod_mediagallery\gallery $gallery
 * @return array
 */
function thumbnail_filepicker_options() {
    $pickeroptions = array(
        'maxbytes' => 1234456,
        'maxfiles' => 1,
        'subdirs' => false,
        'accepted_types' => '*'
    );
    return $pickeroptions;
}

/**
 * Serves any files associated with the plugin (e.g. tile photos).
 * For explanation see https://docs.moodle.org/dev/File_API
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function local_thumbnail_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $context = context_system::instance();
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }

    if (!($filearea == 'book')) {
        debugging('Invalid file area ' . $filearea);
        send_file_not_found();
    }

    // Make sure the user is logged in and has access to the course.
//    require_login();
    if ($filearea == 'book') {
        $fileapiparams = file_api_params();
    }

    $bookid = (int) $args[0];
    $filepath = $args[1];
    $filename = $args[2];

    $fs = get_file_storage();
    // $bookid = 17;
    $filepath = '/book/';
    // $filename = 'default_course.jpg';
    // echo $context->id;
    // echo $fileapiparams['component'];
    // echo $filearea;
    // echo $bookid;
    // echo $filepath;
    // echo $filename;
    $file = $fs->get_file($context->id, $fileapiparams['component'], $filearea, $bookid, $filepath, $filename);
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}

function file_api_params() {
    return array(
        'component' => 'local_thumbnail',
        'filearea' => 'book',
        'filepath' => '/book/',
    );
}

function set_file_from_stored_file($sourcefile, $newfilename, $bookid) {

    $context = context_system::instance();
    if ($sourcefile) {
        $sourceimageinfo = $sourcefile->get_imageinfo();
        $newwidth = 1500; //self::get_max_image_width();

        $newfile = image_processor::adjust_and_copy_file(
                        $sourcefile,
                        $newfilename,
                        $context,
                        $bookid,
                        $newwidth,
                        $sourceimageinfo['height'] * $newwidth / $sourceimageinfo['width']
        );

        if ($newfile) {
            return $newfile;
        } else {
            debugging('Failed to set file from details - filename ' . $newfilename);

            // Restore the original file name of the original file.
            debugging("New file could not be created");
            debugging($newfile);
            // $this->get_file()->rename(self::file_api_params()['filepath'], $this->filename);
            return false;
        }
    } else {
        debugging('Failed to set file from details - filename ' . $newfilename);
        return false;
    }
}

function get_image_url($id) {

    $config = file_api_params();

    $fs = get_file_storage();
    if (!$files = $fs->get_area_files(context_system::instance()->id, $config['component'], $config['filearea'], $id, 'id DESC', false)) {
        return false;
    }
    foreach ($files as $fileo) {
        $fileurl = moodle_url::make_pluginfile_url($fileo->get_contextid(), $fileo->get_component(), $fileo->get_filearea(), $fileo->get_itemid(), $fileo->get_filepath(), $fileo->get_filename());
        return $fileurl;
    }
}

function file_handling($newfilename, $filename, $mform, $context, $insertid) {
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'local_thumbnail', 'book', $insertid);
    $fmoptions = thumbnail_filepicker_options();
    $fileapiparams = file_api_params();
    $tempfile = $mform->save_stored_file(
            $filename,
            $context->id,
            $fileapiparams['component'],
            $fileapiparams['filearea'],
            $insertid,
            $fileapiparams['filepath'],
            $newfilename,
            true
    );

    $newfile = set_file_from_stored_file($tempfile, $newfilename, $insertid);
}
