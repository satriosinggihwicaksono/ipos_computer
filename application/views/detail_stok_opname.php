<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-10">
						<h4><?=$this->komputer->namaCabang($data[0]['id_cabang']); ?></h4>
						<h4><?=date('d-m-Y',strtotime($data[0]['waktu']))?><h4>
						<h3>Detail Stok Opname</h3>
					</div>
				</div>
			</div>

			 <div class="card-body">
				<div class="bsc-tbl"> 
				<form method="POST" action="<?php echo base_url().'laporan/add_stok_opname/'.$data[0]['id']; ?>">
				<table class="table table-sc-ex" id="dataTable" width="100%" cellspacing="0">
					<thead style='background-color:#FFEB3B'>
						<tr>
							<th>Nama</th>
							<th style="text-align:center">Laporan Stok</th>
							<th>Stok Terakhir</th>
							<th>Balance Stok</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach($item as $i){ 
							$this->db->where('id_item',$i['id']);
							$this->db->where('id_stok_opname',$data[0]['id']);
							$stok = $this->db->get('detail_stok_opname')->result_array();	
						?>
						
						<tr>
							<td><?php echo $i['nama'].' '.$i['warna'].' '.$i['tipe'] ?></td>
							<td><input type="text" name="stok_<?=$i['id'];?>" value="<?=$stok[0]['stok']?>"/></td>
							<td>
							<?php
									
								$this->db->where('id_cabang',$data[0]['id_cabang']);
								$this->db->where('waktu <',$data[0]['waktu']);
								$stok_opname = $this->db->get('stok_opname')->result_array();
								if(!empty($stok_opname)){
									$tanggal = $stok_opname[0]['waktu'];
									$waktu = strtotime($stok_opname[0]['waktu']);
									if(date('Y',$waktu) != -00001){
										$this->db->where('id_stok_opname',$stok_opname[0]['id']);
										$this->db->where('id_item',$i['id']);
										$real_stok = $this->db->get('detail_stok_opname')->result_array();								
										$real_stok = $real_stok[0]['stok'];
									}
								} else {
									$tanggal = '';
									$real_stok = 0;
								}

								$pembelian_unit = array();
								$this->db->select('*');
								$this->db->from('sub_pembelian');
								$this->db->join('pembelian','pembelian.id = sub_pembelian.id_pembelian');
								$this->db->where('pembelian.id_cabang',$data[0]['id_cabang']);
								$this->db->where('id_item',$i['id']);
								if(!empty($tanggal)){
									$this->db->where('pembelian.waktu >=',$tanggal);
								}
								$pembelian = $this->db->get()->result_array();
								foreach($pembelian as $p){
									$pembelian_unit[] = $p['unit'];
								}

								$pembelian_unit = array_sum($pembelian_unit);
								
								// transfer to
								$to_unit = array();
								$this->db->select('trans_item.unit');
								$this->db->from('trans_item');
								$this->db->join('trans_stok','trans_stok.id = trans_item.id_trans_stok');
								$this->db->where('id_to',$data[0]['id_cabang']);
								$this->db->where('id_item',$i['id']);
								$this->db->where('status',1);
								if(!empty($tanggal)){
									$this->db->where('trans_stok.tgl_trans >=',$tanggal);
								}
								$to = $this->db->get()->result_array();
								foreach($to as $t){
									$to_unit[] = $t['unit'];
								}

								$to_unit = array_sum($to_unit);

								// Transfer
								
								$from_unit = array();
								$this->db->select('trans_item.unit');
								$this->db->from('trans_item');
								$this->db->join('trans_stok','trans_stok.id = trans_item.id_trans_stok');
								$this->db->where('id_from',$data[0]['id_cabang']);
								$this->db->where('id_item',$i['id']);
								$this->db->where('status',1);
								if(!empty($tanggal)){
									$this->db->where('trans_stok.tgl_trans >=',$tanggal);
								}

								$from = $this->db->get()->result_array();
								foreach($from as $f){
									$from_unit[] = $f['unit'];
								}
								$from_unit = array_sum($from_unit);

								// Transaksi 
								
								$transaksi_unit = array();
								$this->db->select('sub_transaksi.unit');
								$this->db->from('sub_transaksi');
								$this->db->join('transaksi','transaksi.id = sub_transaksi.id_transaksi');
								$this->db->where('cabang',$data[0]['id_cabang']);
								$this->db->where('id_item',$i['id']);
								if(!empty($tanggal)){
									$this->db->where('transaksi.waktu >=',$tanggal);
								}
								$transaksi_1 = $this->db->get()->result_array();
								foreach($transaksi_1 as $t){
									$transaksi_unit[] = $t['unit'];
								}
								$transaksi_unit = array_sum($transaksi_unit);
								
								// Stok keluar 
								
								$stok_keluar_unit = array();
								$this->db->select('sub_stok_keluar.unit');
								$this->db->from('sub_stok_keluar');
								$this->db->join('stok_keluar','stok_keluar.id = sub_stok_keluar.id_stok_keluar');
								$this->db->where('stok_keluar.id_cabang',$data[0]['id_cabang']);
								$this->db->where('id_item',$i['id']);
								if(!empty($tanggal)){
									$this->db->where('stok_keluar.waktu >=',$tanggal);
								}

								$stok_keluar = $this->db->get()->result_array();
								foreach($stok_keluar as $sk){
									$stok_keluar_unit[] = $sk['unit'];
								}
								$stok_keluar_unit = array_sum($stok_keluar_unit);
								
								
								// Return
								if($data[0]['id_cabang'] == 1){
									$return_unit = array();
									$this->db->select('*');
									$this->db->from('return_item');
									$this->db->join('return_stok','return_stok.id = return_item.id_return_stok');
									$this->db->where('return_item.id_item',$i['id']);
									$this->db->where('return_stok.status',1);
									if(!empty($tanggal)){
										$this->db->where('return_stok.waktu >=',$tanggal);
									}
									$return = $this->db->get()->result_array();
									foreach($return as $r){
										$return_unit[] = $r['unit'];
									}
									$return_unit = array_sum($return_unit);										
								} else {
									$return_unit = 0;										
								}
								
								//service
								$service_unit = array();
								$this->db->select('*');
								$this->db->from('sub_service');
								$this->db->join('service','service.id = sub_service.id_service');
								$this->db->where('cabang',$data[0]['id_cabang']);
								$this->db->where('id_item',$i['id']);
								if(!empty($tanggal)){
									$this->db->where('service.waktu >=',$tanggal);
								}
								$service = $this->db->get()->result_array();
								foreach($service as $s){
									$service_unit[] = $s['unit'];
								}
								$service_unit = array_sum($service_unit);
								
								$total = $real_stok + ($pembelian_unit + $to_unit) - ($from_unit + $transaksi_unit) - ($return_unit + $stok_keluar_unit + $service_unit);
								echo $total;
							?>
							</td>
							<td>
							<?php echo $total - $stok[0]['stok']; ?>
							</td>
							<input type="hidden" name="<?=$i['id'];?>" value="<?=$i['id']?>"/>
						</tr>
						<?php } ?>
					</tbody>
				</table>
					<div style="text-align:right;">
						<h2><input type="submit" value="SIMPAN" /></h2>
					</div>
				</form>
				 </div>	
			</div>
		</div>
	</div>
</div>