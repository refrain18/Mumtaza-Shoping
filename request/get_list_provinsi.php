<?php
// Memanggil Library Fungsi
include_once("../function/helper.php");

// Var Response Handler
$status = true;
$message = '';
$data = '';

// Blok Persiapan data List Povinsi
if ($status) {
    // Menarik list provinsi dari API Raja ongkir
    $list_provinsi = curl_get("https://api.rajaongkir.com/starter/province");

    // Cek Status API Response
    if (is_array($list_provinsi)) {
        // Set Response
        $data = $list_provinsi;
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
