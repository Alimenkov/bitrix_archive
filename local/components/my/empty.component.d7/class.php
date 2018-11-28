<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

class MakeFilterArray extends CBitrixComponent
{
	const E_IBLOCK_MODULE_NOT_INSTALLED = 10000;

	protected $arrErrorsFatal = array();
	protected $arrErrors = array();

	public function __construct($objComponent = null)
	{
		parent::__construct($objComponent);

		Loc::loadMessages(__FILE__);
	}

	public function onPrepareComponentParams($arrParams)
	{

		return $arrParams;
	}

	public function executeComponent()
	{
		try
		{
			$this->checkRequiredFields();


		} catch (Exception $objE)
		{
			$this->arrErrorsFatal[$objE->getCode()] = $objE->getMessage();
		}

		$this->formatResultErrors();

		$this->includeComponentTemplate();
	}

	protected function formatResultErrors()
	{
		$arrErrors = array();

		if (!empty($this->arrErrorsFatal))
			$arrErrors['FATAL'] = $this->arrErrorsFatal;

		if (!empty($this->arrErrors))
			$arrErrors['NONFATAL'] = $this->arrErrors;

		if (!empty($arrErrors))
			$this->arResult['ERRORS'] = $arrErrors;
	}

	protected function checkRequiredModules()
	{
		if (!Loader::includeModule('iblock'))
			throw new Bitrix\Main\SystemException(Loc::getMessage('MFA_IBLOCK_MODULE_NOT_INSTALL'), self::E_IBLOCK_MODULE_NOT_INSTALLED);

	}

}