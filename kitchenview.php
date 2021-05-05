<?php  
	
	require_once "config.php";

    ini_set('display_errors',1);
    error_reporting(E_ALL);

    /*** THIS! ***/
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    /*** ^^^^^ ***/

    $page = $_SERVER['PHP_SELF'];
    $sec = "10";
    header("Refresh: $sec; url=$page");

    $false = false;
    $no = 0;

    if (isset($_POST["done"]))
    {
    	$id = mysqli_real_escape_string($link, $_POST["id"]);

    	 $query = "DELETE FROM kitchenview WHERE id =('$id') ";

    	mysqli_query($link , $query);
        unset($query);
    }

    if (isset($_POST["cancel"])) 
    {
        unset($_SESSION["loggedins"]);
        unset($_SESSION["staffid"]);
        unset($_SESSION["staffname"]);
        header("location: index.php");
        exit(); 
    }

?>


<!DOCTYPE html>
<html>
<head>
	<title>Kitchen View</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
	<style type="text/css">
		.wrapper{
            margin-top: 4%;
            margin-left: 20%;
            margin-right: 20%;
        }

        table th {
            text-align: center;
        }

        table td {
            text-align: center;
        }
	</style>
</head>
<body>
	<div class="wrapper">
		<div class="page-header clearfix">
			<h4 class="pull-left">Order To Be Process</h4>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" name="cancel" class="btn btn-default btn-sm pull-right">
                    <i class="zmdi zmdi-power"> Logout</i>
                </button>
           </form>
		</div>

		<?php 

		$sql = "SELECT * FROM kitchenview WHERE availability = ('$false')";

	        if ($result = mysqli_query($link, $sql)) 
	        {
	        	if (mysqli_num_rows($result) > 0) 
	        	{
	        		echo '
	        			<table class="table table-bordered table-striped">
	        				<thead>
			        			<tr>
									<th>#</th>
									<th>Item Name</th>
									<th>Quantity</th>
									<th>Table ID</th>
									<th>Done ?</th>
								</tr>
							</thead>
	        		';
	        		while ($row = mysqli_fetch_array($result)) 
	        		{
	        			$no += 1;
	        			echo '
	        					<tr>
	        						<td align="center">
	        						'.$no.'
	        						</td>
	        						<td align="center">
	        						'.$row['item_name'].'
	        						</td>
	        						<td align="center">
	        						'.$row['quantity'].'
	        						</td>
	        						<td align="center">
	        						'.$row['tableid'].'
	        						</td>
	        						<td align="center">
	        						<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post" >
	        							<input type="hidden" name="id" value="'.$row['id'].'"/>
	        							<button type="submit" name="done" class="btn btn-primary" title="Payed"><span class="glyphicon glyphicon-ok-sign"></span> 
                                        </button>
	        						</form>
	        						</td>
	        					</tr>
	        				';
	        		}
	        		mysqli_free_result($result);
	        		echo '</table>';
	        	}else
	        	{
	                echo "<p class='lead'><em>No records were found.</em></p>";
	            }
	        }else{
	            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	        }


		?>
	</div>



</body>
</html>