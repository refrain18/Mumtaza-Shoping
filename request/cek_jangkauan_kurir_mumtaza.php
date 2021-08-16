<?php
include_once("../function/helper.php");

// Retrieve URL Params
$nama_kota_tujuan = isset($_GET['kota_tujuan']) ? $_GET['kota_tujuan'] : '';

// Var Response Handler
$status = true;
$message = '';
$data = '';

// Validasi Params
if (empty($nama_kota_tujuan)) {
  // Set Response
  $status = false;
  $message = "Params Request Tidak Valid!";
}

// Blok Pengecekan Jangkauan Kurir
if ($status) {
  // Fake DB Cabang Toko (Data Sebenarnya diambil pada tb_cabang_toko/sejenisnya dari database toko Mumjew)
  $cabang_toko = array(
    'Jakarta Barat',
    'Jakarta Pusat',
    'Jakarta Selatan',
    'Jakarta Timur',
    'Jakarta Utara'
  );

  // Cek Jangkauan Kota Tujuan
  foreach ($cabang_toko as $key => $val) {
    if ($val == $nama_kota_tujuan) {
      $data = 'Terjangkau';
    }
  }
}

// Final Cek Status Request
if ($status) {
  // Set Response
  $message = 'Request Berhasil, Kota Tujuan Terjangkau oleh kurir Mumtaza';

  // Cek Hasil Jangkauan Kurir
  if (empty($data)) {
    // Set Response
    $message = 'Kota tujuan diluar jangkauan Kurir Mumtaza, Toko Mumtaza hanya melakukan COD pada daerah DKI Jakarta. Silahkan ubah Metode Pembayaran ke Transfer Bank, lalu pilih opsi Metode Pengiriman yang tersedia.';
  }
}

echo json_encode(
  array(
    'status' => $status,
    'message' => $message,
    'data' => $data
  )
);
