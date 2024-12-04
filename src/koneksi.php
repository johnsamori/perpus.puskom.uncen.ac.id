.<?php
    define('DB_HOST', '172.10.10.240');
    define('DB_USER', 'perpus');
    define('DB_PASS', 'perpus2024');
    define('DB_NAME', 'db_srtperpus');

    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    ?>