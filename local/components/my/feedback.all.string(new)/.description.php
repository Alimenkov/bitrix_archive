<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
	"NAME" => Loc::getMessage("FAS_NAME"),
	"DESCRIPTION" => Loc::getMessage("FAS_DESC"),
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "bit",
		"CHILD" => array(
			"ID" => "messages",
			"NAME" => Loc::getMessage("FAS_NAME_SECT"),
			"SORT" => 10,
		)
	)
);