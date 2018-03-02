<?php

class CategoriesController extends Controller {

    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Каталог';
    }

    public $view = 'categories';

    public function index($data) {
        session_start();

        // получение данных каталога
        $categories = Category::getCategories();
        $goods = Good::getGoodsCategory($data);
        $goods_category = Category::getCategoryGoods($data);

        // работа корзины
        CartFunction::beginCart();
        $cart = CartFunction::getCart();

        // модуль изменения категорий для администратора
        if ($_SESSION['user']['id_user'] == '4') {
            $is_admin = true;
            $adm_upload_status = AdminFunction::updateCategory();
        } else {
            $is_admin = false;
        }

        return ['is_admin' => $is_admin, 'upload_status' => $adm_upload_status['upload_status'], 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'goods_cat' => $goods_category, 'subcategories' => $categories, 'goods' => $goods, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

    public function special($data) {
        session_start();
        // получение каталога 
        $categories = Category::getCategories();

        // получение товаров, относящихся к спец.предложению
        $goods_special = Good::getSpecialOfferGoods($data);

       
        // работа корзины
        CartFunction::beginCart();
        $cart = CartFunction::getCart();

        return ['message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'goods_special' => $goods_special, 'subcategories' => $categories, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

    // обработчик переключения страниц каталога с помощью ajax (запускается при переходе от одной категории внутри categories к другой)
    public function ajax() {
        if (!empty($_POST['id'])) {
            $a = [];
            $a['id'] = $_POST['id'];
            $data = $a;
        }
        
        // получение товаров категории
        $goods = Good::getGoodsCategory($data);

        // получение данных (названия) выбранной категории
        $goods_category = Category::getCategoryGoods($data);

        // модуль изменения категорий для администратора
        if ($_SESSION['user']['id_user'] == '4') {
            $is_admin = true;
            $adm_upload_status = AdminFunction::updateCategory();
        } else {
            $is_admin = false;
        }

        return ['is_admin' => $is_admin, 'upload_status' => $adm_upload_status['upload_status'], 'goods' => $goods, 'goods_cat' => $goods_category];
    }

}

