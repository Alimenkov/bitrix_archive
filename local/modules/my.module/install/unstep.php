<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application,
	Bitrix\Main\Web\Uri;


Loc::loadMessages(__FILE__);

if (empty(check_bitrix_sessid()))
{

	return false;
}

echo CAdminMessage::ShowNote(Loc::getMessage('MM_UNSTEP_BEFORE') . ' ' . Loc::getMessage('MM_UNSTEP_AFTER'));

$objRequest = Application::getInstance()->getContext()->getRequest();

$strUri = $objRequest->getRequestedPage();

?>

<form action="<?= $strUri; ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>"/>
    <input type="submit" value="<? echo(Loc::getMessage('MM_UNSTEP_SUBMIT_BACK')); ?>">
</form>