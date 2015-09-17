	<?php
		// Include the head information
		include "components/top.php";

		// Include the nav
		include "components/nav.php";

		// Include the database information for the admin info
		include "databaseTops/databaseAdmin.php";
		if (isset($_POST['btnSubmitCode'])) {
			session_start();

			$enteredCode = htmlentities($_POST['txtCode']);
			$correctCode = htmlentities($_SESSION['code']);

			session_destroy();

			if ($enteredCode == $correctCode) {
				$record_id = htmlentities($_GET['r']);

				$data = array();
				$data[] = $record_id;

				// Check whether they've finished the survey directions
				$query = "SELECT surveydirections ";
				$query .= "FROM Responses ";
				$query .= "WHERE SHA1(record_id) = ?";

				$results = $thisDatabase->select($query, $data);

				if ($results[0]['surveydirections']) {
					// To anyone reading this in the future. I'm sorry for the next 25 lines
		      $data = array();
		      $data[] = $record_id;

		      // Select the first section of the survey
		      $query = "SELECT build_code ";
		      $query .= "FROM Survey, Responses ";
		      $query .= "WHERE Survey.survey_name = Responses.survey_name ";
		      $query .= "AND SHA1(record_id) = ?";

		      $results = $thisDatabase->select($query, $data);

		      $build_code = $results[0]['build_code'];

		      // Explode the build code and extract the first entry
		      $exploded = explode(',', $build_code);
		      $data = array();
		      $data[] = $exploded[0];

		      $query = "SELECT question_name ";
		      $query .= "FROM Questions ";
		      $query .= "WHERE question_id = ?";

		      $results = $thisDatabase->select($query, $data);

		      // Trim the question_name and set that as the URL
		      $section = rtrim($results[0]['question_name'], " 0123456789");

					$urlArray = array();
					$urlArray['ur'] = $record_id;
					$urlArray['section'] = $section;

					$urlExt = buildURL($urlArray);

					$url = "survey.php" . $urlExt;
				} else {
					$url = "prInfo.php?ur=" . $record_id;
				}

				Header ("Location: " . $url);
			} else {
				print "<p>Incorrect code entered</p>";
			}

		} elseif (isset($_POST['btnPrinfo'])) {
			// Get the email
			$email = htmlentities($_POST['email']);

			$data = array();
			$data[] = $email;

			// Select the user record with this email
			// Since there will only ever be one it won't be hard
			$query = "SELECT record_id ";
			$query .= "FROM Responses ";
			$query .= "WHERE email = ?";

			$results = $thisDatabase->select($query, $data);

			$ur = $results[0]['record_id'];

			// Update the rest of the information
			$prname = htmlentities($_POST['prname']);
			$prtype = htmlentities($_POST['prtype']);
			$prsize = htmlentities($_POST['prsize']);
			$prposition = htmlentities($_POST['prposition']);
			$prlocation = htmlentities($_POST['prlocation']);
			$prstate = htmlentities($_POST['prstate']);
			$przipcode = htmlentities($_POST['przipcode']);
			$integration_effort = htmlentities($_POST['integration_effort']);
			$prac_bhc = htmlentities($_POST['prac_bhc']);

			$data = array();
			$data[] =	$prname;
			$data[] =	$prtype;
			$data[] =	$prsize;
			$data[] =	$prposition;
			$data[] =	$prlocation;
			$data[] =	$prstate;
			$data[] =	$przipcode;
			$data[] =	$integration_effort;
			$data[] =	$prac_bhc;
			$data[] = $ur;

			$query = "UPDATE Responses ";
			$query .= "SET prname = ?, ";
			$query .= "prtype = ?, ";
			$query .= "prsize = ?, ";
			$query .= "prposition = ?, ";
			$query .= "prlocation = ?, ";
			$query .= "prstate = ?, ";
			$query .= "przipcode = ?, ";
			$query .= "integration_effort = ?, ";
			$query .= "prac_bhc = ? ";
			$query .= "WHERE record_id = ?";

			$results = $thisDatabase->update($query, $data);

			$url = array();
			$url['e'] = 1;
			$url['r'] = sha1($ur);
			$url['ea'] = $email;

			$newURL = buildURL($url);

			Header ("Location: " . $newURL);

		} elseif (isset($_GET['e'])) {
			include "loginViews/enterCode.php";
		} elseif (isset($_POST['btnSubmit'])) {
			// Sanitize
			$email = htmlentities($_POST['txtEmail']);

			// Make the user
			$newUser = new User($thisDatabase, $email);

			$userExists = $newUser->exists();

			if ($userExists) {
				include "loginViews/pickSurvey.php";
			} else {
				include "loginViews/userNotFound.php";
			}
		} else {
	?>

	<form method=post class='loginBox'>
		<h3>VIP Survey</h3>

		<fieldset class='txtEmail'>
			<input type='text' name='txtEmail' placeholder='Email' class='validate_txt_input'>
			<input type='submit' name='btnSubmit' value=''>
		</fieldset>

		<p>Want to take the survey as a guest? <a href='survey.php?section=wflow'>Click here!</a></p>

	</form>
	<?php
		}
	?>
	</body>
</html>
