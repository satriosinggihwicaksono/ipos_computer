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

$supplier = $this->db->get('supplier')->result_array();
?>
<div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Return stok</label>
									<br>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#return_stok">Daftar Return Stok</button>
				</div>
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'stok/search_return_stok/'; ?>">
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
								<th>Tgl Return</th>
								<th>Tgl Konfirmasi</th>
								<th>Supplier</th>
								<th>Nota</th>
								<th>Keterangan</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  	
								$proses = array('UNPOESES','KIRIM','TERIMA');
								foreach($data as $d){
								include 'ubah_return.php';
								include 'proses_return.php';
								
								if($d['status'] == 0){
									$warna = 'danger';
								} elseif($d['status'] == 1){
									$warna = 'warning';
								} else {
									$warna = 'success';
								}
							?>
							<tr>
								<td><?php if(date('Y',strtotime($d['waktu'])) != -0001) echo date('d-m-Y',strtotime($d['waktu'])); ?></td>
								<td><?php if(date('Y',strtotime($d['waktu_a'])) != -0001) echo date('d-m-Y',strtotime($d['waktu_a'])); ?></td>
								<td><?php echo $this->komputer->namaSupplier($d['id_supplier']); ?></td>
								<td><?php if(!empty($d['nota'])) echo $d['nota']; ?></td>
								<td><?php if(!empty($d['keterangan'])) echo $d['keterangan']; ?></td>
								<td>
									<?php if($d['status'] !=  2){ ?>
										<button type="button" class="btn btn-<?php echo $warna; ?>" data-toggle="modal" data-target="#proses_return<?php echo $d['id']; ?>"><?php echo $proses[$d['status']]; ?></button>
									<?php } else { ?>
										<button type="button" class="btn btn-<?php echo $warna; ?>"><?php echo $proses[$d['status']]; ?></button>
									<?php } ?>
								</td>
								<td>
									<button type="submit" class="btn btn-info" data-toggle="modal" data-target="#ubah_return<?php echo $d['id']; ?>">Ubah</button>
									<a href='<?php echo base_url().'stok/daftar_return_stok/'.$d['id'].'^'; ?>'><span class="btn btn-success"> Detail</span></a>
									<?php if($d['status'] == 0 || $check_admin){ ?>
									<a href='<?php echo base_url().'persedian_page/delete_item/return_stok/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');">
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

<div class="modal fade" id="return_stok" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/add_return_stok/'; ?>">
			<div class="modal-body">
					<div class='row'>
						<label>Tanggal Return : </label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d'); ?>">
					</div>
					<div class='row'>
						<label>Nota : </label><br>
						<input type="text" name="nota" value="">
					</div>
				<div class="row">
					<label>Supplier :</label><br>
					<select name="id_supplier">
						<?php 
							foreach($supplier as $s){
						?>
							<option value="<?php echo $s['id']; ?>"><?php echo $s['supplier']; ?></option>
						<?php 
							}
						?>
					</select>	
				</div>	
				<div class="row">
						<label>Keterangan : </label><br>
						<textarea name="keterangan"></textarea>
				</div>	
			</div>
				
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Return</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>