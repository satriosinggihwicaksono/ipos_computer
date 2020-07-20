<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$penerima = $t[0];
	$tgl_kirim = $t[1];
	$tgl_con = $t[2];
	$month = $t[3];
	$year = $t[4];
} else {
	$penerima = '';
	$tgl_kirim = '';
	$tgl_con = '';
	$month = date('m');
	$year = date('Y');
}
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Penerima stok</label>
							<br>
						</div>
					</div>
				</div>
			</div>
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#FFFFFFF'>
						<form method="POST" action="<?php echo base_url().'stok/search_penerima_stok/'; ?>">
						<tr>
							<th>Seles :
								<select name="penerima" >
									<option></option>
									<?php
									$this->db->where('hakakses',2);
									$user = $this->db->get('user')->result_array();
									foreach($user as $u){ ?>
										<option <?php if($penerima == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
									<?php } ?>
								</select>

							</th>
							<th>Tanggal Transfer <br><input type="date" name="tgl_kirim" placeholder="" value="<?php if(!empty($tgl_kirim)) echo date('Y-m-d',$tgl_kirim); ?>" style="width:150px"></th>
							<th>Tanggal Konfirmasi<br><input type="date" name="tgl_con" placeholder="" value="<?php if(!empty($tgl_con)) echo date('Y-m-d',$tgl_con); ?>" style="width:150px"></th>
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
							<th>Status</th>
							<th>Penerima</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php  	
							foreach($data as $d){
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
								<?php if($d['status'] == 0){ ?>
									<a href='<?php echo base_url().'stok/terima_stok/'.$d['id'].'/'.$id_username.'/'.$d['id_to']; ?>'><span class="btn btn-info">Terima</span></a>
								<?php } ?>
									<a href='<?php echo base_url().'stok/daftar_item_stok/'.$d['id'].'^'; ?>'><span class="btn btn-success">Detail</span></a>
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