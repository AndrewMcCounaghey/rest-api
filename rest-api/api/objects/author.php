<?php
class Author {

    // соединение с БД и таблицей "author"
    private $conn;
    private $table_name = "author";

    // свойства объекта
    public $id;
    public $name;
    public $description;
    public $created;

    public function __construct($db) {
        $this->conn = $db;
    }

    // используем раскрывающийся список выбора
    public function readAll() {
        // выборка всех данных
        $query = "SELECT
                    id, surname, name, lastname
                FROM
                    " . $this->table_name . "
                ORDER BY
                    surname";
 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
 
        return $stmt;
    }
    // используем раскрывающийся список выбора
    public function read() {

        // выбираем все данные
        $query = "SELECT
                    id, surname, name, lastname
                FROM
                    " . $this->table_name . "
                ORDER BY
                    surname";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    // метод create - создание товаров
    function create(){

        // запрос для вставки (создания) записей
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    surname=:surname, name=:name, lastname=:lastname";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        
        // привязка значений
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":lastname", $this->lastname);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // используется при заполнении формы обновления товара
    function readOne() {

        // запрос для чтения одной записи (товара)
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";

        // подготовка запроса
        $stmt = $this->conn->prepare( $query );

        // привязываем id товара, который будет обновлен
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->surname = $row["surname"];
        $this->name = $row["name"];
        $this->lastname = $row["lastname"];
    }
    // метод update() - обновление товара
    function update(){

        // запрос для обновления записи (товара)
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    surname = :surname,
                    name = :name,
                    lastname = :lastname
                WHERE
                    id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));

        // привязываем значения
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":lastname", $this->lastname);

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
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY surname ASC LIMIT ?, ?";

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