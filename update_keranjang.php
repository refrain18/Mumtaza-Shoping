<?php

	include_once("function/koneksi.php");

	$barang_id = $_POST["barang_id"];
	$value = $_POST["value"];
	$status = false;
	$pesan = "";

	$query = mysqli_query($koneksi, "SELECT stok FROM barang WHERE barang_id='$barang_id'");
	$row = mysqli_fetch_array($query);

	if($row['stok'] > $value) {
		$status = true;

		session_start();

		$keranjang = $_SESSION["keranjang"];

		$keranjang[$barang_id]["quantity"] = $value;
		$_SESSION["keranjang"] = $keranjang;
	}else{
		$status = false;
		$pesan = 'stok hanya ' . $row['stok'];
	}

	$arr = ['status' => $status, 'pesan' => $pesan];
	$json = json_encode($arr);

	echo $json;