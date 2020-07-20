<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$nama = $t[0];
	$nama = str_replace("%20"," ",$nama);
	$ket = $t[1];
	$id_cabang = $t[2];
} else {
	$kode = '';
	$nama = '';
	$ket = '';
	$id_cabang = '';
}
?>
<div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Stok Opname</label><br>
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhlist">Tambah List</button>
							</div>
						</div>
					</div>
				</div>
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<form method="POST" action="<?php echo base_url().'stok/search_stok_opname/'; ?>">
							<tr>
								<th>
									Tanggal <input type="text" class="form-control border-input" name="nama" placeholder="" value="<?php echo $nama; ?>" style="width:150px">
								</th>
								<th>
									Keterangan <input type="text" class="form-control border-input" name="ket" placeholder="" value="<?php echo $ket; ?>" style="width:150px">
								</th>
								<th>Cabang
									<?php if($check_admin){ ?>
									<select name="cabang" >
										<option></option>
										<?php
										$cabang = $this->db->get('cabang')->result_array();
										foreach($cabang as $c){ ?>
											<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'>
											<?php echo $c['nama']; ?></option>
										<?php } ?>
									</select>
	 								<?php } else {
										echo '<input value="'.$nama_cabang.'" disabled/>';
										echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
									}
									?>
								</th>
								<th><button type="submit" class="btn btn-warning">Cari</button></th>
							</tr>
							</form>	
						</thead>
					</table>
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>Tanggal laporan</th>
								<th>Cabang</th>
								<th>Keterangan</th>
								<th>Users</th>
								<th style="text-align:center;">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($data as $d){ ?>
							<tr>
								<td><?php echo date('d-m-Y',strtotime($d['waktu'])); ?></td>
								<td><?php echo $this->komputer->namaCabang($d['id_cabang']); ?></td>
								<td><?php echo $d['ket']; ?></td>
								<td><?php echo $d['users']; ?></td>
								<td style="text-align:center">
									<a href="<?php echo base_url().'laporan/detail_stok_opname/'.$d['id']; ?>"><button type="button" class="btn btn-success">Detail</button></a>	
									<a href='<?php echo base_url().'laporan/delete_stok_opname/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
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
		
			if($posisi > 0){
				$page = $this->uri->segment(4) - 10;
				$href = base_url().'laporan/'.$this->uri->segment(2).'/'.$link.'/'.$page;
			} else {
				$page = $this->uri->segment(3) - 10;
				$href = base_url().'laporan/'.$this->uri->segment(2).'/'.$page;	
			}
			echo $this->pagination->create_links();
			?>
	</div>
</div>

<div class="modal fade" id="tbhlist" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'laporan/tambah_stok_opname' ?>">
			<div class="modal-body">
				<label>Tanggal</label>
				<input type="date" class="form-control border-input" name="waktu" placeholder="" value="">

				<label>Ket</label>
				<input type="text" class="form-control border-input" name="ket" placeholder="" value="">

				<label>Cabang : </label>
				<?php if($check_admin){ ?>
				<select name="cabang" >
				<option></option>
				<?php
				$cabang = $this->db->get('cabang')->result_array();
				foreach($cabang as $c){ ?>
					<option <?php if($id_cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'>
					<?php echo $c['nama']; ?></option>
				<?php } ?>
				</select>
				<?php } else {
					echo '<input value="'.$nama_cabang.'" disabled/>';
					echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
				}
				?>
				</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Item</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>