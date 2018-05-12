<?php

// +---------------------------------------------------------------------------+
// | J-Tickets Plugin for Geeklog                                              |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/jtickets/autoinstall.php                                  |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2018 MeYan                                                  |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

if (strpos(strtolower($_SERVER['PHP_SELF']), strtolower(basename(__FILE__))) !== FALSE) {
	die('This file can not be used on its own!');
}

require_once dirname(__FILE__) . '/config.php';

/**
* Plugin autoinstall function
*
* @param    string  $pi_name    Plugin name
* @return   array               Plugin information
*/
function plugin_autoinstall_jtickets($pi_name) {
	global $_JTICKETS_CONF;
	
    $pi_name         = 'jtickets';
    $pi_display_name = 'J-Tickets';
    $pi_admin        = $pi_name . ' Admin';

    $info = array(
        'pi_name'         => $pi_name,
        'pi_display_name' => $pi_display_name,
        'pi_version'      => $_JTICKETS_CONF['pi_version'],
        'pi_gl_version'   => $_JTICKETS_CONF['pi_gl_version'],
        'pi_homepage'     => $_JTICKETS_CONF['pi_url'],
    );

    $groups = $_JTICKETS_CONF['GROUPS'];
    $features = $_JTICKETS_CONF['FEATURES'];
    $mappings = $_JTICKETS_CONF['MAPPINGS'];
    $requires = $_JTICKETS_CONF['REQUIRES'];

    $tables = array('jtickets');

    $inst_parms = array(
        'info'      => $info,
        'groups'    => $groups,
        'features'  => $features,
        'mappings'  => $mappings,
        'tables'    => $tables,
        'requires'  => $requires
    );

    return $inst_parms;
}

/**
* Checks if the plugin is compatible with this Geeklog version
*
* @param    string  $pi_name    Plugin name
* @return   boolean             true = plugin compatible, false = otherwise
*/
function plugin_compatible_with_this_version_jtickets($pi_name) {
	global $_CONF, $_DB_dbms, $_JTICKETS_CONF;
	
	$retval = TRUE;
	
    $dbFile = $_CONF['path'] . 'plugins/' . $pi_name . '/sql/'
            . $_DB_dbms . '_install.php';
	clearstatcache();

    if (! file_exists($dbFile)) {
		$retval = FALSE;
	} else if (defined('VERSION')) {
		$gl_version = preg_replace('/[^0-9\.]/', '', VERSION);
		$retval = (version_compare($gl_version, $_JTICKETS_CONF['pi_gl_version']) >= 0);
	} else {
		$retval = FALSE;
	}

    return $retval;
}

?>
