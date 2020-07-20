<?php 
$url = $this->uri->segment(3);
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Laporan stok</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		table {
		  font-size:9px;
		  border-collapse: collapse;
		}
		 th {
		  background: #ccc;
		}

		th, td {
		  border: 1px solid #ccc;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background: #efefef;
		}

		tr:hover {
		  background: #d1d1d1;
		}
	</style>
</head>
<body onload="window.print()">	
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
	<h2>LAPORAN PENJUALAN</h2>
		
		<table class="table table-sc-ex">
			<thead style='background-color:#99ccff'>
				<tr>
					<th style="text-align:center; width:100px;">Tanggal</th>
					<th style="text-align:left;">Nama</th>
					<th style="text-align:center; width:25px;">Serial</th>
					<th style="text-align:center; width:25px;">Unit</th>
					<th style="text-align:center; width:25px;">Harga Pokok</th>
					<th style="text-align:center; width:100px;">Harga Jual</th>
					<th style="text-align:center; width:100px;">Margin</th>
					<th style="text-align:center; width:100px;">Itensif</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$total_penjualan = array();
				$total_pemelian = array();
				$total_itensif = array();
				foreach($data as $d){ 
				$total_penjualan[] = $d['total'];
			?>
				<tr>
					<td style="background-color:#90EE90;"><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
					<td colspan='7' style="background-color:#90EE90;"><?php echo $d['nota'].' ('.$this->komputer->namaUser($d['id_user']).')'; ?></td>
				</tr>
				<?php 
					$sub_transaksi = $this->komputer->cek($d['id'],'id_transaksi','sub_transaksi');
					foreach($sub_transaksi as $st){
					$harga = $st['harga'] * $st['unit'];
				?>
				<tr>
					<td></td>
					<td>
						<?php 
							$item = $this->komputer->cek($st['id_item'],'id','item');
							if(!empty($item)) echo $item[0]['nama'].' '.$item[0]['warna'].' '.$item[0]['tipe'];
						?> 
					</td>
					<td>
					<?php 
						$serial = $this->komputer->cek($st['id_serial'],'id','serial'); 
						if(!empty($serial)) echo $serial[0]['serial'];
					?>
					</td>
					<td><?php echo $st['unit']; ?></td>
					<td style="text-align:right;">
						<?php
							if(!empty($item)){
								if($item[0]['serial'] == 1){
									$this->db->where('id_item',$serial[0]['id_item']);
									$this->db->where('id_pembelian', $serial[0]['id_pembelian']);
								} else {
									$this->db->where('id_item',$item[0]['id']);
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
									$harga_pokok = ($avg_harga / $count_harga);	
								}

							$total_pembelian[] = $harga_pokok;
							$margin = $harga - ($harga_pokok * $st['unit']);

							echo '<a href ="'.base_url().'stok/detail_pembelian_stok/'.$harga_pokok_array[0]['id_pembelian'].'">'.$this->komputer->format($harga_pokok * $st['unit']).'</a>';
							}
						?>
					</td>
					<td style="text-align:right;"><?php echo '<a href ="'.base_url().'transaksi/detail_transaksi/'.$st['id_transaksi'].'">'.$this->komputer->format($harga).'</a>'; ?></td>
					<td style="text-align:right;"><?php if(!empty($item)) echo $this->komputer->format($margin); ?></td>
					<td style="text-align:right;">
						<?php
							if(!empty($item)){
								$itensif = $this->komputer->cek($item[0]['id'],'id_item','itensif');
								if(!empty($itensif)){
									if($serial[0]['kondisi'] = 1){
										$harga_itensif = $itensif[0]['ins_b'];
									} else {
										$harga_itensif = $itensif[0]['ins_s']; 
									}	
								} else {
									$harga_itensif = 0;
								}
								$total_itensif[] = $harga_itensif;
								echo $harga_itensif;
							}
						?>
					</td>
				</tr>
				<?php } ?>
			<?php } 
				if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
				if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
				if(!empty($tanggal)){
					$this->db->where('YEAR(waktu)', date('Y',$tanggal));
					$this->db->where('MONTH(waktu)', date('m',$tanggal));
					$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
				}
				if(!empty($cabang)) $this->db->where('cabang',$cabang);
				if(!empty($seles)) $this->db->where('teknisi',$seles);
				 $this->db->where('status',1);
				$service = $this->db->get('service')->result_array();
				foreach($service as $s){
			?>
				<tr style="background-color:#9FE2FE">
					<td><?php echo date('d-m-Y',strtotime($s['waktu'])); ?></td>
					<td colspan='7'><?php echo $s['nota'].' ('.$this->komputer->namaUser($s['teknisi']).')'; ?></td>
				</tr>
					<?php 
						$this->db->where('id_service',$s['id']);
						$sub_service = $this->db->get('sub_service')->result_array();
						foreach($sub_service as $ss){	
						$harga = $ss['harga'] * $ss['unit'];
						$total_penjualan[] = $harga;	
						if($ss['id_item'] == 0){
							$nama_service = $ss['service'];
						} else {
							$item_s = $this->komputer->cek($ss['id_item'],'id','item');
							$nama_service = $item_s[0]['nama'].' '.$item_s[0]['tipe'].' '.$item_s[0]['warna'];
						}
					?>
				<tr>
					<td></td>
					<td><?php echo $nama_service; ?></td>
					<td><?php if(!empty($ss['id_serial'])) echo $this->komputer->namaSerial($ss['id_serial']); ?></td>
					<td><?php echo $ss['unit']; ?></td>
					<td>								
						<?php
						if(!empty($ss['id_item'])){
							if(!empty($item_s['serial']) == 1){
								$this->db->where('id_item',$ss['id_item']);
								$this->db->where('id_pembelian', $ss['id_pembelian']);
							} else {
								$this->db->where('id_item',$ss['id_item']);
							}	
							$harga_pokok_array = $this->db->get('sub_pembelian')->result_array(); 
							if(!empty($harga_pokok_array)){
								$harga_pokok = $harga_pokok_array[0]['harga'];
							} else {
								$harga_pokok = 0;
							}	

							$avg_harga = array();
							if($item_s[0]['serial'] == 0 && !empty($harga_pokok_array)){
							foreach($harga_pokok_array as $h){
								$avg_harga[] = $h['harga'];						
							}
								$count_harga = count($avg_harga);
								$avg_harga = array_sum($avg_harga);
								$harga_pokok = ($avg_harga / $count_harga);	
							}

							if(!empty($harga_pokok)){
								$margin = $harga - ($harga_pokok * $ss['unit']);
							} 
						if(!empty($harga_pokok)){	
							$total_pembelian[] = $harga_pokok;
						}
						if(!empty($harga_pokok_array)){
							$url = base_url().'stok/detail_pembelian_stok/'.$harga_pokok_array[0]['id_pembelian'];
						} else {
							$url = '#';
						}
						echo '<a href ="'.$url.'">'.$this->komputer->format($harga_pokok * $ss['unit']).'</a>';
						} else {
								$margin = $harga;
						}

						?>
					</td>
					<td style="text-align:right;"><?php echo '<a href ="'.base_url().'service/detail_service/'.$s['id'].'">'.$this->komputer->format($harga).'</a>'; ?></td>
					<td style="text-align:right;"><?php echo $this->komputer->format($margin); ?></td>
					<td></td>
				</tr>
				<?php 
						}
					}
					if(empty($total_pembelian)){
						$total_pembelian = 0;
					} else {
						$total_pembelian = array_sum($total_pembelian);
					}

					if(empty($total_penjualan)){
						$total_penjualan = 0;
					} else {
						$total_penjualan = array_sum($total_penjualan);
					}

					$margin = $total_penjualan - $total_pembelian;
				?>
				<tr>
					<td colspan='4' style="text-align:left; background-color:#F5DEB3;"><h3>TOTAL</h3></td>
					<td style="text-align:right; background-color:#F5DEB3;"><?php if(!empty($total_pembelian)) echo $this->komputer->format($total_pembelian); ?></td>
					<td style="text-align:right; background-color:#F5DEB3;"><?php if(!empty($total_penjualan)) echo $this->komputer->format($total_penjualan); ?></td>
					<td style="text-align:right; background-color:#F5DEB3;"><?php echo $this->komputer->format($margin); ?></td>
					<td style="text-align:right; background-color:#F5DEB3;"><?php if(!empty($total_itensif)) echo $this->komputer->format(array_sum($total_itensif)); ?></td>
				<tr>	
			</tbody>
	</table>
	</body>
</html>