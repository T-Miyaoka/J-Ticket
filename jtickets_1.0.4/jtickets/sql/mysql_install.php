<?php

// +---------------------------------------------------------------------------+
// | J-Tickets Plugin                                                          |
// +---------------------------------------------------------------------------+
// | mysql_install.php                                                         |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2015 MeYan                                                  |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// +---------------------------------------------------------------------------|
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
// | along with this program; if not, write to the Free Software               |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA|
// |                                                                           |
// +---------------------------------------------------------------------------|

$_SQL[] = "CREATE TABLE {$_TABLES['jtickets']} (
  ticketid varchar(14) NOT NULL default '0',
  status mediumint(1) NOT NULL default '0',
  title varchar(255) NOT NULL,
  text text NOT NULL,
  limityear mediumint(4) NOT NULL,
  limitmonth mediumint(2) NOT NULL,
  limitday mediumint(2) NOT NULL,
  userid mediumint(8) NOT NULL,
  filename varchar(50) NOT NULL,
  filedata longtext NOT NULL,
  PRIMARY KEY (ticketid)
) ENGINE=MyISAM;";

?>
