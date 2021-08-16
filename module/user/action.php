<?php
    include("../../function/koneksi.php");   
	include("../../function/helper.php");   
	
	$button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
	$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
	
	$nama = isset($_POST['nama']) ? $_POST['nama'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
    $email_lama = isset($_POST['email_lama']) ? $_POST['email_lama'] : false;
	$phone = isset($_POST['phone']) ? $_POST['phone'] : false;
	$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : false;
	$level = isset($_POST['level']) ? $_POST['level'] : false;
	$status = isset($_POST['status']) ? $_POST['status'] : false;

	if ($button == "Update") {

			if(!preg_match("/^[a-zA-Z\s]*$/",$nama)){
				header("location: ".BASE_URL."index.php?page=my_profile&module=user&action=form&user_id=$user_id&notif=nama");
				die();
			}elseif(!preg_match("/^[0-9]*$/",$phone)){
				header("location: ".BASE_URL."index.php?page=my_profile&module=user&action=form&user_id=$user_id&notif=phone");
				die();    
			}else{

			// Menyimpan id Kategori
			$user_id = $_GET['user_id'];


			// Prepare Var
			$update_email = '';
			
			// Cek apakah ada perubahan email
			if ($email_lama != $email) {
				$update_email = "email = '$email', ";
			}
		
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
        if(mysqli_num_rows($query) == 1){
            $v = mysqli_fetch_array($query);
            if($v['email'] != $email_lama){
            header("location: ".BASE_URL."index.php?page=my_profile&module=user&action=form&user_id=$user_id&notif=email");
            die();
            }
        }
		mysqli_query($koneksi, "UPDATE user SET nama='$nama',
											   $update_email
											   phone='$phone',
											   alamat='$alamat',
											   level='$level',
											   status='$status'
											   WHERE user_id='$user_id'")or die(mysqli_error($koneksi));
		header("location: ".BASE_URL."index.php?page=my_profile&module=user&action=list&notif=sukses_update");
		die(); 									   
		}
	}											   

	if($button == "Delete"){
		mysqli_query($koneksi, "DELETE FROM user WHERE user_id='$user_id'");
		header("location: ".BASE_URL."index.php?page=my_profile&module=user&action=list&notif=sukses_delete");
            die(); 
	}
	
    header("location: ".BASE_URL."index.php?page=my_profile&module=user&action=list&notif=sukses");

?>