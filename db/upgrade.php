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
 * This file keeps track of upgrades to the webhooks plugin.
 *
 * @package   local_webhooks
 * @copyright 2024 OpenCollabZA <info@opencollab.co.za>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade 'local_webhooks'.
 *
 * @param int $oldversion older version of plugin.
 * @return bool
 */
function xmldb_local_webhooks_upgrade(int $oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022072900) {
        // Increase the "token" column size from "char (255)" to "text"
        // https://github.com/valentineus/moodle-webhooks/issues/27
        $table = new xmldb_table('local_webhooks_service');
        $field = new xmldb_field('token');
        $field->set_attributes(XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $dbman->change_field_type($table, $field);

        // Webhooks savepoint reached.
        upgrade_plugin_savepoint(true, 2022072900, 'local', 'webhooks');
    }

    return true;
}