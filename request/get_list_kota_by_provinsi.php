<?php
include_once("../function/helper.php");

// Retrieve URL Params
$id_province = isset($_GET['id_province']) ? $_GET['id_province'] : "";

// Var Response Handler
$status = true;
$message = '';
$data = '';

// Validasi URL Params
if (empty($id_province)) {
  // Set Response
  $status = false;
  $message = "Params Request Tidak Valid! Input Provinsi harus sesuai dengan data API Raja Ongkir, Silahkan periksa kembali Provinsi dan pilihlah Provinsi dari Daftar Rekomendasi yang tampil pada input Provinsi.";
}

// Blok Persiapan data List Kota berdasarkan Provinsi
if ($status) {
  // Menarik List Kota berasarkan Provinsi dari API Raja ongkir
  $list_kota = curl_get("https://api.rajaongkir.com/starter/city?province={$id_province}");

  // Cek Status API Response
  if (is_array($list_kota)) {
    // Set Response
    $data = $list_kota;
  } else {
    // Set Response
    $status = false;
    $message = 'Terjadi kesalahan dalam penarikan data pada API Raja Ongkir';
  }
}

if ($status) {
  // Set Response
  $message = 'Berhasil menarik data API Raja Ongkir';
}

echo json_encode(
  array(
    'status' => $status,
    'message' => $message,
    'data' => $data
  )
);
