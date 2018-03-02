<?php

class OrderController extends Controller {

    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Заказы';
    }

    public $view = 'order';

    public function index($data) {
        session_start();

        // проверка id администратора
        if ($_SESSION['user']['id_user'] == '4') {
            // получение данных обо всех заказах 		
            $order = OrderGood::getOrders($data['id']);

            // 	обработка формы изменения статуса заказа 		
            if ((!null == $_POST['id_status']) && ((!null == $_POST['id_order']))) {
                $id_status = $_POST['id_status'];
                $id_order = $_POST['id_order'];
                if ((is_numeric($id_status)) && (is_numeric($id_order))) {
                    $set_status = OrderGood::setOrders($id_order, $id_status);
                }
            }
            // обработка удаления заказов (если в форме выбран статус "удалить")	
            OrderGood::deleteOrders();

            return ['order' => $order, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
        }
        return ['message' => 'Доступ закрыт'];
    }

    public function detail($data) {
        session_start();

        // проверка id администратора
        if ($_SESSION['user']['id_user'] == '4') {
            // получение подробных данных заказа (список товаров и их количество)	
            $order = OrderGood::getOrdersDetail($data);

            return ['order' => $order, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
        }
        return ['message' => 'Доступ закрыт', 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

}
