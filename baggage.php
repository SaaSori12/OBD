<?php
include 'check_user.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Багажи</title>
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
		<a href = "http://AirTransport/cashier.php">Касири</a>
		</div>
		<div class="menu">
		<a href = "http://AirTransport/tickets.php">Білети</a>
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
			
		$BaggageID = "";
		$Weight = "";
        $Number = "";

		function getPosts()
			{
			    $posts = array();
			    $posts[0] = $_POST['BaggageID'];
			    $posts[1] = $_POST['Weight'];
                $posts[2] = $_POST['Number'];
			    return $posts;
			}


			$sql = "SELECT * FROM `baggage`";

			$orderBy = 'BaggageID';
			if (isset($_POST['order']) && $_POST['order']) {
				$orderBy = $_POST['order']; 
			}
			$orderType = 'ASC';
			if (isset($_POST['order_type']) && $_POST['order_type']) {
				$orderType = $_POST['order_type']; 
			}

			$orderSql =  " ORDER BY `" . $orderBy . "` " . $orderType . " LIMIT 50";
			if (
				(isset($_POST['from_id']) && $_POST['from_id']) || 
				(isset($_POST['to_id']) && $_POST['to_id']) ||  
				(isset($_POST['from_weight']) && $_POST['from_weight']) || 
				(isset($_POST['to_weight']) && $_POST['to_weight']) || 
				(isset($_POST['from_number']) && $_POST['from_number']) || 
				(isset($_POST['to_number']) && $_POST['to_number'])  
				) {
				$sql .= " WHERE "; 
				$where = [];
				if (isset($_POST['from_id']) && $_POST['from_id']) {
					$where[] = "`BaggageID` >= " . $_POST['from_id']; 
				}
				if (isset($_POST['to_id']) && $_POST['to_id']) {
					$where[] = "`BaggageID` <= " . $_POST['to_id']; 
				}
				if (isset($_POST['from_weight']) && $_POST['from_weight']) {
					$where[] = "`Weight` >= " . $_POST['from_weight']; 
				}
				if (isset($_POST['to_weight']) && $_POST['to_weight']) {
					$where[] = "`Weight` <= " . $_POST['to_weight'];
				}
				if (isset($_POST['from_number']) && $_POST['from_number']) {
					$where[] = "`Number` >= " . $_POST['from_number']; 
				}
				if (isset($_POST['to_number']) && $_POST['to_number']) {
					$where[] = "`Number` <= " . $_POST['to_number'];
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
			echo "<thead><tr><th colspan = '3'>Багажи</tr></th></thead>\n";
			echo "<thead><tr><td>ID багажу</td><td>Вага</td><td>Кількість багажу</td></tr></thead>";
				while ($baggage = $result->fetch_assoc()) {
					echo "<tr>\n";
				    echo "<td>" . $baggage['BaggageID'] . "</td><td>". $baggage['Weight'] . "</td><td>" . $baggage['Number'] . "</td>" ;
				    echo "</tr>";
				}

			echo "</table>\n";


			// Search
			if(isset($_POST['search']))
			{
			    $data = getPosts();
			    
			    $search_Query = "SELECT * FROM `baggage` WHERE BaggageID = $data[0]";
			    
			    $search_Result = mysqli_query($connect, $search_Query);
			    
			    if($search_Result)
			    {
			        if(mysqli_num_rows($search_Result))
			        {
			            while($row = mysqli_fetch_array($search_Result))
			            {
			                $BaggageID = $row['BaggageID'];
			                $Weight = $row['Weight'];
			                $Number = $row['Number'];
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
			    $insert_Query = "INSERT INTO `baggage`(`BaggageID`, `Weight`, `Number`) VALUES ('$data[0]','$data[1]','$data[2]')";
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
			    $delete_Query = "DELETE FROM `baggage` WHERE `BaggageID` = $data[0]";
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
			    $update_Query = "UPDATE `baggage` SET `Number`='$data[2]',`Weight`='$data[1]' WHERE `BaggageID` = $data[0]";
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
	<form action="baggage.php" method="post"><br><br>
		<input type="number" name = "BaggageID" placeholder = "Введіть ID" value="<?php echo $BaggageID;?>"><br><br>
		<input type="number" name = "Weight" placeholder = "Введіть вагу" value="<?php echo $Weight;?>"><br><br>
		<input type="number" name = "Number" placeholder = "Введіть кількість" value="<?php echo $Number;?>"><br><br>
		
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
		<form action="baggage.php" method="post">
			<div>
				<div>ID:</div>
				<div>
					Від <input type="number" name = "from_id">
					До <input type="number" name = "to_id">
				</div><br>
				<div>Вага:</div>
				<div>
					Від <input type="number" name = "from_weight">
					До <input type="number" name = "to_weight">
				</div><br>
				<div>Кількість:</div>
				<div>
					Від <input type="number" name = "from_number">
					До <input type="number" name = "to_number">
				</div><br>
				<div>Сортувати:</div>
				<div>
					<select name="order"> 
						<option value="" selected></option>
						<option value="BaggageID">BaggageID</option>
						<option value="Weight">Weight</option>
						<option value="Number">Number</option>
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

