<?php
// This file is part of Moodle Google Oauth2 plugin
//
// It is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with it.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observer for googleoauth2 auth plugin.
 *
 * @package    auth_googleoauth2
 * @copyright  2015 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

 * If the email doesn't exist, then the auth plugin creates the user.
 * If the email exist (and the user has for auth plugin this current one),
 * then the plugin login the user related to this email.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page.
}

require_once($CFG->libdir . '/authlib.php');
require_once($CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php');
require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');

/**
 * Google/Facebook/Messenger Oauth2 authentication plugin.
 */
class auth_plugin_googleoauth2 extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'googleoauth2';
        $this->roleauth = 'auth_googleoauth2';
        $this->errorlogtag = '[AUTH GOOGLEOAUTH2] ';
        $this->config = get_config('auth/googleoauth2');
        $this->token = null;
    }

    /**
     * Prevent authenticate_user_login() to update the password in the DB
     * @return boolean
     */
    public function prevent_local_passwords() {
        return true;
    }

    /**
     * Authenticates user against the selected authentication provide (Google, Facebook...)     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {
        global $DB, $CFG;

        // Retrieve the user matching username.
        $user = $DB->get_record('user', array('username' => $username,
            'mnethostid' => $CFG->mnet_localhost_id));

        // Username must exist and have the right authentication method.
        if (!empty($user) && ($user->auth == 'googleoauth2')) {
            $code = optional_param('code', false, PARAM_TEXT);
            if (empty($code)) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    public function can_change_password() {
        return false;
    }
    //modify by shiva
    public function postlogout_hook($user) {
        global $CFG; // "https://".$_SERVER['SERVER_NAME'];
        
        if($user->auth == "googleoauth2"){
            
            //to handle sfdc deleted user
            if(isset($user->sfdcstatus) && $user->sfdcstatus == '404'){
                 $to = $CFG->wwwroot.'/contactadmin.php';    
            }
            else{
                $to = $CFG->wwwroot.'/';
            }
            //to handle sfdc deleted user
            redirect(SSO_AOUTH2_BASE_URL."/sign_out_globally?to=".$to);
        }
    }

    /**
    * Return true if can create new account
    *
    * @return bool
    */
    function can_create_new_user() {
        return ( $this->config->donotcreatenewuser != 1 );
    }
 
    /**
     * Returns the user information for 'external' users.
     *
     * @return array $result Associative array of user data
     */
    function get_userinfo($username) {
	$result=array();
        // Get User Details from provider token
	if ( $this->token ) {
           $result = (array) $provider->get_user_details($this->token);
           debugging('Delivery userinfo: '.print_r($result,true), DEBUG_DEVELOPER);
        }
	return $result;
    }

    /**
     * Return true if the email domain is valid
     *
     * @return bool
    */
    function email_auth_domains($email) {
        $auth_domains = $this->config->authdomains;
        if (!empty(trim($auth_domains))) {
                $allowedpatterns = explode(',', $auth_domains);
                foreach ($allowedpatterns as $allowedpattern) {
                        $allowedpattern = trim($allowedpattern);
                        if (strlen($allowedpattern) == 0) {
                                continue;
                        }
                        if (strpos($allowedpattern, '.') === 0) {
                            if (strpos(strrev($email), strrev($allowedpattern)) === 0) {
                                // Subdomains are in a form ".example.com" - matches "xxx@anything.example.com".
                                return true;
                            }
                        } else {
                            if (strpos(strrev($email), strrev('@'.$allowedpattern)) === 0) {
                                return true;
                            }
                        }
                }
                return false;
        }
        return true;
    }

    /**
     * Authentication hook - is called every time user hit the login page
     * The code is run only if the param code is mentionned.
     */
    public function loginpage_hook() {
        global $USER, $SESSION, $CFG, $DB;

        // Check the Google authorization code.
        $authorizationcode = optional_param('code', '', PARAM_TEXT);
        if (!empty($authorizationcode)) {

            $authprovider = required_param('authprovider', PARAM_ALPHANUMEXT);
            require_once($CFG->dirroot . '/auth/googleoauth2/classes/provider/'.$authprovider.'.php');
            $providerclassname = 'provideroauth2' . $authprovider;
            $provider = new $providerclassname();


            // Try to get an access token (using the authorization code grant).
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $authorizationcode
            ]);

            $accesstoken = $token->accessToken;
            $refreshtoken = $token->refreshToken;
            $tokenexpires = $token->expires;



                // We got an access token, let's now get the user's details.
                $userdetails = $provider->getUserDetails($token);


                // added by lakhan
                if(!is_object($userdetails)) $userdetails = (object)$userdetails;


            // With access token request by curl the email address.
            if (!empty($accesstoken)) {

                //$useremail = '';

                try {
                   

                    // Use these details to create a new profile.
                    switch ($authprovider) {
                        case 'battlenet':
                            // Battlenet as no email notion.
                            // TODO: need to check the idp table for matching user and request user to add his email.
                            // TODO: It will be similar logic for twitter.
                            $useremail = $userdetails->id . '@fakebattle.net';
                            break;
                        case 'github':
                            $useremails = $provider->getUserEmails($token);
                            // Going to try to find someone with a similar email using googleoauth2 auth.
                            $fallbackuseremail = '';
                            foreach ($useremails as $githubuseremail) {
                                if ($githubuseremail->verified) {
                                    if ($DB->record_exists('user',
                                        array('auth' => 'googleoauth2', 'email' => $githubuseremail->email))) {
                                        $useremail = $githubuseremail->email;
                                    }
                                    $fallbackuseremail = $githubuseremail->email;
                                }
                            }
                            // If we didn't find anyone then we take a verified email address.
                            if (empty($useremail)) {
                                $useremail = $fallbackuseremail;
                            }
                            break;
                        case 'vk':
                            // VK doesn't return the email address?
                            if ($userdetails->uid) {
                                $useremail = 'id'.$userdetails->uid.'@vkmessenger.com';
                            };
                            break;
                        default:
                            $useremail = $userdetails->email;
                            break;
                    }

                    $verified = 1;
                } catch (Exception $e) {
                    // Failed to get user details.
                    throw new moodle_exception('faileduserdetails', 'auth_googleoauth2');
                }
	
                // Throw an error if the email address is not verified.
                if (!$verified) {
                    throw new moodle_exception('emailaddressmustbeverified', 'auth_googleoauth2');
                }

                // Prohibit login if email belongs to the prohibited domain.
                if ($err = email_is_not_allowed($useremail)) {
                    throw new moodle_exception($err, 'auth_googleoauth2');
                }

                // commented by lakhan temp
                // If email not existing in user database then create a new username (userX).
                if (empty($useremail) || $useremail != clean_param($useremail, PARAM_EMAIL)) {
                    throw new moodle_exception('couldnotgetuseremail', 'auth_googleoauth2');
                    // TODO: display a link for people to retry.
                }

                // Get the user.
                // Don't bother with auth = googleoauth2 because authenticate_user_login() will fail it if it's not 'googleoauth2'.
                //updated by lakhan
                /*$user = $DB->get_record('user',
                    array('email' => $useremail, 'deleted' => 0, 'mnethostid' => $CFG->mnet_localhost_id));*/



                $user = $DB->get_record('user',
                    array('username' => $useremail, 'mnethostid' => $CFG->mnet_localhost_id));

                // Create the user if it doesn't exist.
                if (empty($user)) {
                    // Deny login if setting "Prevent account creation when authenticating" is on.
                    if ($CFG->authpreventaccountcreation) {
                        throw new moodle_exception("noaccountyet", "auth_googleoauth2");
                    }
		    // Check if the plugin can create a new account
                    if (!$this->can_create_new_user()) {
                        throw new moodle_exception('cantcreatenewuser', 'auth_googleoauth2');
                    }

                    // // Get following incremented username.
                    $googleuserprefix = core_text::strtolower(get_config('auth/googleoauth2', 'googleuserprefix'));
                    $lastusernumber = get_config('auth/googleoauth2', 'lastusernumber');
                    $lastusernumber = empty($lastusernumber) ? 1 : $lastusernumber + 1;

                    // Mutual Lock plugin activities
                    $timeout = 5; // 5 seconds.
                    // A namespace for the locks. Must be prefixed with the component name to prevent conflicts.
                    $locktype = 'auth_googleoauth2'; 
                    $resource = 'usercreationnumber';
 
                    // Get an instance of the currently configured lock_factory.
                    $lockfactory = \core\lock\lock_config::get_lock_factory('auth_googleoauth2');

                    // // Check the user doesn't exist.
                    // $nextuser = $DB->record_exists('user', array('username' => $googleuserprefix.$lastusernumber));

                    // while ($nextuser) {
                    //     $lastusernumber++;
                    //     $nextuser = $DB->record_exists('user', array('username' => $googleuserprefix.$lastusernumber));
                    // }
                    // set_config('lastusernumber', $lastusernumber, 'auth/googleoauth2');
                    // $username = $googleuserprefix . $lastusernumber;

                    // Retrieve more information from the provider.
                    $newuser = new stdClass();
                    $newuser->email = $useremail;

                    switch ($authprovider) {
                        case 'battlenet':
                            // Battlenet as no firstname/lastname notion.
                            $newuser->firstname = $userdetails->display_name;
                            $newuser->lastname = '['.$userdetails->clan_tag.']';
                            break;
                        case 'github':
                        case 'dropbox':
                            // As Github/Dropbox doesn't provide firstname/lastname, we'll split the name at the first whitespace.
                            $githubusername = explode(' ', $userdetails->name, 2);
                            $newuser->firstname = $githubusername[0];
                            $newuser->lastname = $githubusername[1];
                            break;
                        default:
                            $newuser->firstname = $userdetails->firstname;
                            $newuser->lastname = $userdetails->lastname;
                            break;
                    }

                    $username = $userprefix . $lastusernumber;

                    // Some providers allow empty firstname and lastname.
                    if (empty($newuser->firstname)) {
                        $newuser->firstname = get_string('unknownfirstname', 'auth_googleoauth2');
                    }
                    if (empty($newuser->lastname)) {
                        $newuser->lastname = get_string('unknownlastname', 'auth_googleoauth2');
                    }


                    // Retrieve country and city if the provider failed to give it.

                        //updated by lakhan
                        if (!isset($userdetails->country) || !isset($userdetails->city)) {
                        //if (!isset($newuser->country) || !isset($newuser->city)) {
                        $googleipinfodbkey = get_config('auth/googleoauth2', 'googleipinfodbkey');

                        if (!empty($googleipinfodbkey)) {
                            require_once($CFG->libdir . '/filelib.php');
                            $curl = new curl();
                            $locationdata = $curl->get('http://api.ipinfodb.com/v3/ip-city/?key=' .
                                $googleipinfodbkey . '&ip='. getremoteaddr() . '&format=json' );
                            $locationdata = json_decode($locationdata);
                        }

                        if (!empty($locationdata)) {
                            // TODO: check that countryCode does match the Moodle country code.
                            $newuser->country = isset($newuser->country) ? isset($newuser->country) : $locationdata->countryCode;
                            $newuser->city = isset($newuser->city) ? isset($newuser->city) : $locationdata->cityName;
                        }
                    }

                    $username = $userdetails->email;

                    $createdUser = create_user_record($username, '', 'googleoauth2');
                    //print_r($user);die;
                    //added by lakhan
                    //$userdetails->id = $createdUser->id;
                    //$DB->update_record('user', $userdetails);

                }elseif($user->deleted == 1 && $user->suspended == 1){ 
                    
                    $muser = $DB->get_record('tool_mergeusers',array('fromuserid' => $user->id));
                    if(!empty($muser)){
                        redirect($CFG->wwwroot.'/oldtonewuser.php?o='.$muser->fromuserid.'&n='.$muser->touserid); // to open after estimate approval
                        

                        //$newmuser = $DB->get_record('user',array('id' => $muser->touserid));
                        
                        //echo "<div>Your account has been associated with ".$newmuser->username." Please login with that account <br/>Please chick to the following link to login again with the new user credentials <a href=".$CFG->wwwroot.'/oldtonewuser.php'.">Click Here</a></div>";die;
                        //throw new moodle_exception("useralreadymerged", "auth_googleoauth2","", $newmuser->username);
                    }else{
                        redirect($CFG->wwwroot.'/login/logout.php?flagdelete=1');
                        //echo "<div>This account has been deleted <br/>Please chick to the following link to login again with the new user credentials <a href=".$CFG->wwwroot.'/oldtonewuser.php'.">Click Here</a></div>";die;
                        //throw new moodle_exception("useralreadydeleted", "auth_googleoauth2");
                    }


                }else{
                    //$username = $user->username;
                    $username = $userdetails->email;
                }

// LOGIN
                // Authenticate the user.
                // TODO: delete this log later.
                require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
                $userid = empty($user) ? 'new user' : $user->id;
                oauth_add_to_log(SITEID, 'auth_googleoauth2', '', '', $username . '/' . $useremail . '/' . $userid);


                $user = authenticate_user_login($username, null);

                if($user){

                    // Set a cookie to remember what auth provider was selected.
                    setcookie('MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie, $authprovider,
                            time() + (DAYSECS * 60), $CFG->sessioncookiepath,
                            $CFG->sessioncookiedomain, $CFG->cookiesecure,
                            $CFG->cookiehttponly);



                    // Prefill more user information if new user.
                    /*if (!empty($user)) {
                        $newuser->id = $user->id;
                        $DB->update_record('user', $newuser);
                        $user = (object) array_merge((array) $user, (array) $newuser);
                    }*/


                    $providerUserId = $userdetails->providerUserId;
                    unset($userdetails->providerUserId);

                    // LMS has 'company email' value in 'email' field which should update from SSO if empty else remain set value by user in LMS profile page
                     // echo $user->email;
                     // echo $userdetails->email;die;

                    if($user->email != ""){
						//echo "1".$userdetails->email."-".$user->email;
                    	$userdetails->email = $user->email; 
                    }else{
                    	//echo "2".$userdetails->email;
                    	$userdetails->email = $username; 
                    }
					//die;

                    //Updated by lakhan
                    if (!empty($userdetails)) {
                        $userdetails = array_merge(['id'=>$user->id], (array)$userdetails);
                        $objUser = (object)$userdetails;
                        $DB->update_record('user', $objUser);
                        $user = (object) array_merge((array) $user, (array) $objUser);
                    }

                    //print_r($user);die;
                    complete_user_login($user);

                    // Let's save/update the access token for this user.

                    //updated by lakhan
                    //$cansaveaccesstoken = get_config('auth/googleoauth2', 'saveaccesstoken');
                    //if (!empty($cansaveaccesstoken)) {
                        $existingaccesstoken = $DB->get_record('auth_googleoauth2_user_idps',
                            array('userid' => $user->id, 'provider' => $authprovider));
                        if (empty($existingaccesstoken)) {
                            $accesstokenrow = new stdClass();
                            $accesstokenrow->userid = $user->id;
                            switch ($authprovider) {
                                case 'battlenet':
                                    $accesstokenrow->provideruserid = $userdetails->id;
                                    break;
                                default:
                                    //$accesstokenrow->provideruserid = $userdetails->uid;  // updated by lakhan
                                    $accesstokenrow->provideruserid = $providerUserId;
                                    break;
                            }

                            $accesstokenrow->provider = $authprovider;
                            $accesstokenrow->accesstoken = $accesstoken;
                            $accesstokenrow->refreshtoken = $refreshtoken;
                            $accesstokenrow->expires = $tokenexpires;

                            $DB->insert_record('auth_googleoauth2_user_idps', $accesstokenrow);

                        } else {
                            $existingaccesstoken->accesstoken = $accesstoken;
                            $existingaccesstoken->provideruserid = $userdetails->providerUserId;
                            $DB->update_record('auth_googleoauth2_user_idps', $existingaccesstoken);
                        }
                    //}

                    // Check if the user picture is the default and retrieve the provider picture.
                    if (empty($user->picture)) {
                        $profilepicurl = '';
                        switch ($authprovider) {
                            case 'battlenet':
                                $profilepicurl = $userdetails->portrait_url;
                                break;
                            case 'google':
                                $profilepicurl = str_replace('?sz=50', '?sz=300', $userdetails->imageUrl);
                                break;
                            default:
                                if (!empty($userdetails->imageUrl)) {
                                    $profilepicurl = $userdetails->imageUrl;
                                }
                                break;
                        }
                        if (!empty($profilepicurl)) {
                            $this->set_profile_picture($user, $profilepicurl);
                        }
                    }

                    // Create event for authenticated user.
                    $event = \auth_googleoauth2\event\user_loggedin::create(
                        array('context' => context_system::instance(),
                            'objectid' => $user->id, 'relateduserid' => $user->id,
                            'other' => array('accesstoken' => $accesstoken)));
                    $event->trigger();


                    // Redirection.
                    //if (user_not_fully_set_up($USER){
                    //updated by lakhan
                    //US #17913 remove empty check(phone,zip) in below condition
                    if (user_not_fully_set_up($USER) or empty($USER->lang)) {
                        $urltogo = $CFG->wwwroot.'/user/edit.php';
                        // We don't delete $SESSION->wantsurl yet, so we get there later.
                    }else if (isset($SESSION->wantsurl) && (strpos($SESSION->wantsurl, $CFG->wwwroot) === 0)) {
                        $urltogo = $SESSION->wantsurl;    // Because it's an address in this site.
                        unset($SESSION->wantsurl);
                    } else {
                        // No wantsurl stored or external - go to homepage.
                        $urltogo = $CFG->wwwroot.'/';
                        unset($SESSION->wantsurl);
                    }

                    // Add my record to statistics table with subtype information
                    $loginrecord = array('userid' => $USER->id, 'time' => time(),
                        'auth' => 'googleoauth2', 'subtype' => $authprovider);
                    $DB->insert_record('auth_googleoauth2_logins', $loginrecord);

                    redirect($urltogo);
                } else {

                    
                    // Authenticate_user_login() failure, probably email registered by another auth plugin.
                    // Do a check to confirm this hypothesis.
                    $userexist = $DB->get_record('user', array('email' => $useremail));
                    if (!empty($userexist) && $userexist->auth != 'googleoauth2') {
                        $a = new stdClass();
                        $a->loginpage = (string) new moodle_url(empty($CFG->alternateloginurl) ?
                            '/login/index.php' : $CFG->alternateloginurl);
                        $a->forgotpass = (string) new moodle_url('/login/forgot_password.php');
                        throw new moodle_exception('couldnotauthenticateuserlogin', 'auth_googleoauth2', '', $a);
                    } else {
                        throw new moodle_exception('couldnotauthenticate', 'auth_googleoauth2');
                    }
                }
                debugging('Complete User Details: '.print_r($userdetails,true), DEBUG_DEVELOPER);
            } else {
                throw new moodle_exception('couldnotgetgoogleaccesstoken', 'auth_googleoauth2');
            }
        } else {
            // If you are having issue with the display buttons option, add the button code directly in the theme login page.
            if (get_config('auth/googleoauth2', 'oauth2displaybuttons')
                // Check manual parameter that indicate that we are trying to log a manual user.
                // We can add more param check for others provider but at the end,
                // the best way may be to not use the oauth2displaybuttons option and
                // add the button code directly in the theme login page.
                && empty($_POST['username'])
                && empty($_POST['password'])) {
                // Display the button on the login page.
                require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');

                // Insert the html code below the login field.
                // Code/Solution from Elcentra plugin: https://moodle.org/plugins/view/auth_elcentra.
                global $PAGE, $CFG;
                $PAGE->requires->jquery();
                $content = str_replace(array("\n", "\r"), array("\\\n", "\\\r"), auth_googleoauth2_display_buttons(false));
                $PAGE->requires->css('/auth/googleoauth2/style.css');
                $PAGE->requires->js_init_code("buttonsCodeOauth2 = '$content';");
                $PAGE->requires->js(new moodle_url($CFG->wwwroot . "/auth/googleoauth2/script.js"));
            }
        }
    }

    /**
     * Retrieve the profile picture and save it in moodle.
     */
    private function set_profile_picture($user, $profilepicurl) {
        global $CFG, $DB;

        debugging('set user '.$user->username.' profile picture from oauth2 provider to url: '.$profilepicurl, DEBUG_DEVELOPER);
        require_once($CFG->libdir . '/filelib.php');
        require_once($CFG->libdir . '/gdlib.php');
        $imagefilename = $CFG->tempdir . '/googleoauth2-portrait-' . $user->id;
        $imagecontents = download_file_content($profilepicurl);
        file_put_contents($imagefilename, $imagecontents);
        if ($newrev = process_new_icon(context_user::instance($user->id),
            'user', 'icon', 0, $imagefilename)) {
            $DB->set_field('user', 'picture', $newrev, array('id' => $user->id));
        }
        unlink($imagefilename);
    }


    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $config An object containing all the data for this page.
     * @param string $error
     * @param array $user_fields
     * @return void
     */
    function config_upgrade_form($config, $err, $user_fields) {
        return include 'config.html';
    }
    public function config_form($config, $err, $userfields) {
        global $OUTPUT, $CFG;

        echo '<div class="alert alert-success"  role="alert">' . get_string('supportmaintenance', 'auth_googleoauth2') . '</div>';


        // TODO: replace this table html ugliness by some nice bootstrap html code.
        echo '<table cellspacing="0" cellpadding="5" border="0">
            <tr>
               <td colspan="3">
                    <h2 class="main">';

        print_string('auth_googlesettings', 'auth_googleoauth2');

        $providers = provider_list();

        foreach ($providers as $providername) {

            $clientidname = $providername . 'clientid';
            $clientsecretname = $providername . 'clientsecret';

            // Set to defaults if undefined.
            if (!isset($config->{$clientidname})) {
                $config->{$clientidname} = '';
            }
            if (!isset($config->{$clientsecretname})) {
                $config->{$clientsecretname} = '';
            }

            // Client id.

            echo '</h2>
               </td>
            </tr>
            <tr  style="vertical-align: top;">
                <td align="right"><label for="'.$clientidname.'">';

            print_string('auth_'.$clientidname.'_key', 'auth_googleoauth2');

            echo '</label></td><td>';

            echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => $clientidname, 'name' => $clientidname,
                    'class' => $clientidname, 'value' => $config->{$clientidname}));

            if (isset($err[$clientidname])) {
                echo $OUTPUT->error_text($err[$clientidname]);
            }

            echo '</td><td>';
            $parse = parse_url($CFG->wwwroot);
            print_string('auth_'.$clientidname, 'auth_googleoauth2',
                array('jsorigins' => $parse['scheme'].'://'.$parse['host'], 'siteurl' => $CFG->httpswwwroot,
                    'domain' => $CFG->httpswwwroot,
                    'redirecturls' => $CFG->httpswwwroot . '/auth/googleoauth2/'.$providername.'_redirect.php',
                    'callbackurl' => $CFG->httpswwwroot . '/auth/googleoauth2/'.$providername.'_redirect.php',
                    'sitedomain' => $parse['host']));

            echo '</td></tr>';

            // Client secret.

            echo '<tr  style="vertical-align: top;">
                <td align="right"><label for="'.$clientsecretname.'">';

            print_string('auth_'.$clientsecretname.'_key', 'auth_googleoauth2');

            echo '</label></td><td>';

            echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => $clientsecretname, 'name' => $clientsecretname,
                    'class' => $clientsecretname, 'value' => $config->{$clientsecretname}));

            if (isset($err[$clientsecretname])) {
                echo $OUTPUT->error_text($err[$clientsecretname]);
            }

            echo '</td><td>';

            print_string('auth_'.$clientsecretname, 'auth_googleoauth2');

            echo '</td></tr>
            <tr style="min-height: 20px"><td>&nbsp;</td></tr>';
        }

        if (!isset($config->googleipinfodbkey)) {
            $config->googleipinfodbkey = '';
        }

        if (!isset($config->googleuserprefix)) {
            $config->googleuserprefix = 'social_user_';
        }

        if (!isset($config->oauth2displaybuttons)) {
            $config->oauth2displaybuttons = 1;
        }

        // IPinfoDB.

        echo '<tr>
                <td align="right"><label for="googleipinfodbkey">';

        print_string('auth_googleipinfodbkey_key', 'auth_googleoauth2');

        echo '</label></td><td>';

        echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => 'googleipinfodbkey', 'name' => 'googleipinfodbkey',
                    'class' => 'googleipinfodbkey', 'value' => $config->googleipinfodbkey));

        if (isset($err["googleipinfodbkey"])) {
            echo $OUTPUT->error_text($err["googleipinfodbkey"]);
        }

        echo '</td><td>';

        print_string('auth_googleipinfodbkey', 'auth_googleoauth2', (object) array('website' => $CFG->wwwroot));

        echo '</td></tr>';

        // User prefix.

        echo '<tr>
                <td align="right"><label for="googleuserprefix">';

        print_string('auth_googleuserprefix_key', 'auth_googleoauth2');

        echo '</label></td><td>';

        echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => 'googleuserprefix', 'name' => 'googleuserprefix',
                    'class' => 'googleuserprefix', 'value' => $config->googleuserprefix));

        if (isset($err["googleuserprefix"])) {
            echo $OUTPUT->error_text($err["googleuserprefix"]);
        }

        echo '</td><td>';

        print_string('auth_googleuserprefix', 'auth_googleoauth2');

        echo '</td></tr>';

        // Display buttons.

        echo '<tr>
                <td align="right"><label for="oauth2displaybuttons">';

        print_string('oauth2displaybuttons', 'auth_googleoauth2');

        echo '</label></td><td>';

        $checked = empty($config->oauth2displaybuttons) ? '' : 'checked';
        echo html_writer::checkbox('oauth2displaybuttons', 1, $checked, '',
            array('type' => 'checkbox', 'id' => 'oauth2displaybuttons', 'class' => 'oauth2displaybuttons'));

        if (isset($err["oauth2displaybuttons"])) {
            echo $OUTPUT->error_text($err["oauth2displaybuttons"]);
        }

        echo '</td><td>';

        /* $code = '<code>&lt;?php require_once($CFG-&gt;dirroot . \'/auth/googleoauth2/lib.php\');
                 auth_googleoauth2_display_buttons(); ?&gt;</code>';
         print_string('oauth2displaybuttonshelp', 'auth_googleoauth2', $code);*/

        echo '</td></tr>';


        // Block field options.
        // Hidden email options - email must be set to: locked.
        echo html_writer::empty_tag('input', array('type' => 'hidden', 'value' => 'locked',
                    'name' => 'lockconfig_field_lock_email'));

        // Display other field options.
        foreach ($userfields as $key => $userfield) {
            if ($userfield == 'email') {
                unset($userfields[$key]);
            }
        }
        print_auth_lock_options('googleoauth2', $userfields, get_string('auth_fieldlocks_help', 'auth'), false, false);

        echo '</table>';

        // Calculate how many login per providers.
        $providerstats = (object) $this->get_stats();
        $strothermoodle = get_string('othermoodle', 'auth_googleoauth2');
        $strstattitle = get_string('stattitle', 'auth_googleoauth2', $providerstats);
        echo '
            <center>
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script type="text/javascript">
                  google.load("visualization", "1", {packages:["corechart"]});
                  google.setOnLoadCallback(drawChart);
                  function drawChart() {

                    var data = google.visualization.arrayToDataTable([
                      [\'Provider\', \'Login total\'],
                      [\'Google\', ' . $providerstats->google . '],
                      [\'Facebook\', ' . $providerstats->facebook . ' ],
                      [\'Github\',  ' . $providerstats->github . ' ],
                      [\'Linkedin\', ' . $providerstats->linkedin . ' ],
                      [\'Microsoft\', ' . $providerstats->microsoft . ' ],
                      [\'Dropbox\', ' . $providerstats->dropbox . ' ],
                      [\'VK\', ' . $providerstats->vk . ' ],
                      [\'Battle.net\', ' . $providerstats->battlenet . ' ],
                      [\'qscid\', ' . $providerstats->qscid . ' ],
                      [\''.$strothermoodle.'\',    ' . $providerstats->moodle . ' ]
                    ]);

                    var options = {
                      title: \''.$strstattitle.'\',
                      is3D: true,
                      slices: {
                        0: { color: \'#D50F25\' },
                        1: { color: \'#3b5998\' },
                        2: { color: \'#eee\', fontcolor: \'black\'},
                        3: { color: \'#007bb6\'},
                        4: { color: \'#7cbb00\'},
                        5: { color: \'#007ee5\'},
                        6: { color: \'#45668e\'},
                        7: { color: \'#45668e\'},
                        8: { color: \'#00B4FF\'},
                        9: { color: \'#ee7611\'}

                      }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById(\'piechart\'));

                    chart.draw(data, options);
                  }
                </script>
             <div id="piechart" style="width: 900px; height: 500px;"></div>
            </center>
        ';
    }
    /**
     * Retrieve the login provider stats.
     */
    public function get_stats($periodindays = 60) {
        global $DB;

        // Retrieve the logins.
        $sql = 'time > :time';
        $logins = $DB->get_records_select('auth_googleoauth2_logins', $sql,
            array('time' => strtotime('-' . $periodindays . ' days', time())));

        // Retrieve the moodle auth stats.
        $loginstats = array( 
                            'qscid' => 0,
                            'google' => 0,
                            'facebook' => 0,
                            'github' => 0,
                            'linkedin' => 0,
                            'microsoft' => 0,
                            'dropbox' => 0,
                            'vk' => 0,
                            'battlenet' => 0,
                            'moodle' => 0,
                            'periodindays' => $periodindays);

        // Retrieve the provider stats.
        foreach ($logins as $login) {
            if ($login->auth !== 'googleoauth2') {
                $loginstats['moodle'] += 1;
            } else {
                $loginstats[$login->subtype] += 1;
            }
        }



        return $loginstats;
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    public function process_config($config) {
        // Set to defaults if undefined.

        $providers = provider_list();

        foreach ($providers as $providername) {
            $clientidname = $providername . 'clientid';
            $clientsecretname = $providername . 'clientsecret';

            // Set to defaults if undefined.
            if (!isset($config->{$clientidname})) {
                $config->{$clientidname} = '';
            }
            if (!isset($config->{$clientsecretname})) {
                $config->{$clientsecretname} = '';
            }

            // Save settings.
            set_config($clientidname, $config->{$clientidname}, 'auth/googleoauth2');
            set_config($clientsecretname, $config->{$clientsecretname}, 'auth/googleoauth2');
        }

        if (!isset ($config->googleuserprefix)) {
            $config->googleuserprefix = 'social_user_';
        }
        if (!isset ($config->oauth2displaybuttons)) {
            $config->oauth2displaybuttons = 0;
        }

        set_config('googleipinfodbkey', $config->googleipinfodbkey, 'auth/googleoauth2');
        set_config('googleuserprefix', core_text::strtolower($config->googleuserprefix), 'auth/googleoauth2');
        set_config('oauth2displaybuttons', $config->oauth2displaybuttons, 'auth/googleoauth2');

        return true;
    }

    /**
     * Called when the user record is updated.
     *
     * We check there is no hack-attempt by a user to change his/her email address
     *
     * @param mixed $olduser     Userobject before modifications    (without system magic quotes)
     * @param mixed $newuser     Userobject new modified userobject (without system magic quotes)
     * @return boolean result
     *
     */
    public function user_update($olduser, $newuser) {

    	//echo $olduser;die;
        //US #2490 start change email to username
        if ($olduser->username != $newuser->username) {
        //US #2490 end change email to username
            return false;
        }
        return true;
    }
}
