<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение базы данных и файл, содержащий объекты
include_once "../config/database.php";
include_once "../objects/magazine.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// инициализируем объект
$magazine = new Magazine($db);
 
// запрашиваем товары
$stmt = $magazine->read();
$num = $stmt->rowCount();

// проверка, найдено ли больше 0 записей
if ($num>0) {

    // массив товаров
    $magazines_arr = array();
    $magazines_arr["records"] = array();

    // получаем содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлекаем строку
        extract($row);

        $magazine_item = array(
            "id" => $id,
            "title" => $title,
            "description" => html_entity_decode($description),
            "images" => $images,
            "id_author" => $id_author,
            "surname_author" => $surname_author,
	        "name_author" => $name_author,
	        "lastname_author" => $lastname_author
        );

        array_push($magazines_arr["records"], $magazine_item);
    }

    // устанавливаем код ответа - 200 OK
    http_response_code(200);

    // выводим данные о товаре в формате JSON
    echo json_encode($magazines_arr);
}
else {

    // установим код ответа - 404 Не найдено
    http_response_code(404);

    // сообщаем пользователю, что товары не найдены
    echo json_encode(array("message" => "Журналы не найдены."), JSON_UNESCAPED_UNICODE);
}
?>