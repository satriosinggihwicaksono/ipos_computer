<?php
	$indikator = $this->uri->segment(1);
?>
<div class="main-menu-area mg-tb-40">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
					<li <?php if($indikator == 'persedian_page'){ echo 'class="active"'; } ?> ><a data-toggle="tab" href="#home"><i class="notika-icon notika-house"></i> Daftar Item</a>
					</li>
					<li <?php if($indikator == 'stok'){ echo 'class="active"'; } ?> ><a data-toggle="tab" href="#persedian"><i class="notika-icon notika-mail"></i> Persedian</a>
					</li>
					<li  <?php if($indikator == 'transaksi'){ echo 'class="active"'; } ?> ><a data-toggle="tab" href="#transaksi"><i class="notika-icon notika-edit"></i> Transaksi</a>
					</li>
					<li <?php if($indikator == 'service'){ echo 'class="active"'; } ?>><a data-toggle="tab" href="#service"><i class="notika-icon notika-form"></i> Service</a>
					</li>
					<?php if($check_admin){ ?>
					<li <?php if($indikator == 'kas'){ echo 'class="active"'; } ?>><a data-toggle="tab" href="#kas"><i class="notika-icon notika-bar-chart"></i> Kas</a>
					</li>
					<li <?php if($indikator == 'auth'){ echo 'class="active"'; } ?>><a data-toggle="tab" href="#auth"><i class="notika-icon notika-support"></i> Pengguna</a>
					</li>
					<?php } ?>
					<li <?php if($indikator == 'laporan'){ echo 'class="active"'; } ?>><a data-toggle="tab" href="#laporan"><i class="notika-icon notika-windows"></i> Laporan</a>
					</li>
				</ul>
				<div class="tab-content custom-menu-content">
					<div id="home" class="tab-pane <?php if($indikator == 'persedian_page'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
							<li><a href="<?php echo base_url().'persedian_page/daftar_item';?>">Daftar Item</a>
							</li>
							<li><a href="<?php echo base_url().'persedian_page/setting_item';?>">Setting Item</a>
							</li>
							<li><a href="<?php echo base_url().'persedian_page/barang_sn';?>">Barang SN</a>
							</li>
							<?php if($check_admin){ ?>	
							<li><a href="<?php echo base_url().'persedian_page/kategori';?>">Kategori</a></li>
							<?php } ?>
							<li><a href="<?php echo base_url().'persedian_page/pencarian_sn';?>">Pencarian SN</a>
							</li>
						</ul>
					</div>
					<div id="persedian" class="tab-pane <?php if($indikator == 'stok'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
							<li><a href="<?php echo base_url().'stok/transfer_stok/^^'.date('m').'^'.date('Y');?>">Transfer Stok</a>
							</li>
							<li><a href="<?php echo base_url().'stok/penerima_stok';?>">Penerima</a>
							<?php if($check_admin){ ?>	
								</li>
								<li><a href="<?php echo base_url().'stok/pembelian_stok';?>">Pembelian Stok</a>
								</li>
								<li><a href="<?php echo base_url().'stok/supplier';?>">Supplier</a>
								</li>
								<li><a href="<?php echo base_url().'stok/hutang';?>">Hutang Piutang</a>
								</li>
								<li><a href="<?php echo base_url().'stok/return_stok';?>">Return</a>
								</li>
								<li><a href="<?php echo base_url().'stok/stok_keluar';?>">Stok Keluar</a>
								</li>
								<li><a href="<?php echo base_url().'stok/stok_pengembalian';?>">Pengembalian Stok</a>
								</li>
							<?php } ?>
						</ul>
					</div>
					<div id="transaksi" class="tab-pane <?php if($indikator == 'transaksi'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
							<li><a href="<?php echo base_url().'transaksi/transaksi/^^^'.date('m').'^'.date('Y').'^';?>">Transaksi</a>
							</li>
							<li><a href="<?php echo base_url().'transaksi/daftar_penjualan/'; ?>">Daftar Penjualan</a>
							</li>
						</ul>
					</div>
					<div id="kas" class="tab-pane  <?php if($indikator == 'kas'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
							<li><a href="<?php echo base_url().'kas/kas';?>">Kas Cabang</a>
							</li>
							<li><a href="<?php echo base_url().'kas/kas_pusat';?>">Kas Pusat</a>
							</li>
							<li><a href="<?php echo base_url().'kas/tempat_kas';?>">Tempat Kas</a>
							</li>
						</ul>
					</div>
					<div id="laporan" class="tab-pane <?php if($indikator == 'laporan'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
						<?php if($check_admin){ ?>
							<li><a href="<?php echo base_url().'laporan/penjualan/^'.date('m').'^'.date('Y').'^^';?>">Laporan Penjualan</a>
							</li>
							<li><a href="<?php echo base_url().'laporan/history_penjualan/^'.date('m').'^'.date('Y').'^^^';?>">History Penjualan Produk</a>
							</li>
						<?php } ?>	
							<li><a href="<?php echo base_url().'laporan/stok_opname/^'.date('m').'^'.date('Y').'^^^';?>">Stok Opname</a>
							</li>
						</ul>
					</div>
					<div id="service" class="tab-pane <?php if($indikator == 'service'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
							<li><a href="<?php echo base_url().'service/service/^^'.$id_cabang.'^'.date('m').'^'.date('Y').'^';?>">Daftar Service</a>
							</li>
							<li><a href="<?php echo base_url().'service/laporan_service/^^^'.date('m').'^'.date('Y');?>">Laporan Service</a>
							</li>
						</ul>
					</div>
					<div id="auth" class="tab-pane <?php if($indikator == 'auth'){ echo 'in active'; } ?> notika-tab-menu-bg animated flipInX">
						<ul class="notika-main-menu-dropdown">
							<li><a href="<?php echo base_url().'auth/cabang/' ?>">Cabang</a>
							</li>
							<li><a href="<?php echo base_url().'auth/pengguna/' ?>">Pengguna</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>