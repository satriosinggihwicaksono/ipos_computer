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
	$seles = $t[3];
	$nama = $t[4];
	$nama = str_replace("%20"," ",$nama);
	$id_kategori = $t[5];
} else {
	$cabang = 0;
	$month = date('m');
	$year = date('Y');
	$seles = '';
	$kategori = '';
	$nama = '';
}
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<table class="table table-sc-ex">
				<thead style='background-color:#FFFFFFF'>
					<form method="POST" action="<?php echo base_url().'laporan/search_history_penjualan/'; ?>">
					<tr>
						<th>
							Nama : <input type="text" name="nama" value="<?php echo $nama; ?>"/>
						</th>
						<th>Kategori :
							<select name="kategori" >
								<option></option>
								<?php
								$kategori = $this->db->get('kategori')->result_array();
								foreach($kategori as $k){ ?>
									<option <?php if($id_kategori == $k['id']){ echo 'selected="selected"'; } ?> value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
								<?php } ?>
							</select>
						</th>
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
			<div class="basic-tb-hd">
				 <div class="card-body">
					 <div class="bsc-tbl">
					<table class="table table-sc-ex" id="dataTable" width="100%" cellspacing="0">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>Penjualan</th>
								<th>Serial</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$x = 1;
								foreach($data as $d){
							?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $d['nama'].' '.$d['tipe'].' '.$d['warna'].' '.$d['merek']; ?></td>
								<td>
								<?php 
									$total_unit = array();
									$id_item = $d['id'];
									$this->db->select('sub_transaksi.unit,transaksi.waktu,sub_transaksi.id_item,sub_transaksi.id_serial');
									$this->db->from('sub_transaksi');
									$this->db->join('transaksi','transaksi.id = sub_transaksi.id_transaksi');
									$this->db->where('MONTH(transaksi.waktu)', $month);
									$this->db->where('YEAR(transaksi.waktu)',$year);
									$this->db->where('sub_transaksi.id_item',$id_item);	
									if(!empty($seles)) $this->db->where('transaksi.id_user',$seles);	
									if(!empty($cabang)) $this->db->where('transaksi.cabang',$cabang);	
									$sub_transaksi = $this->db->get()->result_array();
									
									if($d['serial'] == 1){
										echo '<a href="'.base_url().'laporan/history_penjualan_item/'.$d['id'].'^'.$month.'^'.$year.'^'.$cabang.'^'.$seles.'">'.count($sub_transaksi).'</a>';
									} else {
										foreach($sub_transaksi as $st){
											$unit = $st['unit'];
											$total_unit[] = $unit;
										}
										echo '<a href="'.base_url().'laporan/history_penjualan_item/'.$d['id'].'^'.$month.'^'.$year.'^'.$cabang.'^'.$seles.'">'.array_sum($total_unit).'</a>';
									}
								?>
								</td>
								<td>
								<?php
									if($d['serial'] == 1){
										foreach($sub_transaksi as $sb){
											$id_serial = $sb['id_serial'];
											echo $this->komputer->namaSerial($id_serial).'<br>';
										}
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