<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once "../config/database.php";
include_once "../objects/author.php";

// создание подключения к базе данных
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$author = new Author($db);

// запрос для авторов
$stmt = $author->read();
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num>0) {

    // массив
    $authors_arr=array();
    $authors_arr["records"]=array();

    // получим содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлекаем строку
        extract($row);

        $author_item=array(
            "id" => $id,
            "surname" => $surname,
            "name" => $name,
            "lastname" => $lastname
        );

        array_push($authors_arr["records"], $author_item);
    }

    // код ответа - 200 OK
    http_response_code(200);

    // покажем данные категорий в формате json
    echo json_encode($authors_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что авторы не найдены
    echo json_encode(array("message" => "Авторы не найдены."), JSON_UNESCAPED_UNICODE);
}
?>