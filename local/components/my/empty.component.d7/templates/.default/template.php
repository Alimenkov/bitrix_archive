<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if (!empty($arResult['SECTIONS']))
{

    ?>
    <div class="catalog-menu">
        <div class="title">
            <span><?=Loc::getMessage('MFA_NAME_CATEGORY')?></span>
            <b><?=$arResult['COUNT_PRODUCTS']?></b>
        </div>
        <ul>
			<? 
			foreach ($arResult['SECTIONS'] as $arrSection)
			{
				?>
                <li <?if(!empty($arrSection['SELECTED']))echo'class="active"';?>>
                    <a href="<?= $arrSection['FILTER_URL'] ?>">
                        <span><?= truncateText($arrSection['NAME'], 60) ?></span><?= (!empty($arrSection['COUNT_ITEMS']) ? '<b>' . $arrSection['COUNT_ITEMS'] . '</b>' : '') ?>
                    </a>
                </li>
				<?
			} ?>

        </ul>
    </div>
	<?
} ?>