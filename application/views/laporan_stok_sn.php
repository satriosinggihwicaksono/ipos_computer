<?php 
$url = $this->uri->segment(3);
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Laporan stok</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		table {
		  font-size:9px;
		  border-collapse: collapse;
		}
		 th {
		  background: #ccc;
		}

		th, td {
		  border: 1px solid #ccc;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background: #efefef;
		}

		tr:hover {
		  background: #d1d1d1;
		}
	</style>
</head>
<body onload="window.print()">
	<h2>Stok Konter <?php echo $this->komputer->namaCabang($url); ?></h2>
		<table class="my_table" width="100%" cellspacing="0">
			<thead style='background-color:#FFEB3B'>
				<tr>
					<th>Nama</th>
					<th style="text-align:center">Serial</th>
					<th style="text-align:center">Cabang</th>
					<th style="text-align:center">Kondisi</th>
				</tr>
			</thead>
			<tbody>
				<?php 	
					foreach($data as $is){
				?>
				<tr>
					<td><?php echo $is['nama'].' '.$is['tipe'].' '.$is['warna']; ?></td>
					<td><?php echo $is['serial']; ?></td>
					<td><?php echo $this->komputer->namaCabang($is['cabang']); ?></td>
					<td>
						<?php
						if($is['kondisi'] == 1){
							echo 'BEKAS'; 
						} else {
							echo 'BARU';
						}	
						?>
					</td>
				</tr>
				<?php 
					} 
				?>
			</tbody>
		</table>
	</body>
</html>