<?php

class GoodController extends Controller {

    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Описание товара';
    }

    public $view = 'good';

    public function index($data) {
        session_start();
        $categories = Category::getCategories();

        // получение данных выбранного товара
        $goods = Good::getOneGood($data);

        // получение данных (названия) категории, в которой относится товар
        $good_category = Good::getGoodsCategories($data);

        // работа корзины
        CartFunction::beginCart();
        $cart = CartFunction::getCart($data);

        // модуль изменения товара для администратора
        if ($_SESSION['user']['id_user'] == '4') {
            $admin_update_good = AdminFunction::updateGood();

            return ['is_admin' => true, 'error_size' => $admin_update_good['error_size'], 'error_change' => $admin_update_good['error_change'], 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'subcategories' => $categories, 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'good' => $goods, 'good_category' => $good_category, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
        }

        return [ 'is_admin' => false, 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'subcategories' => $categories, 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'good' => $goods, 'good_category' => $good_category, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

}
