<?php

class CartFunction {

    public static function getCartAjax($array_quantity, $array_delete) {
        session_start();
        if (null !== ($array_quantity)) {

            $view_coast = true;

            $v = $array_quantity;

            // В цикле получаем $k = id_good, $w = количество

            foreach ($v as $k => $w) {

                $price = $_SESSION['cart'][$k]['price'];

                // Вычисляем стоимость заказанного товара по количеству и цене         
                $res = [];
                $w = strip_tags($w);
                $res = $w * $price;

// Вычисляем общую сумму заказа
                $sum+=$res;
                $sum = number_format($sum, 2, '.', '');

                // Записываем полученные значения в массив сессии            
                $_SESSION['cart'][$k]['q'] = $w;
                $_SESSION['cart'][$k]['res'] = number_format($res, 2, '.', '');
                $_SESSION['sum'] = $sum;
// Если количество = 0, удаляем соответствующий массив из сессии         
                if ($w == 0) {
                    unset($_SESSION['cart'][$k]);
                }
            }
            if ($sum == 0) {
                unset($_SESSION['cart']);
            }
            // Обрабатываем кнопку "Удалить заказ"

            if (null !== $array_delete) {
                unset($_SESSION['cart']);
            }
        }
        return [ 'cart' => $_SESSION['cart'], 'summa' => $sum, 'coast' => $view_coast];
    }

    public static function getCart() {
        session_start();
        if (null !== ($_POST['update'])) {
            $view_coast = true;

            // $_POST['quantity'] содержит массив значений (передаем массив через параметр формы name[]) 

            $v = $_POST['quantity'];

            // В цикле получаем $k = id_good, $w = количество   
            foreach ($v as $k => $w) {

                $price = $_SESSION['cart'][$k]['price'];

                // Вычисляем стоимость заказанного товара по количеству и цене         
                $res = [];
                $w = strip_tags($w);
                $res = $w * $price;

// Вычисляем общую сумму заказа
                $sum+=$res;
                $sum = number_format($sum, 2, '.', '');
                // Записываем полученные значения в массив сессии            
                $_SESSION['cart'][$k]['q'] = $w;
                $_SESSION['cart'][$k]['res'] = number_format($res, 2, '.', '');
                $_SESSION['sum'] = $sum;
// Если количество = 0, удаляем соответствующий массив из сессии         
                if ($w == 0) {
                    unset($_SESSION['cart'][$k]);
                }
            }
            if ($sum == 0) {
                unset($_SESSION['cart']);
            }
        }
// Обрабатываем кнопку "Удалить заказ"

        if (null !== $_POST['del']) {
            unset($_SESSION['cart']);
        }

//Проверка, запущен ли сеанс корзины

        if (isset($_SESSION['cart'])) {

            $msg = 'Ваш заказ';
        } else {
            $msg = 'Ваша корзина пуста!';
        }
        return [ 'cart' => $_SESSION['cart'], 'summa' => $sum, 'coast' => $view_coast, 'msg' => $msg];
    }

    // Начинаем работу с корзиной (добавление товаров в корзину)
    public static function beginCart() {
        session_start();

        //if(isset($_POST['add_']) && $_POST['add_']=="Add to cart"){ 
        if (null != $_POST['id_good']) {
            $id = $_POST['id_good'];
            // валидация 		  
            if ((is_numeric($id)) && (strlen($id) < 50)) {

                // По id_good получаем все данные о товаре из базы   
                $cart_goods = Good::getCartGood($id);
                if ($cart_goods) {
                    // Обрабатываем массив, полученный из БД: через цикл получаем данные, относящихся к товару, они попадают в $ord 
                    foreach ($cart_goods as $k => $ord) {
                        
                    }
                }
            }
            // Записываем массив заказов (товаров) в сессию, ключом к каждому заказу будет id товара (id_good)
            if ($ord) {
                $_SESSION['cart'][$id] = $ord;
                // Изначально количество товара = 1
                $_SESSION['cart'][$id]['q'] = 1;
            }
        }
        return [ $_SESSION['cart'][$id], $_SESSION['cart'][$id]['q']];
    }

}
