<?php

  $email = htmlentities($_GET['ea']);
  $record_id = htmlentities($_GET['r']);

  $code = safeRand();

  session_start();

  $_SESSION['code'] = $code;

  $sent = sendCode($code, $email);

?>

<form method=post class='codeBox'>

  <fieldset class='txtCode'>
    <p>Please enter the code you received in your email to start the survey</p>
    <input type='text' name='txtCode' placeholder='Code' class='validate_txt_input'>
    <input type='submit' name='btnSubmitCode' value=''>
  </fieldset>

</form>
