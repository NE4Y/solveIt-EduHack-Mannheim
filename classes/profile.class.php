<?php
class Profile implements JsonSerializable
{
	//---- private -------------------------------------------------------------------------------
	private $id;
	private $email;
	private $username;
	private $creationDate;
	
	private function __construct($db_entry)
	{
		$this->id = $db_entry["id"];
		$this->email = $db_entry["email"];
		$this->username = $db_entry["username"];	
		$this->creationDate = $db_entry["createTime"];
		
	}
	
	
	public function getId()
	{
		return $this->id;	
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getRegistrationDate()
	{
		return $this->creationDate;
	}
	
	public function getPictureUrl()
	{
		$hash = md5(strtolower(trim($this->email)));
		return "http://www.gravatar.com/avatar/" . $hash . "?default=retro&s=250";
	}
	
	public function getNrOfHashtags()
	{
		return DBHandler::getDB()->num_rows("SELECT * FROM account_hashtags WHERE account = ?", array($this->id));
	}
	
	public function jsonSerialize() {
		return array(
				"id" => $this->id,
				"name" => utf8_encode($this->username),
				"pic" => $this->getPictureUrl(),
				"description" => "Lorem Ipsum Dolor Sit Amet..."
		);
	}
	
	/**
	 * Associates a hashtag with the question.
	 * @param Hashtag $hashtag
	 */
	public function addCompetence(Hashtag $hashtag)
	{
		DBHandler::getDB()->query("INSERT INTO account_hashtags (account, hashtag) VALUES (?, ?)", array($this->id, $hashtag->getId()));
	}
	
	/**
	 * Removes the given hashtag from the question.
	 * @param Hashtag $hashtag
	 */
	public function removeCompetence(Hashtag $hashtag)
	{
		DBHandler::getDB()->query("DELETE FROM account_hashtags WHERE account = ? AND hashtag = ?", array($this->id, $hashtag->getId()));
	}
	
	
	//---- public --------------------------------------------------------------------------------
	
	//---- public static -------------------------------------------------------------------------
	
	/**
	 * @param int $id
	 * @return Profile
	 */
	public static function findOneById($id)
	{
		$result = DbHandler::getDb()->fetch_assoc("SELECT * FROM account WHERE id = ?", array($id));
		if($result === false) {
			die();
		}
		else {
			return new Profile($result);
		}
	}
	
	/**
	 * @return Profile
	 */
	public static function findOneByCurrentSession()
	{
		if(isset($_SESSION["user_id"]))
		{
			return self::findOneById($_SESSION["user_id"]);
		}
		else
		{
			die();
		}
	}
	
	/**
	 * @param Hashtag $hashtag
	 * @return array:Profile
	 */
	public static function findManyByCompetence(Hashtag $hashtag)
	{
		$db_result = DBHandler::getDB()->fetch_all("SELECT * FROM account WHERE id IN (SELECT account FROM account_hashtags WHERE hashtag = ?)", array($hashtag->getId()));
		$result = array();
		foreach ($db_result as $entry) {
			$result[] = new Profile($entry);
		}
		return $result;
	}
	
	public static function findManyByKeywords($keywords)
	{
		// tags
		$hashtags = array();
		foreach ($keywords as $keyword) {
			$levenshtein_hashtags = Hashtag::findManyByLevenshtein($keyword);
			$hashtags = array_merge($hashtags, $levenshtein_hashtags);
		}
		
		// profiles with these tags
		$profiles = array();
		foreach ($hashtags as $hashtag)
		{
			$hashtag_profiles = Profile::findManyByCompetence($hashtag);
			foreach ($hashtag_profiles as $hashtag_profile) {
				if(isset($profiles[$hashtag_profile->getId()]))
				{
					$profiles[$hashtag_profile->getId()]["count"]++;
				}
				else
				{
					$profiles[$hashtag_profile->getId()] = array("count" => 1, "profile" => $hashtag_profile);
				}
			}
		}
		
		// calculate the ranking = nr_of_matching_tags / nr_of_all_tags
		foreach ($profiles as &$profile) {
			$profile["ranking"] = $profile["count"] / $profile["profile"]->getNrOfHashtags();
		}
		
		// sort for count/nr_tags
		usort($profiles, function ($a, $b){
			if($a["ranking"] == $b["ranking"])
			{
				return 0;
			}
			return ($a["ranking"] > $b["ranking"])? -1 : 1;
		});
		
		// summarize
		$result = array();
		foreach ($profiles as $profile) {
			$result[] = $profile["profile"];
		}
		
		//
		return $result;
	}
}
