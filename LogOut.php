<?php require_once('Connections/dbConn.php'); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "Login.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
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

$colname_Logout = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Logout = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_Logout = sprintf("SELECT * FROM `user` WHERE UserName = %s", GetSQLValueString($colname_Logout, "text"));
$Logout = mysql_query($query_Logout, $dbConn) or die(mysql_error());
$row_Logout = mysql_fetch_assoc($Logout);
$totalRows_Logout = mysql_num_rows($Logout);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/Layout.css" rel="stylesheet" type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div id="Holder">
<div id="Header"></div>
<div id="Navbar">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">RegistrationSystem</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Login</a></li>
      <li><a href="#">Register</a></li>
      <li><a href="#">Forgot Password</a></li>
    </ul>
    <form class="navbar-form navbar-left" action="/action_page.php">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search" name="search">
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit">
            <i class="glyphicon glyphicon-search"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
</nav>
</div>

<div id="Content">
	<div id="PageHeading">
	  <h1>YOU HAVE LOGGED OUT</h1>
	</div>
		<div id="ContentLeft">
		  <h2>Your Message here</h2>
		  <p>&nbsp;</p>
		  <h6><a href="Admin.php">Your Name|Admin</a></h6>
		</div>
    <div align="left"></div>
    <div align="left"></div>
    <div id="ContentRight"></div>
</div>
<div id="Footer"></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Logout);
?>
