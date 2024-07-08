<?php

$messages = [];


// это можно брать из инфоблоков

/* ==== тексты кнопок ==== */
$buttonTextYes      = '✅ Да';
$buttonTextConfirm  = '✅ Подтверждаю';
$buttonTextNo       = '❌ Нет';
$buttonTextCancel   = '❌ Отмена';

/* ==== тексты сообщений ==== */
$messages['welcome_registered'] = ", вы уже зарегистрированы. Ожидайте, с вами свяжутся";
$messages['register_ok']        = ", приветствую! Вы успешно зарегистрированы. Ожидайте, с вами свяжутся";
$messages['register_not_ok']    = "Похоже, вас нет в базе. Свяжитесь с куратором. Ваш номер телефона не найден: ";

/* ==== клавиатуры ==== */
$removeKeyboard = ['remove_keyboard' => true]; // убрать клаву


/* ==== полные сообщения ==== */
$messages['test'] = [ 
    'text' => "Тестовое сообщение. Вы можете выбрать кнопку" ,
    'keyboard' => [ 'inline_keyboard' => [[
                                        ['text' => '✅ тестовая кнопка', 'callback_data' => 'yes_test'],
                                        ['text' => $buttonTextCancel,    'callback_data' => 'cancel_test'],
                                    ]]]
];

$messages['start'] = [
    'text' => "Для окончания регистрации отправьте свой номер телефона",
    // 'text' => "Нажмите на кнопку, чтобы отправить свой номер телефона 👇",
    'keyboard' => null
    // 'keyboard' => [
    //     'keyboard' => [[['text' => '📞 Отправить контакт','request_contact' => true]]],
    //     'resize_keyboard' => true,
    //     'one_time_keyboard' => true
    // ]
];


return $messages;