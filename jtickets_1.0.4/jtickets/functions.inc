<?php

// +---------------------------------------------------------------------------+
// | J-Tickets Plugin for Geeklog                                              |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/jtickets/functions.inc                                    |
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

/**
* Includes language file 
*/
$langfile = $_CONF['path'] . 'plugins/jtickets/language/' . $_CONF['language'] . '.php';

if (file_exists($langfile)) {
	require_once $langfile;
} else {
	require_once $_CONF['path'] . 'plugins/jtickets/language/japanese_utf-8.php';
}

/*
* Loads config
*/
require_once $_CONF['path'] . 'plugins/jtickets/config.php';

if (stripos($_SERVER['PHP_SELF'], 'functions.inc') !== false) {
    die('This file cannot be used on its own.');
}

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+

/**
* Returns the items for this plugin that should appear on the main menu
*
* @return    array   Associative array of menu items to be added to menu
*/
function plugin_getmenuitems_jtickets() {
    return FALSE;
}

/**
* Checks to see if this Plugin uses comments
*
* @return   boolean TRUE or FALSE
*/
function plugin_commentsupport_jtickets() {
    return FALSE;
}

/**
* This will put an option for the plugin in the command and control block on
* moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*/
function plugin_cclabel_jtickets() {
    global $_CONF, $LANG_JTK;
    
    $pi_name   = JTICKETS_str('admin_label');
    $admin_url = $_CONF['site_admin_url'] . '/plugins/jtickets/index.php';
    $icon_url  = $_CONF['site_url'] . '/jtickets/images/jtickets.png';

    return array($pi_name, $admin_url, $icon_url);
}

/**
* Returns the administrative option for this plugin
*
* @return   array   Array containing (plugin name, plugin admin url, # of items
*                   in plugin or '')
*/
function plugin_getadminoption_jtickets() {
    global $_USER, $_CONF, $_TABLES, $LANG_JTK;

    if (SEC_hasRights('jtickets.admin')){
        $total = DB_getItem($_TABLES['jtickets'], 'count(*)', "status != 4");
        $count = DB_getItem($_TABLES['jtickets'], 'count(*)', "userid = {$_USER['uid']} and status != 4");

        $pi_name   = JTICKETS_str('admin_label');
        $admin_url = $_CONF['site_admin_url'] . '/plugins/jtickets/index.php';
        $num_items  = $count.'/'.$total;

        return array($pi_name, $admin_url, $num_items);
    }
}

/*
* Returns the version for this plugin
*
* @return string VersionNo
*/
function plugin_chkVersion_jtickets() {
	global $_JTICKETS_CONF;
	
	return $_JTICKETS_CONF['pi_version'];
}

/**
* Upgrades this plugin
*
* @return   boolean TRUE (= success)
*/
function plugin_upgrade_jtickets() {
	global $_CONF, $_TABLES, $_JTICKETS_CONF;

	// Updates plugin info
	$sql = "UPDATE {$_TABLES['plugins']} "
		 . "  SET pi_version = '" . addslashes($_JTICKETS_CONF['pi_version']) . "' "
		 . "  WHERE (pi_name = 'jtickets')";
	$result = DB_query($sql);
	
	return TRUE;
}

/**
* Automatic uninstall function for plugins
*
* @return   array
*/
function plugin_autouninstall_jtickets() {

    $out = array (
        /* give the name of the tables, without $_TABLES[] */
        'tables'     => array('jtickets'),
		/* give the full name of the group, as in the db */        
        'groups'     => array('jtickets Admin'),
		/* give the full name of the feature, as in the db */
        'features'   => array('jtickets.admin'),
		/* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array(),
		/* give all vars with their name */
        'vars'       => array()
    );

    return $out;
}

// -------------------------------------------------------------------------------------------------------------- //

function JTICKETS_str($str) {

    global $LANG_JTK;
    return isset($LANG_JTK[$str]) ? $LANG_JTK[$str] : '(undefined)';

}

function JTICKETS_getUserFormOptions($uid) {
    global $_USER, $_TABLES;

    $sql = "SELECT uid,username FROM {$_TABLES['users']} WHERE status = 3 and algorithm != 0 order by username";
    $result = DB_query($sql);
    $num = DB_numrows($result);
    for ($i = 0; $i < $num; ++$i)
    {
        $A = DB_fetchArray($result);

        if($A['uid'] == $uid){
          $selected_user =  ' selected="selected"';
        }
        else{
          $selected_user = '';
        }

        $ret .= '<option value="'.$A['uid'].'"'.$selected_user.'>'.$A['username'].'</option>'."\n";
    }
    return $ret;
}

