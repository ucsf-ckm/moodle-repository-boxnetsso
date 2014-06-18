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
 *
 * @author Carson Tam <carson.tam@ucsf.edu>
 * @link http://library.ucsf.edu
 * @access public
 * @version 1.0
 * @copyright copyright ucsf.edu 2014
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Moodle general purpose functions
require_once('../../config.php');
require_once($CFG->libdir . '/moodlelib.php');

//get the simple html dom parser library...
require_once('simple_html_dom.php');

//get the current user email address
$current_user_email = $_GET['mail'];

//get the contents of the box.com login page...
$boxappurl = 'https://app.box.com';

$box_popup_html = file_get_contents($boxappurl . '/api/oauth2/authorize?client_id='.$_GET['client_id'].'&response_type='.$_GET['response_type'].'&redirect_uri='.$_GET['redirect_uri'].'&state='.$_GET['state'].'&scope='.$_GET['scope']);

//parse the contents with simple_html_dom.php
$html = str_get_html($box_popup_html);

//force the user email address
$html->getElementById("login")->setAttribute('value', $current_user_email);
$html->getElementById("login")->setAttribute('readonly','readonly');

//get rid of the password prompt and move it to the right
$html->getElementById("password")->parent()->setAttribute('style','display: none;');

//hide the 'sso switching links'
$html->find("div.sso_switch .sso_on",0)->setAttribute('style','display: none;');
$html->find("div.sso_switch .sso_off",0)->setAttribute('style','display: none;');

//hide the 'forgot password' link...
$html->find("div.additional_options div.mvl a",0)->setAttribute('style','display: none;');

// Fix redirect url (need to urlencode it.)
$form = $html->find("form.login_form",0);
$action_string = html_entity_decode($form->action);

$startpos = strpos($action_string, "&redirect_uri=") + strlen("&redirect_uri=");
$strlength = strrpos($action_string, "&state=") - $startpos;
$new_redirect_uri = urlencode(substr($action_string, $startpos, $strlength));

$startpos = strpos($action_string, "&state=") + strlen("&state=");
$strlength = strrpos($action_string, "&scope=") - $startpos;
$new_state = urlencode(substr($action_string, $startpos, $strlength));

$form->action = strstr($action_string, "&redirect_uri=", true) . "&redirect_uri=" . $new_redirect_uri . "&state=" . $new_state . strstr($action_string, "&scope=");

//output the newly-updated html
print $html;

//clean up (because of the memory leak issue at http://simplehtmldom.sourceforge.net/manual_faq.htm)
$html->clear();
unset($html);
