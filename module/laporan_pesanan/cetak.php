<?php 
// include autoloader
require_once '../../libs/dompdf/autoload.inc.php';

include_once("../../function/koneksi.php");
include_once("../../function/helper.php");
// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

// Get ID
$pesanan_id = isset($_GET["pesanan_id"]) ? $_GET["pesanan_id"] : '';

// Style Laporan
$style = '
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">';

// Header laporan default
$isiLaporan = '<center><h1>Laporan Detail Pemesanan</h1></center><br><hr></br>';

// Blok pengolahan konten laporan
$query = mysqli_query($koneksi, "SELECT pesanan.metode_pengiriman, pesanan.metode_pembayaran, pesanan.nama_penerima, pesanan.nomor_telepon, pesanan.alamat, pesanan.tanggal_pemesanan, user.nama FROM pesanan JOIN user ON pesanan.user_id=user.user_id WHERE pesanan.pesanan_id='$pesanan_id'") OR die(mysqli_error($koneksi));

$row=mysqli_fetch_assoc($query);

$tanggal_pemesanan = $row['tanggal_pemesanan'];
$nama_penerima = $row['nama_penerima'];
$nomor_telepon = $row['nomor_telepon'];
$alamat = $row['alamat'];
$nama = $row['nama'];
$mtd_pengiriman = $row['metode_pengiriman'];
$mtd_pembayaran = $row['metode_pembayaran'] == 'tf' ? 'Transfer': 'COD';

$isiLaporan .= '
  <div id="tabel-laporan">
    <table>
      <tr>
        <th>Nomor Faktur</th>
        <td> : </td>
        <td>'.$pesanan_id.'</td>
      </tr>
      <tr>
        <th>Nama Pemesan</th>
        <td> : </td>
        <td>'.$nama.'</td>
      </tr>
      <tr>
        <th>Nama Penerima</th>
        <td> : </td>
        <td>'.$nama_penerima.'</td>
      </tr>
      <tr>
        <th>Alamat</th>
        <td> : </td>
        <td>'.$alamat.'</td>
      </tr>
      <tr>
        <th>Nomor Telepon</th>
        <td> : </td>
        <td>'.$nomor_telepon.'</td>
      </tr>		
      <tr>
        <th>Tanggal Pemesanan</th>
        <td> : </td>
        <td>'.$tanggal_pemesanan.'</td>
      </tr>
      <tr>
        <th>Metode Pengiriman</th>
        <td> : </td>
        <td>'.$mtd_pengiriman.'</td>
      </tr>	
      <tr>
        <th>Metode Pembayaran</th>
        <td> : </td>
        <td>'.$mtd_pembayaran.'</td>
      </tr>			
    </table>
  </div>';

  $queryDetail = mysqli_query($koneksi, "SELECT pesanan_detail.*, barang.nama_barang, barang.diskon, barang.harga as harga_asli FROM pesanan_detail JOIN barang ON 
  pesanan_detail.barang_id=barang.barang_id WHERE pesanan_detail.pesanan_id='$pesanan_id'")  OR die(mysqli_error($koneksi));

  $no = 1;
  $grandTotal = 0;
  $order = '';
  while($rowDetail=mysqli_fetch_assoc($queryDetail)){
    
    $hargaAsli = $rowDetail["harga_asli"];
    $diskon = $rowDetail["diskon"];
    $subTotal = $rowDetail["harga"] * $rowDetail["quantity"];
    $grandTotal = $grandTotal + $subTotal;

    if ($rowDetail["harga"] != $rowDetail["harga_asli"]) {
      $labelHargaDiskon = "<th scope='col'>Harga Diskon</th>";
      $isiHargaDiskon = "<td class='kanan'>".rupiah($rowDetail["harga"])."</td>";
      $diskon = $rowDetail["diskon"];
      $col = 5;
    }else{
      $labelHargaDiskon = "";
      $isiHargaDiskon = "";
      $diskon = 0;
      $col = 4;
    }

    $order .= "
      <tr>
        <td class='no'>$no</td>
        <td class='kiri'>$rowDetail[nama_barang]</td>
        <td class='kanan'>".rupiah($rowDetail["harga_asli"])."</td>
        <td class='tengah'>$diskon %</td>
        {$isiHargaDiskon}
        <td class='tengah'>$rowDetail[quantity]</td>
        <td class='kanan'>".rupiah($subTotal)."</td>
      </tr>";
    $biaya_pengiriman = $rowDetail["biaya_pengiriman"];
    $no++;
  }

  $grandTotal = $grandTotal + $biaya_pengiriman;

  $isiLaporan .= '
    <table class="table table-sm">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Harga</th>
          <th scope="col">Diskon</th>
          '.$labelHargaDiskon.'
          <th scope="col">Qty</th>
          <th scope="col">Sub Total</th>
        </tr>
      </thead>
      <tbody>
        '.$order.'
      </tbody>
      <tfoot>
        <tr>
          <th colspan='.$col.'></th>
          <th>Biaya Pengiriman</th>
          <td>'.rupiah($biaya_pengiriman).'</td>
        </tr>
        <tr>
          <th colspan='.$col.'></th>
          <th>Total</th>
          <th>'.rupiah($grandTotal).'</th>
        </tr>
      </tfoot>
    </table>';

$dompdf->loadHtml('
  <html>
    <head>
      <title>Cetak Laporan</title>
      '.$style.'
      <style>
        #tabel-laporan {
          font-size: 10pt;
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
$dompdf->stream('laporan_detail_pemesanan.pdf', ['Attachment' => 0]);

?>