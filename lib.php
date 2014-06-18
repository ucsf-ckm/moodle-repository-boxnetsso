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
 * Box.net SSO client.
 *
 * @package core
 * @author Carson Tam <carson.tam@ucsf.edu>
 * @link http://library.ucsf.edu
 * @access public
 * @version 1.0
 * @copyright copyright ucsf.edu 2014
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/repository/boxnet/lib.php');
require_once($CFG->dirroot . '/repository/boxnetsso/boxssolib.php');

class repository_boxnetsso extends repository_boxnet {

  /** @var string boxappurl */
  protected $boxappurl;

  public function __construct($repositoryid, $context = SYSCONTEXTID, $options = array()) {
    parent::__construct($repositoryid, $context, $options);

    $clientid = get_config('boxnet', 'clientid');
    $clientsecret = get_config('boxnet', 'clientsecret');
    $this->boxappurl = get_config('boxnetsso', 'customboxurl');
    if (empty($boxappurl)) {
      $this->boxappurl = 'https://app.box.com';
    }

    $returnurl = new moodle_url('/repository/repository_callback.php');
    $returnurl->param('callback', 'yes');
    $returnurl->param('repo_id', $this->id);
    $returnurl->param('sesskey', sesskey());

    $this->boxnetclient = new boxnetsso_client($clientid, $clientsecret, $returnurl, '');
  }


  /**
   * Get file listing
   *
   * @param string $path
   * @param string $page
   * @return mixed
   */
  public function get_listing($fullpath = '', $page = ''){
    global $OUTPUT;

    $ret = parent::get_listing($fullpath, $page);
    $ret['manage'] = $this->boxappurl . '/login/sso';

    return $ret;
  }

  /**
   * Return login form
   *
   * @return array
   */
  public function print_login(){
    global $USER;
    $url = $this->boxnetclient->get_login_url();
    $url->param('mail', $USER->email);   // passing the user's email address to the sso login url.
    if ($this->options['ajax']) {
      $ret = array();
      $popup_btn = new stdClass();
      $popup_btn->type = 'popup';
      $popup_btn->url = $url->out(false);
      $ret['login'] = array($popup_btn);
      return $ret;
    } else {
      echo html_writer::link($url, get_string('login', 'repository'), array('target' => '_blank'));
    }
  }

  /**
   * Names of the plugin settings
   *
   * @return array
   */
  public static function get_type_option_names() {
    $arr = parent::get_type_option_names();
    $arr[] = 'customboxurl';

    // return $arr;
    return array('clientid', 'clientsecret', 'pluginname', 'customboxurl');
  }

  /**
   * Add Plugin settings input to Moodle form
   *
   * @param moodleform $mform
   * @param string $classname
   */
  public static function type_config_form($mform, $classname = 'repository') {
    global $CFG;
    parent::type_config_form($mform);

    $mform->addElement('text', 'customboxurl', get_string('customboxurl', 'repository_boxnetsso'),
		       array('size' => '40'));
    $mform->setType('customboxurl', PARAM_URL);

    $mform->addElement('static', 'customboxurl_intro', '', get_string('customboxurl_intro', 'repository_boxnetsso'));
  }

}