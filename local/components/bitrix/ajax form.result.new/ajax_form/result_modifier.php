<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (trim($arParams["AJAX_CALL_VAR"]) == "")
{
	$arParams["AJAX_CALL_VAR"] = "wf_ajax_call";
}
$arParams["AJAX_CALL"] = ($_POST[$arParams["AJAX_CALL_VAR"]] == "y");

$arResult["FORM_HEADER_AJAX"] = CAjax::GetForm('name="' . $arResult["WEB_FORM_NAME"] . '" action="" method="POST" enctype="multipart/form-data"', 'ajax_w_form_' . $arResult["arForm"]["ID"], '1');

$arr = explode("<form ", $arResult["FORM_HEADER"]);
$arResult["FORM_HEADER_BEFORE"] = $arr[0];
unset($arr[0]);
$arr = implode("<form ", $arr);
$arr = explode(">", $arr);
unset($arr[0]);
$arResult["FORM_HEADER_AFTER"] = implode('>', $arr) . '<input type="hidden" name="' . $arParams["AJAX_CALL_VAR"] . '" value="y">';