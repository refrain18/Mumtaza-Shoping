<?php
	$search = isset($_GET["search"]) ? $_GET["search"] : false;

	$where = "";
	$search_url = "";
	if($search){
			$search_url = "&search=$search";
			$where = "WHERE pesanan.pesanan_id LIKE '%$search%' || user.nama LIKE '%$search%'";                
	} 
?>
<?php if($level == "superadmin"){ ?>	
<div id="frame-tambah">
	<div id="left">
		<form action="<?php echo BASE_URL."index.php"; ?>" method="GET">
			<input type="hidden" name="page" value="<?php echo $_GET["page"] ?>" />
			<input type="hidden" name="module" value="<?php echo $_GET["module"] ?>" />
			<input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
			<input type="text" name="search" value="<?php echo $search; ?>" size="30px" placeholder="Cari Nomor atau Nama"/>
			<input type="submit" value="Search" />
		</form>
	</div>
	<div id="right">
	<a class="tombol-action" href="#popup">Cetak</a>
	</div>	
	<br>	
</div>

<div id="popup">
    <div class="window">
        <a href="#" class="close-button" title="Close">X</a>
        <h4><center> Cetak PDF Laporan Pemesanan</center></h4>

        <form action="<?php echo "module/laporan_pesanan/cetak_filter.php"; ?>" target='_BLANK' method="POST">
        <div class="element-form">      
			<label>Tanggal Pesanan</label>
			<br>
			<label>Mulai dari</label>
			<input type="date" name="tgl_a"/> 
			<br>
			<label>Sampai</label> 
			<input type="date" name="tgl_b"/>
        </div>
        <div class="element-form">
            <span ><button class='tombol-action'>Cetak</button></span>
		</div>
		</form>
	</div>
</div>
<?php } ?>
<?php

	$pagination = isset($_GET["pagination"]) ? $_GET["pagination"] : 1;
	$data_per_halaman = 10;
	$mulai_dari = ($pagination-1) * $data_per_halaman;

	$no=1 + $mulai_dari;

	if($level == "superadmin"){
		$queryPesanan = mysqli_query($koneksi, "SELECT pesanan.*, user.nama FROM pesanan JOIN user ON pesanan.user_id=user.user_id $where ORDER BY pesanan.tanggal_pemesanan DESC LIMIT $mulai_dari, $data_per_halaman");
	}else{
		$queryPesanan = mysqli_query($koneksi, "SELECT pesanan.*, user.nama FROM pesanan JOIN user ON pesanan.user_id=user.user_id WHERE pesanan.user_id='$user_id' ORDER BY pesanan.tanggal_pemesanan DESC LIMIT $mulai_dari, $data_per_halaman");
	}
	
	if(mysqli_num_rows($queryPesanan) == 0){
		echo "<h3>Saat ini belum ada data pesanan</h3>";
	}
	else{
	
		echo "<table class='table-list'>
						<tr class='baris-title'>
							<th class='kiri'>Tanggal Pesanan</th>
							<th class='kiri'>Nomor Pesanan</th>
							<th class='kiri'>Nama Pemesan</th>
							<th class='kiri'>Status</th>
							<th class='kiri'>Action</th>
						</tr>";
		
		$adminButton = "";
		while($row=mysqli_fetch_assoc($queryPesanan)){
			$status = $row['status'];
			echo "<tr>
							<td class='kiri'>$row[tanggal_pemesanan]</td>
							<td class='kiri'>$row[pesanan_id]</td>
							<td class='kiri'>$row[nama]</td>
							<td class='kiri'>$arrayStatusPesanan[$status]</td>
							<td class='kiri'>
								<a class='tombol-action' href='".BASE_URL."index.php?page=laporan&module=laporan_pesanan&action=detail&pesanan_id=$row[pesanan_id]'>Detail Pesanan</a>
								<a class='tombol-action' href='".BASE_URL."module/laporan_pesanan/cetak.php?pesanan_id=$row[pesanan_id]' target='_BLANK'>Cetak</a>
							</td>
						</tr>";
		}
		
		echo "</table>";

		$queryHitungPesanan= mysqli_query($koneksi, "SELECT * FROM pesanan JOIN user ON pesanan.user_id=user.user_id $where");
		pagination($queryHitungPesanan, $data_per_halaman, $pagination, "index.php?page=my_profile&module=laporan_pesanan&action=list$search_url");
	}
	
?>