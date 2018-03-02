<?php

class Auth extends Model {

    public static function getAdminEmail() {
        return db::getInstance()->Select('SELECT email FROM user WHERE id_user=:id', ['id' => 4]);
    }

    public static function getUser($login, $pass, $activation) {
        return db::getInstance()->Select('SELECT * FROM user WHERE user_login=:login AND complete_password=:pass AND activation=:activation', ['login' => $login, 'pass' => $pass, 'activation' => 1]);
    }

    public static function getActivation($activation_key) {
        return db::getInstance()->Select('SELECT user_name FROM user WHERE activation_key=:activation_key', ['activation_key' => $activation_key]);
    }

    public static function setActivation($activation_key) {
        return db::getInstance()->Query('UPDATE user SET activation=:activation WHERE activation_key=:activation_key', [ 'activation' => 1, 'activation_key' => $activation_key]);
    }

    public static function getSalt($login) {
        return db::getInstance()->Select('SELECT `salt` FROM user WHERE user_login=:login ', ['login' => $login]);
    }

    public static function goodLogin($login) {
        return count(db::getInstance()->Select(
                                'SELECT * FROM user WHERE user_login = :login', ['login' => $login]
                )) === 0;
    }

    public static function goodEmail($email) {
        return count(db::getInstance()->Select(
                                'SELECT * FROM user WHERE email = :email', ['email' => $email]
                )) === 0;
    }

    public static function setUser($login, $pass_complete, $salt, $name, $email, $activation, $activation_key, $phone) {
        return db::getInstance()->Query('INSERT INTO user (`user_name`, `complete_password`, `salt`, `user_login`, `email`, `activation`, `activation_key`, `phone`) VALUES (:name, :complete_pass, :salt, :login, :email, :activation, :activation_key, :phone)', ['name' => $name, 'salt' => $salt, 'complete_pass' => $pass_complete, 'login' => $login, 'email' => $email, 'activation' => $activation, 'activation_key' => $activation_key, 'phone' => $phone]);
    }

    public static function getTimeRegistration() {
        return db::getInstance()->Select('SELECT `user_last_action` FROM user WHERE activation=:activation', ['activation' => 0]);
    }

    public static function deleteNotActiveUsers_1($to_delete) {
        return db::getInstance()->Query('DELETE FROM `user` WHERE `user_last_action` IN(:to_delete)', ['to_delete' => $to_delete]);
    }

    public static function updateAdminEmail($admin_email) {
        return db::getInstance()->Query('UPDATE user SET email=:email WHERE id_user=:id', ['email' => $admin_email, 'id' => 4]);
    }

    public static function deleteNotActiveUsers($time_del) {
        return db::getInstance()->Query('DELETE FROM `user` WHERE `user_last_action`<(:timedel) AND activation=:activation ', ['timedel' => $time_del, 'activation' => 0]);
    }

}