function JTICKETS_getStatusFormOptions($status_no) {
  global $_TABLES, $LANG_JTK;

  $arrstatus = array();
  $arrstatus[$status_no] = ' selected="selected"';

  $ret = '<option value="0"'.$arrstatus['0'].'>'.$LANG_JTK['status_new'].'</option>'."\n"
       . '<option value="1"'.$arrstatus['1'].'>'.$LANG_JTK['status_do'].'</option>'."\n"
       . '<option value="2"'.$arrstatus['2'].'>'.$LANG_JTK['status_done'].'</option>'."\n"
       . '<option value="3"'.$arrstatus['3'].'>'.$LANG_JTK['status_feedback'].'</option>'."\n"
       . '<option value="4"'.$arrstatus['4'].'>'.$LANG_JTK['status_close'].'</option>'."\n"
       . '<option value="5"'.$arrstatus['5'].'>'.$LANG_JTK['status_pending'].'</option>'."\n";

  return $ret;
}

function JTICKETS_getSummaryList() {
  global $_CONF, $_TABLES, $LANG_JTK;

  $sql = "SELECT us.uid, us.username, count(*) cn "
       . "FROM {$_TABLES['jtickets']} jt, {$_TABLES['users']} us "
       . "WHERE jt.status != 4 and jt.userid = us.uid "
       . "group by jt.userid order by cn desc, username asc;";

  $result = DB_query($sql);
  $num = DB_numrows($result);

  $ret = '';

  for ($i = 0; $i < $num; ++$i)
  {
    $A = DB_fetchArray($result);
    $limitout = JTICKETS_getLimitEndCount($A['uid']);
    $closeout = DB_getItem($_TABLES['jtickets'], 'count(*)', "status = 4 and userid = '{$A['uid']}'");

    if($trclass == "admin-listitem1") {
      $trclass = "admin-listitem2";
    }
    else {
      $trclass = "admin-listitem1";
    }
    
    switch($i+1) {
      case 1:
        $ranking = '<img src="'.$_CONF['site_url'].'/jtickets/images/gold.gif">';
        break;
      case 2:
        $ranking = '<img src="'.$_CONF['site_url'].'/jtickets/images/silver.gif">';
        break;
      case 3:
        $ranking = '<img src="'.$_CONF['site_url'].'/jtickets/images/blonze.gif">';
        break;
      default:
        $ranking = $i + 1;
        break;
    }
    
    $ret .= '<tr class='.$trclass.' style="height: 32px;">'."\n"
         .  '  <td class="admin-list-field" style="text-align: center;">'.$ranking.'</td>'."\n"
         .  '  <td class="admin-list-field">'.$A['username'].'</td>'."\n"
         .  '  <td><a href="./index.php?mode=filter&opt=1&uid='.$A['uid'].'">'.$A['cn'].' '.$LANG_JTK['count'].'</a></td>'."\n"
         .  '  <td><a href="./index.php?mode=filter&opt=2&uid='.$A['uid'].'">'.$limitout.' '.$LANG_JTK['count'].'</a></td>'."\n"
         .  '  <td><a href="./index.php?mode=filter&opt=3&uid='.$A['uid'].'">'.$closeout.' '.$LANG_JTK['count'].'</a></td>'."\n"
         .  '</tr>'."\n";
  }

  return $ret;
}

function JTICKETS_getLimitEndCount($uid) {
    global $_TABLES;

    $dt = new DateTime();
    $now = $dt->format('Y-m-d H:i:s');

    $sql = "SELECT limityear, limitmonth, limitday "
         . "FROM {$_TABLES['jtickets']} "
         . "WHERE status != 4 and userid = {$uid};";

    $result = DB_query($sql);
    $num = DB_numrows($result);
    
    $limitout = 0;

    for ($i = 0; $i < $num; ++$i)
    {
        $A = DB_fetchArray($result);
        $end = "{$A['limityear']}-{$A['limitmonth']}-{$A['limitday']} 00:00:00";
        if (strtotime($end) < strtotime($now)) {
          $limitout++;
        }
    }
    
    return $limitout;
}

function JTICKETS_unpack($fdata) {
  $tmp = unpack("c*", $fdata);
  foreach($tmp as $val) {
    if($ret == "") { 
      $ret = $val;
    }
    else {
      $ret .= ",".$val;
    }
  }
  return $ret;
}

