<?php
// Memanggil Library Fungsi
include_once("../function/helper.php");

// Retrieve URL Params
$nama_kota_tujuan = isset($_GET['kota_tujuan']) ? $_GET['kota_tujuan'] : '';

// Var for Response Handler
$status = true;
$message = '';
$data = '';

// Validasi Params
if (empty($nama_kota_tujuan)) {
    // Set Response
    $status = false;
    $message = "Params Request Tidak Valid! Kota Tujuan harus sesuai dengan daftar kota pada API Raja Ongkir, Silahkan periksa kembali kota tujuan dan pilihlah Kota Tujuan dari Daftar Rekomendasi yang tampil pada input kota.";
}

// Blok Untuk Menarik Data Ongkir
if ($status) {
    // Var Preparasi
    $id_kota_asal = 457; // kode bersumber dari data rajaongkir
    $id_kota_tujuan = '';
    $kd_kurir = array(
        'jne',
        'tiki'
    ); // kode bersumber dari data rajaongkir
    $info_ongkir_paket_kurir = array();

    // Tarik Data Kota dari rajaongkir API
    $city_list = curl_get("https://api.rajaongkir.com/starter/city");

    // Cek Jika API Sukses
    if (is_array($city_list)) {
        // Tarik Id kota tujuan
        foreach ($city_list['rajaongkir']['results'] as $value) {
            if ($value['city_name'] == $nama_kota_tujuan) {
                $id_kota_tujuan = $value['city_id'];
            }
        }
    } else {
        // Set Response
        $status = false;
        $message = "Terjadi Kesalahan Pada Penarikan ID KOTA dari API!";
    }

    // Proses Lanjutan dalam Blok Penarikan Data Ongkir
    if ($status && !empty($id_kota_tujuan)) {
        // Menarik Data Ongkir dari rajaongkir API
        foreach ($kd_kurir as $key => $value) {
            $info_ongkir = curl_post("https://api.rajaongkir.com/starter/cost", [$id_kota_asal, $id_kota_tujuan, $kd_kurir[$key]]);
            // Cek Status API Response
            if (is_array($info_ongkir)) {
                // Menyiapkan Info onkir per kurir
                $info_ongkir_paket_kurir[$value] = $info_ongkir['rajaongkir']['results'][0]['costs'];
            }
        }

        // Cek Kesiapan Data Info Kurir 
        if (is_array($info_ongkir_paket_kurir)) {
            // Set Response Data
            $data = $info_ongkir_paket_kurir;
        }
    } else {
        // Set Response
        $status = false;
        $message = "Terjadi kesalahan, Kota Tujuan tidak terdaftar pada Database Rajaongkir !";
    }
}

// Set Response Success
if ($status) {
    // Set Response
    $message = "Request Berhasil, Tidak ada kesalahan dalam Penarikan Data Ongkir.";
}

echo json_encode(
    array(
        'status' => $status,
        'message' => $message,
        'data' => $data
    )
);
