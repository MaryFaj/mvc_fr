<?php

class IndexController extends Controller
{
    public $view = 'index';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= ' | Главная';
    }

function index(){
    
session_start();
    
    if(null!==($_POST['exit'])){
         unset( $_SESSION['user'], $_POST['login_user']);
        $user=null;
       
       
     }

 if((null!==$_POST['login_user'])&&(null!==$_POST['pass'])) {
    $login=strip_tags($_POST['login_user']);
    
    $pass=strip_tags($_POST['pass']);
    $pass=md5($pass);
   
     $user = Auth::getUser($login, $pass);
     
 }     //print_r($user);
     
     if((isset($user))&&(!empty($user))){
            
         foreach ($user as $key=>$u){
        
         }
             $_SESSION['user']=$u;
         
         }

 return ['user' => $user, 'id_user'=>$_SESSION['user']['id_user'], 'user_name'=>$_SESSION['user']['user_name'] ];


    
     
}
}

