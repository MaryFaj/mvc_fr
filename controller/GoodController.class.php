<?php
class GoodController extends Controller
{

    public $view = 'good';

    public function index($data)
        
    { 
        session_start();
        $goods = Good::getOneGood($data);
        
        
    // Начинаем работу с корзиной 
         if(isset($_GET['add']) && $_GET['add']=="в корзину"){ 
             
          $id=$_GET['id_good'];
             
    // По id_good получаем все данные о товаре из базы   
             $cart_goods = Good::getCartGood($id);
             //print_r($cart_goods);
             
    // Через цикл получаем массив значений из базы данных, относящихся к товару, он попадет в $ord 
             foreach($cart_goods as $k => $ord){
                 
             }
    // Записываем массив заказов (товаров) в сессию, ключом к каждому заказу будет id товара (id_good)
             
              $_SESSION['cart'][$id]=$ord;           
                  
        }
         
        
        return ['good' => $goods, 'id_user'=>$_SESSION['user']['id_user']];
        
        
      }
}

?>
