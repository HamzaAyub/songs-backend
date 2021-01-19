<?php
	include_once('includes/connect_database.php');
	include_once('functions.php'); 
?>

<div id="content" class="container col-md-12">
	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store slider data
		$slider_data = array();
			
		$sql_query = "SELECT image 
				FROM tbl_slider 
				WHERE sid = ?";
				
		$stmt_slider = $connect->stmt_init();
		if($stmt_slider->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt_slider->bind_param('s', $ID);
			// Execute query
			$stmt_slider->execute();
			// store result 
			$stmt_slider->store_result();
			$stmt_slider->bind_result($previous_image);
			$stmt_slider->fetch();
			$stmt_slider->close();
		}
		
			
		if(isset($_POST['btnEdit'])){
			// get image info
			$menu_image = $_FILES['image']['name'];
			$image_error = $_FILES['image']['error'];
			$image_type = $_FILES['image']['type'];
				
			// create array variable to handle error
			$error = array();
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["image"]["name"]));
			
			if(!empty($menu_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
			$link = $_POST['link'];	
			if( empty($error['image'])){
					
				if(!empty($menu_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
					$function = new functions;
					$image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink('upload/slider/'."$previous_image");
					
					// upload new image
					$upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/slider/'.$image);
					$cat_type = $_POST['cat_type'];
				$link = $_POST['link'];
				
					$sql_query = "UPDATE tbl_slider 
							SET  image = ? , link = ?
							WHERE sid = ?";
							
					$upload_image = $image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sss', 
									$upload_image,
									$link,
								$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
						
					
					$sql_query = "UPDATE tbl_slider 
							SET link = ?
							WHERE sid = ?";
					
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ss', 
										$link, 
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}
				
				// check update result
				if($update_result){
					$error['update_slider'] = " <h4><div class='alert alert-success'>
														* Category success updated.
														<a href='slider.php'>
														<i class='fa fa-check fa-lg'></i>
														</a></div>
												  </h4>";
				}else{
					$error['update_slider'] = " <span class='label label-danger'>Failed to update slider.</span>";
				}
			}
				
		}
			
		// create array variable to store previous data
		$data = array();
		
		$sql_query = "SELECT * 
				FROM tbl_slider 
				WHERE sid = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['sid'], 
					$data['image'],
					$data['link']
					
					);
			$stmt->fetch();
			$stmt->close();
		}

		if(isset($_POST['btnCancel'])){
			header("location: slider.php");
		}
		
	?>
	<div class="col-md-12">
		<h1>Edit Category</h1>
		<?php echo isset($error['update_slider']) ? $error['update_slider'] : '';?>
		<hr />
	</div>
	
	<div class="col-md-5">
		<form method="post"
			enctype="multipart/form-data">
			
			<label>Image :</label><?php echo isset($error['image']) ? $error['image'] : '';?>
			<input type="file" name="image" id="image" /><br />
			<img src="upload/slider/<?php echo $data['image']; ?>" width="280" height="190"/>
			  			<br/>
            <div id="url">
            <label>Link :</label>
			<input type="text" class="form-control" name="link" value="<?php echo $data['link']; ?>"/>
			<br/>
            </div>
			<input type="submit" class="btn-primary btn" value="Update" name="btnEdit"/>
			<input type="submit" class="btn-danger btn" value="Cancel" name="btnCancel"/>
		</form>
	</div>

	<div class="separator"> </div>
</div>
	
<?php include_once('includes/close_database.php'); ?>
