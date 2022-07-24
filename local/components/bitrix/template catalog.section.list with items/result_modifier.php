<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!empty($arResult["SECTIONS"]))
{
    $arSectId=array();
    foreach($arResult["SECTIONS"] as $arSection)
    {
        $arSectId[]=$arSection["ID"];
    }
    $arResult["ITEMS"]=array();
    $arSelect = Array("ID","NAME","DETAIL_PAGE_URL","PREVIEW_PICTURE","DETAIL_PICTURE");
    $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"],"SECTION_ID"=>$arSectId, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
        $arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK_ID"],$ob["ID"],0,array("SECTION_BUTTONS"=>false, "SESSID"=>false));
        $ob["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $ob["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
        if($ob["PREVIEW_PICTURE"])
        {
            $arFileTmp = CFile::ResizeImageGet(
                $ob["PREVIEW_PICTURE"],
                array("width" => 325, "height" => 260),
                BX_RESIZE_IMAGE_EXACT,
                false
            );
            $arSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arFileTmp["src"]);
            $ob["PREVIEW_IMG"] = array(
                "SRC" => $arFileTmp["src"],
                "WIDTH" => IntVal($arSize[0]),
                "HEIGHT" => IntVal($arSize[1]),
            );
        }
        elseif($ob["DETAIL_PICTURE"])
        {
            $arFileTmp = CFile::ResizeImageGet(
                $ob["DETAIL_PICTURE"],
                array("width" => 325, "height" => 260),
                BX_RESIZE_IMAGE_EXACT,
                false
            );
            $arSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arFileTmp["src"]);
            $ob["PREVIEW_IMG"]= array(
                "SRC" => $arFileTmp["src"],
                "WIDTH" => IntVal($arSize[0]),
                "HEIGHT" => IntVal($arSize[1]),
            );
        }
        if($ob["IBLOCK_SECTION_ID"])
        {
            $arResult["ITEMS"][$ob["IBLOCK_SECTION_ID"]][]=$ob;
        }
    }
}