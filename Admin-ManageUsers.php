<?php @session_start(); ?>
<?php require_once('Connections/dbConn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "Login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
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

if ((isset($_POST['hiddenField'])) && ($_POST['hiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM `user` WHERE UserID=%s",
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($deleteSQL, $dbConn) or die(mysql_error());

  $deleteGoTo = "Admin-ManageUsers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$currentPage = $_SERVER["PHP_SELF"];

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_User = sprintf("SELECT * FROM `user` WHERE UserName = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $dbConn) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

$maxRows_ManageUsers = 10;
$pageNum_ManageUsers = 0;
if (isset($_GET['pageNum_ManageUsers'])) {
  $pageNum_ManageUsers = $_GET['pageNum_ManageUsers'];
}
$startRow_ManageUsers = $pageNum_ManageUsers * $maxRows_ManageUsers;

mysql_select_db($database_dbConn, $dbConn);
$query_ManageUsers = "SELECT * FROM `user` ORDER BY `Timestamp` DESC";
$query_limit_ManageUsers = sprintf("%s LIMIT %d, %d", $query_ManageUsers, $startRow_ManageUsers, $maxRows_ManageUsers);
$ManageUsers = mysql_query($query_limit_ManageUsers, $dbConn) or die(mysql_error());
$row_ManageUsers = mysql_fetch_assoc($ManageUsers);

if (isset($_GET['totalRows_ManageUsers'])) {
  $totalRows_ManageUsers = $_GET['totalRows_ManageUsers'];
} else {
  $all_ManageUsers = mysql_query($query_ManageUsers);
  $totalRows_ManageUsers = mysql_num_rows($all_ManageUsers);
}
$totalPages_ManageUsers = ceil($totalRows_ManageUsers/$maxRows_ManageUsers)-1;

$queryString_ManageUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ManageUsers") == false && 
        stristr($param, "totalRows_ManageUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ManageUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ManageUsers = sprintf("&totalRows_ManageUsers=%d%s", $totalRows_ManageUsers, $queryString_ManageUsers);
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
	<div id="PageHeading"></div>
		<div id="ContentLeft">
		  <h2>ADMIN CP</h2>
		  <p>&nbsp;</p>
		  <h6>Admin Links</h6>
		  <p>Links Here!</p>
		</div>
    <div id="ContentRight">
      <table width="670" border="0" align="center" cellpadding="5" cellspacing="5">
        <tr>
          <td><div align="right">Showing<?php echo ($startRow_ManageUsers + 1) ?>to<?php echo min($startRow_ManageUsers + $maxRows_ManageUsers, $totalRows_ManageUsers) ?>of <?php echo $totalRows_ManageUsers ?> </div></td>
        </tr>
        <tr>
          <td><div align="center">
            <?php if ($totalRows_ManageUsers > 0) { // Show if recordset not empty ?>
              <?php do { ?>
                <table width="570" border="0" cellspacing="5" cellpadding="5">
                  <tr>
                    <td><p>First Name:<?php echo $row_ManageUsers['FirstName']; ?></p>
                      <p>LastName:<?php echo $row_ManageUsers['LastName']; ?></p>
                      <p>Email id:<?php echo $row_ManageUsers['Email']; ?></p>
                      <p>Password:<?php echo $row_ManageUsers['Password']; ?></p></td>
                    </tr>
                  <tr>
                    <td><form id="DeleteUserForm" name="DeleteUserForm" method="post" action="">
                      <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_ManageUsers['UserID']; ?>" />
                      <input type="submit" name="DeleteUserButton" id="DeleteUserButton" value="DeleteUser" />
                      </form></td>
                    </tr>
                  <tr>
                    <td>&nbsp;</td>
                    </tr>
                </table>
                <?php } while ($row_ManageUsers = mysql_fetch_assoc($ManageUsers)); ?>
              <?php } // Show if recordset not empty ?>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
        </tr>
      </table>
    </div>
</div>
<div id="Footer"></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($User);

mysql_free_result($ManageUsers);
?>
