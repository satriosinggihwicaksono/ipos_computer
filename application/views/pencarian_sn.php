c<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$serial = $t[0];
	$id_serial = $t[1];
} else {
	$serial = '';
	$id_serial = '';
}
?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
					<div class="row">
						<div class="col-md-4">
								<label>Pencarian Serial</label>
						</div>
					</div>	
					<div class="row">
						<div class="col-md-4">
							<form method="POST" action="<?php echo base_url().'persedian_page/search_pencarian_sn/' ?>">
								<input type="text" name="serial" value="<?php echo $serial; ?>"/>
								<input type="submit" value="Cari"/>
							</form>	
						</div>
					</div>
				</div>
			</div>
		</div>	
		<?php if(!empty($serial)){ ?>		
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">	
				<div class="bsc-tbl">
					<table class="table table-sc-ex" style="width:50%">
						<thead style='background-color:#2196F3'>
							<tr>
								<th>Nama</th>
								<th style="text-align:center;">Serial</th>
								<th style="text-align:center; width:100px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								$this->db->like('serial',$serial);
								$searching = $this->db->get('serial')->result_array();
								foreach($searching as $s){
								$item = $this->komputer->cek($s['id_item'],'id','item');			
								if(!empty($item)){
								$harga = $this->komputer->cek($item[0]['id'],'id_item','harga');
								if(!empty($harga)){
									$harga_jual = $harga[0]['harga_jual'];
								} else {
									$harga_jual = 0;
								}	
							?>
							<tr>
								<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe']; ?></td>
								<td style="text-align:center;"><?php if(!empty($searching)) echo $s['serial']; ?></td>
								<td style="text-align:center; width:100px;">
									<a href="<?php echo base_url().'persedian_page/pencarian_sn/^'.$s['id'].'^';?>">
									<span class="btn btn-success"> Cari</span></a>
								</td>
							</tr>
							<?php }
								} 
							?>
						</tbody>
					</table>
				</div>
			</div>
		<?php	 
			} 
		?>	

		<?php if(!empty($id_serial)){ ?>		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">	
				<div class="bsc-tbl">
					<?php 
						$this->db->select('item.nama,item.tipe,item.warna,serial.serial,serial.id,pembelian.waktu,pembelian.nota,pembelian.id_supplier,pembelian.id');
						$this->db->from('serial');
						$this->db->join('item','serial.id_item = item.id');
						$this->db->join('pembelian','serial.id_pembelian = pembelian.id');
						$this->db->where('serial.id',$id_serial);
						$item_serial = $this->db->get()->result_array();	
					?>
					<table class="table table-sc-ex">
						<thead style='background-color:#2196F3'>
							<tr>
								<th>History</th>
								<th>SN</th>
								<th></th>
							</tr>
						</thead>	
						<tbody>
							<tr>
								<td><?php echo $item_serial[0]['nama'].' '.$item_serial[0]['tipe'].' '.$item_serial[0]['warna']; ?></td>
								<td><?php echo $item_serial[0]['serial']; ?></td>
							</tr>
							<?php if(!empty($item_serial)){ ?>
							<tr style='background-color:#ffff99'>
								<td>Tanggal Pembelian</td>
								<td>Nota</td>
								<td>Supplier</td>
							</tr>
							<tr>
								<td><?php echo $item_serial[0]['waktu'];?></td>
								<td><?php echo '<a href="'.base_url().'stok/detail_pembelian_stok/'.$item_serial[0]['id'].'">'.$item_serial[0]['nota'].'</a>';?></td>
								<td><?php echo $this->komputer->namaSupplier($item_serial[0]['id_supplier']);?></td>
							</tr>
							<?php } ?>
							<?php 
								$this->db->select('trans_stok.tgl_trans,trans_stok.id_from,trans_stok.id_to,trans_stok.id_user,trans_stok.status,trans_stok.id,trans_item.id_item');
								$this->db->from('trans_stok');
								$this->db->join('trans_item','trans_stok.id = trans_item.id_trans_stok');
								$this->db->where('trans_item.serial',$id_serial);
								$trans_stok = $this->db->get()->result_array();
								if(!empty($trans_stok)){
							
							?>
							<tr style='background-color:#FFDAB9'>
								<td>Tanggal Kirim</td>
								<td>Pengiriman</td>
								<td>Penerima</td>
							</tr>
							<?php
								foreach($trans_stok as $ts){
								if($ts['status'] == 0){
									$color = 'danger';
								} else {
									$color = 'success';
								}	
							?>
							
							<tr>
								<td><?php echo $ts['tgl_trans']; ?></td>
								<td><?php echo $this->komputer->namaCabang($ts['id_from']).' <a href="'.base_url().'stok/tambah_stok/'.$ts["id_item"].'^'.$ts['id_to'].'^^"><button class="btn btn-'.$color.' '.$color.'-icon-notika"><i class="notika-icon notika-right-arrow"></i></button></a> '.$this->komputer->namacabang($ts['id_to']);?></td>
								<td><?php if(!empty($ts['id_user'])) echo $this->komputer->namaUser($ts['id_user']); ?></td>
							</tr>
							
							<?php
									}
								}
							?>
							
							<?php 
								$this->db->select('transaksi.waktu,transaksi.nota,transaksi.id_user,transaksi.id');
								$this->db->from('transaksi');
								$this->db->join('sub_transaksi','sub_transaksi.id_transaksi = transaksi.id');
								$this->db->where('sub_transaksi.id_serial',$id_serial);
								$transaksi = $this->db->get()->result_array();
								if(!empty($transaksi)){
							?>
							<tr style='background-color:#A9A9A9'>
								<td>Tanggal Transaksi</td>
								<td>Nota</td>
								<td>Seles</td>
							</tr>
							<tr>
								<td><?php echo $transaksi[0]['waktu']; ?></td>
								<?php if($check_admin){ ?>
								<td><?php echo '<a href="'.base_url().'transaksi/detail_transaksi/'.$transaksi[0]['id'].'">'.$transaksi[0]['nota'].'</a>'; ?></td>
								<?php } else { ?>
								<td><?php echo $transaksi[0]['nota']; ?></td>
								<?php } ?>
								<td><?php echo $this->komputer->namaUser($transaksi[0]['id_user']); ?></td>
							</tr>
							<?php 
								}
							?>
							
							<?php
								// stok keluar
								$this->db->select('*');
								$this->db->from('stok_keluar');
								$this->db->join('sub_stok_keluar','sub_stok_keluar.id_stok_keluar = stok_keluar.id');
								$this->db->where('sub_stok_keluar.id_serial',$id_serial);
								$stok_keluar = $this->db->get()->result_array();
								if(!empty($stok_keluar)){
							?>
							<tr style='background-color:#FF6347'>
								<td>Tanggal Stok Keluar</td>
								<td>Nota</td>
								<td>Keterangan</td>
							</tr>
							<tr>
								<td><?php echo $stok_keluar[0]['waktu']; ?></td>
								<td><?php echo '<a href="'.base_url().'stok/detail_stok_keluar/'.$stok_keluar[0]['id'].'">'.$stok_keluar[0]['nota'].'</a>'; ?></td>
								<td><?php echo $stok_keluar[0]['keterangan']; ?></td>
							</tr>
							
							<?php
									}
							?>
							
							<?php
								// service
								$this->db->select('*');
								$this->db->from('service');
								$this->db->join('sub_service','sub_service.id_service = service.id');
								$this->db->where('sub_service.id_serial',$id_serial);
								$this->db->where('service.status',1);
								$service = $this->db->get()->result_array();
								if(!empty($service)){
								$deskripsi = $service[0]['deskripsi'];	
							?>
							<tr style='background-color:#FF6347'>
								<td>Tanggal Service</td>
								<td>Nota</td>
								<td>Keterangan</td>
							</tr>
							<tr>
								<td><?php echo $service[0]['waktu']; ?></td>
								<td><?php echo '<a href="'.base_url().'service/detail_service/'.$service[0]['id_service'].'">'.$service[0]['nota'].'</a>'; ?></td>
								<td><?php echo $deskripsi[2]; ?></td>
							</tr>
							
							<?php
									}
							?>
							
							<?php
								// Return
								$this->db->select('*');
								$this->db->from('return_stok');
								$this->db->join('return_item','return_item.id_return_stok = return_stok.id');
								$this->db->where('return_item.id_serial',$id_serial);
								$this->db->where('return_stok.status',1);
								$return = $this->db->get()->result_array();
								if(!empty($return)){
							?>
							<tr style='background-color:#FF6347'>
								<td>Tanggal Return</td>
								<td>Nota</td>
								<td>Keterangan</td>
							</tr>
							<tr>
								<td><?php echo $return[0]['waktu']; ?></td>
								<td><?php echo '<a href="'.base_url().'stok/daftar_return_stok/'.$return[0]['id'].'^">'.$return[0]['nota'].'</a>'; ?></td>
								<td><?php echo $return[0]['keterangan']; ?></td>
							</tr>
							<?php
									}
							?>
						</tbody>
					</table>	
				</div>
		</div>
		<?php	 
			} 
		?>	
	</div>	
</div>	