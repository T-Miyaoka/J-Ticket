<?php
//
// +---------------------------------------------------------------------------+
// | J-Tickets Plugin for Geeklog - The Ultimate Weblog                        |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/jtickets/config.php                                       |
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

global $_DB_table_prefix, $_TABLES, $_JTICKETS_CONF;

// set Plugin Table Prefix the Same as Geeklogs

$_JTICKETS_table_prefix = $_DB_table_prefix;

// Add to $_TABLES array the tables your plugin uses

$_TABLES['jtickets']      = $_JTICKETS_table_prefix . 'jtickets';

$_JTICKETS_CONF = array();

// Plugin info

$_JTICKETS_CONF['pi_version']    = '1.0.4';					// Plugin Version
$_JTICKETS_CONF['pi_gl_version'] = '1.6.0';					// GL Version plugin for
$_JTICKETS_CONF['pi_url']        = 'http://www.happa.bz/';	// Plugin Homepage

$_JTICKETS_CONF['GROUPS'] = array(
	'J-Tickets Admin' => 'Users in this group can administer the J-Ttickets plugin'
);
$_JTICKETS_CONF['FEATURES'] = array(
	'jtickets.admin' => 'Access to J-Tickets plugin editor',
);
$_JTICKETS_CONF['MAPPINGS'] = array(
	'jtickets.admin' => array('J-Tickets Admin'),
);

$_JTICKETS_CONF['REQUIRES'] = array(
        array(
               'db'      => 'mysql',
               'version' => '4.1'
             )
    );

