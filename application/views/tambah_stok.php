<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$id = $t[0];
	$id_cabang = $t[1];
	$search = $t[2];
	$kondisi = $t[3];
} else {
	$id = '';
	$id_cabang = '';
	$search = '';
	$kondisi = '';
}

if(!empty($check_admin)){
	$disabled = "";
} else {
	$disabled = "disabled";
}
$url = $this->uri->segment(3);
$item = $this->komputer->cek($url,'id','item');
?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Tambah Stok <?php echo $this->komputer->namaCabang($id_cabang); ?> </label>
									<h2><?php echo $item[0]['nama'].' '.$item[0]['warna'].' '.$item[0]['tipe']; ?></h2>
									<form method="POST" action="<?php echo base_url().'stok/search_tambah_stok/' ?>" >
										<input type="hidden" name='id' value="<?php echo $id; ?>"/>
										<input type="hidden" name='id_cabang' value="<?php echo $id_cabang; ?>"/>
										<input type="text" name="serial" value="<?php echo $search; ?>"/>
										<select name="kondisi" >
										<?php
										$kondisi_status = array('Baru','Second');
										$kondisi_count = count($kondisi_status);
										for($x=0; $x < $kondisi_count; $x++){
											if($kondisi == $x){
												$selected = 'selected="selected"';
											} else {
												$selected = '';
											}
											echo "<option ".$selected." value='".$x."'>".$kondisi_status[$x]."</option>";
										 } ?>
										</select>
										<button type="submit" class="btn btn-success">Search</button>
									</form>	
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label>Serial</label>
									<form method="POST" action="<?php echo base_url().'stok/add_stok/'; ?>">
											<input type="text"  name="serial" placeholder="" style="width:200px" value="" /> <input type="submit" value="Tambah Stok"/>
											<input type="hidden" name="id_item" value="<?php echo $id; ?>">
											<input type="hidden" name="id_cabang" value="<?php echo $id_cabang; ?>">
									</form>	
								</div>
							</div>
							
						</div>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhstok">Tambah Daftar Stok</button>
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>No</th>
								<th>Serial</th>
								<th>CN</th>
								<th>Kondisi</th>
								<?php if($check_admin || $check_gudang){ ?>
									<th>Harga Pokok</th>
									<th>Actions</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php  
								if($posisi > 0){
									$v = $this->uri->segment('4') + 1;
								} else {
									$v = $this->uri->segment('3') + 1;
								}	
								foreach($data as $d){
							?>
							<form method="POST" action="<?php echo base_url().'stok/ubah_serial/'.$d['id']; ?>" >
							<tr>
								<td style="width:2%;"><?php echo $v++ ?></td>
								<td><input name="serial" value= "<?php echo $d['serial']; ?>" <?php echo $disabled; ?>/></td>
								<td><input name="cn" value= "<?php echo $d['cn']; ?>" <?php echo $disabled; ?>/></td>
								<td>
								<select name="kondisi" >
										<?php
										$kondisi_status = array('Baru','Second');
										$kondisi_count = count($kondisi_status);
										for($x=0; $x < $kondisi_count; $x++){
											if($d['kondisi'] == $x){
												$selected = 'selected="selected"';
											} else {
												$selected = '';
											}
											echo "<option ".$selected." value='".$x."'>".$kondisi_status[$x]."</option>";
										 } ?>
									</select>
								</td>
								<?php if($check_admin || $check_gudang){ ?>
									<td>
										<?php
											if($item[0]['serial'] == 1){
												$this->db->where('id_item',$d['id_item']);
												$this->db->where('id_pembelian', $d['id_pembelian']);
											} else {
												$this->db->where('id_item',$d['id']);
											}	
											$harga_pokok_array = $this->db->get('sub_pembelian')->result_array(); 

											if(!empty($harga_pokok_array)){
												$harga_pokok = $harga_pokok_array[0]['harga'];
											} else {
												$harga_pokok = 0;
											}	

											$avg_harga = array();
											if($item[0]['serial'] == 0 && !empty($harga_pokok_array)){
											foreach($harga_pokok_array as $h){
												$avg_harga[] = $h['harga'];						
											}
												$count_harga = count($avg_harga);
												$avg_harga = array_sum($avg_harga);
												$harga_pokok = $avg_harga / $count_harga;	
											}
											$total_pembelian[] = $harga_pokok;
											echo $this->komputer->format($harga_pokok);
									?>
									</td>
									<td>
										<button type="submit" class="btn btn-info">Ubah</button>	
										<a href='<?php echo base_url().'persedian_page/delete_item/serial/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									</td>
								<?php } ?>
							</tr>
							</form>	
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center">
		<?php
		
			if($posisi > 0){
				$page = $this->uri->segment(4) - 10;
				$href = base_url().'stok/'.$this->uri->segment(2).'/'.$link.'/'.$page;
			} else {
				$page = $this->uri->segment(3) - 10;
				$href = base_url().'stok/'.$this->uri->segment(2).'/'.$page;
			}
			echo $this->pagination->create_links();
		?>
	</div>
</div>	

<div class="modal fade" id="tbhstok" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/add_stok/'; ?>">
			<div class="modal-body">
				<label>Serial</label>
				<input type="text" class="form-control border-input" name="serial" placeholder="" value="">
				<input type="hidden" name="id_item" value="<?php echo $id; ?>">
				<input type="hidden" name="id_cabang" value="<?php echo $id_cabang; ?>">
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Stok</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>