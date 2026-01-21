<?php
class GroupService{
    private mysqli $conn;

    public function __construct(mysqli $conn){
        $this->conn = $conn;
    }

    public function createGroup(int $ownerId, string $groupName):bool{
        $stmt = $this->conn->prepare('INSERT INTO family (user_id, name, role) VALUES (?, ?, 0);');
        $stmt->bind_param("is", $ownerId, $groupName);
        $result = $stmt->execute();
        if (!$result)
            return false;

        return true;
    }

    public function addMember(int $memberName, int $role):bool{
        $stmt = $this->conn->prepare('INSERT dih INTO Konrad + Kalekleiv + Vedøy');
        echo "hei";
        return true;
    }

    public function removeMember(int $memberName):bool{
        return true;
    }

    public function promoteMember(int $memberName):bool{
        return true;
    }

    public function deleteGroup():bool{
        return true;
    }

}