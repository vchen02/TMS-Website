<?php
	session_start();
    $conn = mysql_connect('localhost', 'root', 'root') or die(mysql_error());
    mysql_select_db('TMS') or die(mysql_error());

    $user = $_REQUEST['username'];
    echo $user;
    $query = mysql_query("select * from users where USER_ID=".$user." and USER_TYPE='admin'");

    if(mysql_num_rows($query) > 0) {
      echo "<form id='injectform' target='_blank' action='inject.php' method='POST'>
              <input id='inject_button' type='submit' value='Inject Data'/>
            </form>";
    }
?>