<? require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/header.php");
$APPLICATION->SetTitle("Список работников");
$APPLICATION->SetPageProperty('title', "Список работников");

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

<div class="col-span-12 lg:col-span-5">


    <!-- Шапка таблицы -->
    <div class="flex items-center justify-between mt-4 ">

        <div class="flex items-center justify-between">
            <!-- <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                Доступные работники
            </h2> -->
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
                    <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                        Телефон
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                        Рейтинг
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                        Допущен
                    </th>
                    <th class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                        Telegram
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
    $(document).ready(function () {
        var xhr = null;
        xhr = $.ajax({
            type: 'POST',
            url: '<?= SITE_TEMPLATE_PATH ?>/responds/getAllUsers.php',
            dataType: 'json',
            //contentType: 'application/json',
            data: {getUser: 'Y'},
            success: function (response) {
                $('#countFreeM').html(response.resultM['COUNT_GENDER_M']);
                $('#countFreeF').html(response.resultM['COUNT_GENDER_F']);

                var keys = Object.keys(response.resultM['USERS']);
                var inteUser = '';

                for (var i = 0; i < keys.length; i++) {
                    inteUser += '<tr user-id="' + response.resultM['USERS'][i]['ID'] + '" class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4"><label class="flex h-8 w-8 items-center justify-center" x-tooltip="\'Select\'"><input user-id="' + response.resultM['USERS'][i]['ID'] + '" class="form-checkbox is-outline h-4.5 w-4.5 rounded border-slate-400/70 before:bg-primary checked:border-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:before:bg-accent dark:checked:border-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox"/></label></td>' +
                    // '<td class="whitespace-nowrap px-2 py-3 sm:px-4"><a href="/all_workers/detail.php?id=' + response.resultM['USERS'][i]['ID'] + '">' + response.resultM['USERS'][i]['ID'] + '</a></td>' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['ID'] + '</a></td>' +
                    '<td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">' + response.resultM['USERS'][i]['GENDER'] + '</td>' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['NAME'] + '</td>' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['PHONE'] + '</td>' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['RATING'] + '</td>' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4">' + response.resultM['USERS'][i]['IS_ACTIVE'] + '</td>' +
                    '<td class="whitespace-nowrap px-2 py-3 sm:px-4"><p class ="text-xs text-slate-400" style="font-size: 8px;">' + response.resultM['USERS'][i]['TELEGRAM'] + '</p></td>' +

                    '</tr>';
                }
                $("#listFreeUser").html(inteUser);
                $("#tablefreeuser").attr("x-data", "");
                $("#tablefreeuser").attr("x-init", "$el._x_grid =  new Gridjs.Grid({from: $refs.table,sort: true,search: true,}).render($refs.wrapper);");
            }
        });
    });
</script>

<? require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/footer.php"); ?>