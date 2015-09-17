<?php
  print "<div class='bubbleNav'>";

  // Count how many Sections there are
  $query = "SELECT section_id ";
  $query .= "FROM Sections";

  $results = $thisDatabase->select($query);

  // Get the user record for the current user
  if (isset($_GET['ur'])) {
    $userRecord = htmlentities($_GET['ur']);
    $getArray['ur'] = $userRecord;
  }

  // Get the current section
  $currentSection = htmlentities($_GET['section']);

  foreach ($results as $section) {
    $getArray['section'] = $section['section_id'];

    $newURL = buildURL($getArray);

    if ($section['section_id'] == $currentSection) {
      print "<a href='" . $newURL . "' class='bubbleLink bubbleActive'></a>";
    } else {
      print "<a href='" . $newURL . "' class='bubbleLink'></a>";
    }
  }

  print "</div>"; // Ends bubbleNav
?>
