<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<h3>Daftar Stok Keluar</h3>	
								<label>Tanggal : <?php if(!empty($stok_keluar[0]['waktu'])) echo date('d/m/Y',strtotime($stok_keluar[0]['waktu'])); ?></label><br>
								<label>Nota : <?php if(!empty($stok_keluar[0]['nota'])) echo $stok_keluar[0]['nota']; ?></label>
							</div>
						</div>
					</div>
			<?php if($check_admin){ ?>	
			<form method="POST" action="<?php echo base_url().'stok/search_detail_stok_keluar/'; ?>">
				Input SN : <input type='text' name='search' value="<?php echo $search; ?>"/> <input type='hidden' name='id' value="<?php echo $stok_keluar[0]['id']; ?>"/> <button type="submit" class="btn btn-default">Cari Item</button>
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
								<a href="<?php echo base_url().'stok/add_stok_keluar_list/'.$stok_keluar[0]['id'].'/'.$item[0]["id"].'/'.$id_serial.'/1'?>">	<span class="btn btn-success"> Tambah</span></a>
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
								<a href="<?php echo base_url().'stok/add_stok_keluar_list/'.$stok_keluar[0]['id'].'/'.$i["id"].'/0/1'?>">	<span class="btn btn-success"> Tambah</span></a>
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
							<?php if($check_admin ){ ?>
							<th style="text-align:center; width:100px;">Actions</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php  
							$x = 1;
							$this->db->where('id_stok_keluar',$stok_keluar[0]['id']);
							$data = $this->db->get('sub_stok_keluar')->result_array();
							foreach($data as $d){
							$item = $this->komputer->cek($d['id_item'],'id','item');
							$serial = $this->komputer->cek($d['id_serial'],'id','serial');
						?>
						<tr>
							<td style="text-align:center; width:50px;"><?php echo $x++ ?></td>
							<td><?php echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe']; ?></td>
							<td><?php if(!empty($serial)) echo $serial[0]['serial']; ?></td>
							<?php if(empty($serial)){ ?>
								<form method="POST" action="<?php echo base_url().'stok/edit_sub_stok_keluar/'.$d['id']; ?>">
									<td><input name="unit" type="text" value="<?php if(!empty($d['unit'])) echo $d['unit']; ?>" /></td>
								</form>	
							<?php } else { ?>
								<td> <?php if(!empty($d['unit'])) echo $d['unit']; ?></td>
							<?php } if($check_admin ){ ?>
							<td style="text-align:center; width:100px;">
								<?php if($item[0]['serial'] == 0){ ?>
									<a href='<?php echo base_url().'persedian_page/delete_barang/sub_stok_keluar/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								<?php } else { ?>
									<a href='<?php echo base_url().'stok/delete_sub_stok_keluar/'.$d['id'].'/'.$serial[0]['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								<?php } ?>
							</td>
							<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<br>
				<div class="row">
					<div class="col-md-9">
					</div>
					<div class="col-md-3" style="text-align:right">
						<a href="<?php echo base_url().'stok/simpan_stok_keluar/'.$stok_keluar[0]['id']; ?>"><h1><button>STOK KELUAR</button></h1></a>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>	
</div>