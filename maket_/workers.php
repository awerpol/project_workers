<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тичер");
?>


<div class="flex items-center py-5 lg:py-6">
    <div class="flex items-center space-x-1 mx-3">
        <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50 lg:text-2xl">Работники</h2>
    </div>
    <div class="flex items-center space-x-2 mx-3">
    <!-- Добавление смены - всплывающая форма -->
        <div x-data="{showModal:false}">
            <button @click="showModal = true" class="btn bg-info font-medium text-white hover:bg-info-focus hover:shadow-lg hover:shadow-info/50 focus:bg-info-focus focus:shadow-lg focus:shadow-info/50 active:bg-info-focus/90"> 
                Добавить работника 
            </button>
                <template x-teleport="#x-teleport-target">
                    <div
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
                        class="relative w-full max-w-lg origin-top rounded-lg bg-white transition-all duration-300 dark:bg-navy-700"
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
                                    Новый работник
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
                                Данные на нового работника
                            </p>
                            <div class="mt-4 space-y-4">
                                <label class="block">
                                    <span>ФИО:</span>
                                    <select class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                        <option>Гурман</option>
                                        <option>Не Гурман</option>
                                        <option>ИП Пупкин</option>
                                        <option>Другой</option>
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
                                        <input x-input-mask="{
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
                                        <input 
                                            x-input-mask="{time: true,timePattern: ['h', 'm']}"
                                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                            placeholder="00:00"
                                            type="text"
                                            value="<?php echo $defaultValue; ?>"
                                        />
                                    </label>
                                    <label class="block">
                                        <span>Длительность:</span>
                                            <input 
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
                                        <input 
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
                                        <input
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
                            <textarea
                              rows="4"
                              placeholder="Введите комментарий"
                              class="form-textarea mt-1.5 w-full resize-none rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            ></textarea>
                          </label> -->

                                <label class="inline-flex items-center space-x-2">
                                    <input
                                        class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-transparent before:rounded-full before:bg-slate-300 checked:border-primary checked:before:bg-primary dark:border-navy-400 dark:before:bg-navy-300 dark:checked:border-accent dark:checked:before:bg-accent"
                                        type="checkbox"
                                        checked 
                                    />
                                    <span>Запустить набор</span>
                                </label>
                                <div class="space-x-2 text-right">
                                    <button
                                        @click="showModal = false"
                                        class="btn min-w-[7rem] rounded-full border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90"
                                    >
                                        Отмена
                                    </button>
                                    <button
                                        @click="showModal = false"
                                        class="btn min-w-[7rem] rounded-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"
                                    >
                                        Создать
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

        <!-- див с таблицей -->
<div class="col-span-12 lg:col-span-5">

      


    <!-- Таблица -->
    <div class="card"
      x-data
        x-init="$el._x_grid =  new Gridjs.Grid({
        from: $refs.table,
        sort: true,
        search: true,
      }).render($refs.wrapper);"
    >
      <div class="is-scrollbar-hidden min-w-max overflow-x-auto overflow-y-auto max-h-80 mt-4">

          <table x-ref="table" class="is-hoverable w-full text-left" >
              <thead class="basis-full">
              <tr>
                  <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                      D
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
                      пол
                  </th>                                 
                  <th
                      class="whitespace-nowrap bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4"
                  > 
                    Карма
                  </th>
                  <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                    Паспорт
                  </th>
                  <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                    Регистрация
                  </th>
                  <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                    Патент
                  </th>
                  <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-2 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-4">
                    Санкнижка
                  </th>
              </tr>
              </thead>
              <tbody>
              <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">

                    <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                        <span class="text-success">
                            да
                        </span> 
                    </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar h-9 w-9">
                            <img
                            class="rounded-full"
                            src="to_del/avatar-19.jpg"
                            alt="avatar"
                            />
                        </div>
                        <a href="#" class="hover:underline focus:underline">
                            Иван Иванов
                        </a>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      --
                  </td>
                  <td
                      class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4"
                  >
                      М
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      3
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      21 34 123456
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.01.2025
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.01.2025
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      нет
                  </td>
              </tr>
              <tr
                  class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500"
              >
                    <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                          <p class="text-error">
                            нет
                          </p>  
                    </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar h-9 w-9">
                            <img
                            class="rounded-full"
                            src="to_del/avatar-11.jpg"
                            alt="avatar"
                            />
                        </div>
                        <a href="#" class="hover:underline focus:underline">
                            Анна Иванова
                        </a>
                    </div>                      
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      +7 912 953 1324
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                      Ж
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      2
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      21 34 654321
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.12.2025
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.01.2024
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      15.04.2024
                  </td>
              </tr>
              <tr
                  class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500"
              >
                    <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                        <span class="text-success">
                            да
                        </span> 
                    </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar h-9 w-9">
                            <img
                            class="rounded-full"
                            src="to_del/avatar-11.jpg"
                            alt="avatar"
                            />
                        </div>
                        <a href="#" class="hover:underline focus:underline">
                            Светлана Нечепуренко
                        </a>
                    </div>  
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      +7 912 953 1122
                  </td>
                  <td
                      class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4"
                  >
                      Ж
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      2
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      00 00 1230978
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.12.2025
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.12.2024
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      10.05.2024
                  </td>
              </tr>
              <tr
                  class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500"
              >
                    <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                        <span class="text-success">
                            да
                        </span>    
                    </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar h-9 w-9">
                            <img
                            class="rounded-full"
                            src="to_del/avatar-5.jpg"
                            alt="avatar"
                            />
                        </div>
                        <a href="#" class="hover:underline focus:underline">
                            Николай Федеров
                        </a>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      +7 912 953 3113
                  </td>
                  <td
                      class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4"
                  >
                      М
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      1
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      --
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      01.12.2025
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      --
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      нет
                  </td>
              </tr>
              <tr
                  class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500"
              >

                    <td class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4">
                        <span class="text-error">
                            нет
                        </span>  
                    </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar h-9 w-9">
                            <img
                            class="rounded-full"
                            src="to_del/avatar-18.jpg"
                            alt="avatar"
                            />
                        </div>
                        <a href="#" class="hover:underline focus:underline">
                            Всеволод Дзержинский
                        </a>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      +7 912 953 5675
                  </td>
                  <td
                      class="whitespace-nowrap px-2 py-3 font-medium text-slate-700 dark:text-navy-100 sm:px-4"
                  >
                      М
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      0
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      --
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      нет
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      --
                  </td>
                  <td class="whitespace-nowrap px-2 py-3 sm:px-4">
                      нет
                  </td>
              </tr>

              
              </tbody>
          </table>
      </div> 
      <div>
        <div x-ref="wrapper"></div>
      </div>
    



    </div>
    </div>



  </div>
</div>



        
        

</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>