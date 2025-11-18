<?php
require_once 'database.php';
class chatController{
    static function getAllChats(int $idUser){
        $conn = Database::getConnection();
        $sql = "SELECT * FROM chats WHERE id_user = ?";
        
    }
}
?>