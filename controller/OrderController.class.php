<?php

class OrderController extends Controller
{

    public $view = 'order';

    public function index($data)
    {
        session_start();
        $order = OrderGood::getOrders();
        
        return ['order' => $order];
        
      }
     

    public function status($data){
    
    session_start();
    if (!null==$_GET['id']){
        
    $set_status = OrderGood::setOrders($data);
        
        }
    
    $order = OrderGood::getOrders(isset($data['id']) ? $data['id'] : 0);
        
        return ['order' => $order, 'id_user'=>$_SESSION['user']['id_user']];
    }
}

?>