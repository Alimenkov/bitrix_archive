<?if( !defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED!==true) ) die();

$arComponentDescription = array(
	"NAME"        => GetMessage("SIMAI_COMPONENT_FORM_FEEDBACK_NAME"),
	"DESCRIPTION" => GetMessage("SIMAI_COMPONENT_FORM_FEEDBACK_NAME_DESC"),
	"ICON"        => "/images/icon.gif",
	"SORT"        => 20,
	"CACHE_PATH"  => "Y",
	"PATH"        => array(
		"ID"    => "simai",
		"NAME"  => GetMessage("SIMAI_COMPONENTS_NAME"), // "SIMAI Components",
		"CHILD" => array(
			"ID"   => "simai_form",
			"NAME" => GetMessage("SIMAI_FORM_SECTION_NAME"), // "Form"
			"SORT" => 10,
		),
	),
);
?>
