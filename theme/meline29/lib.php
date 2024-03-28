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
 * @package   theme_meline29
 * @copyright 2014 Eduardo Ramos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Include the Awesome Font.
 */
function theme_meline29_set_fontwww($css) {
    global $CFG;
    $tag = '[[setting:fontwww]]';
	
    $theme = theme_config::load('meline29');
    if (!empty($theme->settings->bootstrapcdn)) {
        $css = str_replace($tag, '//netdna.bootstrapcdn.com/font-awesome/4.3.0/fonts/', $css);
    } else {
        //Prepare local URL without http(s) and domain, so some browsers load the font even with mixed content.
        $url = $CFG->wwwroot.'/theme/meline29/fonts/';
        $localurl = parse_url($url, PHP_URL_PATH);
        
        if(empty($localurl)){
            //Some error happened, at least try with the full URL
            $localurl = $url;
        }
        
        $css = str_replace($tag, $localurl, $css);
    }
    
    if (empty($theme->settings->themebgc)) {
        $themebgc = '#4d877f'; // default 

    } else {
        $themebgc = $theme->settings->themebgc;
    }
    
    $css = meline29_set_themebgc($css, $themebgc);
        
    // Set the page Custom menu color   
           
   if (empty($theme->settings->themehoverbgc)) {
        $themehoverbgc = '#e47508'; // default 

    } else {
        $themehoverbgc = $theme->settings->themehoverbgc;
    }
    
    $css = meline29_set_themehoverbgc($css, $themehoverbgc);
    
    return $css;
}

/**
 * Adds the logo to CSS.
 *
 * @param string $css The CSS.
 * @param string $logo The URL of the logo.
 * @return string The parsed CSS
 */
 
function meline29_set_themebgc($css, $themebgc) {
	
    $tag = '[[setting:themebgc]]';
    $css = str_replace($tag, $themebgc, $css);
    
    return $css;
}

function meline29_set_themehoverbgc($css, $themehoverbgc) {
	
    $tag = '[[setting:themehoverbgc]]';
    $css = str_replace($tag, $themehoverbgc, $css);    
    return $css;
}
 
