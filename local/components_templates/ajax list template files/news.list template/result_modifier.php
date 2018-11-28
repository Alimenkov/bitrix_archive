<?
foreach($arResult["ITEMS"] as $key=>$arItem){
	if(is_array($arItem["PREVIEW_PICTURE"])){
		$arFileTmp = CFile::ResizeImageGet(
			$arItem["PREVIEW_PICTURE"],
			array("width" => 244, "height" => 195),
			BX_RESIZE_IMAGE_EXACT ,
			false
		);
		$arSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arFileTmp["src"]);
		$arResult["ITEMS"][$key]["PREVIEW_IMG"]= array(
			"SRC" => $arFileTmp["src"],
			"WIDTH" => IntVal($arSize[0]),
			"HEIGHT" => IntVal($arSize[1]),
		);
		}
		elseif(is_array($arItem["DETAIL_PICTURE"])){
			$arFileTmp = CFile::ResizeImageGet(
			$arItem["DETAIL_PICTURE"],
			array("width" => 244, "height" => 195),
			BX_RESIZE_IMAGE_EXACT ,
			false
		);
		$arSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arFileTmp["src"]);
		$arResult["ITEMS"][$key]["PREVIEW_IMG"]= array(
			"SRC" => $arFileTmp["src"],
			"WIDTH" => IntVal($arSize[0]),
			"HEIGHT" => IntVal($arSize[1]),
		);
		}
}
?>