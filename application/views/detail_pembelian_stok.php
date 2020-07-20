<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Nota : <?php echo $pembelian[0]['nota'] ?></label> <button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhitem">Tambah Daftar Item</button>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">		
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<?php echo date('d/m/Y',strtotime($pembelian[0]['waktu'])).' '; ?><button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubah_pembelian<?php echo $pembelian[0]['id']; ?>"> Ubah</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<form method="POST" action="<?php echo base_url().'stok/search_detail_pembelian_stok/'; ?>">
									<label>Nama </label>
									<input type='text' name='nama' value="<?php echo $nama; ?>"/>
									<input type='hidden' name='id_pembelian' value="<?php echo $pembelian[0]['id']; ?>"/>
									<button type="submit" class="btn btn-default">Cari Item</button>
								</form>	
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">		
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Nota : <?php echo $pembelian[0]['nota']; ?></label> <br>
								<label>Cabang : <?php echo $this->komputer->namaCabang($pembelian[0]['id_cabang']); ?></label> <br>
								<label>Supplier : <?php echo $this->komputer->namaSupplier($pembelian[0]['id_supplier']); ?></label>
							</div>
						</div>
					</div>
			</div>
			<?php if(!empty($nama) || !empty($merek) || !empty($tipe)){ ?>
			<div class="bsc-tbl">
				<table class="table table-sc-ex" style="width:35%">
					<thead style='background-color:#2196F3'>
						<tr>
							<th>Nama</th>
							<th style="text-align:center; width:100px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if(!empty($tipe)) $this->db->like('tipe',$tipe);
							if(!empty($warna)) $this->db->like('warna',$warna);
							if(!empty($merek)) $this->db->like('merek',$merek);
							if(!empty($nama)) $this->db->like('nama',$nama);
							$item = $this->db->get('item')->result_array();
							foreach($item as $s){			
							$harga = $this->komputer->cek($s['id'],'id_item','harga');
							if(!empty($harga)){
								$harga_jual = $harga[0]['harga_jual'];
								$harga_pokok = $harga[0]['harga_pokok'];
							} else {
								$harga_jual = 0;
								$harga_pokok = 0;
							}	

						?>
						<tr>
							<td><?php echo $s['nama'].' '. $s['warna'].' '.$s['tipe']; ?></td>
							<td style="text-align:center; width:100px;">
								<a href="<?php echo base_url().'stok/add_sub_pembelian/'.$s['id'].'/'.$harga_pokok.'/'.$pembelian[0]['id']; ?>">
								<span class="btn btn-success"> Tambah</span></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php	 
					}
			?>	
			<div class="breadcomb-area">
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#FFEB3B'>
						<tr>
							<th style="text-align:center; width:50px;">No</th>
							<th>Nama</th>
							<th style="width:300px;">Serial</th>
							<th style="text-align:center; width:100px;">Unit</th>
							<th style="width:200px;">Harga</th>
							<th>Total</th>
							<th style="text-align:center; width:200px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php  
							$total = array();
							$y = 1;
							foreach($sub_pembelian as $sp){	
						?>
						<tr>
							<td><?php echo $y++; ?></td>
							<td>
							<?php 
									$nama = $this->komputer->cek($sp['id_item'],'id','item'); 
									if($nama){
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

							<?php 
							if(!empty($nama[0]['serial'])){ 
								$this->db->where('id_item',$sp['id_item']);
								$this->db->where('id_pembelian',$pembelian[0]['id']);
								$serial = $this->db->get('serial')->result_array();
								$count_serial = count($serial);
							?>

							<form method="POST" action="<?php echo base_url().'stok/add_stok_pembelian/'; ?>">
							<td>
								<input class="form-control border-input" name="serial" type="text" value=""/>
								<input name="id_pembelian" type="hidden" value="<?php echo $pembelian[0]['id']; ?>" />
								<input name="id_cabang" type="hidden" value="<?php echo $pembelian[0]['id_cabang']; ?>" />
								<input name="id_item" type="hidden" value="<?php if(!empty($nama))	 echo $nama[0]['id']; ?>" />
							</td>
							</form>	
							<td> <?php echo $count_serial; ?> </td>
							<?php } else { ?>

							<form method="POST" action="<?php echo base_url().'stok/ubah_harga_pembelian/'.$sp['id'];  ?>">
							<td></td>
							<td><input class="form-control border-input" name="unit" type="text" value="<?php echo $sp['unit']; ?>" /></td>
							</form>

							<?php 
								}
							?>
							<form method="POST" action="<?php echo base_url().'stok/ubah_harga_pembelian/'.$sp['id'];  ?>">
							<td>
								<input type="text" id="inputku" name="harga" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($sp['harga']); ?>" style="width:150px; direction: rtl;"/>
							</td>
							</form>	
							<td style="text-align:right;">
								<?php 
									if(!empty($nama)){
										if($nama[0]['serial'] == 1){
											$total_sub_transaksi = $count_serial * $sp['harga'];
										} else {
											$total_sub_transaksi = $sp['unit'] * $sp['harga'];
										}
									}	
									$total[] = $total_sub_transaksi;									
									if(!empty($nama)) echo $this->komputer->format($total_sub_transaksi);
								?>
							</td>
							<td>
								<?php if($pembelian[0]['status'] == 0 || !empty($check_admin)){ ?>
								<a href="<?php echo base_url().'stok/delete_sub_pembelian/'.$sp["id"].'/'.$pembelian[0]['id'].'/'.$sp['id_item']; ?>" onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									<?php } ?>
							</td>
						<tr>

						<?php
							if(!empty($nama)){
							if($nama[0]['serial'] != 0 ){
							foreach($serial as $sl){ 
						?>
						<form method="POST" action="<?php echo base_url().'stok/ubah_serial/'.$sl['id'];  ?>">	
						<tr>
							<td colspan='2'></td>
							<td>
								<input class="form-control border-input" name="serial" placeholder="SERIAL" type="text" value="<?php echo $sl['serial']; ?>"/>
							</td>
							<td>
								<input class="form-control border-input" name="cn" placeholder="CN" type="text" value="<?php echo $sl['cn']; ?>"/>
							</td>
							<td>
							<select name="kondisi" >
								<?php
								$kondisi_status = array('Baru','Second');
								$kondisi_count = count($kondisi_status);
								for($x=0; $x < $kondisi_count; $x++){
									var_dump($kondisi);
									if($sl['kondisi'] == $x){
										$selected = 'selected="selected"';
									} else {
										$selected = '';
									}
									echo "<option ".$selected." value='".$x."'>".$kondisi_status[$x]."</option>";
								 } ?>
							</select>
							</td>
							<td colspan='1'></td>
							<td>
								<?php if($pembelian[0]['status'] == 0 || !empty($check_admin)){ ?>
									<button type="submit" class="btn btn-info">Ubah</button>	
									<a href='<?php echo base_url().'persedian_page/delete_item/serial/'.$sl['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								<?php } ?>
							</td>
						</tr>
						</form>	
						<?php	 	}
									}
								}
							} 
						?>
						<tr>
							<td colspan='5' style="text-align:left;"><h3>TOTAL</h3></td>
							<td style="text-align:right;"><?php echo $this->komputer->format(array_sum($total)); ?></td>
						<tr>	
						<tr>
							<form method="POST" action="<?php echo base_url().'stok/bayar_sub_pembelian/'.$pembelian[0]['id'];  ?>">
							<td colspan='4' style="text-align:left;"><h3>BAYAR</h3></td>
							<td>
								<select name="tempat_kas" >
									<?php
									$this->db->where('id_pembelian',$pembelian[0]['id']);
									$cek_kas = $this->db->get('kas')->result_array();
									if(empty($cek_kas)){
										$cek_kas = 0;
									}
									$kas = $this->db->get('tempat_kas')->result_array();
									foreach($kas as $k){ ?>
										<option <?php if($cek_kas[0]['id_stor'] == $k['id']) { ?> selected="selected" <?php } ?> value='<?=$k['id']?>'><?=$k['nama']?></option>
									<?php } ?>
								</select>
							</td>
							<td style="text-align:right">
								<input type="text" id="inputku" name="bayar" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($pembelian[0]['bayar']); ?>" style="width:150px; direction: rtl;"/>
								<input name="waktu" type="hidden"  value="<?php echo $pembelian[0]['waktu']; ?>"/>
								<input name="total" type="hidden"  value="<?php echo array_sum($total); ?>"/>
								<input name="nota" type="hidden"  value="<?php echo $pembelian[0]['nota']; ?>"/>
							</td>
							<td><button class="fa fa-money"> BAYAR</button></td>
							</form>	
						<tr>
						<tr>
							<form method="POST" action="<?php echo base_url().'stok/bayar_sub_pembelian/'.$pembelian[0]['id'];  ?>">
							<td colspan='5' style="text-align:left;"><h3>KEKURANGAN</h3></td>
							<td style="text-align:right">
								<?php echo $this->komputer->format(array_sum($total) - $pembelian[0]['bayar']); ?>
							</td>
							</form>	
						<tr>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>	
	
<div class="row">
	<div class="col-lg-9">
	</div>	
	<div class="col-lg-3">
		<div class="recent-items-wp notika-shadow sm-res-mg-t-30">
			<div class="rc-it-ltd">
				<div class="recent-items-ctn">
					<div class="text-align:center;">
						<?php if($pembelian[0]['status'] == 0 || !empty($check_admin)){ ?>
							<h2><a href="<?php echo base_url().'stok/simpan_pembelian/'.$pembelian[0]['id'];  ?>"><input type="submit" value="SIMPAN" /></a></h2>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
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

<div class="modal fade" id="ubah_pembelian<?php echo $pembelian[0]['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/ubah_pembelian_stok/'.$pembelian[0]['id'] ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" value="<?php echo date('Y-m-d',strtotime($pembelian[0]['waktu'])); ?>">
				</div>
				<div class="row">
					<label>Waktu Tempo</label>
					<input type="date" name="waktu_tempo" value="<?php echo date('Y-m-d',strtotime($pembelian[0]['waktu_tempo'])); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" name="nota" value="<?php echo $pembelian[0]['nota']; ?>" style="width:200px;">
				</div>
		
				<div class="row">
					<label>Supplier :</label>
					<select name="id_supplier" class="form-control border-input">
							<?php
							$supplier = $this->db->get('supplier')->result_array();
							foreach($supplier as $s){ ?>
							<option <?php if($pembelian[0]['id_supplier'] == $s['id']){ echo 'selected="selected"'; } ?> value='<?php echo $s['id']; ?>'><?php echo $s['supplier']; ?></option>
							<?php } ?>
					</select>
				</div>
				
				<div class="row">
					<label>Cabang :</label>
					<select name="cabang" >
						<?php
						$this->db->where('id !=', 0);
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($pembelian[0]['id_cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
				</div>
				
				<div class="row">
					<label>Keterangan :</label>
					<textarea type="text" class="form-control border-input" name="keterangan"><?php echo $pembelian[0]['keterangan']; ?></textarea>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Pembelian Stok</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>