function theme_meline29_set_logo($css, $logo) {
    $tag = '[[setting:logo]]';
    $replacement = $logo;
    if (is_null($replacement)) {
        $replacement = '';
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_blockoneimage($css, $blockoneimage) {
    $tag = '[[setting:blockoneimage]]';
    if (!empty($pagebackground)) {
        $replacement = "blockoneimage: url(\"$blockoneimage\")";
    }else{
        $replacement = "img1";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_get_html_for_settings(renderer_base $output, moodle_page $page, $lang_translate = NULL) {
    global $CFG;
    $return = new stdClass;

    if (!empty($page->theme->settings->logo)) {
        $return->heading = html_writer::link($CFG->wwwroot, '', array('title' => get_string('home'), 'class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->{'footnote'.$lang_translate})) {
        $return->footnote = '<div class="footnote text-center">'.$page->theme->settings->{'footnote'.$lang_translate}.'</div>';
    }

    return $return;
}

function theme_meline29_set_blocktwoimage($css, $blocktwoimage) {
    $tag = '[[setting:blocktwoimage]';
    if (!empty($blocktwoimage)) {
        $replacement = "blocktwoimage: url(\"$blocktwoimage\")";
    }else{
        $replacement = "img2";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_blockthreeimage($css, $blockthreeimage) {
    $tag = '[[setting:blockthreeimage]';
    if (!empty($blockthreeimage)) {
        $replacement = "blockthreeimage: url(\"$blockthreeimage\")";
    }else{
        $replacement = "img3";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_blockfourimage($css, $blockfourimage) {
    $tag = '[[setting:blockfourimage]';
    if (!empty($blockfourimage)) {
        $replacement = "blockfourimage: url(\"$blockfourimage\")";
    }else{
        $replacement = "img4";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_blockfiveimage($css, $blockfiveimage) {
    $tag = '[[setting:blockfiveimage]';
    if (!empty($blockfiveimage)) {
        $replacement = "blockfiveimage: url(\"$blockfiveimage\")";
    }else{
        $replacement = "img5";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_blocksiximage($css, $blocksiximage) {
    $tag = '[[setting:blocksiximage]';
    if (!empty($blocksiximage)) {
        $replacement = "blocksiximage: url(\"$blocksiximage\")";
    }else{
        $replacement = "img6";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_featureoneimage($css, $featureoneimage) {
    $tag = '[[setting:featureoneimage]';
    if (!empty($featureoneimage)) {
        $replacement = "featureoneimage: url(\"$featureoneimage\")";
    }else{
        $replacement = "1";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_featuretwoimage($css, $featuretwoimage) {
    $tag = '[[setting:featuretwoimage]';
    if (!empty($featuretwoimage)) {
        $replacement = "featuretwoimage: url(\"$featuretwoimage\")";
    }else{
        $replacement = "2";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_featurethreeimage($css, $featurethreeimage) {
    $tag = '[[setting:featurethreeimage]';
    if (!empty($featurethreeimage)) {
        $replacement = "featurethreeimage: url(\"$featurethreeimage\")";
    }else{
        $replacement = "3";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_featurefourimage($css, $featurefourimage) {
    $tag = '[[setting:featurefourimage]';
    if (!empty($featurefourimage)) {
        $replacement = "featurefourimage: url(\"$featurefourimage\")";
    }else{
        $replacement = "4";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_testimonialoneuserpic($css, $testimonialoneuserpic) {
    $tag = '[[setting:testimonialoneuserpic]';
    if (!empty($testimonialoneuserpic)) {
        $replacement = "testimonialoneuserpic: url(\"$testimonialoneuserpic\")";
    }else{
        $replacement = "t1";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_testimonialtwouserpic($css, $testimonialtwouserpic) {
    $tag = '[[setting:testimonialtwouserpic]';
    if (!empty($testimonialtwouserpic)) {
        $replacement = "testimonialtwouserpic: url(\"$testimonialtwouserpic\")";
    }else{
        $replacement = "t1";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_testimonialthreeuserpic($css, $testimonialthreeuserpic) {
    $tag = '[[setting:testimonialthreeuserpic]';
    if (!empty($testimonialthreeuserpic)) {
        $replacement = "testimonialthreeuserpic: url(\"$testimonialthreeuserpic\")";
    }else{
        $replacement = "t1";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_testimonialfouruserpic($css, $testimonialfouruserpic) {
    $tag = '[[setting:testimonialfouruserpic]';
    if (!empty($testimonialfouruserpic)) {
        $replacement = "testimonialfouruserpic: url(\"$testimonialfouruserpic\")";
    }else{
        $replacement = "t1";
    }
    return str_replace($tag, $replacement, $css);
}

function theme_meline29_set_slideshow_sizing($css, &$theme) {
    //Replace slideshow colors:
    $setting = 'slideshowsizingmode';

    $modecss = 'auto';
    if (isset($theme->settings->$setting)) {
        if ($theme->settings->$setting === 'height') {
            $modecss = 'auto 100%';
        } elseif ($theme->settings->$setting === 'width') {
            $modecss = '100% auto';
        }
    }
    $tag = "[[setting:$setting]]";

    return str_replace($tag, $modecss, $css);
}

function theme_meline29_set_slideshow_colors($css, &$theme) {
    //Replace slideshow colors:
    $defaults = array(
        'slideshowtitlecolor' => '#fff',
        'slideshowcaptioncolor' => '#fff',
        'slideshowarrowscolor' => '#fff'
    );
    
    foreach ($defaults as $setting => $default) {
        if(!empty($theme->settings->$setting)){
            $color = $theme->settings->$setting;
        }else{
            $color = $default;
        }
        
        $tag = "[[setting:$setting]]";
        
        $css = str_replace($tag, $color, $css);
    }
    
    return $css;
}

/**
 * Serves any files associated with the theme settings.
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
function theme_meline29_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('meline29');
        
        $valid_files = array(
            'logo',
            'footerimage',
            'blockoneimage',
            'blocktwoimage',
            'blockthreeimage',
            'blockfourimage',
            'blockfiveimage',
            'blocksiximage',
            'featureoneimage',
            'featuretwoimage',
            'featurethreeimage',
            'featurefourimage',
            'testimonialoneuserpic',
            'testimonialtwouserpic',
            'testimonialthreeuserpic',
            'testimonialfouruserpic',
            'iphoneicon',
            'iphoneretinaicon',
            'ipadicon',
            'ipadretinaicon'
        );

        if (in_array($filearea, $valid_files) || preg_match("/block+[a-z]+image/", $filearea) || preg_match("/feature+[a-z]+image/", $filearea) || preg_match("/testimonial+[a-z]+userpic/", $filearea) || preg_match("#slide\d+image#", $filearea) || preg_match("#marketing\d+image#", $filearea)) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
        
        // if (in_array($filearea, $valid_files) || preg_match("#slide\d+image#", $filearea) || preg_match("#marketing\d+image#", $filearea)) {
        //     return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        // } else {
        //     send_file_not_found();
        // }
    } else {
        send_file_not_found();
    }
}

/**
 * get_performance_output() override get_peformance_info()
 *  in moodlelib.php. Returns a string
 * values ready for use.
 *
 * @return string
 */
function meline29_performance_output($param) {
    $html = '<div class="uk-grid performanceinfo"><div><h2>Performance Information</h2></div>';
    if (isset($param['realtime'])){
        $html .= '<div class="uk-width-1-4"><a href="#"><var id="load">' . $param['realtime'] . ' secs</var><span>Load Time</span></a></div>';
    }
    if (isset($param['memory_total'])){
        $html .= '<div class="uk-width-1-4"><a href="#"><var id="memory">' . display_size($param['memory_total']) . '</var><span>Memory Used</span></a></div>';
    }
    if (isset($param['includecount'])){
        $html .= '<div class="uk-width-1-4"><a href="#"><var id="included">' . $param['includecount'] . ' Files </var><span>Included</span></a></div>';
    }
    if (isset($param['dbqueries'])){
        $html .= '<div class="uk-width-1-4"><a href="#"><var id="db">' . $param['dbqueries'] . ' </var><span>DB Read/Write</span></a></div>';
    }
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}


/**
 * This function checks if the generated styles file has been included, and replaces the placeholder with the real generated css (saved in moodledata).
 * Then runs other post-processing functions (logo, background images, etc.) of this theme with the final css.
 * @param string $css
 * @return string
 */
function theme_meline29_process_css($css, $theme) {
    $placeholder = '[[theme_meline29_full_generated_styles]]';
    if(strpos($css, $placeholder) !== false){
        //Generated styles placeholder found, put real styles from moodledata:
        
        $context = context_system::instance();
        $fileinfo = array(
            'contextid' => $context->id,
            'component' => 'theme_meline29',
            'filearea' => 'theme_meline29_styles',
            'itemid' => 0,
            'filepath' => '/',
            'filename' => 'theme_meline29_generated_styles.css'
        );

        $fs = get_file_storage();
        $generated_css_file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
            $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
        
        if($generated_css_file){
            $generatedcss = $generated_css_file->get_content();
        }else{
            //Error: the file should be present. Try to recover by loading the theme base styles:
            $generatedcss = file_get_contents(dirname(__FILE__).'/style/thememeline29.css');
        }
        
        $css = str_replace($placeholder, $generatedcss, $css);

        //Do a second replace of the placeholder, in case generatedcss contains malicious css that has the same placeholder (it would cause endless calls to this function)
        $css = str_replace($placeholder, '', $css);

        //We need to post-process the styles again by core Moodle to include images and fonts, and run the rest of this theme post-processing functions
        $themeName = 'meline29';
        $theme = theme_config::load($themeName);
        $css = $theme->post_process($css);
    }else{
        // Set the background image for the logo.
        $logo = $theme->setting_file_url('logo', 'logo');
        $css = theme_meline29_set_logo($css, $logo);

        // Set the main content block one image.
        $setting = 'blockoneimage';
        $blockoneimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_blockoneimage($css, $blockoneimage);

        // Set the main content block two image.
        $setting = 'blocktwoimage';
        $blocktwoimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_blocktwoimage($css, $blocktwoimage);
        
                // Set the main content block two image.
        $setting = 'blockthreeimage';
        $blockthreeimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_blockthreeimage($css, $blockthreeimage);
        
        // Set the main content block two image.
        $setting = 'blockfourimage';
        $blockfourimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_blockfourimage($css, $blockfourimage);
        
        // Set the main content block five image.
        $setting = 'blockfiveimage';
        $blockfiveimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_blockfiveimage($css, $blockfiveimage);
        
        // Set the main content block six image.
        $setting = 'blocksiximage';
        $blocksiximage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_blocksiximage($css, $blocksiximage);
        
        // Set the block one image.
        $setting = 'featureoneimage';
        $featureoneimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_featureoneimage($css, $featureoneimage);
        
        // Set the block two image.
        $setting = 'featuretwoimage';
        $featuretwoimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_featuretwoimage($css, $featuretwoimage);
        
        // Set the block three image.
        $setting = 'featurethreeimage';
        $featurethreeimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_featurethreeimage($css, $featurethreeimage);
        
        // Set the block four image.
        $setting = 'featurefourimage';
        $featurefourimage = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_featurefourimage($css, $featurefourimage);
        
        // Set the testimonial user one image.
        $setting = 'testimonialoneuserpic';
        $testimonialoneuserpic = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_testimonialoneuserpic($css, $testimonialoneuserpic);
        
        // Set the testimonial user two image.
        $setting = 'testimonialtwouserpic';
        $testimonialtwouserpic = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_testimonialtwouserpic($css, $testimonialtwouserpic);
        
        // Set the testimonial user three image.
        $setting = 'testimonialthreeuserpic';
        $testimonialthreeuserpic = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_testimonialthreeuserpic($css, $testimonialthreeuserpic);
        
        // Set the testimonial user four image.
        $setting = 'testimonialfouruserpic';
        $testimonialfouruserpic = $theme->setting_file_url($setting, $setting);
        $css = theme_meline29_set_testimonialfouruserpic($css, $testimonialfouruserpic);
        

        // Set the slideshow colors
        $css = theme_meline29_set_slideshow_sizing($css, $theme);
        $css = theme_meline29_set_slideshow_colors($css, $theme);

        // Set the font path.
        $css = theme_meline29_set_fontwww($css);
    }
    
    return $css;
}

function theme_meline29_page_init(moodle_page $page) {
    $page->requires->jquery();
}