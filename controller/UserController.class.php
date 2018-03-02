<?php

class UserController extends Controller {

    public $view = 'user';
    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Регистрация и Вход';
    }

    public function index($data) {
        session_start();
        // получение каталога
        $categories = Category::getCategories();

        // отображение корзины
        $cart = CartFunction::getCart($data);

        // авторизация 
        UserFunction::userExit();
        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring_enter']) {
            $auth = UserFunction::authorisation();
            unset($_SESSION['captcha_keystring']);
        }

        // проверка наличия в GET-запросе ключа активации и его валидация 
        if (isset($_GET['activation']) && (null !== $_GET['activation'])) {
            $act_key = strip_tags($_GET['activation']);
            // проверяем, есть ли в БД пользователь, которому был выслан данный ключ 		
            $is_activation = Auth::getActivation($act_key);
            // если есть, то активируем его (меняем статус в БД)		
            if ($is_activation) {
                if (Auth::setActivation($act_key)) {
                    $msg_auth = 'Вы успешно зарегистрированы, ' . $is_activation[0]['user_name'] . '! Для входа на сайт введите логин и пароль.';
                } else {
                    $msg_auth = "Произошла ошибка. Пожалуйста попробуйте еще раз!";
                }
            }
        }
        // обработка регистрации нового пользователя
        if ((null !== $_POST['user_name']) && (null !== $_POST['password']) && (null !== $_POST['login']) && ($_SESSION['captcha_keystring'] === $_POST['keystring_registration'])) {

            $pass = trim($_POST['password']);
            $name = trim($_POST['user_name']);
            $login = trim($_POST['login']);
            $email = trim($_POST['email']);
            $phone = strip_tags(trim($_POST['phone']));

            if (UserFunction::userNameValidation($name)) {
                if (UserFunction::userNameValidation($login)) {
                    if (UserFunction::userPasswordValidation($pass)) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            if (strlen($phone) < 200) {
                                $validation_form = true;
                            } else {
                                $msg_auth = "Некорректные данные в поле Телефон";
                            }
                        } else {
                            $msg_auth = "Некорректные данные в поле E-mail";
                        }
                    } else {
                        $msg_auth = "Некорректные данные в поле Пароль: используйте латинские буквы, цифры, тире и нижнее подчеркивание";
                    }
                } else {
                    $msg_auth = "Некорректные данные в поле Логин: используйте латинские буквы, цифры, тире и нижнее подчеркивание";
                }
            } else {
                $msg_auth = "Некорректные данные в поле Имя: используйте латинские буквы или кириллицу, тире и нижнее подчеркивание";
            }
            // если валидация прошла, проверяем, свободен ли логин, если логин свободен, продолжаем	
            if ($validation_form) {
                if (Auth::goodLogin($login) === true) {
                    // проверяем, была ли уже регистрация с таким email, если нет, продолжаем
                    if (Auth::goodEmail($email) === true) {
                        // формируем соль	   
                        $salt = UserFunction::randomString(20);
                        // формируем пароль
                        $pass_complete = md5($pass . $salt);
                        // статус пользователя - не активирован
                        $activation = 0;
                        // формируем ключ активации
                        $salt_act = UserFunction::randomString(10);
                        $activation_key = md5($email . $salt_act);
                        // записываем нового пользователя в БД	 
                        $new_user = Auth::setUser($login, $pass_complete, $salt, $name, $email, $activation, $activation_key, $phone);

                        // Формируем сообщение, содержащее ключ активации, и отправляем на указанный пользователем email
                        $to = '<' . $email . '>';
                        $subject = "Ссылка для подтверждения регистрации";
                        $message = '
          <html>
          <head>
          <title>Регистрация на Онлайн Продукты</title>
          </head>
          <body>
		  <p>Добрый день! в этом письме содержится ссылка для завершения регистрации на сайте Онлайн Продукты. Просто перейдите по ней для завершения процесса регистрации. </p>
          <p><a style="text-decoration: underline;"href="http://localhost/user/?activation=' . $activation_key . '">Перейдите по этой ссылке, чтобы завершить регистрацию</a></p>
          </body>
          </html>
          ';
                        $headers = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=utf-8\r\n";
                        $headers .= "From: Registration service of Grocery Store <mary.po.fatj@gmail.com>\r\n";
                        $headers .= "Cc: mary.po.fatj@gmail.com\r\n";
                        $headers .= "Bcc: mary.po.fatj@gmail.com\r\n";

                        $send = mail($to, $subject, $message, $headers);

                        if ($send) {
                            $msg_auth = "Вам отправлено письмо для подтверждения регистрации. Пожалуйста проверьте свой e-mail.";
                        } else {
                            $msg_auth = "Ошибка отправки письма";
                        }
                    } else {
                        $msg_auth = "Ошибка. Такой e-mail уже зарегистрирован";
                    }
                } else {
                    $msg_auth = "Такой логин уже используется. Пожалуйста, выберете другой.";
                }
            }
            unset($_SESSION['captcha_keystring']);
        }


        return ['capcha_msg' => $auth['capcha_msg'], 'auth_status' => $auth['error'], 'status_msg' => $msg_auth, 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'subcategories' => $categories, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

// обработка авторизации ajax (в хэдере)
    public function ajaxAuth() {
        session_start();

        UserFunction::userExit();
        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']) {
            $auth = UserFunction::authorisation();
            unset($_SESSION['captcha_keystring']);
        }

        return ['auth_status_error' => $auth['error'], 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

}
