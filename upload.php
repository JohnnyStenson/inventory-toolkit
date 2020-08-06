<?php

	require 'site-auth.php';
	
    // import the Intervention Image Manager Class
    use Intervention\Image\ImageManager;

	if(isset($_POST["auth"]) && $_POST['auth'] == 'legit'){		

		$errors = array();
		$extension = array("jpeg","jpg","png","gif");
		$bytes = 1024;
		$allowedKB = 10000;
		$totalBytes = $allowedKB * $bytes;

		if(isset($_FILES["file"])==false){
			echo "<b>Please, Select the files to upload!!!</b>";
			return;
		}else{
			$uploadThisFile = true;
			$file_name=$_FILES["file"]["name"];
			$file_tmp=$_FILES["file"]["tmp_name"];
			$ext=pathinfo($file_name,PATHINFO_EXTENSION);
			if(!in_array(strtolower($ext),$extension)){
				array_push($errors, "File type is invalid. Name:- ".$file_name);
				$uploadThisFile = false;
			}				

			if($_FILES["file"]["size"] > $totalBytes){
				array_push($errors, "File size must be less than 100KB. Name:- ".$file_name);
				$uploadThisFile = false;
			}

			if(file_exists("uploads/".$_FILES["file"]["name"])){
				array_push($errors, "File is already exist. Name:- ". $file_name);
				$uploadThisFile = false;
			}

			if($uploadThisFile){
				$filename=basename($file_name,$ext);
				$newFileName=$filename.$ext;				
                move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/".$newFileName); 
                
                

                // create an image manager instance with favored driver
                $manager = new ImageManager(array('driver' => 'imagick'));

                // to finally create image instances
                $image = $manager->make('uploads/'.$newFileName)->resize(null, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image->save('uploads/'.$newFileName, 60);
                

                
                if(update_img_url($mySforceConnection, $_POST['id'], $newFileName)){
                    echo "<a href='https://lightning.thunderroadinc.com/inventory/menu.php' style='font-size:30px; margin:50px; padding:20px;display:block; border:5px solid black; color:black; text-align:center; text-decoration:none; '>Go Back</a>";
                }
			}
		}

		$count = count($errors);
		if($count != 0){
			foreach($errors as $error){
				echo $error."<br/>";
			}
		}		
	}

function update_img_url($mySforceConnection, $id, $newFileName){
    $records[0] = new SObject();
	$records[0]->Id = $id;
	$records[0]->fields = array(
    	'Image_for_ListView__c' => 'https://lightning.thunderroadinc.com/inventory/uploads/' . $newFileName,
	);
	$records[0]->type = 'TrackIT__Inventory__c';

	$response = $mySforceConnection->update($records);
	foreach ($response as $result) {
		echo $result->id . " updated<br/>\n";
		echo "<img src='https://lightning.thunderroadinc.com/inventory/uploads/" . $newFileName . "' /><br />";
	}
    return true;
}
?>
