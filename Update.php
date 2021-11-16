<?php @session_start(); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateForm")) {
  $updateSQL = sprintf("UPDATE `user` SET `Email`=%s,`Password`=%s WHERE `UserID`=%s",
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['UserIDHiddenField'], "int"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($updateSQL, $dbConn) or die(mysql_error());

  $updateGoTo = "Account.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_dbConn, $dbConn);
$query_User = sprintf("SELECT * FROM `user` WHERE UserName = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $dbConn) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/Layout.css" rel="stylesheet" type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
</head>

<body>
:
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
	  <h1>UPDATE ACCOUNT</h1>
	</div>
		<div id="ContentLeft">
		  <h2>ACCOUNT LINKS</h2>
		  <p>&nbsp;</p>
		  <h6>LINKS HERE</h6>
		</div>
    <div id="ContentRight">
      <form action="<?php echo $editFormAction; ?>" id="UpdateForm" name="UpdateForm" method="POST">
        <table width="672" border="0" cellspacing="5" cellpadding="5">
          <tr>
            <td width="610"><p>Account :<?php echo $row_User['FirstName']; ?><?php echo $row_User['LastName']; ?></p>
              <p>Username:<?php echo $row_User['UserName']; ?></p></td>
          </tr>
        </table>
        <table width="600" border="0" cellspacing="5" cellpadding="5">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprytextfield1">
              <label for="Email"></label>
              Email<br />
              <input name="Email" type="text" id="Email" value="<?php echo $row_User['Email']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprypassword1">
              <label for="Password"></label>
              Password<br />
              <input name="Password" type="password" id="Password" value="<?php echo $row_User['Password']; ?>" />
            <span class="passwordRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="submit" name="Update" id="Update" value="Update" />
            <input name="UserIDHiddenField" type="hidden" id="UserIDHiddenField" value="<?php echo $row_User['UserID']; ?>" /></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <input type="hidden" name="MM_update" value="UpdateForm" />
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>
<?php
mysql_free_result($User);
?>
