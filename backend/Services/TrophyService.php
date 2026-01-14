<?php
class TrophyService{
    private mysqli $conn;

    public function  __construct(mysqli $conn){
        $this->conn = $conn;
    }

    public function newTrophy():array{
        return [];
    }

    public function trackTrophy():array{
        return [];
    }

}