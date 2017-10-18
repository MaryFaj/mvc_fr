<?php

class OrderGood extends Model {
    protected static $table = 'orders_goods';

    protected static function setProperties()
    {
        self::$properties['order_id'] = [
            'type' => 'int',
        ];

        self::$properties['good_id'] = [
            'type' => 'int',
        ];
    }
    
    
     public static function getOrders()
    {
        return db::getInstance()->Select('SELECT * FROM orders NATURAL JOIN basket NATURAL JOIN goods NATURAL JOIN user ORDER BY datatime_create');
            

    }
    
      public static function setOrders($data)
    { 
         return db::getInstance()->Query('UPDATE orders SET id_order_status=:id WHERE id_order=:id_order', ['id' => $data['id'], 'id_order'=>$data['id_order']]  );
    }
           
        public static function newOrders($amount, $id_user, $id_order_status, $data_time)
        {
           return db::getInstance()->Query('INSERT INTO orders (`id_user`, `amount`, `id_order_status`, `datatime_create`) VALUES (:id_user, :amount, :id_order_status, :data_time)', ['id_user' => $id_user, 'amount'=>$amount, 'id_order_status' => $id_order_status, 'data_time'=>$data_time]);  
        }
    
    public static function newBasket($id_user, $id_good, $price, $q, $id_order_data)
        {
           return db::getInstance()->Query('INSERT INTO basket (`id_user`, `id_good`, `price`, `is_in_order`, `id_order`) VALUES (:id_user, :id_good, :price, :q, :id_order)', ['id_user'=>$id_user, 'id_good'=>$id_good, 'price' => $price, 'q'=>$q, 'id_order'=>$id_order_data]);  
        }
    
    
     public static function getNewOrder($data_time, $amount, $id_user)
     {
        return db::getInstance()->Select ('SELECT id_order FROM orders WHERE datatime_create=:data_time AND amount=:amount AND id_user=:id_user', ['data_time'=>$data_time, 'amount'=>$amount, 'id_user'=>$id_user]);
     }
    
    
    
    
    
}
