<!DOCTYPE html>
<html>
<head>
	<title>Kartu Anggota</title>
    <style>
    		@page {
				size: 110mm 70mm; 
      			/*size: A4; /* DIN A4 standard, Europe */
      			margin:0px 0px 0px 0px;
    		}
		.header {
			font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
			font-size:14px; font-weight:bold;
			text-align:center; vertical-align:middle;
			padding-top:20px; padding-bottom:20px;
			border-bottom:#999 double 1px;
		}
		.text {
			font-family:Verdana, Geneva, sans-serif; font-size:11px;
		}
		.border {
			border:#666 1px solid; width:50%;;
		}
		table {
  			border-collapse: collapse;
  			border: 0px solid black;
		}
	</style>
</head>
<body>
		<table border="0" width="100%">
        	<tr>
            	<td colspan="4" class="header">KARTU ANGGOTA</td>
            </tr>
				<tr>
					<td rowspan="5" class="text" width="30%" style="text-align:center; vertical-align:middle; ">Foto<br>3x4</td>
                    <td class="text" width="20%">No. Anggota</td><td class="text" width="1%">:</td><td class="text">{{$data->no_kartu}}</td>
				</tr>
                <tr>
					<td class="text" width="20%">Nama</td><td class="text" width="1%">:</td><td class="text">{{$data->nama}}</td>
				</tr>
                <tr>
					<td class="text" width="30%" style="vertical-align:top">Tempat, Tgl. Lahir</td><td class="text" width="1%" style="vertical-align:top">:</td><td class="text" style="vertical-align:top">{{$data->tempat_lahir}}, <?=date('d-m-Y',strtotime($data->tgl_lahir))?></td>
				</tr>
                <tr>
					<td class="text" width="30%" style="vertical-align:top">Alamat</td><td class="text" width="1%" style="vertical-align:top">:</td><td class="text" style="vertical-align:top">{{$data->alamat}}</td>
				</tr>
                <tr>
					<td class="text" width="30%" style="vertical-align:top">Masa Berlaku</td><td class="text" width="1%" style="vertical-align:top">:</td><td class="text" style="vertical-align:top"></td>
				</tr>
                <tr>
                	<td colspan="4" style="text-align:right; padding-right:10px; vertical-align:bottom; " class="text" height="20">Tanda Tangan Pemilik</td>
                </tr>
                <tr>
                	<td colspan="4" style="text-align:right; padding-right:25px; vertical-align:bottom; font-variant:small-caps;"  class="text" height="40">{{$data->nama}}</td>
                </tr>
		</table>

</body>
</html>