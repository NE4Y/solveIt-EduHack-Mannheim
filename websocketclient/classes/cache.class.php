<?php
/* ---------------------
Cache System 
----------------------- */

class Cache {
	 private static $dir = "cache";
	 
    /* -----------------------------
    Prüft ob der Cache existiert
    -------------------------------- */	 
	 public static function cacheAvailable($name, $time = 0) {
		if(file_exists(self::$dir."/".$name.".cfs")) {
			if($time == 0) {
				return true;
			}
		    else {
			  if(filemtime(self::$dir . "/" . $name . ".cfs") > (time() - $time)) {
                return true;
              } 
			  else {
                return false;
              }
			} // if time == 0
		 }
		 else {
			 return false;			 
		 } // if exists file
	 }
	 
	/* -----------------------------------
    Schreibt den Cache
    ------------------------------------- */
    public static function cacheIt($datei, $inhalt) {
        if ($handle = fopen(self::$dir . "/" . $datei . ".cfs", "w")) {
            if (fwrite($handle, base64_encode($inhalt))) {
                fclose($handle);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	  /* ---------------------------------
    List den Cache aus
    ----------------------------------- */
    public static function readFile($datei) {
        $infos   = null;
        $myDatei = file(self::$dir . "/" . $datei . ".cfs");
        
        foreach ($myDatei as $e) {
            $infos .= $e;
        }
        
        return base64_decode($infos);
    }
	
	 /* -------------------------------
    Gibt das Erstelldatum des Caches zurück
    --------------------------------- */
    public static function getCreateTime($datei) {        
        return date("H:i d.m.Y", filemtime(self::$dir . "/" . $datei . ".cfs"));
    }
	
    /* --------------------------------
    Löscht einen bestimmten Cache
    ----------------------------------- */
    public static function deleteCache($name) {
        if (file_exists(self::$dir . '/' . $name . '.cfs')) {
            unlink(self::$dir . '/' . $name . '.cfs');
        }
    }
	
	/* ---------------------------
	Gibt den Cache Pfad zurück
	------------------------------ */
	public static function getFilePath($name) {
		return self::$dir.'/'.$name.'.cfs';
	}
	 
}

