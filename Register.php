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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="Register.php";
  $loginUsername = $_POST['UserName'];
  $LoginRS__query = sprintf("SELECT UserName FROM `user` WHERE UserName=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_dbConn, $dbConn);
  $LoginRS=mysql_query($LoginRS__query, $dbConn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "RegisterForm")) {
  $insertSQL = sprintf("INSERT INTO `user` (FirstName, LastName, Email, UserName, Password) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastNmae'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($insertSQL, $dbConn) or die(mysql_error());

  $insertGoTo = "Login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "RegisterForm")) {
  $insertSQL = sprintf ("INSERT INTO `user` (`FirstName`, `LastName`, `Email`, `UserName`, `Password`, `UserLevel`) VALUES (%s, %s, %s, %s, %s, '1')",
  						GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));
                      

  mysql_select_db($database_dbConn, $dbConn);
  $Result1 = mysql_query($insertSQL, $dbConn) or die(mysql_error());

  $insertGoTo = "Login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_dbConn, $dbConn);
$query_Register = "SELECT * FROM `user`";
$Register = mysql_query($query_Register, $dbConn) or die(mysql_error());
$row_Register = mysql_fetch_assoc($Register);
$totalRows_Register = mysql_num_rows($Register);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/Layout.css" rel="stylesheet" type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
      <li><a href="Login.php">Login</a></li>
      <li class="active"><a href="Register.php">Register</a></li>
      <li><a href="ForgotPassword.php">Forgot Password</a></li>
      
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
	  <h1>SIGN UP</h1>
	</div>
		<div id="ContentLeft">
		  <h2>REGISTER</h2>
		  <p>&nbsp;</p>
		  <h6><a href="Login.php">Adminlogin</a></h6>
		</div>
    <div id="ContentRight">
      <form action="<?php echo $editFormAction; ?>" id="RegisterForm" name="RegisterForm" method="POST">
        <table width="2400" border="0" cellspacing="5" cellpadding="5">
          <tr>
            <td><table border="0" cellspacing="5" cellpadding="5">
              <tr>
                <td width="216" height="28"><p>
                  <label for="FirstName"></label>
                  First Name
                  </p>
                  <p>
                    <input type="text" name="FirstName" id="FirstName" />
                </p></td>
                <td width="234"><p>
                  <label for="LastNmae"></label>
                  Last Name
                  </p>
                  <p>
                    <input type="text" name="LastNmae" id="LastNmae" />
                </p></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><p>
              <label for="Email"></label>
              Email</p>
              <p><span id="sprytextfield1">
              <input type="text" name="Email" id="Email" />
              <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></p></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><p>
              <label for="UserName"></label>
              User Name</p>
              <p>
                <input name="UserName" type="text" id="UserName" />
            </p></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table border="0" cellspacing="5" cellpadding="5">
              <tr>
                <td><p><td width="190" height="28">
                  <label for="Password"></label>
                  Password</p>
                  <p>
                    <input name="Password" type="password" id="Password" />
                  </p></td>
              </td>
                <td><p>
                  <label for="ConfirmPassword"></label>
                  ConfirmPassword</p>
                  <p>
                    <input name="ConfirmPassword" type="password" id="ConfirmPassword" />
                  </p></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><p>
              <label for="Contact"></label>
            </p></td>
          </tr>
          <tr>
            <td><input type="submit" name="RegisterButton" id="RegisterButton" value="Register" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="RegisterForm" />
    
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");
</script>
</body>
</html>
<?php
mysql_free_result($Register);
?>
