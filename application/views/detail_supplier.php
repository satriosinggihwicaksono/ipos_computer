<div class="modal fade" id="detailsupplier<?php echo $k['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/detail_supplier/'.$k['id']; ?>">
			<div class="modal-body">
				<?php 
					$deskripsi = $k['deskripsi'];
					$deskripsi = explode('^',$deskripsi);
				?>
				<label>Nama Supplier</label>
				<input type="text" class="form-control border-input" name="supplier" placeholder="" value="<?php echo $k['supplier']; ?>">
				
				<label>Alamat</label>
				<input type="text" class="form-control border-input" name="alamat" placeholder="" value="<?php if(!empty($deskripsi[0])) echo $deskripsi[0]; ?>">
				
				<label>No. Handpone</label>
				<input type="text" class="form-control border-input" name="hp" placeholder="" value="<?php if(!empty($deskripsi[1])) echo $deskripsi[1]; ?>">	
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	