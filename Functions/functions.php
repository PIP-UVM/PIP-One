<?php
    function showChoices($questionName, $choices, $disabled = true, $userAnswer = null) {
        // God
        for ($choice = 0; $choice <= 4; $choice++) {

          if ($userAnswer == $choice && !(is_null($userAnswer))) {
            $checked = true;
          } else {
            $checked = false;
          }

          print "<label class = 'response'>";
          if (!$disabled) {
            if ($checked) {
              print "<input type = radio value = '" . $choice . "' name = '" . $questionName . "' checked>";
            } else {
              print "<input type = radio value = '" . $choice . "' name = '" . $questionName . "'>";
            }

          } else {
            if ($checked) {
              print "<input type = radio value = '" . $choice . "' name= ' " . $questionName . "' disabled checked>";
            } else {
              print "<input type = radio value = '" . $choice . "' name = '" . $questionName . "' disabled>";
            }
          }

          switch($choice) {
              case 0:
                  $whichChoice = "choice_zero";
                  break;
              case 1:
                  $whichChoice = "choice_one";
                  break;
              case 2:
                  $whichChoice = "choice_two";
                  break;
              case 3:
                  $whichChoice = "choice_three";
                  break;
              case 4:
                  $whichChoice = "choice_four";
                  break;
          }

          print $choices[$whichChoice] . "</label>";
        }
    }

    function sendCode($secureCode, $email) {
      $to = $email;
      $cc = "";
      $bcc = "";
      $from = "vip@uvm.edu";
      $subject = "Your Survey Code is " . $secureCode;
      $message = "<p>Your Vermont Integration Profile survey code is:</p>";
      $message .= "<b>" . $secureCode . "</b>";

      return sendMail($to, $cc, $bcc, $from, $subject, $message);
    }

    function sendInvitation($record_id, $email) {
      $surveyURL = "http://vip.w3.uvm.edu/DEVELOP/survey.php?ur=" . $record_id . "&section=collabagree";

			$to = $email;
			$cc = "";
			$bcc = "";
			$from = "vip@uvm.edu";
			$subject = "VIP Survey Link";
			$message = "<p>This is the link to your Vermont Integration Profile survey.</p>";
			$message .= "<p>You may bookmark this, refer to this email, or you will be resent an email upon entering your email on the login page.</p>";
			$message .= "<a href='" . $surveyURL . "'>" . $surveyURL . "</a>";

      return sendMail($to, $cc, $bcc, $from, $subject, $message);
    }

    // Outdate: Try and find them all and replace with htmlentities

    function sanitize ($var) {
	    return htmlentities($var, ENT_QUOTES, "UTF-8");
    }

    function buildURL($array) {
      $newURL = "?";

      foreach ($array as $key => $val) {
        $newURL .= $key . "=" . $val;
        $newURL .= "&";
      }

      $trimmed = rtrim($newURL, "&");

      return $trimmed;
    }

    function safeRand() {
      $returnString = "";

      for ($x = 0; $x < 6; $x++) {
        $returnString .= mt_rand(0,9);
      }

      return $returnString;
    }
?>
