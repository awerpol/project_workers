<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>


<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">

		<!--  -->

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="card grow items-center p-4 sm:p-5" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <a href="<?= $arItem["DETAIL_PAGE_URL"]?>" class="pt-3 text-lg font-medium text-slate-700 dark:text-navy-100"> <?= $arItem["NAME"]?> </a>
        <p class="text-xs+"><?= $arItem["SHIFT_DATE"] ?></p>
        <!-- <p class="text-xs+">Пельменный цех</p> -->

        <p class="mt-3 text-xl font-medium text-slate-700 dark:text-white "><?= $arItem["SHIFT_PERIOD"] ?></p>
        <div class="my-4 h-px w-full bg-slate-200 dark:bg-navy-500"></div>

        <!-- блок с М/Ж-таблицей  -->
        <div class="grow space-y-4">
            <table class="is-hoverable w-full text-center">
                <thead>
                <tr>
                    <th class="whitespace-nowrap rounded-l-lg bg-slate-200 px-3 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        М
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                        Ж
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr class="dark:border-b-navy-500">
                    <td class="whitespace-nowrap rounded-l-lg px-4 py-3 sm:px-5">
                        <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                            <?= $arItem['PROPERTIES']['SHIFT_COUNT_M']['VALUE'] ?>
                        </p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                            <?= $arItem['PROPERTIES']['SHIFT_COUNT_F']['VALUE'] ?>
                        </p>
                    </td>
                </tr>
                <tr class="dark:border-b-navy-500">
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <p class="text-xl text-slate-700 dark:text-navy-100">
                            <?= $arItem["MEN_COUNT"]?>
                        </p>
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                        <p class="text-xl text-slate-700 dark:text-navy-100">
                            <?= $arItem["WOMEN_COUNT"]?>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4"> 
			<button class="btn mt-5 space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
				<a href="<?= $arItem["DETAIL_PAGE_URL"]?>">
					<span>Подробно</span>
				</a>
			</button>		

			<? if($arItem["PROPERTIES"]["SHIFT_IS_CTIVE"]["VALUE"]=="Y"): ?>
				<button class="btn mt-5 space-x-2 border border-primary font-medium text-primary hover:bg-primary hover:text-white focus:bg-primary focus:text-white active:bg-success/90">
				<a href="<?= $arItem["DETAIL_PAGE_URL"]?>">
						<span>Редактировать</span>
					</a>
				</button>
			<?endif;?>

		</div>
    </div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
<!-- </div> -->
