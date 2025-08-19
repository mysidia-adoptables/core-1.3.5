<?php

//Max Volume Installation Wizard
define("SUBDIR", "Install");
include("../inc/config.php");

$step = $_GET["step"];
$step = preg_replace("/[^a-zA-Z0-9s]/", "", (string) $step);

if ($step == 3 or $step == "3") {

    try {
        $dsn = "mysql:host=".constant("DBHOST").";dbname=".constant("DBNAME");
        $prefix = constant("PREFIX");
        $adopts = new PDO($dsn, DBUSER, DBPASS);
    } catch (PDOException $pe) {
        die("Could not connect to database, the following error has occurred: <br><b>{$pe->getmessage()}</b>");
    }

    // Now begins the tedious database execution process

    // Create Table alternates
    $query = "CREATE TABLE {$prefix}alternates (alid int NOT NULL AUTO_INCREMENT PRIMARY KEY, adopt varchar(40), image varchar(100), level int DEFAULT 0, item int DEFAULT 0, gender varchar(10), lastalt int DEFAULT 0, chance int DEFAULT 0)";
    $adopts->query($query);

    // Create Table systems and its corresponding rows
    $query = "CREATE TABLE {$prefix}systems (name varchar(20), value varchar(350))";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('site', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('adopts', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('friends', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('items', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('messages', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('online', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('promo', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('register', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('shops', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('shoutbox', 'enabled')";
    $adopts->query($query);

    $query = "INSERT INTO {$prefix}systems VALUES ('vmessages', 'enabled')";
    $adopts->query($query);


    // Alter table adoptables to remove column altchance
    $query = "ALTER TABLE {$prefix}adoptables DROP COLUMN altchance";
    $adopts->query($query);

    // Alter table levels to remove column alternateimage
    $query = "ALTER TABLE {$prefix}levels DROP COLUMN alternateimage";
    $adopts->query($query);

    // Alter table owned_adoptables to rename column usealternates to alternate
    $query = "ALTER TABLE {$prefix}owned_adoptables CHANGE `usealternates` `alternate` varchar(10);";
    $adopts->query($query);


    // All done, cheers!

    echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'
'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<title>Mysidia Adoptables Upgrade Wizard</title>
<style type='text/css'>
<!--
body,td,th {
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
body {
	background-color: #ffff00;
}
a:link {
	color: #000000;
}
a:visited {
	color: #000000;
}
a:hover {
	color: #000000;
}
a:active {
	color: #000000;
}
.style1 {
	font-size: 18px;
	color: #FFFFFF;
}
.style2 {font-size: 14px}
.style4 {font-size: 12px; }
-->
</style></head>

<body>
<center><table width='750' border='0' cellpadding='0' cellspacing='0'>
  <!--DWLayoutTable-->
  <tr>
    <td width='750' height='57' valign='top' bgcolor='#FF3300'><div align='left'>
      <p><span class='style1'>Mysidia Adoptables Upgrade Wizard <br>
        <span class='style2'>Successfully upgrade Mysidia Adoptables to version v1.3.5 </span></span></p>
    </div></td>
  </tr>
  <tr>
    <td height='643' valign='top' bgcolor='#FFFFFF'><p align='left'><br>
      <span class='style2'>Congratulations, you have successfully upgraded to Mysidia Adoptables version v1.3.5! We strongly advise you to remove this upgrader before working on your site again.</span></p>
        </p></td>
  </tr>
</table>
</center>
</body>
</html>";

} elseif ($step == 2 or $step == "2") {

    //Check file permissions...
    $flag = 0;
    echo"<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'
'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<title>Mysidia Adoptables Installation Wizard</title>
<style type='text/css'>
<!--
body,td,th {
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
body {
	background-color: #ffff00;
}
a:link {
	color: #000000;
}
a:visited {
	color: #000000;
}
a:hover {
	color: #000000;
}
a:active {
	color: #000000;
}
.style1 {
	font-size: 18px;
	color: #FFFFFF;
}
.style2 {font-size: 14px}
-->
</style></head>

<body>
<center><table width='750' border='0' cellpadding='0' cellspacing='0'>
  <!--DWLayoutTable-->
  <tr>
    <td width='750' height='57' valign='top' bgcolor='#FF3300'><div align='left'>
      <p><span class='style1'>Mysidia Adoptables Upgrade Wizard <br>
        <span class='style2'>Step 2: Add/Modify database tables </span></span></p>
    </div></td>
  </tr>
  <tr>
    <td height='643' valign='top' bgcolor='#FFFFFF'><p align='left'><br>
      <span class='style2'>This page will check information provided in your config.php file, which should not be a problem unless you have manually edited it by yourself.  
	Please make sure your file config.php is writable and your database information is provided correctly. Then click on the continue button below to proceed.</span></p>";

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Check the file permissions here and echo the results...

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    if (is_writable("../inc/config.php")) {
        echo "<p align='left'><img src='../templates/icons/yes.gif'> <b>PASS:</b>  Your config.php file is writable and is connected to database.<br></p>";
    } else {
        echo "<b><p align='left'><img src='../templates/icons/no.gif'> FAIL:</b> Your config.php file is not writable.  Please CHMOD config.php so that it is executable.<br></p>";
        $flag = 1;
    }

    if (file_exists("../admincp/alternate.php")) {
        echo "<p align='left'><img src='../templates/icons/yes.gif'> <b>PASS:</b>  Your admincp/alternate.php file exists and is executable.<br></p>";
    } else {
        echo "<b><p align='left'><img src='../templates/icons/warning.gif'> WARNING:</b> Something is very very wrong with your admincp/alternate.php file. Please make sure it exists and CHMOD the directory to 644.<br></p>";
        $flag = 1;
    }

    if (file_exists("../admincp/view/alternateview.php")) {
        echo "<p align='left'><img src='../templates/icons/yes.gif'> <b>PASS:</b>  Your admincp/view/alternateview.php file exists and is executable.<br></p>";
    } else {
        echo "<b><p align='left'><img src='../templates/icons/warning.gif'> WARNING:</b> Something is very very wrong with your admincp/view/alternateview.php file. Please make sure it exists and CHMOD the directory to 644.<br></p>";
        $flag = 1;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //END THE FILE PERMISSIONS CHECKS
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if ($flag == 0) {

        echo "<br><p align='right'><br>
        <a href='upgrade.php?step=3'><span class='style2'><img src='../templates/icons/yes.gif' border=0> Yes, I wish to Continue</span></a> </p></td>";

    }


    echo"</tr>
</table>
</center>
</body>
</html>";

} else {
    echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'
'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<title>Mysidia Adoptables Installation Wizard</title>
<style type='text/css'>
<!--
body,td,th {
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
body {
	background-color: #ffff00;
}
a:link {
	color: #000000;
}
a:visited {
	color: #000000;
}
a:hover {
	color: #000000;
}
a:active {
	color: #000000;
}
.style1 {
	font-size: 18px;
	color: #FFFFFF;
}
.style2 {font-size: 14px}
.style3 {font-size: 16px}
.style4 {font-size: 12px}
-->
</style></head>

<body>
<center><table width='750' border='0' cellpadding='0' cellspacing='0'>
  <!--DWLayoutTable-->
  <tr>
    <td width='750' height='57' valign='top' bgcolor='#FF3300'><div align='left'>
      <p><span class='style1'>Mysidia Adoptables Upgrade Wizard <br>
        <span class='style2'>Step 1: Welcome and License Agreement</span>
      </span></p>
    </div></td>
  </tr>
  <tr>
    <td height='643' valign='top' bgcolor='#FFFFFF'><p align='left'><br>
      <span class='style2'>This upgrader will update your Mysidia Adoptables to version v1.3.5. Before you upgrade, however, please make sure that your Mysidia Adoptables version is currently at v1.3.4. Also, you must agree to the Mysidia Adoptables License Agreement as it is outlined below:</span></p>
      <p align='left' class='style3'><u>Mysidia Adoptables License Agreement: </u></p>
      <p align='left' class='style4'>Mysidia Adoptables is licensed under a Free for Non-Commercial Use license, terms of this license are interpreted as the following: </p>
      <p align='left' class='style4'>---Commercial use of the product on your server is OK, while the script may not be redistributed in whole or as part of another script. </p>
      <p align='left' class='style4'>---You must post credit to Mysidia Adoptables (http://www.mysidiaadoptables.com) and keep it visible on all pages unless you have created a credits page.  </p>
      <p align='left' class='style4'>---You can create modifications of this script (or hire freelancers to create modifications of this script) at any time. </p>
      <p align='left' class='style4'>For permissions beyond the scope of this license please Contact (Hall of Famer) at halloffamer@mysidiaadoptables.com.</p>
    <p align='right' class='style2'><a href='upgrade.php?step=2'><img src='../templates/icons/yes.gif' border=0> I Agree - Continue Installation</a>  </p></td>
  </tr>
</table>
</center>
</body>
</html>";
}
