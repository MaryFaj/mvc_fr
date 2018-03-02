<?php

class CartController extends Controller {

    public $view = 'cart';
    public $title;

    function __construct() {
        parent::__construct();
        $this->title .= ' | Корзина';
    }

    public function index($data) {
        session_start();

        // получение каталога
        $categories = Category::getCategories();
        //	получение корзины
        $cart = CartFunction::getCart();
        // обработка оформления заказа
        if ((null != $_POST['issue']) && (null != $_POST['delivery'])) {
            // проверка пользователя
            if (isset($_SESSION['user']) && (!empty($_SESSION['user']))) {

                $amount = $_SESSION['sum'];
                $id_user = $_SESSION['user']['id_user'];
                $id_order_status = 1;
                $data_time = date("Y-m-d H:i:s");
                $address = strip_tags(trim($_POST['delivery']));
                $comment = strip_tags(trim($_POST['comment']));
                if ((strlen($address) < 15000) && (strlen($comment) < 15000)) {
                    // записаваем новый заказ в базу данных (в таблицу orders)
                    $new_order = OrderGood::newOrders($amount, $id_user, $id_order_status, $data_time, $address, $comment);
                    // из базы данных получаем массив с id нового заказа
                    $id_order_data = OrderGood::getNewOrder($data_time, $amount, $id_user);
                    // получаем из массива сам id
                    foreach ($id_order_data as $key => $val) {
                        foreach ($val as $k => $v) {        
                        }
                    }
                    $id_order_data = $v;

                    //через цикл получаем из массива сессии подробную информацию о новом заказе, чтобы записать ее в таблицу basket БД
                    foreach ($_SESSION['cart'] as $i => $ord) {
                        $id_good = $_SESSION['cart'][$i]['id_good'];
                        $q = $_SESSION['cart'][$i]['q'];
                        $price = $_SESSION['cart'][$i]['price'];

                        // в цикле записаваем данные в basket
                        $new_basket = OrderGood::newBasket($id_user, $id_good, $price, $q, $id_order_data);
                    }
                    // получаем подробную информацию о новом заказе, чтобы отправить оповещание на email администратора
                    $id['id'] = $id_order_data;
                    $data_new_order = OrderGood::getOrdersDetail($id);

                    // получаем из БД email администратора
                    $admin_email = Auth::getAdminEmail();
                    $admin_email = $admin_email[0]['email'];

                    // формируем сообщение
                    $to = '<' . $admin_email . '>';
                    $subject = "Новый заказ";
                    $message = '
          <html>
          <head>
          <title>У вас новый заказ</title>
		  <style>
             table, th, td {
             text-align: center;
	         padding: 10px;
	         border: 1px solid grey;
            }
           </style>
          </head>
         <body>
			<table>
			<tr><th>Заказ №</th><th>Пользователь</th><th> e-mail</th><th>телефон</th><th>сумма</th><th>адрес</th><th>комментарии</th><th>дата</th></tr>
			<tr>
			<td>' . $data_new_order[0]['id_order'] . '</td>
			<td>' . $data_new_order[0]['user_name'] . '</td>
			<td>' . $data_new_order[0]['email'] . '</td>
			<td>' . $data_new_order[0]['phone'] . '</td>
			<td>' . number_format($data_new_order[0]['amount'], 2, '.', '') . '&#8381;</td>
			<td>' . $data_new_order[0]['address'] . '</td>
			<td>' . $data_new_order[0]['commentary'] . '</td>
			<td>' . $data_new_order[0]['datatime_create'] . '</td>
			</tr>
			</table>
         </body>
       </html>
          ';
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=utf-8\r\n";
                    $headers .= "From: Онлайн продукты <mary.po.fatj@gmail.com>\r\n";
                    // отправляем сообщение
                    $send = mail($to, $subject, $message, $headers);
                    // удаляем массив корзины из сессии
                    unset($_SESSION['cart']);
                } else {
                    unset($_SESSION['cart']);
                }

                $cart_status = 1;
            } else {
                $cart_status = 2;
            }
        }
        return ['status' => $cart_status, 'message' => $cart['msg'], 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast'], 'subcategories' => $categories, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
    }

    //работа корзины через ajax (в хедере)
    public function ajax_cart() {
        session_start();

        $array_quantity = $_POST['quantity'];
        $array_delete = $_POST['del'];
        CartFunction::beginCart();
        $cart = CartFunction::getCartAjax($array_quantity, $array_delete);

        return['message' => "cart", 'cart_goods' => $cart['cart'], 'sum' => $cart['summa'], 'view_coast' => $cart['coast']];
    }

}
