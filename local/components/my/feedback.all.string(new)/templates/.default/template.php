<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if (sizeof($arResult['ERRORS']) > 0)
{
	?>
    <p class="error"><?= implode('<br>', $arResult['ERRORS']) ?></p>
	<?
}
elseif (!empty($arResult['STATUS']) && strcasecmp($arResult['STATUS'], 'ok') == 0)
{
	?>
    <p class="success"><?= $arParams['OK_MSG'] ?></p>
	<?
} ?>

<div class="vopros_form">
    <form method="post" action="<?= $arResult['FORM_ACTION'] ?>">

		<? foreach ($arResult['FIELDS'] as $strIntCode => $strName)
		{
			?>
            <div class="form-group">
				<?
				if (array_key_exists($strIntCode, $arResult["FIELDS_DIV"]))
				{
					?>
					<?= $strName ?>
					<?
				}
				else
				{
					?>
                    <div class="form-label">
						<?= $strName ?><? if (array_key_exists($strIntCode, $arResult["FIELDS_REQ"])): ?>*<? endif; ?>:
                    </div>

					<? if (array_key_exists($strIntCode, $arResult["FIELDS_TXT"]))
				{ ?>
                    <textarea class="form-control"
                              name="PROP[<?= $strIntCode ?>]"><?= htmlspecialchars(strip_tags($_POST["PROP"][$strIntCode]), ENT_QUOTES) ?></textarea>
					<?
				}
				else
				{ ?>
                    <input class="form-control" name="PROP[<?= $strIntCode ?>]" type="text"
                           value="<?= htmlspecialchars(strip_tags($_POST["PROP"][$strIntCode]), ENT_QUOTES) ?>">
					<?
				}
				} ?>
            </div>
			<?
		} ?>
		<? if (isset($arResult['CAP_CODE'])): ?>
            <div class="form-group">
                <div class="form-label">
					<?= Loc::getMessage('FAS_ENTER_CODE') ?> *:
                </div>

                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialchars($arResult['CAP_CODE']) ?>"
                     width="180"
                     height="40">
                <input type="hidden" name="CAPTCHA_SID" value="<?= htmlspecialchars($arResult['CAP_CODE']) ?>">

                <input class="form-control" type="text" name="CAPTCHA_WORD" maxlength="50" value="">
            </div>
		<? endif; ?>

        <input class="button" type="submit" name="FB_SUBMIT" value="<?= Loc::getMessage('FAS_SEND') ?>">
    </form>
    <br>* - <?= Loc::getMessage('FAS_REQUIRED_FIELDS') ?>

</div>
