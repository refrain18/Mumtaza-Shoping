<?php

	include_once("../../function/koneksi.php");
	include_once("../../function/helper.php");
	
	session_start();

	$button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
	$pesanan_id = isset($_GET['pesanan_id']) ? $_GET['pesanan_id'] : "";
	

	if($button == "Konfirmasi"){
		
		$user_id = $_SESSION["user_id"];
		$tanggal_transfer = isset($_POST['tanggal_transfer']) ? $_POST['tanggal_transfer'] : false;

		if($_FILES["file"]["name"] != "")
		{
			$bukti_pembayaran = $_FILES["file"]["name"];
			$tipefile = $_FILES["file"]["type"];
			$ukuranfile = $_FILES["file"]["size"];

			if($tipefile != "image/jpeg" and $tipefile != "image/jpg" and $tipefile != "image/png"){
				header("location: ".BASE_URL."index.php?page=my_profile&module=pesanan&action=konfirmasi_pembayaran&konfirmasi_id=$konfirmasi_id&notif=tipefile");
                die();
			}elseif ($ukuranfile >= 1000000) {
				header("location: ".BASE_URL."index.php?page=my_profile&module=pesanan&action=konfirmasi_pembayaran&konfirmasi_id=$konfirmasi_id&notif=ukuranfile");
				die();
			}else{
				move_uploaded_file($_FILES["file"]["tmp_name"], "../../images/bukti_pembayaran/" . $bukti_pembayaran);
			} 
		}

		$queryPembayaran = mysqli_query($koneksi, "INSERT INTO 
													konfirmasi_pembayaran (pesanan_id, bukti_pembayaran, tanggal_transfer)
												   VALUES 
												   	('$pesanan_id', '$bukti_pembayaran', '$tanggal_transfer')");
																			
		if($queryPembayaran){
			mysqli_query($koneksi, "UPDATE pesanan SET status='2' WHERE pesanan_id='$pesanan_id'");
		}
	} else if($button == "Edit Status"){
		$status = $_POST["status"];
		
		mysqli_query($koneksi, "UPDATE pesanan SET status='$status' WHERE pesanan_id='$pesanan_id'");
		
		if($status == "3"){
			$query = mysqli_query($koneksi, "SELECT * FROM pesanan_detail WHERE pesanan_id='$pesanan_id'");
			while($row=mysqli_fetch_assoc($query)){
				$barang_id = $row["barang_id"];
				$quantity = $row["quantity"];
				
				mysqli_query($koneksi, "UPDATE barang SET stok=stok-$quantity WHERE barang_id='$barang_id'");

				// Program hapus kode_barang yang dipesan dari tb kode_barang
				$tempArr = array();
				
				// Ambil kode_item yang tersedia beserta dgn kode_barang nya
				$query_getAvailableKodeBarang = "SELECT kode_item, barang.kode_barang as kd_brg FROM barang JOIN kode_barang ON barang.kode_barang = kode_barang.kode_barang WHERE barang.barang_id = '$barang_id'";
				$execQuery_getAvailableKodeBarang = mysqli_query($koneksi, $query_getAvailableKodeBarang) OR die('Error Query 3: '.mysqli_error($koneksi));
				
				$result_getAvailableKodeBarang = mysqli_fetch_assoc($execQuery_getAvailableKodeBarang);
				$available_kode_item_arr = explode(',', $result_getAvailableKodeBarang['kode_item']);
				$kd_brg = $result_getAvailableKodeBarang['kd_brg'];
				
				// Buat kode item yang dipesan berdasarkan quantity pesanan
				for ($i=0; $i < $quantity ; $i++) { 
					$kode_item_terpesan = $available_kode_item_arr[$i];
					array_push($tempArr, $kode_item_terpesan);
				}

				// Simpan kode item terpesan pada var baru
				$booked_kode_item_arr = $tempArr;

				// Hapus kode_item pesanan dari kode_item yang tersedia
				foreach ($available_kode_item_arr as $k => $v) {
					foreach ($booked_kode_item_arr as $value) {
						if ($v == $value) {
							unset($available_kode_item_arr[$k]);
						}
					}
				}
				// Reset array keys & konversi ke str
				$available_kode_item_arr = array_values($available_kode_item_arr);
				$available_kode_item_str = implode(',', $available_kode_item_arr);

				// Update sisa kode_item yg tersedia setelah dikurangi dengan kode_item pesanan
				$query_updateKodeItem = "UPDATE kode_barang SET kode_item = '$available_kode_item_str' WHERE kode_barang = '$kd_brg'";
				$execQuery_updateKodeItem = mysqli_query($koneksi, $query_updateKodeItem) OR die('Error Query 4: '.mysqli_error($koneksi));
			}
		}
	}

	else if($button == "Delete"){
		mysqli_query($koneksi, "DELETE FROM pesanan WHERE pesanan_id='$pesanan_id'");
	}
	
	header("location:".BASE_URL."index.php?page=my_profile&module=pesanan&action=list");