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
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is built using the Clean template to allow for new theme's using
 * meline29 framework
 *
 *
 * @package   theme_meline29
 * @copyright 2014 Eduardo Ramos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//echo "<pre>";
$cur_lang = current_language();

if(!isset($_GET['lang'])){
 $_SESSION['slidelangfe']  = $lang = '_en';
 if(!empty($USER) && $USER->id!=0){
    $lang = "_".$USER->lang;
 }
}else{
 $_SESSION['slidelangfe']  = $lang = "_".$_GET['lang'];
}

if($lang == "_en" || $_SESSION['slidelangfe'] == "_en"){
 $_SESSION['slidelangfe']  = $lang = "_".$cur_lang;
}
//echo $lang;
/*echo ${"PAGE->theme->settings->toggleslideshow".$lang};
echo "<br>$$$$$$$<br>";
echo $PAGE->theme->settings->{'toggleslideshow'.$lang};

echo "<br>$$$$$$$<br>";
print_r($PAGE->theme->settings);
exit("Success123");
*/
$slideshowenabled = 
        ($PAGE->theme->settings->{'toggleslideshow'.$lang} == 1 
            || ($PAGE->theme->settings->{'toggleslideshow'.$lang} == 2 && !isloggedin()) 
            || ($PAGE->theme->settings->{'toggleslideshow'.$lang} == 3 && isloggedin()))
        ;

$slideshowheight = isset($PAGE->theme->settings->{'slideshowheight'.$lang}) ? $PAGE->theme->settings->{'slideshowheight'.$lang} : '300px';
$slideshowautoplay = isset($PAGE->theme->settings->{'slideshowautoplay'.$lang}) ? $PAGE->theme->settings->{'slideshowautoplay'.$lang} : true;
$slideshowanimation = isset($PAGE->theme->settings->{'slideshowanimation'.$lang}) ? $PAGE->theme->settings->{'slideshowanimation'.$lang} : 'swipe';
$slideshowkenburns = isset($PAGE->theme->settings->{'slideshowkenburns'.$lang}) ? $PAGE->theme->settings->{'slideshowkenburns'.$lang} : false;

//if ($slideshowenabled) {

    function theme_meline29_get_slide_html(&$PAGE, $i) {
        $lang = $_SESSION['slidelangfe'];
        //echo $lang;
       // echo $lang; exit("Success2");
        $titleSetting = "slide$i".$lang;
        $captionSetting = "slide{$i}caption".$lang;
        $urlSetting = "slide{$i}url".$lang;
        $urlTextSetting = "slide{$i}urltext".$lang;
        $captionPlacementSetting= "slide{$i}captionplacement".$lang;

        $title = $PAGE->theme->settings->$titleSetting;
        $caption = $PAGE->theme->settings->$captionSetting;
        $url = $PAGE->theme->settings->$urlSetting;
        $urlText = $PAGE->theme->settings->$urlTextSetting;
        $image = $PAGE->theme->setting_file_url("slide{$i}image{$lang}", "slide{$i}image{$lang}");
        $slideshowbuttontype = empty($PAGE->theme->settings->{"slideshowbuttontype".$lang}) ? '' : $PAGE->theme->settings->{"slideshowbuttontype".$lang};
        $slideshowcaptionplacement = empty($PAGE->theme->settings->$captionPlacementSetting) ? 'center' : $PAGE->theme->settings->$captionPlacementSetting;

        if (!empty($title) || !empty($caption) || !empty($url) || !empty($image)) {
        if (!empty($url)) {
                $attr = 'onclick="javascript:window.location.href=\''.$url.'\';"';
            }
            $html = '<li class="slide-'.$i.'" style="cursor:pointer;" '.$attr.'>';
            if (!empty($image)) {
                $html .= '<img src="' . $image . '" alt="' . $title . '">';
            }
            $html .= '<div class="uk-caption uk-caption-'.$slideshowcaptionplacement.' uk-caption-panel uk-animation-fade uk-flex uk-flex-center uk-flex-middle uk-text-center">';
            $html .= '<div>';
            if (!empty($title)) {
                $html .= '<h2>' . $title . '</h2>';
            }
            if (!empty($caption)) {
                $html .= '<p>' . $caption . '</p>';
            }
            //if (!empty($url)) {
              //  if (empty($urlText)) {
                //    $urlText = get_string('readmore', 'theme_meline29');
                //}
                //$html .= '<a href="' . $url . '" class="uk-button uk-button-small '.$slideshowbuttontype.'">' . $urlText . '</a>';
            //}
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</li>';
        } else {
            $html = '';
        }

        return $html;
    }

    $slidesHtml = '';
    $slides = isset($PAGE->theme->settings->{"slideshownumber".$lang}) ? $PAGE->theme->settings->{"slideshownumber".$lang} : 4;
    $slidesWithInfoCount = 0;
    foreach (range(1, $slides) as $i) {
        $slide = theme_meline29_get_slide_html($PAGE, $i);
        if(!empty($slide)){
            $slidesHtml .= $slide;
            $slidesWithInfoCount++;
        }
    }

    if(!empty($slidesHtml)){
        ?>
        <div id="thememeline29-slideshow" class="uk-slidenav-position uk-margin-bottom" data-uk-slideshow="{height: '<?php echo $slideshowheight; ?>', autoplay: <?php echo $slideshowautoplay ? 'true' : 'false'; ?>, animation: '<?php echo $slideshowanimation; ?>', kenburns: <?php echo $slideshowkenburns ? 'true' : 'false'?>}">
            <ul class="uk-slideshow">
                <?php echo $slidesHtml; ?>
            </ul>
            <?php if($slidesWithInfoCount > 1){ ?>
                <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
                <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
                <ul class="uk-dotnav uk-dotnav-contrast uk-position-bottom uk-text-center uk-hidden-small">
                    <?php foreach (range(0, $slidesWithInfoCount - 1) as $i) { ?>
                        <li data-uk-slideshow-item="<?php echo $i; ?>"><a href=""></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
        <?php
    }
//}
