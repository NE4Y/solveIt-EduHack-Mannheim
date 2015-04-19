<?php
/* -----------------------------------------
Datenbankklasse auf Grundlagen von PDO
Author: Steffen Lindner 
-------------------------------------------- */

class DB {
    private $con;
    
    public function __construct($host, $db, $user, $pw) {
        try {
            $this->con = @new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8', $user, $pw, array(
                PDO::ATTR_PERSISTENT => true,
           		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            ));
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            print $e->getMessage();
            die();
        }
        
        return $this->con;
    }
    
    /* -----------------------------------------
    Führt einen Query aus 
    -------------------------------------------- */
    public function query($sql, array $param = null) {
        try {
            $stm = $this->con->prepare($sql);
            $stm->execute($param);
        }
        catch (PDOException $e) {
            print $e->getMessage();
            die();
        }
        
        return $stm;
    }
    
    /* -----------------------------------------
    Zählt die vorhandenen Datensätze 
    ------------------------------------------- */
    public function num_rows($sql, array $param = null) {
        $stm = $this->query($sql, $param);
        return $stm->rowCount();
    }
    
    /* ------------------------------------------
    Liest einen einzelnen Datensatz aus
    -------------------------------------------- */
    public function fetch_assoc($sql, array $param = null) {
        $stm = $this->query($sql, $param);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
    
    /* ---------------------------------------------
    List mehrere Datensätze aus - Durchlauf mit foreach
    -------------------------------------------------- */
    public function fetch_all($sql, array $param = null) {
        $stm = $this->query($sql, $param);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>