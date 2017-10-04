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
        return db::getInstance()->Select('SELECT * FROM orders ORDER BY datatime_create');
            

    }
    
   
       
        
         public static function setOrders($data)
    { 
         return db::getInstance()->Query('UPDATE orders SET id_order_status=:id WHERE id_order=:id_order', ['id' => $data['id'], 'id_order'=>$data['id_order']]  );
    }
           

    //'id_order'=>$data['id_order']]
}