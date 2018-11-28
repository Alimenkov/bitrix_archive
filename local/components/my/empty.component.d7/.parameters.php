<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = array(
	'PARAMETERS' => array(
		'CONFORMITY' => array(
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('MFA_CONFORMITY'),
			'TYPE' => 'STRING',
			'MULTIPLE' => 'Y',
		)
	)
);