<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
$APPLICATION->SetPageProperty('title', $arResult[ "NAME" ]);
?>

<div class="grid grid-cols-12 gap-4">
    <!-- блок с таблицей слева -->
    <div class="col-span-12   lg:col-span-5">

        <!-- Шапка таблицы -->
        <div class="flex items-center justify-between mt-4 ">

            <div class="flex items-center justify-between">
                <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                    <!-- #<?= $arResult[ "ID" ] ?>. <strong><?= $arResult[ "NAME" ] ?></strong> -->
                    <?= $arResult[ "PROPERTIES" ][ "SHIFT_START" ][ "VALUE" ] ?>
                </h2>
            </div>
            <!-- <div class="flex items-center space-x-2">
                 <p><?= $arResult[ "SHIFT_PERIOD" ] ?> </p>
                <div>
                    <p>Заказ</p>
                    <p>Набрано</p>
                </div>

            </div> -->
            <div class="flex items-center space-x-2">
                <div>
                    <p class="text-xs text-slate-600 dark:text-navy-100"><br></p>
                    <h2 class="text-base font-medium text-slate-900 dark:text-navy-100">
                        Заказ:
                    </h2>
                    <p class="text-xs font-medium text-slate-600 dark:text-navy-100">
                        Набрано:
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-slate-600 dark:text-navy-100"> М</p>
                    <h2 class="text-base font-medium text-slate-900 dark:text-navy-100">
                        <?= $arResult[ "PROPERTIES" ][ "SHIFT_COUNT_M" ][ "VALUE" ] ?>
                    </h2>
                    <p class="text-xs font-medium text-slate-600 dark:text-navy-100">
                        <?= $arResult[ "MEN_COUNT" ] ?>
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-slate-600 dark:text-navy-100"> Ж</p>
                    <h2 class="text-base font-medium text-slate-900 dark:text-navy-100">
                        <?= $arResult[ "PROPERTIES" ][ "SHIFT_COUNT_F" ][ "VALUE" ] ?>
                    </h2>
                    <p class="text-xs font-medium text-slate-600 dark:text-navy-100">
                        <?= $arResult[ "WOMEN_COUNT" ] ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Таблица -->
        <div class="card mt-3">
            <div class="is-scrollbar-hidden min-w-max overflow-x-auto">
                <table class="is-hoverable min-w-max w-full text-left">
                    <thead class="basis-full">
                    <tr>
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                            <!-- <label class="flex h-8 w-8 items-center justify-center" x-tooltip="'Select All'">
                                <input
                                        class="form-checkbox is-basic h-5 w-5 rounded border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent"
                                        type="checkbox"
                                />
                            </label> -->
                            
                        </th>
                       
                        <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                            #
                        </th>
                        <th
                                class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4"
                        >
                            пол
                        </th>
                        <th
                                class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4"
                        >
                            ФИО
                        </th>

                        <th
                                class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4"
                        >
                            Телефон
                        </th>
                        <th
                                class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4"
                        >
                            <div class="h-7 w-7 flex items-center justify-center rounded-full bg-current text-primary dark:text-accent-light">
                                <i class="fa-solid fa-paper-plane text-white text-xs"></i>
                                
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tableListSelectedUsers">
                    <? foreach ($arResult[ "WORKERS" ] as $arUser): ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                            <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                                <label
                                        class="flex h-8 w-8 items-center justify-center"
                                        x-tooltip="'Select'"
                                >
                                    <input user-id="<?= $arUser[ "ID" ] ?>"
                                           class="form-checkbox is-outline h-4.5 w-4.5 rounded border-slate-400/70 before:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:before:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent"
                                           type="checkbox"
                                    />
                                </label>
                            </td>
                            <td class="whitespace-nowrap px-2 py-3 sm:px-4"><?= $arUser[ "ID" ] ?></td>
                            <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                                <?= $arUser[ "PERSONAL_GENDER" ] ?>
                            </td>
                            <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                                <?= $arUser[ "NAME" ]." ".$arUser[ "LAST_NAME" ] ?>
                            </td>
                            <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                                <?= $arUser[ "PERSONAL_PHONE" ] ?>
                            </td>

                            <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                                <div
                                        class="badge space-x-2.5 px-0 text-primary dark:text-accent-light"
                                >
                                    <div class="h-3 w-3 rounded-full bg-current"></div>
                                </div>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?  // if ($arResult[ 'PROPERTIES' ][ 'SHIFT_IS_CTIVE' ][ 'VALUE' ] == 'Y'): 
        // кнопки только у ФОРМИРУЕМЫХ смен
        if ($arResult[ 'PROPERTIES' ][ 'SHIFT_STAGE' ][ 'VALUE_XML_ID' ] == "FORMING"):
    ?>
        
        <!-- блок с кнопками посередине -->
        <div class="col-span-12 md:col-span-2 lg:col-span-2 ">

            <div class="flex items-center mt-1 lg:mt-10">
                <div class="flex items-center mt-1 lg:mt-6">
                </div>
            </div>
            <!-- Кнопка Заполнить -->
            <div class="flex flex-col items-center">
                <div class="flex items-center w-full p-1 my-1">
                    <button id="fillUsersInWork"
                            class="w-full btn border border-primary font-medium text-primary hover:bg-primary hover:text-white focus:bg-primary focus:text-white active:bg-primary/90 dark:border-accent dark:text-accent-light dark:hover:bg-accent dark:hover:text-white dark:focus:bg-accent dark:focus:text-white dark:active:bg-accent/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                  d="M10 19l-7-7 7-7M20 19l-7-7 7-7"/>
                        </svg>
                        <span style="margin-left: 0.5rem;">Заполнить</span>
                    </button>
                </div>
                <!-- Кнопка Уведомить -->
                <div class="flex  items-center w-full p-1 my-1">
                    <button class="w-full btn border border-info font-medium text-info hover:bg-info hover:text-white focus:bg-info focus:text-white active:bg-info/90">
                        <div class="h-5 w-5 flex items-center justify-center rounded-full bg-current text-primary dark:text-accent-light">
                            <i class="fa-solid fa-paper-plane text-white text-xs"></i>
                        </div>
                        <span style="margin-left: 0.5rem;"> Уведомить</span>
                    </button>
                </div>
                <!-- Кнопка Добавить -->
                <div class="flex  items-center w-full p-1 my-1 mt-1 md:mt-10 lg:mt-10">
                    <button id="addUsersInWork"
                            class="w-full btn border border-success font-medium text-success hover:bg-success hover:text-white focus:bg-success focus:text-white active:bg-success/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 19l-7-7 7-7"/>
                        </svg>
                        <span style="margin-left: 0.5rem;"> Добавить</span>
                    </button>
                </div>

                <!-- Кнопка Убрать -->
                <div class="flex items-center w-full p-1 my-1">
                    <button id="deleteUsersInWork"
                            class="w-full btn border border-error font-medium text-error hover:bg-error hover:text-white focus:bg-error focus:text-white active:bg-error/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                  d="M10 5l7 7-7 7"/>
                        </svg>
                        <span style="margin-left: 0.5rem;">Убрать</span>
                    </button>
                </div>

                <!-- Кнопка Похвалить/поругать -->
                <div class="flex  items-center w-full p-1 my-1 mt-1 md:mt-10 lg:mt-10" x-data="{showModal:false}">
                    <button @click="showModal = true"
                            class="w-full btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <span style="margin-left: 0.5rem;">Оценить</span>
                    </button>
                    <template x-teleport="#x-teleport-target">
                        <div
                                class="fixed inset-0 z-[100] flex flex-col items-center justify-center overflow-hidden px-4 py-6 sm:px-5"
                                x-show="showModal"
                                role="dialog"
                                @keydown.window.escape="showModal = false"
                        >

                            <div
                                    class="relative w-full max-w-md origin-top rounded-lg bg-white transition-all duration-300 dark:bg-navy-700"
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
                                    <h3 class="text-base font-medium text-slate-700 dark:text-navy-100">
                                        Добавление оценки сотруднику
                                    </h3>
                                    <button @click="showModal = !showModal"
                                            class="btn -mr-1.5 h-7 w-7 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
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
                                        Всем отмеченным сотрудникам будет добавлена выбранная оценка:
                                    </p>
                                    <div class="mt-4 space-y-4">
                                        <label class="block">
                                            <!-- <span>Оценка:</span> -->
                                            <select id="carmaSet" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <?php foreach ($arResult["CARMA_CASES"] as $case): ?>
										            <option value="<?= $case['VALUE'] ?>"  data-name="<?= htmlspecialcharsbx($case['NAME']) ?>">
                                                        <?= ($case['NUM'] > 0 ? '+' : '') . htmlspecialcharsbx($case['NUM'])?>&nbsp;&nbsp;<?= htmlspecialcharsbx($case['NAME']) ?>
                                                    </option>
									            <?php endforeach; ?>
                                            </select>
                                        </label>

                                        <!-- <label class="block">
                                          <span>Описание:</span>
                                          <textarea
                                            rows="4"
                                            placeholder="Введите комментарий"
                                            class="form-textarea mt-1.5 w-full resize-none rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                          ></textarea>
                                        </label> -->

                                        <label class="inline-flex items-center space-x-3">
                                            <span><? echo date("d.m.Y"); ?></span>
                                        </label>
                                        <div class="space-x-2 text-right">
                                            <button
                                                    @click="showModal = false"
                                                    class="btn min-w-[7rem] rounded-full border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90"
                                            >
                                                Отмена
                                            </button>
                                            <button
                                                    id="rateThem"
                                                    @click="showModal = false"
                                                    class="btn min-w-[7rem] rounded-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"
                                            >
                                                Сохранить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>


