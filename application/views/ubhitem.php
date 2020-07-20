<div class="modal fade" id="ubhitem<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'persedian_page/ubah_item/'.$d['id']; ?>">
			<div class="modal-body">
				<label>Kode</label>
				<input type="text" class="form-control border-input" name="kode" placeholder="" value="<?php echo $d['kode']; ?>">
				
				<label>Nama</label>
				<input type="text" class="form-control border-input" name="nama" placeholder="" value="<?php echo $d['nama']; ?>">
				
				<label>Merek</label>
				<input type="text" class="form-control border-input" name="merek" placeholder="" value="<?php echo $d['merek']; ?>">
				
				<label>Warna</label>
				<input type="text" class="form-control border-input" name="warna" placeholder="" value="<?php echo $d['warna']; ?>">
				
				<label>Tipe</label>
				<input type="text" class="form-control border-input" name="tipe" placeholder="" value="<?php echo $d['tipe']; ?>">
				
				<label>Kategori</label>
				<select name="kategori" >
					<?php
					$kategori = $this->db->get('kategori')->result_array();
					foreach($kategori as $k){ ?>
						<option <?php if($d['id_kategori'] == $k['id']){ echo 'selected="selected"'; } ?> value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
					<?php } ?>
				</select>
				
				<labe>Kondisi</labe>
				<select name="kondisi" >
					<?php
					$kondisi_status = array('','Baru','Second');
					$kondisi_count = count($kondisi_status);
					for($x=0; $x < $kondisi_count; $x++){
						if($d['kondisi'] == $x){
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='".$x."'>".$kondisi_status[$x]."</option>";
					 } ?>
				</select>
				<br>
				
				<label>Serial</label>
				<input type="checkbox" name="serial" <?php if($d['serial'] == 1){ echo 'value="1" checked'; } else { echo 'value="1"';} ?>/>	
				<br>
				
				<label>Harga Pokok</label>
				<input type="text" class="form-control border-input" name="harga_pokok" placeholder="" value="<?php if(!empty($harga_pokok)) echo $harga_pokok; ?>">
				
				<label>Harga Jual</label>
				<input type="text" class="form-control border-input" name="harga_jual" placeholder="" value="<?php if(!empty($harga_jual)) echo $harga_jual; ?>">
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Item</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>