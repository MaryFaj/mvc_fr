<?php
class CategoriesController extends Controller
{

    public $view = 'categories';

    public function index($data)
    {
        session_start();
        $categories = Category::getCategories($data['id']);
        
        $goods = Good::getGoodsCategory($data);
        
        // print_r($categories);
        
        return ['subcategories' => $categories, 'goods' => $goods, 'id_user'=>$_SESSION['user']['id_user']];
        
       
    }
}































?>