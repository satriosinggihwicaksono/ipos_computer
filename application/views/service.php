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
	$teknisi = $t[5];
} else {
	$nota = '';
	$tanggal = '';
	$cabang = '';
	$month = date('m');
	$year = date('Y');
	$teknisi = '';
}
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Daftar Service</label>
							</div>
						</div>
					</div>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbh_service">Tambah Service</button>
			</div>
			<div class="breadcomb-area">
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#FFFFFFF'>
						<form method="POST" action="<?php echo base_url().'service/search_service/'; ?>">
						<tr>
							<th>Nota <input type="text" class="form-control border-input" name="nota" placeholder="" value="<?php echo $nota; ?>" style="width:100px"></th>
							<th>Tanggal <br><input type="date" name="tanggal" placeholder="" value="<?php if(!empty($tanggal)){ echo date('Y-m-d',$tanggal); } ?>" style="width:150px"></th>
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
							<th>Cabang
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
							<th>
							<div class="row">
								<label>Teknisi :</label>
								<select name="teknisi">
									<option></option>
									<?php
									$this->db->where('hakakses',2);
									$user = $this->db->get('user')->result_array();
									foreach($user as $u){ ?>
										<option <?php if($teknisi == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
									<?php } ?>
								</select>
							</div>
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
							<th>Tgl Konfirmasi</th>
							<th>Nota</th>
							<th>Teknisi</th>
							<th>Cabang</th>
							<th>Status</th>
							<th style="text-align:center">Actions</th>
						</tr>
					</thead>
					<tbody>	
							<?php 
								$x = 1;
								foreach($data as $d){
								$deskripsi = $d['deskripsi'];
								$t = explode(',',$deskripsi);
								$nama = $t[0];
								$telepon = $t[1];
								$keterangan = $t[2];
								include 'ubhservice.php';
							?>
						<tr>
							<td><?php echo $x++; ?></td>
							<td><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
							<td><?php if(date('Y',strtotime($d['waktu_con'])) != -0001) echo date('d/m/Y',strtotime($d['waktu_con'])); ?></td>
							<td><?php echo $d['nota']; ?></td>
							<td><?php echo $this->komputer->namaUser($d['teknisi']); ?></td>
							<td><?php echo $this->komputer->namaCabang($d['cabang']); ?></td>
							<td><?php if($d['status'] == 1){ echo '<span style="color:green; font-size:18px;">SERVICE SELESAI</span>'; } else { echo '<span style="color:red; font-size:18px;">BELUM SELESAI</span>'; }  ?></td>
							<td style="text-align:center;">
								<a href='<?php echo base_url().'service/detail_service/'.$d['id']; ?>'><span class="btn btn-success"> Detail</span></a>
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubh_service<?php echo $d['id']; ?>">Ubah</button>
								<?php if($check_admin){ ?>
								<a href='<?php echo base_url().'service/delete_service/'.$d['id'].'/'.$this->uri->segment(2); ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								<?php } ?>
							</td>
						</tr>	
							<?php 
								} 
							?>
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

<div class="modal fade" id="tbh_service" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'service/tambah_service' ?>">
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
					<label>Nama :</label>
					<input type="text" class="form-control border-input" name="nama" placeholder="" value="" style="width:200px;">
				</div>
				<div class="row">
					<label>Telepon :</label>
					<input type="text" class="form-control border-input" name="telepon" placeholder="" value="" style="width:300px;">
				</div>
				<div class="row">
					<label>Keterangan :</label>
					<textarea name="keterangan" class="form-control border-input"></textarea>
				</div>
				<div class="row">
					<label>Cabang :</label>
					<?php if($check_admin){ ?>
					<select name="cabang">
						<option></option>
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$nama_cabang.'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="cabang" value="'.$id_cabang.'" />';
					}
					?>
				</div>
				<?php if($check_admin){ ?>
				<div class="row">
					<label>Teknisi :</label>
					<select name="teknisi" >
						<?php
						$this->db->where('hakakses',2);
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
				<button type="submit" class="btn btn-default">Tambah Service</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	