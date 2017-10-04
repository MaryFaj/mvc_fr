<?php

class OrderController extends Controller
{

    public $view = 'order';

    public function index($data)
    {
        $order = OrderGood::getOrders(isset($data['id']) ? $data['id'] : 0);
        
        return ['order' => $order];
        
      }
     
        
    

public function status($data){
    
    
    if (!null==$_GET['id']){
        
    $set_status = OrderGood::setOrders($data);
        
        }
    
    $order = OrderGood::getOrders(isset($data['id']) ? $data['id'] : 0);
        
        return ['order' => $order];
    }
}

?>