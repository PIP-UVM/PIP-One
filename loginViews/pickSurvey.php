<?php
  $userSurveys = $newUser->getSurveys();

  // Get the most recently published survey name
  $query = "SELECT MAX(survey_name) AS survey_name ";
  $query .= "FROM Survey ";
  $query .= "WHERE NOT survey_name = 'Unpublished'";

  $results = $thisDatabase->select($query);
  $newestPublished = $results[0]['survey_name'];

  // Get the newest survey completed by the user
  $newestSurvey = max($userSurveys);
  $newestName = $newestSurvey['survey_name'];

  print "<div class='pickSurvey user_content'>";

  print "<h1>Incomplete:</h1>";

  foreach ($userSurveys as $survey) {
    $record_id = sha1($survey['record_id']);

    print "<a href='?e=1&r=" . $record_id . "&ea=" . $email . "' class='currentSurveys'>" . $survey['survey_name'] . "</a>";
  }

  if ($newestPublished != $newestName) {
    print "<a href='?e=1&r=" . $survey['record_id'] . "&ea=" . $email . "' class='currentSurveys newestSurvey'>Start the newest survey.</a>";
  }

  print "</div>"; // End .pickSurvey
?>
