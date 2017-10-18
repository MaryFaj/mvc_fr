<?php
class UserController extends Controller
{
    public $view = 'user';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= ' | Регистрация';
    }

function index(){
    
session_start();
    
   
if((null!==$_POST['user_name'])&&(null!==$_POST['password'])&&(null!==$_POST['login'])) {
    $login=strip_tags($_POST['login_user']);
    
    $pass=strip_tags($_POST['password']);
    $pass=md5($pass);
    $name=strip_tags($_POST['user_name']);
    $login=strip_tags($_POST['login']);
    $email=strip_tags($_POST['email']);
    
   
   
     $new_user = Auth::setUser($login, $pass, $name, $email);
     
        //print_r($user);
     
     header("Location: /public/"  );
        return true;
    }

}
}

?>