function JTICKETS_execute($type,$lang) {
  global $_CONF, $_USER, $_TABLES, $LANG_JTK;

  $type = (string) $type;

  switch ($type) {
    case 'save':

      if($lang{'title'} == "" or $lang{'text'} == ""){
        return $LANG_JTK['blockmsg_caution'];
      }
      else{

        if(is_uploaded_file($_FILES["upfile"]["tmp_name"]))
        {
          $fp = fopen($_FILES['upfile']['tmp_name'], "rb");

          if($fp) {
            $fdata = fread($fp, filesize($_FILES["upfile"]["tmp_name"]));
            fclose($fp);

            $fdata = JTICKETS_unpack($fdata);
            $fname = $_FILES["upfile"]["name"];
          }
          else {
            return $LANG_JTK['blockmsg_fileerr'];
          }
        }

        $sql = "SELECT count(*) cnt FROM {$_TABLES['jtickets']} WHERE ticketid = {$lang['id']}";
        $result = DB_query($sql);
        $A = DB_fetchArray($result);

        if($A['cnt'] == 0){
          $sql  = "";
          $sql .= "insert into {$_TABLES['jtickets']} ";
          $sql .= "(ticketid, status, title, text, limityear, limitmonth, limitday, userid, filename, filedata) ";
          $sql .= "values ";
          $sql .= "('{$lang['id']}',{$lang['status']},'{$lang['title']}','{$lang['text']}',{$lang['limit_year']},{$lang['limit_month']},{$lang['limit_day']},{$lang['uid']},'{$fname}','{$fdata}')";
          DB_query($sql);
          return $LANG_JTK['blockmsg_regist'];
        }
        else{
          $sql  = "";
          $sql .= "update {$_TABLES['jtickets']} set ";

          if($lang['fdelete'] == "on") {
            $sql .= "filename = '',";
            $sql .= "filedata = '',";
          }
          else {
            if(is_uploaded_file($_FILES["upfile"]["tmp_name"])) {

              $fp = fopen($_FILES['upfile']['tmp_name'], "rb");

              if($fp) {
                $fdata = fread($fp, filesize($_FILES["upfile"]["tmp_name"]));
                fclose($fp);

                $fdata = JTICKETS_unpack($fdata);
                $fname = $_FILES["upfile"]["name"];
              }
              else {
                return $LANG_JTK['blockmsg_fileerr'];
              }

              $sql .= "filename = '{$fname}',";
              $sql .= "filedata = '{$fdata}',";
            }
          }

          $sql .= "status = {$lang['status']},";
          $sql .= "title = '{$lang['title']}',";
          $sql .= "text = '{$lang['text']}',";
          $sql .= "limityear = {$lang['limit_year']},";
          $sql .= "limitmonth = {$lang['limit_month']},";
          $sql .= "limitday = {$lang['limit_day']},";
          $sql .= "userid = {$lang['uid']} ";
          $sql .= "where ticketid = '{$lang['id']}'";
          DB_query($sql);
          return $LANG_JTK['blockmsg_regist'];
        }

      }

      break;

    case 'delete':
        $sql = "delete FROM {$_TABLES['jtickets']} WHERE ticketid = {$lang['id']}";
        DB_query($sql);
      break;

    case 'reload':

      $status = array(
        $LANG_JTK['status_new'],
        $LANG_JTK['status_do'],
        $LANG_JTK['status_done'],
        $LANG_JTK['status_feedback'],
        $LANG_JTK['status_close'],
        $LANG_JTK['status_pending']
      );
      
      if($lang['opt1'] != '1'){
        $opt_where = "where userid = ".$_USER['uid'];
        if($lang['opt2'] != 'checked'){
          $opt_where .= " and status != 4";
        }
      }
      else {
        if($lang['opt2'] != 'checked'){
          $opt_where .= "where status != 4";
        }
      }

      $sql = "SELECT * FROM {$_TABLES['jtickets']} ".$opt_where." order by limityear,limitmonth,limitday";
      $result = DB_query($sql);
      $num = DB_numrows($result);

      for ($i = 0; $i < $num; ++$i)
      {
        $A = DB_fetchArray($result);

        if($trclass == "admin-listitem1"){
          $trclass = "admin-listitem2";
        }
        else{
          $trclass = "admin-listitem1";
        }

        $username = DB_getItem($_TABLES['users'], 'username', "uid={$A['userid']}");
        $filename = DB_getItem($_TABLES['jtickets'], 'filename', "ticketid={$A['ticketid']}");

        $limit_date = $A['limityear'].'-'.$A['limitmonth'].'-'.$A['limitday'];
        $dt = new DateTime();
        $today = $dt->format('Y-m-d');
        
        if(strtotime($limit_date) > strtotime($today)){
          $span_class = "yes";
        }
        elseif(strtotime($limit_date) < strtotime($today)){
          $span_class = "no";
        }
        else{
          $span_class = "no";
        }

        $text = str_replace(array("\r\n", "\r", "\n"), '<br/>', $A['text']);

        $ret .= '<tr class="'.$trclass.'">';
        $ret .= '<td class="admin-list-field"><a href="./index.php?mode=edit&id='.$A['ticketid'].'"><img src="'.$_CONF['site_url'].'/jtickets/images/edit.png'.'"></a></td>';

        if($filename == "") {
          $ret .= '<td class="admin-list-field">-</td>';        
        }
        else{
          $ret .= '<td class="admin-list-field"><a href="'.$_CONF['site_url'].'/jtickets/create_file.php?&id='.$A['ticketid'].'"><img src="'.$_CONF['site_url'].'/jtickets/images/clip.png'.'"></a></td>';
        }

        $ret .= '<td class="admin-list-field">'.$status[$A['status']].'</td>';
        $ret .= '<td class="admin-list-field"><span class="'.$span_class.'">'.$limit_date.'</span></td>';
        $ret .= '<td class="admin-list-field">'.$A['title'].'</td>';
        $ret .= '<td class="admin-list-field" style="word-break: break-all;">'.$text.'</td>';
        $ret .= '<td class="admin-list-field">'.$username.'</td>';
        $ret .= '</tr>';
      }

      return $ret;

      break;

    case 'filter':

      $status = array(
        $LANG_JTK['status_new'],
        $LANG_JTK['status_do'],
        $LANG_JTK['status_done'],
        $LANG_JTK['status_feedback'],
        $LANG_JTK['status_close'],
        $LANG_JTK['status_pending']
      );

      if($lang['opt'] == 3) {
        $sql = "SELECT * "
             . "FROM {$_TABLES['jtickets']} "
             . "WHERE status = 4 and userid = {$lang['uid']}";
      }
      else{
        $sql = "SELECT * "
             . "FROM {$_TABLES['jtickets']} "
             . "WHERE status != 4 and userid = {$lang['uid']}";
      }

      $result = DB_query($sql);
      $num = DB_numrows($result);

      $ret = '';

      for ($i = 0; $i < $num; ++$i)
      {
        $A = DB_fetchArray($result);

        if($trclass == "admin-listitem1") {
          $trclass = "admin-listitem2";
        }
        else{
          $trclass = "admin-listitem1";
        }

        $username = DB_getItem($_TABLES['users'], 'username', "uid={$A['userid']}");
        $filename = DB_getItem($_TABLES['jtickets'], 'filename', "ticketid={$A['ticketid']}");

        $limit_date = $A['limityear'].'-'.$A['limitmonth'].'-'.$A['limitday'];
        $dt = new DateTime();
        $today = $dt->format('Y-m-d');

        if(strtotime($limit_date) > strtotime($today)){
          $span_class = "yes";
        }
        elseif(strtotime($limit_date) < strtotime($today)){
          $span_class = "no";
        }
        else{
          $span_class = "no";
        }
        
        if($lang['opt'] == 2 and $span_class == "yes") {
        }
        else{

          $text = str_replace(array("\r\n", "\r", "\n"), '<br/>', $A['text']);

          $ret .= '<tr class="'.$trclass.'">';
          $ret .= '<td class="admin-list-field"><a href="./index.php?mode=edit&id='.$A['ticketid'].'"><img src="'.$_CONF['site_url'].'/jtickets/images/edit.png'.'"></a></td>';

          if($filename == "") {
            $ret .= '<td class="admin-list-field">-</td>';
          }
          else{
            $ret .= '<td class="admin-list-field"><a href="'.$_CONF['site_url'].'/jtickets/create_file.php?&id='.$A['ticketid'].'"><img src="'.$_CONF['site_url'].'/jtickets/images/clip.png'.'"></a></td>';
          }

          $ret .= '<td class="admin-list-field">'.$status[$A['status']].'</td>';
          $ret .= '<td class="admin-list-field"><span class="'.$span_class.'">'.$limit_date.'</span></td>';
          $ret .= '<td class="admin-list-field">'.$A['title'].'</td>';
          $ret .= '<td class="admin-list-field" style="word-break: break-all;">'.$text.'</td>';
          $ret .= '<td class="admin-list-field">'.$username.'</td>';
          $ret .= '</tr>';
        }
      }

      return $ret;
      
      break;

    default:
    COM_errorLog(__FUNCTION__ . ': Unknown type "' . $type . '" was called.');
      break;
  }

}

?>