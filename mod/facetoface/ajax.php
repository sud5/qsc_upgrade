<?php
define('AJAX_SCRIPT', true);

require_once(dirname(dirname(__FILE__)) . '/../config.php');
require_once("$CFG->dirroot/enrol/locallib.php");
//require_login();


echo "777";
//echo "2ee225";
// if (isguestuser()) { echo json_encode("Your session is destroyed. Please login again.");}
// else{

 
// $PAGE->set_url('/mod/facetoface/ajax.php');
// $PAGE->set_context(context_system::instance());

// //Mail feature
// $cm = get_coursemodule_from_id('facetoface', optional_param('coursemoduleid', null, PARAM_INT)); 
//  //echo "<pre>";print_r($cm);
//  //print_r($_REQUEST); //exit("Success1");

// $course = $DB->get_record('course', array('id' => $cm->course));

$courseid = optional_param('courseid', null, PARAM_INT); 

$unenrol_flag_return = unenrolled_classroom_session($courseid, $USER, 0);
    
// $adminObj = $DB->get_record('user', array('id' => 2), "*");
// $adminObj->mailformat = 1;

// $admin = get_admin(); //print_r($admin); exit("Success4");

// }

die();

//print_r($USER); exit;
/*
[id] => 5
    [auth] => manual
    [confirmed] => 1
    [policyagreed] => 0
    [deleted] => 0
    [suspended] => 0
    [mnethostid] => 1
    [username] => beyondkeysystem
    [idnumber] => 
    [firstname] => BeyondKey
    [lastname] => User
    [email] => beyondkey@mailinator.com
    [emailstop] => 0
    [icq] => 
    [skype] => 
    [yahoo] => 
    [aim] => 
    [msn] => 
    [phone1] => 
    [phone2] => 
    [institution] => 
    [department] => 
    [address] => 
    [city] => Indore
    [country] => IN
    [lang] => en
    [calendartype] => gregorian
    [theme] => 
    [timezone] => Asia/Kolkata
    [firstaccess] => 1447923804
    [lastaccess] => 1449494098
    [lastlogin] => 1449138629
    [currentlogin] => 1449481975
    [lastip] => 127.0.0.1
    [secret] => 
    [picture] => 0
    [url] => 
    [descriptionformat] => 1
    [mailformat] => 1
    [maildigest] => 0
    [maildisplay] => 2
    [autosubscribe] => 1
    [trackforums] => 0
    [timecreated] => 1447923699
    [timemodified] => 1447923699
    [trustbitmask] => 0
    [imagealt] => 
    [lastnamephonetic] => 
    [firstnamephonetic] => 
    [middlename] => 
    [alternatename] => 
    [lastcourseaccess] => Array
        (
            [6] => 1449138709
            [7] => 1449001587
            [10] => 1448448728
        )

    [currentcourseaccess] => Array
        (
            [6] => 1449494098
        )

    [groupmember] => Array
        (
            [6] => Array
                (
                    [1] => 1
                )

        )

    [profile] => Array
        (
        )

    [sesskey] => SYoSyAWpLm
    [access] => Array
        (
            [ra] => Array
                (
                    [/1] => Array
                        (
                            [7] => 7
                        )

                    [/1/2] => Array
                        (
                            [8] => 8
                        )

                    [/1/31/72/73/76] => Array
                        (
                            [5] => 5
                        )

                    [/1/31/72/73/85] => Array
                        (
                            [5] => 5
                        )

                    [/1/31/72/145] => Array
                        (
                            [5] => 5
                        )

                )

            [rdef] => Array
                (
                    [/1:7] => Array
                        (
                            [block/admin_bookmarks:myaddinstance] => 1
                            [block/badges:myaddinstance] => 1
                            [block/calendar_month:myaddinstance] => 1
                            [block/calendar_upcoming:myaddinstance] => 1
                            [block/comments:myaddinstance] => 1
                            [block/community:myaddinstance] => 1
                            [block/course_list:myaddinstance] => 1
                            [block/course_overview:myaddinstance] => 1
                            [block/glossary_random:myaddinstance] => 1
                            [block/html:myaddinstance] => 1
                            [block/mentees:myaddinstance] => 1
                            [block/messages:myaddinstance] => 1
                            [block/mnet_hosts:myaddinstance] => 1
                            [block/myprofile:myaddinstance] => 1
                            [block/navigation:myaddinstance] => 1
                            [block/news_items:myaddinstance] => 1
                            [block/online_users:myaddinstance] => 1
                            [block/online_users:viewlist] => 1
                            [block/private_files:myaddinstance] => 1
                            [block/recent_activity:viewaddupdatemodule] => 1
                            [block/recent_activity:viewdeletemodule] => 1
                            [block/rss_client:myaddinstance] => 1
                            [block/settings:myaddinstance] => 1
                            [block/tags:myaddinstance] => 1
                            [message/airnotifier:managedevice] => 1
                            [mod/folder:view] => 1
                            [mod/imscp:view] => 1
                            [mod/newsletter:manageownsubscription] => 1
                            [mod/newsletter:readissue] => 1
                            [mod/newsletter:viewnewsletter] => 1
                            [mod/page:view] => 1
                            [mod/resource:view] => 1
                            [mod/url:view] => 1
                            [moodle/badges:earnbadge] => 1
                            [moodle/badges:manageownbadges] => 1
                            [moodle/badges:viewbadges] => 1
                            [moodle/badges:viewotherbadges] => 1
                            [moodle/block:view] => 1
                            [moodle/blog:create] => 1
                            [moodle/blog:manageexternal] => 1
                            [moodle/blog:search] => 1
                            [moodle/blog:view] => 1
                            [moodle/calendar:manageownentries] => 1
                            [moodle/comment:post] => 1
                            [moodle/comment:view] => 1
                            [moodle/course:request] => 1
                            [moodle/my:manageblocks] => 1
                            [moodle/portfolio:export] => 1
                            [moodle/rating:rate] => 1
                            [moodle/rating:view] => 1
                            [moodle/rating:viewall] => 1
                            [moodle/rating:viewany] => 1
                            [moodle/site:sendmessage] => 1
                            [moodle/tag:create] => 1
                            [moodle/tag:edit] => 1
                            [moodle/tag:flag] => 1
                            [moodle/user:changeownpassword] => 1
                            [moodle/user:editownmessageprofile] => 1
                            [moodle/user:editownprofile] => 1
                            [moodle/user:manageownblocks] => 1
                            [moodle/user:manageownfiles] => 1
                            [moodle/webservice:createmobiletoken] => 1
                            [report/usersessions:manageownsessions] => 1
                            [repository/alfresco:view] => 1
                            [repository/areafiles:view] => 1
                            [repository/boxnet:view] => 1
                            [repository/dropbox:view] => 1
                            [repository/equella:view] => 1
                            [repository/flickr:view] => 1
                            [repository/flickr_public:view] => 1
                            [repository/googledocs:view] => 1
                            [repository/merlot:view] => 1
                            [repository/picasa:view] => 1
                            [repository/recent:view] => 1
                            [repository/s3:view] => 1
                            [repository/skydrive:view] => 1
                            [repository/upload:view] => 1
                            [repository/url:view] => 1
                            [repository/user:view] => 1
                            [repository/wikimedia:view] => 1
                            [repository/youtube:view] => 1
                        )

                    [/1:5] => Array
                        (
                            [block/online_users:viewlist] => 1
                            [booktool/print:print] => 1
                            [enrol/self:unenrolself] => 1
                            [gradereport/overview:view] => 1
                            [gradereport/user:view] => 1
                            [mod/assign:exportownsubmission] => 1
                            [mod/assign:submit] => 1
                            [mod/assign:view] => 1
                            [mod/assignment:exportownsubmission] => 1
                            [mod/assignment:submit] => 1
                            [mod/assignment:view] => 1
                            [mod/book:read] => 1
                            [mod/certificate:view] => 1
                            [mod/chat:chat] => 1
                            [mod/chat:readlog] => 1
                            [mod/choice:choose] => 1
                            [mod/data:comment] => 1
                            [mod/data:exportownentry] => 1
                            [mod/data:viewentry] => 1
                            [mod/data:writeentry] => 1
                            [mod/feedback:complete] => 1
                            [mod/feedback:view] => 1
                            [mod/feedback:viewanalysepage] => 1
                            [mod/forum:allowforcesubscribe] => 1
                            [mod/forum:createattachment] => 1
                            [mod/forum:deleteownpost] => 1
                            [mod/forum:exportownpost] => 1
                            [mod/forum:replypost] => 1
                            [mod/forum:startdiscussion] => 1
                            [mod/forum:viewdiscussion] => 1
                            [mod/forum:viewrating] => 1
                            [mod/glossary:comment] => 1
                            [mod/glossary:exportownentry] => 1
                            [mod/glossary:view] => 1
                            [mod/glossary:write] => 1
                            [mod/lti:view] => 1
                            [mod/newsletter:manageownsubscription] => 1
                            [mod/newsletter:readissue] => 1
                            [mod/newsletter:viewnewsletter] => 1
                            [mod/quiz:attempt] => 1
                            [mod/quiz:reviewmyattempts] => 1
                            [mod/quiz:view] => 1
                            [mod/scorm:savetrack] => 1
                            [mod/scorm:skipview] => 1
                            [mod/scorm:viewscores] => 1
                            [mod/survey:participate] => 1
                            [mod/wiki:createpage] => 1
                            [mod/wiki:editcomment] => 1
                            [mod/wiki:editpage] => 1
                            [mod/wiki:viewcomment] => 1
                            [mod/wiki:viewpage] => 1
                            [mod/workshop:peerassess] => 1
                            [mod/workshop:submit] => 1
                            [mod/workshop:view] => 1
                            [mod/workshop:viewauthornames] => 1
                            [mod/workshop:viewauthorpublished] => 1
                            [mod/workshop:viewpublishedsubmissions] => 1
                            [moodle/block:view] => 1
                            [moodle/blog:manageexternal] => 1
                            [moodle/blog:search] => 1
                            [moodle/blog:view] => 1
                            [moodle/comment:post] => 1
                            [moodle/comment:view] => 1
                            [moodle/course:isincompletionreports] => 1
                            [moodle/course:viewparticipants] => 1
                            [moodle/course:viewscales] => 1
                            [moodle/grade:view] => 1
                            [moodle/portfolio:export] => 1
                            [moodle/question:flag] => 1
                            [moodle/rating:rate] => 1
                            [moodle/rating:view] => 1
                            [moodle/rating:viewall] => 1
                            [moodle/rating:viewany] => 1
                            [moodle/user:readuserblogs] => 1
                            [moodle/user:readuserposts] => 1
                            [moodle/user:viewdetails] => 1
                        )

                    [/1:8] => Array
                        (
                            [booktool/print:print] => 1
                            [mod/book:read] => 1
                            [mod/data:viewentry] => 1
                            [mod/forum:allowforcesubscribe] => 1
                            [mod/forum:viewdiscussion] => 1
                            [mod/glossary:view] => 1
                            [mod/newsletter:manageownsubscription] => 1
                            [mod/newsletter:readissue] => 1
                            [mod/newsletter:viewnewsletter] => 1
                            [moodle/comment:view] => 1
                        )

                )

            [rdef_count] => 3
            [rdef_lcc] => 3
            [loaded] => Array
                (
                    [6] => 1
                    [7] => 1
                )

            [time] => 1449481976
            [rsw] => Array
                (
                )

        )

    [enrol] => Array
        (
            [enrolled] => Array
                (
                    [6] => 2147483647
                )

            [tempguest] => Array
                (
                )

        )

    [message_lastpopup] => 0
    [ajax_updatable_user_prefs] => Array
        (
            [docked_block_instance_70] => int
            [docked_block_instance_5] => int
            [docked_block_instance_65] => int
            [docked_block_instance_66] => int
            [docked_block_instance_67] => int
            [docked_block_instance_69] => int
            [block65hidden] => bool
            [block66hidden] => bool
            [block67hidden] => bool
            [block69hidden] => bool
            [block70hidden] => bool
            [docked_block_instance_35] => int
            [docked_block_instance_36] => int
            [docked_block_instance_37] => int
            [docked_block_instance_38] => int
            [block5hidden] => bool
            [block35hidden] => bool
            [block37hidden] => bool
            [block38hidden] => bool
            [filepicker_recentrepository] => int
            [filepicker_recentlicense] => safedir
            [filepicker_recentviewmode] => int
        )

    [editing] => 0
    [preference] => Array
        (
            [auth_forcepasswordchange] => 0
            [docked_block_instance_35] => 1
            [docked_block_instance_37] => 1
            [docked_block_instance_38] => 1
            [docked_block_instance_4] => 0
            [docked_block_instance_5] => 1
            [email_bounce_count] => 1
            [email_send_count] => 1
            [_lastloaded] => 1449494126
        )

)
*/
