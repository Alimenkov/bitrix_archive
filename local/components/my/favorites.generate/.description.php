<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
    'NAME' => Loc::getMessage('T_IBLOCK_DESC_FAV'),
    'DESCRIPTION' => Loc::getMessage('T_IBLOCK_DESC_FAV_DESC'),
    'ICON' => '/images/news_line.gif',
    'SORT' => 10,
    'CACHE_PATH' => 'Y',
    'PATH' => array(
        'ID' => 'bit',
        'CHILD' => array(
            'ID' => 'news',
            'NAME' => Loc::getMessage('T_IBLOCK_DESC_FAV_SECT'),
            'SORT' => 10,
        )
	),
);