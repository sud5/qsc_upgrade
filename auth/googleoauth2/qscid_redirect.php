<?php
// This file is part of Oauth2 authentication plugin for Moodle.
//
// Oauth2 authentication plugin for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Oauth2 authentication plugin for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Oauth2 authentication plugin for Moodle.  If not, see <http://www.gnu.org/licenses/>.

require('../../config.php');
require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
googleoauth2_provider_redirect('qscid');




		//$content = json_encode(array("name" => $course_name,"course_version_type__c"=>$course_type,"date_of_training__c"=>$dot)); 

		// $content =	'{
		// 			    "data": {
		// 			        "id": "72d15875-96c6-4e30-9e2e-7d2428b87436",
		// 			        "type": "users",
		// 			        "attributes": {
		// 			            "first-name": "Lakhan newuser",
		// 			            "last-name": "Chouhan newuser",
		// 			            "email": "lms_user@mailinator.com",
		// 			            "email-opt-in": false,
		// 			            "phone": "1111111111"
		// 			        },
		// 			        "relationships": {
		// 			            "groups": {
		// 			                "data": []
		// 			            }
		// 			        }
		// 			    }
		// 			}';

  //       $url = "https://qscid.ciclabs.com/api/vi/users/me";
  //       $curl = curl_init($url);
  //       curl_setopt($curl, CURLOPT_HEADER, false);
  //       curl_setopt($curl, CURLOPT_HTTPHEADER,
  //               array("Authorization: OAuth $access_token",
  //                   "Content-type: application/json"));
  //       curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
  //       curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

  //       $json_response = curl_exec($curl);

  //       $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  //       if ( $status != 204 ) {
  //           die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
  //       }
  //       curl_close($curl);
  //       $response = json_decode($json_response, true);

