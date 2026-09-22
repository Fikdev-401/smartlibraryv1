// JavaScript Document
function convertDateIndotoDB(string) {
	//bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];
 
    tanggal = string.split("-")[2];
    //bulan = bulanIndo.indexOf(string.split(" ")[1]);
    //if(parseInt(bulan) < 10){
    //    bulan = "0" + bulan;
    //}
	bulan = string.split("-")[1];
    tahun = string.split("-")[0];
 
    return tanggal + "/" + bulan + "/" + tahun;
}