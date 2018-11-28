<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-"=>" "));

$arIBlocks = Array();
$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch()):
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];
endwhile;

$arEvents = Array();
$db_events = CIBlock::GetList(Array("LID"=>LANGUAGE_ID));
while($arRes = $db_events->Fetch()):
	$arIBlocks[$arRes["TYPE_ID"]] = $arRes["NAME"];
endwhile;
	
$arComponentParameters = array(
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SIMAI_FORM_INFOBLOK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SIMAI_FORM_INFOBLOK_NAME"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"EMAIL" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SIMAI_FORM_TO_EMAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"EMAIL_SUBJ" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SIMAI_FORM_MESSAGE_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"OK_MSG" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SIMAI_FORM_SUCCESS_MESSAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"USE_CAPTCHA" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SIMAI_FORM_USE_CAPTCHA"),
			"TYPE" => "checkbox",
			"DEFAULT" => 'Y',
		)
	)
);

?>