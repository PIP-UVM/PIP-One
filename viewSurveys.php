<?php
	// Query to get the data for surveys
	$query = "SELECT survey_name, survey_id, date_published ";
	$query .= "FROM Survey ";
	$query .= "ORDER BY survey_id desc";
	$results = $thisDatabase->select($query);

	// This table is used to display all of the surveys
	print "<table class='surveyTable'>";
	print "<tr>";
	print "<th>Version</th>";
	print "<th>Date Published</th>";
	print "</tr>";

	// Show all information for each of the Surveys
	foreach ($results as $survey) {
		print "<tr>";

		$surveyId = htmlentities($survey['survey_id']);
		$surveyName = htmlentities($survey['survey_name']);
		$datePublished = htmlentities($survey['date_published']);

		// PRint out the Survey version with a link to view that survey
		print "<td>";
		print "<a href='?viewQuestions&s=" . $surveyId . "'>" . $surveyName . "</a>";
		print "</td>";

		// Print out the date that Survey was published
		print "<td>" . date("m-d-y", strtotime($datePublished)) . "</td>";

		print "</tr>";
	} // End foreach loop
	print "</table>";

?>
