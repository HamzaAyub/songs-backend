<?php
	include_once('includes/connect_database.php');
	include_once('functions.php'); 
?>

<div id="content" class="container col-md-12">
	<?php 
		$sql_query = "SELECT cid, category_name 
				FROM tbl_category 
				ORDER BY cid ASC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($category_data['cid'], 
				$category_data['category_name']
				);
				
		}
		$stmt_category2 = $connect->stmt_init();
		if($stmt_category2->prepare($sql_query)) {	
			// Execute query
			$stmt_category2->execute();
			// store result 
			$stmt_category2->store_result();
			$stmt_category2->bind_result($category_data['cid'], 
				$category_data['category_name']
				);
				
		}
		$stmt_category3 = $connect->stmt_init();
		if($stmt_category3->prepare($sql_query)) {	
			// Execute query
			$stmt_category3->execute();
			// store result 
			$stmt_category3->store_result();
			$stmt_category3->bind_result($category_data['cid'], 
				$category_data['category_name']
				);
				
		}
		$stmt_category4 = $connect->stmt_init();
		if($stmt_category4->prepare($sql_query)) {	
			// Execute query
			$stmt_category4->execute();
			// store result 
			$stmt_category4->store_result();
			$stmt_category4->bind_result($category_data['cid'], 
				$category_data['category_name']
				);
				
		}
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = 1;
		}
		
		// create array variable to store category data
		//$category_data = array();
		if(isset($_POST['btnEdit'])){
			$cat_id = $_POST['cat_id'];
			$cat_id_2 = $_POST['cat_id_2'];
			$cat_id_3 = $_POST['cat_id_3'];
			$cat_id_4 = $_POST['cat_id_4'];
			
			// get image info
			
			
			// common image file extensions
			$sql_query = "UPDATE featured_category 
							SET cat_id = ? ,cat_id_2 = ?, cat_id_3 = ? , cat_id_4 = ?
							WHERE fid = ?";
					
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssss', 
									$cat_id,
									$cat_id_2,
									$cat_id_3,
									$cat_id_4,
									$ID);
						// Execute query
						$stmt->execute();
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				
				
				// check update result
				if($update_result){
					$error['update_category'] = " <h4><div class='alert alert-success'>
														* Category success updated.
														<a href='featured_category.php'>
														<i class='fa fa-check fa-lg'></i>
														</a></div>
												  </h4>";
				}else{
					$error['update_category'] = " <span class='label label-danger'>Failed to update category.</span>";
				}
			
		}	
		// create array variable to store previous data
		$data = array();
		
		$sql_query = "SELECT * 
				FROM featured_category 
				WHERE fid = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['fid'], 
					$data['cat_id'],
					$data['cat_id_2'],
					$data['cat_id_3'],
					$data['cat_id_4']
			);
			$stmt->fetch();
			$stmt->close();
		}

		if(isset($_POST['btnCancel'])){
			header("location: featured_category.php");
		}
		
	?>
	<div class="col-md-12">
		<h1>Edit Featured Category</h1>
		<?php echo isset($error['update_category']) ? $error['update_category'] : '';?>
		<hr />
	</div>
	
	<div class="col-md-5">
		<form method="post"
			enctype="multipart/form-data">
			<label>Featured Category 1 :</label>
			<select name="cat_id" class="form-control">
			<?php while($stmt_category->fetch()){ 
				if($category_data['cid'] == $data['cat_id']){?>
					<option value="<?php echo $category_data['cid']; ?>" selected ><?php echo $category_data['category_name']; ?></option>
				<?php }else{ ?>
					<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
				<?php }} ?>
		</select>
			<br/>
			<label>Featured Category 2 :</label>
			<select name="cat_id_2" class="form-control">
			<?php while($stmt_category2->fetch()){ 
				if($category_data['cid'] == $data['cat_id_2']){?>
					<option value="<?php echo $category_data['cid']; ?>" selected ><?php echo $category_data['category_name']; ?></option>
				<?php }else{ ?>
					<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
				<?php }} ?>
		</select>
			<br/><br/>
              <label>Featured Category 3 :</label>
			<select name="cat_id_3" class="form-control">
			<?php while($stmt_category3->fetch()){ 
				if($category_data['cid'] == $data['cat_id_3']){?>
					<option value="<?php echo $category_data['cid']; ?>" selected ><?php echo $category_data['category_name']; ?></option>
				<?php }else{ ?>
					<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
				<?php }} ?>
		</select>
			<br/>
            
            <label>Featured Category 4 :</label>
			
			<select name="cat_id_4" class="form-control">
			<?php while($stmt_category4->fetch()){ 
				if($category_data['cid'] == $data['cat_id_4']){?>
					<option value="<?php echo $category_data['cid']; ?>" selected ><?php echo $category_data['category_name']; ?></option>
				<?php }else{ ?>
					<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
				<?php }} ?>
		</select>
			<br/>
            
			
			<input type="submit" class="btn-primary btn" value="Update" name="btnEdit"/>
			<input type="submit" class="btn-danger btn" value="Cancel" name="btnCancel"/>
		</form>
	</div>

	<div class="separator"> </div>
</div>
	
<?php include_once('includes/close_database.php'); ?>
