<?php

// +---------------------------------------------------------------------------+
// | J-Tickets Plugin                                                          |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/jtickets/language/japanese_utf-8.php                      |
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

if (stripos($_SERVER['PHP_SELF'], strtolower(basename(__FILE__))) !== false) {
    die('This file cannot be used on its own!');
}

$LANG_JTK = array(
    'piname'           => 'J-Tickets',
    'admin_label'      => 'J-Tickets',
    'list_mode'        => 'チケットの一覧',
    'edit_mode'        => 'チケットの編集',
    'nodata'           => '現在のところチケットは登録されていません。',
    'blockmsg_title'   => 'システムからのメッセージ',
    'blockmsg_error'   => 'エラーが発生しました。',
    'blockmsg_caution' => 'タイトルと内容は必ず記載してください。',
    'blockmsg_fileerr' => 'ファイルのアップロードでエラーが発生しました。',
    'blockmsg_regist'  => '登録処理を実行しました。',
    'status_new'       => '未着手',
    'status_do'        => '作業中',
    'status_done'      => '承認待ち',
    'status_feedback'  => '差戻し',
    'status_close'     => '決了',
    'status_pending'   => '保留',
    'save'             => '保存',
    'cancel'           => 'キャンセル',
    'delete'           => '削除',
    'reload'           => 'リストを更新',
    'count'            => '件',
);

$LANG_configsections['jtickets'] = array();

$LANG_confignames['jtickets'] = array();

$LANG_configsubgroups['jtickets'] = array();

$LANG_fs['jtickets'] = array();

$LANG_configselects['jtickets'] = array();

?>
