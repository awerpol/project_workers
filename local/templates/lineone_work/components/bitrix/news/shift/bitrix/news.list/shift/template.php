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


<?
// echo '<pre>';
// // var_dump($arParams['IS_ARCHIVE']);
// var_dump($arResult["ITEMS"]);
// echo '</pre>';
?>
<div x-data="{showModal:false}">

<!-- шапка  -->
<!-- <div class="flex items-center space-x-2 mx-3 py-5 lg:py-6" > -->
<!-- Добавление смены - всплывающая форма -->
	<?php if ($arParams['IS_ARCHIVE'] !== 'Y'): ?>
	<div class="flex items-right space-x-2 " style="margin-bottom:10px;">
	<!-- <div class="flex items-right space-x-2 " x-data="{showModal:false}" style="margin-bottom:10px;"> -->
	
		<button @click="showModal = true" 
			class="editButton btn bg-info font-medium text-white hover:bg-info-focus hover:shadow-lg hover:shadow-info/50 focus:bg-info-focus focus:shadow-lg focus:shadow-info/50 active:bg-info-focus/90"
			data-id="0"
		> 
			Добавить смену 
		</button>
			<template x-teleport="#x-teleport-target">
				<div id="shiftCreateForm" 
					class="fixed inset-0 z-[100] flex flex-col items-center justify-center overflow-hidden px-4 py-6 sm:px-5"
					x-show="showModal"
					role="dialog"
					@keydown.window.escape="showModal = false"
				>
					<div
						class="absolute inset-0 bg-slate-900/60 transition-opacity duration-300"
						@click="showModal = false"
						x-show="showModal"
						x-transition:enter="ease-out"
						x-transition:enter-start="opacity-0"
						x-transition:enter-end="opacity-100"
						x-transition:leave="ease-in"
						x-transition:leave-start="opacity-100"
						x-transition:leave-end="opacity-0"
					></div>
					<div
					class="relative w-full max-w-lg max-h-full overflow-y-auto origin-top rounded-lg bg-white transition-all duration-300 dark:bg-navy-700"
			
					x-show="showModal"
					x-transition:enter="easy-out"
					x-transition:enter-start="opacity-0 scale-95"
					x-transition:enter-end="opacity-100 scale-100"
					x-transition:leave="easy-in"
					x-transition:leave-start="opacity-100 scale-100"
					x-transition:leave-end="opacity-0 scale-95"
					>
						<div
						class="flex justify-between rounded-t-lg bg-slate-200 px-4 py-3 dark:bg-navy-800 sm:px-5">
							<h3 id="shiftEditH" class="text-base font-medium text-slate-700 dark:text-navy-100">
								Редактирование смены
							</h3>
						<button @click="showModal = !showModal" class="btn -mr-1.5 h-7 w-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								class="h-4.5 w-4.5"
								fill="none"
								viewBox="0 0 24 24"
								stroke="currentColor"
								stroke-width="2"
							>
								<path
									stroke-linecap="round"
									stroke-linejoin="round"
									d="M6 18L18 6M6 6l12 12"
								></path>
							</svg>
						</button>
						</div>
					<div class="px-4 py-4 sm:px-5">
						<p>
							Для начала работы с новой сменой заполните данные из заказа.
						</p>
						<div class="mt-4 space-y-4">
							<label class="block">
								<span>Заказчик:</span>
								<select id="shiftCreateForm_client" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
									<?php foreach ($arResult["CLIENTS"] as $client): ?>
										<option value="<?= $client['ID'] ?>"><?= htmlspecialcharsbx($client['NAME']) ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						<!-- <label class="block">
						<span>Адрес:</span>
						<input
							class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
							placeholder="(цех)"
							type="text"
						/>
						</label> -->
							<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">  
								<label class="block">
									<span>Дата:</span>
										<?php
											$today = new DateTime();
											$formattedDate = $today->format('d.m.Y');
										?>
									<input id="shiftCreateForm_date" x-input-mask="{
										date: true,
										delimiter: '.',
										datePattern: ['d', 'm','Y']
										}"
										value="<?php echo $formattedDate; ?>"
										class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
										placeholder="<?php echo $formattedDate; ?>"
										type="text"
									/>
								</label>
									<?php
										$currentTime = date("H:i"); // текущее время
										// Устанавливаем значение по умолчанию в зависимости от текущего времени
										if ($currentTime < "12:00") {
											$defaultValue = "20:00";
										} else {
											$defaultValue = "08:00";
										}
									?>
								<label class="block">
									<span>Время начала:</span>
									<input id="shiftCreateForm_startTime"
										x-input-mask="{time: true,timePattern: ['h', 'm']}"
										class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
										placeholder="00:00"
										type="text"
										value="<?php echo $defaultValue; ?>"
									/>
								</label>
								<label class="block">
									<span>Длительность:</span>
										<input id="shiftCreateForm_duration"
											x-input-mask="{numeral: true,}"
											class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
											placeholder="12"
											type="number"
											inputmode="numeric"
											min="0"
											value="12"
										/>
								</label>
							</div>
							<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">  
								<label class="block">
									<span>Мужчин:</span>
									<input id="shiftCreateForm_needM"
										x-input-mask="{numeral: true,}"
										class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
										placeholder="0"
										type="number"
										inputmode="numeric"
										min="0"
									/>
								</label>
								<label class="block">
									<span>Женщин:</span>
									<input id="shiftCreateForm_needF"
										x-input-mask="{numeral: true,}"
										class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
										placeholder="0"
										type="number"
										inputmode="numeric"
										min="0"
									/>
								</label>
							</div>

