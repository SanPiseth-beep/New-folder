<?php 
class Quote {
    private $conn;
    private $table_name = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $author_name;
    public $category_id;
    public $category_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        if (isset($this->author_id) && isset($this->category_id)){
            $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                      FROM $this->table_name p
                      LEFT JOIN categories c ON p.category_id = c.id
                      LEFT JOIN authors a ON p.author_id = a.id
                      WHERE p.author_id = ? AND p.category_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->author_id);
            $stmt->bindParam(2, $this->category_id);
        } else if (isset($this->author_id)){
            $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                      FROM $this->table_name p
                      LEFT JOIN categories c ON p.category_id = c.id
                      LEFT JOIN authors a ON p.author_id = a.id
                      WHERE p.author_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->author_id);
        } else if(isset($this->category_id)){
            $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                      FROM $this->table_name p
                      LEFT JOIN categories c ON p.category_id = c.id
                      LEFT JOIN authors a ON p.author_id = a.id
                      WHERE p.category_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->category_id);
        } else {
            $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                      FROM $this->table_name p
                      LEFT JOIN categories c ON p.category_id = c.id
                      LEFT JOIN authors a ON p.author_id = a.id
                      ORDER BY p.id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        return $stmt;
    }

    public function read_single() {
        $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                  FROM $this->table_name p
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN authors a ON p.author_id = a.id
                  WHERE p.id = ?
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            $this->id = $row['id'];
            $this->quote = $row['quote'];
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
            return true;
        } else {
            return false;
        }
    }

    public function create() {
        $query = "INSERT INTO $this->table_name (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    public function update() {
        $query = "UPDATE $this->table_name 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}