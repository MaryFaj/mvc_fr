<?php
require_once 'autoload.php';

try{
    App::init();
}
catch (PDOException $e){
	Logger::Write($e);
    echo "DB is not available";
    //var_dump($e->getTrace());
}
catch (Exception $e){
	Logger::Write($e);
    //echo $e->getMessage();
	echo "Error";
}

