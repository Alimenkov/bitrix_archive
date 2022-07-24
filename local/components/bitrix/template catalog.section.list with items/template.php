<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["SECTIONS"])):?>
    <div class="mp">
            <?foreach($arResult["SECTIONS"] as $arSection):
                if(!empty($arResult['ITEMS'][$arSection['ID']])):
                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="section-title-wr">
                    <h3 class="section-title left"><span><a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></span></h3>
                </div>
                    <div id="service_<?=$arSection["ID"]?>" class="carousel carousel-3 slide animate-hover-slide">
                        <?if(count($arResult['ITEMS'][$arSection['ID']])>4):?>
                            <div class="carousel-nav">
                                <a data-slide="prev" class="left" href="#service_<?=$arSection["ID"]?>"><i class="fa fa-angle-left"></i></a>
                                <a data-slide="next" class="right" href="#service_<?=$arSection["ID"]?>"><i class="fa fa-angle-right"></i></a>
                            </div>
                        <?endif?>
                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="row">
                                    <?
                                    $i=0;
                                    foreach($arResult['ITEMS'][$arSection['ID']] as $key=>$arItem):?>
                                        <?
                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                        if($i && $i%4==0)echo"</div></div><div class='item'><div class='row'>";
                                        $i++;
                                        ?>
                                        <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="col-md-3 col-sm-3">
                                            <div class="wp-block article grid">
                                                <?if(is_array($arItem["PREVIEW_IMG"])):?>
                                                    <div class="article-image">
                                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img alt="<?=$arItem["NAME"]?>" src="<?=$arItem["PREVIEW_IMG"]["SRC"]?>" class="img-responsive"></a>
                                                    </div>
                                                <?endif?>
                                                <h3 class="title">
                                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" hidefocus="true"><?=$arItem["NAME"]?></a>
                                                </h3>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?
                endif;
            endforeach?>
    </div>
<?endif?>


