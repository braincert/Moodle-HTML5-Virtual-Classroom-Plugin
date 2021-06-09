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
 * The main braincert configuration form.
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

define('BRAINCERT_MINIMUM_DURATION', 30);
define('BRAINCERT_MAXIMUM_DURATION', 600);
define('BRAINCERT_MAXIMUM_ATTENDEES', 300);

require_once($CFG->dirroot . '/course/moodleform_mod.php');
$PAGE->requires->css('/mod/braincert/css/styles.css', true);

/**
 * class braincert mod form
 * @copyright Dualcube (https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_braincert_mod_form extends moodleform_mod
{

    /**
     * Define add discount form
     */
    public function definition() {
        global $PAGE, $CFG;

        if ($CFG->version >= 2016120500) {
            $PAGE->force_settings_menu();
        }

        $bctimezoneoptions = $this->timezone_options();
        $bcregionoptions = $this->region_options();

        $bcrepeatoptions = $this->weekly_options();
        $bcweekdaysoptions = array('1' => get_string('weeksunday', 'braincert'), '2' => get_string('weekmonday', 'braincert'),
            '3' => get_string('weektuesday', 'braincert'), '4' => get_string('weekwednesday', 'braincert'),
            '5' => get_string('weekthursday', 'braincert'), '6' => get_string('weekfriday', 'braincert'),
            '7' => get_string('weeksaturday', 'braincert'));

        $bctimeoptions = $this->time_options();
        $dtoption = array('startyear' => date("Y", strtotime("-1 year")),
            'stopyear' => date("Y", strtotime("+10 years")), 'timezone' => 99);

        $mform = $this->_form;
        // Adding the "general" fieldset.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('title', 'braincert'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_ALPHANUMEXT);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'title', 'braincert');

        $mform->addElement('hidden', 'lasteditorid', "");
        $mform->setType('lasteditorid', PARAM_INT);

        if ($CFG->version >= 2015051100) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor(true, get_string('description', 'braincert'));
        }

        // Adding the required braincert settings, spreeading all them into this fieldset.
        // or adding more fieldsets ('header' elements) if needed for better logic.
        $mform->addElement('header', 'braincertdatetimesetting', get_string('braincertdatetimesetting', 'braincert'));

        $mform->addElement('select', 'braincert_timezone', get_string('bc_timezone', 'braincert'), $bctimezoneoptions);
        $mform->addHelpButton('braincert_timezone', 'bc_timezone', 'braincert');
        $mform->addRule('braincert_timezone', get_string('timezone_required', 'braincert'), 'required', null, 'client', true);
        $mform->setDefault('braincert_timezone', 28);

        $mform->addElement('date_selector', 'start_date', get_string('start_date', 'braincert'), $dtoption);
        $mform->addHelpButton('start_date', 'start_date', 'braincert');

        $mform->addElement('select', 'start_time', get_string('bc_starttime', 'braincert'), $bctimeoptions);
        $mform->addHelpButton('start_time', 'bc_starttime', 'braincert');

        $mform->addElement('select', 'end_time', get_string('bc_endtime', 'braincert'), $bctimeoptions);
        $mform->addHelpButton('end_time', 'bc_endtime', 'braincert');

        // Adding the rest of braincert settings.
        $mform->addElement('header', 'braincertclasssettings', get_string('braincertclasssettings', 'braincert'));

        $mform->addElement('select', 'is_region', get_string('setregion', 'braincert'), $bcregionoptions);
        $mform->addHelpButton('is_region', 'setregion', 'braincert');
        $mform->addRule('is_region', get_string('region_required', 'braincert'), 'required', null, 'client', true);
        // For Recurring Class.
        $checkrecurring = array();
        $checkrecurring[] = $mform->createElement('radio', 'is_recurring', '', get_string('yes', 'braincert'), 1);
        $checkrecurring[] = $mform->createElement('radio', 'is_recurring', '', get_string('no', 'braincert'), 0);
        $mform->addGroup($checkrecurring, 'recurring_class', get_string('recurring_class', 'braincert'), array(' '), false);
        $mform->addHelpButton('recurring_class', 'recurring_class', 'braincert');
        $mform->setDefault('is_recurring', 0);

        $mform->addElement('select', 'class_repeats', get_string('repeat_class', 'braincert'), $bcrepeatoptions);
        $mform->disabledIf('class_repeats', 'is_recurring', 'checked', 0);

        $mform->addElement('text', 'end_classes_count', get_string('end_classes', 'braincert'), array('size' => '10'));
        $mform->setType('end_classes_count', PARAM_INT);
        $mform->disabledIf('end_classes_count', 'is_recurring', 'checked', 0);
        $mform->setDefault('end_classes_count', 10);
        $mform->addRule('end_classes_count', get_string('max_number', 'braincert'), 'numeric', null, 'client');

        $mform->addElement('select', 'weekdays', get_string('weekday', 'braincert'), $bcweekdaysoptions);
        $mform->disabledIf('weekdays', 'class_repeats', 'neq', 6);
        $mform->getElement('weekdays')->setMultiple(true);

        // Other form fields.
        $this->other_form_fields($mform);

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();
        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }

    private function lang_video_record_field(&$mform) {
        global $CFG;
        // Change Langanguage.
        $allowtochangelang = array();
        $allowtochangelang[] = $mform->createElement('radio', 'change_language', '', get_string('yes', 'braincert'), 1);
        $allowtochangelang[] = $mform->createElement('radio', 'change_language', '', get_string('no', 'braincert'), 0);
        $mform->addGroup(
            $allowtochangelang, 'allow_to_change_lang', get_string('change_language', 'braincert'), array(' '), false
        );
        $mform->addHelpButton('allow_to_change_lang', 'change_language', 'braincert');
        $mform->setDefault('change_language', 1);

        $mform->addElement(
            'select', 'bc_interface_language', get_string('set_language', 'braincert'), $this->lang_options()
        );

        $mform->addHelpButton('bc_interface_language', 'set_language', 'braincert');
        $mform->disabledIf('bc_interface_language', 'change_language', 'checked', 1);
        $mform->setDefault('bc_interface_language', 11);

                // Class Record Types.
        $recordclass = array();
        $recordclass[] = $mform->createElement('radio', 'record_type', '', get_string('no', 'braincert'), 0);
        $recordclass[] = $mform->createElement(
            'radio', 'record_type', '', get_string('record_manually', 'braincert'), 1
        );
        $recordclass[] = $mform->createElement(
            'radio', 'record_type', '', get_string('record_automatically', 'braincert'), 2
        );
        $recordclass[] = $mform->createElement(
            'radio', 'record_type', '', get_string('record_disable_rec_btn', 'braincert'), 3
        );
        $mform->addGroup($recordclass, 'record_class', get_string('record_class', 'braincert'), array(' '), false);
        $mform->addHelpButton('record_class', 'record_class', 'braincert');
        $mform->setDefault('record_type', 0);

        $viewoptions = array(get_string('standard_view', 'braincert'), get_string('enhanced_view', 'braincert'));
        $mform->addElement('select', 'recording_layout', get_string('recording_layout', 'braincert'), $viewoptions);
        $mform->addHelpButton('recording_layout', 'recording_layout', 'braincert');
        $mform->setDefault('recording_layout', 0);
        $mform->setType('recording_layout', PARAM_INTEGER);
        if ($CFG->version >= 2017111300) {
            $mform->hideIf('recording_layout', 'record_type', 'checked', 0);
        } else {
            $mform->disabledIf('recording_layout', 'record_type', 'checked', 0);
        }
    }

    private function other_form_fields(&$mform) {
        $this->lang_video_record_field($mform);

        // Video Delivery.
        $videodelivery = array();
        $videodelivery[] = $mform->createElement(
            'radio', 'isvideo', '', get_string('singlevideofile', 'braincert'), 1
        );
        $videodelivery[] = $mform->createElement(
            'radio', 'isvideo', '', get_string('multiplevideofile', 'braincert'), 0
        );
        $mform->addGroup(
            $videodelivery, 'videodelivery_group', get_string('isvideo', 'braincert'), array(' '), false
        );
        $mform->addHelpButton('videodelivery_group', 'videodelivery_group', 'braincert');
        $mform->setDefault('isvideo', 1);

        $classroomtype = array();
        $classroomtype[] = $mform->createElement(
            'radio', 'classroomtype', '', get_string('classroom_type_zero', 'braincert'), 0
        );
        $classroomtype[] = $mform->createElement(
            'radio', 'classroomtype', '', get_string('classroom_type_one', 'braincert'), 1
        );
        $classroomtype[] = $mform->createElement(
            'radio', 'classroomtype', '', get_string('classroom_type_two', 'braincert'), 2
        );
        $mform->addGroup(
            $classroomtype, 'classroom_type', get_string('classroom_type', 'braincert'), array(' '), false
        );
        $mform->addHelpButton('classroom_type', 'classroom_type', 'braincert');
        $mform->setDefault('classroomtype', 0);

        $iscorporate = array();
        $iscorporate[] = $mform->createElement('radio', 'is_corporate', '', get_string('yes', 'braincert'), 1);
        $iscorporate[] = $mform->createElement('radio', 'is_corporate', '', get_string('no', 'braincert'), 0);
        $mform->addGroup(
            $iscorporate, 'enable_webcam_microphone', get_string('is_corporate', 'braincert'), array(' '), false
        );
        $mform->addHelpButton('enable_webcam_microphone', 'is_corporate', 'braincert');
        $mform->setDefault('is_corporate', 0);

        $isscreenshare = array();
        $isscreenshare[] = $mform->createElement('radio', 'screen_sharing', '', get_string('yes', 'braincert'), 1);
        $isscreenshare[] = $mform->createElement('radio', 'screen_sharing', '', get_string('no', 'braincert'), 0);
        $mform->addGroup(
            $isscreenshare, 'enable_screen_sharing', get_string('screen_sharing', 'braincert'), array(' '), false
        );
        $mform->addHelpButton('enable_screen_sharing', 'screen_sharing', 'braincert');
        $mform->setDefault('screen_sharing', 1);

        $isprivatechat = array();
        $isprivatechat[] = $mform->createElement('radio', 'private_chat', '', get_string('yes', 'braincert'), 0);
        $isprivatechat[] = $mform->createElement('radio', 'private_chat', '', get_string('no', 'braincert'), 1);
        $mform->addGroup(
            $isprivatechat, 'enable_private_chat', get_string('private_chat', 'braincert'), array(' '), false
        );
        $mform->setDefault('private_chat', 1);
        $mform->addHelpButton('enable_private_chat', 'private_chat', 'braincert');

        $classtype = array();
        $classtype[] = $mform->createElement('radio', 'class_type', '', get_string('free', 'braincert'), 0);
        $classtype[] = $mform->createElement('radio', 'class_type', '', get_string('paid', 'braincert'), 1);
        $mform->addGroup($classtype, 'type_of_class', get_string('class_type', 'braincert'), array(' '), false);
        $mform->addHelpButton('type_of_class', 'class_type', 'braincert');
        $mform->setDefault('class_type', 0);

        $bccurrencyoptions = array(
            "aud" => get_string("currencyaud", "braincert"),
            "cad" => get_string("currencycad", "braincert"),
            "eur" => get_string("currencyeur", "braincert"),
            "gbp" => get_string("currencygbp", "braincert"),
            "nzd" => get_string("currencynzd", "braincert"),
            "usd" => get_string("currencyusd", "braincert"),
        );
        $mform->addElement('select', 'currency', get_string('currency', 'braincert'), $bccurrencyoptions);
        $mform->disabledIf('currency', 'class_type', 'checked', 0);

        $mform->addElement('text', 'maxattendees', get_string('max_attendees', 'braincert'));
        $mform->setType('maxattendees', PARAM_INT);
        $mform->addRule('maxattendees', get_string('max_number', 'braincert'), 'numeric', null, 'client');
        $mform->setDefault('maxattendees', 25);
        $mform->addHelpButton('maxattendees', 'max_attendees', 'braincert');
    }

    private function timezone_options() {
        return array(
            "28" => get_string("timezone28", "braincert"),
            "30" => get_string("timezone30", "braincert"),
            "72" => get_string("timezone72", "braincert"),
            "53" => get_string("timezone53", "braincert"),
            "14" => get_string("timezone14", "braincert"),
            "71" => get_string("timezone71", "braincert"),
            "83" => get_string("timezone83", "braincert"),
            "84" => get_string("timezone84", "braincert"),
            "24" => get_string("timezone24", "braincert"),
            "61" => get_string("timezone61", "braincert"),
            "27" => get_string("timezone27", "braincert"),
            "35" => get_string("timezone35", "braincert"),
            "21" => get_string("timezone21", "braincert"),
            "86" => get_string("timezone86", "braincert"),
            "31" => get_string("timezone31", "braincert"),
            "2" => get_string("timezone2", "braincert"),
            "49" => get_string("timezone49", "braincert"),
            "54" => get_string("timezone54", "braincert"),
            "19" => get_string("timezone19", "braincert"),
            "87" => get_string("timezone87", "braincert"),
            "34" => get_string("timezone34", "braincert"),
            "1" => get_string("timezone1", "braincert"),
            "88" => get_string("timezone88", "braincert"),
            "9" => get_string("timezone9", "braincert"),
            "89" => get_string("timezone89", "braincert"),
            "47" => get_string("timezone47", "braincert"),
            "25" => get_string("timezone25", "braincert"),
            "90" => get_string("timezone90", "braincert"),
            "73" => get_string("timezone73", "braincert"),
            "33" => get_string("timezone33", "braincert"),
            "62" => get_string("timezone62", "braincert"),
            "91" => get_string("timezone91", "braincert"),
            "42" => get_string("timezone42", "braincert"),
            "12" => get_string("timezone12", "braincert"),
            "41" => get_string("timezone41", "braincert"),
            "59" => get_string("timezone59", "braincert"),
            "50" => get_string("timezone50", "braincert"),
            "17" => get_string("timezone17", "braincert"),
            "46" => get_string("timezone46", "braincert"),
            "60" => get_string("timezone60", "braincert"),
            "70" => get_string("timezone70", "braincert"),
            "63" => get_string("timezone63", "braincert"),
            "65" => get_string("timezone65", "braincert"),
            "77" => get_string("timezone77", "braincert"),
            "75" => get_string("timezone75", "braincert"),
            "10" => get_string("timezone10", "braincert"),
            "4" => get_string("timezone4", "braincert"),
            "20" => get_string("timezone20", "braincert"),
            "5" => get_string("timezone5", "braincert"),
            "74" => get_string("timezone74", "braincert"),
            "64" => get_string("timezone64", "braincert"),
            "69" => get_string("timezone69", "braincert"),
            "15" => get_string("timezone15", "braincert"),
            "44" => get_string("timezone44", "braincert"),
            "26" => get_string("timezone26", "braincert"),
            "6" => get_string("timezone6", "braincert"),
            "8" => get_string("timezone8", "braincert"),
            "39" => get_string("timezone39", "braincert"),
            "22" => get_string("timezone22", "braincert"),
            "94" => get_string("timezone94", "braincert"),
            "55" => get_string("timezone55", "braincert"),
            "29" => get_string("timezone29", "braincert"),
            "95" => get_string("timezone95", "braincert"),
            "45" => get_string("timezone45", "braincert"),
            "3" => get_string("timezone3", "braincert"),
            "57" => get_string("timezone57", "braincert"),
            "96" => get_string("timezone96", "braincert"),
            "51" => get_string("timezone51", "braincert"),
            "76" => get_string("timezone76", "braincert"),
            "56" => get_string("timezone56", "braincert"),
            "23" => get_string("timezone23", "braincert"),
            "67" => get_string("timezone67", "braincert"),
            "11" => get_string("timezone11", "braincert"),
            "16" => get_string("timezone16", "braincert"),
            "37" => get_string("timezone37", "braincert"),
            "7" => get_string("timezone7", "braincert"),
            "68" => get_string("timezone68", "braincert"),
            "38" => get_string("timezone38", "braincert"),
            "40" => get_string("timezone40", "braincert"),
            "52" => get_string("timezone52", "braincert"),
            "104" => get_string("timezone104", "braincert"),
            "48" => get_string("timezone48", "braincert"),
            "32" => get_string("timezone32", "braincert"),
            "58" => get_string("timezone58", "braincert"),
            "18" => get_string("timezone18", "braincert"),
            "105" => get_string("timezone105", "braincert"),
            "13" => get_string("timezone13", "braincert"),
        );
    }

    private function region_options() {

        //function braincert_get_regions($braincert) {
    $data['task'] = BRAINCERT_TASK_GETSERVERS;
    $datacenters = braincert_get_curl_info($data);
    $final_result = array();
    foreach ($datacenters as $datacenter) {
       $final_result[$datacenter['id']] = $datacenter['name'];
    }

    return $final_result;
    }

    private function lang_options() {
        return array(
            "1" => get_string("cnarabic", "braincert"),
            "2" => get_string("cnbosnian", "braincert"),
            "3" => get_string("cnbulgarian", "braincert"),
            "4" => get_string("cncatalan", "braincert"),
            "5" => get_string("cnchinese-simplified", "braincert"),
            "6" => get_string("cnchinese-traditional", "braincert"),
            "7" => get_string("cncroatian", "braincert"),
            "8" => get_string("cnczech", "braincert"),
            "9" => get_string("cndanish", "braincert"),
            "10" => get_string("cndutch", "braincert"),
            "11" => get_string("cnenglish", "braincert"),
            "12" => get_string("cnestonian", "braincert"),
            "13" => get_string("cnfinnish", "braincert"),
            "14" => get_string("cnfrench", "braincert"),
            "15" => get_string("cngerman", "braincert"),
            "16" => get_string("cngreek", "braincert"),
            "17" => get_string("cnhaitian-creole", "braincert"),
            "18" => get_string("cnhebrew", "braincert"),
            "19" => get_string("cnhindi", "braincert"),
            "20" => get_string("cnhmong-daw", "braincert"),
            "21" => get_string("cnhungarian", "braincert"),
            "22" => get_string("cnindonesian", "braincert"),
            "23" => get_string("cnitalian", "braincert"),
            "24" => get_string("cnjapanese", "braincert"),
            "25" => get_string("cnkiswahili", "braincert"),
            "26" => get_string("cnklingon", "braincert"),
            "27" => get_string("cnkorean", "braincert"),
            "28" => get_string("cnlithuanian", "braincert"),
            "29" => get_string("cnmalayalam", "braincert"),
            "30" => get_string("cnmalay", "braincert"),
            "31" => get_string("cnmaltese", "braincert"),
            "32" => get_string("cnnorwegian-bokma", "braincert"),
            "33" => get_string("cnpersian", "braincert"),
            "34" => get_string("cnpolish", "braincert"),
            "35" => get_string("cnportuguese", "braincert"),
            "36" => get_string("cnromanian", "braincert"),
            "37" => get_string("cnrussian", "braincert"),
            "38" => get_string("cnserbian", "braincert"),
            "39" => get_string("cnslovak", "braincert"),
            "40" => get_string("cnslovenian", "braincert"),
            "41" => get_string("cnspanish", "braincert"),
            "42" => get_string("cnswedish", "braincert"),
            "43" => get_string("cntamil", "braincert"),
            "44" => get_string("cntelugu", "braincert"),
            "45" => get_string("cnthai", "braincert"),
            "46" => get_string("cnturkish", "braincert"),
            "47" => get_string("cnukrainian", "braincert"),
            "48" => get_string("cnurdu", "braincert"),
            "49" => get_string("cnvietnamese", "braincert"),
            "50" => get_string("cnwelsh", "braincert")
        );
    }

    private function weekly_options() {
        return array(
            '1' => get_string('recurrance1', 'braincert'),
            '2' => get_string('recurrance2', 'braincert'),
            '3' => get_string('recurrance3', 'braincert'),
            '4' => get_string('recurrance4', 'braincert'),
            '5' => get_string('recurrance5', 'braincert'),
            '6' => get_string('recurrance6', 'braincert')
        );
    }

    private function time_options() {
        return array(
            '12:00am' => '12:00'.get_string('am', 'braincert'),
            '12:30am' => '12:30'.get_string('am', 'braincert'),
            '1:00am' => '1:00'.get_string('am', 'braincert'),
            '1:30am' => '1:30'.get_string('am', 'braincert'),
            '2:00am' => '2:00'.get_string('am', 'braincert'),
            '2:30am' => '2:30'.get_string('am', 'braincert'),
            '3:00am' => '3:00'.get_string('am', 'braincert'),
            '3:30am' => '3:30'.get_string('am', 'braincert'),
            '4:00am' => '4:00'.get_string('am', 'braincert'),
            '4:30am' => '4:30'.get_string('am', 'braincert'),
            '5:00am' => '5:00'.get_string('am', 'braincert'),
            '5:30am' => '5:30'.get_string('am', 'braincert'),
            '6:00am' => '6:00'.get_string('am', 'braincert'),
            '6:30am' => '6:30'.get_string('am', 'braincert'),
            '7:00am' => '7:00'.get_string('am', 'braincert'),
            '7:30am' => '7:30'.get_string('am', 'braincert'),
            '8:00am' => '8:00'.get_string('am', 'braincert'),
            '8:30am' => '8:30'.get_string('am', 'braincert'),
            '9:00am' => '9:00'.get_string('am', 'braincert'),
            '9:30am' => '9:30'.get_string('am', 'braincert'),
            '10:00am' => '10:00'.get_string('am', 'braincert'),
            '10:30am' => '10:30'.get_string('am', 'braincert'),
            '11:00am' => '11:00'.get_string('am', 'braincert'),
            '11:30am' => '11:30'.get_string('am', 'braincert'),
            '12:00pm' => '12:00'.get_string('pm', 'braincert'),
            '12:30pm' => '12:30'.get_string('pm', 'braincert'),
            '1:00pm' => '1:00'.get_string('pm', 'braincert'),
            '1:30pm' => '1:30'.get_string('pm', 'braincert'),
            '2:00pm' => '2:00'.get_string('pm', 'braincert'),
            '2:30pm' => '2:30'.get_string('pm', 'braincert'),
            '3:00pm' => '3:00'.get_string('pm', 'braincert'),
            '3:30pm' => '3:30'.get_string('pm', 'braincert'),
            '4:00pm' => '4:00'.get_string('pm', 'braincert'),
            '4:30pm' => '4:30'.get_string('pm', 'braincert'),
            '5:00pm' => '5:00'.get_string('pm', 'braincert'),
            '5:30pm' => '5:30'.get_string('pm', 'braincert'),
            '6:00pm' => '6:00'.get_string('pm', 'braincert'),
            '6:30pm' => '6:30'.get_string('pm', 'braincert'),
            '7:00pm' => '7:00'.get_string('pm', 'braincert'),
            '7:30pm' => '7:30'.get_string('pm', 'braincert'),
            '8:00pm' => '8:00'.get_string('pm', 'braincert'),
            '8:30pm' => '8:30'.get_string('pm', 'braincert'),
            '9:00pm' => '9:00'.get_string('pm', 'braincert'),
            '9:30pm' => '9:30'.get_string('pm', 'braincert'),
            '10:00pm' => '10:00'.get_string('pm', 'braincert'),
            '10:30pm' => '10:30'.get_string('pm', 'braincert'),
            '11:00pm' => '11:00'.get_string('pm', 'braincert'),
            '11:30pm' => '11:30'.get_string('pm', 'braincert'),
        );
    }

    /**
     * Validates the data input from various input elements.
     *
     * @param string $data
     * @param string $files
     *
     * @return string $errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        global $defaulttimezone;

        $timezone = $data['braincert_timezone'];  // Timezone.

        $now = new DateTime();
        $now->setTimezone(new DateTimeZone($defaulttimezone[$timezone]));
        $currenttime = $now->getTimestamp();

        $strttime11 = date('H:i:s', strtotime($data['start_time']));
        $endtime11 = date('H:i:s', strtotime($data['end_time']));
        $startdate = date('Y-m-d', $data['start_date']);
        if ($endtime11 < $strttime11) {
            $enddate = date("Y-m-d", strtotime("+1 day", strtotime(date('Y-m-d', $data['start_date']))));
        } else {
            $enddate = date('Y-m-d', $data['start_date']);
        }

        $enddatetime = new DateTime($enddate . ' ' . $data['end_time'], new DateTimeZone($defaulttimezone[$timezone]));
        $enddatetimestamp = $enddatetime->getTimestamp();

        if ($currenttime > $enddatetimestamp) {
            $errors['start_date'] = get_string('wrongtime', 'braincert');
        }

        $starttime = new DateTime($startdate . ' ' . $strttime11);
        $endtime = new DateTime($enddate . ' ' . $endtime11);
        $interval = $starttime->diff($endtime);
        $durationinmin = ($interval->h * 60) + $interval->i;

        if ((BRAINCERT_MAXIMUM_DURATION < $durationinmin) || (BRAINCERT_MINIMUM_DURATION > $durationinmin)) {
            $errors['end_time'] = get_string('wrongduration', 'braincert');
        }

        if ($data['end_classes_count'] <= 2) {
            $errors['end_classes_count'] = get_string('wrongclasscount', 'braincert');
        }

        return $errors;
    }
}
