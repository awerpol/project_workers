<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

$strReturn .= '<div class="flex items-center space-x-4 py-5 lg:py-6">';
$strReturn .= '<h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">';
$strReturn .= CMain::GetTitle();
$strReturn .= '</h2>';
$strReturn .= '<div class="hidden h-full py-1 sm:flex">';
$strReturn .= '<div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>';
$strReturn .= '</div>';
$strReturn .= '<ul class="hidden flex-wrap items-center space-x-2 sm:flex">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++) {
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1) {
		$strReturn .= '
			<li class="flex items-center space-x-2">
				<a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent" href="'.$arResult[$index]["LINK"].'">'.$title.'</a>
				<svg x-ignore xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
				</svg>
			</li>';
	}
	else {
		$strReturn .= '<li>'.$title.'</li>';
	}
}

$strReturn .= '</ul>';
$strReturn .= '</div>';

return $strReturn;
