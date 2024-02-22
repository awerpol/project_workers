<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 */

$this->setFrameMode(true);

if($arResult['ITEMS']){
    if (!empty($arResult['NAV_RESULT']))
    {
        $navParams =  array(
            'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
            'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
            'NavNum' => $arResult['NAV_RESULT']->NavNum
        );
    }
    else
    {
        $navParams = array(
            'NavPageCount' => 1,
            'NavPageNomer' => 1,
            'NavNum' => $this->randString()
        );
    }

    $showBottomPager = true;

    if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
    {
        $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
        $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
        $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
    }
    ?>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">
    <?php foreach ($arResult['ITEMS'] as $item) {?>
        <div class="card">
            <div class="flex grow flex-col p-4">
                <div class="pt-2 line-clamp-2">
                    <a href="<?= $item["DETAIL_PAGE_URL"] ?>" class="text-base font-medium text-slate-700 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light"><?= $item["NAME"] ?></a>
                </div>

                <p class="grow pt-2">
                    <?= $item["PREVIEW_TEXT"] ?>
                </p>

                <div class="mt-3 text-right">
                    <button class="btn h-8 space-x-1.5 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                        <span> 32 </span>
                    </button>
                </div>
            </div>
        </div>

    <?php } ?></div>
<?php }

//region Pagination
if ($showBottomPager)
{?>
    <div class="row mb-4">
        <div class="col text-center" data-pagination-num="<?=$navParams['NavNum']?>">
            <!-- pagination-container -->
            <?=$arResult['NAV_STRING']?>
            <!-- pagination-container -->
        </div>
    </div>
    <?php
}?>



