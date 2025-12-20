<?php

class TaskService{
    private mysqli $conn;

    public function __construct(mysqli $conn){
        $this->conn = $conn;
    }

    public function getTasks():array{
        $result = $this->conn->query('
        SELECT t.*, tc.category_name, completor.username AS completorUser, author.username AS author_name FROM task t
        LEFT JOIN user completor ON t.completorUser = completor.id
        LEFT JOIN user author ON t.author_id = author.id
        LEFT JOIN task_category tc ON t.category = tc.id');

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function completeTask(string $completorUser, int $taskId):bool{
        $stmt = $this->conn->prepare('
        UPDATE task t
        INNER JOIN user u ON u.username = ?
        SET completed_date = CURDATE(), completorUser = u.id WHERE t.id = ?');
        $stmt->bind_param("si", $completorUser, $taskId);

        $result = $stmt->execute();
        if (!$result) return false;

        return true;
    }

    public function deleteTask(int $taskId):bool{
        $stmt = $this->conn->prepare('DELETE FROM task WHERE id = ?');
        $stmt->bind_param("i", $taskId);

        $result = $stmt->execute();
        if (!$result) return false;

        return true;
    }

    public function getMemberTasks(int $user_id):array{
        return [];
    }
}