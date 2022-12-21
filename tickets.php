<?php
include 'check_user.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Квитки</title>
	<link rel="stylesheet" href="css/decor.css">
</head>
<body>
<nav>
	<p>Вітаємо <?=isset($user['name']) ? $user['name'] : "" ?></p>
	MENU:
	<br>
		<div class="menu">
		<a href = "http://AirTransport/guards.php">Охоронці</a>
		</div>
		<div class="menu">
		<a href = "http://AirTransport/baggage.php">Багажи</a>
		</div>
		<div class="menu">
		<a href = "http://AirTransport/cashier.php">Касири</a>
		</div>
		<div class="menu">
		<a href = "http://AirTransport/customer.php">Користувачі</a>
		</div>
		<div class="menu">
		<a href = "http://AirTransport/validation-form/exit.php">Вихід із системи</a>
		</div>
	</nav>
	<br>
	
	<?php 
		
		$host = "localhost";
		$user = "root";
		$password = "";
		$database = "AirTrasport";

		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		// connect to mysql database
		try{
		    $connect = mysqli_connect($host, $user, $password, $database);
		} catch (mysqli_sql_exception $ex) {
		    echo 'Error';
		}
			
		$TickID = "";
		$PassangerPlaceID = "";
        $FlightID = "";
        $Type = "";

		function getPosts()
			{
			    $posts = array();
			    $posts[0] = $_POST['TickID'];
			    $posts[1] = $_POST['PassangerPlaceID'];
                $posts[2] = $_POST['FlightID'];
                $posts[3] = $_POST['Type'];
			    return $posts;
			}


			$sql = "SELECT * FROM `tickets`";

			$orderBy = 'TickID';
			if (isset($_POST['order']) && $_POST['order']) {
				$orderBy = $_POST['order']; 
			}
			$orderType = 'ASC';
			if (isset($_POST['order_type']) && $_POST['order_type']) {
				$orderType = $_POST['order_type']; 
			}

			$orderSql =  " ORDER BY `" . $orderBy . "` " . $orderType . " LIMIT 50";
			if (
				(isset($_POST['from_tickid']) && $_POST['from_tickid']) || 
				(isset($_POST['to_tickid']) && $_POST['to_tickid']) || 
				(isset($_POST['from_passagerplaceid']) && $_POST['from_passagerplaceid']) || 
				(isset($_POST['to_passagerplaceid']) && $_POST['to_passagerplaceid']) || 
				(isset($_POST['from_flightid']) && $_POST['from_flightid']) || 
				(isset($_POST['to_flightid']) && $_POST['to_flightid']) ||
				(isset($_POST['type_like']) && $_POST['type_like']) 
				) {
				$sql .= " WHERE "; 
				$where = [];
				if (isset($_POST['from_tickid']) && $_POST['from_tickid']) {
					$where[] = "`TickID` >= " . $_POST['from_tickid']; 
				}
				if (isset($_POST['to_tickid']) && $_POST['to_tickid']) {
					$where[] = "`TickID` <= " . $_POST['to_tickid']; 
				}
				if (isset($_POST['from_passagerplaceid']) && $_POST['from_passagerplaceid']) {
					$where[] = "`PassangerPlaceID` >= " . $_POST['from_passagerplaceid']; 
				}
				if (isset($_POST['to_passagerplaceid']) && $_POST['to_passagerplaceid']) {
					$where[] = "`PassangerPlaceID` <= " . $_POST['to_passagerplaceid'];
				}
				if (isset($_POST['from_flightid']) && $_POST['from_flightid']) {
					$where[] = "`FlightID` >= " . $_POST['from_flightid']; 
				}
				if (isset($_POST['to_flightid']) && $_POST['to_flightid']) {
					$where[] = "`FlightID` <= " . $_POST['to_flightid'];
				}
				if (isset($_POST['type_like']) && $_POST['type_like']) {
					$where[] = "`Type` like \"%" . $_POST['type_like'] . "%\"";
				}
				$sql .= implode(" AND ", $where);
			}
			$sql .= $orderSql;

			// print_r($_POST);
			// echo "sql: " . $sql;

				if (!$result = mysqli_query($connect, $sql)) {
			    echo "Извините, возникла проблема в работе сайта.";
			    exit;
			}

			echo "<table>\n";
			echo "<thead><tr><th colspan = '4'>Білети</tr></th></thead>\n";
			echo "<thead><tr><td>ID білету</td><td>ID місця пасажира</td><td>ID рейсу</td><td>Тип квитка</td></tr></thead>";
				while ($tickets = $result->fetch_assoc()) {
					echo "<tr>\n";
				    echo "<td>" . $tickets['TickID'] . "</td><td>". $tickets['PassangerPlaceID'] . "</td><td>" . $tickets['FlightID'] . "</td><td>" . $tickets['Type'] . "</td>" ;
				    echo "</tr>";
				}

			echo "</table>\n";


			// Search
			if(isset($_POST['search']))
			{
			    $data = getPosts();
			    
			    $search_Query = "SELECT * FROM `tickets` WHERE TickID = $data[0]";
			    
			    $search_Result = mysqli_query($connect, $search_Query);
			    
			    if($search_Result)
			    {
			        if(mysqli_num_rows($search_Result))
			        {
			            while($row = mysqli_fetch_array($search_Result))
			            {
			                $TickID = $row['TickID'];
			                $PassangerPlaceID = $row['PassangerPlaceID'];
			                $FlightID = $row['FlightID'];
                            $Type = $row['Type'];
			            }
			        }else{
			            echo 'No Data For This Id';
			        }
			    } else{
			        echo 'Result Error';
			    }
			}

			

			// Insert
			if(isset($_POST['insert']))
			{
			    $data = getPosts();
			    $insert_Query = "INSERT INTO `tickets`(`TickID`, `PassangerPlaceID`, `FlightID`, `Type`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
			    try{
			        $insert_Result = mysqli_query($connect, $insert_Query);
			        
			        if($insert_Result)
			        {
			            if(mysqli_affected_rows($connect) > 0)
			            {
			                echo 'Data Inserted';
			            }else{
			                echo 'Data Not Inserted';
			            }
			        }
			    } catch (Exception $ex) {
			        echo 'Error Insert '.$ex->getMessage();
			    }
			}


			// Delete
			if(isset($_POST['delete']))
			{
			    $data = getPosts();
			    $delete_Query = "DELETE FROM `tickets` WHERE `TickID` = $data[0]";
			    try{
			        $delete_Result = mysqli_query($connect, $delete_Query);
			        
			        if($delete_Result)
			        {
			            if(mysqli_affected_rows($connect) > 0)
			            {
			                echo 'Data Deleted';
			            }else{
			                echo 'Data Not Deleted';
			            }
			        }
			    } catch (Exception $ex) {
			        echo 'Error Delete '.$ex->getMessage();
			    }
			}


			// Edit
			if(isset($_POST['update']))
			{
			    $data = getPosts();
			    $update_Query = "UPDATE `tickets` SET `Type`='$data[3]',`FlightID`='$data[2]',`PassangerPlaceID`='$data[1]' WHERE `TickID` = $data[0]";
			    try{
			        $update_Result = mysqli_query($connect, $update_Query);
			        
			        if($update_Result)
			        {
			            if(mysqli_affected_rows($connect) > 0)
			            {
			                echo 'Data Updated';
			            }else{
			                echo 'Data Not Updated';
			            }
			        }
			    } catch (Exception $ex) {
			        echo 'Error Update '.$ex->getMessage();
			    }
			}
			if($type == 1){

		?>
<div class="form1">
	<form action="tickets.php" method="post"><br><br>
		<input type="number" name = "TickID" placeholder = "Введіть ID білету" value="<?php echo $TickID;?>"><br><br>
		<input type="number" name = "PassangerPlaceID" placeholder = "Введіть ID місця пасажира" value="<?php echo $PassangerPlaceID;?>"><br><br>
		<input type="number" name = "FlightID" placeholder = "Введіть ID літака" value="<?php echo $FlightID;?>"><br><br>
        <input type="text" name = "Type" placeholder = "Введіть тип квитка" value="<?php echo $Type;?>"><br><br>
		
		<div>
			<input type="submit" name = "insert" value="Додати">
			<input type="submit" name = "update" value="Редагувати">
			<input type="submit" name = "delete" value="Видалити">
			<input type="submit" name = "search" value="Пошук">
		</div>
	</form>
</div>
	<?php
			}
	?>	
