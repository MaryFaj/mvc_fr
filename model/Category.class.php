<?php

class Category extends Model {

    protected static $table = 'categories';

    protected static function setProperties() {
        self::$properties['name'] = [
            'type' => 'varchar',
            'size' => 512
        ];

        self::$properties['parent_id'] = [
            'type' => 'int',
        ];
    }

    public static function getCategories() {
        return db::getInstance()->Select(
                        'SELECT id_category, title FROM categories WHERE status=:status', ['status' => Status::Active]);
    }

    public static function getCategoryGoods($data) {
        return db::getInstance()->Select(
                        'SELECT * FROM categories WHERE status=:status AND id_category =:id', ['status' => Status::Active, 'id' => $data['id']]);
    }

    public static function createCategory($new_values) {
        return db::getInstance()->Query('INSERT INTO categories SET title=:title, banner=:banner, status=:status', ['title' => $new_values['title'], 'banner' => $new_values['banner'], 'status' => $new_values['status']]);
    }

    public static function getIdCategory($new_values) {
        return db::getInstance()->Select(
                        'SELECT id_category FROM categories WHERE title=:title AND banner=:banner', ['title' => $new_values['title'], 'banner' => $new_values['banner']]);
    }

    public static function changeCategory($new_values) {
        return db::getInstance()->Query('UPDATE categories SET title=:title, banner=:banner, status=:status WHERE id_category=:id_category', ['id_category' => $new_values['id_category'], 'title' => $new_values['title'], 'banner' => $new_values['banner'], 'status' => $new_values['status']]);
    }

    public static function isIdValidate($id_category) {
        return db::getInstance()->Select(
                        'SELECT id_category FROM categories WHERE id_category =:id_category', ['id_category' => $id_category]);
    }

    public static function getOneCategory($id_category) {
        return db::getInstance()->Select(
                        'SELECT * FROM categories WHERE id_category =:id_category AND status=:status', ['id_category' => $id_category, 'status' => Status::Active]);
    }

    public static function getBanner($id_category) {
        return db::getInstance()->Select(
                        'SELECT banner FROM categories WHERE id_category=:id_category', ['id_category' => $id_category]);
    }

    public static function deleteCategory($id_category) {
        return db::getInstance()->Query('DELETE FROM categories WHERE id_category=:id_category', ['id_category' => $id_category]);
    }

    public static function deleteGoodsOfCategory($id_category) {
        return db::getInstance()->Query('DELETE FROM goods WHERE id_category=:id_category', ['id_category' => $id_category]);
    }

}
