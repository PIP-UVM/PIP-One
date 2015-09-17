<?php
	/* Query for getting sections */
	$query = "SELECT section_name, section_id FROM Sections";
	$results = $thisDatabase->select($query);

	$sectionNames = $results;

	// Get the section to add the question to from the URL
	$currentSection = htmlentities($_GET['s']);

	if (isset($_POST['btnSubmit'])) {
		$questionText = htmlentities($_POST['tarQuestionText']);
		$questionSection = htmlentities($_POST['optSection']);
		$questionGrading = htmlentities($_POST['tarQuestionGrading']);
		$questionExample = htmlentities($_POST['tarQuestionExample']);

		$questionInfo = array();

		$questionInfo[] = $questionText;
		$questionInfo[] = $questionSection;
		$questionInfo[] = $questionGrading;
		$questionInfo[] = $questionExample;

		$choiceOne = htmlentities($_POST['txtChoiceOne']);
		$choiceTwo = htmlentities($_POST['txtChoiceTwo']);
		$choiceThree = htmlentities($_POST['txtChoiceThree']);
		$choiceFour = htmlentities($_POST['txtChoiceFour']);
		$choiceFive = htmlentities($_POST['txtChoiceFive']);

		$choicesInfo = array();

		$choicesInfo[] = $choiceOne;
		$choicesInfo[] = $choiceTwo;
		$choicesInfo[] = $choiceThree;
		$choicesInfo[] = $choiceFour;
		$choicesInfo[] = $choiceFive;


		$newQuestion = new Question($thisDatabase, $questionInfo, $choicesInfo);
		$success = $newQuestion->add();

		if ($success) {
			print "<div class='qConfirm'>The question has been added to the survey</div>";
		}
	}
?>

<form method=post class='addQuestion'>
	<fieldset class='selectSection'>
		<label for='optSection'>Question Section</label>
		<select name='optSection'>
			<?php
				foreach ($sectionNames as $section) {
					$sectionName = $section['section_name'];
					$sectionNameShow = ucfirst(strtolower($section['section_name']));

					// If the current section is equal to the section name we are on, have it pre-selected
					if ($sectionName == $currentSection) {
						echo "<option value='" . $sectionName . "' selected>" . $sectionNameShow . "</option>";
					} else {
						echo "<option value='" . $sectionName . "'>" . $sectionNameShow . "</option>";
					}
				}
			?>
			<option value='other'>Other</option>
		</select>
	</fieldset> <!-- Ends selectSection -->

	<fieldset class='hide newSection'>
		<label for='txtSectionId'>New Section Id</label>
		<input type='text' name='txtSectionId' placeholder='New Section ID'>

		<label for='txtSectionId'>New Section Name</label>
		<input type='text' name='txtSectionName' placeholder='New Section Name'>

		<label for='txtSectionDesc'>New Section Description</label>
		<input type='text' name='txtSectionDesc' placeholder='In our practice...' value='In our practice...'>
	</fieldset> <!-- Ends newSection -->

	<fieldset class='newText'>
		<label for='tarQuestionText'>Question Text</label>
		<textarea name='tarQuestionText' required></textarea>
	</fieldset> <!-- Ends questionText -->

	<fieldset class='newChoices'>
		<label for='txtChoiceOne'>Choices</label>
		<div class='inputStyle'><input type='radio' class='styleRadio'><input type='text' name='txtChoiceOne' placeholder='Choice One' required></div>
		<div class='inputStyle'><input type='radio' class='styleRadio'><input type='text' name='txtChoiceTwo' placeholder='Choice Two' required></div>
		<div class='inputStyle'><input type='radio' class='styleRadio'><input type='text' name='txtChoiceThree' placeholder='Choice Three' required></div>
		<div class='inputStyle'><input type='radio' class='styleRadio'><input type='text' name='txtChoiceFour' placeholder='Choice Four' required></div>
		<div class='inputStyle'><input type='radio' class='styleRadio'><input type='text' name='txtChoiceFive' placeholder='Choice Five' required></div>
	</fieldset>

	<fieldset class='newGrading'>
		<label for='tarQuestionGrading'>Grading Criteria</label>
		<textarea name='tarQuestionGrading' required></textarea>
	</fieldset> <!-- Ends questionGrading -->

	<fieldset class='newExample'>
		<label for='tarQuestionExample'>Question Example</label>
		<textarea name='tarQuestionExample' required></textarea>
	</fieldset> <!-- Ends questionExample -->

	<fieldset class='buttons'>
		<input type='submit' name='btnSubmit' value='Add Question'>
	</fieldset>
</form>
