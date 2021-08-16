<?php
    include("../../function/koneksi.php");
    include("../../function/helper.php");

    $button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
    $banner_id = isset($_GET['banner_id']) ? $_GET['banner_id'] : "";
    
	$banner = isset($_POST['banner']) ? $_POST['banner'] : false;
	$barang_id = isset($_POST['barang_id']) ? $_POST['barang_id'] : false;
    $link = "index.php?page=detail&barang_id=".$barang_id;
	$status = isset($_POST['status']) ? $_POST['status'] : false;
     
    $edit_gambar = "";
	
 
    if($_FILES["file"]["name"] != "")
    {
        $nama_file = $_FILES["file"]["name"];
        $tipefile = $_FILES["file"]["type"];
        $ukuranfile = $_FILES["file"]["size"];
        if($tipefile != "image/jpeg" and $tipefile != "image/jpg" and $tipefile != "image/png"){
            header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=form&banner_id=$banner_id&notif=tipefile");
            die();
        }elseif ($ukuranfile >= 1000000) {
            header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=form&banner_id=$banner_id&notif=ukuranfile");
            die();
        }else{
            move_uploaded_file($_FILES["file"]["tmp_name"], "../../images/slide/" . $nama_file);
        } 
        $edit_gambar  = ", gambar='$nama_file'";
    }
     
    if($button == "Add"){
        $query = mysqli_query($koneksi, "SELECT * FROM banner WHERE link='$link'");
        if(mysqli_num_rows($query) == 1){
            header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=form&notif=nlink&notif=gagal_add");
            die();    
        } else {
            mysqli_query($koneksi, "INSERT INTO banner (barang_id, banner, link, gambar, status) VALUES ('$barang_id', '$banner', '$link', '$nama_file', '$status')"); 
        }
    }elseif($button == "Update"){
        $query = mysqli_query($koneksi, "SELECT * FROM banner WHERE link='$link'");
        if(mysqli_num_rows($query) == 1){
            header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=form&notif=nlink&notif=gagal_update");
            die();    
        } else {
        mysqli_query($koneksi, "UPDATE banner SET barang_id='$barang_id', 
                                        banner='$banner',
                                        link='$link'
                                        $edit_gambar,
                                        status='$status'
                                        WHERE banner_id='$banner_id'");
        header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=list&notif=nlink&notif=sukses_update");
        die();                                 
        }
    }    
    else if($button == "Delete"){
        mysqli_query($koneksi, "DELETE FROM banner WHERE banner_id='$banner_id'");
        header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=list&notif=nlink&notif=sukses_delete");
            die(); 
	}
     
     
    header("location: ".BASE_URL."index.php?page=my_profile&module=banner&action=list&notif=sukses_add");
?>