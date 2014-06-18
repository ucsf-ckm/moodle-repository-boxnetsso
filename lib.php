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

    $clientid = get_config('boxnetsso', 'clientid');
    $clientsecret = get_config('boxnetsso', 'clientsecret');

    if (empty($clientid) and empty($clientsecret)) {
      // just use boxnet's secret if it is set
      parent::__construct($repositoryid, $context, $options);
    } else {
      $returnurl = new moodle_url('/repository/repository_callback.php');
      $returnurl->param('callback', 'yes');
      $returnurl->param('repo_id', $this->id);
      $returnurl->param('sesskey', sesskey());

      $this->boxnetclient = new boxnetsso_client($clientid, $clientsecret, $returnurl, '');
    }
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

}