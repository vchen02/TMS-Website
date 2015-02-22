<?php
    session_start();
    if(isset($_POST['submit']))
    {
      mysql_connect('localhost','root','root') or die(mysql_error());
      mysql_select_db('TMS') or die(mysql_error());
      $name=$_POST['username'];
      $pwd=$_POST['pwd'];
      $query=mysql_query("select * from USERS where USER_ID='".$name."' and PASSWORD='".$pwd."'") or die(mysql_error());
      $match=mysql_num_rows($query);

      if($match > 0)
      {
        //$.post('test.php', {username:'test'});
        header('Location: main.php');
        exit();
      }
      else { echo 'Incorrect, enter both username and password again'; }
    }
?>

<html>
<head>
  <!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">-->
  <style type="text/css">
    @import url(http://fonts.googleapis.com/css?family=Stalemate);

    body,td,th {
      font-family: Arial, Helvetica, sans-serif;
      color: #666;
      font-size: 14px;
    }
    body {
      background-color: #000;
      margin-left: 0px;
      margin-top: 0px;
      margin-right: 0px;
      margin-bottom: 0px;
    }
    .footer {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: normal;
      color: #999;
    }
    img { vertical-align: middle;}
    h1  {    
      font-weight: bold;
      font-size: 30px;
      position: absolute;
      float: left; left: 5% ;top: 5%;
      font-family: 'Stalemate', cursive; 
      color: #fff;
    }
   </style> 
  <title>CNC Time Management Software Login</title>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr class="red_shim">
      <td><img src="images/red-shim-08.png" width="100%" height="8"></td>
      <td><img src="images/red-shim-08.png" width="100%" height="8"></td>
      <td><img src="images/red-shim-08.png" width="100%" height="8"></td>
      <td><img src="images/red-shim-08.png" width="100%" height="8"></td>
      <td><img src="images/red-shim-08.png" width="100%" height="8"></td>
    </tr>
    <tr>
      <td bgcolor="#000000">&nbsp;</td>
      <td colspan="3"><img src="images/header.gif" width="960" height="230">
          <h1>CNC Time Management Software</h1>
      </td>
      <td bgcolor="#000000">&nbsp;</td>
    </tr>

   <tr>
      <td width="50%" bgcolor="#000000"></td>
      <td><img src="images/left-of-nav-window.png" width="229"></td>
      <td valign="top" bgcolor="#FFFFFF">
        <form action="" method="post" name="myform">
          <table border="0" cellspacing="0" cellpadding="0">
            <tbody>
            	<tr>
              	<td colspan="4"><img src="images/nav-box-header.png" width="482" height="47"></td>
              </tr>
      	      <tr>
        	      <td width="20">&nbsp;</td>
        	      <td colspan="2"></td>
        	      <td width="20">&nbsp;</td>
      	      </tr>
      		    <tr>
        	      <td width="20">&nbsp;</td>
        	      <td width="221" valign="bottom">
      			       <p><input type="text" name="username" placeholder="Your User Name" size="25" onblur="javascript:if(this.value==''){this.placeholder='Your User Name'}"></p>
      			       <p><input type="password" name="pwd" placeholder="Your Password" size="25"></p>
                   <p><input type="submit" name="submit" value="Submit"></p>
      		      </td>
      	        <td width="221" valign="bottom"><span class="footer"></td>
                <td width="20">&nbsp;</td>
              </tr>
      	    </tbody>
    	    </table>
  	    </form>
      </td>    
      <td><img src="images/right-of-nav-window.png" width="249"></td>
      <td width="50%" bgcolor="#000000">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#000000"></td>
      <td colspan="3"><img src="images/below-nav-window.png" width="960"></td>
      <td bgcolor="#000000"></td>
    </tr>
    <tr>
      <td bgcolor="#000000">&nbsp;</td>
      <td class="footer" colspan="3" bgcolor="#000000"></td>
      <td bgcolor="#000000">&nbsp;</td>
    </tr>
  </tbody>
  </table>

</body>
</html>
