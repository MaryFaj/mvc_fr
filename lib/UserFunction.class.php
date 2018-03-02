<?php

class UserFunction {

    public static function randomString($str_length) {
        $str_characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        // Возвращаем ложь, если параметр равен нулю или не является целым числом
        if (!is_int($str_length) || $str_length < 0) {
            return false;
        }

        // Подсчитываем реальное количество символов, участвующих в формировании случайной строки и вычитаем 1
        $characters_length = count($str_characters) - 1;

        // Объявляем переменную для хранения итогового результата
        $string = '';

        // Формируем случайную строку в цикле
        for ($i = $str_length; $i > 0; $i--) {
            $string .= $str_characters[mt_rand(0, $characters_length)];
        }

        // Возвращаем результат
        return $string;
    }

    public static function userNameValidation($user_name) {

        // максимальная длина
        if (strlen($user_name) > 55) {
            return false;
        }
        // должны иметь по крайней мере один символ
        if (strspn($user_name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZабвгдеёжзиклмнопрстуфхцчшщйьъэюяАБВГДЕЁЖЗЗИКЛМНОПРСТУФХЦЧЩШЙЪЬЭЮЯ0123456789-") == 0) {
            return false;
        }

        // должна содержать все допустимые символы
        if (strspn($user_name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZабвгдеёжзиклмнопрстуфхцчшщйьъэюяАБВГДЕЁЖЗЗИКЛМНОПРСТУФХЦЧЩШЙЪЬЭЮЯ0123456789-_ ") != strlen($user_name)) {
            return false;
        }

        // Запрещённые логины для регистрации
        if (preg_match("/^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)|(uucp)
|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)
|(ns)|(download))$/i", $user_name)) {
            return false;
        }
        if (preg_match("/^(anoncvs_)/", $user_name)) {
            return false;
        }

        return true;
    }

    public static function userPasswordValidation($user_password) {
        // максимальная длина
        if (strlen($user_password) > 55) {
            return false;
        }
        // должны иметь по крайней мере один символ
        if (strspn($user_password, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-") == 0) {
            return false;
        }
        // должна содержать все допустимые символы
        if (strspn($user_password, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_") != strlen($user_password)) {
            return false;
        }

        return true;
    }

    public static function userExit() {
        session_start();

        if (null != $_POST['exit']) {
            unset($_SESSION['user'], $_POST['login_user']);
            $user = null;
        }
    }

    public static function authorisation() {
        session_start();

        if ((null !== $_POST['login_user']) && (null !== $_POST['pass'])) {

            $login = trim($_POST['login_user']);
            $pass = trim($_POST['pass']);
            if (self::userNameValidation($login)) {
                if (self::userPasswordValidation($pass)) {
                    $salt = Auth::getSalt($login);
                    if ($salt) {
                        $salt = $salt[0]['salt'];

                        $pass = md5($pass . $salt);

                        $user = Auth::getUser($login, $pass, $activation);
                        if ($user) {
                            $_SESSION['user'] = $user[0];
                        } else {
                            $error = "Неверный пароль или вы не подтвердили свою регистрацию на сайте по e-mail";
                        }
                    } else {
                        $error = "Неверный логин";
                    }
                }
            }
        }
        return['error' => $error];
    }

    public static function deleteNotActive() {
        $date_today = time();
        $month = (4 * 7 * 24 * 60 * 60);
        $time_del = $date_today - $month;
        $time_del = strval(date('Y-m-d H:i:s', $time_del));

        Auth::deleteNotActiveUsers($time_del);

        return true;
    }

}
