<?php
	include_once('includes/connect_database.php');
	include_once('functions.php'); 
?>
<div id="content" class="container col-md-12">
	<?php 
		if(isset($_POST['btnAdd'])){
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
					
			if($image_error > 0){
				$error['slider_image'] = " <span class='label label-danger'>You're not insert images!!</span>";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($extension, $allowedExts))){
			
				$error['slider_image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
			}
			
			if(!empty($slider_name) && empty($error['slider_image'])){
				
				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['slider_image']['name']);
				$function = new functions;
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
				// upload new image
				$upload = move_uploaded_file($_FILES['slider_image']['tmp_name'], 'upload/slider/'.$menu_image);
		
				// insert new data to menu table
				$cat_type = $_POST['cat_type'];
				$cat_url = $_POST['cat_url'];
				if($cat_type  != 'url'){
				$cat_url = '';
				}
				$cat_keyowrd  = $_POST['cat_keyowrd'];
				$sql_query = "INSERT INTO tbl_slider_video (slider_name, slider_image ,cat_type , cat_url , cat_keyowrd)
						VALUES(?, ?,?,? ,?)";
				
				$upload_image = $menu_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('sssss', 
								$slider_name, 
								$upload_image,
								$cat_type,
								$cat_url,
								$cat_keyowrd
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
			<label>Slider Name :</label><?php echo isset($error['slider_name']) ? $error['slider_name'] : '';?>
			<input type="text" class="form-control" name="slider_name"/>
			<br/>
			<label>Image :</label><?php echo isset($error['slider_image']) ? $error['slider_image'] : '';?>
			<input type="file" name="slider_image" id="slider_image" />
			<br/>
            <label>Slider Type :</label>
			<select name="cat_type" class="form-control"  id="type">
            <option value="keyword">Keyword</option>
            <option value="url">URL</option>
            
            </select>
			<br/>
			<div id="keyword">
            <label>Keyword :</label>
			<input type="text" class="form-control" name="cat_keyowrd" value=""/>
			<br/>
            </div>
           <div id="url">
            <label>Slider URL :</label>
			<input type="text" class="form-control" name="cat_url"/>
			<br/>
            </div>
			<input type="submit" class="btn-primary btn" value="Submit" name="btnAdd"/>
			<input type="reset" class="btn-warning btn" value="Clear"/>
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