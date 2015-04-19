<?php
/* -----------------------------------------
 Question class
 Author: Tobias Dorra
 -------------------------------------------- */

class Question implements JsonSerializable{

	//---- private --------------------------------------------------------------------------------------
	
	private $question = "";
	private $id;
	private $author;
    private $status;
	
	private function __construct($db_result){
		$this->id = $db_result["id"];
		$this->question = $db_result["question"];
        $this->author = $db_result["author"];
        $this->status = $db_result["status"];
	}
    
    
	
	//---- public ---------------------------------------------------------------------------------------
	
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
	public function getQuestion()
	{
		return $this->question;
	}
	
	/**
	 * @param string $question
	 */
	public function setQuestion($question)
	{
		$this->question = $question;
	}
	
	public function isSolved()
	{
		return $this->status == 1;
	}
	
	/**
	 * Saves the changes to the Database
	 */
	public function save()
	{
		DBHandler::getDB()->query("UPDATE questions SET question = ? WHERE id = ?", array($this->question, $this->id));
	}
	
	/**
	 * Associates a hashtag with the question.
	 * @param Hashtag $hashtag
	 */
	public function addHashtag(Hashtag $hashtag)
	{
		DBHandler::getDB()->query("INSERT INTO questions_hashtags (question, hashtag) VALUES (?, ?)", array($this->id, $hashtag->getId()));
	}
	
	/**
	 * Removes the given hashtag from the question. 
	 * @param Hashtag $hashtag
	 */
	public function removeHashtag(Hashtag $hashtag)
	{
		DBHandler::getDB()->query("DELETE FROM questions_hashtags WHERE question = ? AND hashtag = ?", array($this->id, $hashtag->getId()));
	}

	/**
	 * @return int
	 */
	public function getNrOfHashtags()
	{
		return DBHandler::getDB()->num_rows("SELECT * FROM hashtags WHERE id IN (SELECT hashtag FROM questions_hashtags WHERE question = ?)", array($this->id));
	}
	
	/**
	 * @return int
	 */
	public function getAuthor()
	{
		return $this->author;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function jsonSerialize() {
		return array(
				"question" => $this->question,
				"id" => $this->id,
                "author" => $this->author,
                "status" => $this->status
		);
	}
	
	//---- public static --------------------------------------------------------------------------------
	
	/**
	 * Returns the Question with the given ID
	 * If no such person exists, die() is called.
	 * @param int $id
	 * @return Question
	 */
	public static function findOneById($id) {
		$result = DbHandler::getDb()->fetch_assoc("SELECT * FROM questions WHERE id = ?", array($id));
		if($result === false) {
			die();
		}
		else {
			return new Question($result);
		}		
	}
	
	/**
	 * Returns an array containing all questions that are assosiciated with a specific hashtag.
	 * @param Hashtag $hashtag
	 * @param int $limit
	 * @param int $offset
	 * @return array:Question
	 */
	public static function findManyByHashtag(Hashtag $hashtag, $limit = false, $offset = 0)
	{
		if($limit === false)
		{
			$db_result = DBHandler::getDB()->fetch_all("SELECT * FROM questions WHERE id IN (SELECT question FROM questions_hashtags WHERE hashtag = ?)", array($hashtag->getId()));
		}
		else 
		{
			$db_result = DBHandler::getDB()->fetch_all("SELECT * FROM questions WHERE id IN (SELECT question FROM questions_hashtags WHERE hashtag = ? LIMIT ? OFFSET ?)", array($hashtag->getId(), $limit, $offset));
		}
		$result = array();
		foreach ($db_result as $entry) {
			$result[] = new Question($entry);
		}
		return $result;
	}
	
	/**
	 * Returns a (properly ranked) list of search results (just soved ones)
	 * @param array:string $keywords
	 */
	public static function findManyByKeywords($keywords)
	{
		// tags
		$hashtags = array();
		foreach ($keywords as $keyword) {
			$levenshtein_hashtags = Hashtag::findManyByLevenshtein($keyword);
			$hashtags = array_merge($hashtags, $levenshtein_hashtags);
		}
	
		// questions with these tags
		$questions = array();
		foreach ($hashtags as $hashtag)
		{
			$hashtag_questions = Question::findManyByHashtag($hashtag);
			foreach ($hashtag_questions as $hashtag_question) {
				if(isset($questions[$hashtag_question->getId()]))
				{
					$questions[$hashtag_question->getId()]["count"]++;
				}
				else
				{
					$questions[$hashtag_question->getId()] = array("count" => 1, "question" => $hashtag_question);
				}
			}
		}
	
		// calculate the ranking = nr_of_matching_tags / nr_of_all_tags
		foreach ($questions as &$question) {
			$question["ranking"] = $question["count"] / $question["question"]->getNrOfHashtags();
		}
	
		// sort for count/nr_tags
		usort($questions, function ($a, $b){
			if($a["ranking"] == $b["ranking"])
			{
				return 0;
			}
			return ($a["ranking"] > $b["ranking"])? -1 : 1;
		});
				
		// filter for questions with public solutions
		$result = array();
		foreach ($questions as $question) {
				$result[] = $question["question"];
		}

		//
		return $result;
	}
	
	/**
	 * Creates a new Question.
	 * @param string $question The question to be asked.
	 * @return Question
	 */
	public static function create($question) {
		DBHandler::getDB()->query("INSERT INTO questions (question) VALUES (?)", array($question));
		$new_entry = DBHandler::getDB()->fetch_assoc("SELECT * FROM questions WHERE id = LAST_INSERT_ID()");
		if($new_entry === false) {
			die();
		} else {
			return new Question($new_entry);
		}
	}
    
    // get question data by id
    public static function getQuestionData($id) {
        $data = DBHandler::getDB()->fetch_assoc("SELECT * FROM questions WHERE id=? LIMIT 1", array($id));
        
        return new Question($data);
    }
    
    public static function getHashtags($id) {
        $data = DBHandler::getDB()->fetch_all("SELECT hashtag FROM hashtags WHERE id IN (SELECT hashtag FROM questions_hashtags WHERE question = ?)", array($id));
        
        return $data;
    }
}
