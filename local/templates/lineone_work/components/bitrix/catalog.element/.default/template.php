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
 * @var string $templateFolder
 */

$this->setFrameMode(true);
?>
<div class="grid grid-cols-12 lg:gap-6">
    <div class="col-span-12 pt-6 lg:col-span-8 lg:pb-6">
        <div class="card p-4 lg:p-6">
            <!-- Author -->
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            x-data="usePopper({
                       offset: 12,
                       placement: 'bottom',
                       modifiers: [
                          {name: 'preventOverflow', options: {padding: 10}}
                       ]
                    })"
                            class="flex"
                            @mouseleave="isShowPopper = false"
                            @mouseenter="isShowPopper = true"
                        >
                            <div x-ref="popperRef" class="avatar h-12 w-12">
                                <img
                                    class="mask is-squircle"
                                    src="images/200x200.png"
                                    alt="avatar"
                                />
                            </div>
                            <div
                                x-ref="popperRoot"
                                class="popper-root"
                                :class="isShowPopper && 'show'"
                            >

                            </div>
                        </div>
                        <div>
                            <a href="#" class="font-medium text-slate-700 line-clamp-1 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light">
                                Travis Fuller
                            </a>
                            <div class="mt-1.5 flex items-center text-xs">
                                <span class="line-clamp-1">Jun 26</span>
                                <div
                                    class="mx-2 my-0.5 w-px self-stretch bg-white/20"
                                ></div>
                                <p class="shrink-0">8 min red</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blog Post -->
            <div class="mt-6 font-inter text-base text-slate-600 dark:text-navy-200">
                <h1 class="text-xl font-medium text-slate-900 dark:text-navy-50 lg:text-2xl">
                    <?= $arResult["NAME"]?>
                </h1>
                <?= $arResult["DETAIL_TEXT"] ?>
            </div>

        </div>

    </div>
</div>
