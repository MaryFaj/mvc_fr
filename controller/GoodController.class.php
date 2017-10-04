<?php
class GoodController extends Controller
{

    public $view = 'good';

    public function index($data)
    {
        $goods = Good::getOneGood($data);
        
       // print_r($data);
        
        return ['good' => $goods];
        
      }
}
?>