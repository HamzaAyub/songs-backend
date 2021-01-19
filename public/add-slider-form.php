<?php
	include_once('includes/connect_database.php');
	include_once('functions.php'); 
?>
<div id="content" class="container col-md-12">
	<?php 
		if(isset($_POST['btnAdd'])){
			$link = $_POST['link'];
			// get image info
			$menu_image = $_FILES['image']['name'];
			$image_error = $_FILES['image']['error'];
			$image_type = $_FILES['image']['type'];
			
			// create array variable to handle error
			$error = array();
			
			if(empty($name)){
				$error['name'] = " <span class='label label-danger'>Must Insert!</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["image"]["name"]));
					
			if($image_error > 0){
				$error['image'] = " <span class='label label-danger'>You're not insert images!!</span>";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($extension, $allowedExts))){
			
				$error['image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
			}
			
			if(!empty($link) && empty($error['image'])){
				
				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
				$function = new functions;
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
				// upload new image
				$upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/slider/'.$menu_image);
		
				// insert new data to menu table
				$link = $_POST['link'];
				
				$sql_query = "INSERT INTO tbl_slider (link, image)
						VALUES(?, ?)";
				
				$upload_image = $menu_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ss', 
								$link,
								$upload_image
					);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
				
				if($result){
					$error['add_slider'] = " <h4><div class='alert alert-success'>
														* New Slider success added.
														<a href='slider.php'>
														<i class='fa fa-check fa-lg'></i>
														</a></div>
												  </h4>";
				}else{
					$error['add_slider'] = " <span class='label label-danger'>Failed add slider</span>";
				}
			}
			
		}

		if(isset($_POST['btnCancel'])){
			header("location: slider.php");
		}

	?>
	<div class="col-md-12">
		<h1>Add Slider</h1>
		<?php echo isset($error['add_slider']) ? $error['add_slider'] : '';?>
		<hr />
	</div>
	
	<div class="col-md-5">
		<form method="post"
			enctype="multipart/form-data">
			
			<label>Image :</label><?php echo isset($error['image']) ? $error['image'] : '';?>
			<input type="file" name="image" id="image" />
			<br/>
            <label>Link :</label><?php echo isset($error['link']) ? $error['link'] : '';?>
			<input type="text" class="form-control" name="link"/>
			<br/>			<br/>
           <input type="submit" class="btn-primary btn" value="Submit" name="btnAdd"/>
			<input type="reset" class="btn-warning btn" value="Clear"/>
			<input type="submit" class="btn-danger btn" value="Cancel" name="btnCancel"/>
		</form>
	</div>

	<div class="separator"> </div>
</div>
	
<?php include_once('includes/close_database.php'); ?>
