<?php
	/*
		This class will hold information for the questions.
		It will contain the question information

		Questions have:
		* question_id - Auto-generated key for questions
		* question_name - Ex: wflow1
		* question_section - Section the question is, full name all caps
		* question_grading - Grading scheme
		* question_example - Example of response

		FUNCTIONS:
		* add - Adds the question to the questions table, and will create a choices object with the information
		* delete - Gets the current build code, removes the questionId from that array, and rebuilds the build code
		* edit - Changes the current information.
		* get - Get all the information. One get for each of the variables
	*/
	class Question {
		// Initialize variables for this question
		private $questionName;
		private $questionText;
		private $questionSection;
		private $gradingScheme;
		private $questionExample;

		private $questionId;

		private $choiceOne;
		private $choiceTwo;
		private $choiceThree;
		private $choiceFour;
		private $choiceFive;

		private $thisDatabase;
		/*
			The constructor should take in:
			* Question Info:
			-- question text
			-- question section
			-- grading scheme
			-- question example
			-- key for finding choices

			* Choice Info:
			-- choice one
			-- choice two
			-- choice three
			-- choice four
			-- choice five

			This should be presented as an array in this order
			It will automatically generate the question name
		*/
		public function __construct(myDatabase $thisDatabase, $questionInfo = array("", "", "", ""), $choiceInfo = array("", "", "", "", ""), $questionName = null) {
			$this->thisDatabase = $thisDatabase;

			$this->questionText = $questionInfo[0];
			$this->questionSection = $questionInfo[1];
			$this->gradingScheme = $questionInfo[2];
			$this->questionExample = $questionInfo[3];

			if (isset($questionName)) {
				$this->questionName = $questionName;
			} else {
				$this->questionName = $this->generateName();
			}

			$this->choiceOne = $choiceInfo[0];
			$this->choiceTwo = $choiceInfo[1];
			$this->choiceThree = $choiceInfo[2];
			$this->choiceFour = $choiceInfo[3];
			$this->choiceFive = $choiceInfo[4];

		}

		/*
			Add a question to the table.
			Returns boolean whether it was successfull.
		*/
		public function add() {
			$data[] = $this->questionName;
			$data[] = $this->questionText;
			$data[] = $this->questionSection;
			$data[] = $this->gradingScheme;
			$data[] = $this->questionExample;

			$query = "INSERT INTO Questions";
			$query .= "(question_name, question_text, question_section, question_grading, question_example) ";
			$query .= "VALUES (?, ?, ?, ?, ?)";
			$results = $this->thisDatabase->insert($query, $data);
			$data = array();

			$lastInsertId = $this->thisDatabase->lastInsert();
			$this->questionId = $lastInsertId;

			$data[] = $this->questionName;
			$data[] = $this->choiceOne;
			$data[] = $this->choiceTwo;
			$data[] = $this->choiceThree;
			$data[] = $this->choiceFour;
			$data[] = $this->choiceFive;

			$query = "INSERT INTO Choices";
			$query .= "(question_name, choice_zero, choice_one, choice_two, choice_three, choice_four) ";
			$query .= "VALUES (?, ?, ?, ?, ?, ?)";
			$results = $this->thisDatabase->insert($query, $data);
			$data = array();

			// Add to build code

			return $results;
		}
		/*
			Edit the question and update the table
			Takes in arguments:
			* newText - Question Text
			* newGrading - Grading Scheme
			* newExample - Question Example
			* newChoices - Array of Choices

			Returns boolean whether it was successfull.
		*/
		public function edit($newText, $newGrading, $newExample, $newChoices) {
			$this->questionText = $newText;
			$this->gradingScheme = $newGrading;
			$this->questionExample = $newExample;

			$this->choiceOne = $newChoices[0];
			$this->choiceTwo = $newChoices[1];
			$this->choiceThree = $newChoices[2];
			$this->choiceFour = $newChoices[3];
			$this->choiceFive = $newChoices[4];

			$data[] = $this->questionText;
			$data[] = $this->gradingScheme;
			$data[] = $this->questionExample;

			$data[] = $this->questionName;

			$query = "UPDATE Questions ";
			$query .= "SET question_text = ?, question_grading = ?, question_example = ? ";
			$query .= "WHERE question_name = ?";
			$results = $this->thisDatabase->update($query, $data);
			$data = array();

			$data[] = $this->choiceOne;
			$data[] = $this->choiceTwo;
			$data[] = $this->choiceThree;
			$data[] = $this->choiceFour;
			$data[] = $this->choiceFive;

			$data[] = $this->questionName;

			$query = "UPDATE Choices ";
			$query .= "SET choice_zero = ?, choice_one = ?, choice_two = ?, choice_three = ?, choice_four = ? ";
			$query .= "WHERE question_name = ?";
			$results = $this->thisDatabase->update($query, $data);
			$data = array();

			return $results;
		}

		/*
			Generate the question name automatically
			Name should be formatted section_id + (count(question_id) + 1)
		*/
		private function generateName() {
			$section = $this->questionSection;
			$data[] = $section;

			$query = "SELECT section_id, COUNT(question_id) AS count ";
			$query .= "FROM Questions, Sections ";
			$query .= "WHERE section_name = question_section ";
			$query .= "AND section_name = ?";

			$results = $this->thisDatabase->select($query, $data);
			$data = array();

			$sectionId = $results[0]['section_id'];
			$questionCount = $results[0]['count'] + 1;

			return $sectionId . $questionCount;
		}

		public function __toString() {
			$questionInfo = array();

			$questionInfo[] = $this->questionName;
			$questionInfo[] = $this->questionText;
			$questionInfo[] = $this->gradingScheme;
			$questionInfo[] = $this->questionExample;

			$returnString = implode(",", $questionInfo);

			return $returnString;
		}

		public function getName() {
			return $this->questionName;
		}

		public function getText() {
			return $this->questionText;
		}

		public function getSection() {
			return $this->questionSection;
		}

		public function getGrading() {
			return $this->gradingScheme;
		}

		public function getExample() {
			return $this->questionExample;
		}

		public function getChoices() {
			$choices = array();

			$choices['choice_zero'] = $this->choiceOne;
			$choices['choice_one'] = $this->choiceTwo;
			$choices['choice_two'] = $this->choiceThree;
			$choices['choice_three'] = $this->choiceFour;
			$choices['choice_four'] = $this->choiceFive;

			return $choices;
		}
	}
?>
