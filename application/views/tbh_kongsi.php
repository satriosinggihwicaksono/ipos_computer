<?php if($i['serial'] == 1){ ?>
<div class="modal fade" id="tbh_kongsi<?php echo $i['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/tambah_stok_kongsi/'?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" name="nota" placeholder="" value="" >
				</div>
				<div class="row">
					<label>Supplier :</label>
					<select name="id_supplier" >
						<?php
						$supplier = $this->db->get('supplier')->result_array();
						foreach($supplier as $s){ ?>
							<option value='<?php echo $s['id']; ?>'><?php echo $s['supplier']; ?></option>
						<?php } ?>
					</select>
				</div>
				
				<div class="row">
					<label>Keterangan :</label>
					<input type="text" name="keterangan" placeholder="" value="" >
				</div>
				<hr>
				
				<div class="row">
					<label>Nama Item :</label>
					<input type="text" placeholder="" value="<?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?>" disabled>
					<input type="hidden" name="id_item" value="<?php echo $i['id']; ?>">
				</div>
				
				<div class="row">
					<label>Serial :</label>
					<input type="text" name="serial" placeholder="" value="">
				</div>
				
				<div class="row">
					<label>Kondisi :</label>
					<select name="kondisi" >
						<option value='0'>Baru</option>
						<option value='1'>Second</option>
					</select>
				</div>
				
				<div class="row">
					<label>Harga Beli :</label>
					<input type="text" name="harga_pokok" placeholder="" value="">
					<input type="hidden" name="cabang" placeholder="" value="<?php echo $id_cabang; ?>">
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Item Kongsi</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>
<?php } else { ?>
<div class="modal fade" id="tbh_kongsi<?php echo $i['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/tambah_stok_kongsi/'?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" name="nota" placeholder="" value="" >
				</div>
				<div class="row">
					<label>Supplier :</label>
					<select name="id_supplier" >
						<?php
						$supplier = $this->db->get('supplier')->result_array();
						foreach($supplier as $s){ ?>
							<option value='<?php echo $s['id']; ?>'><?php echo $s['supplier']; ?></option>
						<?php } ?>
					</select>
				</div>
				
				<div class="row">
					<label>Keterangan :</label>
					<input type="text" name="keterangan" placeholder="" value="" >
				</div>
				<hr>
				
				<div class="row">
					<label>Nama Item :</label>
					<input type="text" placeholder="" value="<?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?>" disabled>
					<input type="hidden" name="id_item" value="<?php echo $i['id']; ?>">
				</div>
				
				<div class="row">
					<label>Unit :</label>
					<input type="text" name="unit" placeholder="" value="">
				</div>
				
				<div class="row">
					<label>Harga Beli :</label>
					<input type="text" name="harga_pokok" placeholder="" value="">
					<input type="hidden" name="cabang" placeholder="" value="<?php echo $id_cabang; ?>">
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Item Kongsi</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>

<?php
} ?>