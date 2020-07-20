<div class="mobile-menu-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="mobile-menu">
					<nav id="dropdown">
						<ul class="mobile-menu-nav">
							<li><a data-toggle="collapse" data-target="#Charts" href="#">Daftar Item</a>
								<ul class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'persedian_page/daftar_item';?>">Daftar Item</a></li>
									<li><a href="<?php echo base_url().'persedian_page/setting_item';?>">Setting Item</a></li>
									<li><a href="<?php echo base_url().'persedian_page/kategori';?>">Kategori</a></li>
									<li><a href="<?php echo base_url().'persedian_page/pencarian_sn';?>">Pencarian SN</a></li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#demoevent" href="#">Persediaan</a>
								<ul id="demoevent" class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'stok/transfer_stok/^^'.date('m').'^'.date('Y');?>">Transfer Stok</a></li>
									<li><a href="<?php echo base_url().'stok/penerima_stok';?>">Penerima</a></li>
									<?php if($check_admin){ ?>	
									<li><a href="<?php echo base_url().'stok/pembelian_stok';?>">Pembelian Stok</a></li>
									<li><a href="<?php echo base_url().'stok/supplier';?>">Supplier</a></li>
									<li><a href="<?php echo base_url().'stok/hutang';?>">Hutang Piutang</a></li>
									<li><a href="<?php echo base_url().'stok/return_stok';?>">Return</a></li>
									<li><a href="<?php echo base_url().'stok/stok_keluar';?>">Stok Keluar</a></li>
									<li><a href="<?php echo base_url().'stok/stok_pengembalian';?>">Pengembalian Stok</a></li>
									<?php } ?>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#democrou" href="#">Transaksi</a>
								<ul id="democrou" class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'transaksi/transaksi/^^^'.date('m').'^'.date('Y').'^';?>">Transaksi</a></li>
									<li><a href="<?php echo base_url().'transaksi/daftar_penjualan/'; ?>">Daftar Penjualan</a></li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#demolibra" href="#">Service</a>
								<ul id="demolibra" class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'service/service/^^'.$id_cabang.'^'.date('m').'^'.date('Y').'^';?>">Daftar Service</a></li>
									<li><a href="<?php echo base_url().'service/laporan_service/^^^'.date('m').'^'.date('Y');?>">Laporan Service</a></li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#demodepart" href="#">Kas</a>
								<ul id="demodepart" class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'kas/kas';?>">Kas Cabang</a></li>
									<li><a href="<?php echo base_url().'kas/kas_pusat';?>">Kas Pusat</a></li>
									<li><a href="<?php echo base_url().'kas/tempat_kas';?>">Tempat Kas</a></li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#demo" href="#">Laporan</a>
								<ul id="demo" class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'laporan/penjualan/^'.date('m').'^'.date('Y').'^^';?>">Laporan Penjualan</a></li>
									<li><a href="<?php echo base_url().'laporan/history_penjualan/^'.date('m').'^'.date('Y').'^^^';?>">History Penjualan Produk</a></li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pengguna</a>
								<ul id="Pagemob" class="collapse dropdown-header-top">
									<li><a href="<?php echo base_url().'auth/cabang/' ?>">Cabang</a></li>
									<li><a href="<?php echo base_url().'auth/pengguna/' ?>">Pengguna</a></li>
								</ul>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>