<?

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application,
	Bitrix\Main\HttpApplication,
	Bitrix\Main\Web\Uri,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$objRequest = HttpApplication::getInstance()->getContext()->getRequest();

$strModuleId = htmlspecialcharsbx(!empty($objRequest['mid']) ? $objRequest['mid'] : $objRequest['id']);

Loader::includeModule($strModuleId);

$arrTabs = array(
	array(
		'DIV' => 'edit',
		'TAB' => Loc::getMessage('MM_OPTIONS_TAB_NAME'),
		'TITLE' => Loc::getMessage('MM_OPTIONS_TAB_NAME'),
		'OPTIONS' => array(
			Loc::getMessage('MM_OPTIONS_TAB_COMMON'),
			array(
				'switch_on',
				Loc::getMessage('MM_OPTIONS_TAB_SWITCH_ON'),
				'Y',
				array('checkbox')
			),
			Loc::getMessage('MM_OPTIONS_TAB_APPEARANCE'),
			array(
				'width',
				Loc::getMessage('MM_OPTIONS_TAB_WIDTH'),
				'50',
				array('text', 5)
			),
			Loc::getMessage('MM_OPTIONS_TAB_POSITION_ON_PAGE'),
			array(
				'side',
				Loc::getMessage('MM_OPTIONS_TAB_SIDE'),
				'left',
				array('selectbox', array(
					'left' => Loc::getMessage('MM_OPTIONS_TAB_SIDE_LEFT'),
					'right' => Loc::getMessage('MM_OPTIONS_TAB_SIDE_RIGHT')
				))
			)
		),
	)
);

if ($objRequest->isPost() && check_bitrix_sessid())
{
	foreach ($arrTabs as $arrTab)
	{
		foreach ($arrTab['OPTIONS'] as $arrOption)
		{
			if (!is_array($arrOption))
			{

				continue;
			}

			if ($arrOption['note'])
			{

				continue;
			}

			if ($objRequest['apply'])
			{
				$optionValue = $objRequest->getPost($arrOption[0]);

				if ($objRequest[0] == 'switch_on')
				{
					if ($optionValue == '')
					{
						$optionValue = 'N';
					}
				}

				Option::set($strModuleId, $arrOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
			}
            elseif ($objRequest['default'])
			{
				Option::set($strModuleId, $arrOption[0], $arrOption[2]);
			}
		}
	}

	$strUri = $objRequest->getRequestUri();

	LocalRedirect($strUri);
}

$objTabControl = new \CAdminTabControl(
	'tabControl',
	$arrTabs
);

$strUri = $objRequest->getRequestedPage();
$objNewUri = new Uri($strUri);
$objNewUri->addParams(array('mid' => $strModuleId, 'lang' => LANGUAGE_ID));
$strRedirect = $objNewUri->getUri();

$objTabControl->Begin(); ?>
    <form action="<?= $strRedirect ?>" method="post">
		<?
		foreach ($arrTabs as $arrTab)
		{
			if ($arrTab['OPTIONS'])
			{

				$objTabControl->BeginNextTab();

				__AdmSettingsDrawList($strModuleId, $arrTab['OPTIONS']);
			}
		}

		$objTabControl->Buttons();
		?>

        <input type="submit" name="apply" value="<?= Loc::GetMessage('MM_OPTIONS_INPUT_APPLY'); ?>"
               class="adm-btn-save"/>
        <input type="submit" name="default" value="<?= Loc::GetMessage('MM_OPTIONS_INPUT_DEFAULT'); ?>"/>

		<?= bitrix_sessid_post() ?>

    </form>

<?
$objTabControl->End(); ?>