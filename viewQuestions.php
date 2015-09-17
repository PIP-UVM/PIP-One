<?php
	/*
		Deletes the selected question.
		NOT WORKING
	*/
	if (isset($_POST['btnDelete'])) {
		$deleteText = htmlentities($_POST['btnDelete']);

		$questionToDelete = ltrim($deleteText, "Delete ");

		$selectUnpublishedQuery = "SELECT build_code FROM Survey WHERE survey_name = 'Unpublished'";
		$selectUnpublishedResults = $thisDatabase->select($selectUnpublishedQuery);

		$buildCode = htmlentities($selectUnpublishedResults[0]['build_code']);

		$buildCodeArray = explode(",", $buildCode);

		$data[] = $questionToDelete;

		// Get the question_id of the item to be deleted
		$getQuestionIdQuery = "SELECT question_id FROM Questions WHERE question_name = ?";
		$getQuestionIdResults = $thisDatabase->select($getQuestionIdQuery, $data);
		unset($data);

		$questionId = htmlentities($getQuestionIdResults[0]['question_id']);

		// Get the index of the item to be deleted and delete it
		$buildCodeArrayKeys = array_keys($buildCodeArray, $questionId);
		unset($buildCodeArray[$buildCodeArrayKeys[0]]);

		// Rebuild the build code
		$buildCode .= implode(",", $buildCodeArray);

		$data[] = $buildCode;

		//Update the database
		$updateBuildCodeQuery = "UPDATE Survey SET build_code = ? WHERE survey_name = 'Unpublished'";
		$updateBuildCodeResults = $thisDatabase->update($updateBuildCodeQuery, $buildCode);
	}

	/*
		Publishes the survey.
		Working as intended.
	*/
	if (isset($_POST['btnPublishSurvey'])) {
		$newSurvey = new Survey($thisDatabase);

		$newSurvey->publish();
	}

	// If there are modifiers on the questions to show
	// Found if $_GET['s'] or $_GET['u'] are set.
	if (isset($_GET['u'])) {
		$user_record = htmlentities($_GET['u']);
	}

	if (isset($_GET['s'])) {
		$survey_id = htmlentities($_GET['s']);
	}

	$data = array();

	// Get the build code for the unpublished Survey
	$query = "SELECT build_code ";
	$query .= "FROM Survey ";

	// Get the survey of the specific user record
	if (isset($_GET['u'])) {
		$data[] = $user_record;

		$query .= "WHERE survey_name = (";
		$query .= "SELECT survey_name ";
		$query .= "FROM Responses ";
		$query .= "WHERE record_id = ?)";
	} elseif (isset($_GET['s'])) {
		$data[] = $survey_id;

		$query .= "WHERE survey_id = ?";
	} else {
		$query .= "WHERE survey_name = 'Unpublished'";
	}

	$results = $thisDatabase->select($query, $data);

	$buildCode = htmlentities($results[0]['build_code']);

	// Get the questions matching with the choices
	$query = "SELECT question_text, question_section, question_grading, question_example, choice_zero, choice_one, choice_two, choice_three, choice_four, Questions.question_name ";
	$query .= "FROM Choices, Questions ";
	$query .= "WHERE question_id = choice_id ";
	$query .= "AND question_id IN (" . $buildCode . ")";

	$results = $thisDatabase->select($query);

	$allQuestions = $results;

	foreach ($allQuestions as $question) {
		$questionName = htmlentities($question['question_name']);

		if (isset($_GET['u'])) {
			$data = array();
			// $data[] = $questionName;
			$data[] = $user_record;

			$query = "SELECT " . $questionName . " ";
			$query .= "FROM Responses ";
			$query .= "WHERE record_id = ?";

			$results = $thisDatabase->select($query, $data);

			$userAnswer = $results[0][0];
		}

		$questionInfo = array();
		$questionInfo[] = $question['question_text'];
		$questionInfo[] = $question['question_section'];
		$questionInfo[] = $question['question_grading'];
		$questionInfo[] = $question['question_example'];

		$choiceInfo = array();
		$choiceInfo[] = $question['choice_zero'];
		$choiceInfo[] = $question['choice_one'];
		$choiceInfo[] = $question['choice_two'];
		$choiceInfo[] = $question['choice_three'];
		$choiceInfo[] = $question['choice_four'];

		$currentQuestion = new Question($thisDatabase, $questionInfo, $choiceInfo, $questionName);

		print "<article class='question'>";

		$text = $currentQuestion->getText();
		$section = $currentQuestion->getSection();
		$grading = $currentQuestion->getGrading();
		$example = $currentQuestion->getExample();
		$name = $currentQuestion->getName();

		if (!isset($_GET['u']) && !isset($_GET['s'])) {
			print "<form method=post class='delete_question'>";
			print "<input type=submit name=btnDelete value='Delete " . $name . "'>";
			print "</form>";

			print "<a href='?edit&q=" . $name . "'><img class='gearIcon' src='imgs/settings-48.png' title='edit'></a>";
			print "<div class='divPush'></div>";
		}

		print "<p>" . $text . "</p>";

		print "<div class='choiceOptions'>";

		$currentChoices = $currentQuestion->getChoices();

		if (isset($_GET['u'])) {
			showChoices($name, $currentChoices, true, $userAnswer);
		} else {
			showChoices($name, $currentChoices);
		}

		print "</div>";

		print "<p>" . $grading . "</p>";

		print "<p>" . $example . "</p>";

		print "</article>";
	}

	if (!isset($_GET['u']) && !isset($_GET['s'])) {
		print "<form method=post id='publishForm'>";
		print "<input type=submit name='btnPublishSurvey' value='Publish Survey'>";
		print "</form>";
	}

	print "</section>";
?>
