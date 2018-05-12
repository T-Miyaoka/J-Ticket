<?php

// +---------------------------------------------------------------------------+
// | J-Tickets Plugin                                                          |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/jtickets/index.php                              |
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

require_once ('../../../lib-common.php');

if (!SEC_hasRights('jtickets.admin')) {
  exit;
}

if ( isset($_GET['mode']) ) {
    $mode = COM_applyFilter($_GET['mode']);
} else {
    if ( isset($_POST['mode']) ) {
        $mode = COM_applyFilter($_POST['mode']);
    } else {
        $mode = '';
    }
}

// +---------------------------------------------------------------------------+
// | MAIN                                                                      |
// +---------------------------------------------------------------------------+

switch ($mode) {

  case 'edit':

    $T = new Template($_CONF['path'] . 'plugins/jtickets/templates');
    $T->set_file(array (
          'blockheader'  => 'blockheader.thtml',
          'top_menu'     => 'topmenu.thtml',
          'edit'         => 'edit.thtml',
        ));

    if(COM_applyFilter($_GET['id']) == ""){

      $date = new DateTime();
      $ticket_id = $date->format('YmdHis');

      $year_options = COM_getYearFormOptions(date('Y'));
      $month_options = COM_getMonthFormOptions(date('m'));
      $day_options = COM_getDayFormOptions(date('d'));

      $user_options = JTICKETS_getUserFormOptions('');

      $status_options = JTICKETS_getStatusFormOptions('0');

      $display = 'none';
      $fdelete = 'none';

    }
    else{

      $ticket_id = COM_applyFilter($_GET['id']);

      $sql = "SELECT * FROM {$_TABLES['jtickets']} where ticketid = {$ticket_id}";
      $result = DB_query($sql);
      $A = DB_fetchArray($result);

      $year_options = COM_getYearFormOptions($A['limityear']);
      $month_options = COM_getMonthFormOptions($A['limitmonth']);
      $day_options = COM_getDayFormOptions($A['limitday']);

      $user_options = JTICKETS_getUserFormOptions($A['userid']);
      $status_options = JTICKETS_getStatusFormOptions($A['status']);

      $title = $A['title'];
      $text = $A['text'];

      $display = '';

      if($A['filename']) {
      
        $fname = $A['filename'];
        $fdelete = '';
        $fupload = 'none';

      }
      else {

        $fdelete = 'none';
        $fupload = '';

      }

    }

    $T->set_var(array(
      'lang_modename'        => JTICKETS_str('edit_mode'),
      'lang_piname'          => JTICKETS_str('piname'),
      'icon_url'             => $_CONF['site_url'] . '/jtickets/images/jtickets.png',
      'title'                => $title,
      'text'                 => $text,
      'limit_year_options'   => $year_options,
      'limit_month_options'  => $month_options,
      'limit_day_options'    => $day_options,
      'user_options'         => $user_options,
      'status_options'       => $status_options,
      'ticket_id'            => $ticket_id,
      'display'              => $display,
      'fdelete'              => $fdelete,
      'fupload'              => $fupload,
      'filename'             => $fname,
    ));

    $content = "";

    $T->parse('output', 'blockheader');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'top_menu');
    $content .= $T->finish($T->get_var('output'));
  
    $T->parse('output', 'edit');
    $content .= $T->finish($T->get_var('output'));

    break;

  case JTICKETS_str('save'):

    $data = $_POST['text'];

    $data = htmlspecialchars($data, ENT_QUOTES);
    $data = str_replace("?t", 'Å@Å@', $data);
    $data = str_replace("?n", '<br>', $data);
    $data = str_replace('??','&yen;',$data);
    $data = str_replace('&yen;&yen;','&yen;',$data);
    $data = str_replace('&yen;&quot;','&quot;',$data);
    $data = str_replace('&yen;&#039;','&#039;',$data);
    $data = str_replace("&lt;?php", "<font color='blue'>&lt;?php", $data);
    $data = str_replace("?&gt;", "?&gt;</font>", $data);
    $data = str_replace("/*", "<font color='red'>/*", $data);
    $data = str_replace("*/", "*/</font>", $data);
    $data = str_replace("&lt;style", "<font color='green'>&lt;style", $data);
    $data = str_replace("&lt;/style&gt;", "&lt;/style&gt;</font>", $data);

    $lang = array(
      'id'          => COM_applyFilter($_POST['id']),
      'status'      => COM_applyFilter($_POST['status']),
      'title'       => COM_applyFilter($_POST['title']),
      'text'        => $data,
      'limit_year'  => COM_applyFilter($_POST['limit_year']),
      'limit_month' => COM_applyFilter($_POST['limit_month']),
      'limit_day'   => COM_applyFilter($_POST['limit_day']),
      'uid'         => COM_applyFilter($_POST['uid']),
      'fdelete'     => COM_applyFilter($_POST['fdelete']),
      'opt1'        => COM_applyFilter($_POST['opt1']),
      'opt2'        => COM_applyFilter($_POST['opt2']),
    );

    $result = JTICKETS_execute('save',$lang);
    $list = JTICKETS_execute('reload',$lang);

    $T = new Template($_CONF['path'] . 'plugins/jtickets/templates');
    $T->set_file(array (
          'headermsg'    => 'blockheader-message.thtml',
          'blockheader'  => 'blockheader.thtml',
          'top_menu'     => 'topmenu.thtml',
          'option_menu'  => 'optionmenu.thtml',
          'table_header' => 'tableheader.thtml',
          'table_footer' => 'tablefooter.thtml',
        ));

    $T->set_var(array(
        'msg_title'     => JTICKETS_str('blockmsg_title'),
        'msg_text'      => $result,
        'lang_piname'   => JTICKETS_str('piname'),
        'icon_url'      => $_CONF['site_url'] . '/jtickets/images/jtickets.png',
        'checked2'      => ' checked',
        'tickets_list'  => $list,
      ));

    $content = "";

    $T->parse('output', 'headermsg');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'blockheader');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'top_menu');
    $content .= $T->finish($T->get_var('output'));
  
    $T->parse('output', 'option_menu');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_header');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_footer');
    $content .= $T->finish($T->get_var('output'));

    break;

  case JTICKETS_str('delete'):

    $lang = array(
      'id' => COM_applyFilter($_POST['id']),
    );

    $list = JTICKETS_execute('delete',$lang);
    $list = JTICKETS_execute('reload',$lang);

    $T = new Template($_CONF['path'] . 'plugins/jtickets/templates');
    $T->set_file(array (
        'blockheader'  => 'blockheader.thtml',
        'top_menu'     => 'topmenu.thtml',
        'option_menu'  => 'optionmenu.thtml',
        'table_header' => 'tableheader.thtml',
        'table_footer' => 'tablefooter.thtml',
      ));

    $T->set_var(array(
        'lang_piname'  => JTICKETS_str('piname'),
        'icon_url'     => $_CONF['site_url'] . '/jtickets/images/jtickets.png',
        'checked1'     => $checked1,
        'checked2'     => $checked2,
        'checked3'     => COM_applyFilter($_POST['opt2']),
        'tickets_list' => $list,
      ));

    $content = "";

    $T->parse('output', 'blockheader');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'top_menu');
    $content .= $T->finish($T->get_var('output'));
  
    $T->parse('output', 'option_menu');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_header');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_footer');
    $content .= $T->finish($T->get_var('output'));

    break;

  case 'sum':

    $list = JTICKETS_getSummaryList();

    $T = new Template($_CONF['path'] . 'plugins/jtickets/templates');
    $T->set_file(array (
        'blockheader'  => 'blockheader.thtml',
        'top_menu'     => 'topmenu.thtml',
        'summary'      => 'sumheader.thtml',
        'table_footer' => 'tablefooter.thtml',
      ));

    $T->set_var(array(
        'lang_piname'  => JTICKETS_str('piname'),
        'icon_url'     => $_CONF['site_url'] . '/jtickets/images/jtickets.png',
        'summary_list' => $list,
      ));

    $content = "";

    $T->parse('output', 'blockheader');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'top_menu');
    $content .= $T->finish($T->get_var('output'));
  
    $T->parse('output', 'summary');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_footer');
    $content .= $T->finish($T->get_var('output'));

    break;
    
  case 'filter':

    $lang = array(
      'opt' => COM_applyFilter($_GET['opt']),
      'uid' => COM_applyFilter($_GET['uid']),
    );

    $list = JTICKETS_execute('filter', $lang);
    if($list == ""){
      $message = '<br/>'.JTICKETS_str('nodata');
    }

    $T = new Template($_CONF['path'] . 'plugins/jtickets/templates');
    $T->set_file(array (
        'blockheader'  => 'blockheader.thtml',
        'top_menu'     => 'topmenu.thtml',
        'filter_header' => 'filterheader.thtml',
        'table_footer' => 'tablefooter.thtml',
      ));

    $T->set_var(array(
        'lang_piname'  => JTICKETS_str('piname'),
        'icon_url'     => $_CONF['site_url'] . '/jtickets/images/jtickets.png',
        'tickets_list' => $list,
      ));

    $content = "";

    $T->parse('output', 'blockheader');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'top_menu');
    $content .= $T->finish($T->get_var('output'));
  
    $T->parse('output', 'filter_header');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_footer');
    $content .= $T->finish($T->get_var('output'));

    break;
    
  default:

    $lang = array(
      'opt1' => COM_applyFilter($_POST['opt1']),
      'opt2' => COM_applyFilter($_POST['opt2']),
    );

    if(COM_applyFilter($_POST['opt1']) == 1){
      $checked1 = ' checked';
    }
    else{
      $checked2 = ' checked';
    }

    $list = JTICKETS_execute('reload', $lang);
    if($list == ""){
      $message = '<br/>'.JTICKETS_str('nodata');
    }

    $T = new Template($_CONF['path'] . 'plugins/jtickets/templates');
    $T->set_file(array (
          'blockheader'  => 'blockheader.thtml',
          'top_menu'     => 'topmenu.thtml',
          'option_menu'  => 'optionmenu.thtml',
          'table_header' => 'tableheader.thtml',
          'table_footer' => 'tablefooter.thtml',
      ));

    $T->set_var(array(
        'lang_piname'  => JTICKETS_str('piname'),
        'icon_url'     => $_CONF['site_url'] . '/jtickets/images/jtickets.png',
        'checked1'     => $checked1,
        'checked2'     => $checked2,
        'checked3'     => COM_applyFilter($_POST['opt2']),
        'tickets_list' => $list,
        'message'      => $message,
      ));

    $content = "";

    $T->parse('output', 'blockheader');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'top_menu');
    $content .= $T->finish($T->get_var('output'));
  
    $T->parse('output', 'option_menu');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_header');
    $content .= $T->finish($T->get_var('output'));

    $T->parse('output', 'table_footer');
    $content .= $T->finish($T->get_var('output'));
 
    break;
}

  $content .= "</div></div>";
  
  $display = COM_createHTMLDocument($content);
  COM_output($display);
?>
