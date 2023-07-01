<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaModel extends CI_Model {
  public function view(){
    return $this->db->get('tbl_siswa')->result(); // Tampilkan semua data yang ada di tabel siswa
  }
  public function get_keyword($keyword){
		$this->db->select('*');
		$this->db->from('tbl_siswa');
		$this->db->like('nama_lengkap', $keyword);
		$this->db->or_like('nama_sekolah', $keyword);
		$this->db->or_like('no_pendaftaran', $keyword);
		return $this->db->get()->result(); // Tampilkan data siswa berdasarkan keyword
	
		  }
}