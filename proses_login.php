<?php

    include_once("function/koneksi.php");
    include_once("function/helper.php");

    $id_member = isset($_POST['id_member']) ? $_POST['id_member'] : "";
    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : "";
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT user.user_id, user.nama, user.level, member.id_member FROM user 
        LEFT JOIN member ON user.user_id = member.user_id 
        WHERE (user.email='$email' OR (member.id_member = '$id_member' AND member.status='on')) 
        AND user.password='$password' 
        AND user.status='on'") OR die(mysqli_error($koneksi));

        // SELECT * FROM user LEFT JOIN member ON user.user_id = member.user_id WHERE (user.email='kubil@mumtaza.com' OR (member.id_member = '' AND member.status='on')) AND user.password='2263e5c29ab3670d281edadf9e6d2b13' AND user.status='on'

    if(mysqli_num_rows($query) == 0){
        header("location:".BASE_URL . "index.php?page=login&notif=true");
    }else{

        $row = mysqli_fetch_assoc($query);

        session_start();

        // Get User Data
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['level'] = $row['level'];
        
        // Get ID Member
        $_SESSION['id_member'] = $row['id_member'];
        
        if(isset($_SESSION["proses_pesanan"])){
            unset($_SESSION["proses_pesanan"]);
            header("location: ".BASE_URL."data-pemesan.html");
        }else{    
            header("location: ".BASE_URL."index.php?page=my_profile&module=pesanan&action=list");
        }
    }
?>    