<!-- <label class="block">
<span>Описание:</span>
<textarea id="shiftCreateForm_description"
	rows="4"
	placeholder="Введите комментарий"
	class="form-textarea mt-1.5 w-full resize-none rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
></textarea>
</label> -->

							<!-- <label class="inline-flex items-center space-x-2">
								<input id="shiftCreateForm_startSetting"
									class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-transparent before:rounded-full before:bg-slate-300 checked:border-primary checked:before:bg-primary dark:border-navy-400 dark:before:bg-navy-300 dark:checked:border-accent dark:checked:before:bg-accent"
									type="checkbox"
									checked 
								/>
								<span>Запустить набор</span>
							</label> -->
							<input type="hidden" id="hiddenShiftId" value="0">

							<div class="flex justify-end p-4 sm:p-5 space-x-2 text-right">
								<button @click="showModal = false" class="btn min-w-[7rem] rounded-full border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
									Отмена
								</button>
								<button 
									id="shiftCreateForm_saveButton" 
									@click="showModal = false" 
									class="btn min-w-[7rem] rounded-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
										Сохранить
								</button>
					
							</div>
					</div>
				</div>
				</div>
				</div>
			</template>
	</div>
	<?php endif; //($arParams['IS_ARCHIVE'] != 'Y') ?>



<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">

		<!--  -->

<?foreach($arResult["ITEMS"] as $arItem):?>
	
	<?  if ($arParams['IS_ARCHIVE'] == 'Y' XOR $arItem["PROPERTIES"]["SHIFT_STAGE"]["VALUE_XML_ID"] == "FORMING"): ?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
	<!-- карточка смены -->
    <div 
		class="Shift card grow items-center p-4 sm:p-5" 
		id="<?=$this->GetEditAreaId($arItem['ID']);?>"
		data-item="<?= htmlspecialchars(json_encode($arItem)) ?>"
	>
		<div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 place-self-start">
			<div class="text-xs+" ><?= $arItem['ID'] ?></div>
			<!-- пометка "в работе" -->
			<?  if($arItem["PROPERTIES"]["SHIFT_STAGE"]["VALUE_XML_ID"] == "IN_WORK"): ?>
				<div class="text-right text-success">в работе</div>
			<?endif;?>
		</div>
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
				<a href="<?= $arItem["DETAIL_PAGE_URL"]?>" class="btn mt-5 space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
					Подробно
				</a>
			<?  // if($arItem["PROPERTIES"]["SHIFT_IS_CTIVE"]["VALUE"] == "Y"): // отказались от этого поля
				// резактировать можно только ФОРМИРУЕМЫЕ смены
				if ($arItem["PROPERTIES"]["SHIFT_STAGE"]["VALUE_XML_ID"] == "FORMING"):
			?>
				<button @click="showModal = true"
					class="editButton btn mt-5 space-x-2 border border-primary font-medium text-primary hover:bg-primary hover:text-white focus:bg-primary focus:text-white active:bg-success/90" 
					data-id="<?= $arItem['ID'] ?>"
					>
						Редактировать
				</button>
			<?endif;?>
		</div>
    </div>
	<?endif;?>
<?endforeach;?>

</div> <!-- закрытие дива с x-data -->



<script>
	var arItems = <?= json_encode($arResult["ITEMS"]) ?>;
</script>

