<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$cabang2 = $t[0];
	$nama_cabang2 = $this->komputer->namaCabang($t[0]);
	$month = $t[1];
	$year = $t[2];
} else {
	$cabang2 = '';
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
									<label>Tempat Kas</label>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhkas">Tambah Kas</button>
				</div>
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'kas/search_kas_pusat/'; ?>">
							<tr>
								<th>Tempat Kas :
									<?php if(!empty($check_admin)){ ?>
									<select name="cabang" >
										<option value='0'>SEMUA</option>
										<?php
										$tempat_kas = $this->db->get('tempat_kas')->result_array();
										foreach($tempat_kas as $t){ ?>
											<option <?php if($cabang2 == $t['id']){ echo 'selected="selected"'; } ?> value='<?php echo $t['id']; ?>'><?php echo $t['nama']; ?></option>
										<?php } ?>
									</select>
									<?php } else {
										echo '<input value="'.$nama_cabang.'" disabled/>';
										echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
									}
									?>
								</th>
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
						<thead style='background-color:#99ccff'>
							<tr>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th style="text-align:center; width:100px;">Dari</th>
								<th style="text-align:center; width:100px;">Nominal</th>
								<th style="text-align:center; width:100px;">Total</th>
								<th style="text-align:center; width:40px;">Ubah</th>
								<th style="text-align:center; width:40px;">Hapus</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$saldo_array = array();
								$first_date = strtotime('2019-01-01');
								$second_date = mktime(0, 0, 0, $month, 1, $year);
							
								if($check_admin && !empty($cabang)){
									$this->db->where('id_stor', $cabang2);
								} 
								$this->db->where('waktu >=', date('Y-m-d', $first_date));
								$this->db->where('waktu <', date('Y-m-d',$second_date));
								$this->db->where('status !=', 1);
								$saldo_awal = $this->db->get('kas')->result_array();
	
								foreach($saldo_awal as $s){
								if($s['status'] == 2){
										$saldo_array[] = $s['saldo'];
									} else {
										$saldo_array[] = $s['saldo'] * -1;
									}
								}
								$total_saldo = array_sum($saldo_array);
								
								$total_kas = array();
								$total_kas[] = $total_saldo;
								$kredit = array();
								$debit = array();
								?>
								<tr>
									<td style="background-color:#FFFACD;"></td>
									<td style="background-color:#FFFACD;"><b>Saldo Awal Bulan</b></td>
									<td style="background-color:#FFFACD;"></td>
									<td colspan='4' style="background-color:#FFFACD; ?>;"><b><?php echo $this->komputer->format($total_saldo); ?></b></td>
								</tr>	
							<?php
								foreach($data as $d){	
								if($d['status'] == 2){
									$warna = '#90EE90';
								} else {
									if((empty($d['id_cabang']))){
										$warna = '#90EE90';
									} elseif($d['id_cabang'] == 100) {
										$warna = '#ffffcc';
									}	
								}
								if($d['id_stor'] == 0){
									include 'ubhkas.php';
								} else {
									include 'ubhstorkas.php';
								}
							?>
							<tr>
								<td style="background-color:<?php echo $warna; ?>;"><?php echo date('d/m',strtotime($d['waktu'])); ?></td>
								<td style="background-color:<?php echo $warna; ?>;"><?php echo $d['deskripsi']; ?></td>
								<td style="background-color:<?php echo $warna; ?>;"><?php echo $this->komputer->namaCabang($d['id_cabang']); ?></td>
								<td style="text-align:right; background-color:<?php echo $warna; ?>;"><?php echo $this->komputer->format($d['saldo']); ?></td>
								<td style="text-align:right; background-color:<?php echo $warna; ?>;">
								 <?php 
									if($d['status'] == 2){
										if($d['id_cabang'] == 100 || $d['status'] == 2){
											$nominal = $d['saldo'];
										} 
										$debit[] = $nominal;
									} else {
										$nominal = $d['saldo']  * -1;
										$kredit[] = $nominal;
									}	
									
									$total_kas[] = (int)$nominal;
									
									echo $this->komputer->format(array_sum($total_kas));
								 ?>
								</td>
								<td style="text-align:center; width:40px; background-color:<?php echo $warna; ?>;">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhkas<?php echo $d['id']; ?>">Ubah</button>
								</td>
								<td style="text-align:center; width:40px; background-color:<?php echo $warna; ?>;">
									<a href='<?php echo base_url().'persedian_page/delete_item/kas/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan='4' style="text-align:left; background-color:#F5DEB3;"><h3>TOTAL</h3></td>
								<td style="text-align:right; background-color:#F5DEB3;"><?php echo $this->komputer->format(array_sum($total_kas)); ?></td>
								<td colspan='2' style="background-color:#F5DEB3;"></td>
							<tr>	
						</tbody>
					</table>
				</div>
			</div>
				
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
						<div class="website-traffic-ctn">
							<h2>Rp.<span class="counter"><?php echo number_format(array_sum($debit)); ?></span>,-</h2>
							<p>DEBIT</p>
						</div>
						<div class="sparkline-bar-stats1">9,4,8,6,5,6,4,8,3,5,9,5</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
						<div class="website-traffic-ctn">
							<h2>Rp.<span class="counter"><?php echo number_format(array_sum($kredit)); ?></span>,-</h2>
							<p>KREDIT</p>
						</div>
						<div class="sparkline-bar-stats2">1,4,8,3,5,6,4,8,3,3,9,5</div>
					</div>
				</div>
			</div>	
				
		</div>
	</div>
</div>	

<div class="modal fade" id="tbhkas" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'kas/tambah_kas_pusat' ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="tanggal" placeholder="" value="<?php echo date('Y-m-d'); ?>">
					<input type="hidden" name="waktu" placeholder="" value="<?php echo date('H:i:s'); ?>">
				</div>
				<div class="row">
					<label>Tempat Kas :</label>
					<?php if($check_admin){ ?>
					<select name="id_cabang">
						<?php
						$cabang = $this->db->get('tempat_kas')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$nama_cabang.'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="id_cabang" value="'.$id_cabang.'" />';
					}
					?>
				</div>
				<div class="row">
					<label>Keterangan :</label>
					<input type="text" class="form-control border-input" name="deskripsi" placeholder="" value="" style="width:250px;">
				</div>
				<div class="row">
					<label>Nominal :</label>
					<input type="text" class="form-control border-input" name="saldo" placeholder="" value="" style="width:250px;">
				</div>
				<div class="row">
					<label>Status :</label>
					<select name="status" >
						<?php
						$status = array('','','Debit','Kredit');
						$status_count = count($status);
						for($x=2; $x < $status_count; $x++){
							echo '<option value='.$x.'>'.$status[$x].'</option>';	
						 } ?>
					</select>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Kas</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>