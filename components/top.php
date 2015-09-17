<!DOCTYPE html>
<html>

<head>
	<title>Vermont Integration Profile</title>
	<meta charset="utf-8">
	<meta name="author" content="Simon Anguish">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="description" content="A Website for Vermont Integration Profile">

    <link href="/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <link href="/stylesheets/print.css" media="print" rel="stylesheet" type="text/css" />
	<!--[if IE]>
	    <link href="/stylesheets/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
	<![endif]-->

	<!--[if lt IE 9]>
	    <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
	<![endif]-->

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

	<?php
		$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

    	require_once('lib/security.php');
		include "lib/validate-functions.php";
		include "lib/mail-message.php";

        $domain = "https://";
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS']) {
                $domain = "https://";
            }
        }

        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF-8");

        $domain .= $server;

        $path_parts = pathinfo($phpSelf);

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// include all libraries

		$yourURL = $domain . $phpSelf;

		$fileName = $path_parts['filename'];

        include "Functions/functions.php";
        include "Classes/Question.php";
    		include "Classes/User.php";
				include "Classes/Survey.php";
        include "js/script.js";

		// Include functions to connect to and query the database
		require_once("bin/myDatabase.php");
//
    ?>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<?php
	$data = array();
	$getArray = array();
	// Start the body tag which automatically has an id that matches the filename
	print "<body id='" . $fileName . "'>";
?>
