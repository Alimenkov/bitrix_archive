<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if (!empty($arResult['ITEMS']))
{

    ?>
    <div class="catalog">
            <?=Loc::getMessage('MFA_NAME_CATEGORY')?>
			<?
			foreach ($arResult['ITEMS'] as $arrSection)
			{
                ?>

                <?
			} ?>

    </div>
	<?
} ?>