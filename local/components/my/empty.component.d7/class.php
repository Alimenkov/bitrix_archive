<?
namespace My\Components;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use	Bitrix\Main\Loader;

class MakeFilterArray extends \CBitrixComponent
{
	public function __construct($component = null)
	{
		parent::__construct($objComponent);

		Loc::loadMessages(__FILE__);
	}

	public function onPrepareComponentParams($params)
	{

		return $params;
	}

	public function executeComponent()
	{
		try
		{
			$this->checkRequiredFields();


		} catch (\Exception $e)
		{
			$this->arResult['ERRORS'] = $e->getMessage();
		}

		$this->includeComponentTemplate();
	}

	protected function checkRequiredModules()
	{
		if (!Loader::includeModule('iblock'))
			throw new \Exception(Loc::getMessage('MFA_IBLOCK_MODULE_NOT_INSTALL'));

	}

}