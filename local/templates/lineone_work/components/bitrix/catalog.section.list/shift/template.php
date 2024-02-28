<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
if($arResult['SECTIONS']){?>
    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">
        <?php foreach ($arResult['SECTIONS'] as &$arSection) {
            if($arSection['RELATIVE_DEPTH_LEVEL'] == 1){?>
                <div class="card shadow-none">
                    <div class="flex flex-1 flex-col justify-between rounded-lg bg-info/15 p-4 dark:bg-transparent sm:p-5">
                        <div>
                            <div class="flex items-start justify-between">
                                <img class="h-12 w-12 rounded-lg object-cover object-center" src="<?= $arSection["PICTURE"]["SRC"];?>" alt="image"/>
                            </div>
                            <a href="<?= $arSection["SECTION_PAGE_URL"]; ?>" class="mt-3 font-medium text-slate-700 line-clamp-2 dark:text-navy-100">
                                <?= $arSection["NAME"];?>
                            </a>
                            <?php if($arSection["DESCRIPTION"]):?>
                                <p class="text-xs+"><?= $arSection["DESCRIPTION"] ?></p>
                            <?php endif;?>
                        </div>
                        <div class="mt-4">
                            <?php if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null && $arSection["ELEMENT_CNT"] > 0) { ?>
                                <div class="badge mt-2 bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                                    <?= $arSection["ELEMENT_CNT"]; ?> записей
                                </div>
                            <?php } ?>
                            <div class="badge mt-2 bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">
                                6 подписок
                            </div>
                            <div class="mt-5 flex items-center justify-between space-x-2">
                                <div class="flex -space-x-3">
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <img class="rounded-full ring ring-white dark:ring-navy-700" src="<?= SITE_TEMPLATE_PATH ?>/src-template/src/images/200x200.png" alt="avatar"/>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <div class="is-initial rounded-full bg-info text-xs+ uppercase text-white ring ring-white dark:ring-navy-700">
                                            iu
                                        </div>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <img class="rounded-full ring ring-white dark:ring-navy-700" src="<?= SITE_TEMPLATE_PATH ?>/src-template/src/images/200x200.png" alt="avatar"/>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <img class="rounded-full ring ring-white dark:ring-navy-700" src="<?= SITE_TEMPLATE_PATH ?>/src-template/src/images/200x200.png" alt="avatar"/>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <div class="is-initial rounded-full bg-info text-xs+ uppercase text-white ring ring-white dark:ring-navy-700">
                                            iu
                                        </div>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <img class="rounded-full ring ring-white dark:ring-navy-700" src="<?= SITE_TEMPLATE_PATH ?>/src-template/src/images/200x200.png" alt="avatar"/>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <img class="rounded-full ring ring-white dark:ring-navy-700" src="<?= SITE_TEMPLATE_PATH ?>/src-template/src/images/200x200.png" alt="avatar"/>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <div class="is-initial rounded-full bg-info text-xs+ uppercase text-white ring ring-white dark:ring-navy-700">
                                            iu
                                        </div>
                                    </div>
                                    <div class="avatar h-8 w-8 hover:z-10">
                                        <img class="rounded-full ring ring-white dark:ring-navy-700" src="<?= SITE_TEMPLATE_PATH ?>/src-template/src/images/200x200.png" alt="avatar"/>
                                    </div>
                                </div>
                                <button class="btn h-8 w-8 rounded-full bg-slate-150 p-0 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php }?>
    </div>
<?php }?>