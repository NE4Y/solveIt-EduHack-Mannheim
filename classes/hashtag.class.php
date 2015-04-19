<?php
/**
 * Klasse für ein Hashtag.
 * @author Tobias
 */

class Hashtag
{
	//---- private ----------------------------------------------------------------------------
	
	private $id;
	private $hashtag;
	
	private function __construct($db_result)
	{
		$this->id = $db_result["id"];
		$this->hashtag = $db_result["hashtag"];
	}
	
	//---- public -----------------------------------------------------------------------------
	
	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getHashtag()
	{
		return $this->hashtag;
	}
	
	//---- public static ----------------------------------------------------------------------
	
	/**
	 * Finds a hashtag by its id.
	 * @param int $id
	 * @return Hashtag
	 */
	public static function findOneById($id)
	{
		$result = DbHandler::getDb()->fetch_assoc("SELECT * FROM hashtags WHERE id = ?", array($id));
		if($result === false) {
			die();
		}
		else {
			return new Hashtag($result);
		}
	}
	
	/**
	 * Finds a hashtag in the database. If there is no matching entry in the database, false is returned.
	 * @param string $hashtag
	 * @return boolean|Hashtag
	 */
	public static function findOneByHashtag($hashtag)
	{
		$result = DbHandler::getDb()->fetch_assoc("SELECT * FROM hashtags WHERE hashtag = ?", array($hashtag));
		if($result === false) {
			return false;
		}
		else {
			return new Hashtag($result);
		}
	}
	
	/**
	 * @param string $hashtag
	 * @param int $max_distance
	 * @return array:Hashtag
	 */
	public static function findManyByLevenshtein($hashtag, $max_distance = 2)
	{
		$db_result = DBHandler::getDB()->fetch_all("SELECT * FROM hashtags WHERE levenshtein(hashtag, ?) <= ?", array($hashtag, $max_distance));
		$result = array();
		foreach ($db_result as $entry) {
			$result[] = new Hashtag($entry);
		}
		return $result;
	}
	
	/**
	 * Returns an array of all hashtags that correspond to the given question.
	 * @param Question $question
	 * @return array:Hashtag
	 */
	public static function findManyByQuestion(Question $question)
	{
		$db_result = DBHandler::getDB()->fetch_all("SELECT * FROM hashtags WHERE id IN (SELECT hashtag FROM questions_hashtags WHERE question = ?)", array($question->getId()));
		$result = array();
		foreach ($db_result as $entry) {
			$result[] = new Hashtag($entry);
		}
		return $result;
	}
	
	/**
	 * @param Profile $profile
	 */
	public static function findManyByProfile(Profile $profile)
	{
		$db_result = DBHandler::getDB()->fetch_all("SELECT * FROM hashtags WHERE id IN (SELECT hashtag FROM account_hashtags WHERE account = ?)", array($profile->getId()));
		$result = array();
		foreach ($db_result as $entry) {
			$result[] = new Hashtag($entry);
		}
		return $result;
		
	}
	
	public static function findOneOrCreate($hashtag)
	{
		$tag = self::findOneByHashtag($hashtag);
		if($tag === false)
		{
			return self::create($hashtag);
		}
		else
		{
			return $tag;
		}
	}
	
	/**
	 * Creates a new Hashtag
	 * @param string $hashtag The name of the new hashtag.
	 * @return Hashtag
	 */
	public static function create($hashtag)
	{
		DBHandler::getDB()->query("INSERT INTO hashtags (hashtag) VALUES (?)", array($hashtag));
		$new_entry = DBHandler::getDB()->fetch_assoc("SELECT * FROM hashtags WHERE id = LAST_INSERT_ID()");
		if($new_entry === false) {
			die();
		} else {
			return new Hashtag($new_entry);
		}
	}
}
