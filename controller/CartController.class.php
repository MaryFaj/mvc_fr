<?php

class CartController extends Controller
{

    public $view = 'cart';

    public function index()
        
    { 
        session_start();
       
//Обработчик формы изменения товаров в корзине 

if(null!==($_GET['update'])){ 
    
 // $_GET['quantity'] содержит массив значений, т.к в шаблоне формы мы поставили [] в параметре name 
    
  $v=$_GET['quantity'];
    
   // В цикле получаем $k = id_good, $w = количество
    
        foreach($v as $k=>$w){
             
           $price=$_SESSION['cart'][$k]['price'];
            
   // Вычисляем стоимость заказанного товара по количеству и цене         
             $res=[];
              $res = $w*$price;
            
// Вычисляем общую сумму заказа
             $sum+=$res;
            
 // Записываем полученные значения в массив сессии            
            $_SESSION['cart'][$k]['q']=$w;
           $_SESSION['cart'][$k]['res']=$res;
             $_SESSION['sum'] = $sum;
// Если количество = 0, удаляем соответствующий массив из сессии         
             if($w==0){
                 unset($_SESSION['cart'][$k]);
             }
            
        } 
         //print_r($_SESSION['cart']);  
        return ['cart_goods'=>$_SESSION['cart'], 'sum' =>$sum];
    }
    
// Обрабатываем кнопку "Удалить заказ"
        
if(null!==$_GET['del']){
    unset ($_SESSION['cart']);
}

// Если сеанс корзины запущен

if(isset($_SESSION['cart'])){
    
   return['cart_goods'=>$_SESSION['cart'], 'message' => 'Ваш заказ:' ];
    
  }
        else{
            return['message' => 'Ваша корзина пуста!'];
        }
        
}    
    
function issue (){
    session_start();
 
if(isset($_SESSION['user'])&&(!empty($_SESSION['user']))){
    
  $amount = $_SESSION['sum'];
  $id_user = $_SESSION['user']['id_user'];
  $id_order_status = 1;
  $data_time = date("Y-m-d H:i:s");
    
    $new_order = OrderGood::newOrders($amount, $id_user, $id_order_status, $data_time);
    
    $id_order_data = OrderGood::getNewOrder($data_time, $amount, $id_user );
   
    
    foreach($id_order_data as $key =>$val){
        foreach($val as $k=>$v){
        }
    }
    //print_r($v);
    $id_order_data=$v;
    
    foreach ($_SESSION['cart'] as $i=>$ord){
        
      $id_good=$_SESSION['cart'][$i]['id_good'];
      $q=$_SESSION['cart'][$i]['q'];
      $price=$_SESSION['cart'][$i]['price'];
         
        //print_r($id_order_data);
            
          $new_basket = OrderGood::newBasket($id_user, $id_good, $price, $q, $id_order_data);   
        
    }
    
    return ['message'=>'Ваш заказ принят!'];

}
    else{
        
        return['message'=>'Чтобы оформить заказ войдите на сайт'];
        
    }
}
  

 
        
       
   
    
}


?>
