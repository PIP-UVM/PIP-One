<?php
  // Include the top
  include "components/top.php";

  // Include the database files for writing only
  include "databaseTops/databaseWriter.php";

  if (isset($_POST['btnSubmit'])) {
    $userRecord = htmlentities($_GET['ur']);

    // Update the tables
    $data = array();
    $data[] = $userRecord;

    $query = "UPDATE Responses ";
    $query .= "SET collabagree = 1, ";
    $query .= "surveydirections = 1 ";
    $query .= "WHERE SHA1(record_id) = ?";

    $results = $thisDatabase->update($query, $data);

    // To anyone reading this in the future. I'm sorry for the next 25 lines
    // Get the first section
    $data = array();
    $data[] = $userRecord;

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

    // Build the URL
    $urlArray = array();
    $urlArray['ur'] = $userRecord;
    $urlArray['section'] = $section;

    $urlExt = buildURL($urlArray);

    $url = "survey.php" . $urlExt;

    print $url;

    Header ("Location: " . $url);
  } else {
    // Collabagree Section
    print "<section class='confirmTake user_content'>";
  	print "<p>Please review the attached Collaboration Agreement and once you have read and understood it, ";
    print "please let us know if you are willing to participate in our study below.</p>";
  	print "<a href='VIP Collaboration Agreement updated.pdf' class='img_link'><img src='png/glyphicons-72-book.png'></a>";
    print "<a href='VIP Collaboration Agreement updated.pdf' class='img_link_caption'>VIP Collaboration Agreement updated.pdf</a>";
  	print "<form method=post>";

  	print "<label><input type=checkbox name='chkCollabagree' required>I am willing to participate</label>";


  	print "<p>To create the profile for your organization, please check that you have reviewed our terms and conditions. Then, please review the statements in each of the 8 dimensions.  Then select the statement in each dimension that best reflects your organization. After each dimension you will be scored on your level of integration. These scores will be factored into a graph that has a column for each of the dimensions across the bottom, and a summary graph reflecting 0-100%. The resulting graph is a visual of your organization's level of integration on our scale.</p>";

    print "<label><input type=checkbox name='chkSurveydirections' required>I have read and understood these directions.</label>";

  	print "<p>Definition of Behavioral Health for this Measure: ";
    print "'Primary care and behavioral health clinicians, ";
    print "working together with patients, ";
    print "using a systematic approach to mental health and substance abuse conditions, ";
    print "health behavior change, life crises, and stress- related physical symptoms'. ";
    print "(condensed from the 'Lexicon for Behavioral Health and Primary Care Integration' by CJ Peek - 2013)</p>";

  	print "<input type=submit name='btnSubmit' value='Begin'>";
  	print "</form>";
  	print "</section>";
  }
?>
