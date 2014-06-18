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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/boxlib.php');


class boxnetsso_client extends boxnet_client {

  protected function auth_url() {
    return $CFG->wwwroot . '/repository/boxnetsso/oauth2authorize.php';
  }

}
