<?php

class BlogPost {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Create a new post
    public function createPost($title, $content, $authorId) {
        $sql = "INSERT INTO posts (title, content, author_id) 
                VALUES (:title, :content, :author_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':author_id' => $authorId
        ]);
    }

    // Get single post by ID
    public function getPostById($id) {
        $sql = "SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update existing post
    public function updatePost($postId, $title, $content, $authorId) {
        $sql = "UPDATE posts 
                SET title = :title, content = :content 
                WHERE id = :id AND author_id = :author_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':id' => $postId,
            ':author_id' => $authorId
        ]);
    }

    // Delete a post
    public function deletePost($postId, $authorId) {
        $sql = "DELETE FROM posts 
                WHERE id = :id AND author_id = :author_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $postId,
            ':author_id' => $authorId
        ]);
    }

    // Get all posts (for admin/public listing)
    public function getAllPosts($limit = null) {
        $sql = "SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                ORDER BY p.created_at DESC";
        
        if($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get posts by specific author
    public function getPostsByAuthor($authorId) {
        $sql = "SELECT * FROM posts 
                WHERE author_id = :author_id 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':author_id' => $authorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get recent posts (for homepage/widget)
    public function getRecentPosts($limit = 5) {
        $sql = "SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search posts by keyword
    public function searchPosts($keyword) {
        $sql = "SELECT p.*, u.username 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.title LIKE :keyword 
                OR p.content LIKE :keyword 
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>