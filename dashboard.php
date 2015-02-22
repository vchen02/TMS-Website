<?php
  //ESTABLISH THE CONNECTION
  $conn = mysql_connect('localhost', 'root', 'root') or die(mysql_error());
  mysql_select_db('TMS') or die(mysql_error());

  /*DRAFT THE MYSQL QUERY BASE ON THE USER FORM */
  //Get values from User form
  $radio_select = $_GET['radio_select'];
  $all= False;     //variable flag to keep track of show-all input
  if($radio_select == 'dashboard_p') {    //User selected the program dashboard form
    $id = $_GET['p_id']."%";        
    $date = $_GET['p_date'];
    $query_colHead= mysql_query("SHOW COLUMNS FROM PROGRAM_LOG");

    if(isset($_GET['all_program'])) {     //User selected check-all input
      $query = mysql_query("SELECT * FROM PROGRAM_LOG");  
      $all = True;                        //Set show-all flag to True
    }
  }

  else {                                  //User selected the machine dashboard form
    $id = $_GET['m_id']."%";
    $date = $_GET['m_date'];
    $query_colHead= mysql_query("SHOW COLUMNS FROM MACHINE_LOG");

    if(isset($_GET['all_machine'])) {     //User selected check-all input
      $query = mysql_query("SELECT * FROM MACHINE_LOG");
      $all = True;                        //Set show-all flag to True
    }
  }
  
  //User did not select show all
  if (!$all) {

    //Compile query string    
    $query_str = "SELECT * FROM";
    $query_log = ($radio_select == 'dashboard_p' ? "PROGRAM_LOG" : "MACHINE_LOG");
    $query_id  = (strtoupper($id[0]) == 'O' ? "PROGRAM_ID" : "MACHINE_ID");
    $query_date = ($radio_select == 'dashboard_p' ? "PROGRAM_LOG_DATE" : "MACHINE_LOG_DATE");

    if(isset($id)) {    
      if(isset($date) && $date != '') {      //User inputted ID and Date
        $query = mysql_query("$query_str $query_log WHERE $query_id LIKE \"$id\" AND $query_date=\"$date\"");
      }
      else {                  //User inputted ID only
        $query = mysql_query("$query_str $query_log WHERE $query_id LIKE \"$id\"");
      }
    }
    else if (isset($date)) {  //User Date but not ID
      $query = mysql_query("$query_str $query_log WHERE $query_date=\"$date\"");
    }

    /*working sample query on mysql: select * from program_log where program_id like 'O1%' and program_log_date ='2015-03-02' 
    */
  }
?>

<html>
<head>
  <meta charset="UTF-8">
  <title>CNS Time Management Software</title>

    <!--
      <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" type="text/css" />
    -->

    <link rel="stylesheet" href="css/style_m.css" media="screen" type="text/css" />
    <link rel="stylesheet" href="css/style_d.css" media="screen" type="text/css" />
    <!--Use the most up-to-date version of IE possible-->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <!--Use mobile optimized Fennec feature-->
    <meta name="viewport" content="width=device-width initial-scale=1.0">

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <header>
    <h1>CNC Time Management Software</h1>
    <nav role="navigation action">
          <ul class="nav navbar-nav navbar-right">
            <li><a class="link-1 fontawesome-home" href="main.php"></a></li>
            <li><a class="link-2 fontawesome-dashboard active" href="#dashboard"></a></li>
            <li><a class="zoom-in fontawesome-zoom-in" onclick="zoomIn()"></a></li>
            <li><a class="zoom-out fontawesome-zoom-out" onclick="zoomOut()"></a></li>
          </ul>
    </nav> 
  </header>

  <div class="big">
      <div class="pinkbg">
          <!--Input Dashboard Header of User's Selection--> 
          <?php
            $dash_str = ($radio_select == 'dashboard_p' ? "Program Dashboard" : "Machine Dashboard");
            echo "<h1 id=\"dash_head\">$dash_str</h1>";
          ?>
      </div>

      <!--Hover-over-content -->
      <!-- Get Hover box titles -->
      <?php
        $Title4 = ($radio_select == 'dashboard_p' ? "Program Idle Time" : "Machine Idle Time");
        $Title3 = ($radio_select == 'dashboard_p' ? "Program Run Time" : "Machine Run Time");
        $Title2 = ($radio_select == 'dashboard_p' ? "Program Up Time" : "Machine Up Time");
        $Title1 = ($radio_select == 'dashboard_p' ? "Program Succesful Completion Rate" : "Machine ID");
      ?>

      <div class="hove">
        <div class="stat" id="stat1"> 
            <h2 id="stat_a1">''</h2>
            <h3 id="stat_a2"><?php echo"$Title1"; ?> </h3></div>
        <div class="stat" id="stat2"> 
            <h2 id="stat_b1">''</h2>
            <h3 id="stat_b2"><?php echo"$Title2"; ?></h3></div>
        <div class="stat" id="stat3"> 
            <h2 id="stat_c1">''</h2>
            <h3 id="stat_c2"><?php echo"$Title3"; ?></h3></div>
        <div class="stat" id="stat4"> 
            <h2 id="stat_d1">''</h2>
            <h3 id="stat_d2"><?php echo"$Title4"; ?></h3></div>
      </div>

      <div class="container">
        <div class="scroll">
        <?php
          //DISPLAY QUERIED DATA IN TABLE
          //Display Table Column Header
          echo "<table cellpadding='0' cellspacing='0' border='0' width='100%''>";
          $col_stack = array();

          echo "<thead><tr>";
          //$x=0;
          while ($col_head = mysql_fetch_assoc($query_colHead)) {
            echo "<th>".$col_head['Field']."</th>";
            array_push($col_stack, $col_head['Field']);
            //$x++;
          }

          echo "</tr></thead><tbody class='tableBody'>";
          $col_head_count = count($col_stack); //This is the number of Columns of the table
          while($res=mysql_fetch_array($query)) {
            echo "<tr>";      //Begin row
            for($i=0; $i<$col_head_count; $i++) {
              echo "<td>".$res[$col_stack[$i]]."</td>";   //Insert each row data
            }
            echo "</tr>";     //End row
          }
          echo "</tbody></table>";
        ?>
        </div>
      </div>

  </div>    

  <script src='http://codepen.io/assets/libs/fullpage/jquery.js'></script>
  <script src="js/index.js"></script>

</body>
</html>