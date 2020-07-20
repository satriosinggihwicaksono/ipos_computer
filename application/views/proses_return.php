<div class="modal fade" id="proses_return<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/proses_return_stok/'.$d['id']; ?>">
			<div class="modal-body">
				<div class="row">
					<label>Proses :</label><br>
					<select name="proses_return">
						<?php 
							$count_proses = count($proses);
							for($p=0;$p<$count_proses;$p++){
						?>
							<option <?php if($p == $d['status']){ echo 'selected="selected"'; } ?> value="<?php echo $p; ?>"><?php echo $proses[$p]; ?></option>
						<?php 
							}
						?>
					</select>	
				</div>	
			</div>
				
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ganti Proses</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>