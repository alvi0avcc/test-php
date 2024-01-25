<?php

require 'FormHelper.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
list($errors, $input) = validate_form();
if ($errors) {
show_form($errors);
} else {
process_form($input);
}
} else {
show_form();
}
function show_form($errors = array()) {
// Собственные значения, устанавливаемые по умолчанию,
// отсутствуют, поэтому и нечего передавать конструктору
// класса FormHelper
$form = new FormHelper();
// построить HTML-таблицу из сообщений об ошибках для
// последующего применения
if ($errors) {
$errorHtml = '<ul><li>';
$errorHtml .= implode('</li><li>',$errors);
$errorHtml .= '</li></ul>';
} else {
$errorHtml = '';
}
// Это небольшая форма, поэтому ниже выводятся ее составляющие
print <<<_FORM_
<form method="POST" action=
"{$form->encode($_SERVER['PHP_SELF'])}">
$errorHtml
Username: {$form->input('text',
['name' => 'username'])} <br/>
Password: {$form->input('password',
['name' => 'password'])} <br/>
{$form->input('submit', ['value' => 'Log In'])}
</form>
_FORM_;
}
function validate_form() {
$input = array();
$errors = array();
// Некоторые образцы имен пользователей и паролей
$users = array('alice' => 'dogl23',
'bob' => 'my^pwd',
'charlie' => '**fun**');
// убедиться в достоверности имени пользователя
$input['username'] = $_POST['username'] ?? '';
if (! array_key_exists($input['username'], $users)) {
$errors[] = 'Please enter a valid username and password.';
}
// Оператор else означает, что проверка пароля пропускается,
// если введено недостоверное имя пользователя
else {
// проверить правильность введенного пароля
$saved_password = $users[ $input['username'] ];
$submitted_password = $_POST['password'] ?? '';
if ($saved_password != $submitted_password) {
$errors[] =
'Please enter a valid username and password.';
}
}
return array($errors, $input);
}
function process_form($input) {
// ввести имя пользователя в сеанс
$_SESSION['username'] = $input['username'];
print "Welcome, $_SESSION[username]";
}
?>
