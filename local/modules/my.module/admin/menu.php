<?
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arrMenu = array(
	array(
		'parent_menu' => 'global_menu_content',
		'sort' => 100,
		'text' => Loc::getMessage('MM_MODULE_NAME'),
		'title' => '',
		'url' => 'perfmon_table.php?lang=ru&table_name=my_table',
		'items_id' => 'menu_references'
	)
);

return $arrMenu;