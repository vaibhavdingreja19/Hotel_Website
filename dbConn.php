<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbConn = "localhost";
$database_dbConn = "mpro";
$username_dbConn = "root";
$password_dbConn = "iloveneha";
$dbConn = mysql_pconnect($hostname_dbConn, $username_dbConn, $password_dbConn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>