<?
// echo '<pre>';
// var_dump($arResult["ITEMS"][1]["PROPERTIES"]);
// echo '</pre>';
?>



<script>
	// заполняем форму данными - из смены или по умолчанию
	$(document).ready(function () {
		$(".editButton").click(function () {

			var itemId = $(this).data('id');

			if (itemId == 0) {
				var { startDate, startTime } = defaultStartTime();
				var duration = 12;
				var needM = 0;
				var needF = 0;
				var client = 1; // !!!! МАТЬ ЕГО ХАРДКОД

			} else {
				// Находим соответствующий элемент массива $arItem по ID
				var foundItem = getArItemById(itemId);

				var duration = foundItem["SHIFT_DURATION"];
				var needM = foundItem["PROPERTIES"]["SHIFT_COUNT_M"]["VALUE"];
				var needF = foundItem["PROPERTIES"]["SHIFT_COUNT_F"]["VALUE"];
				var client = foundItem["PROPERTIES"]["CLIENT"]["VALUE"];
				var shiftStart = foundItem["PROPERTIES"]["SHIFT_START"]["VALUE"];
				var { startDate, startTime } = parseShiftDateTime(shiftStart);
			}

			// Устанавливаем значения в поля ввода
			$('#shiftCreateForm_startTime').val(startTime);
			$('#shiftCreateForm_date').val(startDate);
			$('#shiftCreateForm_duration').val(duration);
			$('#shiftCreateForm_needM').val(needM);
			$('#shiftCreateForm_needF').val(needF);
			$('#shiftCreateForm_client').val(client);
			$('#hiddenShiftId').val(itemId);
		

			// потом закомментировать
			// $('#shiftCreateForm_description').val(itemId);
		});
	});

	// функция расчета даты-времени по умолчанию 
	function defaultStartTime () {
		// Получить текущую дату и время
		var currentDate = new Date();

		// Получить текущие часы
		var currentHour = currentDate.getHours();

		// Определить startTime и startDate
		var startTime;
		var startDate;

		if (currentHour < 12) {
			startTime = "20:00:00";
			startDate = currentDate.toLocaleDateString('ru-RU', { year: 'numeric', month: 'numeric', day: 'numeric' });
		} else {
			startTime = "08:00:00";
			// Добавить 1 день к текущей дате
			startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + 1).toLocaleDateString('ru-RU', { year: 'numeric', month: 'numeric', day: 'numeric' });
		}
		return { startDate, startTime };
	}

	// функция парсинга даты начала
	function parseShiftDateTime(shiftStart) {
		var [day, month, year, hours, minutes, seconds] = shiftStart.split(/[\s.:]/).map(Number);
		var startDateObject = new Date(year, month - 1, day, hours, minutes, seconds);
		var startDate = startDateObject.toLocaleDateString('ru-RU', { year: 'numeric', month: 'numeric', day: 'numeric' });
		var startTime = startDateObject.toLocaleTimeString('en-US', { hour12: false, hourCycle: 'h24' });
		
		return { startDate, startTime };
	}

	// Функция для поиска элемента массива $arItem по ID
    function getArItemById(itemId) {
        for (var i = 0; i < arItems.length; i++) {
            if (arItems[i]['ID'] == itemId) {
                return arItems[i];
            }
        }
    }

</script>





<script>
	// Создание Смены
	$(document).ready(function () {
		$("#shiftCreateForm_saveButton").click(function () {
			// Получаем значения из полей формы
			var id = $('#hiddenShiftId').val(); 
			var client = $('#shiftCreateForm_client').val();
            var date = $('#shiftCreateForm_date').val();
            var startTime = $('#shiftCreateForm_startTime').val();
            var duration = $('#shiftCreateForm_duration').val();
            var needM = $('#shiftCreateForm_needM').val();
            var needF = $('#shiftCreateForm_needF').val();
			var startSetting = $('#shiftCreateForm_startSetting').prop('checked'); // галочка
			
			var action =  (id != '0') ? 'editShift' : 'newShift';

			var xhrEdit = null;
			xhrEdit = $.ajax({
				type: 'POST',
				url: '<?= SITE_TEMPLATE_PATH ?>/responds/shiftEdit.php',
				dataType: 'json',
				data: {
					todo: action,
					id: id,
					client: client,
					date: date,
					startTime: startTime,
					duration: duration,
					needM: needM,
            		needF: needF,
					startSetting: startSetting 
				},
				success: function (response) {
					location.reload();
				}
			});

		});
	});
</script>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
<!-- </div> -->
