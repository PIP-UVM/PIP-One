<?php
  $newUser->add();

  $practiceType = array();
  $practiceType[1] = "Community Mental health Center";
  $practiceType[2] = "Community Health Center";
  $practiceType[3] = "Pediatrics";
  $practiceType[4] = "OB Gyn";
  $practiceType[5] = "Family Medicine";
  $practiceType[6] = "Internal Medicine";
  $practiceType[7] = "Other Specialty Medical Practice";

  $practiceSize = array();
  $practiceSize[1] = "Less than 3 employees";
  $practiceSize[2] = "Less than 5 employees";
  $pracitceSize[3] = "6 to 10 employees";
  $practiceSize[4] = "10+ employees";

  $practicePositon = array();
  $practicePositon[1] = "Managing Director";
  $practicePositon[2] = "Senior Behavioral Health Clinician";
  $practicePositon[3] = "Practice Manager";
  $practicePositon[4] = "Managing Physician";
  $practicePositon[5] = "Student Intern";

  $practiceLocation = array();
  $practiceLocation[1] = "Inner City";
  $practiceLocation[2] = "Urban";
  $practiceLocation[3] = "Suburban";
  $practiceLocation[4] = "Rural";
  $practiceLocation[5] = "Frontier";

  $integrationEffort = array();
  $integrationEffort[1] = "No Integration Effort";
  $integrationEffort[2] = "Planning Integration but Not Executed";
  $integrationEffort[3] = "Effort is 6 Months or Less";
  $integrationEffort[4] = "Effort is More than 6 Months and Less than 1 year";
  $integrationEffort[5] = "Effort is more than 1 year";

  $pracBhc = array();
  $pracBhc[1] = "Employed by the practice or practice organization";
  $pracBhc[2] = "Contracted with the individual";
  $pracBhc[3] = "Contracted for services with a different organization";
  $pracBhc[4] = "We do not have a behavioral health clinician in our practice";

  // Prinfo section
  print "<section class='prinfo'>";

  print "<p class='infoMessage'>It looks like you're new here. Please provide us with a little bit of information about your practice.</p>";

  print "<form method=post class='user_content'>";

  print "<label for='prname'>Practice Name: </label>";
  print "<input type=text name='prname' placeholder='Practice Name' required class='prtxt_input'>";

  print "<label for='email'>Email Address: </label>";
  print "<input type=text name='email' placeholder='Email' value = '" . $email . "' required class='prtxt_input'>";

  print "<label for='prstate'>Practice State/Territory: </label>";
  print "<select name='prstate' class='pr_select'>";

  $stateQuery = "SELECT state_name, state_id FROM States;";
  $stateResults = $thisDatabase->select($stateQuery);

  foreach ($stateResults as $state) {
    print "<option value=" . $state['state_id'] . ">" . $state['state_name'] . "</option>";
  }

  print "</select>";

  print "<label for='przipcode'>Practice Zip Code: </label>";
  print "<input type=text name='przipcode' placeholder='Zip Code' class='prtxt_input' required maxlength='5'>";

  print "<label for='prtype'>Practice Type: </label>";
  print "<select name='prtype' class='pr_select'>";

  foreach ($practiceType as $optionValue => $prtype) {
    print "<option value=" . $optionValue . ">" . $prtype . "</option>";
  }

  print "</select>";

  print "<label for='prsize'>Practice Size: </label>";
  print "<select name='prsize' class='pr_select'>";

  foreach ($practiceSize as $optionValue => $prsize) {
    print "<option value=" . $optionValue . ">" . $prsize . "</option>";
  }

  print "</select>";

  print "<label for='prposition'>Your Position in the Practice: </label>";
  print "<select name='prposition' class='pr_select'>";

  foreach ($practicePositon as $optionValue => $prposition) {
    print "<option value=" . $optionValue . ">" . $prposition . "</option>";
  }

  print "</select>";

  print "<label for='prlocation'>Practice Location: </label>";
  print "<select name='prlocation' class='pr_select'>";

  foreach ($practiceLocation as $optionValue => $prlocation) {
    print "<option value=" . $optionValue . ">" . $prlocation . "</option>";
  }

  print "</select>";

  print "<label for='integration_effort'>Length of time integration effort has been active at your practice location: </label>";
  print "<select name='integration_effort' class='pr_select'>";

  foreach ($integrationEffort as $optionValue => $integration_effort) {
    print "<option value=" . $optionValue . ">" . $integration_effort . "</option>";
  }

  print "</select>";

  print "<label for='prac_bhc'>The behavioral health clinician(s) in your practice is: </label>";
  print "<select name='prac_bhc' class='pr_select'>";

  foreach ($pracBhc as $optionValue => $prac_bhc) {
    print "<option value=" . $optionValue . ">" . $prac_bhc . "</option>";
  }

  print "</select>";

  print "<input type=submit name='btnPrinfo' value='Continue'>";

  print "</form>";
  print "</section>";
?>
