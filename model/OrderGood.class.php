<?php

class OrderGood extends Model {

    protected static $table = 'orders_goods';

    protected static function setProperties() {
        self::$properties['order_id'] = [
            'type' => 'int',
        ];

        self::$properties['good_id'] = [
            'type' => 'int',
        ];
    }

    public static function getOrdersDetail($data) {
        return db::getInstance()->Select('SELECT * FROM  `orders` LEFT JOIN `basket` USING(id_order) LEFT JOIN `user` ON basket.id_user = user.id_user LEFT JOIN `goods` ON basket.id_good = goods.id_good LEFT JOIN `order_status` ON orders.id_order_status = order_status.id_order_status WHERE id_order = :order', ['order' => $data['id']]);      
    }

    public static function getOrders() {
        return db::getInstance()->Select('SELECT * FROM (`orders` INNER JOIN `user` ON orders.id_user = user.id_user) INNER JOIN `order_status` ON orders.id_order_status = order_status.id_order_status ORDER BY datatime_create');

    }

    public static function setOrders($id_order, $id_status) {
        return db::getInstance()->Query('UPDATE orders SET id_order_status=:id WHERE id_order=:id_order', ['id' => $id_status, 'id_order' => $id_order]);
    }

    public static function deleteOrders() {
        return db::getInstance()->Query('DELETE orders, basket FROM orders LEFT JOIN basket ON orders.id_order = basket.id_order WHERE orders.id_order_status=:id', ['id' => 4]);
    }

    public static function newOrders($amount, $id_user, $id_order_status, $data_time, $address, $comment) {
        return db::getInstance()->Query('INSERT INTO orders (`id_user`, `amount`, `address`, `commentary`,`id_order_status`, `datatime_create`) VALUES (:id_user, :amount, :address, :commentary, :id_order_status, :data_time)', ['id_user' => $id_user, 'amount' => $amount, 'address' => $address, 'commentary' => $comment, 'id_order_status' => $id_order_status, 'data_time' => $data_time]);
    }

    public static function newBasket($id_user, $id_good, $price, $q, $id_order_data) {
        return db::getInstance()->Query('INSERT INTO basket (`id_user`, `id_good`, `price`, `is_in_order`, `id_order`) VALUES (:id_user, :id_good, :price, :q, :id_order)', ['id_user' => $id_user, 'id_good' => $id_good, 'price' => $price, 'q' => $q, 'id_order' => $id_order_data]);
    }

    public static function getNewOrder($data_time, $amount, $id_user) {
        return db::getInstance()->Select('SELECT id_order FROM orders WHERE datatime_create=:data_time AND amount=:amount AND id_user=:id_user', ['data_time' => $data_time, 'amount' => $amount, 'id_user' => $id_user]);
    }

}
