<?php

class SearchController extends Controller {

    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Поиск';
    }

    public $view = 'search';

    public function index($data) {
        session_start();

        // получение каталога
        $categories = Category::getCategories();

        // обработка поискового запроса		
        if ((isset($_POST['query'])) && (!empty($_POST['query']))) {
            $query = $_POST['query'];

            $query = trim($query);
            $query = strip_tags($query);

            if (strlen($query) > 120) {
                $text = 'Длинный поисковый запрос';
            } else {
                $search = Good::getSearch($query);
                $text = "Результат поиска";
            }
        }
        // работа корзины
        CartFunction::beginCart();
        $cart = CartFunction::getCart($data);

        return ['search' => $search, 'text_search' => $text, 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'subcategories' => $categories, 'id_user' => $_SESSION['user']['id_user']];
    }

}
