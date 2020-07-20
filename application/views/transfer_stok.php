<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$tgl_trans = $t[0];
	$tgl_con = $t[1];
	$month = $t[2];
	$year = $t[3];
} else {
	$tgl_trans = '';
	$tgl_con = '';
	$month = date('m');
	$year = date('Y');
}
?>
<div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Transfer stok</label>
									<br>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#transferstok">Daftar Transfer Stok</button>
				</div>
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'stok/search_transfer_stok/'; ?>">
							<tr>
								<th>Tanggal Transfer <br><input type="date" name="tanggal" placeholder="" value="<?php if(!empty($tgl_trans)) echo date('Y-m-d',$tgl_trans); ?>" style="width:150px"></th>
								<th>Tanggal Konfirmasi<br><input type="date" name="tanggal" placeholder="" value="<?php if(!empty($tgl_con)) echo date('Y-m-d',$tgl_con); ?>" style="width:150px"></th>
								<th>Bulan : 
									<select name="month">
										<?php
										$month_array = array("Month","January","February","March","April","May","June","July","August","September","October","November","December");
										$count_month=count($month_array);
										for($c=1; $c<$count_month; $c+=1){ ?>
											<option <?php if($c == $month) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
										<?php
										}
										?>
									</select>
								</th>
								<th>Tahun : 
								<select name="year">
									<?php
									for($i=2019; $i<=2040; $i++){ ?>
										<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
									<?php }	?>
								</select>
								</th>
								<th><button type="submit" class="btn btn-warning">Cari</button></th>
							</tr>
							</form>	
						</thead>
					</table>
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>Tgl Transfer</th>
								<th>Tgl Konfirmasi</th>
								<th>Deskripsi</th>
								<th>Kirim</th>
								<th>Penerima</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  	
								foreach($data as $d){
								include 'ubah_transfer_stok.php';
							?>
							<tr>
								<td><?php if(date('Y',strtotime($d['tgl_trans'])) != -0001) echo date('d-m-Y H:i',strtotime($d['tgl_trans'])); ?></td>
								<td><?php if(date('Y',strtotime($d['tgl_con'])) != -0001) echo date('d-m-Y H:i',strtotime($d['tgl_con'])); ?></td>
								<td><?php if(!empty($d['deskripsi'])) echo $d['deskripsi']; ?></td>
								<td>
									<?php
										if($d['status'] == 0){
											$color = 'danger';
										} else {
											$color = 'success';
										}
									?>
									<?php echo $this->komputer->namaCabang($d['id_from']).' <button class="btn btn-'.$color.' '.$color.'-icon-notika"><i class="notika-icon notika-right-arrow"></i></button> '.$this->komputer->namacabang($d['id_to']);?>
								</td>
								<td>
									<?php 
										$nama = $this->komputer->cek($d['id_user'],'id','user'); 
										if(!empty($nama)) echo $nama[0]['name'];
									?>
								</td>
								<td>
									<button type="submit" class="btn btn-info" data-toggle="modal" data-target="#ubahtransferstok<?php echo $d['id']; ?>">Ubah</button>
									<a href='<?php echo base_url().'stok/daftar_item_stok/'.$d['id'].'^'; ?>'><span class="btn btn-success"> Detail</span></a>
									<?php if($d['status'] == 0 || $check_admin){ ?>
									<a href='<?php echo base_url().'persedian_page/delete_item/trans_stok/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');">
										<span class="btn btn-danger fa fa-trash-o"> Hapus</span>
									</a>
									<?php } ?>
								</td>
							</tr>
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

<div class="modal fade" id="transferstok" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/add_transfer_stok/'; ?>">
			<div class="modal-body">
				<?php if($check_admin){ ?>
					<div class='row'>
						<label>Tanggal Transfer : </label><br>
						<input type="date" name="tgl_trans" value="<?php echo date('Y-m-d'); ?>">
						<input type="time" name="waktu_trans" value="<?php echo date('H:i:s'); ?>">
					</div>
				<?php 
				} else {
					echo '<input name="tgl_trans" value="'.date('Y-m-d H:i:s').'"/>';
				} 	
				?>
					<div class='row'>
						<?php if($check_admin){ ?>
						<div class="col-lg-2">
							<label>Dari : </label>
							<select name="id_from">
								<?php  
									$cabang = $this->db->get('cabang')->result_array();
									foreach($cabang as $c){
										echo "<option value='".$c['id']."'>".$c['nama']."</option>";
									} 
								?>
							</select>
						</div>
						<?php } else { ?>
								<input type="hidden" name="id_from" value="<?php echo $id_cabang; ?>">
						<?php } ?>

						<div class="col-lg-2">
							<label>Ke : </label>
							<select name="id_to">
								<?php  
									if(!$check_admin) $this->db->where('id != ',$id_cabang);
									$cabang = $this->db->get('cabang')->result_array();
									foreach($cabang as $c){
										echo "<option value='".$c['id']."'>".$c['nama']."</option>";
									} 
								?>
							</select>
						</div>
					</div>	
					
					<div class="row">
						<div class="col-lg-4">
							<label>Deskripsi : </label><br>
							<textarea name="deskripsi"></textarea>
						</div>	
					</div>	
			</div>
				
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Transfer</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>