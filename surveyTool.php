	<?php
		//
		include "components/top.php";
		
		// Include the top for the admin database commands
		include "databaseTops/databaseAdmin.php";
		
		// include the nav
		include "components/nav.php";
		
	?>
	<!-- Admin Tabs Navigation for navigating the admin view -->
	
	<section id='adminTabs'>
		<a href='?viewUsers'>View User</a>
		<a href='?viewQuestions'>Edit Current Survey</a>
		<a href='?viewSurveys'>View Surveys</a>
	</section>
		
	<?php
		
		// If ?newQ is in the URL, show the newQ content
		if (isset($_GET['newQ'])) {		
			include ("newQ.php");
		}
		
		// If ?edit is in the URL, show the edit content
		elseif(isset($_GET['edit'])) {
			include ("edit.php");
		}
		
		// If ?viewQuestions is in the URL, show the viewQuestions content
		elseif (isset($_GET['viewQuestions']) || empty($_GET)) {
			include ("viewQuestions.php");
		}
		
		// If ?viewSurveys is in the URL, show the viewSurveys content
		elseif (isset($_GET['viewSurveys']) || empty($_GET)) {
			include ("viewSurveys.php");
		}
		
		// If ?viewUsers is in the URL, show the viewUsers content
		elseif (isset($_GET['viewUsers'])) {
			include ("viewUsers.php");
		}
	?>
	</body>	
</html>

<script>
	// Javascript to make the newSection content disappear if we don't need it
	$('select[name=sltQuestionSection]').change(function () {
		
		// If the select option is set to "other" fade in
		// Otherwise, fade out
	    if ($(this).val() == 'other') {
	        $( "#newSection" ).fadeIn("fast");
	    } else {
	        $( "#newSection" ).fadeOut("fast");
	    }
	});
</script>