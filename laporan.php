<?php

    if($user_id){
        $module = isset($_GET['module']) ? $_GET['module'] : false;
        $action = isset($_GET['action']) ? $_GET['action'] : false;
        $mode = isset($_GET['mode']) ? $_GET['mode'] : false;
    }else{
        header("location: ".BASE_URL."index.php?page=login");
    }

    admin_only($module, $level);

?>

<div id="bg-page-profile">
    <div id="menu-profile">
        <ul>
        <?php if($level == "superadmin"){ ?>    
            <li>
                <a <?php if($module == "laporan_pesanan"){ echo "class='active'"; } ?> href="<?php echo BASE_URL."index.php?page=laporan&module=laporan_pesanan&action=list";?>">Laporan Pemesanan</a>
            </li>
        <?php } ?>    
        </ul>
    </div>

    <div id="profile-content">
        <?php
        
            $file = "module/$module/$action.php";
            if(file_exists($file)){
                include_once($file);
            }else{
                echo "<h3>Maaf, halaman tersebut tidak ditemukan</h3>";
            }

        ?>
    </div>
</div>