<?
//echo '<pre>';
//var_dump($arResult["CARMA_CASES"]);
//echo '</pre>';
?>


        <!-- блок с таблицей справа -->
        <div class="col-span-12 lg:col-span-5">


            <!-- Шапка таблицы -->
            <div class="flex items-center justify-between mt-4 ">

                <div class="flex items-center justify-between">
                    <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                        Доступные работники
                    </h2>
                </div>

                <div class="flex items-center space-x-2">
                    <div>
                        <p class="text-xs text-slate-600 dark:text-navy-100"> М</p>
                        <h2 class="text-base font-medium text-slate-900 dark:text-navy-100" id="countFreeM"> -</h2>
                    </div>
                    <div>
                        <p class="text-xs text-slate-600 dark:text-navy-100"> Ж</p>
                        <h2 class="text-base font-medium text-slate-900 dark:text-navy-100" id="countFreeF"> -</h2>
                    </div>
                </div>
            </div>

            <!-- Таблица -->
            <div id="tablefreeuser" class="card">
                <div class="is-scrollbar-hidden min-w-max overflow-x-auto overflow-y-auto max-h-80 mt-4">

                    <table x-ref="table" class="is-hoverable w-full text-left">
                        <thead class="basis-full">
                        <tr>
                            <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                            </th>
                            <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                                #
                            </th>
                            <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                                пол
                            </th>
                            <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                                ФИО
                            </th>

                            <!-- <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                                Телефон
                            </th> -->
                            <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                                Рейтинг
                            </th>
                            <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                                Карма
                            </th>
                        </tr>
                        </thead>
                        <tbody id="listFreeUser">

                        </tbody>
                    </table>
                </div>
                <div>
                    <div x-ref="wrapper"></div>
                </div>


            </div>
        </div>

        <script>
            // удаляем пользователя из левой таблицы
            $(document).ready(function () {
                $("#deleteUsersInWork").click(function () {
                    var selectedD = [];
                    $('#tableListSelectedUsers .form-checkbox:checked').each(function () {
                        selectedD.push($(this).attr('user-id'));
                    });
                    var xhrDelete = null;
                    xhrDelete = $.ajax({
                        type: 'POST',
                        url: '<?= SITE_TEMPLATE_PATH ?>/responds/updateShiftUsers.php',
                        dataType: 'json',
                        data: {
                            todo: 'deleteUser',
                            userList: selectedD,
                            shiftID: <?= $arResult[ "ID" ] ?>,
                            oldListUser: '<?= implode(",", $arResult[ "LIST_USER_ID" ]) ?>'
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });

                });
            });
        </script>
        
        <script>
            // src='<?= SITE_TEMPLATE_PATH ?>/src-template/src/js/magics/notification.js';
            // добавляем пользователя в левую таблицу
            $(document).ready(function () {

                // ограничиваем чекбоксы, если выбирают больше, чем нужно
                $('#tablefreeuser').on('change', '.form-checkbox', function() {
                    var selectedM = $('#tablefreeuser .form-checkbox:checked').filter(function() {
                        return $(this).attr('user-gender') === 'M';
                    });

                    var selectedF = $('#tablefreeuser .form-checkbox:checked').filter(function() {
                        return $(this).attr('user-gender') === 'Ж';
                    });

                    var needM = <?= $arResult["PROPERTIES"]["SHIFT_COUNT_M"]["VALUE"] ?> - <?= $arResult["MEN_COUNT"] ?>;
                    var needF = <?= $arResult["PROPERTIES"]["SHIFT_COUNT_F"]["VALUE"] ?> - <?= $arResult["WOMEN_COUNT"] ?>;

                    if (selectedM.length > needM || selectedF.length > needF) {
                        var message = selectedM.length > needM ? 
                            "Максимальное количество мужчин превышено" : 
                            "Максимальное количество женщин превышено";

                        alert(message); // как сделать красивый custom-alert или notification?
                        // notification({text:'This is a simple notification',variant:'error',position:'center-top'});
                        // $notification({text:'This is a simple notification',variant:'error',position:'center-top'});
                        $(this).prop('checked', false);
                        return;
                    }
                });

                $("#addUsersInWork").click(function () {
                    var selected = [];

                    $('#tablefreeuser .form-checkbox:checked').each(function () {
                        selected.push($(this).attr('user-id'));
                    });

                    var xhrUpdate = null;
                    xhrUpdate = $.ajax({
                        type: 'POST',
                        url: '<?= SITE_TEMPLATE_PATH ?>/responds/updateShiftUsers.php',
                        dataType: 'json',
                        data: {
                            todo: 'addUser',
                            userList: selected,
                            shiftID: <?= $arResult[ "ID" ] ?>,
                            oldListUser: '<?= implode(",", $arResult[ "LIST_USER_ID" ]) ?>'
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });

                });
            });
        </script>

        <script>
            // автоматически заполняем левую таблицу
            $(document).ready(function () {
                $("#fillUsersInWork").click(function () {
                    var needM = <?= $arResult["PROPERTIES"]["SHIFT_COUNT_M"]["VALUE"] ?> - <?= $arResult["MEN_COUNT"] ?>;
                    var needF = <?= $arResult["PROPERTIES"]["SHIFT_COUNT_F"]["VALUE"] ?> - <?= $arResult["WOMEN_COUNT"] ?>;

                    var xhrFill = null;
                    xhrFill = $.ajax({
                        type: 'POST',
                        url: '<?= SITE_TEMPLATE_PATH ?>/responds/updateShiftUsers.php',
                        dataType: 'json',
                        data: {
                            todo: 'fillUsers',
                            userList: [0],
                            shiftID: <?= $arResult[ "ID" ] ?>,
                            oldListUser: '<?= implode(",", $arResult[ "LIST_USER_ID" ]) ?>',
                            needM: needM,
                            needF: needF
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });

                });
            });
        </script>

        <script>
            // выводим пользователей в правую таблицу
            $(document).ready(function () {
                //переполнение М/Ж слева
                var needM = <?= $arResult["PROPERTIES"]["SHIFT_COUNT_M"]["VALUE"] ?> > <?= $arResult["MEN_COUNT"] ?>;
                var needF = <?= $arResult["PROPERTIES"]["SHIFT_COUNT_F"]["VALUE"] ?> > <?= $arResult["WOMEN_COUNT"] ?>;

                var xhr = null;
                xhr = $.ajax({
                    type: 'POST',
                    url: '<?= SITE_TEMPLATE_PATH ?>/responds/getUsers.php',
                    dataType: 'json',
                    data: {
                        getUser: 'Y',
                        listUser: '<?=  implode(",", $arResult[ "LIST_USER_ID" ]) ?>',
                        shiftID: <?= $arResult[ "ID" ] ?>,
                        needM: needM,
                        needF: needF
                    },
                    success: function (response) {
                        $('#countFreeM').html(response.resultM['COUNT_GENDER_M']);
                        $('#countFreeF').html(response.resultM['COUNT_GENDER_F']);

                        var keys = Object.keys(response.resultM['USERS']);
                        var inteUser = '';

                        for (var i = 0; i < keys.length; i++) {
                            inteUser += '<tr user-id="' + response.resultM['USERS'][i]['ID'] + '" class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">' +
                                '<td class="whitespace-nowrap px-2 py-3 sm:px-4"><label class="flex h-8 w-8 items-center justify-center" x-tooltip="\'Select\'"><input user-id="' + response.resultM['USERS'][i]['ID'] + '" user-gender="' + response.resultM['USERS'][i]['GENDER'] + '" class="form-checkbox is-outline h-4.5 w-4.5 rounded border-slate-400/70 before:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:before:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox"/></label></td>' +
                                '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['ID'] + '</td>' +
                                '<td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">' + response.resultM['USERS'][i]['GENDER'] + '</td>' +
                                '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['NAME'] + '</td>' +
                                // '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['PHONE'] + '</td>' +
                                '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['RATING'] + '</td>' +
                                '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['CARMA'] + '</td>' +
                                '</tr>';
                        }
                        $("#listFreeUser").html(inteUser);
                        $("#tablefreeuser").attr("x-data", "");
                        $("#tablefreeuser").attr("x-init", "$el._x_grid =  new Gridjs.Grid({from: $refs.table,sort: true,search: true,}).render($refs.wrapper);");
                    }
                });
            });
        </script>



<!-- НОВОЭ -->



        <script>
            // Оценка (карма)
            $(document).ready(function () {
                $("#rateThem").click(function () {
                    var currentDate = new Date();
                    var date = currentDate.toISOString().slice(0,10); // Получаем текущую дату в формате "YYYY-MM-DD"

                    var selectedC = [];
                    // сотрудники слева
                    $('#tableListSelectedUsers .form-checkbox:checked').each(function () {
                        selectedC.push($(this).attr('user-id'));
                    });
                    // сотрудники справа
                    $('#tablefreeuser .form-checkbox:checked').each(function () {
                        selectedC.push($(this).attr('user-id'));
                    });


                    // Получаем значение кармы из полей формы
                    var carmaCase = $('#carmaSet').val();
                    var carmaCaseName = $('#carmaSet option:selected').text().trim();


                    // console.log('Карма значение:', carmaCase);
                    // console.log('Карма имя:', carmaCaseName);

                    var xhrEdit = null;
                    xhrEdit = $.ajax({
                        type: 'POST',
                        url: '<?= SITE_TEMPLATE_PATH ?>/responds/carmaSet.php',
                        dataType: 'json',
                        data: {
                            todo: 'rate',
                            userList: selectedC,
                            carmaAdd: carmaCase,
                            name: carmaCaseName,
                            date: date
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });

                });
            });
        </script>
    <? endif; ?>
</div>


