<?php
	
	$pesanan_id= $_GET["pesanan_id"];
		
	$query = mysqli_query($koneksi, "SELECT
		pesanan.metode_pengiriman, pesanan.metode_pembayaran, pesanan.nama_penerima, pesanan.nomor_telepon, pesanan.alamat, pesanan.tanggal_pemesanan, user.nama 
		FROM pesanan JOIN user ON pesanan.user_id=user.user_id WHERE pesanan.pesanan_id='$pesanan_id'") OR die(mysqli_error($koneksi));
	
	$row=mysqli_fetch_assoc($query);
	
	$tanggal_pemesanan = $row['tanggal_pemesanan'];
	$nama_penerima = $row['nama_penerima'];
	$nomor_telepon = $row['nomor_telepon'];
	$alamat = $row['alamat'];
	$nama = $row['nama'];
	$mtd_pengiriman = $row['metode_pengiriman'];
	$mtd_pembayaran = $row['metode_pembayaran'] == 'Transfer' ? 'Transfer': 'COD';
	
?>

<div id="frame-faktur">

	<h3><center>Detail Pesanan</center></h3>
	
	<hr/>
	
	<table>
	
		<tr>
			<td>Nomor Faktur</td>
			<td>:</td>
			<td><?php echo $pesanan_id; ?></td>
		</tr>
		<tr>
			<td>Nama Pemesan</td>
			<td>:</td>
			<td><?php echo $nama; ?></td>
		</tr>
		<tr>
			<td>Nama Penerima</td>
			<td>:</td>
			<td><?php echo $nama_penerima; ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>:</td>
			<td><?php echo $alamat; ?></td>
		</tr>
		<tr>
			<td>Nomor Telepon</td>
			<td>:</td>
			<td><?php echo $nomor_telepon; ?></td>
		</tr>		
		<tr>
			<td>Tanggal Pemesanan</td>
			<td>:</td>
			<td><?php echo $tanggal_pemesanan; ?></td>
		</tr>
		<tr>
			<td>Metode Pengiriman</td>
			<td>:</td>
			<td><?php echo $mtd_pengiriman; ?></td>
		</tr>	
		<tr>
			<td>Metode Pembayaran</td>
			<td>:</td>
			<td><?php echo $mtd_pembayaran; ?></td>
		</tr>			
	</table>	
</div>	

	<?php

		if ($id_member || $level == "superadmin") {
			$labelDiskon = "<th class='tengah'>Diskon</th>";
			$labelHargaDiskon = "<th class='kanan'>Harga Member</th>";
			$jenisHarga = "Asli";
		}else{
			$labelDiskon = "";
			$labelHargaDiskon = "";
			$jenisHarga = "Satuan";
		}

	?>
	<table class="table-list">
	
		<tr class="baris-title">
			<th class="no">No</th>
			<th class="kiri">Nama Barang</th>
			<th class="kiri">Kode Barang</th>
			<th class="tengah">Qty</th>
			<?php //echo $labelDiskon ?>
			<th class="kanan">Harga <?php echo $jenisHarga ?></th>
			<?php echo $labelHargaDiskon ?>
			<th class="kanan">Sub Total</th>
		</tr>
		
		<?php
		
			$queryDetail = mysqli_query($koneksi, "SELECT pesanan_detail.*, barang.kode_barang AS kd_brg, barang.nama_barang, barang.diskon, barang.harga as harga_asli FROM pesanan_detail JOIN barang ON 
			pesanan_detail.barang_id=barang.barang_id WHERE pesanan_detail.pesanan_id='$pesanan_id'")  OR die(mysqli_error($koneksi));
			
			// $row=mysqli_fetch_array($queryDetail);
			
			$no = 1;
			$subtotal = 0;
			while($rowDetail=mysqli_fetch_assoc($queryDetail)){
				
				$hargaAsli = $rowDetail["harga_asli"];
				$diskon = $rowDetail["diskon"];
				$total = $rowDetail["harga"] * $rowDetail["quantity"];
				$subtotal = $subtotal + $total;
				
				if ($rowDetail["harga"] != $rowDetail["harga_asli"]) {
					$diskon = $rowDetail["diskon"];
				}else {
					$diskon = 0;
				}

				if ($id_member || $level == "superadmin") {
					$isiDiskon = "<td class='tengah'>$diskon %</td>";
					$isiHargaAsli = "<td class='kanan'>".rupiah($hargaAsli)."</td>";
				}else{
					$isiDiskon = "";
					$isiHargaAsli = "";
				}

				

				echo "<tr>
						<td class='no'>$no</td>
						<td class='kiri'>$rowDetail[nama_barang]</td>
						<td class='kiri'>$rowDetail[kd_brg]</td>
						<td class='tengah'>$rowDetail[quantity]</td>
						
						{$isiHargaAsli}
						<td class='kanan'>".rupiah($rowDetail["harga"])."</td>
						<td class='kanan'>".rupiah($total)."</td>
					  </tr>";
				
				// Ambil biaya pengiriman dari tb pesanan_detail
				$biaya_pengiriman = $rowDetail['biaya_pengiriman'];
				$no++;
			}
			
			$subtotal = $subtotal + $biaya_pengiriman;
			
			if ($id_member || $level == "superadmin") {
				$colspan = 6;
			}else{
				$colspan = 5;
			}
		?>

		
		<tr>
			<td class="kanan" colspan="<?php echo $colspan ?>">Biaya Pengiriman</td>
			<td class="kanan"><?php echo rupiah($biaya_pengiriman); ?></td>
		</tr>

		<tr>
		    <td class="kanan" colspan="<?php echo $colspan ?>"><b>Total</b></td>
			<td class="kanan"><b><?php echo rupiah($subtotal); ?></b></td>
		</tr>	
  	
	</table>
	
<div id="frame-keterangan-pembayaran">
	<?php if ($mtd_pembayaran == "COD" && $level == 'superadmin') :?>
		<p>
			Silahkan konfirmasi pesanan jika customer sudah menyelesaikan pembayaran.
			<a href="<?php echo BASE_URL."index.php?page=my_profile&module=pesanan&action=status&pesanan_id=$pesanan_id"?>">Konfirmasi</a>.
		</p>
	<?php elseif($mtd_pembayaran == "Transfer" && $level == 'customer') :?>
		
		<?php
			$deathline = date('d-m-Y 23:59', strtotime('+7 days', strtotime($tanggal_pemesanan)));;
		?>
		<p>
			Silahkan lakukan pembayaran sebelum tanggal <?php echo $deathline ?> ke Bank berikut : <br/><br/>
			Nomor Account : 7180181991 ACEP SUPRIADI (BCA).<br/>
			atau <br/>
			Nomor Account : 1280005081085 ACEP SUPRIADI (MANDIRI).<br/><br/>
			Setelah melakukan pembayaran silahkan lakukan konfirmasi pembayaran
			<a href="<?php echo BASE_URL."index.php?page=my_profile&module=pesanan&action=konfirmasi_pembayaran&pesanan_id=$pesanan_id"?>">Disini</a>.
		</p>
	<?php elseif($mtd_pembayaran == "Transfer" && $level == 'superadmin') : ?>
		<p>
			<a href="<?php echo BASE_URL."index.php?page=my_profile&module=pesanan&action=konfirmasi_pembayaran&pesanan_id=$pesanan_id"?>">Cek Konfirmasi Pembayaran</a>.
		</p>	
	<?php else : ?>
		<p>
			Silahkan lunasi tagihan anda pada kurir Mumtaza.
		</p>
	<?php endif; ?>
</div>	