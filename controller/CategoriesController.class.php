<?php
class CategoriesController extends Controller
{

    public $view = 'categories';

    public function index($data)
    {
        $categories = Category::getCategories($data['id']);
        
        $goods = Good::getGoodsCategory($data);
        
         //print_r($data);
        
        return ['subcategories' => $categories, 'goods' => $goods];
        
       
    }
}































?>