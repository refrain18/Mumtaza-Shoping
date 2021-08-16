<?php
    include("../../function/koneksi.php");
    include("../../function/helper.php");

    $button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
    $no_member = isset($_GET['no_member']) ? $_GET['no_member'] : false;

    $idmember_lama = isset($_POST['idmember_lama']) ? $_POST['idmember_lama'] : false;
    $id_member = isset($_POST['id_member']) ? $_POST['id_member'] : false;
	$userid_lama = isset($_POST['userid_lama']) ? $_POST['userid_lama'] : false;
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : false;
	$status = isset($_POST['status']) ? $_POST['status'] : false;
     
    if($button == "Add")
    {
        $query = mysqli_query($koneksi, "SELECT * FROM member WHERE user_id='$user_id'");
        $queryid = mysqli_query($koneksi, "SELECT * FROM member WHERE id_member='$id_member'");
        if(mysqli_num_rows($query) == 1){
            header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=form&notif=nlink&notif=gagal_add");
            die();    
        }elseif(mysqli_num_rows($queryid) == 1){
            header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=form&no_member=$no_member&notif=idmember");
            die(); 
        }else {
            mysqli_query($koneksi, "INSERT INTO member (id_member, user_id, status) VALUES ('$id_member', '$user_id', '$status')")OR die(mysqli_error($koneksi));
        }
    } elseif($button == "Update"){

        // Menyimpan no member
        $no_member = $_GET['no_member'];

        // Prepare Var untuk Query
        $update_member = '';
        $update_idmember = '';

        // Cek apakah ada perubahan id_member
        if ($idmember_lama != $id_member) {
            $update_member = "user_id='$user_id', ";
        }

        // Cek apakah ada perubahan member
        if ($userid_lama != $user_id) {
            $update_idmember = "id_member='$id_member', ";
        }

        // Cek Kesamaan Nama member
        $query = mysqli_query($koneksi, "SELECT * FROM member WHERE member.user_id='$user_id'");
        if(mysqli_num_rows($query) == 1){
            $v = mysqli_fetch_array($query);
            if($v['user_id'] != $userid_lama){
                $status_notif = "gagal_update"; // Status Notif Handle
                header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=form&no_member=$no_member&notif=$status_notif");
                die();
            }
        }

        // Cek Kesamaan Nama member
        $query = mysqli_query($koneksi, "SELECT * FROM member WHERE id_member='$id_member'");
        if(mysqli_num_rows($query) == 1){
            $v = mysqli_fetch_array($query);
            if($v['user_id'] != $userid_lama){
                header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=form&no_member=$no_member&notif=idmember");
                die();
            }
        }

        mysqli_query($koneksi, "UPDATE member SET $update_idmember
                                        $update_member
                                        status='$status'
                                        WHERE no_member='$no_member'")OR die(mysqli_error($koneksi));
        $status_notif = "sukses_update"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=list&notif=$status_notif");
        die();
    } else if($button == "Delete"){
        mysqli_query($koneksi, "DELETE FROM member WHERE no_member='$no_member'")OR die(mysqli_error($koneksi));
        $status_notif = "sukses_delete"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=list&notif=$status_notif");
        die();
    }
    
     
     
    header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=list&notif=sukses_add");
?>