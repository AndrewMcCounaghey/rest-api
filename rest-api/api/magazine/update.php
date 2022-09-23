<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл для работы с БД и объектом Product
include_once "../config/database.php";
include_once "../objects/magazine.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$magazine = new Magazine($db);

// получаем id журнала для редактирования
$data = json_decode(file_get_contents("php://input"));

// установим id свойства журнала для редактирования
$magazine->id = $data->id;

// установим значения свойств журнала
$magazine->title = $data->title;
$magazine->images = $data->images;
$magazine->description = $data->description;
$magazine->id_author = $data->id_author;

// обновление товара
if ($magazine->update()) {

    // установим код ответа - 200 ok
    http_response_code(200);

    // сообщим пользователю
    echo json_encode(array("message" => "Журнал был обновлён."), JSON_UNESCAPED_UNICODE);
}

// если не удается обновить журнал, сообщим пользователю
else {

    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    // сообщение пользователю
    echo json_encode(array("message" => "Невозможно обновить журнал."), JSON_UNESCAPED_UNICODE);
}
?>