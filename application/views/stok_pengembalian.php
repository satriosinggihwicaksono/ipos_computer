<?php 
	$serial = $this->uri->segment(3);
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Pengembalian STOK</label>
						</div>
					</div>
				</div>
			</div>
			<div class="breadcomb-area">
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#FFFFFFF'>
						<form method="POST" action="<?php echo base_url().'stok/search_stok_pengembalian/'; ?>">
						<tr>
							<th> Serial : <input name="serial" value="<?php echo $serial; ?>"/></th>
							<th><button type="submit" class="btn btn-warning">Cari</button></th>
						</tr>
						</form>	
					</thead>
				</table>
			</div>
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#99ccff'>
						<tr>
							<th style="text-align:center; width:100px;">No</th>
							<th style="text-align:left;">Nama</th>
							<th style="text-align:center; width:25px;">Serial</th>
							<th style="width:25px;">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$x = 1;
						foreach($data as $d){
					?>
					<tr>
						<td><?php echo $x++; ?></td>	
						<td><?php echo $d['nama'].' '.$d['tipe'].' '.$d['warna']; ?></td>	
						<td><?php echo $d['serial']; ?></td>	
						<td><a href="<?php echo base_url().'stok/proses_stok_pengembalian/'.$d['id']; ?>" onclick="return confirm('Are you sure delete this item?');"><button class="btn btn-success">Kembalikan</button></a></td>	
					</tr>
					<?php
							}
						?>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="text-center">
	<?php

		if(!empty($serial)){
			$page = $this->uri->segment(4) - 10;
			$href = base_url().'transaksi/'.$this->uri->segment(2).'/'.$serial.'/'.$page;
		} else {
			$page = $this->uri->segment(3) - 10;
			$href = base_url().'transaksi/'.$this->uri->segment(2).'/'.$page;
		}
		echo $this->pagination->create_links();
		?>
</div>