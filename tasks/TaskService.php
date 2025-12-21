<?php
class TaskService{
    private mysqli $conn;

    public function __construct(mysqli $conn){
        $this->conn = $conn;
    }

    public function createTask(object $createData):bool{

        return true;
    }

    public function getTasks():array{
        $result = $this->conn->query('
        SELECT t.*, tc.category_name, completor.username AS completorUser, author.username AS author_name FROM task t
        INNER JOIN user completor ON t.completorUser = completor.id
        INNER JOIN user author ON t.author_id = author.id
        INNER JOIN task_category tc ON t.category = tc.id');

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

    /** Meget spesiel phpTractor dritt
     * @return array<int, array{
     *     userId: int,
     *     username: string,
     *     sumPoints: int,
     *     tasks: array<int, array{
     *         taskId: int,
     *         taskName: string,
     *         taskDescription: string,
     *         taskDifficulty: int,
     *         category: string,
     *         added_date: string,
     *         author_name: string,
     *         completed_date: string
     *     }>
     * }>
     */

    public function getMemberTasks():array{
        $result = [];

        $query = $this->conn->query('
        SELECT t.*, tc.category_name, completor.id AS completor_id, completor.username AS completor_username, author.username AS author_name FROM task t
        INNER JOIN user completor ON t.completorUser = completor.id
        INNER JOIN user author ON t.author_id = author.id
        INNER JOIN task_category tc ON t.category = tc.id');

        while ($row = $query->fetch_assoc()) {
            $user_id = $row['completor_id'];
            if (!isset($result[$user_id])) {
                $sumPoints = $row['difficulty']*3;

                $result[$user_id] = [
                    'userId' => $user_id,
                    'username' => $row['completor_username'],
                    'sumPoints' => $sumPoints,
                    'tasks' => []
                ];
            }

            $result[$user_id]['tasks'][] = [
                'taskId' => (int)$row['id'],
                'taskName' => $row['name'],
                'taskDescription' => $row['description'],
                'taskDifficulty' => (int)$row['difficulty'],
                'category' => $row['category_name'],
                'added_date' => $row['added_date'],
                'author_name' => $row['author_name'],
                'completed_date' => $row['completed_date']
            ];
        }

        return $result;
    }
}