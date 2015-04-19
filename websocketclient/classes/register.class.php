<?php
/* -----------------------------------------
Registerklasse
Author: Steffen Lindner

-------------------------------------------- */


class Register {
    private $id;
	private $pw;
    private $rePW;
	private $email;
    private $status;
  
	
	public function __construct($id, $pw, $repw, $email) {
		$this->id = $id;
		$this->pw = $pw;
		$this->email = $email;
        $this->rePW = $repw;
        $this->status = true;
    }
	
	/* -------------------------------------
	Verifiziert die angegebenen Daten
	-----------------------------------------*/
	public function verifyRegister() {
		if(empty($this->id) || !(RegEx::checkUsername($this->id))) {
            Error::$error[] = "Bitte gib einen Usernamen an.";
			$this->status = false;
		}
		
		if(empty($this->pw) || !(RegEx::checkPW($this->pw))) {
            Error::$error[] = "Bitte gib ein Passwort an.";
			$this->status = false;
		}
		
		if(empty($this->email) || !(RegEx::checkEmail($this->email))) {
            Error::$error[] = "Bitte gib eine Email an.";
			$this->status = false;
		}
        
        if($this->pw != $this->rePW || empty($this->rePW)) {
            Error::$error[] = "Bitte wiederhole dein Passwort.";
            $this->status = false;
        }
		
		return $this->status;
	}
	
		
	/* ---------------------------------------------
	Prüft ob der User existiert
	-----------------------------------------------*/
	public function existsUser() {		
		if(DBHandler::getDB()->num_rows("SELECT id FROM account WHERE username = ? LIMIT 1", array($this->id))) {
			Error::$error[] = "Ein Nutzer mit diesem Nutzernamen existiert bereits.";
			return true;
		}
		else {
			return false;
		}
	}
	
	
	/* ----------------------------------------------
	Prüft ob es bereits einen User mit dieser Email gibt
	--------------------------------------------------- */
	public function existsEmail() {
		if(DBHandler::getDB()->num_rows("SELECT id FROM account WHERE email = ?  LIMIT 1", array($this->email))) {
			Error::$error[] = "Ein Nutzer mit dieser Email existiert bereits.";
			return true;
		}
		else {
			return false;
		}
	}
	
	/* ------------------------------------------------
	Registriert den User
	----------------------------------------------------- */
    public function register() {		
		$time = time();
		$this->pw = sha1($this->pw);
		
		if(DBHandler::getDB()->query("INSERT INTO account (username, password, email, createTime) VALUES (?, ?, ?, ?)", array($this->id, $this->pw, $this->email, $time))) {
            return true;
	   }
        else {
            Error::$error[] = "Es ist ein Fehler aufgetreten.";
            return false;
        }
	
	
     }
}

?>