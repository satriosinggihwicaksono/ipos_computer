<div class="modal fade" id="ubhkas<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'kas/ubah_kas/'.$d['id']; ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="tanggal" placeholder="" value="<?php echo date('Y-m-d',strtotime($d['waktu'])); ?>">
					<input type="hidden" name="waktu" placeholder="" value="<?php echo date('H:i:s',strtotime($d['waktu'])); ?>">
				</div>
				<div class="row">
					<label>Cabang :</label>
					<?php if($check_admin){ ?>
					<select name="id_cabang">
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($d['id_cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$nama_cabang.'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="id_cabang" value="'.$d["id_cabang"].'" />';
					}
					?>
				</div>
				<div class="row">
					<label>Keterangan :</label>
					<input type="text" class="form-control border-input" name="deskripsi" placeholder="" value="<?php echo $d['deskripsi']; ?>" style="width:250px;">
				</div>
				<div class="row">
					<label>Nominal :</label>
					<input type="text" class="form-control border-input" name="saldo" placeholder="" value="<?php echo $d['saldo']; ?>" style="width:250px;">
				</div>
				<div class="row">
					<label>Status :</label>
					<select name="status" >
						<?php
						$status = array('','Debit','Kredit');
						$status_count = count($status);
						for($x=1; $x < $status_count; $x++){
							if($x == $d['status']){
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo '<option '.$selected.' value='.$x.'>'.$status[$x].'</option>';	
						 } ?>
					</select>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Kas</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	