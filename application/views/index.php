<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Aplikasi Orbit Computer</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/font-awesome.min.css">
  
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/meanmenu/meanmenu.min.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/normalize.css">
    
    <!-- jvectormap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/jvectormap/jquery-jvectormap-2.0.3.css">
    <!-- notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/notika-custom-icon.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/';?>css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/vendor/modernizr-2.8.3.min.js"></script>
	  <!-- Page level plugin CSS-->
	<link href="<?php echo base_url().'assets/';?>vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
	<style>
		.pesan{
			display: none;
			position: fixed;
			width: 300px;
			top: 10px;
			right: 10px;
			padding: 10px 10px 10px 10px;
			text-align: center;
			z-index: 100000;
		}
	</style>
</head>
<?php 
$username = $this->session->userdata('username');
$check_admin = $this->komputer->isAdmin($username);
$check_gudang = $this->komputer->isGudang($username);	
$id_username = $this->komputer->getIduser($username);	
$id_cabang = $this->komputer->getIdCabang($username);
$nama_cabang = $this->komputer->namaCabang($id_cabang);
$message = $this->session->flashdata('message');
include 'ubah_user_password.php'; 
?>	
<body>
	<?php
	if($message){ ?>
		  <div class="pesan panel text-sm m-b-none" style="background-color: #98FB98">
			  <h5 style="color:white;"><?php echo $message; ?></h5>
		  </div>
	<?php }
			$message = '';
	?>	
    <!-- Start Header Top Area -->
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="logo-area">
						<a href="#"><h3 style='color:#FFFFFF;'>ORBIT COMPUTER</h3></a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="header-top-menu">
                        <ul class="nav navbar-nav notika-top-nav">
                            <li class="nav-item dropdown">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><i class="notika-icon notika-search"></i></span></a>
                                <div role="menu" class="dropdown-menu search-dd animated flipInX">
                                    <form method="POST" action="<?php echo base_url().'persedian_page/search_pencarian_sn/' ?>" target="_blank">
									<div class="search-input">
                                        <i class="notika-icon notika-left-arrow"></i>
                                        <input type="text" name="serial"; />
                                    </div>
									</form>	
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><i class="fa fa-user"><?php echo ' '.$username; ?></i></span></a>
                                <div role="menu" class="dropdown-menu message-dd chat-dd animated zoomIn">
                                    <div class="hd-mg-tt">
                                        <h2>Account</h2>
                                    </div>
                                   
                                    <div class="hd-message-info">
                                        <a href="#">
                                            <div class="hd-message-sn">
                                                <div class="hd-message-img chat-img">
                                                    <img src="<?php echo base_url().'assets/img/post/1.jpg'; ?>" alt="" />
                                                    <div class="chat-avaible"><i class="notika-icon notika-dot"></i></div>
                                                </div>
                                                <div class="hd-mg-ctn">
                                                    <h3><?php echo $username; ?></h3>
                                                    <span data-toggle="modal" data-target="#ubhpassword<?php echo $id_username; ?>" class="fa fa-wrench icon-name"> Ubah Password</span>
                                                </div>
                                            </div>
                                        </a>
									</div>	
                                        
                                    <div class="hd-mg-va">
                                        <a href="<?php echo base_url().'auth/logout/' ?>">Keluar</a>
                                    </div>
                                </div>	
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Top Area -->
    <!-- Mobile Menu start -->
    <?php include 'menu_mobile.php'; ?>
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    <?php include 'menu_page.php'; ?>
    <!-- Start Sale Statistic area-->
    
        <?php
		if(!empty($this->uri->segment(2))){
			$page = $this->uri->segment(2);
			switch ($page) {
					case 'kategori':
						if(!$check_admin) redirect('welcome');
						include 'kategori.php';
						break;
					case 'daftar_item':
						include 'daftar_item.php';
						break;
					case 'barang_sn':
						include 'barang_sn.php';
						break;
					case 'setting_item':
						include 'setting_item.php';
						break;
					case 'pencarian_sn':
						include 'pencarian_sn.php';
						break;
					case 'tambah_stok':
						include 'tambah_stok.php';
						break;
					case 'cabang':
						if(!$check_admin) redirect('welcome');
						include 'cabang.php';
						break;
					case 'pengguna':
						if(!$check_admin) redirect('welcome');
						include 'pengguna.php';
						break;
					case 'transfer_stok':
						include 'transfer_stok.php';
						break;
					case 'return_stok':
						include 'return_stok.php';
						break;
					case 'daftar_return_stok':
						include 'daftar_return_stok.php';
						break;
					case 'penerima_stok':
						include 'penerima_stok.php';
						break;
					case 'daftar_item_stok':
						include 'daftar_item_stok.php';
						break;
					case 'supplier':
						include 'supplier.php';
						break;
					case 'pembelian_stok':
						include 'pembelian_stok.php';
						break;
					case 'history_pembelian_stok':
						include 'history_pembelian_stok.php';
						break;
					case 'detail_pembelian_stok':
						include 'detail_pembelian_stok.php';
						break;
					case 'stok_opname':
						include 'stok_opname.php';
						break;
					case 'detail_stok_opname':
						include 'detail_stok_opname.php';
						break;	
					case 'hutang':
						include 'hutang.php';
						break;
					case 'stok_keluar':
						include 'stok_keluar.php';
						break;
					case 'stok_pengembalian':
						include 'stok_pengembalian.php';
						break;
					case 'detail_stok_keluar':
						include 'detail_stok_keluar.php';
						break;
					case 'transaksi':
						include 'transaksi.php';
						break;
					case 'daftar_penjualan':
						include 'daftar_penjualan.php';
						break;
					case 'detail_transaksi':
						include 'detail_transaksi.php';
						break;
					case 'kas':
						include 'kas.php';
						break;
					case 'kas_pusat':
						include 'kas_pusat.php';
						break;
					case 'tempat_kas':
						include 'tempat_kas.php';
						break;
					case 'penjualan':
						include 'penjualan.php';
						break;
					case 'history_penjualan':
						include 'history_penjualan.php';
						break;
					case 'history_penjualan_item':
						include 'history_penjualan_item.php';
						break;
					case 'service':
						include 'service.php';
						break;
					case 'laporan_service':
						include 'laporan_service.php';
						break;
					case 'detail_service':
						include 'detail_service.php';
						break;
					 default:
						include 'home.php';
					} 
			} else {
				include 'home.php';
			}
		?>
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2019 
Aplikasi POS <a href="#">Satrio Singgih Wicaksono</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- END -->
  
    <!-- jquery
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/counterup/jquery.counterup.min.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/counterup/waypoints.min.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/counterup/counterup-active.js"></script>
  
    <!-- jvectormap JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/jvectormap/jvectormap-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/sparkline/sparkline-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/flot/jquery.flot.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/flot/curvedLines.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/flot/flot-active.js"></script>
    <!-- knob JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/knob/jquery.knob.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/knob/jquery.appear.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/knob/knob-active.js"></script>
    <!--  wave JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/wave/waves.min.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/wave/wave-active.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/todo/jquery.todo.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/plugins.js"></script>
	
    <script src="<?php echo base_url().'assets/';?>js/chat/moment.min.js"></script>
    <script src="<?php echo base_url().'assets/';?>js/chat/jquery.chat.js"></script>
    <!-- main JS
		============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/main.js"></script>
	
	<!-- JS nominal -->
	<script src="<?php echo base_url().'assets/';?>js/my.js"></script>
	
	<!-- tawk chat JS
	============================================ -->
    <script src="<?php echo base_url().'assets/';?>js/demo/datatables-demo.js"></script>
	<script src="<?php echo base_url().'assets/';?>vendor/datatables/jquery.dataTables.js"></script>
 	<script>
		$(document).ready(function(){setTimeout(function(){$(".pesan").fadeIn('slow');}, 500);});
		setTimeout(function(){$(".pesan").fadeOut('slow');}, 3000);
	</script>
</body>

</html>