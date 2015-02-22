<?php
$server="localhost";
$username="root";
$password="root";
$dbname="TMS";
$dbtables=array("MACHINE_LOG", "PROGRAM_LOG");

//Establish the connection
$conn = mysql_connect('localhost', 'root', 'root') or die(mysql_error());
mysql_select_db('TMS') or die(mysql_error());

//Delete all current table data from the TMS database
$dbtables = array("MACHINE", "MACHINE_LOG", "OPERATOR", "PROGRAM", "PROGRAM_LOG");
$n=0;
while ($n < 5) {
	if(!mysql_query("TRUNCATE ".$dbtables[$n]."")) {
		echo "Table Truncate Error for ".$dbtables[$n]."<br>";
	}
	$n++;
}
echo "Successfully truncated previous tables in ".$dbname."<br><br>";

//Create function to generate data
/*
function setTriggers() {
	//Each time a row is inserted to Machine_Log, the M_Up_Time parameter is automatiicaly calculated.
	mysql_query("
DROP TRIGGER IF EXISTS `AutoSumMachineUpTime`
DELIMITER $$
CREATE TRIGGER AutoSumMachineUpTime
BEFORE INSERT ON `MACHINE_LOG` FOR EACH ROW
BEGIN
    SET NEW.M_UP_TIME = NEW.M_RUN_TIME + NEW.M_IDLE_TIME;
END;
$$
DELIMITER ;") or die(mysql_error());
	//Each time a row is inserted to Program_Log, the P_Up_Time parameter is automatiicaly calculated.
	mysql_query("
DROP TRIGGER IF EXISTS `AutoSumProgramUpTime`
DELIMITER $$
CREATE TRIGGER AutoSumProgramUpTime
BEFORE INSERT ON `PROGRAM_LOG` FOR EACH ROW
BEGIN
    SET NEW.P_UP_TIME = NEW.P_RUN_TIME + NEW.P_IDLE_TIME;
END;
$$
DELIMITER ;") or die(mysql_error());
}
*/

/* Generate a random date either in 'Y-m-d' or 'h:i:s' format */
function rand_date($min_date, $max_date, $opt) {	//helper function
	//Uses Epoch time

	$rand_epoch = mt_rand(strtotime($min_date), strtotime($max_date));
	if($opt==1) 
		return date('Y-m-d', $rand_epoch);
	else 
		return date('h:i:s', $opt*$rand_epoch);	
}

/* Give time1 and time2 in 'h:i:s' format, return sum of time1 + time2 */
function add_HMS_Time($time1, $time2) {	//helper function
	$original_array[0]=$time1; $original_array[1]=$time2;
	$hours=0; $minutes=0; $seconds=0;
	foreach($original_array AS $value){
	    $chunks= explode(":", $value);
	    $hours += $chunks[0];
	    $minutes += $chunks[1];
	    $seconds += $chunks[2];
	}
	$minutes += floor($seconds / 60);
	$seconds %= 60;

	$hours += floor($minutes /60);
	$minutes %= 60;

	$sum_time=$hours.":".$minutes.":".$seconds;
	return $sum_time;
}

function timeRatio($time1, $time2) {
	//split both times into a list containing hours, minutes, and seconds
	list($h1, $m1, $s1) = explode(":", $time1); 
	list($h2, $m2, $s2) = explode(":", $time2);

	$seconds1=0; 
	$seconds1 += (intval($h1) * 3600) + (intval($m1) * 60) + (intval($s1));
	$seconds2=0;
	$seconds2 += (intval($h2) * 3600) + (intval($m2) * 60) + (intval($s2));
	return $seconds1/$seconds2;
}

/* Populate Program_ID, Machine_ID and Operator_ID */
function generateIDs($num) {	//working
	$pre=array("LA","VMC","ROT");
	for($i=0; $i<$num; $i++) {
		$data0=$pre[rand(0,2)] . rand(0,10);   						//MACHINE_ID
		$data1=str_pad("O", 6, rand(0,99999), STR_PAD_RIGHT);		//PROGRAM_ID
		$data2=str_pad("OP_", 9, rand(0,999999), STR_PAD_RIGHT);	//OPERATOR_ID

		mysql_query("REPLACE INTO MACHINE (MACHINE_ID) VALUES ('$data0')") or die(mysql_error());
		generateMachineLog($data0,1); //Generate Machine_Log Data
		mysql_query("REPLACE INTO PROGRAM (PROGRAM_ID) VALUES('$data1')") or die(mysql_error());
		generateProgramLog($data1, $data0, $data2, 1);
		mysql_query("REPLACE INTO OPERATOR (OPERATOR_ID) VALUES('$data2')") or die(mysql_error());
	}
}

