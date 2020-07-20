<div class="modal fade" id="hargajual<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'persedian_page/harga/'.$d['id']; ?>">
			<div class="modal-body">
				<label>Harga Baru</label>
				<input type="text" class="form-control border-input" name="harga_jual" placeholder="" value="<?php if(!empty($harga_jual)) echo $harga_jual; ?>">
				
				<label>Harga Second</label>
				<input type="text" class="form-control border-input" name="harga_pokok" placeholder="" value="<?php if(!empty($harga_pokok)) echo $harga_pokok; ?>">
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