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
$pesanan_id = isset($_GET["pesanan_id"]) ? $_GET["pesanan_id"] : '';

$tgl_a = $_POST['tgl_a'];
$tgl_b = $_POST['tgl_b'];

$where="";
if ($tgl_a && $tgl_b) {
    $where = "WHERE tanggal_pemesanan BETWEEN '$tgl_a' AND '$tgl_b'";
    $tgl = date('d F y',strtotime($tgl_a)) . '&nbsp;s/d&nbsp;' . date('d F y',strtotime($tgl_b));
}else {
    $tgl ="Semua"; 
}


$query = mysqli_query($koneksi, "SELECT count(pesanan.pesanan_id) as jumlah, pesanan_detail.*, 
                                                                                    pesanan.tanggal_pemesanan,
                                                                                    pesanan.nama_penerima,
                                                                                    pesanan.metode_pengiriman,
                                                                                    pesanan.metode_pembayaran,
                                                                                    pesanan.status,
                                                                                    barang.nama_barang  
                                FROM pesanan_detail JOIN pesanan ON pesanan_detail.pesanan_id=pesanan.pesanan_id JOIN barang ON pesanan_detail.barang_id=barang.barang_id $where") OR die(mysqli_error($koneksi));
$data = mysqli_fetch_assoc($query);
$jml_data = $data['jumlah'];

// Style Laporan
$style = '
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">';

// Header laporan default
$isiLaporan = '<center><h1>Laporan Pemesanan</h1></center><br><hr></br>';

// Blok pengolahan konten stok
$isiLaporan .= '
  <div id="tabel-laporan">
    <table>
      <tr>
        <th>Tanggal Pemesanan</th>
        <td> : </td>
        <td>'.$tgl.'</td>
      </tr>	
      <tr>
        <th>Jumlah Data</th>
        <td> : </td>
        <td>'.$jml_data.'</td>
      </tr>		
    </table>
  </div>';


  $queryPesanan = mysqli_query($koneksi, "SELECT pesanan_detail.*, 
                                                 pesanan.tanggal_pemesanan,
                                                 pesanan.pesanan_id,
                                                 pesanan.nama_penerima,
                                                 pesanan.metode_pengiriman,
                                                 pesanan.metode_pembayaran,
                                                 pesanan.status,
                                                 barang.nama_barang,
                                                 barang.harga as harga_satuan,
                                                 barang.diskon 
                                          FROM pesanan_detail JOIN pesanan ON pesanan_detail.pesanan_id=pesanan.pesanan_id JOIN barang ON pesanan_detail.barang_id=barang.barang_id $where") OR die(mysqli_error($koneksi)); 

  $no = 1;
  $order = '';
  while($row=mysqli_fetch_assoc($queryPesanan)){
    
    $tgl = $row['tanggal_pemesanan'];
    $pesanan_id = $row['pesanan_id'];
    $nama_penerima = $row['nama_penerima'];
    $nama_barang = $row['nama_barang'];
    $hargaSatuan = $row['harga_satuan'];
    $qty = $row['quantity'];
    $metode_pengiriman = $row['metode_pengiriman'];
    $metode_pembayaran = $row['metode_pembayaran'];
    $subTotalPembelian = $row["harga"] * $row["quantity"];
    $status = $row['status'];

    if ($row['harga_satuan'] != $row['harga']) {
        $diskon = $row['harga_satuan'] - ($row['harga_satuan'] - ($row['harga_satuan'] * ($row['diskon']/100))); 
    }else {
        $diskon = 0;
    }

    if ($row['status']==1) {
        $status ="Menunggu Pembayaran";
    }elseif ($row['status']==2) {
        $status ="Pembayaran Sedang Di Validasi";
    }elseif ($row['status']==3) {
        $status ="Lunas";
    }else {
        $status ="Pembayaran Ditolak";
    }

    $order .= "
      <tr>
        <td class='no'>$no</td>
        <td class='kiri'>$tgl</td>
        <td class='kiri'>$pesanan_id</td>
        <td class='kiri'>$nama_penerima</td>
        <td class='kiri'>$nama_barang</td>
        <td class='kiri'>".rupiah($hargaSatuan)."</td>
        <td class='kiri'>$qty</td>
        <td class='tengah'>".rupiah($diskon)."</td>
        <td class='tengah'>$metode_pengiriman</td>
        <td class='tengah'>$metode_pembayaran</td>
        <td class='kanan'>".rupiah($subTotalPembelian)."</td>
        <td class='kanan'>$status</td>
      </tr>";

    $no++;
  }

  $isiLaporan .= '
    <table class="table table-sm">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Tanggal Pemesanan</th>
          <th scope="col">Nomor Faktur</th>
          <th scope="col">Nama Penerima</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Harga Satuan</th>
          <th scope="col">Qty</th>
          <th scope="col">Diskon</th>
          <th scope="col">Metode Pengiriman</th>
          <th scope="col">Metode Pembayaran</th>
          <th scope="col">Sub Total Pembelian</th>
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
      <title>Cetak Laporan</title>
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
$dompdf->setPaper('Legal', 'landscape');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('laporan_pemesanan.pdf', ['Attachment' => 0]);

?>