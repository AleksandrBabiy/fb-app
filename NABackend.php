<?php

	/***************************************************************************************
	 * NABackend
	 * 
	 * In order to use this file, please include it on your 'index.php' file:
	 *
	 *		include_once 'NABackend.php';
	 *
	 * Import the given database and 
	 * change the following four (4) lines to match your database settings
	 *
	 ***************************************************************************************/

	/**
	 * Database server
	 */
	$DB_SERVER   = 'localhost';
	
	/**
	 * Database username
	 */ 
	$DB_USERNAME = '';
	
	/**
	 * Database password
	 */ 
	$DB_PASSWORD = '';
	
	/**
	 * Database name
	 */ 
	$DB_DATABASE = 'test';

	/***************************************************************************************
	 * 										API 										   *
	 ***************************************************************************************
	 * 
	 * 	Get all accounts: 					?function=get&data=all
	 * 
	 *  Get single account: 				?function=get&data={id}
	 * 
	 *  Insert account: 					?function=insert  			// Passing JSON data through POST (field name: data)
	 * 																	// { "data" : {"email" : "info@netaffinity.com", "title" : "This is a title"} }
	 * 
	 * 	Update account:						?function=update&data={id}	// Passing JSON data through POST (field name: data)
	 * 																	// { "data" : {"email" : "info@netaffinity.com", "title" : "This is a title"} }
	 * 
	 *  Delete account:						?function=delete&data={id}
	 */

	/***************************************************************************************
	 ***************************************************************************************
	 *                      DO NOT CHANGE THE CODE BELOW THIS LINE						   *
	 *                       MAKE NOTES TO RECOMMEND CHANGES ONLY						   *
	 *************************************************************************************** 
	 ***************************************************************************************/

	$NABackend  = new NABackend($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE); 

	$params = array();

	$params[] = 'accounts';
	if (isset($_GET['function'])) $params[] = $_GET['function'];
	if (isset($_GET['data'])) $params[]     = $_GET['data'];

	if (count($params) > 1 && $params[0] === 'accounts'){
		switch ($params[1]) {
			case 'get':
				if (isset($params[2])){
					if(preg_match('/[0-9]+/i', trim($params[2]))) {
						$NABackend->getAccount((int)$params[2]);
					}else if($params[2] == "all"){
						$NABackend->getAccounts();
					}
				}
				break;
			case 'insert':
				if (isset($_POST['data']))
					$NABackend->insertAccount(json_encode(array('data' => $_POST['data'])));
				
				$NABackend->setError("Insert Account: No data received");
				break;
			case 'update':
				if (isset($_POST['data']) && isset($params[2])) 
					$NABackend->updateAccount((int)$params[2], json_encode(array('data' => $_POST['data'])));


				if (!isset($params[2])) $NABackend->setError("Update Account: Missing arguments");
				if (!isset($_POST['data'])) $NABackend->setError("Update Account: Not enough data received");
				break;
			case 'delete':
				if (isset($params[2])) 
					$NABackend->deleteAccount((int)$params[2]);

				if (!isset($params[2])) $NABackend->setError("Delete Account: Missing arguments");
				break;
			default:
				$NABackend->setError("Function not recognized");
				break;
		}
	}
	
	/**
	 * 
	 * @package default
	 */
	class NABackend{

		/**
		 * Database connection
		 */ 
		protected $conn;

		/**
		 * Database server
		 */ 
		private $server;

		/**
		 * Database username
		 */ 
		private $username;

		/**
		 * Database password
		 */ 
		private $password;

		/**
		 * Database name
		 */ 
		private $database;

		/**
		 * Main table used for this test
		 */ 
		private $tableName;

		/**
		 * Constructor
		 * @return type
		 */
        public function __construct($server = null, $username = null, $password = null, $database = null){
			$this->conn      = null;
			$this->tableName = "accounts";

			if( !(is_null($server) || is_null($username) || is_null($password) || is_null($database)) && 
			    !(empty($server)   || empty($username)   || empty($database)) ) {
				$this->set_connection($server, $username, $password, $database);
			}else{
				die("Not enough arguments to setup database connection");
			}
        }

        /**
         * Function to setup database variables
         * @param type $server 
         * @param type $username 
         * @param type $password 
         * @param type $database 
         */
        private function set_connection($server, $username, $password, $database){
        	$this->server   = $server;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
        }

        /**
         * Function to open database connection
         */
        private function open_connection(){
            $host        = "host=".$this->server;
            $port        = "port=5432";
            $dbname      = "dbname=".$this->database;
            $credentials = "user=".$this->username." password=".$this->password;
            // $this->conn = new mysqli($this->server, $this->username, $this->password, $this->database);
            $this->conn = pg_connect("$host $port $dbname $credentials" );

            // Check connection
            if (!$this->conn) {
                die("Connection failed: " );
            }
        }

        /**
         * Function to close database connection
         */
        private function close_connection(){
            pg_close($this->conn);            
        }

        /**
         * Function to check if the connection is stablished or not
         * 
         * @return bool Whether the connection is stablished or not
         */
        private function is_connected(){
            return !(is_null($this->conn));
        }

        /**
         * Function to execute a query on the database
         * 
         * @param String $sql SQL sentence to execute
         * 
         * @return Array Result of the given SQL
         */
        private function query($sql){
        	$this->open_connection();

			$result = array();
			$rows   = array();

        	if ($this->is_connected()){
				// $result = $this->conn->query($sql);
                $ret = pg_query($this->conn, $sql);
				while($row = pg_fetch_assoc ($ret))
                {
                    $rows[] = $row;
                }

        		$this->close_connection();
        	}

        	return $rows;
        }

        /**
         * Function to execute a SQL sentence
         * 
         * @param String $sql SQL sentence to execute
         * @param bool $affected_rows Whether to return affected rows or not
         * 
         * @return int Number of rows affected / New ID inserted
         */
        private function execute($sql, $affected_rows = true){
        	$this->open_connection();

        	$result = -1;

        	if($this->is_connected()){			
                $ret = pg_query($this->conn, $sql);
                $insert_row = pg_fetch_row($ret);

				$result = ($affected_rows) ? pg_affected_rows($ret) : $insert_row[0];

        		$this->close_connection();
        	}

        	if($result === -1) $this->setError("Something happened");
        	return $result;
        }

        /**
         * Function to get all accounts available
         * 
         * @return JSON All accout data
         */
        public function getAccounts(){
			$sql = "SELECT * FROM " . $this->tableName;

        	echo json_encode($this->query($sql));
        	die();
        }

        /**
         * Function to get a single account (based on given ID)
         * 
         * @param int $id Account ID
         * 
         * @return JSON Account data for selected ID
         */
        public function getAccount($id){
       		$sql = "SELECT * FROM " . $this->tableName . " WHERE id = " . $id;

       		echo json_encode($this->query($sql));
        	die();
        }

        /**
         * Function to insert a new account
         * 
         * @param JSON $data Data to be inserted
         * 
         * @return int New account's ID
         */
        public function insertAccount($data){
			$data = json_decode($data, true);

			$result = -1;

			if (isset($data['data'])){
				$sql    = "INSERT INTO " . $this->tableName . " ( ";
				$values = "";

				foreach ($data['data'] as $key => $value) {
					$sql .= $key;
					$values .= "'" . pg_escape_string($value) . "'";

					if (!($data['data'][$key] === end($data['data']))) {
						$sql    .= ", "; $values .= ", ";
					}
				}

				$sql .= ") VALUES (" . $values . ") RETURNING id;";				
	        	$result = $this->execute($sql, false);

        		echo json_encode($result);
        		die();
			}

			$this->setError('Insert Account: No data received');
        }

        /**
         * Function to update an existing account
         * 
         * @param int $id Account ID
         * @param JSON $data Data to be updated
         * 
         * @return int Number of rows affected
         */
        public function updateAccount($id, $data){
            $data = json_decode($data, true);
            
			$result = -1;

			if (is_null($id) || !is_int($id)) $this->setError("Update Account: Account not valid");

			if (isset($data['data'])){
				$sql    = "UPDATE " . $this->tableName . " SET ";

				foreach ($data['data'] as $key => $value) {
					if ($key !== "id"){
						$sql .= $key . " =  '". pg_escape_string($value)."'" ;

						if (!($data['data'][$key] === end($data['data']))) {
							$sql    .= ", "; 
						}
					}
				}
                $sql .= " WHERE id = " . $id;

        		$result = $this->execute($sql);
        		
        		echo json_encode($result);
        		die();
			}

			$this->setError('Update Account: No data received');
        }

        /**
         * Function to delete an existing account
         * 
         * @param int $id Account ID
         * 
         * @return int Number of rows affected
         */
        public function deleteAccount($id){
        	$sql = "DELETE FROM " . $this->tableName . " WHERE id = " . $id;

        	echo json_encode($this->execute($sql));
        	die();
        }

        /**
         * Function to return an error message
         * 
         * @param String $msg Error message
         * 
         * @return JSON Error message
         */
        public function setError($msg){
        	echo json_encode(array('error' => $msg));
        	die();
        }


	}
?>