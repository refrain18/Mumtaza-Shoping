<?php

    if($totalBarang == 0) {
        echo "<h3>Saat ini belum ada data di dalam keranjang belanja anda</h3>";
    }else{
        foreach($keranjang AS $key => $value){
        if($id_member){
            $diskonMember = "<th class='tengah'>Diskon</th>";
            $hargaDiskon = "<th class='kanan'>Harga Member</th>";
            $disc = $value['diskon'];
        }else if ($id_member && $disc = 0) {
            $diskonMember = "";
            $hargaDiskon = "<th class='kanan'>Harga Member</th>";
        }else{
            $diskonMember = "";
            $hargaDiskon = "";
        }
    }
        $no=1;

        echo "<table class='table-list'>
                <tr class='baris-title'>
                    <th class='tengah'>No</th>
                    <th class='kiri'>Image</th>
                    <th class='kiri'>Nama Barang</th>
                    <th class='tengah'>Qty</th>
                    
                    <th class='kanan'>Harga Satuan</th>
                    {$hargaDiskon}
                    <th class='kanan'>Total</th>
                </tr>";
    $subtotal = 0;
    $total_harga_diskon = 0;
    foreach($keranjang AS $key => $value) {
        $barang_id = $key;

        $nama_barang = $value["nama_barang"];
        $quantity = $value["quantity"];
        $gambar = $value["gambar"];
        $harga = $value["harga"];
        $diskon = $value["diskon"];
        $harga = $value["harga"];

        $harga_diskon = $harga * ($diskon/100);
        $total_harga_diskon = $harga - $harga_diskon;

        if ($id_member) {
            $total = $quantity * $total_harga_diskon;
            $rowDiskon = "<td class='tengah'>$diskon %</td>";
            $rowHargaDiskon = "<td class='kanan'>".rupiah($total_harga_diskon)."</td>";
            $colspan = 6;
            if ($diskon = 0) {
                $rowDiskon ="";
            }
        }else{
            $total = $quantity * $harga;   
            $rowDiskon ="";
            $rowHargaDiskon = "";
            $colspan = 5;
        }
        
        $subtotal = $subtotal + $total;
        // var_dump($keranjang);
        // die();

        echo "<tr>
                <td class='tengah'>$no</td>
                <td class='kiri'><img src='".BASE_URL."images/barang/$gambar' height='100px'/></td>
                <td class='kiri'>$nama_barang</td>
                <td class='tengah'><input type='number' name='$barang_id' value='$quantity' class='update-quantity' /></td>
                
                <td class='kanan'>".rupiah($harga)."</td>
                {$rowHargaDiskon}
                <td class='kanan hapus_item'>".rupiah($total)." <a href='".BASE_URL."hapus_item.php?barang_id=$barang_id'>X</a></td>
            </tr>";

        $no++;

    }        

    echo "<tr>
            <td colspan='$colspan' class='kanan'><b>Sub Total</b></td>
            <td class='kanan'><b>".rupiah($subtotal)."</b></td>
          </tr>";

    echo "</table>";

    echo "<div id='frame-button-keranjang'>
				<a id='lanjut-belanja' href='".BASE_URL."index.php'>< Lanjut Belanja</a>
				<a id='lanjut-pemesanan' href='".BASE_URL."?page=data-pemesan'>Lanjut Pemesanan ></a>
		  </div>";    
}   

?>

<script>

	$(".update-quantity").on("input", function(e){
		var barang_id = $(this).attr("name");
		var value = $(this).val();
		
		$.ajax({
			method: "POST",
			url: "update_keranjang.php",
			data: "barang_id="+barang_id+"&value="+value
		})
		.done(function(data){
            var json = $.parseJSON(data);
            if(json.status == true){
			    location.reload();
            }else{
                alert(json.pesan);
                location.reload();
            }
		});
		
	});

</script>