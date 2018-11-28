<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock"))
	return false;

$arResult["ERRORS"] = Array();

$arParams["IBLOCK_ID"] = IntVal($arParams["IBLOCK_ID"]);
$arParams["EMAIL"] = trim($arParams["EMAIL"]);
$arParams["EMAIL_SUBJ"] = trim($arParams["EMAIL_SUBJ"]);
$arParams["OK_MSG"] = trim($arParams["OK_MSG"]);

$arResult["AJAX_ID"] = "simai_ajax_feedback_".md5(implode($arParams));

if (!($USER->IsAuthorized())):
	include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
endif;

$rsET = CEventType::GetList(Array("TYPE_ID" => "SIMAI_FORM_FEEDBACK"));
if ($rsET->Fetch()):

else:
	$et = new CEventType;
	$et->Add(array(
		"LID" => LANGUAGE_ID,
		"EVENT_NAME" => "SIMAI_FORM_FEEDBACK",
		"NAME" => GetMessage("SIMAI_FORM_EVENT_NAME"),
		"DESCRIPTION" => GetMessage("SIMAI_FORM_EVENT_DESCRIPTION"),
	));
	
	$arSites = array();
	$sites = CSite::GetList(($b=""), ($o=""), Array("LANGUAGE_ID"=>LANGUAGE_ID));
	while ($site = $sites->Fetch()):
		$arSites[] = $site["LID"];
	endwhile;
	
	if(count($arSites) > 0):
		$emess = new CEventMessage;
		$emess->Add(array(
			"ACTIVE" => "Y",
			"EVENT_NAME" => "SIMAI_FORM_FEEDBACK",
			"LID" => $arSites,
			"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
			"EMAIL_TO" => "#EMAIL#",
			"SUBJECT" => "#EMAIL_SUBJ#",
			"BODY_TYPE" => "html",
			"MESSAGE" => "#MESSAGE#"
		));
	endif;
endif;
	

$arProps = array();
$arPropsT = array();
$arPropsR = array();
$arPropsD = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "PROPERTY_TYPE"=>"S", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
while ($arr = $rsProp->Fetch()):
	if ($arr["MULTIPLE"] != "Y"):
		$arProps[($arr["CODE"] != "" ? $arr["CODE"] : $arr["ID"])] = $arr["NAME"];
		if ($arr["USER_TYPE"] == "HTML" || $arr["USER_TYPE"] == "TEXT"):
			$arPropsT[($arr["CODE"] != "" ? $arr["CODE"] : $arr["ID"])] = $arr["NAME"];
		endif;
		if ($arr["IS_REQUIRED"] == "Y"):
			$arPropsR[($arr["CODE"] != "" ? $arr["CODE"] : $arr["ID"])] = $arr["NAME"];
		elseif ($arr["CODE"][0] == "_"):
			$arPropsD[($arr["CODE"] != "" ? $arr["CODE"] : $arr["ID"])] = $arr["NAME"];
		endif;
	endif;
endwhile;

if ($_POST['FB_SUBMIT_'.$arResult["AJAX_ID"]] != ''):
	if($_POST["js"]=="Y"){
	foreach($arPropsR as $field_code => $field_name):
		if (trim($_POST["PROP"][$field_code]) == ""):
			$arResult["ERRORS"][] = GetMessage("SIMAI_FORM_ERROR_MASSAGE_BEFOR")." &laquo;".$arPropsR[$field_code]."&raquo; ".GetMessage("SIMAI_FORM_ERROR_MASSAGE_AFTER");
		endif;
	endforeach;
	}
	else{
		$arResult["ERRORS"][]=getMessage("SIMAI_FORM_ERROR_JS");
	}

	if (!($USER->IsAuthorized()) && $arParams["USE_CAPTCHA"]=="Y"):
		$cpt = new CCaptcha();
		if (!$cpt->CheckCode($_POST["CAPTCHA_WORD"], $_POST["CAPTCHA_SID"])):
			$arResult["ERRORS"][] = GetMessage("SIMAI_FORM_CAPTCHA_ERROR_MASSAGE");
		endif;
	endif;

	if (count($arResult["ERRORS"]) == 0):
		$fb_name = GetMessage("SIMAI_FORM_MASSAGE_TITLE").date('d.m.Y, H:i');
		$fb_html = "";
		foreach($arProps as $field_code => $field_name):
			if (array_key_exists($field_code,$arPropsD)):
				$fb_html = $fb_html."<br><b>".$field_name."</b>";		
			else:
				$fb_html = $fb_html."<br><i>".$field_name."</i>: ".htmlspecialchars(strip_tags($_POST["PROP"][$field_code]),ENT_QUOTES);
			endif;
		endforeach;
		
		$props = Array();
		foreach ($_POST["PROP"] as $field=>$val):
			if (array_key_exists($field,$arPropsT)):
				$props[$field] = Array("VALUE"=>Array("TEXT"=>strip_tags(trim($val)),"TYPE"=>"text"));
			else:
				$props[$field] = strip_tags(trim($val));
			endif;
		endforeach;
		
		$el = new CIBlockElement;
		$arr = Array(
			"IBLOCK_SECTION" => false,
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ACTIVE" => "Y",            
			"NAME" => $fb_name,
			"PROPERTY_VALUES" => $props
		);
		if ($ID = $el->Add($arr)):
			
			$arEventFields = array(
            "EMAIL" => $arParams["EMAIL"],
            "EMAIL_SUBJ" => $arParams["EMAIL_SUBJ"],
            "MESSAGE" => $fb_html
        );
            CEvent::Send("SIMAI_FORM_FEEDBACK", SITE_ID, $arEventFields);

            $arResult["OK"] = true;
		else:
			$arResult["ERRORS"][] = GetMessage("SIMAI_FORM_BD_ERROR");
		endif;

	endif;
endif;

$arResult["FIELDS"] = $arProps;
$arResult["FIELDS_TXT"] = $arPropsT;
$arResult["FIELDS_REQ"] = $arPropsR;
$arResult["FIELDS_DIV"] = $arPropsD;

if (!$USER->IsAuthorized() && $arParams["USE_CAPTCHA"]=="Y"):
	$arResult["CAP_CODE"] = $GLOBALS["APPLICATION"]->CaptchaGetCode();
endif;

$this->IncludeComponentTemplate();
?>