function generateMachineLog($m_id, $num) {
	
	for($i=0; $i<$num; $i++) {
		//generate a random date
		$date=rand_date("2014/11/01", "2015/03/08", 1);
		$run_time=rand_date("2015/02/28", "2015/03/08", 0.3);
		$idle_time=rand_date("2015/02/28", "2015/03/08", 0.7);
		$up_time=add_HMS_Time($run_time, $idle_time);

		mysql_query("REPLACE INTO MACHINE_LOG
			(MACHINE_ID, MACHINE_LOG_DATE, M_UP_TIME, M_RUN_TIME, M_IDLE_TIME) 
			VALUES ('$m_id', '$date', '$up_time', '$run_time', '$idle_time') ") 
			or die(mysql_error());
	}
}

function generateProgramLog($p_id, $m_id, $op_id, $num) {

	for($i=0; $i<$num; $i++) {
		//generate a random date
		$date=rand_date("2014/11/01", "2015/03/08", 1);
		
		//check to make sure generated idle time is 25% or less of run time
		do {
			$run_time=rand_date("2015/02/28", "2015/03/08", 0.75);
			$idle_time=rand_date("2015/02/28", "2015/03/08", 0.25);
		}while (timeRatio($idle_time, $run_time) <= 0.25 );

		$up_time=add_HMS_Time($run_time, $idle_time);

		//randomize boolean true and false for P_Run_Success
		$run_success= mt_rand(0,99999)%2;
		
		//randomize success rate for p_comp_success_rate that is 87%<p<99%
		do {
			$comp_rate = mt_rand(0,100)/100;
		} while ($comp_rate <= 0.87);

		mysql_query("REPLACE INTO PROGRAM_LOG
			(PROGRAM_ID, PROGRAM_LOG_DATE, MACHINE_ID, OPERATOR_ID, 
				P_UP_TIME, P_RUN_TIME, P_IDLE_TIME, P_RUN_SUCCESS, P_COMP_SUCCESS_RATE) 
			VALUES ('$p_id', '$date', '$m_id', '$op_id','$up_time', '$run_time', 
				'$idle_time', '$run_success', '$comp_rate') ") 
			or die(mysql_error());
	}
}

function displayData() {

	//Query results
 	$get_machine_column = mysql_query("SHOW COLUMNS FROM MACHINE_LOG") or die(mysql_error());
 	$get_program_column = mysql_query("SHOW COLUMNS FROM PROGRAM_LOG") or die(mysql_error());
 	$get_machine_log = mysql_query("SELECT * FROM MACHINE_LOG") or die(mysql_error());
	$get_program_log = mysql_query("SELECT * FROM PROGRAM_LOG") or die(mysql_error()); 	

    $query_colHead = array($get_machine_column, $get_program_column);
    $query = array($get_machine_log, $get_program_log);

    for($t =0; $t<2; $t++) {
    //DISPLAY QUERIED DATA IN TABLE

        //Display Table Column Header
        echo "<table cellspacing='0' cellpadding='0' border='1'
        	style='border-collapse: collapse; text-align:center;'
        >";
        $col_stack = array();
        while ($col_head = mysql_fetch_assoc($query_colHead[$t])) {
          echo "<th>".$col_head['Field']."</th>";
          array_push($col_stack, $col_head['Field']);
        }

        $col_head_count = count($col_stack); //This is the number of Columns of the table
        while($res=mysql_fetch_array($query[$t])) {
          echo "<tr>";      //Begin row
          for($i=0; $i<$col_head_count; $i++) {
            echo "<td>".$res[$col_stack[$i]]."</td>";   //Insert each row data
          }
          echo "</tr>";     //End row
        }
        echo "</table>";

        echo "<br><br>";
    }
}

generateIDs(1000);
displayData();

//Terminate connection
mysql_close($conn);
?>

