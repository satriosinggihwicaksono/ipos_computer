<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$cabang = $t[0];
	$month = $t[1];
	$year = $t[2];
	$tanggal = $t[3];
	$seles = $t[4];
} else {
	$cabang = 0;
	$month = date('m');
	$year = date('Y');
	$tanggal = '';
	$seles = '';
}
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Laporan Penjualan</label>
						</div>
					</div>
				</div>
			</div>
			<div class="breadcomb-area">
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#FFFFFFF'>
						<form method="POST" action="<?php echo base_url().'transaksi/search_daftar_penjualan/'; ?>">
						<tr>
							<th>Cabang :
								<?php if(!empty($check_admin)){ ?>
								<select name="cabang" >
									<option value='0'>SEMUA</option>
									<?php
									$this->db->where('id !=', 0);
									$cabang_result = $this->db->get('cabang')->result_array();
									foreach($cabang_result as $c){ ?>
										<option <?php if($cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
									<?php } ?>
								</select>
								<?php } else {
									echo '<input value="'.$nama_cabang.'" disabled/>';
									echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
								}
								?>
							</th>
							<th>Tanggal : <input name="tanggal" type="date" value="<?php if(!empty($tanggal)) echo date('Y-m-d',$tanggal); ?>"/> </th>
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
							<th>Seles :
								<select name="seles" >
									<option></option>
									<?php
									$this->db->where('hakakses',2);
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
					<thead style='background-color:#99ccff'>
						<tr>
							<th style="text-align:center; width:100px;">Tanggal</th>
							<th style="text-align:center; width:100px;">Cabang</th>
							<th style="text-align:center; width:100px;">Nota</th>
							<th style="text-align:center; width:100px;">Seles</th>
							<th style="text-align:left;">Nama</th>
							<th style="text-align:center; width:25px;">Serial</th>
							<th style="text-align:center; width:100px;">Harga Jual</th>
						</tr>
					</thead>
						<?php foreach($data as $d){ ?>
						<tr>
							<td><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
							<td><?php echo $this->komputer->namaCabang($d['cabang']); ?></td>
							<td><?php echo $d['nota']; ?></td>
							<td><?php echo $this->komputer->namaUser($d['id_user']); ?></td>
							<td>
								<?php 
									$item = $this->komputer->cek($d['id_item'],'id','item');
									if(!empty($item)){
										echo $item[0]['nama'].' '.$item[0]['tipe'].' '.$item[0]['warna'];
									}	
								?>
							</td>
							<td>
								<?php 
									$serial = $this->komputer->cek($d['id_serial'],'id','serial');
									echo $serial[0]['serial'];
								?>
							</td>
							<td><?php echo $this->komputer->format($d['harga']); ?></td>
						</tr>
						<?php } ?>
					<tbody>	
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
			$href = base_url().'penjualan/'.$this->uri->segment(2).'/'.$link.'/'.$page;
		} else {
			$page = $this->uri->segment(3) - 10;
			$href = base_url().'penjualan/'.$this->uri->segment(2).'/'.$page;
		}
		echo $this->pagination->create_links();
		?>
</div>