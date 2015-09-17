	<?php

		// Include the top
		include "components/top.php";

		// Include the database files for writing only
		include "databaseTops/databaseWriter.php";

		$sectionId = htmlentities($_GET['section']);

		if (isset($_GET['ur'])) {
			$userRecord = htmlentities($_GET['ur']);
		}
		$data = array();

		$data[] = $sectionId;

		// Search for the current section and the information for that section
		$query = "SELECT section_name, section_description, section_order ";
		$query .= "FROM Sections ";
		$query .= "WHERE section_id = ?";
		$results = $thisDatabase->select($query, $data);

		if (empty($results)) {
			$sectionName = "Complete";
			$sectionDescription = "Thank you for completing the survey";
			$sectionOrder = "";
		} else {
			$sectionName = $results[0]['section_name'];
			$sectionDescription = $results[0]['section_description'];
			$sectionOrder = $results[0]['section_order'];
		}

		// Section for Buttons
		// ===================

		if (isset($_POST['btnComplete'])) {
			$comparison = htmlentities($_POST['prac_bhc']);
			$specialty = htmlentities($_POST['specialty']);

			$data = array();
			$data[] = $comparison;
			$data[] = $specialty;
			$data[] = $userRecord;

			$query = "UPDATE Responses ";
			$query .= "SET comparison = ?, ";
			$query .= "specialty = ? ";
			$query .= "WHERE SHA1(record_id) = ?";

			$results = $thisDatabase->update($query, $data);

			// Select all questions and get the rows where the values are not null
			$data = array();
			$data[] = $userRecord;

			$query = "SELECT * ";
			$query .= "FROM Responses ";
			$query .= "WHERE SHA1(record_id) = ?";

			$results = $thisDatabase->select($query, $data);

			// This goes through all of the responses and gets the results of the questions
			$dataRecord = array();

			foreach ($results as $questions) {
				foreach ($questions as $questionName => $questionValue) {
					if (!is_numeric($questionName) && !is_null($questionValue)) {
						$dataRecord[$questionName] = $questionValue;
					}
				}
			}

			$fileExt = ".csv";
			$myFileName = "data/responses";

			$filename = $myFileName . $fileExt;

			$file = fopen($filename, 'a');

			fputcsv($file, $dataRecord);

			fclose($file);

			header ("Location: thankyou.php");
		}

		if (isset($_POST['btnSave'])) {
			foreach ($_POST as $questionId => $value) {

				// Does not include the btnSave _POST value in saving
				if ($questionId != "btnSave") {
					$questionValue = htmlentities($value);

					$data = array();
					// $data[] = $questionId;
					$data[] = $questionValue;
					$data[] = $userRecord;

					$query = "UPDATE Responses ";
					$query .= "SET " . htmlentities($questionId) . " = ? ";
					$query .= "WHERE SHA1(record_id) = ?";
					$results = $thisDatabase->update($query, $data);

					$sectionPercentage = $sectionPercentage + $questionValue;
				}
			}
		}

		elseif(isset($_POST['btnReset'])) {
			header ("Location: " . $_SERVER['REQUEST_URI']);
		}

		elseif(isset($_POST['btnNext'])) {
			$sectionPercentage = 0;

			print_r($_POST);

			foreach ($_POST as $questionId => $value) {
				if ($questionId != "btnNext") {
					$questionValue = htmlentities($value);

					$data = array();
					$data[] = $questionValue;
					$data[] = $userRecord;

					$query = "UPDATE Responses ";
					$query .= "SET " . htmlentities($questionId) . " = ? ";
					$query .= "WHERE SHA1(record_id) = ?";
					$results = $thisDatabase->update($query, $data);

					$sectionPercentage = $sectionPercentage + $questionValue;
				}
			}

			$data = array();
			$data[] = $sectionId . "%";

			$query = "SELECT COUNT(*) ";
			$query .= "FROM Questions ";
			$query .= "WHERE question_name ";
			$query .= "LIKE ?";

			$results = $thisDatabase->select($query, $data);

			$questionCount = $results[0][0];

			$sectionPercentage = round(100 * ($sectionPercentage / ($questionCount * 4)));

			$data = array();

			if ($sectionId != 'ident') {
				$data[] = $sectionId . "%percent%";
			} else {
				$data[] = "case%percent%";
			}

			$query = "SHOW COLUMNS FROM Responses ";
			$query .= "WHERE Field ";
			$query .= "LIKE ?";

			$results = $thisDatabase->select($query, $data);

			$field = $results[0]['Field'];

			$data = array();
			// $data[] = $field;
			$data[] = $sectionPercentage;
			$data[] = $userRecord;

			$query = "UPDATE Responses ";
			$query .= "SET " . htmlentities($field) . " = ? ";
			$query .= "WHERE SHA1(record_id) = ?";

			$results = $thisDatabase->update($query, $data);

			$data = array();

			$newSectionOrder = $sectionOrder + 1;

			$data[] = $newSectionOrder;

			$query = "SELECT section_id ";
			$query .= "FROM Sections ";
			$query .= "WHERE section_order = ?";

			$results = $thisDatabase->select($query, $data);

			$urlArray = array();

			$urlArray['ur'] = $userRecord;

			if (empty($results)) {
					$urlArray['section'] = "complete";
			} else {
					$nextSectionId = $results[0]['section_id'];
					$urlArray['section'] = $nextSectionId;
			}

			$url = buildURL($urlArray);

			header ("Location: " . $url);
		}

		elseif (isset($_POST['btnLast'])) {
			foreach ($_POST as $questionId => $value) {
				if ($questionId != "btnLast") {
					$questionValue = htmlentities($value);

					$data = array();

					// $data[] = $questionId;
					$data[] = $questionValue;
					$data[] = $userRecord;

					$query = "UPDATE Responses ";
					$query .= "SET " . htmlentities($questionId) . " = ? ";
					$query .= "WHERE SHA1(record_id) = ?";

					$results = $thisDatabase->update($query, $data);
				}
			}

			$data = array();

			$newSectionOrder = $sectionOrder - 1;

			$data[] = $newSectionOrder;

			$query = "SELECT section_id ";
			$query .= "FROM Sections ";
			$query .= "WHERE section_order = ?";

			$results = $thisDatabase->select($query, $data);

			$urlArray = array();

			$urlArray['ur'] = $userRecord;

			$nextSectionId = $results[0]['section_id'];

			if ($nextSectionId != NULL) {
				$urlArray['section'] = $nextSectionId;
			} else {
				$urlArray['section'] = $sectionId;
			}

			$url = buildURL($urlArray);

			header ("Location: " . $url);
		}

		// Section for Finishing Survey
		// ============================

		if ($_GET['section'] == "complete") {
			print "<section class='complete user_content'>";

			print "<div class='sectionInfo'>";
			print "<h3>You're almost done!</h3>";
			print "<h4>We need you to answer a few more questions for us</h4>";
			print "</div>";

			print "<p>If you would like to have a separate analysis of your data in comparison to a specific subset, please indicate the particular subset. An additional cost may be assessed for custom reports.</p>";

			print "<form method=post>";

			print "<select name='prac_bhc' class='pr_select'>";
			print "<option value=1>Community Mental Health Center</option>";
			print "<option value=2>Community Health Center</option>";
			print "<option value=3>Pediatrics</option>";
			print "<option value=4>OB Gyn</option>";
			print "<option value=5>Family Medicine</option>";
			print "<option value=6>Internal Medicine</option>";
			print "<option value=7>Other Specialty Medical Practice</option>";
			print "</select>";

			print "<p>If you chose 'Other Specialty Medical Practice' please specify what type of practice you to which you would like your practice compared.</p>";
			print "<input type=text name='specialty' placeholder='Specialty' class='prtxt_input'>";

			print "<input type=submit name='btnComplete' value='Finish'>";

			print "</form>";
			print "</section>";
		}

		// Section for Questions
		// =====================

		else {

			// If the user record is set, use that. Otherwise, use the most recent one.
			if (isset($_GET['ur'])) {
				$ur = htmlentities($_GET['ur']);

				$data = array();
				$data[] = $ur;

				$query = "SELECT build_code ";
				$query .= "FROM Survey, Responses ";
				$query .= "WHERE Survey.survey_name = Responses.survey_name ";
				$query .= "AND SHA1(record_id) = ?";

				$results = $thisDatabase->select($query, $data);
				$data = array();

			} else {
				$query = "SELECT MAX(build_code) as build_code ";
				$query .= "FROM Survey ";
				$query .= "WHERE survey_name != 'Unpublished' ";
				$query .= "ORDER BY survey_id";

				$results = $thisDatabase->select($query);
				$data = array();

			}

			$buildCode = $results[0]['build_code'];

			// Get users survey
			$data[] = $sectionId . "%";

			$query = "SELECT question_text, Questions.question_name, question_grading, question_example, ";
			$query .= "choice_zero, choice_one, choice_two, choice_three, choice_four ";
			$query .= "FROM Questions, Choices ";
			$query .= "WHERE Questions.question_name = Choices.question_name ";
			$query .= "AND Questions.question_name LIKE ? ";
			$query .= "AND question_id IN (" . $buildCode . ")";

			$qResults = $thisDatabase->select($query, $data);

			$data = array();

			include "components/bubbleNav.php";

			print "<section class='questionSection'>";

			print "<div class='sectionInfo'>";
			print "<h3>" . $sectionName . "</h3>";
			print "<h4>" . $sectionDescription . "</h4>";
			print "</div>";

			print "<form method=post name='frmQuestion'>";

			// Select all questions and get the rows where the values are not null
			if (isset($_GET['ur'])) {
				$data[] = $userRecord;

				$query = "SELECT * ";
				$query .= "FROM Responses ";
				$query .= "WHERE SHA1(record_id) = ?";

				$results = $thisDatabase->select($query, $data);

				$userResponses = array();

				foreach ($results[0] as $questionName => $response) {
					if (!is_numeric($questionName) && !is_null($response)) {
						$userResponses[$questionName] = $response;
					}
				}
			}

			foreach ($qResults as $question) {
				$questionName = $question['question_name'];

				$choices = array();
				$choices['choice_zero'] = $question['choice_zero'];
				$choices['choice_one'] = $question['choice_one'];
				$choices['choice_two'] = $question['choice_two'];
				$choices['choice_three'] = $question['choice_three'];
				$choices['choice_four'] = $question['choice_four'];

				$disabled = false;

				$userAnswer = $userResponses[$questionName];

				print "<div class = 'question'>";
				print "<div class = 'questionTextBox'>";
				print "<p class='questionText'>" . $question['question_text'] . "</p>";
				print "</div>";
				print "<div class = 'choiceOptions'>";

				showChoices($questionName, $choices, $disabled, $userAnswer);

				print "</div>";
				print "<div class = 'divPush'></div>";

				print "<div class='questionGrading gradingText'>";
				print "<p>" . $question['question_grading'] . "</p>";
				print "</div>";

				print "<div class='questionExample exampleText'>";
				print "<p>" . $question['question_example'] . "</p>";
				print "</div>";

				print "<div class = 'divPush'></div>";

				print "</div>";
			}

			include "components/bubbleNav.php";

			print "<div class='questionButtons'>";
			print "<input type=submit name='btnLast' value='Previous Section'>";
			print "<input type=submit name='btnReset' value='Reset'>";
			print "<input type=submit name='btnSave' value='Save'>";
			print "<input type=submit name='btnNext' value='Next Section'>";
			print "</div>";

			print "</form>";

			print "</section>";
		}
	?>
	</body>
</html>
