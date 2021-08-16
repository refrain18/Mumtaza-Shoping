<?php

    include_once("function/koneksi.php");
    include_once("function/helper.php");

    $level = "customer";
    $status = "on";
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $provinsi = $_POST['provinsi'];
    $kota = $_POST['kota'];
    $kode_pos = $_POST['kode_pos'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];
    
    // validasi password
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    // validasi no telepon

    unset($_POST['password']);
    unset($_POST['re_password']);
    $dataForm = http_build_query($_POST);

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
    
    if(empty($nama_lengkap) || empty($email) || empty($phone) || empty($alamat) || empty($password)){
        header("location: ".BASE_URL."index.php?page=register&notif=require&$dataForm");
    }elseif(!preg_match("/^[0-9]*$/",$phone)){
        header("location: ".BASE_URL."index.php?page=register&notif=phone&$dataForm");    
    }elseif($password != $re_password){
        header("location: ".BASE_URL."index.php?page=register&notif=password&$dataForm");
    }else if (!$uppercase || !$lowercase || !$number || strlen($password) < 8){
        header("location: ".BASE_URL."index.php?page=register&notif=passwordChar&$dataForm");
    }elseif(mysqli_num_rows($query) == 1){
        header("location: ".BASE_URL."index.php?page=register&notif=email&$dataForm");
    }elseif(!preg_match("/^[a-zA-Z\s]*$/",$nama_lengkap)){
        header("location: ".BASE_URL."index.php?page=register&notif=nama_lengkap&$dataForm");
    }elseif(strlen($kode_pos) < 5){
        header("location: ".BASE_URL."index.php?page=register&notif=kodepos&$dataForm");         
    }else{ 
       $password = md5($password);
       mysqli_query($koneksi, "INSERT INTO user (level, nama, email, alamat, phone, provinsi, kota, kode_pos, password, status)
                                           VALUES ('$level', '$nama_lengkap', '$email', '$alamat', '$phone', '$provinsi', '$kota', '$kode_pos', '$password', '$status')")OR die(mysqli_error($koneksi));

       header("location: ".BASE_URL."index.php?page=login");                                    
    }
?>