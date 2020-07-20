<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$nama = $t[0];
	$nama = str_replace("%20"," ",$nama);
	$merek = $t[1];
	$merek = str_replace("%20"," ",$merek);
	$id_kategori = $t[2];
} else {
	$nama = '';
	$merek = '';
	$id_kategori = '';
}
?>


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label>Daftar Item</label> <br>
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhitem">Tambah Daftar Item</button>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Print Stok</label>  <br>
							<form method="POST" action="<?php echo base_url().'stok/proses_laporan_stok/'; ?>">
							<select name="id_cabang" > 
								<option value='0'>SEMUA</option>
								<?php
								$cabang = $this->db->get('cabang')->result_array();
								foreach($cabang as $c){ ?>
									<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'>
									<?php echo $c['nama']; ?></option>
								<?php } ?>
							</select>
							<input type="submit" value="PRINT" />
							</form>	
						</div>
					</div>
				</div>
			</div>
			
			<div class="bsc-tbl">
					<table class="table table-sc-ex" style="width:50%">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'persedian_page/search_setting_item/'; ?>">
							<tr>
								<th>Nama <input type="text" class="form-control border-input" name="nama" placeholder="" value="<?php echo $nama; ?>" style="width:200px"></th>
								<th>Merek <input type="text" class="form-control border-input" name="merek" placeholder="" value="<?php echo $merek; ?>" style="width:200px"></th>
								<th>Kategori 
								<select name="kategori" class="selectpicker">
									<option></option>
									<?php
									$kategori = $this->db->get('kategori')->result_array();
									foreach($kategori as $k){ ?>
										<option <?php if($id_kategori == $k['id']){ echo 'selected="selected"'; } ?> value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
									<?php } ?>
								</select>
								</th>
								<th><button type="submit" class="btn btn-warning">Cari</button></th>
							</tr>
							</form>	
						</thead>
					</table>
				</div>
			<div class="breadcomb-area">
			<div class="bsc-tbl">
			 <div class="card-body">
				<table class="table table-sc-ex" width="100%" cellspacing="0">
					<thead style='background-color:#FFEB3B'>
						<tr>
							<th>No</th>
							<th>kode</th>
							<th>Nama</th>
							<th>Tipe</th>
							<th>Warna</th>
							<th>Merek</th>
							<th>Kategori</th>
							<?php if($check_admin){?>
							<th style="text-align:center">Isentif</th>
							<th style="text-align:center">H. Jual</th>
							<?php 
						  	}					   
								$cabang = $this->db->get('cabang')->result_array();
								foreach($cabang as $c){ 
									echo '<th>'.$c['nama'].'</th>';
								}
							?>
							<th>ALL</th>
							<?php if($check_admin){ ?>
							<th style="text-align:center">Actions</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php  
							if($posisi > 0){
								$y = $this->uri->segment('4') + 1;
							} else {
								$y = $this->uri->segment('3') + 1;
							}	
							foreach($data as $d){
							$cek = $this->komputer->cek($d['id'],'id_item','harga');
							$itensif = $this->komputer->cek($d['id'],'id_item','itensif');	
							if(!empty($cek)){
								$harga_pokok = $cek[0]['harga_pokok'];
								$harga_jual = $cek[0]['harga_jual'];
							} else {
								$harga_pokok = 0;
								$harga_jual = 0;
							}	
								
							if(!empty($itensif)){
								$ins_b = $itensif[0]['ins_b'];
								$ins_s = $itensif[0]['ins_s'];
							} else {
								$ins_b = 0;
								$ins_s = 0;
							}		
							include 'ubhitem.php';
							include 'itensif.php';
							include 'harga_jual.php';
						?>
						<tr>
							<td style="width:2%;"><?php echo $y++; ?></td>
							<td><?php echo $d['kode']; ?></td>
							<?php if($check_admin){ ?>
							<td><a href="<?php echo base_url().'stok/history_pembelian_stok/'.$d['id'].'^^^^^'; ?>"><?php echo $d['nama']; ?></a></td>
							<?php } else { ?>
							<td><?php echo $d['nama']; ?></td>
							<?php } ?>
							<td><?php echo $d['tipe']; ?></td>
							<td><?php echo $d['warna']; ?></td>
							<td><?php echo $d['merek']; ?></td>
							<td><?php echo $this->komputer->namaKategori($d['id_kategori']); ?></td>
							<?php if($check_admin){?>
							<td style="text-align:center">
								<?php 
								   if(!empty($ins_b) || ($ins_s)){
										$value = $ins_b.','.$ins_s;
									} else {
								   		$value = "Add Isentif";
								   	}
								?>
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#itensif<?php echo $d['id']; ?>"><?php echo $value; ?></button>	
							</td>
							<td style="text-align:center">
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#hargajual<?php echo $d['id']; ?>">
									<?php if(!empty($harga_jual)){ echo $this->komputer->format($harga_jual).','.$this->komputer->format($harga_pokok); } else {echo 'Nominal'; } ?>
								</button>	
							</td>
							<?php } ?>
							<?php 
								$cabang = $this->db->get('cabang')->result_array();
								$total_keseluruhan = array();
								foreach($cabang as $c){
									if($d['serial'] == 1){
										$this->db->where('cabang',$c['id']);
										$this->db->where('status',0);
										$this->db->where('id_item',$d['id']);
										$total_stok = $this->db->get('serial')->num_rows();	
										$total_keseluruhan[] = $total_stok;
										$url = base_url().'stok/tambah_stok/'.$d["id"].'^'.$c["id"].'^^';
										echo "<td><a href='".$url."'</a>".$total_stok."</td>";
									} else {
										//stok opname
										
										$this->db->where('id_cabang',$c['id']);
										$stok_opname = $this->db->get('stok_opname')->result_array();
										if(!empty($stok_opname)){
											$tanggal = $stok_opname[0]['waktu'];
											$waktu = strtotime($stok_opname[0]['waktu']);
											if(date('Y',$waktu) != -00001){
												
												$this->db->where('id_stok_opname',$stok_opname[0]['id']);
												$this->db->where('id_item',$d['id']);
												$real_stok = $this->db->get('detail_stok_opname')->result_array();								
												$real_stok = $real_stok[0]['stok'];
											}
										} else {
											$tanggal = '';
											$real_stok = 0;
										}

										
										// pembelian
										$pembelian_unit = array();
										$this->db->select('*');
										$this->db->from('sub_pembelian');
										$this->db->join('pembelian','pembelian.id = sub_pembelian.id_pembelian');
										$this->db->where('pembelian.id_cabang',$c['id']);
										$this->db->where('id_item',$d['id']);
										
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
										$this->db->where('id_to',$c['id']);
										$this->db->where('id_item',$d['id']);
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
										$this->db->where('id_from',$c['id']);
										$this->db->where('id_item',$d['id']);
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
										$this->db->where('cabang',$c['id']);
										$this->db->where('id_item',$d['id']);
										
										if(!empty($tanggal)){
											$this->db->where('transaksi.waktu >=',$tanggal);
										}

										$transaksi = $this->db->get()->result_array();
										foreach($transaksi as $t){
											$transaksi_unit[] = $t['unit'];
										}
										$transaksi_unit = array_sum($transaksi_unit);
										
										// Stok keluar 
										
										$stok_keluar_unit = array();
										$this->db->select('sub_stok_keluar.unit');
										$this->db->from('sub_stok_keluar');
										$this->db->join('stok_keluar','stok_keluar.id = sub_stok_keluar.id_stok_keluar');
										$this->db->where('stok_keluar.id_cabang',$c['id']);
										$this->db->where('id_item',$d['id']);
										
										if(!empty($tanggal)){
											$this->db->where('stok_keluar.waktu >=',$tanggal);
										}

										$stok_keluar = $this->db->get()->result_array();
										foreach($stok_keluar as $sk){
											$stok_keluar_unit[] = $sk['unit'];
										}
										$stok_keluar_unit = array_sum($stok_keluar_unit);
										
										
										// Return
										if($c['id'] == 1){
											$return_unit = array();
											$this->db->select('*');
											$this->db->from('return_item');
											$this->db->join('return_stok','return_stok.id = return_item.id_return_stok');
											$this->db->where('return_item.id_item',$d['id']);
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
										$this->db->where('cabang',$c['id']);
										$this->db->where('id_item',$d['id']);
										
										if(!empty($tanggal)){
											$this->db->where('service.waktu >=',$tanggal);
										}

										$service = $this->db->get()->result_array();
										foreach($service as $s){
											$service_unit[] = $s['unit'];
										}
										$service_unit = array_sum($service_unit);
										
										$total = $real_stok + ($pembelian_unit + $to_unit) - ($from_unit + $transaksi_unit) - ($return_unit + $stok_keluar_unit + $service_unit);
										$total_keseluruhan[] = $total;
										echo '<td>'.$total.'</td>';
									}
								}
							?>
							<td>
								<?php
								if($d['serial'] == 1){
								?>
								<a href='<?php echo base_url().'stok/tambah_stok/'.$d['id'].'^0^^'; ?>'><?php echo array_sum($total_keseluruhan); ?></a>
								<?php } else {
									
											$this->db->where('id_item',$d['id']);	
											$harga_pokok_array = $this->db->get('sub_pembelian')->result_array(); 

											if(!empty($harga_pokok_array)){
												$harga_pokok = $harga_pokok_array[0]['harga'];
											} else {
												$harga_pokok = 0;
											}	

											$avg_harga = array();
											if($d['serial'] == 0 && !empty($harga_pokok_array)){
											foreach($harga_pokok_array as $h){
												$avg_harga[] = $h['harga'];						
											}
												$count_harga = count($avg_harga);
												$avg_harga = array_sum($avg_harga);
												$harga_pokok = $avg_harga / $count_harga;	
											}
											$total_pembelian[] = $harga_pokok;
									
									echo '<button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="'.$this->komputer->format($harga_pokok).'">'.array_sum($total_keseluruhan).'</button>';
								}
								?>
							</td>
							<?php if($check_admin){ ?>
							<td style="text-align:center">
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhitem<?php echo $d['id']; ?>">Ubah</button>	
								<a href='<?php echo base_url().'persedian_page/delete_barang/item/'.$d['id']; ?>' onclick="return confirm('Menghapus daftar item dapat menghilangkan semua history?');">
									<span class="btn btn-danger fa fa-trash-o"> Hapus</span>
								</a>
							</td>
							<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			</div>
			</div>
		</div>
	</div>
	
	<div class="text-center">
		<?php
		
			if($posisi > 0){
				$page = $this->uri->segment(4) - 10;
				$href = base_url().'persedian_page/'.$this->uri->segment(2).'/'.$link.'/'.$page;
			} else {
				$page = $this->uri->segment(3) - 10;
				$href = base_url().'persedian_page/'.$this->uri->segment(2).'/'.$page;
			}
			echo $this->pagination->create_links();
			?>
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