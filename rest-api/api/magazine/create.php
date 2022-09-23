<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных
include_once "../config/database.php";

// создание объекта товара
include_once "../objects/magazine.php";

$database = new Database();
$db = $database->getConnection();

$magazine = new Magazine($db);
 
// получаем отправленные данные
$title = $_POST['title'];
$description = $_POST['description'];
$id_author = $_POST['id_author'];
$images = $_FILES['images'];
$msg_img = '';
// убеждаемся, что данные не пусты
if (!empty($title) && !empty($id_author)) {

    // устанавливаем значения свойств товара
    $magazine->title = $title;
    $magazine->images = $images;
    if($images['size']>2097152){
        $msg_img = "Размер файла не должен привышать 2 MB.";
    }
    $magazine->description = $description;
    $magazine->id_author = $id_author;
    $magazine->date_public = date("Y-m-d");

    // создание товара
    if($magazine->create()){

        // установим код ответа - 201 создано
        http_response_code(201);

        // сообщим пользователю
        echo json_encode(array("message" => "Журнал был создан."), JSON_UNESCAPED_UNICODE);
    }

    // если не удается создать журнал, сообщим пользователю
    else {

        // установим код ответа - 503 сервис недоступен
        http_response_code(503);

        // сообщим пользователю
        echo json_encode(array("message" => "Невозможно создать журнал. ".$msg_img." "), JSON_UNESCAPED_UNICODE);
    }
}

// сообщим пользователю что данные неполные
else {

    // установим код ответа - 400 неверный запрос
    http_response_code(400);

    // сообщим пользователю
    echo json_encode(array("message" => "Невозможно создать журнал. Данные неполные.", "data"=>$description), JSON_UNESCAPED_UNICODE);
}
?>