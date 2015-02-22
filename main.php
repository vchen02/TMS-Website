<?php

    $conn = mysql_connect('localhost', 'root', 'root') or die(mysql_error());
    mysql_select_db('TMS') or die(mysql_error());

    $user = $_POST['username'];
    $query = mysql_query("select * from users where USER_ID=".$user." and USER_TYPE='admin'");
?>

<html>

<head>
  <meta charset="UTF-8">
  <title>CNS Time Management Software</title>

    <!--
      <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" type="text/css" />
    -->

    <link rel="stylesheet" href="css/style_m.css" media="screen" type="text/css" />
    <!--Use the most up-to-date version of IE possible-->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <!--Use mobile optimized Fennec feature-->
    <meta name="viewport" content="width=device-width initial-scale=1.0">

</head>

<body>

  <header>
    <h1>CNC Time Management Software</h1>
    <nav role="navigation">
          <ul class="nav navbar-nav navbar-right">
            <li><a class="link-1 fontawesome-home active" href="main.php"></a></li>

            <!--Create a Drop-Down menu for user to enter in program_id or machine_id-->
            <li><a class="link-2 fontawesome-dashboard" href="#"></a></li>
            <li><a class="zoom-in fontawesome-zoom-in" onclick="zoomIn()"></a></li>
            <li><a class="zoom-out fontawesome-zoom-out" onclick="zoomOut()"></a></li>
          </ul>
    </nav> 
  </header>

<section id="home">
  <h1>TMS Homepage</h1>

 <!--Possible GET variables from this form are:
    1.radio_select (dashboard_p)
      a. p_id
      b. p_date
    2.radio_select (dashboard_m)
      a. m_id
      b. m_date
  -->
  <form name="mainform" action="dashboard.php" method="GET"> 
    <!--User_Select Dashboard Radio-->
    <div class="user_select">
      <label for="select_program" style="position:absolute; left: 15%;">
        <input type="radio" id="select_program" name="radio_select" value="dashboard_p" 
        onclick="changeBorderColor('program')">
        <span>Program Dashboard</span></label>
      <label for="select_machine" style="position:absolute; right: 15%;">
        <input type="radio" id="select_machine" name="radio_select" value="dashboard_m"
        onclick="changeBorderColor('machine')">
        <span>Machine Dashboard</span></label>
    </div>

    <!--Program -->
    <div class="home_content"  id="program">
      <h1>Program</h1>
      <div class="first">
        <label>Enter Program Number:</label><br>
        <input id="program_id" name="p_id" placeholder="Program ID">
      </div> 
       
      <div class="second">
        <label>Select the Date:</label><br>
        <input id="program_date" name="p_date" type="date">
      </div>

      <div class="third">
        <label>
            <span>Show All Program IDs</span>
            <input type="checkbox" name="all_program" value="all_program" 
            
        </label>
      </div>
    </div>

    <!--Machine-->
    <div class="home_content"  id="machine">
      <h1>Machine</h1>
      <div class="first">
        <label>Enter Machine Number:</label><br>
        <input id="machine_id" name="m_id" placeholder="Machine ID">
      </div> 

      <div class="second">
        <label>Select the Date:</label><br>
        <input id="machine_date" name="m_date" type="date">
      </div>

      <div class="third">
        <label>
            <span>Show All Machine IDs</span>
            <input type="checkbox" name="all_machine" value="all_machine" 
            onclick="disableInputField('document.mainform', 'all_machine', 'm_id, m_date')">
        </label> 
      </div>
    </div>

    <!--Submit button -->
    <input id="bigbutton" type="submit" value="Show Dashboard" />
  </form>

<!-- 
  <?php
  //  if(mysql_num_rows($query) > 0) {
   //   echo "<form id='injectform' target='_blank' action='inject.php' method='POST'>
     //         <input id='inject_button' type='submit' value='Inject Data'/>
       //     </form>";
    }
  ?> -->

 <form id="injectform" target="_blank" action="inject.php" method="POST">
    <input id="inject_button" type="submit" value="Inject Data"/>
  </form>

</section>

  <script src='http://codepen.io/assets/libs/fullpage/jquery.js'></script>
  <script src="js/index.js"></script>
</body>
</html>

