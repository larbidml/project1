<?php
    // mysql is the driver
    // dsn driver source name 
    $dsn = 'mysql:host=localhost;dbname=contactsv3';
    $username = 'root';
    $password ='';

    try {
         //create an instance of the PDO class with the required parameters
        $db = new PDO($dsn , $username, $password);
        
        // set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         
        //display success message
        //echo "Connected to the register database <br>";
        }
        catch(PDOException $ex)
        {
        //display error message
        echo "Connection failed ".$ex->getMessage();
        }

?>

