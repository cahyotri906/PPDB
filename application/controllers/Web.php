<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('SiswaModel');
    }

    public function index()
    {
        $data['tbl_siswa'] = $this->db->get('tbl_siswa')->result();
        $data['web_ppdb'] = $this->web->web_utama();
        $this->load->view('web/index', $data);
        $data['tbl_siswa'] = $this->db->get('tbl_siswa')->result();
        $sess = $this->session->userdata('id_admin');
    }

    public function idbaru($value = '')
    {
        echo $this->web->pendaftaran('id_baru');
    }

    public function pendaftaran()
    {
        $data = array(
            'id_daftar'         => $this->web->pendaftaran('id_baru'),
            'web_ppdb'          => $this->web->pendaftaran('status_ppdb'),
            'v_pdd'             => $this->web->pendaftaran('v_pdd'),
            'v_penghasilan'     => $this->web->pendaftaran('v_penghasilan'),
            'v_pekerjaan_ayah'  => $this->web->pendaftaran('v_pekerjaan_ayah'),
            'v_komp'            => $this->web->pendaftaran('v_komp'),
            'v_pekerjaan_ibu'   => $this->web->pendaftaran('v_pekerjaan_ibu'),
            'v_pekerjaan_wali'  => $this->web->pendaftaran('v_pekerjaan_wali')
        );

        if ($data['web_ppdb']->status_ppdb == 'tutup') {
            redirect('404');
        }

        $this->load->view('web/pendaftaran', $data);

        if (isset($_POST['btndaftar'])) {
            $nisn = $this->input->post('nisn');

            // Periksa apakah NISN sudah ada di database
            $existingSiswa = $this->db->where('nisn', $nisn)->get('tbl_siswa')->row();
            if ($existingSiswa) {
                redirect('error_not_found/405');
            }

            $acts = $this->web->pendaftaran('daftar', $this->input);

            $this->session->set_userdata('no_pendaftaran', $this->input->post('nis'));
            redirect('panel_siswa');
        }
    }

    public function logcs()
    {
        $data['tbl_siswa'] = $this->db->get('tbl_siswa')->result();
        $data['web_ppdb'] = $this->web->pendaftaran('status_ppdb');
        if ($data['web_ppdb']->status_ppdb == 'tutup') {
            redirect('404');
        }

        if ($this->session->userdata('no_pendaftaran') != NULL) {
            redirect('panel_siswa');
        } else {
            $this->load->view('web/index', $data);

            if (isset($_POST['btnlogin'])) {
                $send = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password')
                );
                $auth = $this->web->auth('cek-masuk', $send);

                if ($auth['sum'] == 0) {
                    $this->session->set_flashdata('msg', $this->err->wrong_auth());
                    redirect('logcs');
                } else {
                    $this->session->set_userdata('no_pendaftaran', $auth['res']->no_pendaftaran);
                    redirect('panel_siswa');
                }
            }
        }
    }

    public function cari()
    {
        $data['siswa'] = $this->SiswaModel->view();
        $this->load->view('web/cari', $data);
    }

public function error_not_found($error_code = '')
{
    if ($error_code === '404') {
        $this->load->view('404_content');
    } else {
        $this->load->view('405_content');
    }
}

    public function search()
    {
        $keyword = $this->input->post('keyword');
        $data['siswa'] = $this->SiswaModel->get_keyword($keyword);
        $this->load->view('web/cari', $data);
    }
}