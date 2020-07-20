<div class="modal fade" id="ubhpassword<?php echo $id_username; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'auth/update_password_pengguna/'.$id_username; ?>">
			<div class="modal-body">
			<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Username</label>
							<input type="text" name="username" readonly class="form-control border-input" placeholder="Company" value="<?php echo $username;?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password lama</label>
							<input type="password" name="old_password" class="form-control border-input" placeholder="Password lama" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password baru</label>
							<input type="password" name="new_password" class="form-control border-input" placeholder="Password baru" value="">
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
				<button type="submit" class="btn btn-default">Ubah Password</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>