<!--filter-->
<div class="form2">
		<form action="tickets.php" method="post">
			<div>
				<div>Id білету:</div>
				<div>
					Від <input type="number" name = "from_tickid">
					До <input type="number" name = "to_tickid">
				</div><br>
				<div>ID місця пасажира:</div>
				<div>
					Від <input type="number" name = "from_passagerplaceid">
					До <input type="number" name = "to_passagerplaceid">
				</div><br>
				<div>ID літака:</div>
				<div>
					Від <input type="number" name = "from_flightid">
					До <input type="number" name = "to_flightid">
				</div><br>
				<div>Тип білету:</div>
				<div>
					<input type="text" name = "type_like" placeholder = "Введіть тип квитка">
				</div><br>
				<div>Сортувати:</div>
				<div>
					<select name="order"> 
						<option value="" selected></option>
						<option value="TickID">TickID</option>
						<option value="PassagerPlaceID">PassagerPlaceID</option>
						<option value="FlightID">FlightID</option>
						<option value="Type">Type of ticket</option>
					</select>
				</div><br>
				<div>Сортувати по:</div>
				<div>
					<select name="order_type">
						<option value="" selected></option>
						<option value="DESC">Від більшого до меншого</option>
						<option value="ASC">Від меншого до більшого</option>
					</select>
				</div><br>
				<div>
					<input type="submit" name = "filter" value="Фільтрувати">
				</div>
			</div><br>
		</form>
	</div>
</body>
</html>

