<?php
@session_start();
$_SESSION{'EMPW'} = $_POST{'Email'};
?>

<?php require_once('Connections/dbConn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_EmailPassword = "-1";
if (isset($_SESSION['EMPW'])) {
  $colname_EmailPassword = $_SESSION['EMPW'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_EmailPassword = sprintf("SELECT * FROM `user` WHERE Email = %s", GetSQLValueString($colname_EmailPassword, "text"));
$EmailPassword = mysql_query($query_EmailPassword, $dbConn) or die(mysql_error());
$row_EmailPassword = mysql_fetch_assoc($EmailPassword);
$totalRows_EmailPassword = mysql_num_rows($EmailPassword);

mysql_free_result($EmailPassword);
?>
<?php
if($totalRows_EmailPassword > 0)
{
    
$to_email = 'vaibhav.dingreja1919@gmail.com';
$subject = 'Testing PHP Mail';
$message = 'Password is:'.$row_EmailPassword['Password'];
$headers = 'From: randolphemperor@gmail.com';
mail($to_email, $subject, $message, $headers);
}

if($totalRows_EmailPassword > 0)
{
	echo "mail has been sent";
}
else
{
	echo "failed";
}
?>