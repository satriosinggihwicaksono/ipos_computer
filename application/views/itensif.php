<div class="modal fade" id="itensif<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'persedian_page/tambah_itensif/'.$d['id']; ?>">
			<div class="modal-body">
				<div class="row">
					<label>Intensif Baru</label>
					<input type="text" class="form-control border-input" name="ins_b" placeholder="" value="<?php echo $ins_b; ?>">
				</div>
				<div class="row">
					<label>Intensif Secon</label>
					<input type="text" class="form-control border-input" name="ins_s" placeholder="" value="<?php echo $ins_s; ?>">
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Submit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>