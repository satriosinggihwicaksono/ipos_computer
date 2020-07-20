
<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$id_item = $t[0];
	$month = $t[1];
	$year = $t[2];
	$cabang = $t[3];
	$seles = $t[4];
}

$item = $this->komputer->cek($id_item,'id','item');
?>
<div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFFFFFF'>
							<tr>
								<td>
									<label>Nama Item : </label>
									<?php echo $item[0]['nama'].' '.$item[0]['tipe'].' '.$item[0]['warna']; ?>
								</td>
								
								<td>
									<label>Cabang : </label>
									<?php if(!empty($cabang)) echo $this->komputer->namaCabang($cabang); ?>
								</td>
								<td>
									<label>Seles : </label>
									<?php if(!empty($eles)) echo $this->komputer->namaUser($seles); ?>
								</td>
							</tr>
						</thead>
					</table>
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>Tanggal</th>
								<th>Nota</th>
								<th>Seles</th>
								<th>Cabang</th>
								<th>Total</th>
								<th style="text-align:center">Kondisi</th>
								<th style="text-align:center">Detail Item</th>
								<th style="text-align:center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								foreach($data as $d){
								if($d['status'] == 0){
									$status = '<h2><span class="fa fa-exclamation-triangle" style="color:red;"></span></h2>';
								} else {
									$status = '<h2><span class="color-info fa fa-check-circle" style="color:green;"></span></h2>';
								}		
							?>
							<tr>
								<td><?php echo date('Y-m-d',strtotime($d['waktu'])); ?></td>
								<td><?php echo $d['nota']; ?></td>
								<td><?php echo $this->komputer->namaUser($d['id_user']); ?></td>
								<td><?php echo $this->komputer->namaCabang($d['cabang']); ?></td>
								<td><?php echo $this->komputer->format($d['total']); ?></td>
								<td style="text-align:center"><?php echo $status; ?></td>
								<td style="text-align:center">
									<a href='<?php echo base_url().'transaksi/detail_transaksi/'.$d['id']; ?>'><span class="btn btn-success"> Detail</span></a>
								</td>
								<td style="text-align:center">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhtransaksi<?php echo $d['id']; ?>">Ubah</button>	
									<a href='<?php echo base_url().'transaksi/delete_transaksi/'.$d['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
							</tr>
							<?php } ?>
						</tdetabody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center">
		<?php
		
			if($posisi > 0){
				$page = $this->uri->segment(4) - 10;
				$href = base_url().'transaksi/'.$this->uri->segment(2).'/'.$link.'/'.$page;
			} else {
				$page = $this->uri->segment(3) - 10;
				$href = base_url().'transaksi/'.$this->uri->segment(2).'/'.$page;
			}
			echo $this->pagination->create_links();
			?>
	</div>
</div>