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
			
		$sql_query = "SELECT slider_image 
				FROM tbl_slider_video 
				WHERE cid = ?";
				
		$stmt_slider = $connect->stmt_init();
		if($stmt_slider->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt_slider->bind_param('s', $ID);
			// Execute query
			$stmt_slider->execute();
			// store result 
			$stmt_slider->store_result();
			$stmt_slider->bind_result($previous_slider_image);
			$stmt_slider->fetch();
			$stmt_slider->close();
		}
		
			
		if(isset($_POST['btnEdit'])){
			$slider_name = $_POST['slider_name'];
			
			// get image info
			$menu_image = $_FILES['slider_image']['name'];
			$image_error = $_FILES['slider_image']['error'];
			$image_type = $_FILES['slider_image']['type'];
				
			// create array variable to handle error
			$error = array();
				
			if(empty($slider_name)){
				$error['slider_name'] = " <span class='label label-danger'>Must Insert!</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["slider_image"]["name"]));
			
			if(!empty($menu_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['slider_image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
				
			if(!empty($slider_name) && empty($error['slider_image'])){
					
				if(!empty($menu_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['slider_image']['name']);
					$function = new functions;
					$slider_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink('upload/slider/'."$previous_slider_image");
					
					// upload new image
					$upload = move_uploaded_file($_FILES['slider_image']['tmp_name'], 'upload/slider/'.$slider_image);
					$cat_type = $_POST['cat_type'];
				$cat_url = $_POST['cat_url'];
				$cat_keyowrd  = $_POST['cat_keyowrd'];
				if($cat_type  != 'url'){
				$cat_url = '';
				}
	  
					$sql_query = "UPDATE tbl_slider_video 
							SET slider_name = ?, slider_image = ? ,cat_type = ?, cat_url = ? , cat_keyowrd = ?
							WHERE cid = ?";
							
					$upload_image = $slider_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssssss', 
									$slider_name, 
									$upload_image,
										$cat_type,
								$cat_url,
								$cat_keyowrd,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
						$cat_type = $_POST['cat_type'];
				$cat_url = $_POST['cat_url'];
				if($cat_type  != 'url'){
				$cat_url = '';
				}
				$cat_keyowrd  = $_POST['cat_keyowrd'];
					
					$sql_query = "UPDATE tbl_slider_video 
							SET slider_name = ? ,cat_type = ?, cat_url = ? , cat_keyowrd = ?
							WHERE cid = ?";
					
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssss', 
									$slider_name,
										$cat_type,
										$cat_url, 
										$cat_keyowrd,
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
														* Slider success updated.
														<a href='slider-video.php'>
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
				FROM tbl_slider_video 
				WHERE cid = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['cid'], 
					$data['cat_type'],
					$data['cat_url'],
					$data['slider_name'],
					$data['cat_keyowrd'],
					$data['slider_image']
					
					);
			$stmt->fetch();
			$stmt->close();
		}

		if(isset($_POST['btnCancel'])){
			header("location: slider.php");
		}
		
	?>
	<div class="col-md-12">
		<h1>Edit Slider</h1>
		<?php echo isset($error['update_slider']) ? $error['update_slider'] : '';?>
		<hr />
	</div>
	
	<div class="col-md-5">
		<form method="post"
			enctype="multipart/form-data">
			<label>Slider Name :</label><?php echo isset($error['slider_name']) ? $error['slider_name'] : '';?>
			<input type="text" class="form-control" name="slider_name" value="<?php echo $data['slider_name']; ?>"/>
			<br/>
			<label>Image :</label><?php echo isset($error['slider_image']) ? $error['slider_image'] : '';?>
			<input type="file" name="slider_image" id="slider_image" /><br />
			<img src="upload/slider/<?php echo $data['slider_image']; ?>" width="280" height="190"/>
			<br/><br/>
              <label>Slider Type :</label>
			<select name="cat_type" class="form-control" id="type" >
            <option <?php if($data['cat_type'] == 'keyword'){ echo "selected"; } ?> value="keyword">Keyword</option>
            <option <?php if($data['cat_type'] == 'url'){ echo "selected"; } ?>  value="url">URL</option>
            
            </select>
			<br/>
            <div id="keyword">
            <label>Keyword :</label>
			<input type="text" class="form-control" name="cat_keyowrd" value="<?php echo $data['cat_keyowrd']; ?>"/>
			<br/>
            </div>
			<div id="url">
            <label>Slider URL :</label>
			<input type="text" class="form-control" name="cat_url" value="<?php echo $data['cat_url']; ?>"/>
			<br/>
            </div>
			<input type="submit" class="btn-primary btn" value="Update" name="btnEdit"/>
			<input type="submit" class="btn-danger btn" value="Cancel" name="btnCancel"/>
		</form>
	</div>

	<div class="separator"> </div>
</div>
	
<?php include_once('includes/close_database.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
 $(function() {
    if($('#type').val() == "url")
	 {
		 $('#url').show();
		 $('#keyword').hide();
	}
	else{
	  $('#url').hide();
	  $('#keyword').show();
	  }
    $('#type').change(function(){
     if($('#type').val() == "url")
	 {
		 $('#url').show();
		 $('#keyword').hide();
	}
	else{
	  $('#url').hide();
	  $('#keyword').show();
	  }
    });
});
</script>