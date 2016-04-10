<?php

    function getDirectoryString ($directoryLevel) {

        $directoryString = "";
        for ($i = 0; $i < $directoryLevel; $i += 1) {
            $directoryString .= "../";
        }
        $directoryString .= "./";

        return $directoryString;
    }

    function getDistricts ($directoryLevel) {
    
        $dirString = getDirectoryString($directoryLevel);

        include $dirString . 'config/connection.php';

        $query =    "   SELECT * 
                        FROM district 
                        GROUP BY dist_name ASC
                    ";

        try {
        
            // echo $query; // UNCOMMENT for DEBUGGING

            // prepare query for execution
            $stmt = $con->prepare($query);

            // Execute the query
            $stmt->execute();
     
            $result = $stmt->fetchAll();
            
            $con = null;

            return $result;
        }

        catch (Exception $e) {
            die(var_dump($e));
        }
    }

    function getTypes ($directoryLevel) {
    
        $dirString = getDirectoryString($directoryLevel);

        include $dirString . 'config/connection.php';

        $query =    "   SELECT type
                        FROM property 
                        GROUP BY type ASC
                    ";


        try {
        
            //echo $query; // UNCOMMENT for DEBUGGING

            // prepare query for execution
            $stmt = $con->prepare($query);

            // Execute the query
            $stmt->execute();
     
            $result = $stmt->fetchAll();

            $con = null;

            return $result;
        }

        catch (Exception $e) {
            die(var_dump($e));
        }
    }
?>