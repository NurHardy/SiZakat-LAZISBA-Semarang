<?php 
	header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=exportfile.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table class="table table-bordered table-hover" border='1'>
					<tr>
						<th style='width:90px;'>A.</th>
						<th colspan='3'>KAS</th>
						<th>Jumlah</th>
						<th>Total</th>
					</tr>
					<tr>
						<td></td>
						<td style='width:30px;'>I</td>
						<td colspan='2'>Saldo Awal</td>
						<td>200.000</td>
						<td>200.000</td>
					</tr>
					<tr>
						<td></td>
						<td>II</td>
						<td colspan='2'>Penerimaan</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style='width:30px;'>1.</td>
						<td>Zakat</td>
						<td>10.000</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>2.</td>
						<td>Infaq</td>
						<td>10.000</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td>20.000</td>
					</tr>
					
					<tr>
						<td></td>
						<td>III</td>
						<td colspan='2'>Pengeluaran</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style='width:30px;'>1.</td>
						<td>Zakat</td>
						<td>10.000</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td>10.000</td>
					</tr>
					<tr>
						<td></td>
						<td colspan='4'>SALDO KAS (I+II-III)</td>
						<td>210.000</td>
					</tr>
					<tr>
						<th style='width:30px;'>A.</th>
						<th colspan='3'>KAS</th>
						<th>Jumlah</th>
						<th>Total</th>
					</tr>
				</table>