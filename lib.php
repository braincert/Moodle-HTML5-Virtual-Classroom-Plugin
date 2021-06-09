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
 * Library of interface functions and constants for module braincert
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
define('BRAINCERT_STATUS_OK', 'ok');
define('BRAINCERT_STATUS_ERROR', 'error');
define('BRAINCERT_STATUS_PAST', 'Past');
define('BRAINCERT_STATUS_LIVE', 'Live');
define('BRAINCERT_STATUS_UPCOMING', 'Upcoming');
define('BRAINCERT_STATUS_NO_PRICE', 'No Price in this Class');
define('BRAINCERT_CURRENCY_INR', 'INR');
define('BRAINCERT_CURRENCY_INR_SYMBOL', '₹');
define('BRAINCERT_CURRENCY_EUR', 'EUR');
define('BRAINCERT_CURRENCY_EUR_SYMBOL', '€');
define('BRAINCERT_CURRENCY_AUD', 'AUD');
define('BRAINCERT_CURRENCY_AUD_SYMBOL', '$');
define('BRAINCERT_CURRENCY_CAD', 'CAD');
define('BRAINCERT_CURRENCY_CAD_SYMBOL', '$');
define('BRAINCERT_CURRENCY_GBP', 'GBP');
define('BRAINCERT_CURRENCY_GBP_SYMBOL', '£');
define('BRAINCERT_METHOD_DISCOUNT_UPDATE', 'updateDiscount');
define('BRAINCERT_METHOD_DISCOUNT_ADD', 'addDiscount');
define('BRAINCERT_METHOD_PRICE_UPDATE', 'updateprice');
define('BRAINCERT_METHOD_PRICE_ADD', 'addprice');
define('BRAINCERT_METHOD_CLASS_UPDATE', 'updateclass');
define('BRAINCERT_TASK_CLASS_LIST', 'listclass');
define('BRAINCERT_TASK_REMOVE_DISCOUNT', 'removediscount');
define('BRAINCERT_TASK_LIST_DISCOUNT', 'listdiscount');
define('BRAINCERT_TASK_ADD_SPECIALS', 'addSpecials');
define('BRAINCERT_TASK_REMOVE_PRICE', 'removeprice');
define('BRAINCERT_TASK_LIST_SCHEMES', 'listSchemes');
define('BRAINCERT_TASK_ADD_SCHEMES', 'addSchemes');
define('BRAINCERT_TASK_GET_CLASS_REPORT', 'getclassreport');
define('BRAINCERT_TASK_GET_CLASS_LAUNCH', 'getclasslaunch');
define('BRAINCERT_TASK_SCHEDULE', 'schedule');
define('BRAINCERT_TASK_REMOVE_CLASS', 'removeclass');
define('BRAINCERT_TASK_CANCEL_CLASS', 'cancelclass');
define('BRAINCERT_TASK_GET_CLASS_RECORDING', 'getclassrecording');
define('BRAINCERT_TASK_CHANGE_STATUS_RECORDING', 'changestatusrecording');
define('BRAINCERT_TASK_REMOVE_CLASS_RECORDING', 'removeclassrecording');
define('BRAINCERT_TASK_GET_PAYMENT_INFO', 'getPaymentInfo');
define('BRAINCERT_TASK_GET_PLAN', 'getplan');
define('BRAINCERT_NO_RECORDING_AVAILABLE', 'No video recording available');
define('BRAINCERT_TASK_GETSERVERS', 'getservers');
define('BRAINCERT_MODE_PERSONA_ADMIN', 1);
define('BRAINCERT_MODE_PERSONA_TEACHER', 2);
define('BRAINCERT_MODE_PERSONA_STUDENT', 3);


