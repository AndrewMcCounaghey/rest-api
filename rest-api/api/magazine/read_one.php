<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение файла для соединения с базой и файл с объектом
include_once "../config/database.php";
include_once "../objects/magazine.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$magazine = new Magazine($db);

// установим свойство ID записи для чтения
$magazine->id = isset($_GET["id"]) ? $_GET["id"] : die();

// прочитаем детали товара для редактирования
$magazine->readOne();

if ($magazine->title!=null) {

    // создание массива
    $magazine_arr = array(
        "id" =>  $magazine->id,
        "title" => $magazine->title,
        "description" => $magazine->description,
        "images" => $magazine->images,
        "id_author" => $magazine->id_author,
        "surname_author" => $magazine->surname_author,
        "name_author" => $magazine->name_author,
        "lastname_author" => $magazine->lastname_author
    );

    // код ответа - 200 OK
    http_response_code(200);

    // вывод в формате json
    echo json_encode($magazine_arr);
}

else {
    // код ответа - 404 Не найдено
    http_response_code(404);

    // сообщим пользователю, что товар не существует
    echo json_encode(array("message" => "Журнал не существует.","title"=>$magazine->title), JSON_UNESCAPED_UNICODE);
}
?>