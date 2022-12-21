<?php
include 'check_user.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Касири</title>
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
			
		$CashierID = "";
		$Name = "";
        $Age = "";
        $Contact = "";

		function getPosts()
			{
			    $posts = array();
			    $posts[0] = $_POST['CashierID'];
			    $posts[1] = $_POST['Name'];
                $posts[2] = $_POST['Age'];
                $posts[3] = $_POST['Contact'];
			    return $posts;
			}


			$sql = "SELECT * FROM `cashier`";

			$orderBy = 'CashierID';
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
				(isset($_POST['name_like']) && $_POST['name_like']) || 
				(isset($_POST['from_age']) && $_POST['from_age']) || 
				(isset($_POST['to_age']) && $_POST['to_age']) || 
				(isset($_POST['contact_like']) && $_POST['contact_like']) 
				) {
				$sql .= " WHERE "; 
				$where = [];
				if (isset($_POST['from_id']) && $_POST['from_id']) {
					$where[] = "`CashierID` >= " . $_POST['from_id']; 
				}
				if (isset($_POST['to_id']) && $_POST['to_id']) {
					$where[] = "`CashierID` <= " . $_POST['to_id']; 
				}
				if (isset($_POST['from_age']) && $_POST['from_age']) {
					$where[] = "`Age` >= " . $_POST['from_age']; 
				}
				if (isset($_POST['to_age']) && $_POST['to_age']) {
					$where[] = "`Age` <= " . $_POST['to_age'];
				}
				if (isset($_POST['name_like']) && $_POST['name_like']) {
					$where[] = "`Name` like \"%" . $_POST['name_like'] . "%\"";
				}
				if (isset($_POST['contact_like']) && $_POST['contact_like']) {
					$where[] = "`Contact` like \"%" . $_POST['contact_like'] . "%\"";
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
			echo "<thead><tr><th colspan = '4'>Касири</tr></th></thead>\n";
			echo "<thead><tr><td>ID касира</td><td>Ім'я</td><td>Вік</td><td>Номер телефону</td></tr></thead>";
				while ($cashier = $result->fetch_assoc()) {
					echo "<tr>\n";
				    echo "<td>" . $cashier['CashierID'] . "</td><td>". $cashier['Name'] . "</td><td>" . $cashier['Age'] . "</td><td>" . $cashier['Contact'] . "</td>" ;
				    echo "</tr>";
				}

			echo "</table>\n";


			// Search
			if(isset($_POST['search']))
			{
			    $data = getPosts();
			    
			    $search_Query = "SELECT * FROM `cashier` WHERE CashierID = $data[0]";
			    
			    $search_Result = mysqli_query($connect, $search_Query);
			    
			    if($search_Result)
			    {
			        if(mysqli_num_rows($search_Result))
			        {
			            while($row = mysqli_fetch_array($search_Result))
			            {
			                $CashierID = $row['CashierID'];
			                $Name = $row['Name'];
			                $Age = $row['Age'];
                            $Contact = $row['Contact'];
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
			    $insert_Query = "INSERT INTO `cashier`(`CashierID`, `Name`, `Age`, `Contact`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
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
			    $delete_Query = "DELETE FROM `cashier` WHERE `CashierID` = $data[0]";
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
			    $update_Query = "UPDATE `cashier` SET `Contact`='$data[3]',`Age`='$data[2]',`Name`='$data[1]' WHERE `CashierID` = $data[0]";
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
		<form action="cashier.php" method="post"><br><br>
			<input type="number" name = "CashierID" placeholder = "Введіть ID" value="<?php echo $CashierID;?>"><br><br>
			<input type="text" name = "Name" placeholder = "Введіть ім'я" value="<?php echo $Name;?>"><br><br>
			<input type="number" name = "Age" placeholder = "Введіть вік" value="<?php echo $Age;?>"><br><br>
			<input type="number" name = "Contact" placeholder = "Введіть номер телефону" value="<?php echo $Contact;?>"><br><br>
			
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
		<form action="cashier.php" method="post">
			<div>
				<div>ID:</div>
				<div>
					Від <input type="number" name = "from_id">
					До <input type="number" name = "to_id">
				</div><br>
				<div>Ім'я:</div>
				<div>
					<input type="text" name = "name_like" placeholder = "Введіть ім'я">
				</div><br>
				<div>Вік:</div>
				<div>
					Від <input type="number" name = "from_age">
					До <input type="number" name = "to_age">
				</div><br>
				<div>Контакти:</div>
				<div>
					<input type="text" name = "contact_like" placeholder = "Введіть номер телефону">
				</div><br>
				<div>Сортувати:</div>
				<div>
					<select name="order"> 
						<option value="" selected></option>
						<option value="CashierID">CashierID</option>
						<option value="Name">Name</option>
						<option value="Age">Age</option>
						<option value="Contact">Contact</option>
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

