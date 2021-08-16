<?php
    include("../../function/koneksi.php");   
	include("../../function/helper.php");   
	
	$button = isset($_POST['button']) ? $_POST['button'] : false;
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
    
	$nama = isset($_POST['nama']) ? $_POST['nama'] : false;
    $email = isset($_POST['email']) ? $_POST['email'] : false;
    $email_lama = isset($_POST['email_lama']) ? $_POST['email_lama'] : false;
	$phone = isset($_POST['phone']) ? $_POST['phone'] : false;
	$provinsi = isset($_POST['provinsi']) ? $_POST['provinsi'] : false;
	$kota = isset($_POST['kota']) ? $_POST['kota'] : false;
	$kode_pos = isset($_POST['kode_pos']) ? $_POST['kode_pos'] : false;
	$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : false;
	$password = !empty($_POST['password']) ? md5($_POST['password']) : false;
    $repassword = !empty($_POST['repassword']) ? md5($_POST['repassword']) : false;

	if ($button == "Update") {

    if(!preg_match("/^[a-zA-Z\s]*$/",$nama)){
        header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&notif=nama");
        die();
    }elseif(!preg_match("/^[0-9]*$/",$phone)){
        header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&notif=phone");
        die();    
    }elseif(strlen($kode_pos) < 5){
        header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&notif=kodepos");
        die();
    }else{  

        // Prepare Var
        $update_email = '';
        $update_pass = '';

        // Cek apakah ada perubahan email
        if ($email_lama != $email) {
            $update_email = "email = '$email', ";
        }
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
        if(mysqli_num_rows($query) == 1){
            $v = mysqli_fetch_array($query);
            if($v['email'] != $email_lama){
            header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&notif=email");
            die();
            }
        }
        // validasi password
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        // Cek apakah ada perubahan password
        if ($password) {
            if ($password != $repassword) {
                header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&pesan_err=pass_tidak_sama");
                die();    
            }
            $update_pass = ", password = '$password' ";
            if (!$uppercase || !$lowercase || !$number || strlen($password) < 8){
                header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&notif=passwordChar");
                die();
            }
        }
        $sql = mysqli_query($koneksi, "UPDATE 
                                        user 
                                        SET 
                                        nama='$nama',
                                        $update_email
                                        phone='$phone',
                                        provinsi='$provinsi',
                                        kota='$kota',
                                        kode_pos='$kode_pos',
                                        alamat='$alamat'
                                        $update_pass
                                        WHERE user_id='$user_id'") or die(mysqli_error($koneksi));
        if ($sql) {
            header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&notif=sukses_update");
            die(); 									                   
        } else {
            die('Gagal Update, Terjadi kesalahan pada Query!');
        }
    }
}											   
?>