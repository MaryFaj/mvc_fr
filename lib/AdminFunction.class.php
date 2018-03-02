<?php

class AdminFunction {

    public static function isAdmin($id_user) {
        if ($id_user == '4') {
            $order = OrderGood::getOrdersDetail($data);

            return ['order' => $order, 'id_user' => $_SESSION['user']['id_user'], 'user_name' => $_SESSION['user']['user_name']];
        }
    }

    public static function randomFileName($str_length) {
        $str_characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

        // Возвращаем ложь, если первый параметр равен нулю или не является целым числом
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

    public static function changeImage($h, $w, $src, $newsrc, $type) {
        $newimg = imagecreatetruecolor($h, $w);
        switch ($type) {
            case 'jpeg':
                $img = imagecreatefromjpeg($src);
                imagecopyresampled($newimg, $img, 0, 0, 0, 0, $h, $w, imagesx($img), imagesy($img));
                imagejpeg($newimg, $newsrc);
                break;
            case 'png':
                $img = imagecreatefrompng($src);
                imagecopyresampled($newimg, $img, 0, 0, 0, 0, $h, $w, imagesx($img), imagesy($img));
                imagepng($newimg, $newsrc);
                break;
            case 'gif':
                $img = imagecreatefromgif($src);
                imagecopyresampled($newimg, $img, 0, 0, 0, 0, $h, $w, imagesx($img), imagesy($img));
                imagegif($newimg, $newsrc);
                break;
        }
    }

    public static function myScandir($dir) {
        $list = scandir($dir);
        unset($list[0], $list[1]);
        return array_values($list);
    }

    public static function clearDir($dir) {
        $list = self::myScandir($dir);

        foreach ($list as $file) {
            if (is_dir($dir . $file)) {
                self::clearDir($dir . $file . '/');
                rmdir($dir . $file);
            } else {
                unlink($dir . $file);
            }
        }
    }

    public static function isValidLength($new_values, $max_length, $e = []) {
        foreach ($new_values as $key => $value) {
            if (!in_array($key, $e)) {
                $arr[].=strlen($value);
            }
        }
        return (max($arr) < $max_length) && (min($arr) >= 1);
    }

    public static function isValidLengthTwo($new_values, $max_length, $e = []) {
        foreach ($new_values as $key => $value) {
            if (in_array($key, $e)) {
                $arr[].=strlen($value);
            }
        }
        return (max($arr) < $max_length);
    }

    public static function changeAdminEmail() {
        if (null !== $_POST['admin_email']) {
            $admin_email = strip_tags(trim($_POST['admin_email']));
            if (filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
                if (Auth::updateAdminEmail($admin_email)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function createCategory() {
        if (null !== $_POST['create_category']) {
            $new_values = [];
            $new_values['title'] = strip_tags(trim($_POST['title_adm']));

            $new_values['status'] = strip_tags(trim($_POST['status_adm']));

            $is_valid_length = self::isValidLength($new_values, 200);

            if (($is_valid_length) && (is_numeric($new_values['status']))) {

                if (!empty($_FILES['upload_banner_adm']['name'])) {
                    $type = explode('/', $_FILES['upload_banner_adm']['type'])[1];
                    if (in_array($type, ['jpeg', 'gif', 'png'])) {

                        $dir = '../public_html/images/';
                        $files = scandir($dir, 1);

                        $upl_name = self::randomFileName(7) . '.' . $type;

                        for ($i = 0; in_array($upl_name, $files) == true; $i++) {

                            $upl_name = self::randomFileName(7) . '.' . $type;
                        }

                        $path = '../public_html/images/' . $upl_name;

                        if ($_FILES['upload_banner_adm']['size'] > 2000000) {
                            $error = 'Размер изображения не должен превышать 2 Мб!';
                        } elseif (copy($_FILES['upload_banner_adm']['tmp_name'], $path)) {

                            $new_values['banner'] = $upl_name;

                            Category::createCategory($new_values);
                            $id_category = Category::getIdCategory($new_values);
                            if ($id_category) {
                                $id_category = $id_category[0]['id_category'];
                            }
                            $dir_category = '../public_html/images/' . $id_category;
                            $dir_small = $dir_category . '/small';
                            $dir_big = $dir_category . '/big';
                            $dir_middle = $dir_category . '/middle';

                            file_exists($dir_category) ? ($message = "папка уже создана") : mkdir($dir_category);
                            file_exists($dir_big) ? ($message = "папка уже создана") : mkdir($dir_big);
                            file_exists($dir_small) ? ($message = "папка уже создана") : mkdir($dir_small);
                            file_exists($dir_middle) ? ($message = "папка уже создана") : mkdir($dir_middle);
                        } else {
                            $error = "Ошибка загрузки";
                        }
                    } else {
                        $error = 'Недопустимый тип файла. Загружайте файлы gif, png или jpeg';
                    }
                } else {
                    $error = 'Необходимо загрузить изображение!';
                }
            } else {
                $error_change = "Ошибка загрузки.";
            }
        } else {
            $error_change = "Ошибка загрузки. Некорректное значение в одном из полей";
        }
        return ['error' => $error];
    }

    public static function updateCategory() {
        if ((null !== $_POST['change_category']) && (null !== $_POST['id_category_adm'])) {
            $title = strip_tags(trim($_POST['title_adm']));
            $status = strip_tags($_POST['status_adm']);
            $id_category = strip_tags($_POST['id_category_adm']);

            $new_values = ['title' => $title, 'status' => $status, 'id_category' => $id_category];
            $is_valid_length = self::isValidLength($new_values, 200);

            if (($is_valid_length) && (is_numeric($status)) && (is_numeric($id_category))) {
                $is_id_category = Category::isIdValidate($id_category);

                if ($is_id_category) {

                    if (!empty($_FILES['upload_banner_adm']['name'])) {
                        $type = explode('/', $_FILES['upload_banner_adm']['type'])[1];
                        if (in_array($type, ['jpeg', 'gif', 'png'])) {
                            $dir = '../public_html/images/';
                            $files_dir = scandir($dir, 1);

                            $upl_banner = self::randomFileName(7) . '.' . $type;

                            for ($i = 0; in_array($upl_name, $files_dir) == true; $i++) {
                                $upl_banner = self::randomFileName(7) . '.' . $type;
                            }
                            $path_dir = '../public_html/images/' . $upl_banner;
                            if ($_FILES['upload_banner_adm']['size'] > 2000000) {
                                $upload_status = 'Ошибка загрузки. Размер изображения не должен превышать 2 Мб!';
                            } elseif (copy($_FILES['upload_banner_adm']['tmp_name'], $path_dir)) {
                                $upload_status = 'Изображение успешно загружено';
                                $banner = $upl_banner;
                            } else {
                                $upload_status = "Ошибка загрузки";
                            }
                        } else {
                            $upload_status = 'Недопустимый тип файла. Загружайте файлы gif, png или jpeg';
                        }
                    } else {
                        $banner = Category::getBanner($id_category);
                        $banner = $banner[0]['banner'];
                    }
                    $new_values = ['title' => $title, 'status' => $status, 'id_category' => $id_category, 'banner' => $banner];

                    Category::changeCategory($new_values);
                    $upload_status = 'Успешно';
                } else {
                    $upload_status = "Ошибка загрузки. Некорректное значение в одном из полей";
                }
            } else {
                $upload_status = "Ошибка загрузки. Некорректное значение в одном из полей";
            }
        } elseif (null !== $_POST['delete_category']) {
            $id_category = strip_tags($_POST['id_category_adm']);

            $is_valid_length = strlen($id_category) < 20;
            if (($is_valid_length) && (is_numeric($id_category))) {
                $category = Category::getOneCategory($id_category);
                if ($category) {
                    Category::deleteCategory($id_category);
                    $path_banner = '../public_html/images/' . $category[0]['banner'];
                    unlink($path_banner);
                    Category::deleteGoodsOfCategory($id_category);

                    $path_big = '../public_html/images/' . $category[0]['id_category'] . '/big/';
                    $path_small = '../public_html/images/' . $category[0]['id_category'] . '/small/';
                    $path_middle = '../public_html/images/' . $category[0]['id_category'] . '/middle/';
                    $path_id = '../public_html/images/' . $category[0]['id_category'] . '/';

                    self::clearDir($path_id);
                    rmdir($path_id);
                } else {
                    $upload_status = "Ошибка загрузки.";
                }
            } else {
                $upload_status = "Ошибка загрузки.";
            }
        }
        return ['upload_status' => $upload_status];
    }

    public static function createGood() {
        if (null !== $_POST['create_good']) {
            $new_values = [];
            $new_values['name'] = strip_tags(trim($_POST['name_adm']));
            $new_values['price'] = strip_tags(trim($_POST['price_adm']));
            $new_values['old_price'] = strip_tags(trim($_POST['old_price_adm']));
            $new_values['id_category'] = strip_tags(trim($_POST['title_adm']));
            $new_values['status'] = strip_tags(trim($_POST['status_adm']));
            $new_values['special'] = strip_tags(trim($_POST['special_adm']));
            $new_values['description'] = strip_tags(trim($_POST['description_adm']));

            $is_valid_length = self::isValidLength($new_values, 200, ['description']);
            $is_valid_length_desc = self::isValidLengthTwo($new_values, 15000, ['description']);

            if (($is_valid_length) && ($is_valid_length_desc) && (is_numeric($new_values['status'])) && (is_numeric($new_values['special'])) && (is_numeric($new_values['price'])) && (is_numeric($new_values['old_price'])) && (is_numeric($new_values['id_category']))) {

                $data_id = $new_values['id_category'];
                if (Category::isIdValidate($data_id)) {

                    if (!empty($_FILES['upload_image_adm']['name'])) {
                        $type = explode('/', $_FILES['upload_image_adm']['type'])[1];
                        if (in_array($type, ['jpeg', 'gif', 'png'])) {

                            $dir_big = '../public_html/images/' . $new_values['id_category'] . '/big/';
                            $files_big = scandir($dir_big, 1);
                            $dir_small = '../public_html/images/' . $new_values['id_category'] . '/small/';
                            $files_small = scandir($dir_small, 1);
                            $dir_middle = '../public_html/images/' . $new_values['id_category'] . '/middle/';
                            $files_middle = scandir($dir_middle, 1);

                            $upl_name = self::randomFileName(7) . '.' . $type;

                            for ($i = 0; (in_array($upl_name, $files_big) == true) || (in_array($upl_name, $files_small) == true) || (in_array($upl_name, $files_middle) == true); $i++) {

                                $upl_name = self::randomFileName(7) . '.' . $type;
                            }

                            $path_big = '../public_html/images/' . $new_values['id_category'] . '/big/' . $upl_name;
                            $path_small = '../public_html/images/' . $new_values['id_category'] . '/small/' . $upl_name;
                            $path_middle = '../public_html/images/' . $new_values['id_category'] . '/middle/' . $upl_name;

                            if ($_FILES['upload_image_adm']['size'] > 2000000) {
                                $error = 'Размер изображения не должен превышать 2 Мб!';
                            } elseif (copy($_FILES['upload_image_adm']['tmp_name'], $path_big)) {
                                self::changeImage(140, 140, $_FILES['upload_image_adm']['tmp_name'], $path_small, $type);
                                self::changeImage(400, 400, $_FILES['upload_image_adm']['tmp_name'], $path_middle, $type);
                                $new_values['image'] = $upl_name;

                                Good::createGood($new_values);
                            } else {
                                $error = "Ошибка загрузки";
                            }
                        } else {
                            $error = 'Недопустимый тип файла. Загружайте файлы gif, png или jpeg';
                        }
                    } else {
                        $error = 'Необходимо загрузить изображение!';
                    }
                } else {
                    $error = "Такой категории нет";
                }
            } else {
                $error_change = "Ошибка загрузки.";
            }
        } else {
            $error_change = "Ошибка загрузки. Некорректное значение в одном из полей";
        }
        return ['error' => $error];
    }

    public static function updateGood() {
        if ((null != $_POST['id_good_adm']) && (null != $_POST['change'])) {

            $name = strip_tags(trim($_POST['name_adm']));
            $price = strip_tags(trim($_POST['price_adm']));
            $old_price = strip_tags(trim($_POST['old_price_adm']));
            $id_category = strip_tags(trim($_POST['title_adm']));
            $status = strip_tags(trim($_POST['status_adm']));
            $special = strip_tags(trim($_POST['special_adm']));
            $description = strip_tags(trim($_POST['description_adm']));
            $id_good = strip_tags($_POST['id_good_adm']);

            $new_values = ['name' => $name, 'price' => $price, 'old_price' => $old_price, 'id_category' => $id_category, 'status' => $status, 'special' => $special, 'description' => $description, 'id_good' => $id_good];


            $is_valid_length = self::isValidLength($new_values, 200, ['description']);
            $is_valid_length_desc = self::isValidLengthTwo($new_values, 15000, ['description']);

            if (($is_valid_length) && ($is_valid_length_desc) && (is_numeric($id_good)) && (is_numeric($status)) && (is_numeric($special)) && (is_numeric($price)) && (is_numeric($old_price)) && (is_numeric($id_category))) {

                if (Category::isIdValidate($id_category)) {
                    $data = [];
                    $data['id'] = $id_good;
                    $good = Good::getOneGood($data);
                    if ($good) {

                        $path_big_old = '../public_html/images/' . $good[0]['id_category'] . '/big/' . $good[0]['image'];
                        $path_small_old = '../public_html/images/' . $good[0]['id_category'] . '/small/' . $good[0]['image'];
                        $path_middle_old = '../public_html/images/' . $good[0]['id_category'] . '/middle/' . $good[0]['image'];

                        $path_big_new = '../public_html/images/' . $id_category . '/big/' . $good[0]['image'];
                        $path_small_new = '../public_html/images/' . $id_category . '/small/' . $good[0]['image'];
                        $path_middle_new = '../public_html/images/' . $id_category . '/middle/' . $good[0]['image'];

                        if (!empty($_FILES['upload_image_adm']['name'])) {
                            $type = explode('/', $_FILES['upload_image_adm']['type'])[1];
                            if (in_array($type, ['jpeg', 'gif', 'png'])) {

                                $dir_big = '../public_html/images/' . $id_category . '/big/';
                                $files_big = scandir($dir_big, 1);
                                $dir_small = '../public_html/images/' . $id_category . '/small/';
                                $files_small = scandir($dir_small, 1);
                                $dir_middle = '../public_html/images/' . $id_category . '/middle/';
                                $files_middle = scandir($dir_middle, 1);

                                $upl_name = self::randomFileName(7) . '.' . $type;

                                for ($i = 0; (in_array($upl_name, $files_big) == true) || (in_array($upl_name, $files_small) == true) || (in_array($upl_name, $files_middle) == true); $i++) {

                                    $upl_name = self::randomFileName(7) . '.' . $type;
                                }

                                $path_big = '../public_html/images/' . $id_category . '/big/' . $upl_name;
                                $path_small = '../public_html/images/' . $id_category . '/small/' . $upl_name;
                                $path_middle = '../public_html/images/' . $id_category . '/middle/' . $upl_name;

                                if ($_FILES['upload_image_adm']['size'] > 2000000) {
                                    $error_size = 'Размер изображения не должен превышать 2 Мб!';
                                } elseif (copy($_FILES['upload_image_adm']['tmp_name'], $path_big)) {
                                    self::changeImage(140, 140, $_FILES['upload_image_adm']['tmp_name'], $path_small, $type);
                                    self::changeImage(400, 400, $_FILES['upload_image_adm']['tmp_name'], $path_middle, $type);


                                    $new_values['image'] = $upl_name;

                                    if (Good::changeOneGood($new_values)) {
                                        unlink($path_big_old);
                                        unlink($path_small_old);
                                        unlink($path_middle_old);
                                    } else {
                                        $error_change = "Ошибка. Попробуйте еще раз.";
                                    }
                                } else {
                                    $error_change = "Ошибка загрузки";
                                    $new_values['image'] = $good[0]['image'];
                                }
                            } else {
                                $error_change = 'Недопустимый тип файла. Загружайте файлы gif, png или jpeg';
                            }
                        } else {
                            $new_values['image'] = $good[0]['image'];

                            if (Good::changeOneGood($new_values)) {
                                if (!($good[0]['id_category'] == $id_category)) {
                                    rename($path_big_old, $path_big_new);
                                    rename($path_small_old, $path_small_new);
                                    rename($path_middle_old, $path_middle_new);
                                }
                            }
                        }
                    } else {
                        $error_change = "Ошибка загрузки.";
                    }
                }
            } else {
                $error_change = "Ошибка загрузки. Некорректное значение в одном из полей";
            }
        } elseif (null !== $_POST['delete_adm']) {

            $id_good = strip_tags($_POST['id_good_adm']);

            $is_valid_length = strlen($id_good) < 20;
            if (($is_valid_length) && (is_numeric($id_good))) {
                if (Good::isIdValidate($id_good)) {
                    $data['id'] = $id_good;
                    $good = Good::getOneGood($data);
                    if ($good) {

                        $path_big = '../public_html/images/' . $good[0]['id_category'] . '/big/' . $good[0]['image'];
                        $path_small = '../public_html/images/' . $good[0]['id_category'] . '/small/' . $good[0]['image'];
                        $path_middle = '../public_html/images/' . $good[0]['id_category'] . '/middle/' . $good[0]['image'];

                        if (Good::deleteOneGood($id_good)) {
                            unlink($path_big);
                            unlink($path_small);
                            unlink($path_middle);
                        } else {
                            $error_change = "Ошибка. Попробуйте еще раз.";
                        }
                    } else {
                        $error_change = "Ошибка. Попробуйте еще раз.";
                    }
                } else {
                    $error_change = "Ошибка";
                }
            } else {
                $error_change = "Ошибка";
            }
        }
        return ['error_size' => $error_size, 'error_change' => $error_change];
    }

}
