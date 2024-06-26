<? require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/header.php");
$APPLICATION->SetTitle("Список смен");
$APPLICATION->SetPageProperty('title', "Список смен");?>


<? $APPLICATION->IncludeComponent(
	"bitrix:news",
	"shift",
	[
		"ADD_ELEMENT_CHAIN"               => "Y",
		"ADD_SECTIONS_CHAIN"              => "Y",
		"AJAX_MODE"                       => "N",
		"AJAX_OPTION_ADDITIONAL"          => "",
		"AJAX_OPTION_HISTORY"             => "N",
		"AJAX_OPTION_JUMP"                => "N",
		"AJAX_OPTION_STYLE"               => "N",
		"BROWSER_TITLE"                   => "-",
		"CACHE_FILTER"                    => "N",
		"CACHE_GROUPS"                    => "N",
		"CACHE_TIME"                      => "36000000",
		"CACHE_TYPE"                      => "A",
		"CATEGORY_CODE"                   => "CATEGORY",
		"CATEGORY_IBLOCK"                 => "",
		"CATEGORY_ITEMS_COUNT"            => "5",
		"CHECK_DATES"                     => "N",
		"COLOR_NEW"                       => "3E74E6",
		"COLOR_OLD"                       => "C0C0C0",
		"DETAIL_ACTIVE_DATE_FORMAT"       => "m.d.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER"     => "Y",
		"DETAIL_DISPLAY_TOP_PAGER"        => "N",
		"DETAIL_FIELD_CODE"               => ["NAME", ""],
		"DETAIL_PAGER_SHOW_ALL"           => "Y",
		"DETAIL_PAGER_TEMPLATE"           => "",
		"DETAIL_PAGER_TITLE"              => "Страница",
		"DETAIL_PROPERTY_CODE"            => ["SHIFT_IS_CTIVE", "SHIFT_START", "SHIFT_END", "WORKERS", ""],
		"DETAIL_SET_CANONICAL_URL"        => "Y",
		"DISPLAY_AS_RATING"               => "rating",
		"DISPLAY_BOTTOM_PAGER"            => "Y",
		"DISPLAY_DATE"                    => "Y",
		"DISPLAY_NAME"                    => "Y",
		"DISPLAY_PICTURE"                 => "Y",
		"DISPLAY_PREVIEW_TEXT"            => "Y",
		"DISPLAY_TOP_PAGER"               => "N",
		"FILTER_FIELD_CODE"               => [0 => "NAME", 1 => "",],
		"FILTER_NAME"                     => "",
		"FILTER_PROPERTY_CODE"            => [0 => "", 1 => "",],
		"FONT_MAX"                        => "50",
		"FONT_MIN"                        => "10",
		"FORUM_ID"                        => "1",
		"GROUP_PERMISSIONS"               => [0 => "1",],
		"HIDE_LINK_WHEN_NO_DETAIL"        => "N",
		"IBLOCK_ID"                       => "2",
		"IBLOCK_TYPE"                     => "SHIFT_WORK",
		"INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
		"LIST_ACTIVE_DATE_FORMAT"         => "d.m.Y",
		"LIST_FIELD_CODE"                 => ["", ""],
		"LIST_PROPERTY_CODE"              => ["SHIFT_IS_CTIVE", "SHIFT_START", "SHIFT_END", ""],
		"MAX_VOTE"                        => "5",
		"MEDIA_PROPERTY"                  => "",
		"MESSAGES_PER_PAGE"               => "10",
		"MESSAGE_404"                     => "",
		"META_DESCRIPTION"                => "-",
		"META_KEYWORDS"                   => "-",
		"NEWS_COUNT"                      => "20",
		"PAGER_BASE_LINK_ENABLE"          => "N",
		"PAGER_DESC_NUMBERING"            => "Y",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL"                  => "N",
		"PAGER_SHOW_ALWAYS"               => "N",
		"PAGER_TEMPLATE"                  => ".default",
		"PAGER_TITLE"                     => "Список смен",
		"PATH_TO_SMILE"                   => "/bitrix/images/forum/smile/",
		"PERIOD_NEW_TAGS"                 => "",
		"PREVIEW_TRUNCATE_LEN"            => "",
		"REVIEW_AJAX_POST"                => "N",
		"SEF_FOLDER"                      => "/shift/",
		"SEF_MODE"                        => "Y",
		"SEF_URL_TEMPLATES"               => [
			"detail"  => "#ELEMENT_ID#/",
			"news"    => "",
			"section" => ""
		],
		"SET_LAST_MODIFIED"               => "Y",
		"SET_STATUS_404"                  => "N",
		"SET_TITLE"                       => "Y",
		"SHOW_404"                        => "N",
		"SHOW_LINK_TO_FORUM"              => "N",
		"SLIDER_PROPERTY"                 => "",
		"SORT_BY1"                        => "ACTIVE_FROM",
		"SORT_BY2"                        => "SORT",
		"SORT_ORDER1"                     => "DESC",
		"SORT_ORDER2"                     => "ASC",
		"STRICT_SECTION_CHECK"            => "Y",
		"TAGS_CLOUD_ELEMENTS"             => "150",
		"TAGS_CLOUD_WIDTH"                => "100%",
		"TEMPLATE_THEME"                  => "blue",
		"URL_TEMPLATES_READ"              => "",
		"USE_CAPTCHA"                     => "N",
		"USE_CATEGORIES"                  => "N",
		"USE_FILTER"                      => "N",
		"USE_PERMISSIONS"                 => "N",
		"USE_RATING"                      => "N",
		"USE_REVIEW"                      => "N",
		"USE_RSS"                         => "N",
		"USE_SEARCH"                      => "N",
		"USE_SHARE"                       => "N",
		"VOTE_NAMES"                      => [0 => "1", 1 => "2", 2 => "3", 3 => "4", 4 => "5", 5 => "",],
		"IS_ARCHIVE"                      => "N"
	]
); ?>

<? require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/footer.php"); ?>