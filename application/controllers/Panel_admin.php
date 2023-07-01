<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panel_admin extends CI_Controller
{

	public function index()
	{
		$sess = $this->session->userdata('id_admin');
		if ($sess == NULL) {
			$this->load->view('404_content');
		} else {
			$data = array(
				'user'		=> $this->admin->base('bio', $sess),
				'web_ppdb'	=> $this->admin->base('status-ppdb'),
				'judul_web'	=> "HOME",
				'v_thn'		=> date('Y'),

			);

			$thn  = date('Y');
			foreach ($this->Model_data->statistik($thn)->result_array() as $row) {
				$data['grafik'][] = (float)$row['Januari'];
				$data['grafik'][] = (float)$row['Februari'];
				$data['grafik'][] = (float)$row['Maret'];
				$data['grafik'][] = (float)$row['April'];
				$data['grafik'][] = (float)$row['Mei'];
				$data['grafik'][] = (float)$row['Juni'];
				$data['grafik'][] = (float)$row['Juli'];
				$data['grafik'][] = (float)$row['Agustus'];
				$data['grafik'][] = (float)$row['September'];
				$data['grafik'][] = (float)$row['Oktober'];
				$data['grafik'][] = (float)$row['Nopember'];
				$data['grafik'][] = (float)$row['Desember'];
			}

			$this->load->view('admin/header', $data);
			$this->load->view('admin/dashboard', $data);
			$this->load->view('admin/footer');

			if ($this->input->post('btnnonaktif')) {
				$acts = $this->admin->ppdb_status('tutup', date('Y-m-d H:i:s'));
				redirect('panel_admin');
			}

			if ($this->input->post('btnaktif')) {
				$acts = $this->admin->ppdb_status('buka', date('Y-m-d H:i:s'));
				redirect('panel_admin');
			}
		}
	}

	public function log_in()
	{
		$sess = $this->session->userdata('id_admin');

		if ($sess != NULL) {
			$this->load->view('404_content');
		} else {
			$this->load->view('admin/login/header_login');
			$this->load->view('admin/login/login');
			$this->load->view('admin/login/footer');

			if ($this->input->post('btnlogin')) {
				$send = array(
					'username'	=> $this->input->post('username'),
					'password'	=> $this->input->post('password')
				);
				$auth	= $this->admin->auth($send);

				if ($auth['sum'] == 0) {
					$this->session->set_flashdata('msg', $this->err->wrong_admin_auth($sess));
					redirect('panel_admin/log_in');
				} else {
					$this->session->set_userdata('administrator', $auth['res']->level);
					$this->session->set_userdata('id_admin', $auth['res']->id_admin);
					redirect('panel_admin');
				}
			}
		}
	}

	public function profile()
	{
		$sess = $this->session->userdata('id_admin');
		if ($sess != NULL) {
			$data = array(
				'user'		=> $this->admin->base('bio', $sess),
				'web_ppdb'	=> $this->admin->base('status-ppdb'),
				'judul_web'	=> "PROFILE",
				'v_thn'		=> date('Y')
			);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/profile', $data);
			$this->load->view('admin/footer');

			if ($this->input->post('btnupdate')) {
				$update = array(
					'nama_lengkap'	=> $this->input->post('nama_lengkap'),
					'username'		=> $this->input->post('username'),
					'password'		=> $this->input->post('password')
				);
				$act = $this->admin->act_edit_bio($update, $sess);
				redirect('panel_admin/profile');
			}
		} else {
			$this->load->view('404_content');
		}
	}

	public function edit_sisswa($no_pendaftaran)
	{
		$sess = $this->session->userdata('id_admin');
		if ($sess == NULL) {
			$this->load->view('404_content');
		} else {
			$data = array(
				'user'		=> $this->admin->base('bio', $sess),
					'web_ppdb'	=> $this->admin->base('status-ppdb'),
					'judul_web'	=> "PROFILE",
					'v_thn'		=> date('Y'),
					'detail'	=> $this->Model_data->get_student_data($no_pendaftaran)
				);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/edit', $data);
			$this->load->view('admin/footer');

			if ($this->input->post('btnupdate')) {
				$data = array(
					'nama'		=> $this->input->post('nama'),
					'alamat'	=> $this->input->post('alamat'),
					'kota'		=> $this->input->post('kota'),
					'tanggal'	=> $this->input->post('tanggal')
				);

				$act = $this->Model_data->edit_siswa($data, $no_pendaftaran);
				redirect('panel_admin/edit_siswa/' . $no_pendaftaran);
			}
		}
	}

	public function ubah_siswa($aksi = '', $id = '')
	{
		$sess = $this->session->userdata('id_admin');
		if ($sess == NULL) {
			$this->load->view('404_content');
		} else {
			$data = array(
				'user'		=> $this->admin->base('bio', $sess),
				'web_ppdb'	=> $this->admin->base('status-ppdb'),
				'judul_web'	=> "PROFILE",
				'v_thn'		=> date('Y'),
				'detail'	=> $this->Model_data->get_student_data($id)
			);

			$this->load->view('admin/header', $data);
			$this->load->view('admin/ubah', $data);
			$this->load->view('admin/footer');

			if ($aksi == 'hapus') {
				$this->Model_data->hapus_siswa($id);
				redirect('panel_admin');
			} elseif ($aksi == 'verifikasi') {
				$this->Model_data->verifikasi_siswa($id);
				redirect('panel_admin');
			} elseif ($this->input->post('btnupdate')) {
				$data = array(
					'nama'		=> $this->input->post('nama'),
					'alamat'	=> $this->input->post('alamat'),
					'kota'		=> $this->input->post('kota'),
					'tanggal'	=> $this->input->post('tanggal')
				);

				$act = $this->Model_data->edit_siswa($data, $id);
				redirect('panel_admin/ubah_siswa/' . $id);
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('panel_admin');
	}
}
?>