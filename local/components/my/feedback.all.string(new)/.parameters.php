<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!CModule::IncludeModule('iblock'))
	return;

$arTypesEx = \CIBlockParameters::GetIBlockTypes(Array('-' => ' '));

$arкIBlocks = Array();
$objIblocks = \CIBlock::GetList(Array('SORT' => 'ASC'), Array('SITE_ID' => $_REQUEST['site'], 'TYPE' => ($arCurrentValues['IBLOCK_TYPE'] != '-' ? $arCurrentValues['IBLOCK_TYPE'] : '')));
while ($arrRes = $objIblocks->Fetch()):
	$arкIBlocks[$arrRes['ID']] = $arrRes['NAME'];
endwhile;


$arComponentParameters = array(
	'PARAMETERS' => array(
		'IBLOCK_TYPE' => Array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('FAS_IBLOCK_LIST_TYPE'),
			'TYPE' => 'LIST',
			'VALUES' => $arTypesEx,
			'REFRESH' => 'Y',
		),
		'IBLOCK_ID' => Array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('FAS_IBLOCK_LIST_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arкIBlocks,
			'DEFAULT' => '',
			'ADDITIONAL_VALUES' => 'Y',
			'REFRESH' => 'Y',
		),
		'EMAIL' => Array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('FAS_EMAIL'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		),
		'EMAIL_SUBJ' => Array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('FAS_THEME'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		),
		'OK_MSG' => Array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('FAS_SUCCESSFULL'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		),
		'USE_CAPTCHA' => Array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('FAS_USE_CAPTCHA'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		)
	)
);