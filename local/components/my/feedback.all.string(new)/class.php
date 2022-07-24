<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;

class MessagesToIblock extends \CBitrixComponent
{

    protected $objRequest;

    public function onPrepareComponentParams($arParams)
    {

        if (!empty($arParams['IBLOCK_ID'])) $arParams['IBLOCK_ID'] = IntVal($arParams['IBLOCK_ID']);

		if (!empty($arParams['EMAIL'])) $arParams['EMAIL'] = trim($arParams['EMAIL']);

		if (!empty($arParams['EMAIL_SUBJ']))
		{
			$arParams['EMAIL_SUBJ'] = trim($arParams['EMAIL_SUBJ']);
		}
		else
		{
			$arParams['EMAIL_SUBJ'] = Loc::getMessage('FAS_EMAIL_SUBJ');
		}

		if (!empty($arParams['OK_MSG']))
		{
			$arParams['OK_MSG'] = trim($arParams['OK_MSG']);
		}
		else
		{
			$arParams['OK_MSG'] = Loc::getMessage('FAS_OK_MSG');
		}

		$arParams['USE_CAPTCHA'] = (!empty($arParams['USE_CAPTCHA']) && !$GLOBALS['USER']->IsAuthorized() && $arParams['USE_CAPTCHA'] == 'Y' ? $arParams['USE_CAPTCHA'] : 'N');

		return $arParams;
	}

	public function executeComponent()
	{
		if ($this->searchSystemError())
		{
			$this->captchaCheckOn();

			$this->checkEvent();

			$this->getPropsMakeStructure();

			if ($this->checkSubmit())
			{
				$this->submitFormExecuteEvents();
			}

			$this->IncludeComponentTemplate();
		}
	}

	protected function checkSubmit()
	{
		$idSubmit = $this->objRequest->getPost("FB_SUBMIT");

		if (!empty($idSubmit)) return true;

		return false;
	}

	protected function getPropsMakeStructure()
	{
		$this->objRequest = Application::getInstance()->getContext()->getRequest();

		$this->arResult['FORM_ACTION'] = $this->makeRedirect();

		$this->arResult['FIELDS'] = array();

		$this->arResult['FIELDS_TXT'] = array();

		$this->arResult['FIELDS_REQ'] = array();

		$this->arResult['FIELDS_DIV'] = array();

		$objProp = \CIBlockProperty::GetList(Array('SORT' => 'ASC', 'NAME' => 'ASC'), Array('ACTIVE' => 'Y', 'PROPERTY_TYPE' => 'S', 'IBLOCK_ID' => $this->arParams['IBLOCK_ID']));

		while ($arrProp = $objProp->Fetch())
		{
			if ($arrProp['MULTIPLE'] != 'Y')
			{
				$this->arResult['FIELDS'][($arrProp['CODE'] != '' ? $arrProp['CODE'] : $arrProp['ID'])] = $arrProp['NAME'];

				if ($arrProp['USER_TYPE'] == 'HTML' || $arrProp['USER_TYPE'] == 'TEXT')
				{
					$this->arResult['FIELDS_TXT'][($arrProp['CODE'] != '' ? $arrProp['CODE'] : $arrProp['ID'])] = $arrProp['NAME'];
				}

				if ($arrProp['IS_REQUIRED'] == 'Y')
				{
					$this->arResult['FIELDS_REQ'][($arrProp['CODE'] != '' ? $arrProp['CODE'] : $arrProp['ID'])] = $arrProp['NAME'];
				}
				elseif ($arrProp['CODE'][0] == '_')
				{
					$this->arResult['FIELDS_DIV'][($arrProp['CODE'] != '' ? $arrProp['CODE'] : $arrProp['ID'])] = $arrProp['NAME'];
				}
			}
		}
	}

