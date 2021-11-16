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
      <li><a href="Login.php">Login</a></li>
      <li><a href="Register.php">Register</a></li>
      <li  class="active"><a href="ForgotPassword.php">Forgot Password</a></li>
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
	  <h1>EmailPassword</h1>
	</div>
		<div id="ContentLeft">
		  <h2>EMPW Message</h2>
		  <p>&nbsp;</p>
		  <h6>Your Message</h6>
		</div>
    <div id="ContentRight">
      <form id="EMPWform" name="EMPWform" method="post" action="Script.php">
        <p>
          <label for="Email"></label>
          <input type="text" name="Email" id="Email" />
        </p>
        <p>
          <input type="submit" name="EMPWButton" id="EMPWButton" value="Email Password" />
        </p>
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>