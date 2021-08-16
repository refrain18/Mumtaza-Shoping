<?php

	include_once("../../function/koneksi.php");
	include_once("../../function/helper.php");
	
	session_start();

	$button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
	$pesanan_id = isset($_GET['pesanan_id']) ? $_GET['pesanan_id'] : "";
	
	if($button == "Konfirmasi"){
		
		$user_id = $_SESSION["user_id"];

		$nomor_rekening = isset($_POST['nomor_rekening']) ? $_POST['nomor_rekening'] : false;
		$nama_account = isset($_POST['nama_account']) ? $_POST['nama_account'] : false;
		$bukti_pembayaran = isset($_POST['bukti_pembayaran']) ? $_POST['bukti_pembayaran'] : false;
		$tanggal_transfer = isset($_POST['tanggal_transfer']) ? $_POST['tanggal_transfer'] : false;

		$queryPembayaran = mysqli_query($koneksi, "INSERT INTO 
													konfirmasi_pembayaran (pesanan_id, nomor_rekening, nama_account, bukti_pembayaran, tanggal_transfer)
												   VALUES 
												   	('$pesanan_id', '$nomor_rekening', '$nama_account', '$bukti_pembayaran', '$tanggal_transfer')");
																			
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
			}
		}
	}

	else if($button == "Delete"){
		mysqli_query($koneksi, "DELETE FROM pesanan WHERE pesanan_id='$pesanan_id'");
	}
	
	header("location:".BASE_URL."index.php?page=my_profile&module=pesanan&action=list");