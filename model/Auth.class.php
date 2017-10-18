<?php
class Auth extends Model {
    
   // protected static $table = 'orders_goods';

    /*protected static function setProperties()
    {
        self::$properties['order_id'] = [
            'type' => 'int',
        ];

        self::$properties['good_id'] = [
            'type' => 'int',
        ];
    }*/


  public static function getUser($login, $pass)
    {
        return db::getInstance()->Select('SELECT * FROM user WHERE user_login=:login AND user_password=:pass', ['login'=>$login, 'pass'=>$pass]);
    }


  public static function setUser($login, $pass, $name, $email)
{
  return db::getInstance()->Query('INSERT INTO user (`user_name`, `user_password`, `user_login`, `email`) VALUES (:name, :pass, :login)', ['name' => $name, 'pass'=>$pass, 'login'=>$login, 'email'=>$email] );  
}
 
}
?>