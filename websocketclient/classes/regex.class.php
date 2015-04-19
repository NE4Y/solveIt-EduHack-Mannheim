<?php
/* ----------------------------------------------
Regex Klasse zur Überprüfung von Zeichenketten
Author: Steffen Lindner
Made for ConKreter
------------------------------------------------ */



class RegEx {
    /* ---------------------------------------------
    Prüft ob der String eine Email ist 
    ------------------------------------------------ */
    public static function checkEmail($string) {
        if (preg_match("/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4}$/", $string)) {
            return true;
        } else {
			Error::$error[] = "Bitte gib eine valide Emailaddresse an.";
            return false;
        }
    }
    
    /* -------------------------------------------
    Prüft ob der String ein Integer ist
    ---------------------------------------------- */
    public static function checkInt($string) {
        if (preg_match("/^[0-9]+$/", $string)) {
            return true;
        } else {
            return false;
        }
    }
    
    /* ------------------------------------------
    Prüft das Passwort
    ----------------------------------------------*/
    public static function checkPW($string) {
		global $language;
        if (preg_match("/^[a-zA-Z0-9]+$/", $string)) {
            return true;
        } else {
			Error::$error[] = "Das Passwort erfüllt nicht die Vorraussetzungen.";
            return false;
        }
    }
    
    /* ---------------------------------------------
    Prüft ob der String nur aus Buchstaben besteht
    ------------------------------------------------- */
    public static function checkString($string) {
        if (preg_match("/^[a-zA-Z]+$/", $string)) {
            return true;
        } else {
            return false;
        }
    }
    
    /* -------------------------------------------
    Prüft den Benutzernamen
    ---------------------------------------------*/
    public static function checkSiteName($string) {
        if (preg_match("/^[a-zA-Z0-9äÄüÜöÖ_]+$/", $string)) {
            return true;
        } else {
            return false;
        }
    }
    
    /* -----------------------------------------
    Prüft den Seitennamen
    -------------------------------------------- */
    public static function checkUsername($string) {
		global $language;
        if (preg_match("/^[a-zA-Z0-9]+$/", $string)) {
            return true;
        } else {
			Error::$error[] = "Dieser Nutzername erfüllt nicht die Vorraussetzungen.";
            return false;
        }
    }
    
	/* -----------------------------------------
	Prüft den Host
	--------------------------------------------- */
	public static function checkhost($string) {
    	if (preg_match("/^[a-zA-Z.\/0-9%]+$/", $string)) {
            return true;
        }
		else {
        	return false;
        }
	}
}
?>
