<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	'NAME' => Loc::getMessage('MFA_NAME'),
	'DESCRIPTION' => Loc::getMessage('MFA_DESC'),
	'SORT' => 70,
	'CACHE_PATH' => 'Y',
	'PATH' => array(
		'ID' => 'bit',
		'CHILD' => array(
			'ID' => 'items',
			'NAME' => Loc::getMessage('MFA_NAME_SECT'),
			'SORT' => 10,
		)
	),
);