require_once('locallib.php');
global $defaulttimezone;
$defaulttimezone = array(
    '1' => 'Asia/Dubai',
    '2' => 'Asia/Baghdad',
    '3' => 'Canada/Atlantic',
    '4' => 'Australia/Darwin',
    '5' => 'Australia/Canberra',
    '6' => 'Atlantic/Azores',
    '7' => 'Canada/Saskatchewan',
    '8' => 'Atlantic/Cape_Verde',
    '9' => 'Asia/Baku',
    '10' => 'Australia/Adelaide',
    '11' => 'America/Belize',
    '12' => 'Asia/Dhaka',
    '13' => 'Europe/Belgrade',
    '14' => 'Europe/Sarajevo',
    '15' => 'Pacific/Guadalcanal',
    '16' => 'America/Chicago',
    '17' => 'Asia/Hong_Kong',
    '18' => 'Etc/GMT-12',
    '19' => 'Africa/Addis_Ababa',
    '20' => 'Australia/Brisbane',
    '21' => 'Europe/Bucharest',
    '22' => 'America/Sao_Paulo',
    '23' => 'America/New_York',
    '24' => 'Africa/Cairo',
    '25' => 'Asia/Yekaterinburg',
    '26' => 'Pacific/Fiji',
    '27' => 'Europe/Helsinki',
    '28' => 'Europe/London',
    '29' => 'America/Godthab',
    '30' => 'Africa/Abidjan',
    '31' => 'Europe/Minsk',
    '32' => 'Pacific/Honolulu',
    '33' => 'Asia/Kolkata',
    '34' => 'Asia/Tehran',
    '35' => 'Asia/Jerusalem',
    '37' => 'America/Cancun',
    '38' => 'America/Chihuahua',
    '39' => 'America/Noronha',
    '40' => 'America/Denver',
    '41' => 'Asia/Rangoon',
    '42' => 'Asia/Novosibirsk',
    '44' => 'Pacific/Auckland',
    '45' => 'Canada/Newfoundland',
    '46' => 'Asia/Irkutsk',
    '47' => 'Asia/Kabul',
    '48' => 'America/Anchorage',
    '49' => 'Asia/Riyadh',
    '50' => 'Asia/Krasnoyarsk',
    '51' => 'America/Santiago',
    '52' => 'America/Los_Angeles',
    '53' => 'Europe/Brussels',
    '54' => 'Europe/Moscow',
    '55' => 'America/Argentina/Buenos_Aires',
    '56' => 'America/Bogota',
    '57' => 'America/La_Paz',
    '58' => 'Pacific/Samoa',
    '59' => 'Asia/Bangkok',
    '60' => 'Asia/Kuala_Lumpur',
    '61' => 'Africa/Blantyre',
    '62' => 'Asia/Colombo',
    '63' => 'Asia/Taipei',
    '64' => 'Australia/Hobart',
    '65' => 'Asia/Tokyo',
    '67' => 'America/Indiana/Indianapolis',
    '68' => 'America/Phoenix',
    '69' => 'Asia/Vladivostok',
    '70' => 'Australia/Perth',
    '71' => 'Africa/Algiers',
    '72' => 'Europe/Amsterdam',
    '73' => 'Asia/Karachi',
    '74' => 'Pacific/Guam',
    '75' => 'Asia/Yakutsk',
    '76' => 'America/Caracas',
    '77' => 'Asia/Seoul',
    '83' => 'Asia/Amman',
    '84' => 'Asia/Beirut',
    '86' => 'Africa/Windhoek',
    '87' => 'Asia/Tbilisi',
    '88' => 'Asia/Baku',
    '89' => 'Indian/Mauritius',
    '90' => 'Asia/Karachi',
    '91' => 'Asia/Kathmandu',
    '94' => 'America/Argentina/Buenos_Aires',
    '95' => 'America/Montevideo',
    '96' => 'America/Manaus',
    '104' => 'America/Tijuana',
    '105' => 'America/New_York'
);

/**
 * Defines the features that are supported by braincert.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function braincert_supports($feature) {
    $feature = array(FEATURE_GROUPS, FEATURE_GROUPINGS, FEATURE_GROUPMEMBERSONLY, FEATURE_MOD_INTRO,
        FEATURE_COMPLETION_TRACKS_VIEWS, FEATURE_COMPLETION_HAS_RULES, FEATURE_GRADE_HAS_GRADE,
        FEATURE_GRADE_OUTCOMES, FEATURE_BACKUP_MOODLE2
    );
    if (in_array($feature, $feature)) {
        return true;
    }
    return null;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $braincert
 * @return bool|int
 */
function braincert_add_instance($braincert) {
    global $DB, $CFG, $defaulttimezone;

    $timezone = $defaulttimezone[$braincert->braincert_timezone];
    $startdate = date('Y-m-d', $braincert->start_date);
    $startdatetime = new DateTime($startdate . ' ' . $braincert->start_time, new DateTimeZone($timezone));
    $startdatetimestamp = $startdatetime->getTimestamp();

    $getschedule = braincert_get_scheduled_class($braincert, $startdatetimestamp);

    if ($getschedule['status'] == BRAINCERT_STATUS_OK) {
        $braincertclass = braincert_set_braincert_object($braincert, $timezone, $startdatetimestamp, $getschedule['class_id']);
        $bcid = $DB->insert_record("braincert", $braincertclass);
        if ($CFG->version >= 2017051500) {
            $completiontime = !empty($braincert->completionexpected) ? $braincert->completionexpected : null;
            \core_completion\api::update_completion_date_event(
                $braincert->coursemodule, 'braincert', $bcid, $completiontime
            );
        }
        return $bcid;
    } else {
        braincert_error_handler($getschedule['error']);
    }
}

