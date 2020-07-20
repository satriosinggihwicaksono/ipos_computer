<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
				<!-- MENU -->	
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>No</th>
								<th>Supplier</th>
								<th>Hutang</th>
								<th style="text-align:center">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								$x = 1;
								$total_hutang_keseluruhan = array();
								foreach($data as $d){
								$total_hutang = $d['total_pembelian'] - ($d['pelunasan'] + $d['bayar']);
								$total_hutang_keseluruhan[] = $total_hutang;
							?>
							<tr>
								<td style="width:2%;"><?php echo $x++ ?></td>
								<td style="width:25%;">
									<?php echo $this->komputer->namaSupplier($d['id_supplier']); ?>						
								</td>
								<td>
									<?php
									echo $this->komputer->format($total_hutang);
									?>
								</td>
								<td style="text-align:center">
									<a href='<?php echo base_url().'stok/pembelian_stok/^^'.$d['id_supplier'].'^0^0^1^0'; ?>'><span class="btn btn-success"> Detail</span></a>
								</td>
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
</div>	