<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов
include_once "../config/core_a.php";
include_once "../shared/utilities.php";
include_once "../config/database.php";
include_once "../objects/author.php";

// utilities
$utilities = new Utilities();

// создание подключения
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$author = new Author($db);

// запрос товаров
$stmt = $author->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// если больше 0 записей
if ($num>0) {

    // массив товаров
    $authors_arr=array();
    $authors_arr["records"]=array();
    $authors_arr["paging"]=array();

    // получаем содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечение строки
        extract($row);

        $author_item=array(
            "id" => $id,
            "surname" => $surname,
            "name" => $name,
            "lastname" => $lastname
        );

        array_push($authors_arr["records"], $author_item);
    }

    // подключим пагинацию
    $total_rows=$author->count();
    $page_url="{$home_url}author/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $authors_arr["paging"]=$paging;

    // установим код ответа - 200 OK
    http_response_code(200);

    // вывод в json-формате
    echo json_encode($authors_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что товаров не существует
    echo json_encode(array("message" => "Автор не найдены."), JSON_UNESCAPED_UNICODE);
}
?>