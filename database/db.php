<?php

/**
 * Database class for handling database connections
 */
class Database
{
    private $con;

    public function connect()
    {
        include_once("constants.php");
        
        // Create a connection to the MySQL server
        $this->con = new mysqli(HOST, USER, PASS);
        
        // Check for connection errors
        if ($this->con->connect_error) {
            $this->logError("Connection failed: " . $this->con->connect_error);
          //  die("Connection failed: " . $this->con->connect_error);
        }

        // Check if the database exists
        $dbExists = $this->con->query("SHOW DATABASES LIKE '" . DB . "'");

        if ($dbExists->num_rows == 0) {
            // Database does not exist, create it
            if ($this->con->query("CREATE DATABASE " . DB) === TRUE) {
                //echo "Database '" . DB . "' created successfully.";
                
                // Now select the database
                $this->con->select_db(DB);

                // Run the SQL file to create tables and insert initial data
                $this->runSqlFile(__DIR__ . '/project_inv.sql');
            } else {
                $this->logError("Error creating database: " . $this->con->error);
                //die("Error creating database: " . $this->con->error);
            }
        } else {
            // Now select the database
            $this->con->select_db(DB);
           // echo "Database '" . DB . "' already exists.";
        }

        return $this->con;
    }

    private function runSqlFile($filePath)
    {
        // Read the SQL file
        $sql = $this->getFileContents($filePath);
        
        if ($sql === false) {
            $this->logError("Error reading SQL file: " . $filePath);
            // die("Error reading SQL file: " . $filePath);
        }

        // Execute the SQL commands
        if ($this->con->multi_query($sql)) {
            do {
                // Store the first result set
                if ($result = $this->con->store_result()) {
                    $result->free();
                }
            } while ($this->con->next_result());
            // echo "SQL file executed successfully.";
        } else {
            $this->logError("Error executing SQL file: " . $this->con->error);
           //  die("Error executing SQL file: " . $this->con->error);
        }
    }

    private function getFileContents($filePath)
    {
        if (is_readable($filePath)) {
            return file_get_contents($filePath);
        } else {
            $this->logError("File not readable: " . $filePath);
            return false;
        }
    }

    private function logError($message)
    {
        // Log the error message to a file
        $logFile = 'error_log.txt'; // Specify your log file path
        $timestamp = date("Y-m-d H:i:s");
        $errorMessage = "[$timestamp] $message\n";
        file_put_contents($logFile, $errorMessage, FILE_APPEND);
    }
}

// Uncomment the following lines to test the connection
// $db = new Database();
// $db->connect();

?>