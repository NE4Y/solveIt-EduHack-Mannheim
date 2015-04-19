<?php
/* -----------------------------------------
Loginklasse
Author: Steffen Lindner
-------------------------------------------- */


class Login {
    private $id;
    private $pw;
    private $status = true;
    
    public function __construct($id, $pw) {
        $this->id = $id;
        $this->pw = $pw;
    }
    
    /* ---------------------------------------
    Verfiziert den Login und erstellt die Sessions
    --------------------------------------------- */
    public function verifyLogin() {
        if (empty($this->id) || (!(RegEx::checkUsername($this->id)) && !(RegEx::checkEmail($this->id)))) {
            Error::$error[] = "Bitte gib einen Usernamen oder eine Email an.";
            $this->status   = false;
        }
        
        if (empty($this->pw) || !(RegEx::checkPW($this->pw))) {
            Error::$error[] = "Bitte gib ein Passwort ein.";
            $this->status   = false;
        }
        
        return $this->status;
    }
   
    /* -------------------------------------------
    Loggt den User ein
    ------------------------------------------------ */
    public function doLogin() {
        if (DBHandler::getDB()->num_rows("SELECT id FROM account WHERE (username = ? OR email = ?) AND password = ? LIMIT 1", array(
            $this->id, $this->id,
            sha1($this->pw)
        )) == 1) {
			
            $data = DBHandler::getDB()->fetch_assoc("SELECT id, username, createTime, email FROM account WHERE (username = ? OR email = ?)", array(
                $this->id, 
                $this->id
            ));	
		
			
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id']  = $data['id'];
			$_SESSION['email'] = $data['email'];
            $_SESSION['createTime'] = $data['createTime'];
			
            return true;
			
			
        } else {
            Error::$error[] = "Login nicht erfolgreich. Überprüfe deine Logindaten.";
            return false;
        }
    }
}