<div class="modal fade" id="ubh_service<?php echo $d['id'] ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'service/ubah_service/'.$d['id']; ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php echo date('Y-m-d',strtotime($d['waktu'])); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" name="nota" placeholder="" value="<?php echo $d['nota']; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Nama :</label>
					<input type="text" class="form-control border-input" name="nama" placeholder="" value="<?php echo $nama; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Telepon :</label>
					<input type="text" class="form-control border-input" name="telepon" placeholder="" value="<?php echo $telepon; ?>" style="width:300px;">
				</div>
				<div class="row">
					<label>Keterangan :</label>
					<textarea name="keterangan" class="form-control border-input"><?php echo $keterangan; ?></textarea>
				</div>
				<div class="row">
					<label>Cabang :</label>
					<?php if($check_admin){ ?>
					<select name="cabang">
						<option></option>
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($d['cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$nama_cabang.'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="cabang" value="'.$id_cabang.'" />';
					}
					?>
				</div>
				<?php if($check_admin){ ?>
				<div class="row">
					<label>Teknisi :</label>
					<select name="teknisi" >
						<?php
						$this->db->where('hakakses',2);
						$user = $this->db->get('user')->result_array();
						foreach($user as $u){ ?>
							<option  <?php if($d['teknisi'] == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php } ?>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Service</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	