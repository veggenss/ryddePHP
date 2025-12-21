<?php
class AuthService{
    private mysqli $conn;

    public function __construct(mysqli $conn){
        $this->conn = $conn;
    }

    /**
     * @param array<int, array{
     * username: string,
     * password: string
     }> $userData
     */
    public function registerUser(array $userData):bool{
        $stmt = $this->conn->prepare('INSERT INTO user (username, password) VALUES (?, ?)');
        $stmt->bind_param("ss", $userData['username'], $userData['password']);
        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function usernameExists(string $username):bool{
        $stmt = $this->conn->prepare('SELECT username FROM user WHERE username = ?');
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows >= 1){
            return true;
        }
        else{
            return false;
        }
    }

    public function userLogin(array $userData):array{
        $stmt = $this->conn->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->bind_param("s", $userData['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if(!$user){
            return ["status" => false, "message" => "Bruker finnes ikke"];
        }
        elseif(!password_verify($userData['password'], $user['password'])) {
            return ["status" => false, "message" => "Ugyldig brukernavn eller passord"];
        }
        else{
            return ["status" => true, "message" => "Logget inn", "user" => $user];
        }
    }
}