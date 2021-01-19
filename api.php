<?php

	include 'includes/variables.php';

	DEFINE ('DB_HOST', $host);
	DEFINE ('DB_USER', $user);	 
	DEFINE ('DB_PASSWORD', $pass);
	DEFINE ('DB_NAME', $database);
	// error_reporting(0);
	$mysqli = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL');
	@mysqli_select_db ($mysqli, DB_NAME) OR die ('Could not select the database');

 	mysqli_query($mysqli, "SET NAMES 'utf8'"); 
	//mysql_query('SET CHARACTER SET utf8');
	
	if(isset($_GET['cat_id']))
	{
	    
			//$query="SELECT * FROM tbl_category WHERE cid='".$_GET['cat_id']."' ORDER BY tbl_category.cid DESC";		
			//$resouter = mysql_query($query);
			
			$query="SELECT * FROM tbl_category WHERE cid=".$_GET['cat_id'];
			$res = mysqli_query($mysqli, $query);
			$result = mysqli_fetch_assoc($res);
			if($result['cat_type'] == 'keyword'){
				$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  
				$API_key    = 'AIzaSyBg9BZ2oW0dW83kj_w6pAdytAPgJfTpea4';
				$videoList =json_decode(file_get_contents(
				"https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&q=".urlencode($result['cat_keyowrd'])."&key=AIzaSyBg9BZ2oW0dW83kj_w6pAdytAPgJfTpea4&maxResults=10&order=Relevance", false, stream_context_create($arrContextOptions)),true);
 
				//json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&q=php&key=AIzaSyCR5In4DZaTP6IEZQ0r1JceuvluJRzQNLE&maxResults=10&order=Relevance'), false, stream_context_create($arrContextOptions));
//echo "<pre>";
//print_r($videoList);		
//exit;
$ytlist = array();
$i = 0;
foreach($videoList['items'] as $item){
	//echo $item['snippet']['title'];		
	//echo "<br>";
	if(isset($item['id']['videoId'])){
		$ytlist[$i]['youtube_id'] = $item['id']['videoId'];
	}else{
	    $ytlist[$i]['youtube_id'] = $item['id']['playlistId'];;
	}
	$ytlist[$i]['title'] = $item['snippet']['title'];
	$ytlist[$i]['image'] = $item['snippet']['thumbnails']['medium']['url'];
	$ytlist[$i]['description'] = $item['snippet']['description'];
	$ytlist[$i]['time'] = $item['snippet']['publishedAt'];
    
$i++;
	}
				
				$set['yt_list'] = '1';
				$result['play_list'] = $ytlist;
				$set['YourVideosChannel'] = $result;
				echo $val= str_replace('\\/', '/', json_encode($set));
			exit;
			}else{
				$query="SELECT * FROM tbl_category c,tbl_gallery n WHERE timestamp(DATE_SUB(n.viewdate, INTERVAL 5 MINUTE)) <= CONVERT_TZ(Now(),'+00:00','+12:00') 			AND c.cid=n.cat_id and c.cid='".$_GET['cat_id']."' ORDER BY n.viewdate DESC, n.id DESC";			
				$resouter = mysql_query($query);
			}
	}
	else if(isset($_GET['video_slider_id']))
	{
			$query="SELECT * FROM tbl_slider_video WHERE cid=".$_GET['video_slider_id'];
			$res = mysql_query($query);
			$result = mysql_fetch_assoc($res);
			if($result['cat_type'] == 'keyword'){
				$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  
				$API_key    = 'AIzaSyBg9BZ2oW0dW83kj_w6pAdytAPgJfTpea4';
				//echo "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&q=".$result['cat_keyowrd']."&key=AIzaSyCR5In4DZaTP6IEZQ0r1JceuvluJRzQNLE&maxResults=10&order=Relevance";
				
				$videoList =json_decode(file_get_contents(
				"https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&q=".urlencode($result['cat_keyowrd'])."&key=AIzaSyBg9BZ2oW0dW83kj_w6pAdytAPgJfTpea4&maxResults=10&order=Relevance", false, stream_context_create($arrContextOptions)),true);
 

$ytlist = array();
$i = 0;
foreach($videoList['items'] as $item){
	//echo $item['snippet']['title'];		
	//echo "<br>";
	if(isset($item['id']['videoId'])){
		$ytlist[$i]['youtube_id'] = $item['id']['videoId'];
	}else{
	    $ytlist[$i]['youtube_id'] = $item['id']['playlistId'];;
	}
	$ytlist[$i]['title'] = $item['snippet']['title'];
	$ytlist[$i]['image'] = $item['snippet']['thumbnails']['medium']['url'];
	$ytlist[$i]['description'] = $item['snippet']['description'];
	$ytlist[$i]['time'] = $item['snippet']['publishedAt'];
    
$i++;
	}
				
				$set['yt_list'] = '1';
				$result['play_list'] = $ytlist;
				$set['YourVideosChannel'] = $result;
				echo $val= str_replace('\\/', '/', json_encode($set));
			exit;
			}else{
				$query="SELECT * FROM tbl_slider_video WHERE cid=".$_GET['video_slider_id'];			
				$resouter = mysql_query($query);
			//exit;
			}
	}
	else if(isset($_GET['id']))
	{		
			$id = $_GET['id'];

			$query="SELECT * FROM tbl_category c, tbl_gallery n WHERE timestamp(DATE_SUB(n.viewdate, INTERVAL 5 MINUTE)) <= CONVERT_TZ(Now(),'+00:00','+12:00')  		AND c.cid = n.cat_id && n.id = '$id'";					
			$resouter = mysql_query($query);
			
	}
	else if(isset($_GET['latest']))
	{
			$limit=$_GET['latest'];	 	
			
			$query="SELECT * FROM tbl_category c,tbl_gallery n WHERE timestamp(DATE_SUB(n.viewdate, INTERVAL 5 MINUTE)) <= CONVERT_TZ(Now(),'+00:00','+12:00') 		AND c.cid=1	AND c.cid=n.cat_id ORDER BY n.viewdate DESC, n.id DESC LIMIT $limit";			
			$resouter = mysql_query($query);
	}
	else if(isset($_GET['latest_youtube'])){
	   $videoList =json_decode(file_get_contents(
				"https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&key=AIzaSyBg9BZ2oW0dW83kj_w6pAdytAPgJfTpea4&maxResults=10&order=date", false, stream_context_create($arrContextOptions)),true);

$ytlist = array();
$i = 0;
foreach($videoList['items'] as $item){
	if(isset($item['id']['videoId'])){
		$ytlist[$i]['youtube_id'] = $item['id']['videoId'];
	}else{
	    $ytlist[$i]['youtube_id'] = $item['id']['playlistId'];;
	}
	$ytlist[$i]['title'] = $item['snippet']['title'];
	$ytlist[$i]['image'] = $item['snippet']['thumbnails']['medium']['url'];
	$ytlist[$i]['description'] = $item['snippet']['description'];
	$ytlist[$i]['time'] = $item['snippet']['publishedAt'];

	$i++;
}
$set['yt_list'] = '1';
$result['play_list'] = $ytlist;
$set['YourVideosChannel'] = $result;
echo $val= str_replace('\\/', '/', json_encode($set));
exit;
	}
	else if(isset($_GET['featured_category']))
	{ 
			$query="SELECT * FROM featured_category";		
			$resouter = mysql_query($query);
			while($data  = mysql_fetch_assoc($resouter)){
				$id[] =  $data['cat_id'];
				$id[] =  $data['cat_id_2'];
				$id[] =  $data['cat_id_3'];
				$id[] =  $data['cat_id_4'];

			}
			//print_r($id);
			$query="SELECT * FROM tbl_category where cid IN ('".implode("','",$id)."')";		
			$resouter = mysql_query($query);
			//print_r($id);
			//exit;
	}
	else if(isset($_GET['apps_details']))
	{ 
			$query="SELECT * FROM tbl_settings WHERE id='1'";		
			$resouter = mysql_query($query);
	}
	else if(isset($_GET['slider']))
	{ 
			$query="SELECT * FROM tbl_slider";		
			$resouter = mysqli_query($mysqli, $query);
	}
	else if(isset($_GET['slider_video']))
	{ 
			$query="SELECT * FROM tbl_slider_video ORDER BY cid DESC";		
			$resouter = mysql_query($query);
	}
	else
	{	
			$query="SELECT * FROM tbl_category ORDER BY cid DESC";			
			$resouter = mysqli_query($mysqli,$query);
			$set = array();
     		$total_records = mysqli_num_rows($resouter);
    		if($total_records >= 1){
				while ($link = mysqli_fetch_array($resouter)){
				$query_1="SELECT * FROM featured_category where cat_id = '".$link['cid']."'";			
				$resouter_1 = mysqli_query($mysqli, $query_1);
				$total_records_1 = mysqli_num_rows($resouter_1);
				$query_2="SELECT * FROM featured_category where cat_id_2 = '".$link['cid']."'";			
				$resouter_2 = mysqli_query($mysqli, $query_2);
				$total_records_2 = mysqli_num_rows($resouter_2);
				$query_3="SELECT * FROM featured_category where cat_id_3 = '".$link['cid']."'";			
				$resouter_3 = mysqli_query($mysqli, $query_3);
				$total_records_3 = mysqli_num_rows($resouter_3);
				$query_4="SELECT * FROM featured_category where cat_id_4 = '".$link['cid']."'";			
				$resouter_4 = mysqli_query($mysqli, $query_4);
				$total_records_4 = mysqli_num_rows($resouter_4);
				if($total_records_1 == 0 && $total_records_2 == 0 && $total_records_3 == 0 && $total_records_4 == 0){
					$set['YourVideosChannel'][] = $link;
				}
		}
    }
     
     echo $val= str_replace('\\/', '/', json_encode($set));
	 exit;
	}
     
    $set = array();
     
    $total_records = mysqli_num_rows($resouter);
    
	if($total_records >= 1){
     
      while ($link = mysqli_fetch_array($resouter)){
		if(isset($_GET['cat_id'])){
			$set['yt_list'] = '0';
		}
		
        $set['YourVideosChannel'][] = $link;
      }
    }
     
     echo $val= str_replace('\\/', '/', json_encode($set));
	 	 
	 
?>