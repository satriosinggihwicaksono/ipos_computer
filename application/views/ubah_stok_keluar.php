<div class="modal fade" id="ubah_stok_keluar<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/ubah_stok_keluar/'.$d['id'] ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" value="<?php echo date('Y-m-d',strtotime($d['waktu'])); ?>">
				</div>
	
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" name="nota" value="<?php echo $d['nota']; ?>" style="width:200px;">
				</div>
				
				<div class="row">
					<label>Cabang :</label>
					<select name="cabang" >
						<?php
						$this->db->where('id !=', 0);
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($d['id_cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
				</div>
				
				<div class="row">
					<label>Keterangan :</label>
					<textarea type="text" class="form-control border-input" name="keterangan"><?php echo $d['keterangan']; ?></textarea>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Stok Keluar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>