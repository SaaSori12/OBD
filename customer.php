<?php
include 'check_user.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Користувачі</title>
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
		<a href = "http://AirTransport/tickets.php">Білети</a>
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
			
		$PassagerID = "";
		$PasportID = "";
		$Name = "";
		$Sex = "";
        $Age = "";
        $Contact = "";

		function getPosts()
			{
			    $posts = array();
			    $posts[0] = $_POST['PassagerID'];
			    $posts[1] = $_POST['PasportID'];
			    $posts[2] = $_POST['Name'];
			    $posts[3] = $_POST['Sex'];
                $posts[4] = $_POST['Age'];
                $posts[5] = $_POST['Contact'];
			    return $posts;
			}


			$sql = "SELECT * FROM `people`";

			$orderBy = 'PassagerID';
			if (isset($_POST['order']) && $_POST['order']) {
				$orderBy = $_POST['order']; 
			}
			$orderType = 'ASC';
			if (isset($_POST['order_type']) && $_POST['order_type']) {
				$orderType = $_POST['order_type']; 
			}

			$orderSql =  " ORDER BY `" . $orderBy . "` " . $orderType . " LIMIT 50";
			if (
				(isset($_POST['from_passagerid']) && $_POST['from_passagerid']) || 
				(isset($_POST['to_passagerid']) && $_POST['to_passagerid']) ||
				(isset($_POST['from_pasportid']) && $_POST['from_pasportid']) || 
				(isset($_POST['to_pasportid']) && $_POST['to_pasportid']) || 
				(isset($_POST['name_like']) && $_POST['name_like']) || 
				(isset($_POST['sex_like']) && $_POST['sex_like']) || 
				(isset($_POST['from_age']) && $_POST['from_age']) || 
				(isset($_POST['to_age']) && $_POST['to_age']) || 
				(isset($_POST['contact_like']) && $_POST['contact_like']) 
				) {
				$sql .= " WHERE "; 
				$where = [];
				if (isset($_POST['from_passagerid']) && $_POST['from_passagerid']) {
					$where[] = "`PassagerID` >= " . $_POST['from_passagerid']; 
				}
				if (isset($_POST['to_passagerid']) && $_POST['to_passagerid']) {
					$where[] = "`PassagerID` <= " . $_POST['to_passagerid']; 
				}
				if (isset($_POST['to_pasportid']) && $_POST['from_pasportid']) {
					$where[] = "`PasportID` >= " . $_POST['from_pasportid']; 
				}
				if (isset($_POST['to_pasportid']) && $_POST['to_pasportid']) {
					$where[] = "`PasportID` <= " . $_POST['to_pasportid']; 
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
				if (isset($_POST['sex_like']) && $_POST['sex_like']) {
					$where[] = "`Sex` like \"%" . $_POST['sex_like'] . "%\"";
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
			echo "<thead><tr><th colspan = '6'>Користувачі</tr></th></thead>\n";
			echo "<thead><tr><td>ID пасажира</td><td>ID паспорту</td><td>Ім'я</td><td>Стать</td><td>Вік</td><td>Номер телефону</td></tr></thead>";
				while ($people = $result->fetch_assoc()) {
					echo "<tr>\n";
				    echo "<td>" . $people['PassagerID'] . "</td><td>". $people['PasportID'] . "</td><td>" . $people['Name'] . "</td><td>" . $people['Sex'] . "</td><td>" . $people['Age'] . "</td><td>" . $people['Contact'] . "</td>" ;
				    echo "</tr>";
				}

			echo "</table>\n";

			// Search
			if(isset($_POST['search']))
			{
			    $data = getPosts();
			    
			    $search_Query = "SELECT * FROM `people` WHERE PassagerID = $data[0]";
			    
			    $search_Result = mysqli_query($connect, $search_Query);
			    
			    if($search_Result)
			    {
			        if(mysqli_num_rows($search_Result))
			        {
			            while($row = mysqli_fetch_array($search_Result))
			            {
			                $PassagerID = $row['PassagerID'];
			                $PasportID = $row['PasportID'];
			                $Name = $row['Name'];
			                $Sex = $row['Sex'];
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
			    $insert_Query = "INSERT INTO `people`(`PassagerID`, `PasportID`, `Name`, `Sex`, `Age`, `Contact`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]')";
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
			    $delete_Query = "DELETE FROM `people` WHERE `PassagerID` = $data[0]";
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
			    $update_Query = "UPDATE `people` SET `Contact`='$data[5]',`Age`='$data[4]',`Sex`='$data[3]',`Name`='$data[2]',`PasportID`='$data[1]' WHERE `PassagerID` = $data[0]";
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
	<form action="customer.php" method="post"><br><br>
		<input type="number" name = "PassagerID" placeholder = "Введіть ID пасажира" value="<?php echo $PassagerID;?>"><br><br>
        <input type="number" name = "PasportID" placeholder = "Введіть ID паспорту" value="<?php echo $PasportID;?>"><br><br>
		<input type="text" name = "Name" placeholder = "Введіть ім'я" value="<?php echo $Name;?>"><br><br>
		<input type="text" name = "Sex" placeholder = "Введіть стать" value="<?php echo $Sex;?>"><br><br>
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
		<form action="customer.php" method="post">
			<div>
				<div>ID пасажира:</div>
				<div>
					Від <input type="number" name = "from_passagerid">
					До <input type="number" name = "to_passagerid">
				</div><br>
				<div>ID паспорта:</div>
				<div>
					Від <input type="number" name = "from_pasportid">
					До <input type="number" name = "to_pasportid">
				</div><br>
				<div>Ім'я:</div>
				<div>
					<input type="text" name = "name_like" placeholder = "Введіть ім'я">
				</div><br>
				<div>Стать:</div>
				<div>
					<input type="text" name = "sex_like" placeholder = "Введіть стать">
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
						<option value="PassagerID">PassagerID</option>
						<option value="PasportID">PasportID</option>
						<option value="Name">Name</option>
						<option value="Sex">Sex</option>
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

