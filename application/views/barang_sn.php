<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-10">
						<h3>DAFTAR ITEM SERIAL</h3>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						 <div class="bsc-tbl">
						<table class="table table-sc-ex" style="width:100%">
						<form method="POST" action="<?php echo base_url().'persedian_page/search_barang_sn/'; ?>">
							<tr>
								<th>Nama <input type="text" name="nama" placeholder="" value="<?php echo $nama; ?>" style="width:200px"></th>
								<th>Merek <input type="text" name="merek" placeholder="" value="<?php echo $merek; ?>" style="width:200px"></th>
								<th>Kategori 
								<select name="kategori" class="selectpicker">
									<option></option>
									<?php
									$kategori = $this->db->get('kategori')->result_array();
									foreach($kategori as $k){ ?>
										<option <?php if($id_kategori == $k['id']){ echo 'selected="selected"'; } ?> value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
									<?php } ?>
								</select>
								</th>
								<th>Kondisi
								<select name="kondisi" >
								<option value='3'>SEMUA</option>	
								<?php
								$kondisi_status = array('Baru','Bekas');
								$kondisi_count = count($kondisi_status);
								for($x=0; $x < $kondisi_count; $x++){
									if($kondisi == $x){
										$selected = 'selected="selected"';
									} else {
										$selected = '';
									}
									echo "<option ".$selected." value='".$x."'>".$kondisi_status[$x]."</option>";
								 } 
								?>
								</select>
								</th>
								<th>Cabang
									<?php if(!empty($check_admin)){ ?>
									<select name="cabang" >
										<option value="0">SEMUA</option>
										<?php
										$cabang_form = $this->db->get('cabang')->result_array();
										foreach($cabang_form as $c){ ?>
											<option <?php if($cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
										<?php } ?>
									</select>
									<?php } else {
										echo '<input value="'.$nama_cabang.'" disabled/>';
										echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
									}
									?>
								</th>
								<th>Supplier :
									<select name="supplier" >
										<option value='0'>SEMUA</option>
										<?php
										$supplier = $this->db->get('supplier')->result_array();
										foreach($supplier as $s){ ?>
											<option <?php if($id_supplier == $s['id']){ echo 'selected="selected"'; } ?> value='<?php echo $s['id']; ?>'><?php echo $s['supplier']; ?></option>
										<?php } ?>
									</select>
								</th>
								<th><button type="submit" class="btn btn-warning">Cari</button></th>
							</tr>
						</form>
						</table>
						</div>	 
					</div>
				</div>
			</div>

			 <div class="card-body">
				 <div class="bsc-tbl">
					<table class="table table-sc-ex" id="dataTable" width="100%" cellspacing="0">
					<thead style='background-color:#FFEB3B'>
						<tr>
							<th>Nama</th>
							<th style="text-align:center">Serial</th>
							<th style="text-align:center">Cabang</th>
							<th style="text-align:center">Kondisi</th>
						</tr>
					</thead>
					<tbody>
						<?php 	
							foreach($data as $is){
						?>
						<tr>
							<td><?php echo $is['nama'].' '.$is['tipe'].' '.$is['warna']; ?></td>
							<td><?php echo $is['serial']; ?></td>
							<td><?php echo $this->komputer->namaCabang($is['cabang']); ?></td>
							<td>
								<?php
								if($is['kondisi'] == 1){
									echo 'BEKAS'; 
								} else {
									echo 'BARU';
								}	
								?>
							</td>
						</tr>
						<?php 
							} 
						?>
					</tbody>
				</table>
			</div>
			</div>	 
			<div class="text-center">
				<?php
					if($posisi > 0){
						$page = $this->uri->segment(4) - 10;
						$href = base_url().'persedian_page/'.$this->uri->segment(2).'/'.$link.'/'.$page;
					} else {
						$page = $this->uri->segment(3) - 10;
						$href = base_url().'persedian_page/'.$this->uri->segment(2).'/'.$page;
					}
					echo $this->pagination->create_links();
				?>
			</div>
			<div class="row">
				<div class="col-md-10">
				</div>
				<div class="col-md-2">
					<h2><a href="<?php echo base_url().'printer/printSN/'.$url;?>" onclick="window.open('<?php echo base_url().'printer/printSN/'.$url; ?>', 'newwindow', 'width=600, height=700'); return false;"><button onclick="demo.showNotification('top','left')" class="fa fa-print"> CETAK</button></a></h2>
				</div>
			</div>	
		</div>
	</div>
</div>	