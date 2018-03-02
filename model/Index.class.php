<?php

class Index extends Model {

    public static function setSubscribe($email) {
        return db::getInstance()->Query('INSERT INTO subscribe (`email_subscribe`) VALUES (:email)', ['email' =>
                    $email]);
    }

    public static function isNotSubscribe($email) {
        return count(db::getInstance()->Select(
                                'SELECT * FROM subscribe WHERE email_subscribe = :email', ['email' => $email]
                )) === 0;
    }

}