<?php include("database.php");
   // Creates ZIP file
	if(isset($_POST['createzip'])){
		$post = $_POST;		
		$file_folder = "uploads/docs/";	// folder to load files
		if(extension_loaded('zip')){	// Checking ZIP extension is available
			if(isset($post['files']) and count($post['files']) > 0){	// Checking files are selected
				$zip = new ZipArchive();			// Load zip library	
				$zip_name = date("now").".zip";			// Zip name
				if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){		// Opening zip file to load files
					$alert = 'show';
					$class = urlencode('alert-danger');
					$alertmsg = urlencode('ZIP creation failed at this time.');
					header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_list");
				}
				foreach($post['files'] as $file){				
					$zip->addFile($file_folder.$file);			// Adding files into zip
				}
				$zip->close();
				if(file_exists($zip_name)){
					// push to download the zip
					header('Content-type: application/zip');
					header('Content-Disposition: attachment; filename="'.$zip_name.'"');
					readfile($zip_name);
					// remove zip file is exists in temp path
					unlink($zip_name);
				}
				
			}else
				$alert = 'show';
				$class = urlencode('alert-danger');
				$alertmsg = urlencode('Please select file to zip.');
				header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_list");
		}else
			$alert = 'show';
			$class = urlencode('alert-danger');
			$alertmsg = urlencode('You dont have ZIP extension.');
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_list");	
	}	
?>