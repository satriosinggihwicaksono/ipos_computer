<?php
	$tgl_con = 0;
	$id = $this->uri->segment(3);
?>
<div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<h3>Daftar Return Item</h3>	
									<label>Dikirim dari : <?php if(!empty($return[0]['waktu'])) echo date('d/m/Y',strtotime($return[0]['waktu'])); ?></label><br>
									<label>Supplier : <?php if(!empty($return[0]['id_supplier'])) echo $this->komputer->namaSupplier($return[0]['id_supplier']); ?></label><br>
									<label>Nota : <?php if(!empty($return[0]['nota'])) echo $return[0]['nota']; ?></label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
								
									<label>Diterima : <?php if(date('Y',strtotime($return[0]['waktu_a'])) != -0001) echo date('d/m/Y',strtotime($return[0]['waktu_a'])); ?></label><br>
								</div>
							</div>
						</div>
				<?php if($check_admin){ ?>	
				<form method="POST" action="<?php echo base_url().'stok/search_return_item/'; ?>">
					Input SN : <input type='text' name='search' value="<?php echo $search; ?>"/> <input type='hidden' name='id' value="<?php echo $return[0]['id']; ?>"/> <button type="submit" class="btn btn-default">Cari Item</button>
				</form>	
				</div>
				<?php if(!empty($search)){ ?>
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
								$this->db->where('status',0);
								$this->db->like('serial',$search);
								$searching = $this->db->get('serial')->result_array();
								if(!empty($searching)){
								foreach($searching as $s){
								$id_serial = $s['id'];
								$item = $this->komputer->cek($s['id_item'],'id','item');	
							?>
							<tr>
								<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe']; ?></td>
								<td style="text-align:center;"><?php echo $s['serial']; ?></td>
								<td style="text-align:center; width:100px;">
									<a href="<?php echo base_url().'stok/add_return_item_list/'.$return[0]['id'].'/'.$item[0]["id"].'/'.$id_serial.'/1'?>">	<span class="btn btn-success"> Tambah</span></a>
								</td>
							</tr>
							<?php 
							} 
								}  else {
									$this->db->where('serial',0);
									$this->db->like('nama',$search);
									$item = $this->db->get('item')->result_array();
									if(!empty($item)){
										foreach($item as $i){
										$harga = $this->komputer->cek($i['id'],'id_item','harga');
										if(!empty($harga)){
											$harga_jual = $harga[0]['harga_jual'];
										} else {
											$harga_jual = 0;
										}	
							?>
								<tr>
								<td><?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?></td>
								<td style="text-align:center;"></td>
								<td style="text-align:center; width:100px;">
									<?php if($i['serial'] == 0 ){ ?>
									<a href="<?php echo base_url().'stok/add_return_item_list/'.$return[0]['id'].'/'.$i["id"].'/0/1'?>">	<span class="btn btn-success"> Tambah</span></a>
								<?php } ?>
								</td>
							</tr>
							<?php	
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<?php	 }
					}
				}
				?>	
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th style="text-align:center; width:50px;">No</th>
								<th>Nama</th>
								<th>Serial</th>
								<th style="text-align:center;">Pertukaran</th>
								<th>Serial Tukar</th>
								<th style="text-align:center; width:50px;">Unit</th>
								<?php if($check_admin ){ ?>
								<th style="text-align:center; width:100px;">Actions</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php  
								$x = 1;
								$this->db->where('id_return_stok',$id);
								$data = $this->db->get('return_item')->result_array();
								foreach($data as $d){
								$item = $this->komputer->cek($d['id_item'],'id','item');
								$serial = $this->komputer->cek($d['id_serial'],'id','serial');
							?>
							<tr>
								<td style="text-align:center; width:50px;"><?php echo $x++ ?></td>
								<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe']; ?></td>
								<td><?php if(!empty($serial)) echo $serial[0]['serial']; ?></td>
								<td style="text-align:center;"><h3 class='fa fa-exchange'></h3></td>
								<?php if($item[0]['serial'] == 1){ ?>
									<form method="POST" action="<?php echo base_url().'stok/tambah_tukar_serial_return/'.$d['id']; ?>">
										<td><input name="serial" value="<?php if(!empty($d['serial'])) echo $d['serial']; ?>"/></td>
									</form>	
								<?php } else { ?>
								<td></td>
								<?php } ?>
								<td style="text-align:center; width:50px;">
									<?php
									if($item[0]['serial'] == 1){
										echo $d['unit']; 
									} else { ?>
									<form method="POST" action="<?php echo base_url().'stok/ubah_unit_return_stok/'.$d['id']; ?>">
										<input type="text" name="unit" value="<?php echo $d['unit']; ?>" style="width:50px;"/>
									</form>	
									<?php } ?>
								</td>	
								<?php if($check_admin ){ ?>
								<td style="text-align:center; width:100px;">	
									<a href='<?php echo base_url().'persedian_page/delete_barang/return_item/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
								<?php } ?>
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