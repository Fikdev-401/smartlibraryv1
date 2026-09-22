<!DOCTYPE html>
<html>
<head>
	<title>Daftar Buku Hilang/Rusak Perpusatakaan</title>
    <style>
    		@page {
				/*size: 110mm 70mm; */
      			size: A4 landscape; /* DIN A4 standard, Europe */
      			margin:30px 30px 10px 30px;
    		}
		.header {
			font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
			font-size:14px; font-weight:bold;
			text-align:center; vertical-align:middle;
			padding-top:20px; padding-bottom:20px;
		}
		.text {
			font-family:Verdana, Geneva, sans-serif; font-size:11px;
		}
		.border {
			border:#666 1px solid; width:50%;;
		}
		table {
  			border-collapse: collapse;
  			border: 1px solid black;
		}
		table th {
			border: 1px solid black;
			font-family:Verdana, Geneva, sans-serif; font-size:13px;
			font-weight:bold; background-color:#CCC;
			padding-top:5px; padding-bottom:5px;
		}
		table td{
			border: 1px solid black;
			font-family:Verdana, Geneva, sans-serif; font-size:11px;
			padding-top:2px; padding-bottom:2px; padding-left:2px; padding-right:2px;
		}
	</style>
</head>
<body>
<?php
	$namabulan=array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')
?>
		<table border="0" width="100%">
        <caption style="font-weight:bold;"><p>DAFTAR BUKU HILANG/RUSAK PERPUSTAKAAN<br> Bulan &nbsp;
        <?=$namabulan[$id1]?> &nbsp;&nbsp;<?=$id2?></p></caption>
        <thead>
        	<tr>
            	<th width="4%">No.</th>
                <th width="10%">Tanggal</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th width="4%">Jml</th>
                <th width="10%">Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
        	<?php $no=1; ?>
        	@foreach($data as $rData)
        	<tr>
            	<td style="text-align:center; vertical-align:top"><?=$no?></td>
                <td style="text-align:center; vertical-align:top"><?=date('d-m-Y',strtotime($rData->tanggal))?></td>
                <td style="vertical-align:top">ISBN: {{$rData->isbn}}<br>{{$rData->judul}}</td>
                <td style="vertical-align:top">{{$rData->pengarang}}</td>
                <td style="vertical-align:top">{{$rData->penerbit}} / {{$rData->tempat_terbit}} / {{$rData->tahun_terbit}}</td>
                <td style="vertical-align:top; text-align:center">{{$rData->jumlah}}</td>
                <td style="vertical-align:top; text-align:center">{{$rData->jenis}}</td>
                <td style="vertical-align:top;">{{$rData->catatan}}</td>
            </tr>
            <?php $no++;?>
            @endforeach
        </tbody>	
		</table>
<p style="text-align:right; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
	
</p>
</body>
</html>