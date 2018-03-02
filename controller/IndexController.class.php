<?php

class IndexController extends Controller {

    public $view = 'index';
    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Главная';
    }

    public function index($data) {

        session_start();

        // получение каталога
        $categories = Category::getCategories();

        // получение корзины
        $cart = CartFunction::getCart($data);

        // обработчик формы подписки на рассылку
        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring_subscribe']) {
            if (null != $_POST['email']) {

                $email = trim($_POST['email']);
                $is_email = true;
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    // проверка на повторную подписку
                    if (Index::isNotSubscribe($email)) {
                        Index::setSubscribe($email);
                        $status_subscribe = "Вы успешно подписаны на рассылку!";
                    } else {
                        $status_subscribe = "Вы уже подписаны";
                    }
                } else {
                    $status_subscribe = "Некорректный e-mail";
                }
            }
            unset($_SESSION['captcha_keystring']);
        }


        return ['is_email' => $is_email, 'status_subscribe' => $status_subscribe, 'msg_auth_success' => $_SESSION['auth_success'], 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'user' => $user, 'subcategories' => $categories, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

    public function contacts($data) {
        // получаем все функции index
        new IndexController;
        $index = $this->index($data);

        // обработка формы обратной связи		
        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring_contact']) {
            if ((null != $_POST['name_contact'])) {

                $name_contact = htmlspecialchars(trim($_POST['name_contact']));
                $email_contact = htmlspecialchars(trim($_POST['email_contact']));
                $phone_contact = htmlspecialchars(trim($_POST['phone_contact']));
                $subject_contact = htmlspecialchars(trim($_POST['subject_contact']));
                $message_contact = htmlspecialchars(trim($_POST['message_contact']));

                if (strlen($name_contact) < 200) {
                    if (strlen($subject_contact) < 200) {
                        if (strlen($phone_contact) < 50) {
                            if (filter_var($email_contact, FILTER_VALIDATE_EMAIL)) {
                                if (strlen($message_contact) < 15000) {
                                    $validation_form = true;
                                } else {
                                    $status_send = "Некорректные данные в поле Сообщение";
                                }
                            } else {
                                $status_send = "Некорректные данные в поле E-mail";
                            }
                        } else {
                            $status_send = "Некорректные данные в поле Телефон";
                        }
                    } else {
                        $status_send = "Некорректные данные в поле Тема";
                    }
                } else {
                    $status_send = "Некорректные данные в поле Имя";
                }
                // если валидация прошла, получаем email администратора для отправки сообщения	
                if ($validation_form) {
                    $admin_email = Auth::getAdminEmail();
                    $admin_email = $admin_email[0]['email'];

// формируем сообщение	 
                    $to = '<' . $admin_email . '>';
                    //$to='<m-fatj@rambler.ru>';		
                    $message_contact = 'Вам пришло сообшение с сайта <b>Grocery Store</b> от <b>' . $name_contact . '</b> почтовый ящик для ответа:<b> ' . $email_contact . '</b>:<br>' . $message_contact;
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=utf-8\r\n";
                    $headers .= "From:" . $name_contact . '<' . $email_contact . '>' . "\r\n";

                    // отправляем	сообщение    
                    $send = mail($to, $subject_contact, $message_contact, $headers);
                }
                if ($send) {
                    $status_send = $name_contact . ", ваше сообщение успешно отправлено!";
                } else {
                    $status_send = $name_contact . ", произошла ошибка отправки сообщения. Пожалуйста, попробуйте еще раз.";
                }
            }
        }
        return ['status_send' => $status_send, 'is_email' => $index['is_email'], 'status_subscribe' => $index['status_subscribe'], 'msg_auth_success' => $_SESSION['auth_success'], 'message' => $index ['message'], 'cart_goods' => $index['cart_goods'], 'sum' => $index['sum'], 'view_coast' => $index['view_coast'], 'user' => $index['user'], 'subcategories' => $index['subcategories'], 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

    public function about($data) {
        new IndexController;
        $index = $this->index($data);

        return ['is_email' => $index['is_email'], 'status_subscribe' => $index['status_subscribe'], 'msg_auth_success' => $_SESSION['auth_success'], 'message' => $index ['message'], 'cart_goods' => $index['cart_goods'], 'sum' => $index['sum'], 'view_coast' => $index['view_coast'], 'user' => $index['user'], 'subcategories' => $index['subcategories'], 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

    public function service($data) {
        new IndexController;
        $index = $this->index($data);

        return ['is_email' => $index['is_email'], 'status_subscribe' => $index['status_subscribe'], 'msg_auth_success' => $_SESSION['auth_success'], 'message' => $index ['message'], 'cart_goods' => $index['cart_goods'], 'sum' => $index['sum'], 'view_coast' => $index['view_coast'], 'user' => $index['user'], 'subcategories' => $index['subcategories'], 'goods' => $index['goods'], 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

}
