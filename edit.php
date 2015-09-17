<?php
	$questionName = htmlentities($_GET['q']);

	$data = array();

	$data[] = $questionName;

	// Query for the question info for the question with this name
	$query = "SELECT question_text, question_section, question_grading, question_example ";
	$query .= "FROM Questions ";
	$query .= "WHERE question_name = ?";

	$results = $thisDatabase->select($query, $data);
	$data = array();

	$questionText = $results[0]['question_text'];
	$questionSection = $results[0]['question_section'];
	$questionGrading = $results[0]['question_grading'];
	$questionExample = $results[0]['question_example'];

	$questionInfo = array();

	$questionInfo[] = $questionText;
	$questionInfo[] = $questionSection;
	$questionInfo[] = $questionGrading;
	$questionInfo[] = $questionExample;

	$data[] = $questionName;

	// Query for the choices
	$query = "SELECT choice_zero, choice_one, choice_two, choice_three, choice_four ";
	$query .= "FROM Choices ";
	$query .= "WHERE question_name = ?";
	$results = $thisDatabase->select($query, $data);
	$data = array();

	$choiceOne = $results[0]['choice_zero'];
	$choiceTwo = $results[0]['choice_one'];
	$choiceThree = $results[0]['choice_two'];
	$choiceFour = $results[0]['choice_three'];
	$choiceFive = $results[0]['choice_four'];

	$choiceInfo = array();

	$choiceInfo[] = $choiceOne;
	$choiceInfo[] = $choiceTwo;
	$choiceInfo[] = $choiceThree;
	$choiceInfo[] = $choiceFour;
	$choiceInfo[] = $choiceFive;

	$currentQuestion = new Question($thisDatabase, $questionInfo, $choiceInfo, $questionName);

	if (isset($_POST['btnSubmit'])) {
		$newText = htmlentities($_POST['tarNewText']);
		$newGrading = htmlentities($_POST['tarNewGrading']);
		$newExample = htmlentities($_POST['tarNewExample']);

		$newChoices = array();

		$newChoices[] = htmlentities($_POST['txtChoiceOne']);
		$newChoices[] = htmlentities($_POST['txtChoiceTwo']);
		$newChoices[] = htmlentities($_POST['txtChoiceThree']);
		$newChoices[] = htmlentities($_POST['txtChoiceFour']);
		$newChoices[] = htmlentities($_POST['txtChoiceFive']);

		$success = $currentQuestion->edit($newText, $newGrading, $newExample, $newChoices);

		if ($sucess) {
			echo "<div class='qConfirm'>Congrats! It worked!</div>";
		}
	}
?>

<form method=post class='editQuestion'>
	<fieldset class='newText'>
		<label for='tarNewText'>Question Text</label>
		<?php
			echo "<textarea name='tarNewText'>" . $currentQuestion->getText() . "</textarea>";
		?>
	</fieldset>

	<fieldset class='newChoices'>
		<label for='txtChoiceOne'>Choices</label>
		<?php
			$choices = $currentQuestion->getChoices();

			echo "<div class='inputStyle'><input type='radio' class='styleRadio' disabled><input type='text' name='txtChoiceOne' value='" . $choices[0] . "'></div>";
			echo "<div class='inputStyle'><input type='radio' class='styleRadio' disabled><input type='text' name='txtChoiceTwo' value='" . $choices[1] . "'></div>";
			echo "<div class='inputStyle'><input type='radio' class='styleRadio' disabled><input type='text' name='txtChoiceThree' value='" . $choices[2] . "'></div>";
			echo "<div class='inputStyle'><input type='radio' class='styleRadio' disabled><input type='text' name='txtChoiceFour' value='" . $choices[3] . "'></div>";
			echo "<div class='inputStyle'><input type='radio' class='styleRadio' disabled><input type='text' name='txtChoiceFive' value='" . $choices[4] . "'></div>";
		?>
	</fieldset>

	<fieldset class='newGrading'>
		<label for='tarNewGrading'>Grading Scheme</label>
		<?php
			echo "<textarea name='tarNewGrading'>" . $currentQuestion->getGrading() . "</textarea>";
		?>
	</fieldset>

	<fieldset class='newExample'>
		<label for='tarNewExample'>Example</label>
		<?php
			echo "<textarea name='tarNewExample'>" . $currentQuestion->getExample() . "</textarea>";
		?>
	</fieldset>

	<fieldset class='buttons'>
		<input type='submit' name='btnSubmit' value='Edit Question'>
	</fieldset>
</form>
