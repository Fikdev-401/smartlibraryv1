<!DOCTYPE html>
<html>
<head>
	<title>Daftar Anggota Perpusatakaan</title>
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
		<table border="0" width="100%">
        <caption style="font-weight:bold; padding-bottom:15px">DAFTAR ANGGOTA PERPUSTAKAAN</caption>
        <thead>
        	<tr>
            	<th width="5%">No.</th>
                <th>No. Kartu</th>
                <th>Nama</th>
                <th>Tempat, Tgl. Lahir</th>
                <th width="5%">Kel.</th>
                <th>Alamat</th>
                <th width="15%">Telp</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        	<?php $no=1; ?>
        	@foreach($anggota as $rData)
        	<tr>
            	<td style="text-align:center; vertical-align:top"><?=$no?></td>
                <td style="vertical-align:top">{{$rData->no_kartu}}</td>
                <td style="vertical-align:top">{{$rData->nama}}</td>
                <td style="vertical-align:top;">{{$rData->tempat_lahir}}, <?=date('d M Y',strtotime($rData->tgl_lahir))?></td>
                <td style="vertical-align:top; text-align:center">{{$rData->kel}}</td>
                <td style="vertical-align:top">{{$rData->alamat}}</td>
                <td style="vertical-align:top">{{$rData->telp}}</td>
                <td style="vertical-align:top">{{$rData->email}}</td>
            </tr>
            <?php $no++;?>
            @endforeach
        </tbody>	
		</table>

</body>
</html>