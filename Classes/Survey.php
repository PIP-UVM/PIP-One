<?php
  class Survey {
    private $thisDatabase;
    private $buildCode;
    private $surveyName;

    public function __Construct(myDatabase $thisDatabase) {
      $this->thisDatabase = $thisDatabase;
    }

    /*
      !!! PLEASE CLEAN THIS !!!
      -Simon
    */
    public function publish() {

      // Set previous surveyCode as active
  		$query = "SELECT survey_id, build_code ";
      $query .= "FROM Survey ";
      $query .= "WHERE survey_id = (";
      $query .= "SELECT MAX(survey_id) ";
      $query .= "FROM Survey";
      $query .= ")";
  		$results = $this->thisDatabase->select($query);

  		if (empty($results)) {
  			$query = "SELECT question_id ";
        $query .= "FROM Questions";
  			$results = $this->thisDatabase->select($query);

  			$surveyBuildCode = array();

  			foreach ($results as $questionToBuild) {
  				$buildQuestionId = htmlentities($questionToBuild['question_id']);
  				$surveyBuildCode[] = $buildQuestionId;
  			}

  			$surveyBuildCode = implode(",", $surveyBuildCode);

  		} else {
  			$surveyBuildCode = htmlentities($results[0]['build_code']);

  			$this->surveyName = "VIP" . htmlentities($results[0]['survey_id']) . ".0";
  			$data[] = $this->surveyName;

  			$query = "UPDATE Survey ";
        $query .= "SET survey_name = ? ";
        $query .= "WHERE survey_name = 'Unpublished'";

  			$results = $this->thisDatabase->update($query, $data);
  			$data = array();
  		}

  		$data[] = $surveyBuildCode;

  		$query = "INSERT INTO Survey ";
      $query .= "(build_code, survey_name, date_published) ";
      $query .= "VALUES (?, 'Unpublished', NOW())";

  		$results = $this->thisDatabase->insert($query, $data);
  		$data = array();

      $this->addAll();
    }

    private function addAll() {
      $query = "SELECT user_email ";
      $query .= "FROM Users";

      $results = $this->thisDatabase->select($query);

      foreach ($results as $user) {
        $data[] = $this->surveyName;
        $data[] = $user['user_email'];

        $query = "INSERT INTO Responses ";
        $query .= "(survey_name, email) ";
        $query .= "VALUES (?, ?)";

        $results = $this->thisDatabase->insert($query, $data);
        $data = array();

        // Email them
        $to = $user['user_email'];
        $cc = "";
        $bcc = "";
        $from = "vip@uvm.edu";
        $subject = "You have a new survey!";
        $message = "<p>You have a new survey to complete.</p>";
        $message .= "<p>Please sign in <a href='vip.w3.uvm.edu/DEVELOP/login.php'>here</a> to complete it.</p>";

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
      }
    }

    public function __toString() {
      return $buildCode;
    }
  }
?>
