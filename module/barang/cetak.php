<?php 
// include autoloader
require_once '../../libs/dompdf/autoload.inc.php';

include_once("../../function/koneksi.php");
include_once("../../function/helper.php");
// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

// Get Laporan Data
$barang_id = isset($_GET['barang_id']) ? $_POST['barang_id'] : false;
$brand_cetak = isset($_POST['brand_cetak']) ? $_POST['brand_cetak'] : false;
// var_dump($brand_cetak);die;
$stk_a = $_POST['stk_a'];
$stk_b = $_POST['stk_b'];

// echo "$brand_cetak dan $stk_a dan $stk_b";
// die();

$where="";
if ($stk_a && $stk_b && $brand_cetak == false) {
    $where = "WHERE stok BETWEEN '$stk_a' AND '$stk_b' ORDER BY stok ASC";
    $stok = "$stk_a s/d $stk_b";
    $brand_cetak="Semua";
}elseif (($stk_a && $stk_b) && $brand_cetak == true) {
    $where = "WHERE stok BETWEEN '$stk_a' AND '$stk_b' AND banner_branded='$brand_cetak' ORDER BY stok ASC";
    $stok = "$stk_a s/d $stk_b";
    $brand_cetak = $brand_cetak;
}elseif ($stk_a == 0 && $stk_b == 0 && $brand_cetak) {
    $where = "WHERE banner_branded='$brand_cetak'";
    $stok ="Semua";   
}else{
    $where="";
    $stok ="Semua";
    $brand_cetak="Semua";
}
// var_dump($brand_cetak);die;

$query = mysqli_query($koneksi, "SELECT count(barang.barang_id) as jumlah, banner_branded FROM barang JOIN banner_branded ON barang.bb_id=banner_branded.bb_id $where") OR die(mysqli_error($koneksi));
$data = mysqli_fetch_assoc($query);
$jml_data = $data['jumlah'];

// Style Laporan
$style = '
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">';

// Header laporan default
$isiLaporan = '<center><h1>Laporan Stok</h1></center><br><hr></br>';

// Blok pengolahan konten stok
$isiLaporan .= '
  <div id="tabel-laporan">
    <table>
      <tr>
        <th>Brand</th>
        <td> : </td>
        <td>'.$brand_cetak.'</td>
      </tr>
      <tr>
        <th>Stok</th>
        <td> : </td>
        <td>'.$stok.'</td>
      </tr>		
      <tr>
        <th>Jumlah Data</th>
        <td> : </td>
        <td>'.$jml_data.'</td>
      </tr>		
    </table>
  </div>';


  $query = mysqli_query($koneksi, "SELECT  barang_id, nama_barang, harga, harga_distributor, banner_branded, stok, barang.status FROM barang JOIN banner_branded ON barang.bb_id=banner_branded.bb_id $where") OR die(mysqli_error($koneksi));
  

  $no = 1;
  $order = '';
  while($row=mysqli_fetch_assoc($query)){
    
    $barang_id = $row['barang_id'];
    $nama_barang = $row['nama_barang'];
    $harga = $row['harga'];
    $harga_distributor = $row['harga_distributor'];
    $brand = $row['banner_branded'];
    $stok = $row['stok'];
    $status = $row['status'];
    
    $order .= "
      <tr>
        <td class='no'>$no</td>
        <td class='kiri'>$barang_id</td>
        <td class='kanan'>$nama_barang</td>
        <td class='kanan'>$brand</td>
        <td class='tengah'>".rupiah($harga_distributor)."</td>
        <td class='tengah'>".rupiah($harga)."</td>
        <td class='kanan'>$stok</td>
        <td class='kanan'>$status</td>
      </tr>";

    $no++;
  }

  $isiLaporan .= '
    <table class="table table-sm">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Kode</th>
          <th scope="col">Nama</th>
          <th scope="col">Brand</th>
          <th scope="col">Harga Distributor</th>
          <th scope="col">Harga Jual</th>
          <th scope="col">Stok</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        '.$order.'
      </tbody>
    </table>';

$dompdf->loadHtml('
  <html>
    <head>
      <title>Cetak Laporan Stok</title>
      '.$style.'
      <style>
        #tabel-laporan {
          font-size: 12pt;
          margin: 20px 0px;
        }
      </style>
    </head>
    <body>
      '.$isiLaporan.'
    </body>
  </html>'
);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('laporan_stok.pdf', ['Attachment' => 0]);

?>