<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="w-full max-w-[26rem] p-4 sm:px-5">
    <div class="text-center">
        <img class="mx-auto h-16 w-16" src="<?= SITE_TEMPLATE_PATH ?>/src-template/dist/images/app-logo.svg" alt="logo"/>
        <div class="mt-4">
            <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
                Авторизация
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
                в базу знаний
            </p>
        </div>
    </div>
    <form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />
        <?if ($arResult["BACKURL"] <> ''):?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <?endif?>
        <?foreach ($arResult["POST"] as $key => $value):?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
        <?endforeach?>
        <div class="card mt-5 rounded-lg p-5 lg:p-7">
            <label class="block">
                <span>Логин:</span>
                <span class="relative mt-1.5 flex">
                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Введите логин" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                    </span>
                  </span>
            </label>
            <label class="mt-4 block">
                <span>Пароль:</span>
                <span class="relative mt-1.5 flex">
                    <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" placeholder="Введите пароль" type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off"/>
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                      </svg>
                    </span>
                  </span>
            </label>
            <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                <div class="mt-4 flex items-center justify-between space-x-2">
                    <label class="inline-flex items-center space-x-2">
                        <input class="form-checkbox is-basic h-5 w-5 rounded border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y"/>
                        <span class="line-clamp-1">Запомнить меня</span>
                    </label>
                </div>
            <?endif?>
            <input type="submit" class="btn mt-5 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90" name="Login" value="Войти" />
        </div>
    </form>
    <? if (!empty($arParams["~AUTH_RESULT"])) {?>
        <br>
        <div class="alert flex rounded-full bg-error/10 py-4 px-4 text-error dark:bg-error/15 sm:px-5">
            <?= ShowMessage($arParams["~AUTH_RESULT"]); ?>
        </div>
    <?} if (!empty($arResult['ERROR_MESSAGE'])) {?>
        <div class="alert flex rounded-full bg-warning/10 py-4 px-4 text-warning dark:bg-warning/15 sm:px-5">
            <?= ShowMessage($arResult['ERROR_MESSAGE']); ?>
        </div>
    <?} ?>
</div>

<script type="text/javascript">
    <?if ($arResult["LAST_LOGIN"] <> ''):?>
    try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
    <?else:?>
    try{document.form_auth.USER_LOGIN.focus();}catch(e){}
    <?endif?>
</script>