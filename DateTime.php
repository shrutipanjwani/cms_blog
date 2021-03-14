<?php 
    date_default_timezone_set("Asia/Kolkata");
    $CurrentTime = time();
    echo $DateTime = strftime( "%Y-%m-%d %H:%M:%S" , $CurrentTime);
    echo "<br>";
    echo $DateTime = strftime( "%B-%d-%y %H:%M:%S" , $CurrentTime);
?>