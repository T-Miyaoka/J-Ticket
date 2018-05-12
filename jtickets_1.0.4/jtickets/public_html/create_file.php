<?php

// +---------------------------------------------------------------------------+
// | J-Tickets Plugin                                                          |
// +---------------------------------------------------------------------------+
// | public_html/jtickets/create_file.php                                      |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2015 MeYan                                                  |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// +---------------------------------------------------------------------------+
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

require_once ('../lib-common.php');

$fdata = DB_getItem($_TABLES['jtickets'], 'filedata', "ticketid={$_GET['id']}");
$fname = DB_getItem($_TABLES['jtickets'], 'filename', "ticketid={$_GET['id']}");

$info = new SplFileInfo($fname);
$ext = $info->getExtension();

$arr = explode(",", $fdata);
foreach($arr as $val) {
  $tmp = pack("c*", $val);
  $str .= $tmp;
}

$fdata = $str;

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$_GET['id'].'.'.$ext);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fdata));
ob_clean();
ob_flush();
flush();
echo $fdata;

?>