	protected function submitFormExecuteEvents()
	{
		$this->arResult['ERRORS'] = array();

		$arrPostProps = $this->objRequest->getPost('PROP');

		foreach ($this->arResult['FIELDS_REQ'] as $field_code => $field_name)
		{
			if (trim($arrPostProps[$field_code]) == '')
			{
				$this->arResult['ERRORS'][] = Loc::getMessage('FAS_REQ_FIELDS', array('#NAME#' => $this->arResult['FIELDS_REQ'][$field_code]));
			}
		}

		if ($this->arParams['USE_CAPTCHA'] == 'Y')
		{
			$objCpt = new \CCaptcha();

			$strCaptchaWord = $this->objRequest->getPost('CAPTCHA_WORD');

			$intCaptchaSID = $this->objRequest->getPost('CAPTCHA_SID');

			if (!$objCpt->CheckCode($strCaptchaWord, $intCaptchaSID))
			{
				$this->arResult['ERRORS'][] = Loc::getMessage('FAS_CAPTCHA_ERROR_CODE');
			}
		}

		if (!sizeof($this->arResult['ERRORS']))
		{
			$strName = Loc::getMessage('FAS_IBLOCK_NAME', array('#DATE#' => date('d.m.Y, H:i')));

			$strHtml = '';

			foreach ($this->arResult['FIELDS'] as $field_code => $field_name)
			{
				if (array_key_exists($field_code, $this->arResult['FIELDS_DIV']))
				{

					$strHtml = $strHtml . '<br><b>' . $field_name . '</b>';
				}
				else
				{
					$strHtml = $strHtml . '<br><i>' . $field_name . '</i>: ' . htmlspecialchars(strip_tags($arrPostProps[$field_code]), ENT_QUOTES);
				}
			}

			$arrProps = Array();

			foreach ($arrPostProps as $field => $val)
			{
				if (array_key_exists($field, $this->arResult['FIELDS_TXT']))
				{
					$arrProps[$field] = Array('VALUE' => Array('TEXT' => strip_tags(trim($val)), 'TYPE' => 'text'));
				}
				else
				{
					$arrProps[$field] = strip_tags(trim($val));
				}
			}

			$objEl = new \CIBlockElement;

			$arrIblockElement = Array(
				'IBLOCK_SECTION' => false,
				'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'NAME' => $strName,
				'PROPERTY_VALUES' => $arrProps
			);

			if ($intId = $objEl->Add($arrIblockElement))
			{

				$arrEventFields = array(
					'EMAIL' => $this->arParams['EMAIL'],
					'EMAIL_SUBJ' => $this->arParams['EMAIL_SUBJ'],
					'MESSAGE' => $strHtml
				);

				\CEvent::Send('FEEDBACK_ALL_STRING', SITE_ID, $arrEventFields);


				$strRedirect = $this->makeRedirect(array('inf' => 'ok'));

				if (!empty($strRedirect))
				{
					LocalRedirect($strRedirect);

				}

				die();
			}
			else
			{
				$this->arResult['ERRORS'][] = Loc::getMessage('FAS_BD_ERROR');
			}
		}
	}

	protected function checkEvent()
	{
		$objET = \CEventType::GetList(Array('TYPE_ID' => 'FEEDBACK_ALL_STRING'));

		if (!$objET->Fetch())
		{
			$objEvTy = new \CEventType;
			$objEvTy->Add(array(
				'LID' => LANGUAGE_ID,
				'EVENT_NAME' => 'FEEDBACK_ALL_STRING',
				'NAME' => Loc::getMessage('FAS_CET_TITLE'),
				'DESCRIPTION' => Loc::getMessage('FAS_CET_DESCRIPTION'),
			));

			$arrSites = array();

			$objSites = \CSite::GetList(($b = ''), ($o = ''), Array('LANGUAGE_ID' => LANGUAGE_ID));

			while ($arrSite = $objSites->Fetch())
			{
				$arrSites[] = $arrSite['LID'];
			}

			if (sizeof($arrSites) > 0)
			{
				$objEM = new CEventMessage;
				$objEM->Add(array(
					'ACTIVE' => 'Y',
					'EVENT_NAME' => 'FEEDBACK_ALL_STRING',
					'LID' => $arrSites,
					'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
					'EMAIL_TO' => '#EMAIL#',
					'SUBJECT' => '#EMAIL_SUBJ#',
					'BODY_TYPE' => 'html',
					'MESSAGE' => '#MESSAGE#'
				));
			}
		}

		return true;
	}

	protected function captchaCheckOn()
	{
		if ($this->arParams['USE_CAPTCHA'] == 'Y')
		{
			include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/captcha.php');

			$this->arResult['CAP_CODE'] = $GLOBALS['APPLICATION']->CaptchaGetCode();
		}
	}

	protected function searchSystemError()
	{
		if (empty($this->arParams['IBLOCK_ID']))
		{
			showError(Loc::GetMessage('FAS_ERROR_IBLOCK_ID'));
			return false;
		}

		if (!Loader::IncludeModule('iblock'))
		{
			showError(Loc::GetMessage('FAS_ERROR_MODULE_IBLOCK'));
			return false;
		}

		return true;
	}

	protected function makeRedirect($arrGet = array())
	{
		$strUriString = $this->objRequest->getRequestUri();

		if (!empty($arrGet))
		{
			$objNewUri = new Uri($strUriString);

			$objNewUri->deleteParams(array_keys($arrGet));

			$objNewUri->addParams($arrGet);

			$strRedirect = $objNewUri->getUri();

			return $strRedirect;
		}

		return $strUriString;

	}
}