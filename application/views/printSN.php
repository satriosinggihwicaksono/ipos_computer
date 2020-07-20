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
	<h2>Stok Konter <?php echo $this->komputer->namaCabang($url); ?></h2>
		<table class="my_table">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Barang</th>
					<th>Total Stok</th>
					<th>SN</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$kategori = $this->db->get('kategori')->result_array();
					foreach($kategori as $k){
					$x = 1;
					$this->db->where('id_kategori',$k['id']);
					$this->db->order_by('merek', 'desc');
					$data = $this->db->get('item')->result_array();
					$item = array();
					foreach($data as $d){
						if($d['serial'] == 1){
							if(!empty($url)) $this->db->where('cabang',$url);
							$this->db->where('status',0);
							$this->db->where('id_item',$d['id']);
							$total = $this->db->get('serial')->num_rows();
						} else {

							$pembelian_unit = array();
							$this->db->where('id_item',$d['id']);
							$pembelian = $this->db->get('sub_pembelian')->result_array();
							foreach($pembelian as $p){
								$pembelian_unit[] = $p['unit'];
							}

							$pembelian_unit = array_sum($pembelian_unit);

							$to_unit = array();
							$this->db->select('trans_item.unit');
							$this->db->from('trans_item');
							$this->db->join('trans_stok','trans_stok.id = trans_item.id_trans_stok');
							//$this->db->where('id_to',$url);
							$this->db->where('id_item',$d['id']);
							$to = $this->db->get()->result_array();
							foreach($to as $t){
								$to_unit[] = $t['unit'];
							}

							$to_unit = array_sum($to_unit);

							$from_unit = array();
							$this->db->select('trans_item.unit');
							$this->db->from('trans_item');
							$this->db->join('trans_stok','trans_stok.id = trans_item.id_trans_stok');
							$this->db->where('id_from',$url);
							$this->db->where('id_item',$d['id']);
							$from = $this->db->get()->result_array();
							foreach($from as $f){
								$from_unit[] = $f['unit'];
							}
							$from_unit = array_sum($from_unit);


							$transaksi_unit = array();
							$this->db->select('sub_transaksi.unit');
							$this->db->from('sub_transaksi');
							$this->db->join('transaksi','transaksi.id = sub_transaksi.id_transaksi');
							if(!empty($url)) $this->db->where('cabang',$url);
							$this->db->where('id_item',$d['id']);
							$transaksi = $this->db->get()->result_array();
							foreach($transaksi as $t){
								$transaksi_unit[] = $t['unit'];
							}
							$transaksi_unit = array_sum($transaksi_unit);
							if($url != 1){
								$pembelian_unit = 0;
							}
							$total = $pembelian_unit + ($to_unit - ($from_unit + $transaksi_unit));
						}
						if($total != 0){
							$d['total'] = $total;
							$item[] = $d;
						}
					}

					$data = $item;
				?>
				<tr style='background-color:#99ccff'>
					<td colspan='4'> <?php echo $k['kategori']; ?></td>
				</tr>
				<?php	
					foreach($data as $d){
						$nama = $d['nama'];
						$tipe = $d['tipe'];
						$warna = $d['warna'];
						
				?>
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo $nama.' '.$tipe.' '.$warna; ?></td>
					<td><?php echo $d['total']; ?></td>
					<td>
					<?php 
							$this->db->select('serial.serial,serial.kondisi');
							if(!empty($url)) $this->db->where('cabang',$url);
							$this->db->where('status',0);
							$this->db->where('id_item',$d['id']);
							$serial = $this->db->get('serial')->result_array();	
							if($d['id_kategori'] != 12){
								foreach($serial as $s){
									if($s['kondisi'] == 1){
										$kondisi = '(2ND)';
									} else {
										$kondisi = '';
									}
									echo $s['serial'].' '.$kondisi.', ';
								}
							}	
					?>
					</td>
				</tr>
				<?php 
					} 
				}
				?>
			</tbody>
		</table>
	</body>
</html>