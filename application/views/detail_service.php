<?php
	$deskripsi = $service[0]['deskripsi'];
	$deskripsi = explode(',',$deskripsi);
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-9">
						<div class="form-group">
							<label>Tanggal :<?php echo date('d/m/Y',strtotime($service[0]['waktu'])); ?></label> <br>
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbh_service">Tambah Service</button> 
							<?php if($service[0]['status'] == 1) {?> 
							<span style="color:green; font-size:20px;">SERVICE SELESAI</span> 
							<?php } ?> 
							<hr>
							<form method="POST" action="<?php echo base_url().'service/search_detail_service/'; ?>">
								Ambil : <input type='text' name='kongsi' value="<?php echo $kongsi; ?>"/> <input type='hidden' name='id' value="<?php echo $service[0]['id']; ?>"/>
								<input type="submit" value="Ambil" />
							</form>	
							<form method="POST" action="<?php echo base_url().'service/search_detail_service/'; ?>">
								Cari SN : <input type='text' name='search' value="<?php echo $search; ?>"/> <input type='hidden' name='id' value="<?php echo $service[0]['id']; ?>"/> <button type="submit" class="btn btn-default">Cari Item</button>
							</form>	
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Service : <?php echo $service[0]['nota']; ?></label> <br>
							<label>Nama : <?php if(!empty($deskripsi[0])) echo $deskripsi[0]; ?></label><br>
							<label>Telepon : <?php if(!empty($deskripsi[0])) echo $deskripsi[1]; ?></label><br>
							<label>Keterangan : <?php if(!empty($deskripsi[0])) echo $deskripsi[2]; ?></label><br>
							<label>Tgl Konfirmasi : <?php if(date('Y',strtotime($service[0]['waktu_con'])) != -0001){ echo date('d/m/Y',strtotime($service[0]['waktu_con']));} ?></label><br>
							<a href='<?php echo base_url().'service/delete_service/'.$service[0]['id'].'/'.$this->uri->segment(2); ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Batalkan</span></a>
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
							<th style="text-align:center; width:100px;">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$this->db->where('id_service',$service[0]['id']);	
							$removes = $this->db->get('sub_service')->result_array();
							foreach($removes as $r){
								$this->db->where('id !=',$r['id_serial']);
							}
							$this->db->where('status',0);
							if(!$check_admin){
								$this->db->where('cabang',$service[0]['cabang']);
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
						?>
						<tr>
							<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe'].' ('.$this->komputer->namaCabang($s['cabang']).')'; ?></td>
							<td style="text-align:center;"><?php if(!empty($searching)) echo $s['serial']; ?></td>
							<td style="text-align:center; width:100px;">
								<a href="<?php echo base_url().'service/add_sub_service/'.$service[0]["id"].'/'.$item[0]["id"].'/'.$s["id"].'/'.$harga_jual;?>">
								<span class="btn btn-success"> Tambah</span></a>
							</td>
						</tr>
						<?php } } } else {
								$this->db->where('serial',0);
								$this->db->like('nama',$search);
								$item_serach = $this->db->get('item')->result_array();
								if(!empty($item_serach)){
									foreach($item_serach as $i){
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
							<td style="text-align:center; width:100px;">
								<a href="<?php echo base_url().'service/add_sub_service/'.$service[0]["id"].'/'.$i["id"].'/0/'.$harga_jual;?>">
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
								$x = 1;
								$total_service = array();
								foreach($data as $d){
								$item = $this->komputer->cek($d['id_item'],'id','item');
								$serial = $this->komputer->cek($d['id_serial'],'id','serial');
								$total_service[] = $d['unit'] * $d['harga'];
							?>
							<form method="POST" action="<?php echo base_url().'service/ubah_sub_service/'.$d['id']; ?>">
							<tr>
								<td><?php echo $x++; ?></td>
								<td>
									<?php if($d['id_item'] == 0){
										echo $d['service']; 
									} else {
										echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe'];
									} ?>
								</td>
								<td>
									<?php if($d['id_serial'] != 0){
										 echo $serial[0]['serial'];
									} 
									?>
								</td>
								<td><input name="unit" value="<?php echo $d['unit']; ?>" /></td>
								<td><input type="text" id="inputku" name="harga" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($d['harga']); ?>" style="width:150px; direction: rtl;"/></td>
								<td style="text-align:right;"><?php echo $this->komputer->format($d['unit'] * $d['harga']); ?></td>
								<td style="text-align:center;">
									<input type="submit" value="Ubah"/>
									<?php if($d['id_item'] == 0){ ?>
									<a href='<?php echo base_url().'persedian_page/delete_item/sub_service/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									<?php } elseif(empty($serial)) { ?>
									<a href='<?php echo base_url().'persedian_page/delete_item/sub_service/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									<?php } else { ?>
									<a href='<?php echo base_url().'service/delete_sub_service/'.$d['id'].'/'.$serial[0]['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									<?php } ?>
								</td>
							</tr>	
							</form>	
							<?php
								}
							?>	
							<tr style="background-color:#F5DEB3;">
								<td colspan='5' ><b>TOTAL</b></td>
								<td style="text-align:right;"><?php echo $this->komputer->format(array_sum($total_service)); ?></td>
								<td></td>
							</tr>

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
							<h2><a href="<?php echo base_url().'service/service_selesai/'.$service[0]['id'].'/'.array_sum($total_service);?>"><button>SERVICE SELESAI</button></a></h2>
							<h2><a href="<?php echo base_url().'printer/printService/'.$service[0]['id'];?>" onclick="window.open('<?php echo base_url().'printer/printService/'.$service[0]['id'];?>', 'newwindow', 'width=600, height=700'); return false;"><button onclick="demo.showNotification('top','left')" class="fa fa-print"> CETAK</button></a></h2>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>	
</div>

<div class="modal fade" id="tbh_service" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'service/add_service/'.$service[0]['id']; ?>">
			<div class="modal-body">
				<div class="row">
					<label>Service :</label>
					<textarea type="text" class="form-control border-input" name="service"></textarea>
				</div>
				<div class="row">
					<label>Biaya :</label>
					<input type="text" class="form-control border-input" name="biaya" placeholder="" value="" style="width:200px;">
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Service</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	