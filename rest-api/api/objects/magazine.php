<?php
class Magazine {

    // подключение к базе данных и таблице "magazine"
    private $conn;
    private $table_name = "magazine";

    // свойства объекта
    public $id;
    public $title;
    public $description;
    public $images;
    public $date_public;
    public $id_author;

    // конструктор для соединения с базой данных
    public function __construct($db){
        $this->conn = $db;
    }

    function read() {

    // выбираем все записи
        $query = "SELECT
                    a.surname as surname_author, a.name as name_author, a.lastname as last_name_author, m.id, m.title, m.description, m.images, m.id_author, m.date_public
                FROM
                    " . $this->table_name . " m
                    LEFT JOIN
                        author a
                            ON m.id_author = a.id
                ORDER BY
                    m.date_public DESC";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // выполняем запрос
        $stmt->execute();

        return $stmt;
    }
    // метод create - создание товаров
    function create(){

        // запрос для вставки (создания) записей
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    title=:title, images=:images, description=:description, id_author=:id_author, date_public=:date_public";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);
        if(!empty($this->images)){
            $file = $this->images;
            if($file['size']<=2097152){
                $path = __DIR__ . '/uploads/';
                $t = explode(".", $file['name']);
                $type = $t[1];
                $name = md5($file['name']);
                move_uploaded_file($file['tmp_name'], $path.$name.".".$type);
            }
            else{
                return false;
            }
        }
        // // очистка
        // $this->title=htmlspecialchars(strip_tags($this->title));
        // $this->description=htmlspecialchars(strip_tags($this->description));
        // $this->id_author=htmlspecialchars(strip_tags($this->id_author));
        // $this->date_public=htmlspecialchars(strip_tags($this->date_public));

        // привязка значений
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":images", $name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id_author", $this->id_author);
        $stmt->bindParam(":date_public", $this->date_public);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // используется при заполнении формы обновления товара
    function readOne() {

        // запрос для чтения одной записи (товара)
        $query = "SELECT
                   a.surname as surname_author, a.name as name_author, a.lastname as lastname_author, m.id, m.title, m.description, m.images, m.id_author, m.date_public
                FROM
                    " . $this->table_name . " m
                    LEFT JOIN
                        author a
                            ON m.id_author = a.id
                WHERE
                    m.id = :id
                LIMIT
                    0,1";

        // подготовка запроса
        $stmt = $this->conn->prepare( $query );

        // привязываем id товара, который будет обновлен
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->title = $row["title"];
        $this->images = $row["images"];
        $this->description = $row["description"];
        $this->id_author = $row["id_author"];
        $this->surname_author = $row["surname_author"];
        $this->name_author = $row["name_author"];
        $this->lastname_author = $row["lastname_author"];
    }
    // метод update() - обновление товара
    function update(){

        // запрос для обновления записи (товара)
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    title = :title,
                    images = :images,
                    description = :description,
                    id_author = :id_author
                WHERE
                    id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->images=htmlspecialchars(strip_tags($this->images));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->id_author=htmlspecialchars(strip_tags($this->id_author));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем значения
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":images", $this->images);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id_author", $this->id_author);
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // метод delete - удаление товара
    function delete(){

        // запрос для удаления записи (товара)
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // чтение товаров с пагинацией
    function readPaging($from_record_num, $records_per_page){

        // выборка
        $query = "SELECT
                    a.surname as surname_author, a.name as name_author, a.lastname as lastname_author, m.id, m.title, m.description, m.images, m.id_author, m.date_public
                FROM
                    " . $this->table_name . " m
                    LEFT JOIN
                        author a
                            ON m.id_author = a.id
                ORDER BY m.date_public DESC
                LIMIT ?, ?";

        // подготовка запроса
        $stmt = $this->conn->prepare( $query );

        // свяжем значения переменных
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // выполняем запрос
        $stmt->execute();

        // вернём значения из базы данных
        return $stmt;
    }
    // используется для пагинации товаров
    function count() {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["total_rows"];
    }
}
?>