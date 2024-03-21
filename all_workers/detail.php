<? require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/header.php");
$APPLICATION->SetTitle("Список работников"); ?>

<?
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?> 
<?$APPLICATION->IncludeComponent("bitrix:forum.user.profile.edit", "workers", Array(
	"CACHE_TIME" => "7200",
		"CACHE_TYPE" => "A",	
		"SET_NAVIGATION" => "Y",	
		"SET_TITLE" => "Y",	
		"UID" => $_GET["id"],	
		"URL_TEMPLATES_PROFILE_VIEW" => "",	
		"USER_PROPERTY" => array(	
			0 => "UF_RULES",
			1 => "UF_RATING",
		),
		// "COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
<? require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/footer.php"); ?>