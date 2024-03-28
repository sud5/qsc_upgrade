<?php
require_once('/var/www/moodle_qsc/config.php');
// echo "<pre>";
// echo $CFG->sessiontimeout." test <br>".$_COOKIE['MoodleSession'];
//user_accesstime_log   /var/www/html/qsclmslocal/lib/datalib.php
//!isloggedin()

        if (!isloggedin()) {
        	echo $flgSessnew = "not gotcha";
            // Ignore guest and not-logged-in timeouts, there is very little risk here.
        } else {
            echo $flgSessnew = "yes";
        }
//$_SESSION['SESSION']->has_timed_out

//  print_r($_SESSION);
// // /// Check for timed out sessions
// // if (!empty($SESSION->has_timed_out)) {
// //     $session_has_timed_out = true;
// //     unset($SESSION->has_timed_out);
// // } else {
// //     $session_has_timed_out = false;
// // }

//  print_r($_SERVER);
//  echo '1';

?>