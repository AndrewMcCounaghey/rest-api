<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов
include_once "../config/core_a.php";
include_once "../shared/utilities.php";
include_once "../config/database.php";
include_once "../objects/magazine.php";

// utilities
$utilities = new Utilities();

// создание подключения
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$magazine = new Magazine($db);

// запрос товаров
$stmt = $magazine->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// если больше 0 записей
if ($num>0) {

    // массив товаров
    $magazines_arr=array();
    $magazines_arr["records"]=array();
    $magazines_arr["paging"]=array();

    // получаем содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечение строки
        extract($row);

        $magazine_item=array(
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

    // подключим пагинацию
    $total_rows=$magazine->count();
    $page_url="{$home_url}magazine/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $magazines_arr["paging"]=$paging;

    // установим код ответа - 200 OK
    http_response_code(200);

    // вывод в json-формате
    echo json_encode($magazines_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что товаров не существует
    echo json_encode(array("message" => "Журнал не найдены."), JSON_UNESCAPED_UNICODE);
}
?>