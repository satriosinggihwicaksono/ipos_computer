<div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<h3>Daftar Transfer Item</h3>
									<label>Dikirim dari : <?php if(!empty($id_from)) echo $this->komputer->namaCabang($id_from); ?></label><br>
									<label>Dikirim ke : <?php if(!empty($id_to)) echo $this->komputer->namaCabang($id_to); ?></label><br>
									<label>Deskripsi : <?php if(!empty($deskripsi)) echo $deskripsi; ?></label><br>
									<label>Waktu Kirim : <?php  if(date('Y',strtotime($tgl_trans)) == 0001) echo date('d-m-Y H:i:s',strtotime($tgl_trans)); ?></label><br>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Diterima : <?php if(!empty($id_user)) echo $this->komputer->namaUser($id_user); ?></label><br>
									<label>Waktu Terima : <?php if(date('Y',strtotime($tgl_con)) == 0001) echo date('d-m-Y H:i:s',strtotime($tgl_con)); ?></label><br>
									<?php if(empty($data)){ ?><a href='<?php echo base_url().'stok/remove_trans_item/'.$id_trans; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a> <?php } ?>
								</div>
							</div>
						</div>
				<?php if(($id_from == $id_cabang && $status == 0) || $check_admin){ ?>	
				<form method="POST" action="<?php echo base_url().'stok/search_daftar_item/'; ?>">
					Input SN : <input type='text' name='serial' value="<?php echo $search; ?>"/> <input type='hidden' name='id_daftar' value="<?php echo $id_daftar; ?>"/> <button type="submit" class="btn btn-default">Cari Item</button>
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
								$this->db->where('id_trans_stok',$id_daftar);
								$filter = $this->db->get('trans_item')->result_array();
								foreach($filter as $f){
								$this->db->where('id!=',$f['serial']);
								}
								$this->db->where('status',0);
								$this->db->where('cabang',$id_from);
								$this->db->like('serial',$search);
								$searching = $this->db->get('serial')->result_array();
								if(!empty($searching)){
								foreach($searching as $s){
								$item = $this->komputer->cek($s['id_item'],'id','item');
								if(!empty($item)){
							?>
							<tr>
								<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe']; ?></td>
								<td style="text-align:center;"><?php echo $s['serial']; ?></td>
								<td style="text-align:center; width:100px;">
									<?php if($status != 1){?>
									<a href='<?php echo base_url().'stok/add_daftar_item/'.$id_daftar.'/'.$item[0]['id'].'/'.$s['id']; ?>'><span class="btn btn-success"> Tambah</span></a>
									<?php } else { ?>
									<a href='<?php echo base_url().'stok/add_daftar_item_status/'.$id_daftar.'/'.$item[0]['id'].'/'.$s['id'].'/'.$id_to; ?>'><span class="btn btn-success"> Tambah</span></a>									
									<?php }?>
								</td>
							</tr>
							<?php 
								}		
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
								<td>
									<?php 
										echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; 
									?>
								</td>
								<td style="text-align:center;"></td>
								<td style="text-align:center; width:100px;">
									<?php if($status != 1){?>
									<a href="<?php echo base_url().'stok/add_daftar_item/'.$id_daftar.'/'.$i["id"].'/0/0'?>"> <span class="btn btn-success"> Tambah</span></a>
									<?php } else { ?>
									<a href="<?php echo base_url().'stok/add_daftar_item_status/'.$id_daftar.'/'.$i["id"].'/0/0/'.$id_to?>">	<span class="btn btn-success"> Tambah</span></a>
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
								<th style="text-align:center; width:50px;">Unit</th>
								<?php if(($id_from == $id_cabang && $status == 0) || $check_admin ){ ?>
								<th style="text-align:center; width:100px;">Actions</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php  
								$x = 1;
								foreach($data as $d){
								$item = $this->komputer->cek($d['id_item'],'id','item');
								$serial = $this->komputer->cek($d['serial'],'id','serial');
							?>
							<tr>
								<td style="text-align:center; width:50px;"><?php echo $x++ ?></td>
								<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe']; ?></td>
								<td><?php if(!empty($serial)) echo $serial[0]['serial']; ?></td>
								<td style="text-align:center; width:50px;">
									<?php
									if($item[0]['serial'] == 1){
										echo $d['unit']; 
									} else { ?>
									<form method="POST" action="<?php echo base_url().'stok/ubah_unit/'.$d['id']; ?>">
										<input type="text" name="unit" value="<?php echo $d['unit']; ?>" style="width:50px;"/>
									</form>	
									<?php } ?>
								</td>	
								<?php if(($id_from == $id_cabang && $status == 0) || $check_admin ){ ?>
								<td style="text-align:center; width:100px;">	
									<a href='<?php echo base_url().'persedian_page/delete_barang/trans_item/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
								<?php } ?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
		<div class="col-lg-9">
		</div>	
		<div class="col-lg-3">
			<div class="recent-items-wp notika-shadow sm-res-mg-t-30">
				<div class="rc-it-ltd">
					<div class="recent-items-ctn">
						<div class="text-align:center;">
							<h2><a href="<?php echo base_url().'printer/printTransfer/'.$id_daftar;?>" onclick="window.open('<?php echo base_url().'printer/printTransfer/'.$id_daftar;?>', 'newwindow', 'width=600, height=700'); return false;"><button onclick="demo.showNotification('top','left')" class="fa fa-print"> CETAK</button></a></h2>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>	
	</div>
</div>