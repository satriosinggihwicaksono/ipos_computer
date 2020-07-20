<div class="modal fade" id="pelunasan<?php echo $d['id'] ?>" role="dialog">
	<div class="modal-dialog modal-large" style="margin:30px 0px 0px 0px; width:100%;" >
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'stok/add_pelunasan/'.$d['id']; ?>">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2">
						<label>Tanggal Pembelian</label><br>
						<label><?php echo date('d-m-Y',strtotime($d['waktu'])); ?></label>
					</div>
					<div class="col-md-8">
						<label>Total Pembelian</label>
						<input type="text" style="text-align:right" class="form-control border-input" value="<?php echo $this->komputer->format($total_pembelian); ?>"  disabled>
					</div>
					<div class="col-md-2">
					<input type="hidden" name='total_pembelian' value="<?php echo $total_pembelian; ?>" >
						<input type="hidden" name='bayar' value="<?php echo $d['bayar']; ?>" >
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<input type="text" style="text-align:right" class="form-control border-input" value="<?php echo $this->komputer->format($d['bayar']); ?>"  disabled>
					</div>
					<div class="col-md-2"></div>
				</div>
				
				<?php
					$total_pelunasan = array();
					$this->db->where('id_pembelian', $d['id']);
					$pelunasan = $this->db->get('pelunasan')->result_array();
					foreach($pelunasan as $p){
					$total_pelunasan[] = $p['nominal'];
				?>
				<div class="row">
					<div class="col-md-2">
						<label><?php echo date('d-m-Y',$p['waktu']); ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" style="text-align:right" class="form-control border-input" value="<?php echo $this->komputer->format($p['nominal']); ?>"  disabled>
					</div>
					<div class="col-md-2">
						<a href='<?php echo base_url().'stok/delete_pelunasan/pelunasan/'.$p['id'].'/'.$d['id'].'/'.$d['bayar'].'/'.$total_pembelian.'/'.date('Y-m-d H:i:s', strtotime($d['waktu'])).'/'.date('Y-m-d H:i:s', strtotime($d['waktu_tempo'])); ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
					</div>
				</div>
				<?php 
					}
					$total_pelunasan = array_sum($total_pelunasan);
					$total_kekuranan = $total_pembelian - ($total_pelunasan + $d['bayar']);
				?>
				<div class="row">
					<div class="col-md-2">
						<label>Kekurangan</label>
					</div>
					<div class="col-md-8">
						<input type="text" style="text-align:right" class="form-control border-input" value="<?php echo $this->komputer->format($total_kekuranan); ?>"  disabled>
					</div>
					<div class="col-md-2"></div>
				</div>
				
				<div class="row">
					<div class="col-md-2">
						<input type="date" name="waktu" value="<?php echo date('Y-m-d'); ?>"/>
						<input name="waktu_pembelian" type="hidden"  value="<?php echo date('Y-m-d H:i:s', strtotime($d['waktu'])); ?>"/>
						<input name="waktu_con" type="hidden"  value="<?php echo date('Y-m-d H:i:s', strtotime($d['waktu_tempo'])); ?>"/>
					</div>
					<div class="col-md-8">
						<input type="text" style="text-align:right" class="form-control border-input" name="nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="">
					</div>
					<div class="col-md-2"></div>
				</div>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Bayar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>	