<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Printer extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('pdf');
		$this->load->helper('form');
		$this->load->library('javascript');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->load->helper('url');
    	$this->load->library('form_validation');
		if(!$this->session->logged_in){
			redirect('auth');
		}
	}
	
	public function printTransaksi($id){
		$transaksi = $this->komputer->cek($id,'id','transaksi');
		$nota = $transaksi[0]['nota'];
		$waktu = strtotime($transaksi[0]['waktu']);
		$id_user = $transaksi[0]['id_user'];
		$deskripsi = $transaksi[0]['deskripsi'];
		if(!empty($deskripsi)){
			$deskripsi = explode(',',$deskripsi);
			$pembeli = $deskripsi[0];
			$alamat = $deskripsi[1];
			$keterangan = $deskripsi[2];
		}
			
		include_once APPPATH . '/third_party/fpdf/pdf_js.php';
		$x = 70;
		$pdf = new PDF_AutoPrint('p','mm',array(210,297));
		$pdf->SetMargins(2.35, 2.35, 2.35, true);
		$pdf->SetAutoPageBreak(false, 2.35);
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('times','B',11);
        // mencetak string 
		$pdf->Cell(0,5,'.Perhatian',0,1,'L');
		$pdf->SetFont('times','',10);
		$pdf->Cell(0,5,'1. Barang yang sudah dibeli tidak dapat ditukar / dikembalikan.',0,1,'L');
		$pdf->Cell(0,5,'3. Barang yang tidak diambil setelah lebih dari 1 minggu, maka transaksi akan di anggap batal dan uang muka hilang.',0,1,'L');
		$pdf->Cell(0,5,'4. Harga barang tidak termasuk software dan kami hanya melayani install software Original',0,1,'L');
		$pdf->Cell(0,5,'5. Bila komplain atau kerusakan harus dibawa sendiri ke toko kami',0,1,'L');
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		
		$pdf->Cell(28,5,'',0,0);
		$pdf->Cell(87,5,'FAKTUR PENJUALAN ORBIT COMPUTER',0,0);
		$pdf->Image(base_url().'assets/images/orbit.jpg',5,34,25,15);
		$pdf->Cell(35,5,'Tanggal',0,0,'L');
		$pdf->Cell(3,5,':',0,0);
		$pdf->Cell(0,5,date('d M Y H:i',$waktu),0,0);
		$pdf->Cell(0,5,'',0,1);

		$pdf->Cell(28,5,'',0,0);
		$pdf->Cell(87,5,'JL.JENDRAL AHMAD YANI PLASA SIMPANG LIMA',0,0);
		$pdf->Cell(35,5,'No. Bukti',0,0,'L');
		$pdf->Cell(3,5,':',0,0);
		$pdf->Cell(20,5,$nota,0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(28,5,'',0,0);
		$pdf->Cell(87,5,'LANTAI 5 ('.$this->komputer->namaCabang($transaksi[0]['cabang']).')',0,0);
		$pdf->Cell(35,5,'Pembeli',0,0,'L');
		$pdf->Cell(3,5,':',0,0);
		if(!empty($pembeli)) $pdf->Cell(20,5,$pembeli,0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(28,5,'',0,0);
		$pdf->Cell(87,5,'082135300077 , 0248456386',0,0);
		$pdf->Cell(35,5,'Alamat',0,0,'L');
		$pdf->Cell(3,5,':',0,0);
		if(!empty($alamat)) $pdf->Cell(20,5,$alamat,0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(28,5,'',0,0);
		$pdf->Cell(87,5,'',0,0);
		$pdf->Cell(35,5,'Telpone',0,0,'L');
		$pdf->Cell(3,5,':',0,0);
		if(!empty($keterangan)) $pdf->Cell(20,5,$keterangan,0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		
        $pdf->SetFont('times','B',10);
		$pdf->Cell(8,5,'No.',0,0);
        $pdf->Cell(87,5,'Name',0,0);
		$pdf->Cell(30,5,'Harga',0,0,'L');
		$pdf->Cell(37,5,'No. Seri',0,0);
		$pdf->Cell(6,5,'Unit',0,0);
		$pdf->Cell(30,5,'Sub Total',0,1,'R');
        
		$pdf->SetFont('times','',10);
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		$pdf->Cell(0,5,'',0,1);
		$total = $transaksi[0]['bayar'] - $transaksi[0]['total'];
		$sb = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
		$x = 1;
		$total_keseluruhan = array();
		foreach($sb as $s){
		$item = $this->komputer->cek($s['id_item'],'id','item');
		$serial = $this->komputer->cek($s['id_serial'],'id','serial');
		$harga = $s['harga'] * $s['unit'];
		$total_keseluruhan[] = $harga;
		$pdf->SetFont('times','',10);
		$pdf->Cell(8,5,$x++,0,0,'C');
        if(!empty($item)) { 
			$pdf->Cell(92,5, $item[0]['kode'].'-'.$item[0]['nama'].' '.$item[0]['warna'].' '.$item[0]['tipe'],0,0); 
		} else {
			$pdf->Cell(92,5,'',0,0);
		}
			
		$pdf->Cell(30,5,$this->komputer->format($s['harga']),0,0,'L');
		if(!empty($serial)){	
		$pdf->Cell(37,5,$serial[0]['serial'],0,0,'L');
		} else {
		$pdf->Cell(37,5,'',0,0,'C');
		}	
		$pdf->Cell(6,5,$s['unit'],0,0,'C');
		$pdf->Cell(30,5,$this->komputer->format($harga),0,1,'R');
		}	
		
		$pdf->SetFont('times','',10);
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->SetFont('times','B',10);
		$pdf->Cell(40,5,'Penerima',0,0,'C');
		$pdf->Cell(50,5,'Hormat Kami',0,0,'C');
		$pdf->Cell(46,5,'',0,0);
		$pdf->Cell(20,5,'Total',0,0,'L');
		$pdf->Cell(47,5,$this->komputer->format($transaksi[0]['total']),0,1,'R');
		
		$pdf->Cell(136,5,'',0,0);
		$pdf->Cell(20,5,'Bayar',0,0,'L');
		$pdf->Cell(47,5,$this->komputer->format($transaksi[0]['bayar']),0,1,'R');
		
		$pdf->Cell(136,5,'',0,0);
		$pdf->Cell(20,5,'Kembalian',0,0,'L');
		$pdf->Cell(47,5,$this->komputer->format($total),0,1,'R');
		
		$pdf->Cell(40,5,$pembeli,0,0,'C');
		$pdf->Cell(50,5,$this->komputer->namaUser($id_user),0,0,'C');
		
		// simpan otomatis //
		$data = array(
			'status' => 1,
			'nota' => $this->komputer->namaCabang($transaksi[0]['cabang']).$this->komputer->nota($transaksi[0]['id'],5),
			'total' => array_sum($total_keseluruhan),
		);
		$ins = $this->komputer->updateItem('transaksi',$data,array('id' => $id));
		
		$sub_trans = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
		foreach($sub_trans as $s){
			$id_serial = $s['id_serial'];
			$data = array(
				'status' => 1,
			);
			$this->komputer->updateItem('serial',$data,array('id' => $id_serial));
		}

		$cek = $this->komputer->cek($id,'id_transaksi','kas');

		$data = array(
			'waktu' => $transaksi[0]['waktu'],
			'id_cabang' => $transaksi[0]['cabang'],
			'status' => 1,
			'deskripsi' => 'TRANSAKSI'.$transaksi[0]['nota'],
			'saldo' => array_sum($total_keseluruhan),
			'id_transaksi' => $transaksi[0]['id'],
		); 

		if(empty($cek)){
			$this->komputer->insertItem('kas',$data);
		} else {
			$this->komputer->updateItem('kas',$data,array('id'=> $cek[0]['id']));
		}
		
		// tutup 
		$pdf->AutoPrint();
		$pdf->Output();
	}
	
	public function printTransaksiThermal($id){
		$transaksi = $this->komputer->cek($id,'id','transaksi');
		$nota = $transaksi[0]['nota'];
		$waktu = strtotime($transaksi[0]['waktu']);
		$id_user = $transaksi[0]['id_user'];
		$deskripsi = $transaksi[0]['deskripsi'];
		if(!empty($deskripsi)){
			$deskripsi = explode(',',$deskripsi);
			$pembeli = $deskripsi[0];
			$alamat = $deskripsi[1];
			$keterangan = $deskripsi[2];
		}
		$count_data = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
		$count_data = count($count_data);
		include_once APPPATH . '/third_party/fpdf/pdf_js.php';
		$x = 80;
		$y = 78 + $count_data * 3;
		$pdf = new PDF_AutoPrint('p','mm',array($x,$y));
		$pdf->SetMargins(2.35, 2.35, 2.35, true);
		$pdf->SetAutoPageBreak(false, 2);
        // membuat halaman baru
        $pdf->AddPage();
        
		$total = $transaksi[0]['bayar'] - $transaksi[0]['total'];
		$sb = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
		$x = 1;
		$total_keseluruhan = array();

		$pdf->SetFont('times','B',5);
		
		$pdf->Cell(75,5,'NOTA ORBIT COMPUTER',0,1,'C');
		$pdf->Cell(75,5,'JL.JENDRAL AHMAD YANI PLASA SIMPANG LIMA LANTAI 5',0,1 ,'C');
		$pdf->Cell(75,5,'082135300077 , 0248456386',0,1,'C');
		
		$pdf->SetFont('times','B',5);
		$pdf->Cell(10,5,'No. Nota',0,0,'L');
		$pdf->Cell(2,5,':',0,0,'L');
		$pdf->Cell(20,5,$transaksi[0]['nota'],0,1,'L');
		
		$pdf->Cell(10,5,'Seles',0,0,'L');
		$pdf->Cell(2,5,':',0,0,'L');
		$pdf->Cell(20,5,$this->komputer->namaUser($transaksi[0]['id_user']),0,1,'L');
		
		$pdf->Cell(10,5,'Pembeli',0,0,'L');
		$pdf->Cell(2,5,':',0,0,'L');
		$pdf->Cell(20,5,$pembeli,0,1,'L');
		
		$pdf->Cell(10,5,'Tgl Trans',0,0,'L');
		$pdf->Cell(2,5,':',0,0,'L');
		$pdf->Cell(20,5,date('d/m/Y H:i',strtotime($transaksi[0]['waktu'])),0,1,'L');
		
		
		$pdf->SetFont('times','',8);
		$pdf->Cell(0,5,'------------------------------------------------------------------------------',0,1,'C');
		
		foreach($sb as $s){
		$item = $this->komputer->cek($s['id_item'],'id','item');
		$serial = $this->komputer->cek($s['id_serial'],'id','serial');
		$harga = $s['harga'] * $s['unit'];
		$total_keseluruhan[] = $harga;
		
			
		$pdf->SetFont('times','B',5);
		$pdf->Cell(1,5,$x++,0,0,'C');
        if(!empty($item)) { 
			$pdf->Cell(50,5, $item[0]['nama'].' '.$item[0]['tipe'],0,0); 
		} else {
			$pdf->Cell(50,5,'',0,0);
		}
			
		$pdf->Cell(7,5,$s['harga'],0,0,'L');
		if(!empty($serial)){	
		$pdf->Cell(37,5,$serial[0]['serial'],0,0,'L');
		} else {
		$pdf->Cell(1,5,'',0,0,'C');
		}	
		$pdf->Cell(2,5,$s['unit'],0,0,'C');
		$pdf->Cell(10,5,$this->komputer->format($harga),0,1,'R');
		}	
		
		$pdf->SetFont('times','',8);
		$pdf->Cell(0,5,'------------------------------------------------------------------------------',0,1,'C');
		
		$pdf->SetFont('times','B',5);
		$pdf->Cell(20,5,'Sub Total',0,0,'L');
		$pdf->Cell(51,5,$this->komputer->format($transaksi[0]['total']),0,1,'R');
		
		$pdf->Cell(15,5,'Total',0,0,'L');
		$pdf->Cell(0,5,$this->komputer->format($transaksi[0]['total']),0,1,'L');
		
		$pdf->Cell(15,5,'Bayar',0,0,'L');
		$pdf->Cell(0,5,$this->komputer->format($transaksi[0]['bayar']),0,1,'L');
		
		$pdf->Cell(15,5,'Kembalian',0,0,'L');
		$pdf->Cell(0,5,$this->komputer->format($total),0,1,'L');
		
		// simpan otomatis //
		$data = array(
			'status' => 1,
			'nota' => $this->komputer->namaCabang($transaksi[0]['cabang']).$this->komputer->nota($transaksi[0]['id'],5),
			'total' => array_sum($total_keseluruhan),
		);
		$ins = $this->komputer->updateItem('transaksi',$data,array('id' => $id));
		
		$sub_trans = $this->komputer->cek($id,'id_transaksi','sub_transaksi');
		foreach($sub_trans as $s){
			$id_serial = $s['id_serial'];
			$data = array(
				'status' => 1,
			);
			$this->komputer->updateItem('serial',$data,array('id' => $id_serial));
		}

		$cek = $this->komputer->cek($id,'id_transaksi','kas');

		$data = array(
			'waktu' => $transaksi[0]['waktu'],
			'id_cabang' => $transaksi[0]['cabang'],
			'status' => 1,
			'deskripsi' => 'TRANSAKSI'.$transaksi[0]['nota'],
			'saldo' => array_sum($total_keseluruhan),
			'id_transaksi' => $transaksi[0]['id'],
		); 

		if(empty($cek)){
			$this->komputer->insertItem('kas',$data);
		} else {
			$this->komputer->updateItem('kas',$data,array('id'=> $cek[0]['id']));
		}
		
		// tutup 
		$pdf->AutoPrint();
		$pdf->Output();
    }
	
	public function printTransfer($id){
		$trans = $this->komputer->cek($id,'id','trans_stok');
		
		
		include_once APPPATH . '/third_party/fpdf/pdf_js.php';
		$x = 70;
		$total_unit = array();
		$pdf = new PDF_AutoPrint('p','mm',array(210,297));
		$pdf->SetMargins(2.35, 2.35, 2.35, true);
		$pdf->SetAutoPageBreak(false, 2.35);
        // membuat halaman baru
        $pdf->AddPage();
        $pdf->SetFont('times','B',11);
		$pdf->Cell(0,5,'Bukti Transfer Cabang(Out)',0,1,'C');
		$pdf->Cell(0,5,'Kota : Semarang',0,1,'C');
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');

		$pdf->Cell(30,5,'Tanggal kirim',0,0);
		$pdf->Cell(3,5,':',0,0);
		if(!empty($trans[0]['tgl_trans'])) $pdf->Cell(3,5, date('d M Y',strtotime($trans[0]['tgl_trans'])),0,0);
	
		$pdf->Cell(140,5,'Tanggal konfirmasi',0,0,'R');
		$pdf->Cell(3,5,':',0,0);
		if(date('Y',strtotime($trans[0]['tgl_con'])) == 0001) $pdf->Cell(0,5,date('d M Y',strtotime($trans[0]['tgl_con'])),0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(30,5,'Dari',0,0);
		$pdf->Cell(3,5,':',0,0);
		if($trans[0]['id_from']) $pdf->Cell(3,5,$this->komputer->namaCabang($trans[0]['id_from']),0,0);
		
		$pdf->Cell(140,5,'Ke',0,0,'R');
		$pdf->Cell(3,5,':',0,0);
		if($trans[0]['id_to']) $pdf->Cell(0,5,$this->komputer->namaCabang($trans[0]['id_to']),0,0,'R');
		
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(176,5,'Penerima',0,0,'R');
		$pdf->Cell(3,5,':',0,0);
		if($trans[0]['id_user']) $pdf->Cell(0,5,$this->komputer->namaUser($trans[0]['id_user']),0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		
        $pdf->SetFont('times','B',11);
		$pdf->Cell(8,5,'No.',0,0);
        $pdf->Cell(100,5,'Name',0,0);
		$pdf->Cell(60,5,'No. Seri',0,0);
		$pdf->Cell(0,5,'Unit',0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->SetFont('times','B',11);
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		$pdf->Cell(0,5,'',0,1);
		
		
		$sub_trans = $this->komputer->cek($id,'id_trans_stok','trans_item');
		$x = 1;
		foreach($sub_trans as $st){
		$item = $this->komputer->cek($st['id_item'],'id','item');
		$serial = $this->komputer->cek($st['serial'],'id','serial');
		$pdf->SetFont('times','',10);
		$pdf->Cell(8,5,$x++,0,0,'C');
        $pdf->Cell(100,5,$item[0]['kode'].'-'.$item[0]['nama'].' '.$item[0]['warna'].' '.$item[0]['tipe'],0,0);
		
		if(!empty($serial)){	
		$pdf->Cell(60,5,$serial[0]['serial'],0,0,'L');
		} else {
		$pdf->Cell(40,5,'',0,0,'C');
		}
			
		$pdf->Cell(0,5,$st['unit'],0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		$total_unit[] = $st['unit'];
		}		
		
		$pdf->SetFont('times','B',11);
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->SetFont('times','B',11);
		$pdf->Cell(136,5,'',0,0);
		$pdf->Cell(20,5,'Total',0,0,'L');
		$pdf->Cell(0,5,array_sum($total_unit),0,1,'R');
		
		$pdf->AutoPrint();
		$pdf->Output();
    }
	
	public function printService($id){
		$trans = $this->komputer->cek($id,'id','service');
		$deskripsi = $trans[0]['deskripsi'];
		$deskripsi = explode(',',$deskripsi);
		include_once APPPATH . '/third_party/fpdf/pdf_js.php';
		$x = 70;
		$total_unit = array();
		$pdf = new PDF_AutoPrint('p','mm',array(210,297));
		$pdf->SetMargins(2.35, 2.35, 2.35, true);
		$pdf->SetAutoPageBreak(false, 2.35);
        // membuat halaman baru
        $pdf->AddPage();
        $pdf->SetFont('times','B',11);
		$pdf->Cell(0,5,'Nota Service',0,1,'C');
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');

		$pdf->Cell(30,5,'Tanggal Service',0,0);
		$pdf->Cell(3,5,':',0,0);
		if(!empty($trans[0]['waktu'])) $pdf->Cell(3,5, date('d M Y',strtotime($trans[0]['waktu'])),0,0);
	
		$pdf->Cell(140,5,'Tanggal konfirmasi',0,0,'R');
		$pdf->Cell(3,5,':',0,0);
		if(date('Y',strtotime($trans[0]['waktu_con'])) == 0001) $pdf->Cell(0,5,date('d M Y',strtotime($trans[0]['waktu_con'])),0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(30,5,'Nama',0,0);
		$pdf->Cell(3,5,':',0,0);
		if($deskripsi[0]) $pdf->Cell(3,5,$deskripsi[0],0,0);
		
		$pdf->Cell(140,5,'Teknisi',0,0,'R');
		$pdf->Cell(3,5,':',0,0);
		if($trans[0]['teknisi']) $pdf->Cell(0,5,$this->komputer->namaUser($trans[0]['teknisi']),0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(30,5,'Telepon',0,0);
		$pdf->Cell(3,5,':',0,0);
		if($deskripsi[1]) $pdf->Cell(3,5,$deskripsi[1],0,0);
		
		$pdf->Cell(140,5,'Cabang',0,0,'R');
		$pdf->Cell(3,5,':',0,0);
		if($trans[0]['cabang']) $pdf->Cell(3,5,$this->komputer->namaCabang($trans[0]['cabang']),0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(30,5,'Keterangan',0,0);
		$pdf->Cell(3,5,':',0,0);
		if($deskripsi[2]) $pdf->Cell(3,5,$deskripsi[2],0,0);
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		
        $pdf->SetFont('times','B',11);
		$pdf->Cell(8,5,'No.',0,0);
        $pdf->Cell(160,5,'Keterangan',0,0);
		$pdf->Cell(5,5,'Unit',0,0,'C');
		$pdf->Cell(0,5,'Total',0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->SetFont('times','B',11);
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		$pdf->Cell(0,5,'',0,1);
		
		$sub_trans = $this->komputer->cek($id,'id_service','sub_service');
		$x = 1;
		foreach($sub_trans as $st){
		$item = $this->komputer->cek($st['id_item'],'id','item');
		$serial = $this->komputer->cek($st['id_serial'],'id','serial');
		$pdf->SetFont('times','',10);
		$pdf->Cell(8,5,$x++,0,0,'C');	
        if($st['id_item'] != 0){ 
			$pdf->Cell(160,5,$item[0]['kode'].'-'.$item[0]['nama'].' '.$item[0]['warna'].' '.$item[0]['tipe'],0,0); 
		} else {
			$pdf->Cell(160,5,$st['service'],0,0); 
		}	
		if($st['id_item'] != 0){ $pdf->Cell(5,5,$st['unit'] ,0,0,'R'); } 
		else { $pdf->Cell(5,5,'',0,0,'R'); }
			
		$pdf->Cell(0,5,$this->komputer->format($st['harga']),0,0,'R');
		$pdf->Cell(0,5,'',0,1);
		$total_unit[] = $st['harga'];
		}		
		
		$pdf->SetFont('times','B',11);
		$pdf->Cell(0,5,'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
		$pdf->Cell(0,5,'',0,1);
		
		$pdf->SetFont('times','B',11);
		$pdf->Cell(136,5,'',0,0);
		$pdf->Cell(20,5,'Total',0,0,'L');
		$pdf->Cell(0,5,$this->komputer->format(array_sum($total_unit)),0,1,'R');
		
		$pdf->AutoPrint();
		$pdf->Output();
    }
	
	public function printSN(){
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode('%5E',$link);
			$nama = $t[0];
			$nama = str_replace("%20"," ",$nama);
			$merek = $t[1];
			$merek = str_replace("%20"," ",$merek);
			$id_kategori = $t[2];
			$cabang = $t[3];
			$kondisi = $t[4];
		} else {
			$nama = '';
			$merek = '';
			$id_kategori = '';
			$cabang = '';
			$kondisi = '';
		}
		
		$this->db->select('item.nama,item.tipe,item.warna,serial.serial,serial.kondisi,serial.cabang,serial.status');
		$this->db->join('item','serial.id_item = item.id');
		$this->db->join('pembelian','serial.id_pembelian = pembelian.id');
		$this->db->where('serial.status',0);
		
		if(!empty($nama))$this->db->like('item.nama',$nama);
		if(!empty($merek)) $this->db->like('item.merek',$merek);
		if(!empty($id_kategori)) $this->db->where('item.id_kategori',$id_kategori);
		if(!empty($cabang)) $this->db->where('serial.cabang',$cabang);
		$this->db->where('serial.kondisi',$kondisi);
		$data_result = $this->db->get('serial')->num_rows();	
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/persedian_page/barang_sn/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/persedian_page/barang_sn/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 50;
		$this->pagination->initialize($config);
		
		$this->db->select('item.nama,item.tipe,item.warna,serial.serial,serial.kondisi,serial.cabang,serial.status');
		$this->db->join('item','serial.id_item = item.id');
		$this->db->join('pembelian','serial.id_pembelian = pembelian.id');
		$this->db->where('.serial.status',0);
		if(!empty($nama))$this->db->like('item.nama',$nama);
		if(!empty($merek)) $this->db->like('item.merek',$merek);
		if(!empty($id_kategori)) $this->db->where('item.id_kategori',$id_kategori);
		if(!empty($cabang)) $this->db->where('serial.cabang',$cabang);
		$this->db->where('serial.kondisi',$kondisi);
		$query = $this->db->get('serial',$config['per_page'],$from)->result_array();
		
		$url = $nama.'^'.$merek.'^'.$id_kategori.'^'.$cabang.'^'.$kondisi;
		
		$data['data'] = $query;
		$data['nama'] = $nama;
		$data['merek'] = $merek;
		$data['id_kategori'] = $id_kategori;
		$data['posisi'] = $posisi;
		$data['kondisi'] = $kondisi;
		$data['cabang'] = $cabang;
		$data['link'] = $link;
		$data['url'] = $url;
		$this->load->view('laporan_stok_sn',$data);
	}
	
	public function laporan_penjualan()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->komputer->getIdCabang($username);
		$check_admin = $this->komputer->isAdmin($username);
		
		$link = $this->uri->segment(3);
		if(!empty($link)){
			$posisi=strpos($link,'%5E',1);
		} else {
			$posisi = 0;
		}
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = $t[0];
			$month = $t[1];
			$year = (int)$t[2];
			$tanggal = (int)$t[3];
			$seles = (int)$t[4];
		}  else {
			$cabang = '';
			$month = date('m');
			$year = date('Y');
			$tanggal = '';
			$seles = '';
		}
		
		if(!$check_admin) {
			$this->db->where('cabang',$id_username);
		}
		
		if($check_admin && $cabang != 0){
			$this->db->where('cabang',$cabang);
		}
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		if(!empty($seles)) $this->db->where('id_user',$seles);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)', date('Y',$tanggal));
			$this->db->where('MONTH(waktu)', date('m',$tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->where('status',1);
		$query = $this->db->get('transaksi')->result_array();
		$data['data'] = $query;
		$this->load->view('laporan_penjualan',$data);
	}
}
