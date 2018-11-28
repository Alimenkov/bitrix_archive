<?

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;


Loc::loadMessages(__FILE__);

if (empty(check_bitrix_sessid()))
{

	return false;
}

if ($objErrorException = $APPLICATION->GetException())
{

	echo CAdminMessage::ShowMessage($objErrorException->GetString());
}
else
{

	echo CAdminMessage::ShowNote(Loc::getMessage('MM_STEP_BEFORE') . ' ' . Loc::getMessage('MM_STEP_AFTER'));
}

$objRequest = Application::getInstance()->getContext()->getRequest();

$strUri = $objRequest->getRequestedPage();

?>

<form action="<?= $strUri; ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>"/>
    <input type="submit" value="<? echo(Loc::getMessage('MM_STEP_SUBMIT_BACK')); ?>">
</form>