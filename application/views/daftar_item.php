<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-10">
						<h3>DAFTAR ITEM</h3>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Print Stok</label>  <br>
							<form method="POST" action="<?php echo base_url().'stok/proses_laporan_stok/'; ?>">
							<select name="id_cabang" > 
								<option value="0">SEMUA</option>
								<?php
								$cabang = $this->db->get('cabang')->result_array();
								foreach($cabang as $c){ ?>
									<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'>
									<?php echo $c['nama']; ?></option>
								<?php } ?>
							</select>
							<input type="submit" value="PRINT" />
							</form>	
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
							<th style="text-align:center">H. Baru</th>
							<th style="text-align:center">H. Second</th>
							<?php if($check_admin){ ?>
							<th style="text-align:center">Actions</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php  
							foreach($data as $d){
							$cek = $this->komputer->cek($d['id'],'id_item','harga');
							$itensif = $this->komputer->cek($d['id'],'id_item','itensif');	
							if(!empty($cek)){
								$harga_pokok = $cek[0]['harga_pokok'];
								$harga_jual = $cek[0]['harga_jual'];
							} else {
								$harga_pokok = 0;
								$harga_jual = 0;
							}	
								
							if(!empty($itensif)){
								$ins_b = $itensif[0]['ins_b'];
								$ins_s = $itensif[0]['ins_s'];
							} else {
								$ins_b = 0;
								$ins_s = 0;
							}		
						?>
						<tr>
							<td><?php echo $d['nama'].' '.$d['tipe'].' '.$d['warna']; ?></td>
							<td style="text-align:center">
								<?php if(!empty($harga_jual)){ echo $this->komputer->format($harga_jual); } else {echo 'Nominal'; } ?>	
							</td>
							<td style="text-align:center">
								<?php if(!empty($harga_pokok)){ echo $this->komputer->format($harga_pokok); } else {echo 'Nominal'; } ?>	
							</td>
							<?php if($check_admin){ ?>
							<td style="text-align:center">
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhitem<?php echo $d['id']; ?>">Ubah</button>	
								<a href='<?php echo base_url().'persedian_page/delete_barang/item/'.$d['id']; ?>' onclick="return confirm('Menghapus daftar item dapat menghilangkan semua history?');">
									<span class="btn btn-danger fa fa-trash-o"> Hapus</span>
								</a>
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