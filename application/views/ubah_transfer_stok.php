<?php 
if(($d['id_from'] == $id_cabang && $d['status'] == 0) || $check_admin ){ 
 	$disabled = '';
} else {
	$disabled = 'disabled';
}
?>
<div class="modal fade" id="ubahtransferstok<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/ubah_transfer_stok/'.$d['id']; ?>">
			<div class="modal-body">
				<div class='row'>
					<?php if($check_admin){ ?>
					<label>Tanggal Transfer : </label><br>
					<input type="date" name="tgl_trans" value="<?php echo date('Y-m-d',strtotime($d['tgl_trans'])); ?>">
					<input type="time" name="waktu_trans" value="<?php echo date('H:i:s',strtotime($d['tgl_trans'])); ?>">
					<?php 
						} else {
							echo '<input name="tgl_trans" value="'.date('d-m-Y H:i',strtotime($d['tgl_trans'])).'" disabled/>';
						} 	
					?>
				</div>	
				
				<div class='row'>
					<?php if($check_admin){ ?>	
							<label>Dari : </label>
							<select name="id_from">
								<?php  
									if(!$check_admin) $this->db->where('id !=',$id_cabang);
									$cabang = $this->db->get('cabang')->result_array();
									foreach($cabang as $c){
										echo "<option value='".$c['id']."'>".$c['nama']."</option>";
									} 
								?>
							</select>
						<?php } else { ?>
								<input type="hidden" name="id_from" value="<?php echo $d['id_from']; ?>"/>
						<?php } ?>
							<label>Kirim : </label><br>
							<?php if($d['status'] == 0 || $check_admin){ ?>
							<select name="id_to">
								<?php 
									if(!$check_admin) $this->db->where('id !=',$id_cabang);
									$cabang = $this->db->get('cabang')->result_array();
									foreach($cabang as $c){
										if($c['id'] == $d['id_to']){
											$selected = 'selected="selected"';
										} else {
											$selected = '';
										}
										echo "<option ".$selected." value='".$c['id']."'>".$c['nama']."</option>";
									}
								?>
							</select>
							<?php } else {
								echo '<input type="text" name="id_to" value="'.$this->komputer->namaCabang($d['id_to']).'" disabled />';		
							} ?>
				</div>	
					
				<div class="row">
					<label>Deskripsi : </label><br>
					<textarea name="deskripsi" <?php echo $disabled; ?> ><?php echo $d['deskripsi'];?></textarea>
				</div>	
			</div>
			<hr>
			<div class="modal-footer">
			<?php if($d['status'] == 0 || $check_admin){ ?>
				<button type="submit" class="btn btn-default">Ubah Transfer</button>
			<?php } ?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>