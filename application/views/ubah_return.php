<div class="modal fade" id="ubah_return<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/ubah_return_stok/'.$d['id']; ?>">
			<div class="modal-body">
					<div class='row'>
						<label>Tanggal Return : </label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d',strtotime($d['waktu'])); ?>">
					</div>
					<div class='row'>
						<label>Nota : </label><br>
						<input type="text" name="nota" value="<?php echo $d['nota']; ?>">
					</div>
				<div class="row">
					<label>Supplier :</label><br>
					<select name="id_supplier">
						<?php 
							foreach($supplier as $s){
						?>
							<option <?php if($d['id_supplier'] == $s['id']){ echo 'selected="selected"'; } ?> value="<?php echo $s['id']; ?>"><?php echo $s['supplier']; ?></option>
						<?php 
							}
						?>
					</select>	
				</div>	
				<div class="row">
						<label>Keterangan : </label><br>
						<textarea name="keterangan"><?php echo $d['keterangan']; ?></textarea>
				</div>	
			</div>
				
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Return</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>