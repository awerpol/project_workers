<?php


AddEventHandler("main", "OnEndBufferContent", "deleteKernelJs");
AddEventHandler("main", "OnEndBufferContent", "deleteKernelCss");

function deleteKernelJs(&$content) {
    global $USER, $APPLICATION;
    if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
    if($APPLICATION->GetProperty("save_kernel") == "Y") return;

    $arPatternsToRemove = Array(
        '/<sc ript.+?src=".+?kernel_main\/kernel_main\.js\?\d+"><\/sc ript\>/',
        '/<sc ript.+?src=".+?bitrix\/js\/main\/core\/core[^"]+"><\/sc ript\>/',
        '/<sc ript.+?>BX\.(setCSSList|setJSList)\(\[.+?\]\).*?<\/sc ript>/',
        '/<sc ript.+?>if\(\!window\.BX\)window\.BX.+?<\/sc ript>/',
        '/<sc ript[^>]+?>\(window\.BX\|\|top\.BX\)\.message[^<]+<\/sc ript>/',
    );

    $content = preg_replace($arPatternsToRemove, "", $content);
    $content = preg_replace("/\n{2,}/", "\n\n", $content);
}

function deleteKernelCss(&$content) {
    global $USER, $APPLICATION;
    if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
    if($APPLICATION->GetProperty("save_kernel") == "Y") return;

    $arPatternsToRemove = Array(
        '/<li nk.+?href=".+?kernel_main\/kernel_main\.css\?\d+"[^>]+>/',
        '/<li nk.+?href=".+?bitrix\/js\/main\/core\/css\/core[^"]+"[^>]+>/',
        '/<li nk.+?href=".+?bitrix\/templates\/[\w\d_-]+\/styles.css[^"]+"[^>]+>/',
        '/<li nk.+?href=".+?bitrix\/templates\/[\w\d_-]+\/template_styles.css[^"]+"[^>]+>/',
    );

    $content = preg_replace($arPatternsToRemove, "", $content);
    $content = preg_replace("/\n{2,}/", "\n\n", $content);
}