/**
 *
 * @global object $OUTPUT
 * @global object $COURSE
 * @param string $message
 */
function braincert_error_handler($message = '') {
    global $OUTPUT, $COURSE;
    echo $OUTPUT->header();
    $notification = $message ? $message : get_string('unknownerror', 'braincert');
    echo $OUTPUT->notification($notification, 'notifyproblem');
    $continueurl = new moodle_url('/course/view.php', array('id' => $COURSE->id));
    $continuebutton = $OUTPUT->render(new single_button($continueurl, get_string('continue')));
    echo html_writer::tag('div', $continuebutton, array('class' => 'mdl-align'));
    echo $OUTPUT->footer();
    exit;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $braincert
 * @return bool
 */
function braincert_update_instance($braincert) {
    global $DB, $CFG, $defaulttimezone;

    $timezone = $defaulttimezone[$braincert->braincert_timezone];
    $braincertclass = $DB->get_record('braincert', array('id' => $braincert->instance), '*', MUST_EXIST);
    $classdata['task'] = BRAINCERT_TASK_CLASS_LIST;
    $classdata['search'] = preg_replace('/\s+/', '', $braincertclass->name);
    $getclassdetails = braincert_get_curl_info($classdata);

    if ($getclassdetails['status'] == BRAINCERT_STATUS_ERROR) {
        braincert_error_handler($getclassdetails['error']);
    }

    $startdate = date('Y-m-d', $braincert->start_date);
    $startdatetime = new DateTime($startdate . ' ' . $braincert->start_time, new DateTimeZone($timezone));
    $startdatetimestamp = $startdatetime->getTimestamp();

    foreach ($getclassdetails['classes'] as $getclassdetail) {
        if ($getclassdetail['id'] == $braincertclass->class_id) {
            if ($getclassdetail['status'] == BRAINCERT_STATUS_UPCOMING) {
                $getschedule = braincert_get_scheduled_class($braincert, $startdatetimestamp, $getclassdetail['id']);
            }
            if (
                isset($getschedule['status']) && ($getschedule['status'] == BRAINCERT_STATUS_OK) &&
                ($getschedule['method'] == BRAINCERT_METHOD_CLASS_UPDATE)
                ) {
                $braincertclass = braincert_set_braincert_object($braincert, $timezone,
                        $startdatetimestamp, $getschedule['class_id']);

                if ($CFG->version >= 2017051500) {
                    $completiontime = !empty($braincert->completionexpected) ?
                        $braincert->completionexpected : null;
                    \core_completion\api::update_completion_date_event(
                        $braincert->coursemodule, 'braincert', $braincertclass->id, $completiontime
                    );
                }
                return $DB->update_record("braincert", $braincertclass);
            } else {
                braincert_error_handler($getclassdetail['error']);
            }
        }
    }
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id
 * @return bool
 */
function braincert_delete_instance($id) {
    global $DB, $CFG;

    if (!$braincert = $DB->get_record("braincert", array("id" => $id))) {
        return false;
    }
    $result = true;

    if (isset($braincert->class_id)) {
        $removedata['task'] = BRAINCERT_TASK_REMOVE_CLASS;
        $removedata['cid'] = $braincert->class_id;

        $getremovestatus = braincert_get_curl_info($removedata);
        if ($getremovestatus['status'] == BRAINCERT_STATUS_OK) {
            echo get_string('braincert_class_removed', 'braincert');
        }
    }

    if ($CFG->version >= 2017051500) {
        $cm = get_coursemodule_from_instance('braincert', $id);
        \core_completion\api::update_completion_date_event($cm->id, 'braincert', $braincert->id, null);
    }
    if (!$DB->delete_records("braincert", array("id" => $braincert->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @param object $coursemodule
 * @return cached_cm_info|null
 */
function braincert_get_coursemodule_info($coursemodule) {
    global $DB;

    if ($braincert = $DB->get_record('braincert', array(
        'id' => $coursemodule->instance), 'id, name, intro, introformat')) {
        if (empty($braincert->name)) {
            // Braincert name missing, fix it.
            $braincert->name = "braincert{$braincert->id}";
            $DB->set_field('braincert', 'name', $braincert->name, array('id' => $braincert->id));
        }
        $info = new cached_cm_info();
        // No filtering hre because this info is cached and filtered later.
        $info->content = format_module_intro('braincert', $braincert, $coursemodule->id, false);
        $info->name = $braincert->name;
        return $info;
    } else {
        return null;
    }
}

/**
 * Returns all other caps used in module
 *
 * @return array
 */
function braincert_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

/**
 *
 * @param object $braincert
 * @param integer $startdatetimestamp
 * @param integer $classid
 * @return array $getschedule
 */
function braincert_get_scheduled_class($braincert, $startdatetimestamp, $classid = null) {
    $data['task'] = BRAINCERT_TASK_SCHEDULE;
    $data['title'] = urlencode($braincert->name);
    if ($classid) {
        $data['cid'] = $classid;
        $data['title'] = preg_replace('/\s+/', '', $braincert->name);
    }

    $data['timezone'] = $braincert->braincert_timezone;
    $data['date'] = date('Y-m-d', $startdatetimestamp);
    $data['start_time'] = strtoupper($braincert->start_time);
    $data['end_time'] = strtoupper($braincert->end_time);
    $data['ispaid'] = $braincert->class_type;
    if (($braincert->class_type == 1) && isset($braincert->currency)) {
        $data['currency'] = $braincert->currency;
    }
    $data['is_recurring'] = $braincert->is_recurring;

    if (($braincert->is_recurring == 1) && isset($braincert->class_repeats)) {
        $data['repeat'] = $braincert->class_repeats;
        $data['end_classes_count'] = $braincert->end_classes_count;
        if (($braincert->class_repeats == 6) && isset($braincert->weekdays)) {
            $data['weekdays'] = implode(",", $braincert->weekdays);
        }
    }
    $data['isBoard'] = $braincert->classroomtype;
    if ($braincert->change_language == 0) {
        $data['isLang'] = $braincert->bc_interface_language;
    } else {
        $data['isLang'] = 0;
    }
    $data['isRegion'] = $braincert->is_region;
    $data['isvideo'] = $braincert->isvideo;
    $data['isCorporate'] = $braincert->is_corporate;
    $data['isScreenshare'] = $braincert->screen_sharing;
    $data['isPrivateChat'] = $braincert->private_chat;
    $data['seat_attendees'] = $braincert->maxattendees;
    $data['record'] = $braincert->record_type;
    $data['isRecordingLayout'] = $braincert->recording_layout;
    return braincert_get_curl_info($data);
}

/**
 *
 * @param object $braincert
 * @param string $timezone
 * @param integer $startdatetimestamp
 * @param integer $classid
 * @return \stdClass $braincertclass
 */
function braincert_set_braincert_object($braincert, $timezone, $startdatetimestamp, $classid) {
    $braincertclass = new stdClass();
    if ($braincert->instance) {
        $braincertclass->id = $braincert->instance;
    }
    $braincertclass->course = $braincert->course;
    $braincertclass->name = $braincert->name;
    $braincertclass->class_id = $classid;
    $braincertclass->intro = $braincert->intro;
    $braincertclass->introformat = $braincert->introformat;
    $braincertclass->braincert_timezone = $braincert->braincert_timezone;
    $braincertclass->default_timezone = $timezone;
    $braincertclass->start_date = $startdatetimestamp;
    $braincertclass->start_time = $braincert->start_time;
    $braincertclass->end_time = $braincert->end_time;
    $braincertclass->is_region = $braincert->is_region;
    $braincertclass->is_recurring = $braincert->is_recurring;
    $braincertclass->class_repeats = '';
    if (isset($braincert->class_repeats)) {
        $braincertclass->class_repeats = $braincert->class_repeats;
    }
    $braincertclass->weekdays = '';
    if (isset($braincert->weekdays) && $braincert->class_repeats == 6) {
        $braincertclass->weekdays = implode(",", $braincert->weekdays);
    }

    $braincertclass->end_classes_count = $braincert->end_classes_count;
    $braincertclass->change_language = $braincert->change_language;
    $braincertclass->bc_interface_language = 0;
    if ($braincert->change_language == 0) {
        $braincertclass->bc_interface_language = $braincert->bc_interface_language;
    }
    $braincertclass->record_type = $braincert->record_type;
    $braincertclass->classroomtype = $braincert->classroomtype;
    $braincertclass->is_corporate = $braincert->is_corporate;
    $braincertclass->isvideo = $braincert->isvideo;
    $braincertclass->screen_sharing = $braincert->screen_sharing;
    $braincertclass->private_chat = $braincert->private_chat;
    $braincertclass->class_type = $braincert->class_type;
    $braincertclass->currency = '';
    if (isset($braincert->currency)) {
        $braincertclass->currency = $braincert->currency;
    }
    $braincertclass->maxattendees = $braincert->maxattendees;
    $braincertclass->groupingid = $braincert->groupingid;
    $braincertclass->timemodified = time();
    $braincertclass->recording_layout = $braincert->recording_layout;
    return $braincertclass;
}