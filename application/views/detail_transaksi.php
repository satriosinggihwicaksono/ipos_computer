<?php 
	$id = $this->uri->segment(3);
	$deskripsi = $transaksi[0]['deskripsi'];
	$deskripsi = explode(',',$deskripsi);
?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhitem">Tambah Daftar Item</button><br>
									<br>
									<form method="POST" action="<?php echo base_url().'transaksi/search_detail_transaksi/'; ?>">
										AMBIL : <input type='text' name='kongsi' value="<?php echo $kongsi; ?>"/> <input type='hidden' name='id' value="<?php echo $transaksi[0]['id']; ?>"/>
										<input type="submit" value="AMBIL" />
									</form>
									<br>
									<form method="POST" action="<?php echo base_url().'transaksi/search_detail_transaksi/'; ?>">
										CARI : <input type='text' name='search' value="<?php echo $search; ?>"/> <input type='hidden' name='id' value="<?php echo $transaksi[0]['id']; ?>"/>
										<input type="submit" value="SEARCH" />
									</form>	
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label> <?php echo date('Y-m-d H:i:s',strtotime($transaksi[0]['waktu'])); ?> </label> <br>
									<label>
										Transaksi 
										<?php if(empty($transaksi[0]['nota'])){ 
											echo $this->komputer->namaCabang($transaksi[0]['cabang']).$this->komputer->nota($transaksi[0]['id'],5); 
										} else { 
											echo $transaksi[0]['nota']; 
										} ?>
									</label>
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhtransaksi<?php echo $transaksi[0]['id']; ?>">Ubah</button>	
									<br>
									<label>Pembeli : <?php if(!empty($deskripsi[0])) echo $deskripsi[0]; ?></label><br>
									<label>Alamat : <?php if(!empty($deskripsi[0])) echo $deskripsi[1]; ?></label><br>
									<label>Handphone : <?php if(!empty($deskripsi[0])) echo $deskripsi[2]; ?></label> <br>
									<?php if($transaksi[0]['status'] != 1){ ?>
									<a href='<?php echo base_url().'transaksi/delete_transaksi/'.$transaksi[0]['id'].'/'.$this->uri->segment(2); ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Batalkan</span></a>
									<?php } ?>
									
								</div>
							</div>
						</div>
				</div>
				<?php if(!empty($search)){ ?>
				<div class="bsc-tbl">
					<table class="table table-sc-ex" style="width:50%">
						<thead style='background-color:#2196F3'>
							<tr>
								<th>Nama</th>
								<th style="text-align:center;">Serial</th>
								<th style="text-align:center;">Stok</th>
								<th style="text-align:center; width:100px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$this->db->where('id_transaksi',$transaksi[0]['id']);	
								$removes = $this->db->get('sub_transaksi')->result_array();
								foreach($removes as $r){
									$this->db->where('id !=',$r['id_serial']);
								}
								$this->db->where('status',0);
								if(!$check_admin){
									$this->db->where('cabang',$transaksi[0]['cabang']);
								}	

								$this->db->like('serial',$search);
								$searching = $this->db->get('serial')->result_array();
								if(!empty($searching)){
									foreach($searching as $s){	
									$item = $this->komputer->cek($s['id_item'],'id','item');			
									if(!empty($item)){
									$harga = $this->komputer->cek($item[0]['id'],'id_item','harga');
										if(!empty($harga)){
											$harga_jual = $harga[0]['harga_jual'];
										} else {
											$harga_jual = 0;
										}	
									}
							?>
							<tr>
								<td><?php if(!empty($item)) echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe'].' ('.$this->komputer->namaCabang($s['cabang']).')'; ?></td>
								<td style="text-align:center;"><?php if(!empty($searching)) echo $s['serial']; ?></td>
								<td style="text-align:center;">1</td>
								<td style="text-align:center; width:100px;">
									<?php if(!empty($item)){ ?>
									<a href="<?php echo base_url().'transaksi/add_sub_transaksi/'.$transaksi[0]["id"].'/'.$item[0]["id"].'/'.$s["id"].'/'.$harga_jual.'/1';?>">
									<span class="btn btn-success"> Tambah</span></a>
									<?php } ?>
								</td>
							</tr>
							<?php } } else {
									$this->db->where('serial',0);
									$this->db->like('nama',$search);
									$item_search = $this->db->get('item')->result_array();
									if(!empty($item_search)){
										foreach($item_search as $i){
											$harga = $this->komputer->cek($i['id'],'id_item','harga');
											if(!empty($harga)){
												$harga_jual = $harga[0]['harga_jual'];
											} else {
												$harga_jual = 0;
											}
							?>
							<tr>
								<td><?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?></td>
								<td style="text-align:center;"></td>
								<td style="text-align:center;">
								<?php
									
									$this->db->where('id_cabang',$transaksi[0]['cabang']);
									$stok_opname = $this->db->get('stok_opname')->result_array();
									if(!empty($stok_opname)){
										$tanggal = $stok_opname[0]['waktu'];
										$waktu = strtotime($stok_opname[0]['waktu']);
										if(date('Y',$waktu) != -00001){
											$this->db->where('id_stok_opname',$stok_opname[0]['id']);
											$this->db->where('id_item',$i['id']);
											$real_stok = $this->db->get('detail_stok_opname')->result_array();								
											$real_stok = $real_stok[0]['stok'];
										}
									} else {
										$tanggal = '';
										$real_stok = 0;
									}

									$pembelian_unit = array();
									$this->db->select('*');
									$this->db->from('sub_pembelian');
									$this->db->join('pembelian','pembelian.id = sub_pembelian.id_pembelian');
									$this->db->where('pembelian.id_cabang',$transaksi[0]['cabang']);
									$this->db->where('id_item',$i['id']);
									if(!empty($tanggal)){
										$this->db->where('pembelian.waktu >=',$tanggal);
									}
									$pembelian = $this->db->get()->result_array();
									foreach($pembelian as $p){
										$pembelian_unit[] = $p['unit'];
									}

									$pembelian_unit = array_sum($pembelian_unit);
									
									// transfer to
									$to_unit = array();
									$this->db->select('trans_item.unit');
									$this->db->from('trans_item');
									$this->db->join('trans_stok','trans_stok.id = trans_item.id_trans_stok');
									$this->db->where('id_to',$transaksi[0]['cabang']);
									$this->db->where('id_item',$i['id']);
									$this->db->where('status',1);
									if(!empty($tanggal)){
										$this->db->where('trans_stok.tgl_trans >=',$tanggal);
									}
									$to = $this->db->get()->result_array();
									foreach($to as $t){
										$to_unit[] = $t['unit'];
									}

									$to_unit = array_sum($to_unit);

									// Transfer
									
									$from_unit = array();
									$this->db->select('trans_item.unit');
									$this->db->from('trans_item');
									$this->db->join('trans_stok','trans_stok.id = trans_item.id_trans_stok');
									$this->db->where('id_from',$transaksi[0]['cabang']);
									$this->db->where('id_item',$i['id']);
									$this->db->where('status',1);
									if(!empty($tanggal)){
										$this->db->where('trans_stok.tgl_trans >=',$tanggal);
									}

									$from = $this->db->get()->result_array();
									foreach($from as $f){
										$from_unit[] = $f['unit'];
									}
									$from_unit = array_sum($from_unit);

									// Transaksi 
									
									$transaksi_unit = array();
									$this->db->select('sub_transaksi.unit');
									$this->db->from('sub_transaksi');
									$this->db->join('transaksi','transaksi.id = sub_transaksi.id_transaksi');
									$this->db->where('cabang',$transaksi[0]['cabang']);
									$this->db->where('id_item',$i['id']);
									if(!empty($tanggal)){
										$this->db->where('transaksi.waktu >=',$tanggal);
									}
									$transaksi_1 = $this->db->get()->result_array();
									foreach($transaksi_1 as $t){
										$transaksi_unit[] = $t['unit'];
									}
									$transaksi_unit = array_sum($transaksi_unit);
									
									// Stok keluar 
									
									$stok_keluar_unit = array();
									$this->db->select('sub_stok_keluar.unit');
									$this->db->from('sub_stok_keluar');
									$this->db->join('stok_keluar','stok_keluar.id = sub_stok_keluar.id_stok_keluar');
									$this->db->where('stok_keluar.id_cabang',$transaksi[0]['cabang']);
									$this->db->where('id_item',$i['id']);
									if(!empty($tanggal)){
										$this->db->where('stok_keluar.waktu >=',$tanggal);
									}

									$stok_keluar = $this->db->get()->result_array();
									foreach($stok_keluar as $sk){
										$stok_keluar_unit[] = $sk['unit'];
									}
									$stok_keluar_unit = array_sum($stok_keluar_unit);
									
									
									// Return
									if($transaksi[0]['cabang'] == 1){
										$return_unit = array();
										$this->db->select('*');
										$this->db->from('return_item');
										$this->db->join('return_stok','return_stok.id = return_item.id_return_stok');
										$this->db->where('return_item.id_item',$i['id']);
										$this->db->where('return_stok.status',1);
										if(!empty($tanggal)){
											$this->db->where('return_stok.waktu >=',$tanggal);
										}
										$return = $this->db->get()->result_array();
										foreach($return as $r){
											$return_unit[] = $r['unit'];
										}
										$return_unit = array_sum($return_unit);										
									} else {
										$return_unit = 0;										
									}
									
									//service
									$service_unit = array();
									$this->db->select('*');
									$this->db->from('sub_service');
									$this->db->join('service','service.id = sub_service.id_service');
									$this->db->where('cabang',$transaksi[0]['cabang']);
									$this->db->where('id_item',$i['id']);
									if(!empty($tanggal)){
										$this->db->where('service.waktu >=',$tanggal);
									}
									$service = $this->db->get()->result_array();
									foreach($service as $s){
										$service_unit[] = $s['unit'];
									}
									$service_unit = array_sum($service_unit);
									
									$total = $real_stok + ($pembelian_unit + $to_unit) - ($from_unit + $transaksi_unit) - ($return_unit + $stok_keluar_unit + $service_unit);
									echo $total;
								?>
								</td>
								<td style="text-align:center; width:100px;">
									<a href="<?php echo base_url().'transaksi/add_sub_transaksi/'.$transaksi[0]["id"].'/'.$i["id"].'/0/'.$harga_jual.'/'.$total;?>">
									<span class="btn btn-success"> Tambah</span></a>
								</td>
							</tr>
							<?php	
										}	
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<?php	 
						} 
				?>	
				
				<?php if(!empty($kongsi)){ ?>
				<div class="bsc-tbl">
					<table class="table table-sc-ex" style="width:30%">
						<thead style='background-color:#2196F3'>
							<tr>
								<th>Nama</th>
								<th style="text-align:center; width:100px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$this->db->like('nama',$kongsi);
								$item = $this->db->get('item')->result_array();
								if(!empty($item)){
								foreach($item as $i){
								include 'tbh_kongsi.php';
							?>
							
							<tr>
								<td><?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?></td>
								<td style="text-align:center; width:100px;">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbh_kongsi<?php echo $i['id']; ?>">Tambah</button>	
								</td>
							</tr>
							<?php 
								}
							?> 
						</tbody>
					</table>
				</div>
				<?php		} 
						} 
				?>	
				
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th style="text-align:center; width:50px;">No</th>
								<th>Nama</th>
								<th>Serial</th>
								<th style="text-align:center; width:50px;">Unit</th>
								<th>Harga</th>
								<th>Total</th>
								<th style="text-align:center; width:200px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								$total = array();
								$x = 1;
								foreach($sub_transaksi as $st){
								$serial = $this->komputer->cek($st['id_serial'],'id','serial'); 
								$total_harga = $st['harga'] * $st['unit'];
								$total[] = $total_harga;
								if(!empty($serial[0]['status']) != 1){
									$color = '#FFFAE6';
								} else {
									$color = '#F2FFE6';
								}
							?>
							<form method="POST" action="<?php echo base_url().'transaksi/ubah_sub_transaksi/'.$st['id']; ?>">
							<tr style="background-color:<?php echo $color; ?>">
								<td ><?php echo $x++; ?></td>
								<td>
									<?php 
										$nama = $this->komputer->cek($st['id_item'],'id','item'); 
										if(!empty($nama)){
											$nama_item = $nama[0]['nama'];
											if(empty($nama_item)) $nama_item = '';
											$warna = $nama[0]['warna'];
											if(empty($warna)) $warna = '';
											$tipe = $nama[0]['tipe'];
											if(empty($tipe)) $tipe = '';
											echo $nama_item.' '.$warna.' '.$tipe;
										}	
									?>
								</td>
								<td>
								<?php if(!empty($serial)) echo $serial[0]['serial']; ?>
								</td>
								<?php 
								if(!empty($nama[0]['serial']) == 1){
									$status = 'disabled';
								} else {
									$status = '';
								}
								?>
								<td style="text-align:center; width:50px;"><input name="unit" value="<?php echo $st['unit'];?>" style="width:50px;"/></td>
								<td><input type="text" id="inputku" name="harga" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($st['harga']); ?>" style="width:150px; direction: rtl;"/></td>
								<td style="text-align:right;"><?php echo $this->komputer->format($total_harga);?></td>
								<td>
									<input type="submit" value="Ubah"/>
									<?php
										if($transaksi[0]['status'] != 1 || $check_admin){
									?>
									<a href='<?php echo base_url().'transaksi/delete_sub_transaksi/'.$st['id'].'/'.$st['id_serial']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									<?php } ?>
								</td>
							</tr>
							</form>	
								<?php } ?>
							<tr>
								<td colspan='5' style="text-align:left;"><h3>TOTAL</h3></td>
								<td style="text-align:right;"><?php echo $this->komputer->format(array_sum($total)); ?></td>
							<tr>
							<form method="POST" action="<?php echo base_url().'transaksi/bayar_sub_transaksi/'.$transaksi[0]['id']; ?>">	
							<tr>
								<td colspan='5' style="text-align:left;"><h3>BAYAR</h3></td>
								<td style="text-align:right">
									<input type="text" id="inputku" name="bayar" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($transaksi[0]['bayar']); ?>" style="width:150px; direction: rtl;"/>
									<input name="waktu" type="hidden"  value="<?php echo date('Y-m-d H:i:s', strtotime($transaksi[0]['waktu'])); ?>"/>
									
									<input name="total" type="hidden"  value="<?php echo array_sum($total); ?>"/>
								</td>	
								<td><input type="submit" value="BAYAR"/></td>
							<tr>	
							</form>	
							<tr>
								<td colspan='5' style="text-align:left;"><h3>KEKURANGAN</h3></td>
								<td style="text-align:right;"><?php echo $this->komputer->format($transaksi[0]['bayar'] - array_sum($total)); ?></td>
							<tr>		
						</tbody>
					</table>
				</div>
			</div>	

			<div class="row">
				<div class="col-lg-1"></div>
				<div class="col-lg-8">
					<div class="text-align:center;">
						<h2><button style="color:#32CD32;" type="button" data-toggle="modal" data-target="#tbhtransaksi"><li class="fa fa-cart-arrow-down"></li> TRANSAKSI BARU</button></h2>
					</div>
				</div>	
				<div class="col-lg-3">
					<div class="text-align:center;">
						<h2><a href="<?php echo base_url().'printer/printTransaksi/'.$transaksi[0]['id'];?>" onclick="window.open('<?php echo base_url().'printer/printTransaksi/'.$transaksi[0]['id'];?>', 'newwindow', 'width=600, height=700'); return false;"><button onclick="demo.showNotification('top','left')" class="fa fa-print"> CETAK DOT MATRIX</button></a></h2>
					    <h2><a href="<?php echo base_url().'printer/printTransaksiThermal/'.$transaksi[0]['id'];?>" onclick="window.open('<?php echo base_url().'printer/printTransaksiThermal/'.$transaksi[0]['id'];?>', 'newwindow', 'width=600, height=700'); return false;"><button onclick="demo.showNotification('top','left')" class="fa fa-print"> CETAK THERMAL</button></a></h2>
					</div>
				</div>
			</div>	
		</div>			
	</div>	
</div>

<!-- Ubah Transaksi -->

<div class="modal fade" id="ubhtransaksi<?php echo $transaksi[0]['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'transaksi/ubah_transaksi/'.$transaksi[0]['id'] ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php if(!empty($transaksi[0]['waktu'])) echo date('Y-m-d',strtotime($transaksi[0]['waktu'])); ?>">
					<input type="time" name="jam" value="<?php if(!empty($transaksi[0]['waktu'])) echo date('H:i:s',strtotime($transaksi[0]['waktu'])); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" placeholder="" value="<?php if(empty($transaksi[0]['nota'])){ echo $this->komputer->namaCabang($transaksi[0]['cabang']).$this->komputer->nota($transaksi[0]['id'],5);} else { echo $transaksi[0]['nota']; } ?>" style="width:200px;" disabled>
					<input name="nota" type="hidden" value="<?php echo $transaksi[0]['nota']; ?>" />
				</div>
				<div class="row">
					<label>Pembeli :</label>
					<input type="text" class="form-control border-input" name="pembeli" placeholder="" value="<?php if(!empty($deskripsi)) echo $deskripsi[0]; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Alamat :</label>
					<input type="text" class="form-control border-input" name="alamat" placeholder="" value="<?php if(!empty($deskripsi)) echo $deskripsi[1]; ?>" style="width:300px;">
				</div>
				<div class="row">
					<label>Telepone :</label>
					<input name="keterangan" class="form-control border-input" value="<?php if(!empty($deskripsi)) echo $deskripsi[2]; ?>" style="width:300px;"/>
				</div>
				<div class="row">
					<label>Cabang :</label>
					<?php if($check_admin){ ?>
					<select name="cabang" >
						<option></option>
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($transaksi[0]['cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$this->komputer->namaCabang($transaksi[0]['cabang']).'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="cabang" value="'.$transaksi[0]['cabang'].'" />';
					}
					?>
				</div>
				<?php if($check_admin){ ?>
				<div class="row">
					<label>Seles :</label>
					<select name="seles" >	
						<?php
						$this->db->where('hakakses',2);
						$user = $this->db->get('user')->result_array();
						foreach($user as $u){ ?>
							<option <?php if($transaksi[0]['id_user'] == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php } ?>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Transaksi</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>

<div class="modal fade" id="tbhitem" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'persedian_page/tambah_item' ?>">
			<div class="modal-body">
				<label>Kode</label>
				<input type="text" class="form-control border-input" name="kode" placeholder="" value="">
				
				<label>Nama</label>
				<input type="text" class="form-control border-input" name="nama" placeholder="" value="">
				
				<label>Merek</label>
				<input type="text" class="form-control border-input" name="merek" placeholder="" value="">
				
				<label>Warna</label>
				<input type="text" class="form-control border-input" name="warna" placeholder="" value="">
				
				<label>Tipe</label>
				<input type="text" class="form-control border-input" name="tipe" placeholder="" value="">
				
				<label>Kategori : </label>
					<select name="kategori" class="selectpicker">
						<?php
						$kategori = $this->db->get('kategori')->result_array();
						foreach($kategori as $k){ ?>
							<option value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
						<?php } ?>
					</select>
				
				<label>Serial</label>
				<input type="checkbox" name="serial" value="1"/>	
				<br>

				<label>Harga Jual</label>
				<input type="text" class="form-control border-input" name="harga_jual" placeholder="" value="">
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Item</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>

<div class="modal fade" id="tbhtransaksi" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'transaksi/tambah_transaksi' ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="row">
					<label>Pembeli :</label>
					<input type="text" class="form-control border-input" name="pembeli" placeholder="" value="" style="width:200px;">
				</div>
				<div class="row">
					<label>Alamat :</label>
					<input type="text" class="form-control border-input" name="alamat" placeholder="" value="" style="width:300px;">
				</div>
				<div class="row">
					<label>Telepone :</label>
					<input name="keterangan" class="form-control border-input" style="width:300px;"/>
				</div>
				<div class="row">
					<label>Cabang :</label>
					<select name="cabang" >
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php if($check_admin){ ?>
				<div class="row">
					<label>Seles :</label>
					<select name="seles" >
						<?php
						$user = $this->db->get('user')->result_array();
						foreach($user as $u){ ?>
							<option value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php } ?>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Transaksi</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	