<?php include 'tbh_pengguna.php'; ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
					<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Pengguna</label>
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-info btn-fill btn-wd" data-toggle="modal" data-target="#tbh_pengguna" >Tambah Pengguna</button>
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Cabang</th>
								<th>Hak Akases</th>
								<th>Status</th>
								<th>Ubah Password</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								$x = $this->uri->segment('3') + 1;
								foreach($data as $d){
								include 'ubah_password.php';
							?>
							<tr>
								<td style="width:2%;"><?php echo $x++ ?></td>
								<form method="POST" action="<?php echo base_url().'auth/update_pengguna/'.$d['id'] ?>">
								<td>
									<?php echo $d['username']; ?>
								</td>
								<td>
									<select name="cabang" >
										<option value='0'>ALL</option>
										<?php
										$cabang = $this->db->get('cabang')->result_array();
										foreach($cabang as $c){ ?>
											<option <?php if($d['cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select name="hakakses" >
									<?php
										$status = array('Admin','Gudang','Seles');
										$status_count = count($status);
										for($x=0; $x<$status_count; $x++){
											if($d['hakakses'] == $x){
												$select = 'selected="selected"';
											} else {
												$select = '';
											}
											echo '<option '.$select.'  value="'.$x.'">'.$status[$x].'</option>';
										}
									?>
									</select>
								</td>	
								<td>
									<select name="status" >
									<?php
										$status = array('nonaktif','aktif');
										$status_count = count($status);
										for($x=0; $x<$status_count; $x++){
											if($d['status'] == $x){
												$select = 'selected="selected"';
											} else {
												$select = '';
											}
											echo '<option '.$select.'  value="'.$x.'">'.$status[$x].'</option>';
										}
									?>
									</select>
								</td>	
								<td>
									<span data-toggle="modal" data-target="#ubhpassword<?php echo $d['id']; ?>" class="fa fa-wrench icon-name"> Ubah Password</span>
								</td>		
								<td style="width:25%;">
									<button type='submit' class="btn btn-info fa fa-wrench"> Ubah</button>	
									<a href='<?php echo base_url().'persedian_page/delete_item/user/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
								</form>	
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center">
		<?php
			$page = $this->uri->segment(3) - 10;
			$href = base_url().'persedian_page/'.$this->uri->segment(2).'/'.$page;
			echo $this->pagination->create_links();
		?>
	</div>
</div>