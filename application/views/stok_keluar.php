<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$nota = $t[0];
	$tanggal = $t[1];
	$month = $t[2];
	$year = $t[3];
	$status = $t[4];
	$cabang2 = $t[5];
} else {
	$nota = '';
	$tanggal = '';
	$month = date('m');
	$year = date('Y');
	$status = '';
	$cabang2 = '';
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
									<label>Stok Keluar</label>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhstokkeluar"> Add Stok keluar</button>
				</div>
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'stok/search_stok_keluar/'; ?>">
							<tr>
								<th>Nota <input type="text" class="form-control border-input" name="nota" placeholder="" value="<?php echo $nota; ?>" style="width:100px"></th>
								<th>Tanggal <br><input type="date" name="tanggal" placeholder="" value="<?php if(!empty($tanggal)) echo date('Y-m-d',$tanggal); ?>" style="width:150px"></th>
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
								<th>Cabang :
									<?php if(!empty($check_admin)){ ?>
									<select name="cabang" >
										<option></option>
										<?php
										$this->db->where('id !=', 0);
										$cabang = $this->db->get('cabang')->result_array();
										foreach($cabang as $c){ ?>
											<option <?php if($cabang2 == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
										<?php } ?>
									</select>
									<?php } else {
										echo '<input value="'.$nama_cabang.'" disabled/>';
										echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
									}
									?>
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
								<th>No</th>
								<th>Tanggal</th>
								<th>Nota</th>
								<th>Keterangan</th>
								<th style="text-align:center">Kondisi</th>
								<th style="text-align:center">Detail Item</th>
								<th style="text-align:center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								if($posisi > 0){
									$x = $this->uri->segment('4') + 1;
								} else {
									$x = $this->uri->segment('3') + 1;
								}	
								foreach($data as $d){
								if($d['status'] == 0){
									$status = '<h2><span class="fa fa-exclamation-triangle" style="color:red;"></span></h2>';
								} elseif($d['status'] == 1) {
									$status = '<h2><span class="color-info fa fa-check-circle" style="color:green;"></span></h2>';
								}
								include 'ubah_stok_keluar.php';
							?>
							<tr>
								<td style="width:2%;"><?php echo $x++ ?></td>
								<td><?php echo date('d M Y',strtotime($d['waktu'])); ?></td>
								<td><?php echo $d['nota']; ?></td>
								<td><?php echo $d['keterangan']; ?></td>
								<td style="text-align:center"><?php echo $status; ?></td>
								<td style="text-align:center">
									<a href='<?php echo base_url().'stok/detail_stok_keluar/'.$d['id']; ?>'><span class="btn btn-success"> Detail</span></a>
								</td>
								<td style="text-align:center">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubah_stok_keluar<?php echo $d['id']; ?>">Ubah</button>	
									<a href='<?php echo base_url().'stok/stok_keluar/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
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
				$href = base_url().'transaksi/'.$this->uri->segment(2).'/'.$link.'/'.$page;
			} else {
				$page = $this->uri->segment(3) - 10;
				$href = base_url().'transaksi/'.$this->uri->segment(2).'/'.$page;
			}
			echo $this->pagination->create_links();
			?>
	</div>
</div>	

<div class="modal fade" id="tbhstokkeluar" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/tambah_stok_keluar' ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" name="nota" placeholder="" value="" style="width:200px;">
				</div>
				<div class="row">
					<label>Cabang :</label>
					<select name="cabang" >
						<?php
						$this->db->where('id !=', 0);
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
				</div>
	
				<div class="row">
					<label>Keterangan :</label>
					<textarea type="text" class="form-control border-input" name="keterangan"></textarea>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Stok Keluar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	