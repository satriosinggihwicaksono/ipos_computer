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
	$cabang = $t[2];
	$month = $t[3];
	$year = $t[4];
	$seles = $t[5];
} else {
	$nota = '';
	$tanggal = '';
	$cabang = '';
	$month = date('m');
	$year = date('Y');
	$seles = '';
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
									<label>Daftar Transaksi</label>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhtransaksi">Tambah Transaksi</button>
				</div>
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'transaksi/search_transaksi/'; ?>">
							<tr>
								<th>Nota <input type="text" class="form-control border-input" name="nota" placeholder="" value="<?php echo $nota; ?>" style="width:100px"></th>
								<th>Tanggal <br><input type="date" name="tanggal" placeholder="" value="<?php if(!empty($tanggal)) echo date('Y-m-d',$tanggal); ?>" style="width:150px"></th>
								<th>Bulan : 
									<select name="month">
										<?php
										$month_array = array("","January","February","March","April","May","June","July","August","September","October","November","December");
										$count_month=count($month_array);
										for($c=0; $c<$count_month; $c+=1){ ?>
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
								<th>Cabang <br>
									<?php if(!empty($check_admin)){ ?>
									<select name="cabang" >
										<option></option>
										<?php
										$cabang_form = $this->db->get('cabang')->result_array();
										foreach($cabang_form as $c){ ?>
											<option <?php if($cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
										<?php } ?>
									</select>
									<?php } else {
										echo '<input value="'.$nama_cabang.'" disabled/>';
										echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
									}
									?>
								</th>
								<th>Seles : <br />
								<select name="seles">
								<option></option>	
									<?php
									$user = $this->db->get('user')->result_array();
									foreach($user as $u){ ?>
										<option <?php if($seles == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
									<?php } ?>
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
								<th>No</th>
								<th>Tanggal</th>
								<th>Waktu</th>
								<th>Nota</th>
								<th>Pembeli</th>
								<th>Seles</th>
								<th>Cabang</th>
								<th>Total</th>
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
								} else {
									$status = '<h2><span class="color-info fa fa-check-circle" style="color:green;"></span></h2>';
								}	
								include 'ubhtransaksi.php';
							?>
							<tr>
								<td style="width:2%;"><?php echo $x++ ?></td>
								<td><?php echo date('Y-m-d',strtotime($d['waktu'])); ?></td>
								<td><?php echo date('H:s',strtotime($d['waktu'])); ?></td>
								<td>
									<?php 
										if(!empty($d['nota'])){ 
											echo $d['nota']; 
										} else {
											echo $this->komputer->namaCabang($d['cabang']).$this->komputer->nota($d['id'],5);
										} 
									?>
								</td>
								<td><?php if(!empty($deskripsi)) echo $pembeli; ?></td>
								<td><?php echo $this->komputer->namaUser($d['id_user']); ?></td>
								<td><?php echo $this->komputer->namaCabang($d['cabang']); ?></td>
								<td><?php echo $this->komputer->format($d['total']); ?></td>
								<td style="text-align:center"><?php echo $status; ?></td>
								<td style="text-align:center">
									<a href='<?php echo base_url().'transaksi/detail_transaksi/'.$d['id']; ?>'><span class="btn btn-success"> Detail</span></a>
								</td>
								<td style="text-align:center">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhtransaksi<?php echo $d['id']; ?>">Ubah</button>	
									<?php
										if($d['status'] != 1 || $check_admin){
									?>
									<a href='<?php echo base_url().'transaksi/delete_transaksi/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
									<?php 
										}
									?>
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