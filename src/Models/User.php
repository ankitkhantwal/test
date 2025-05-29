<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): array {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->execute($data);
        $data['id'] = $this->db->lastInsertId();
        
        return $data;
    }

    public function read(int $id): ?array {
        $sql = "SELECT id, name, email, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(['id' => $id]);
    }

    public function getAll(): array {
        $sql = "SELECT id, name, email, created_at FROM users";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll();
    }
} 