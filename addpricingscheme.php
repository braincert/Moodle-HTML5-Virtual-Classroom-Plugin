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
 * Add the pricing scheme for paid class.
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->libdir.'/formslib.php');
require_once('locallib.php');


$bcid = required_param('bcid', PARAM_INT);   // Virtual class.
$pid = optional_param('pid', 0, PARAM_INT); // Price ID.
$action = optional_param('action', 'edit', PARAM_TEXT);

$PAGE->set_url('/mod/braincert/addpricingscheme.php', array('bcid' => $bcid));

$braincertrec = $DB->get_record('braincert', array('class_id' => $bcid));
if (!$course = $DB->get_record('course', array('id' => $braincertrec->course))) {
    print_error('invalidcourseid');
}

require_login($course);
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add(get_string('pluginname', 'braincert'));
$addprice = get_string('addprice', 'braincert');
$PAGE->navbar->add($addprice);

$PAGE->requires->css('/mod/braincert/css/styles.css', true);

if ($action == 'delete') {
    $data['task']      = 'removeprice';
    $data['id']        = $pid;
    $removepricescheme = braincert_get_curl_info($data);
    if ($removepricescheme['status'] == "ok") {
        echo "Removed Successfully.";
        redirect(new moodle_url('/mod/braincert/addpricingscheme.php?bcid='.$bcid));
    } else {
        echo $removepricescheme['error'];
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('addpricingscheme', 'braincert'));

$pricelistdata['task']     = 'listSchemes';
$pricelistdata['class_id'] = $bcid;
$pricelists = braincert_get_curl_info($pricelistdata);

/**
 * class add addpricingscheme_form form
 * @copyright Dualcube (https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class addpricingscheme_form extends moodleform {
    /**
     * Define add discount form
     */
    public function definition() {
        global $CFG, $DB, $bcid, $action, $pid, $pricelists;

        $defaultprice      = '';
        $defaultschemeday   = '';
        $defaultaccesstype  = 0;
        $defaultnumbertimes = '';
        if ($action == 'edit') {
            if (!empty($pricelists)) {
                if (!isset($pricelists['Price'])) {
                    foreach ($pricelists as $pricelist) {
                        if (isset($pricelist['id'])) {
                            if ($pricelist['id'] == $pid) {
                                $defaultprice      = $pricelist['scheme_price'];
                                $defaultschemeday   = $pricelist['scheme_days'];
                                $defaultaccesstype  = $pricelist['times'];
                                $defaultnumbertimes = $pricelist['numbertimes'];
                            }
                        }
                    }
                } else if (isset($pricelists['status']) && ($pricelists['status'] == 'error')) {
                    echo $pricelists['error'];
                }
            }
        }

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'pid', $pid);
        $mform->setType('pid', PARAM_INT);

        $mform->addElement('text', 'price', get_string('price', 'braincert'));
        $mform->setType('price', PARAM_INT);
        $mform->addRule('price', null, 'required', null, 'client');
        $mform->addRule('price', '', 'numeric', null, 'client');
        $mform->setDefault('price', $defaultprice);

        $mform->addElement('text', 'schemedays', get_string('schemedays', 'braincert'));
        $mform->setType('schemedays', PARAM_INT);
        $mform->addRule('schemedays', null, 'required', null, 'client');
        $mform->addRule('schemedays', '', 'numeric', null, 'client');
        $mform->setDefault('schemedays', $defaultschemeday);

        $accesstype = array();
        $accesstype[] = $mform->createElement('radio', 'accesstype', '', get_string('unlimited', 'braincert'), 0);
        $accesstype[] = $mform->createElement('radio', 'accesstype', '', get_string('limited', 'braincert'), 1);
        $mform->addGroup($accesstype, 'access_type', get_string('accesstype', 'braincert'), array(' '), false);
        $mform->setDefault('accesstype', $defaultaccesstype);

        $mform->addElement('text', 'numbertimes', get_string('numbertimes', 'braincert'));
        $mform->setType('numbertimes', PARAM_INT);
        $mform->addRule('numbertimes', '', 'numeric', null, 'client');
        $mform->disabledIf('numbertimes', 'accesstype', 'checked', 0);
        $mform->setDefault('numbertimes', $defaultnumbertimes);

        $this->add_action_buttons();
    }

    /**
     * validation check
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        return array();
    }
}

$mform = new addpricingscheme_form($CFG->wwwroot.'/mod/braincert/addpricingscheme.php?bcid='.$bcid);

if ($pricingscheme = $mform->get_data()) {
    $data['task']        = 'addSchemes';
    $data['price']       = $pricingscheme->price;
    $data['scheme_days'] = $pricingscheme->schemedays;
    $data['times']       = $pricingscheme->accesstype;
    $data['class_id']    = $bcid;
    if (isset($pricingscheme->numbertimes) && ($pricingscheme->accesstype == 1)) {
        $data['numbertimes'] = $pricingscheme->numbertimes;
    }
    if ($pricingscheme->pid > 0) {
        $data['id']     = $pricingscheme->pid;
    }
    $getscheme = braincert_get_curl_info($data);
    if ($getscheme['status'] == "ok") {
        if ($getscheme['method'] == "updateprice") {
            echo "Scheme Updated Successfully.";
        } else if ($getscheme['method'] == "addprice") {
            echo "Scheme Added Successfully.";
        }
    } else {
        echo $getscheme['error'];
    }
    $mform->display();
} else {
    $mform->display();
}

$pricelistdata['task']     = 'listSchemes';
$pricelistdata['class_id'] = $bcid;
$pricelists = braincert_get_curl_info($pricelistdata);

$table = new html_table();
$table->head = array ();
$table->head[] = 'Price ID';
$table->head[] = 'Price';
$table->head[] = 'Scheme days';
$table->head[] = 'Access type';
$table->head[] = 'Numbertimes';
$table->head[] = 'Actions';

if (!empty($pricelists)) {
    if (isset($pricelists['Price'])) {
        echo $pricelists['Price'];
    } else if (isset($pricelists['status']) && ($pricelists['status'] == 'error')) {
        echo $pricelists['error'];
    } else {
        foreach ($pricelists as $pricelist) {
            $row = array ();
            $row[] = $pricelist['id'];
            $row[] = $pricelist['scheme_price'];
            $row[] = $pricelist['scheme_days'];
            if ($pricelist['times'] == 0) {
                $row[] = 'unlimited';
            } else {
                $row[] = 'limited';
            }
            $row[] = $pricelist['numbertimes'];
            $row[] = '<a href="'.$CFG->wwwroot.'/mod/braincert/addpricingscheme.php?action=edit&bcid='.$bcid.'&pid='
                     .$pricelist['id'].'" value="edit-'.$pricelist['id'].'">'.get_string('edit', 'braincert').'</a>'
                     .' '.'<a href="'.$CFG->wwwroot.'/mod/braincert/addpricingscheme.php?action=delete&bcid='.$bcid
                     .'&pid='.$pricelist['id'].'" value="delete-'.$pricelist['id'].'">'.get_string('delete', 'braincert').'</a>';
            $table->data[] = $row;
        }
    }
}

if (!empty($table)) {
    echo html_writer::start_tag('div', array('class' => 'no-overflow display-table'));
    echo html_writer::table($table);
    echo html_writer::end_tag('div');
}
echo $OUTPUT->footer();