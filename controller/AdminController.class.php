<?php

class AdminController extends Controller {

    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Администратор';
    }

    public $view = 'admin';

    public function index($data) {
        session_start();

        // проверка id администратора
        if ($_SESSION['user']['id_user'] == '4') {

            // обработка изменения email администратора
            if (null != $_POST['change_email']) {
                if (AdminFunction::changeAdminEmail()) {
                    $status = "Email успешно изменен";
                } else {
                    $status = "Ошибка. Попробуйте еще раз";
                }
            }
            // обработка удаления неактивированных пользователей
            if (null != $_POST['del_not_active']) {
                if (UserFunction::deleteNotActive()) {
                    $status = "Неактивированные пользователи удалены";
                } else {
                    $status = "Ошибка. Попробуйте еще раз";
                }
            }
            // работа корзины
            $cart = CartFunction::getCart($data);
            // получение всех категорий для отображения их в форме создания новой категории и нового товара
            $categories = Category::getCategories();
            // создание нового товара
            $admin_create_good = AdminFunction::createGood();
            // создание новой категории
            $admin_create_category = AdminFunction::createCategory();

            return ['status' => $status, 'is_admin' => true, 'error_good' => $admin_create_good['error'], 'error_category' => $admin_create_category['error'], 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'subcategories' => $categories, 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
        } else {
            return['restrict' => 'Страница администратора', 'is_admin' => false];
        }
    }

}
