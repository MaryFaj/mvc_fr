<?php

class Good extends Model {

    protected static $table = 'goods';

    protected static function setProperties() {
        self::$properties['name'] = [
            'type' => 'varchar',
            'size' => 512
        ];

        self::$properties['price'] = [
            'type' => 'float'
        ];

        self::$properties['description'] = [
            'type' => 'text'
        ];

        self::$properties['category'] = [
            'type' => 'int'
        ];
    }

    public static function getGoodsCategory($data) {
        return db::getInstance()->Select(
                        'SELECT * FROM `goods`  WHERE id_category = :category AND status_good=:status', ['status' => Status::Active, 'category' => $data['id']]);
    }

    public static function getGoodsCategories($data) {
        return db::getInstance()->Select(
                        'SELECT * FROM `goods` NATURAL JOIN `categories`  WHERE id_good = :good AND status_good=:status', ['good' => $data['id'], 'status' => Status::Active]);
    }

    public static function getSpecialOfferGoods($data) {
        return db::getInstance()->Select(
                        'SELECT * FROM goods WHERE status_good=:status AND special=:special', ['status' => Status::Active, 'special' => SpecialOffer::Special]);
    }

    public static function getOneGood($data) {
        return db::getInstance()->Select(
                        'SELECT * FROM goods LEFT JOIN categories USING (id_category)  WHERE id_good = :good', ['good' => $data['id']]);
    }

    public static function getCartGood($id) {
        return db::getInstance()->Select(
                        'SELECT * FROM goods WHERE id_good IN (:cart_good)', ['cart_good' => $id]);
    }

    public static function getSearch($query) {
        return db::getInstance()->Select(                        
                        'SELECT * FROM goods WHERE MATCH(name) AGAINST (:query) AND status_good=:status', ['query' => $query, 'status' => Status::Active]);
    }

    public static function getImage($id_good) {
        return db::getInstance()->Select(
                        'SELECT image FROM goods WHERE id_good = :id_good', ['id_good' => $id_good]);
    }

    public static function isIdValidate($id_good) {
        return db::getInstance()->Select(
                        'SELECT id_good FROM goods WHERE id_good = :id_good', ['id_good' => $id_good]);
    }

    public static function changeOneGood($new_values) {
        return db::getInstance()->Query('UPDATE goods SET name=:name, description=:description, price=:price, old_price=:old_price, id_category=:id_category, status_good=:status, special=:special, image=:image WHERE id_good=:id_good', [ 'name' => $new_values['name'], 'description' => $new_values['description'], 'price' => $new_values['price'], 'old_price' => $new_values['old_price'], 'id_category' => $new_values['id_category'], 'status' => $new_values['status'], 'special' => $new_values['special'], 'image' => $new_values['image'], 'id_good' => $new_values['id_good']]);
    }

    public static function createGood($new_values) {
        return db::getInstance()->Query('INSERT INTO goods SET name=:name, description=:description, image=:image, price=:price, old_price=:old_price, id_category=:id_category, status_good=:status, special=:special', ['name' => $new_values['name'], 'description' => $new_values['description'], 'image' => $new_values['image'], 'price' => $new_values['price'], 'old_price' => $new_values['old_price'], 'id_category' => $new_values['id_category'], 'status' => $new_values['status'], 'special' => $new_values['special']]);
    }

    public static function deleteOneGood($id_good) {
        return db::getInstance()->Query('DELETE FROM goods WHERE id_good=:id_good', ['id_good' => $id_good]);
    }

}
