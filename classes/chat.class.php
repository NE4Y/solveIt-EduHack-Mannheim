<?php

class Chat {
    
	public function getTogetherChats(Profile $p1, Profile $p2)
	{
		$chats = DBHandler::getDB()->fetch_all("SELECT * FROM chat WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)", array($p1->getId(), $p2->getId(), $p2->getId(), $p1->getId()));
		return $chats;
	}
	
    public function getChats() {
        $chats = DBHandler::getDB()->fetch_all("SELECT * FROM chat WHERE (user1 = ? OR user2 = ?) AND status != ?", array($_SESSION['user_id'], $_SESSION['user_id'], 1));
        
        return $chats;        
    }
    
    public function getUserNameById($id) {
        $username = DBHandler::getDB()->fetch_assoc("SELECT username FROM account WHERE id=? LIMIT 1", array($id));
        
        return $username['username'];
        
    }
    
     public static function getUserNameId($id) {
        $username = DBHandler::getDB()->fetch_assoc("SELECT username FROM account WHERE id=? LIMIT 1", array($id));
        
        return $username['username'];
        
    }
    
    public function getChatById($id) {
        $chats = DBHandler::getDB()->fetch_assoc("SELECT * FROM chat WHERE id=? LIMIT 1", array($id));
        return $chats;
    }
    
    public function getChatMessages($id) {
        $chats = DBHandler::getDB()->fetch_all("SELECT * FROM chat_messages WHERE chat_id=?", array($id));
        
        return $chats;
    }
    
    private function getChatTitle($id) {
        $d = DBHandler::getDB()->fetch_assoc("SELECT question FROM questions WHERE id=? LIMIT 1", array($id));
        return $d['question'];
    }
    
    public function createChat($qID, $toID) {
        $title = $this->getChatTitle($qID);
        $chats = DBHandler::getDB()->query("INSERT INTO chat (user1, user2, chat_title) VALUES (?,?,?)", array($_SESSION['user_id'], $toID, $title));
        
        $id = DBHandler::getDB()->fetch_assoc("SELECT id FROM chat ORDER BY id DESC LIMIT 1");
        $id = $id['id'];
        DBHandler::getDB()->query("UPDATE chat SET question=? WHERE id=? LIMIT 1", array($qID, $id));
        $ch = DBHandler::getDB()->query("INSERT INTO chat_messages (author, msg, chat_id, timestamp) VALUES (?,?,?,?)", array($toID, $title, $id, time()));
        
        return $id;
    }
    
    public function solveQuestion($q) {
        DBHandler::getDB()->query("UPDATE questions SET status=? WHERE id=(SELECT question FROM chat WHERE id=?) LIMIT 1", array(1, $q));
	if(DBHandler::getDB()->query("UPDATE chat SET status=? WHERE id=?", array(1, $q))) {
		return true;
	}
    }	
}
?>
