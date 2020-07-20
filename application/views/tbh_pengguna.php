<div class="modal fade" id="tbh_pengguna" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'auth/registration/'; ?>">
			<div class="modal-body">
			<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Username</label>
							<input type="text" name="username" class="form-control border-input" placeholder="Username" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama</label>
							<input type="text" name="name" class="form-control border-input" placeholder="Name" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password</label>
							<input type="password" name="password" class="form-control border-input" placeholder="Password baru" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Re-password</label>
							<input type="password" name="repassword" class="form-control border-input" placeholder="Repassword" value="">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Pengguna</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>