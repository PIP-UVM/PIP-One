<?php
	/*
		Users are the people who use the site.
		We want them to be able to start surveys as well as continue completed ones
		Users have:
		* user_email - The Email address of the user
		* time_invited - The time the user was invited, this can be changed or removed
		* hashed_email - sha1(user_email)

		FUNCTIONS:
		* add - Add a user to the table
		* delete - Delete a user from the table
		* edit - Edit a user's email
		* check - Check if a user is in the database
		* get - Return the user's email address
	*/
	class User {
			private $email;
			private $hashed_email;
			private $record_id;

			private $thisDatabase;

			public function __construct(myDatabase $thisDatabase, $email) {
				$this->thisDatabase = $thisDatabase;
				$this->email = $email;
				$this->hashed_email = sha1($this->email);
			}

			public function add() {
				$data[] = $this->email;
				$data[] = $this->hashed_email;

				$query = "INSERT INTO Users";
				$query .= "(user_email, time_invited, hashed_email) ";
				$query .= "VALUES (?, NOW(), ?)";

				$results = $this->thisDatabase->insert($query, $data);
				$data = array();

				// Get the most recent survey, and start them with that
				$query = "SELECT MAX(survey_name) AS survey_name ";
				$query .= "FROM Survey ";
				$query .= "WHERE NOT survey_name = 'Unpublished'";

				$results = $this->thisDatabase->select($query);
				$results = $results[0];

				$data[] = $this->email;
				$data[] = $results['survey_name'];

				$query = "INSERT INTO Responses ";
				$query .= "(email, survey_name) ";
				$query .= "VALUES (?, ?)";

				$results = $this->thisDatabase->insert($query, $data);
			}
			/*
				This will totally delete the user from the database. This shouldn't be used.
				You probably want to use the response class for this
			*/

			public function delete() {
				$data[] = $this->email;

				$query = "DELETE FROM Users ";
				$query .= "WHERE email = ?";

				$results = $this->thisDatabase->insert($query, $data);
				$data = array();
			}

			public function exists() {
				$data[] = $this->email;

				$query = "SELECT user_email ";
				$query .= "FROM Users ";
				$query .= "WHERE user_email = ?";

				$results = $this->thisDatabase->select($query, $data);
				$data = array();

				if (empty($results)) {
					return False;
				} else {
					return True;
				}
			}

			public function getSurveys() {
				$data[] = $this->email;

				$query = "SELECT survey_name, record_id ";
				$query .= "FROM Responses ";
				$query .= "WHERE email = ? ";
				$query .= "ORDER BY record_id";

				$results = $this->thisDatabase->select($query, $data);
				$data = array();

				return $results;
			}

			public function __toString() {
				$string = "The email of the user is" . $this->email . ".";
				return $string;
			}

			public function getEmail() {
				return $this->email;
			}

			public function setResponse($record_id) {
				$this->record_id = $record_id;
			}
		}
?>
