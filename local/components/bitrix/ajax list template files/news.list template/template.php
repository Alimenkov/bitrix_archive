<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-listener">
    <?if($arParams["AJAX_PAGE"]=="Y")$GLOBALS['APPLICATION']->RestartBuffer();?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
    <div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <?if(is_array($arItem["PREVIEW_IMG"])):?>
        <div class="news-image">
            <?if($arItem["DISPLAY_ACTIVE_FROM"]):?><div class="news-date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div><?endif?>
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img alt="<?=$arItem["NAME"]?>" src="<?=$arItem["PREVIEW_IMG"]["SRC"]?>" /></a>
        </div>
        <?endif?>

        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news-name"><?=$arItem["NAME"]?></a>
       <?=$arItem["PREVIEW_TEXT"]?truncateText(strip_tags($arItem["PREVIEW_TEXT"]),500):truncateText(strip_tags($arItem["DETAIL_TEXT"]),500)?>

    </div>
<?endforeach;?>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?>
    <?endif;?>
<?if($arParams["AJAX_PAGE"]=="Y")exit();?>
</div>
