<?php

    include_once("../../function/koneksi.php");
    include_once("../../function/helper.php");

    $button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
    $barang_id = isset($_GET['barang_id']) ? $_GET['barang_id'] : "";

    $nama_barang = isset($_POST['nama_barang']) ? $_POST['nama_barang'] : false;
    $kategori_id = isset($_POST['kategori_id']) ? $_POST['kategori_id'] : false;
    $bb_id = isset($_POST['bb_id']) ? $_POST['bb_id'] : false;
    $spesifikasi = isset($_POST['spesifikasi']) ? $_POST['spesifikasi'] : false;
    $status = isset($_POST['status']) ? $_POST['status'] : false;
    $harga_distributor = isset($_POST['harga_distributor']) ? $_POST['harga_distributor'] : false;
    $harga = isset($_POST['harga']) ? $_POST['harga'] : false;
    $diskon = isset($_POST['diskon']) ? $_POST['diskon'] : false;
    $stok = isset($_POST['stok']) ? $_POST['stok'] : false;

    $update_gambar = "";
    
    // Nilai Default Notif
    $status_notif = "";

    if(!empty($_FILES["file"]["name"])){
        $nama_file = $_FILES["file"]["name"];
        $tipefile = $_FILES["file"]["type"];
        $ukuranfile = $_FILES["file"]["size"];
        if($tipefile != "image/jpeg" and $tipefile != "image/jpg" and $tipefile != "image/png"){
            header("location: ".BASE_URL."index.php?page=my_profile&module=barang&action=form&barang_id=$barang_id&notif=tipefile");
            die();
        }elseif ($ukuranfile >= 1000000) {
            header("location: ".BASE_URL."index.php?page=my_profile&module=barang&action=form&barang_id=$barang_id&notif=ukuranfile");
            die();
        }else{
            move_uploaded_file($_FILES["file"]["tmp_name"], "../../images/barang/".$nama_file);
        }
        $update_gambar = ", gambar='$nama_file'";
    }
    if($button == "Add"){
        // Membuat kode barang berdasarkan nama barang
        $kode_barang = kode_produk_generator($nama_barang);
        $query_getAvailableKodeBarang = "SELECT kode_barang FROM kode_barang";
        $execQuery_getAvailableKodeBarang = mysqli_query($koneksi, $query_getAvailableKodeBarang) OR die('Error Query 1: '.mysqli_error($koneksi));

        if (mysqli_num_rows($execQuery_getAvailableKodeBarang) != 0) {
            while ($data = mysqli_fetch_assoc($execQuery_getAvailableKodeBarang)) {
                // Cek jika kode_barang sudah tersedia
                if ($data['kode_barang'] == $kode_barang) {
                    // Regenerate kode_barang baru
                    $randomNumbStr = rand(1, 9);
                    $kode_barang = $kode_barang.$randomNumbStr;
                }
            }
        }

        // Membuat kode item berdasarkan jumlah stok
        $kode_item_arr = kode_item_generator($stok);
        $kode_item_str = implode(',', $kode_item_arr);

        $query_insertKodeBarangDanKodeItem = "INSERT INTO kode_barang (kode_barang, kode_item) VALUES ('$kode_barang', '$kode_item_str');";
        mysqli_query($koneksi, $query_insertKodeBarangDanKodeItem) OR die('Error Query 2: '.mysqli_error($koneksi));

        mysqli_query($koneksi, "INSERT INTO barang (kode_barang, nama_barang, kategori_id, bb_id, spesifikasi, gambar, harga, harga_distributor, diskon, stok, status) 
            VALUES ('$kode_barang', '$nama_barang', '$kategori_id', '$bb_id', '$spesifikasi', '$nama_file', '$harga', '$harga_distributor', '$diskon', '$stok', '$status')") OR die(mysqli_error($koneksi));

    }
    else if($button == "Update"){
        $query_getCurrentStok = "SELECT stok FROM barang WHERE barang_id = '$barang_id'";
        $execQuery_getCurrentStok = mysqli_query($koneksi, $query_getCurrentStok) OR die('Error Query 3: '.mysqli_error($koneksi));
        $res_getCurrentStok = mysqli_fetch_assoc($execQuery_getCurrentStok);
        $currentStok = $res_getCurrentStok['stok'];

        $jml_stok_tambahan = $stok - $currentStok;

        $query_getCurrentKodeItem = "SELECT kode_barang.kode_item, kode_barang.kode_barang AS kd_brg FROM barang JOIN kode_barang ON barang.kode_barang = kode_barang.kode_barang WHERE barang.barang_id = '$barang_id'";
        $execQuery_getCurrentKodeItem = mysqli_query($koneksi, $query_getCurrentKodeItem) OR die('Error Query 4: '.mysqli_error($koneksi));
        $res_getCurrentKodeItem = mysqli_fetch_assoc($execQuery_getCurrentKodeItem);
        $currentKodeItem = $res_getCurrentKodeItem['kode_item']; 
        $kd_brg = $res_getCurrentKodeItem['kd_brg'];
        
        $kode_item_tambahan_arr = kode_item_generator($jml_stok_tambahan, $currentKodeItem);
        $kode_item_tambahan_str = implode(',', $kode_item_tambahan_arr);
        $kode_item_final = "$currentKodeItem,$kode_item_tambahan_str";

        mysqli_query($koneksi, "UPDATE kode_barang SET kode_item = '$kode_item_final' WHERE kode_barang = '$kd_brg'") OR die('Error Query 5: '.mysqli_error($koneksi));

        mysqli_query($koneksi, "UPDATE barang SET kategori_id='$kategori_id',
            bb_id='$bb_id',
            nama_barang='$nama_barang',
            spesifikasi='$spesifikasi',
            harga='$harga',
            harga_distributor='$harga_distributor',
            diskon='$diskon',
            stok='$stok',
            status='$status'
            $update_gambar WHERE barang_id='$barang_id'
        ");
        $status_notif = "sukses_update"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=barang&action=list&notif=$status_notif");
        die();                                          
    }

    else if($button == "Delete"){
        mysqli_query($koneksi, "DELETE FROM barang WHERE barang_id='$barang_id'");
        $status_notif = "sukses_delete"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=barang&action=list&notif=$status_notif");
        die();
    }


    header("location:".BASE_URL."index.php?page=my_profile&module=barang&action=list&notif=sukses_add");
?>