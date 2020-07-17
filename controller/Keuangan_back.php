<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            admin_redirect('login');
        }

        if ($this->Customer || $this->Supplier) {
            redirect('/');
        }

        $this->load->library('form_validation');
        $this->load->admin_model('keuangan_model');
        $this->load->admin_model('db_model');
    }

    public function index()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $saldo_bca_anik = $this->keuangan_model->get_nominal_bank(8);
        $saldo_mandiri_anik = $this->keuangan_model->get_nominal_bank(7);
        $saldo_bca_ali = $this->keuangan_model->get_nominal_bank(3);
        $payment = $this->keuangan_model->get_payment_data();
        $parchase = $this->keuangan_model->get_purchase_data();
        $product = $this->keuangan_model->get_product_data();

        $this->keuangan_model->update_data($saldo_bca_anik, '1-10004');
        $this->keuangan_model->update_data($saldo_mandiri_anik, '1-10006');
        $this->keuangan_model->update_data($saldo_bca_ali, '1-10005');
        $this->keuangan_model->update_data($payment, '1-10001');  
        $this->keuangan_model->update_data($parchase, '1-10002');   
        $this->keuangan_model->update_data($product, '1-10200');

        $this->data['accounts']=$this->keuangan_model->get_accounts();

        //print_r($saldo_bca_anik);
        $this->page_construct('keuangan/akun', $meta, $this->data);

    }

    public function kas_masuk(){
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
        //print_r($this->db->last_query());

        $this->data['accounts']=$this->keuangan_model->get_accounts();
        $this->page_construct('keuangan/kas_masuk2', $meta, $this->data);
    }

    public function getKas_masuk()
    {
        $delete_link = "<a href='#' class='po' title='<b>" . 'Delete' . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('keuangan/delete_kas_masuk/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . 'Delete' . "</a>";
        $edit_link = anchor('admin/keuangan/edit_kas_masuk/$1', '<i class="fa fa-edit"></i> ' . 'Edit Kas Masuk', 'class="sledit"');
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">

                        <li>'.$edit_link.'</li>
                        <li>'.$delete_link.'</li>
                        
                    </ul>
                </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        $this->datatables->select('sma_custom_keuangan_kas_masuk.id, sma_custom_keuangan_kas_masuk.tgl, sma_custom_keuangan_nap.tan as tan_nap, sma_custom_keuangan_nap.nop as nop_nap, sma_custom_keuangan_kas_masuk.ket, sma_custom_keuangan_kas_masuk.quantity, sma_custom_keuangan_kas_masuk.harga, sma_custom_keuangan_kas_masuk.jumlah, sma_custom_keuangan_kas_masuk.bkm, sma_custom_keuangan_kas_masuk.penyetor, sma_custom_keuangan_kas_masuk.bank')
               ->from('sma_custom_keuangan_kas_masuk')
               ->join('sma_custom_keuangan_nap', 'sma_custom_keuangan_kas_masuk.nap_id = sma_custom_keuangan_nap.id');

        //$this->datatables->select('sma_custom_keuangan_nap.*, mts_d, mts_k')
            //->join('(SELECT nap_id, SUM(jumlah) AS mts_d FROM sma_custom_keuangan_kas_keluar GROUP BY nap_id) as B', 'sma_custom_keuangan_nap.id = B.nap_id', 'left')
            //->join('(SELECT nap_id, SUM(jumlah) AS mts_k FROM sma_custom_keuangan_kas_masuk GROUP BY nap_id) as C', 'sma_custom_keuangan_nap.id = C.nap_id', 'left');

        $this->datatables->add_column("Actions", $action, "sma_custom_keuangan_kas_masuk.id");
        echo $this->datatables->generate();
    }

    public function add_kas_masuk(){
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $this->form_validation->set_rules('nap','nap','integer');
        $this->form_validation->set_rules('harga','harga','numeric');
        $this->form_validation->set_rules('jumlah','jumlah','numeric');
        $this->form_validation->set_rules('q','q','numeric');

        if($this->form_validation->run() == true){
            $post = $this->input->post();

            $data = array(
                                'nap_id' => $post['nap'],
                                'harga' => $post['harga'],
                                'jumlah' => $post['jumlah'],
                                'bkm' => $post['bkm'],
                                'penyetor' => $post['penyetor'],
                                'quantity' => $post['q'],
                                'bank' => $post['bank'],
                                'ket' => $post['ket'],
                                'tgl' => $post['tgl']
                            );

            if($this->db->insert('sma_custom_keuangan_kas_masuk', $data)){
                $this->session->set_flashdata('message', 'Berhasil ditambahkan');
                redirect('admin/keuangan/kas_masuk');
            }else{
                $this->session->set_flashdata('error', 'Gagal ditambahkan periksa kembali informasi yang anda berikan');
                redirect('admin/keuangan/kas_masuk');
            }

        }else{
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['sales'] = $this->db_model->getLatestSales();
            $this->data['quotes'] = $this->db_model->getLastestQuotes();
            $this->data['purchases'] = $this->db_model->getLatestPurchases();
            $this->data['transfers'] = $this->db_model->getLatestTransfers();
            $this->data['customers'] = $this->db_model->getLatestCustomers();
            $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
            $this->data['chatData'] = $this->db_model->getChartData();
            $this->data['stock'] = $this->db_model->getStockValue();
            $this->data['bs'] = $this->db_model->getBestSeller();
            $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
            $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
    
            $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
            //print_r($this->db->last_query());
    
            $this->data['accounts']=$this->keuangan_model->get_accounts();
            $this->data['naps']=$this->keuangan_model->get_nap();
    
            $this->page_construct('keuangan/add_kas_masuk', $meta, $this->data);
        }


    }

    public function edit_kas_masuk($id){
        $this->form_validation->set_rules('harga','harga','numeric');
        $this->form_validation->set_rules('jumlah','jumlah','numeric');
        $this->form_validation->set_rules('q','q','numeric');

        if($this->form_validation->run() == true){
            $post = $this->input->post();

            $data = array(
                                'harga' => $post['harga'],
                                'jumlah' => $post['jumlah'],
                                'bkm' => $post['bkm'],
                                'penyetor' => $post['penyetor'],
                                'quantity' => $post['q'],
                                'bank' => $post['bank'],
                                'ket' => $post['ket']
                            );

            if($this->db->update('sma_custom_keuangan_kas_masuk', $data, array('id'=>$id))){
                $this->session->set_flashdata('message', 'Berhasil diperbaruhi');
                redirect('admin/keuangan/edit_kas_masuk/'.$id);
            }else{
                $this->session->set_flashdata('error', 'Gagal diperbaruhi periksa kembali informasi yang anda berikan');
                redirect('admin/keuangan/edit_kas_masuk/'.$id);
            }

        }else{
            $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
            $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
    
            $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
            //print_r($this->db->last_query());
    
            $this->data['accounts']=$this->keuangan_model->get_accounts();
            
            $this->data['kas_masuk']=$this->keuangan_model->get_kas_masuk_by_id($id);
    
            //print_r($this->data['kas_masuk']);
            $this->page_construct('keuangan/edit_kas_masuk', $meta, $this->data);
        }
    }

    public function kas_keluar(){
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
        //print_r($this->db->last_query());

        $this->data['accounts']=$this->keuangan_model->get_accounts();
        $this->page_construct('keuangan/kas_keluar', $meta, $this->data);
    }

    public function getKas_keluar()
    {
        $delete_link = "<a href='#' class='po' title='<b>" . 'Delete' . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('keuangan/delete_kas_keluar/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . 'Delete' . "</a>";
        $edit_link = anchor('admin/keuangan/edit_kas_keluar/$1', '<i class="fa fa-edit"></i> ' . 'Edit Kas Keluar', 'class="sledit"');
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">

                        <li>'.$edit_link.'</li>
                        <li>'.$delete_link.'</li>
                        
                    </ul>
                </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        $this->datatables->select('sma_custom_keuangan_kas_keluar.id, sma_custom_keuangan_kas_keluar.tgl, sma_custom_keuangan_nap.tan as tan_nap, sma_custom_keuangan_nap.nop as nop_nap, sma_custom_keuangan_kas_keluar.ket, sma_custom_keuangan_kas_keluar.quantity, sma_custom_keuangan_kas_keluar.harga, sma_custom_keuangan_kas_keluar.jumlah, sma_custom_keuangan_kas_keluar.bkk, sma_custom_keuangan_kas_keluar.penyetor, sma_custom_keuangan_kas_keluar.bank')
                ->from('sma_custom_keuangan_kas_keluar')
                ->join('sma_custom_keuangan_nap', 'sma_custom_keuangan_kas_keluar.nap_id = sma_custom_keuangan_nap.id');

        $this->datatables->add_column("Actions", $action, "sma_custom_keuangan_kas_keluar.id");
        echo $this->datatables->generate();
    }

    public function add_kas_keluar(){
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $this->form_validation->set_rules('nap','nap','integer');
        $this->form_validation->set_rules('harga','harga','numeric');
        $this->form_validation->set_rules('jumlah','jumlah','numeric');
        $this->form_validation->set_rules('q','q','numeric');

        if($this->form_validation->run() == true){
            $post = $this->input->post();

            $data = array(
                                'nap_id' => $post['nap'],
                                'harga' => $post['harga'],
                                'jumlah' => $post['jumlah'],
                                'bkk' => $post['bkk'],
                                'penyetor' => $post['penyetor'],
                                'quantity' => $post['q'],
                                'bank' => $post['bank'],
                                'ket' => $post['ket']
                            );

            if($this->db->insert('sma_custom_keuangan_kas_keluar', $data)){
                $this->session->set_flashdata('message', 'Berhasil ditambahkan');
                redirect('admin/keuangan/kas_keluar');
            }else{
                $this->session->set_flashdata('error', 'Gagal ditambahkan periksa kembali informasi yang anda berikan');
                redirect('admin/keuangan/kas_keluar');
            }

        }else{
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['sales'] = $this->db_model->getLatestSales();
            $this->data['quotes'] = $this->db_model->getLastestQuotes();
            $this->data['purchases'] = $this->db_model->getLatestPurchases();
            $this->data['transfers'] = $this->db_model->getLatestTransfers();
            $this->data['customers'] = $this->db_model->getLatestCustomers();
            $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
            $this->data['chatData'] = $this->db_model->getChartData();
            $this->data['stock'] = $this->db_model->getStockValue();
            $this->data['bs'] = $this->db_model->getBestSeller();
            $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
            $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
    
            $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
            //print_r($this->db->last_query());
    
            $this->data['accounts']=$this->keuangan_model->get_accounts();
            $this->data['naps']=$this->keuangan_model->get_nap();
    
            $this->page_construct('keuangan/add_kas_keluar', $meta, $this->data);
        }


    }

    public function edit_kas_keluar($id){
        $this->form_validation->set_rules('harga','harga','numeric');
        $this->form_validation->set_rules('jumlah','jumlah','numeric');
        $this->form_validation->set_rules('q','q','numeric');

        if($this->form_validation->run() == true){
            $post = $this->input->post();

            $data = array(
                                'harga' => $post['harga'],
                                'jumlah' => $post['jumlah'],
                                'bkk' => $post['bkk'],
                                'penyetor' => $post['penyetor'],
                                'quantity' => $post['q'],
                                'bank' => $post['bank'],
                                'ket' => $post['ket']
                            );

            if($this->db->update('sma_custom_keuangan_kas_keluar', $data, array('id'=>$id))){
                $this->session->set_flashdata('message', 'Berhasil diperbaruhi');
                redirect('admin/keuangan/edit_kas_keluar/'.$id);
            }else{
                $this->session->set_flashdata('error', 'Gagal diperbaruhi periksa kembali informasi yang anda berikan');
                redirect('admin/keuangan/edit_kas_keluar/'.$id);
            }

        }else{
            $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
            $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
    
            $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
            //print_r($this->db->last_query());
    
            $this->data['accounts']=$this->keuangan_model->get_accounts();
            
            $this->data['kas_keluar']=$this->keuangan_model->get_kas_keluar_by_id($id);
    
            //print_r($this->data['kas_masuk']);
            $this->page_construct('keuangan/edit_kas_keluar', $meta, $this->data);
        }
    }

    public function rms(){
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $from = null;
        $to = null;
        if($this->input->get('from')){
            $from = $this->input->get('from').' '.'00:00:00';
        }
        if($this->input->get('from')){
             $to = $this->input->get('to').' '.'23:59:59';
        }
        $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();        
        $this->data['a100000']=$this->keuangan_model->get_nap_by_nop('10,00,00',$from,$to);
        $this->data['a101000']=$this->keuangan_model->get_nap_by_nop('10,10,00',$from,$to);
        $this->data['a1020']=$this->keuangan_model->get_nap_by_nop('10,20',$from,$to);
        $this->data['a1021']=$this->keuangan_model->get_nap_by_nop('10,21',$from,$to);
        $this->data['a103']=$this->keuangan_model->get_nap_by_nop('10,3',$from,$to);
        $this->data['a104']=$this->keuangan_model->get_nap_by_nop('10,40,00',$from,$to);
        $this->data['a105']=$this->keuangan_model->get_nap_by_nop('10,50,00',$from,$to);
        $this->data['a106']=$this->keuangan_model->get_nap_by_nop('10,6',$from,$to);
        $this->data['a107']=$this->keuangan_model->get_nap_by_nop('10,70,00',$from,$to);
        $this->data['a108']=$this->keuangan_model->get_nap_by_nop('10,80,00',$from,$to);
        $this->data['a109']=$this->keuangan_model->get_nap_by_nop('10,90,00',$from,$to);
        $this->data['a11000']=$this->keuangan_model->get_nap_by_nop('11,00,0',$from,$to);
        $this->data['a11100']=$this->keuangan_model->get_nap_by_nop('11,10,',$from,$to);
        $this->data['a11110']=$this->keuangan_model->get_nap_by_nop('11,11,01',$from,$to);
        $this->data['a11120']=$this->keuangan_model->get_nap_by_nop('11,12,00',$from,$to);
        $this->data['a11200']=$this->keuangan_model->get_nap_by_nop('11,20,0',$from,$to);
        $this->data['a20']=$this->keuangan_model->get_nap_by_nop('20,',$from,$to);
        $this->data['a30']=$this->keuangan_model->get_nap_by_nop('30,',$from,$to);
        $this->data['a40']=$this->keuangan_model->get_nap_by_nop('40,',$from,$to);
        $this->data['a50']=$this->keuangan_model->get_nap_by_nop('50,',$from,$to);

        //print_r($this->db->last_query());
        $this->page_construct('keuangan/rms', $meta, $this->data);
    }

    public function akv(){
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $from = null;
        $to = null;
        if($this->input->get('from')){
            $from = $this->input->get('from').' '.'00:00:00';
        }
        if($this->input->get('from')){
             $to = $this->input->get('to').' '.'23:59:59';
        }

        $this->data['a4010'] = $this->keuangan_model->get_nap_by_nop('40,10', $from, $to);
        $this->data['a4011'] = $this->keuangan_model->get_nap_by_nop('40,11', $from, $to);
        //$this->data['a401001'] = $this->keuangan_model->get_nap_by_nop('40,10,01');
        $this->data['a4001'] = $this->keuangan_model->get_nap_by_nop('40,01', $from, $to);        
        $this->data['a4012'] = $this->keuangan_model->get_nap_by_nop('40,12', $from, $to);
        $this->data['a4020'] = $this->keuangan_model->get_nap_by_nop('40,20', $from, $to);
        $this->data['a4099'] = $this->keuangan_model->get_nap_by_nop('40,99', $from, $to);
        $this->data['a5000'] = $this->keuangan_model->get_nap_by_nop('50,00', $from, $to);
        $this->data['a6'] = $this->keuangan_model->get_nap_by_nop('6', $from, $to);
        $this->data['total'] =0;
        //print_r($this->db->last_query());
        $this->page_construct('keuangan/akv', $meta, $this->data);
    }

    public function delete_kas_keluar($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->db->delete('sma_custom_keuangan_kas_keluar', array('id'=>$id)) ) {
            if ($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => 'Dihapus'));
            }
            $this->session->set_flashdata('message', 'Dihapus');
            admin_redirect('welcome');
        }else{
            $this->sma->send_json(array('error' => 1, 'msg' => $this->db->last_query()));
        }
    }

    public function delete_kas_masuk($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->db->delete('sma_custom_keuangan_kas_masuk', array('id'=>$id)) ) {
            if ($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => 'Dihapus'));
            }
            $this->session->set_flashdata('message', 'Dihapus');
            admin_redirect('welcome');
        }else{
            $this->sma->send_json(array('error' => 1, 'msg' => $this->db->last_query()));
        }
    }

    public function rkp(){
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $from = null;
        $to = null;
        if($this->input->get('from')){
            $from = $this->input->get('from').' '.'00:00:00';
        }
        if($this->input->get('from')){
             $to = $this->input->get('to').' '.'23:59:59';
        }
        $this->data['a1000'] = $this->keuangan_model->get_nap_by_nop('10,00', $from, $to);
        $this->data['a1010'] = $this->keuangan_model->get_nap_by_nop('10,10', $from, $to);
        $this->data['a102'] = $this->keuangan_model->get_nap_by_nop('10,2', $from, $to);
        $this->data['a103'] = $this->keuangan_model->get_nap_by_nop('10,3', $from, $to); 
        $this->data['a104'] = $this->keuangan_model->get_nap_by_nop('10,40,00', $from, $to);
        $this->data['a105'] = $this->keuangan_model->get_nap_by_nop('10,50,00', $from, $to);

        $this->data['a103'] = (object) array_merge((array)$this->data['a103'], (array)$this->data['a104']);
        $this->data['a103'] = (object) array_merge((array)$this->data['a103'], (array)$this->data['a105']);

        $this->data['a106'] = $this->keuangan_model->get_nap_by_nop('10,6', $from, $to);
        $this->data['a107'] = $this->keuangan_model->get_nap_by_nop('10,70,00', $from, $to);
        $this->data['a108'] = $this->keuangan_model->get_nap_by_nop('10,80,00', $from, $to);
        $this->data['a109'] = $this->keuangan_model->get_nap_by_nop('10,90,00', $from, $to);

        $this->data['a107'] = (object) array_merge((array)$this->data['a107'], (array)$this->data['a108']);
        $this->data['a107'] = (object) array_merge((array)$this->data['a107'], (array)$this->data['a109']);

        $this->data['a11000'] = $this->keuangan_model->get_nap_by_nop('11,00,0', $from, $to);
        $this->data['a1110'] = $this->keuangan_model->get_nap_by_nop('11,10,', $from, $to);
        $this->data['a111200'] = $this->keuangan_model->get_nap_by_nop('11,12,00', $from, $to);

        $this->data['a1120'] = $this->keuangan_model->get_nap_by_nop('11,20,', $from, $to);
        $this->data['a1130'] = $this->keuangan_model->get_nap_by_nop('11,30,', $from, $to);
        $this->data['a1140'] = $this->keuangan_model->get_nap_by_nop('11,40,', $from, $to);
        $this->data['a1150'] = $this->keuangan_model->get_nap_by_nop('11,50,', $from, $to);
        $this->data['a1160'] = $this->keuangan_model->get_nap_by_nop('11,60,', $from, $to);

        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1130']);
        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1140']);
        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1150']);
        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1160']);

        $this->data['a20'] = $this->keuangan_model->get_nap_by_nop('20,', $from, $to);
        $this->data['a30000'] = $this->keuangan_model->get_nap_by_nop('30,00,0', $from, $to);
        $this->data['a40'] = $this->keuangan_model->get_nap_by_nop('40,', $from, $to);
        $this->data['a50'] = $this->keuangan_model->get_nap_by_nop('50,', $from, $to);
        //print_r($this->data['a103']);
        //print("<pre>".print_r($this->data['a103'],true)."</pre>");
        $this->page_construct('keuangan/rkp', $meta, $this->data);
    }

    public function nrc(){
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $from = null;
        $to = null;
        if($this->input->get('from')){
            $from = $this->input->get('from').' '.'00:00:00';
        }
        if($this->input->get('from')){
             $to = $this->input->get('to').' '.'23:59:59';
        }
        $this->data['a1000'] = $this->keuangan_model->get_nap_by_nop('10,00', $from, $to);
        $this->data['a101000'] = $this->keuangan_model->get_nap_by_nop('10,10,00', $from, $to);

        $this->data['a102'] = $this->keuangan_model->get_nap_by_nop('10,2', $from, $to);
        $this->data['a103'] = $this->keuangan_model->get_nap_by_nop('10,3', $from, $to); 
        $this->data['harta_lancar'] = (object) array_merge((array)$this->data['a102'], (array)$this->data['a103']);

        $this->data['a106'] = $this->keuangan_model->get_nap_by_nop('10,6', $from, $to);
        $this->data['a107'] = $this->keuangan_model->get_nap_by_nop('10,70,00', $from, $to);
        $this->data['a108'] = $this->keuangan_model->get_nap_by_nop('10,80,00', $from, $to);
        $this->data['a109'] = $this->keuangan_model->get_nap_by_nop('10,90,00', $from, $to);
        $this->data['harta_tidak_lancar'] = (object) array_merge((array)$this->data['a106'], (array)$this->data['a107'], (array)$this->data['a108'], (array)$this->data['a109']);


        $this->data['a1010'] = $this->keuangan_model->get_nap_by_nop('10,10', $from, $to);
        
        $this->data['a104'] = $this->keuangan_model->get_nap_by_nop('10,40,00', $from, $to);
        $this->data['a105'] = $this->keuangan_model->get_nap_by_nop('10,50,00', $from, $to);

        $this->data['a103'] = (object) array_merge((array)$this->data['a103'], (array)$this->data['a104']);
        $this->data['a103'] = (object) array_merge((array)$this->data['a103'], (array)$this->data['a105']);



        $this->data['a107'] = (object) array_merge((array)$this->data['a107'], (array)$this->data['a108']);
        $this->data['a107'] = (object) array_merge((array)$this->data['a107'], (array)$this->data['a109']);

        $this->data['a11000'] = $this->keuangan_model->get_nap_by_nop('11,00,0', $from, $to);
        
        $this->data['a111200'] = $this->keuangan_model->get_nap_by_nop('11,12,00', $from, $to);

        $this->data['a1120'] = $this->keuangan_model->get_nap_by_nop('11,20,', $from, $to);
        $this->data['a1130'] = $this->keuangan_model->get_nap_by_nop('11,30,', $from, $to);
        $this->data['a1140'] = $this->keuangan_model->get_nap_by_nop('11,40,', $from, $to);
        $this->data['a1150'] = $this->keuangan_model->get_nap_by_nop('11,50,', $from, $to);
        $this->data['a1160'] = $this->keuangan_model->get_nap_by_nop('11,60,', $from, $to);

        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1130']);
        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1140']);
        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1150']);
        $this->data['a1120'] = (object) array_merge((array)$this->data['a1120'], (array)$this->data['a1160']);

        $this->data['a20'] = $this->keuangan_model->get_nap_by_nop('20,', $from, $to);
        $this->data['a30000'] = $this->keuangan_model->get_nap_by_nop('30,00,0', $from, $to);
        $this->data['a40'] = $this->keuangan_model->get_nap_by_nop('40,', $from, $to);
        $this->data['a50'] = $this->keuangan_model->get_nap_by_nop('50,', $from, $to);
        //print_r($this->data['a103']);
        //print("<pre>".print_r($this->data['a103'],true)."</pre>");
        $this->page_construct('keuangan/nrc', $meta, $this->data);
    }


    public function add_account(){

        $this->form_validation->set_rules('code','code','required');
        $this->form_validation->set_rules('name','name','required');

        if($this->form_validation->run() == true){
            $post = $this->input->post();
            $tmp_parent = (int)$post['sub_account_parent'];
            if($tmp_parent == 0){
                $tmp_parent = $post['account_parent'];
            }

            $data = array(
                        'parent_id'=>$tmp_parent,
                        'name' =>$post['name'],
                        'account_number' =>$post['code']
                    );
            if($this->db->insert('sma_custom_keuangan_account', $data)){
                $this->session->set_flashdata('message', 'Berhasil ditambahkan');
                redirect(admin_url().'keuangan/add_account');
            }else{
                 $this->session->set_flashdata('error', 'Gagal ditambahkan');
                redirect(admin_url().'keuangan/add_account');
            }
        }else{
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
            $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
    
            $from = null;
            $to = null;
            if($this->input->get('from')){
                $from = $this->input->get('from').' '.'00:00:00';
            }
            if($this->input->get('from')){
                 $to = $this->input->get('to').' '.'23:59:59';
            }
            $this->data['parent_accounts'] = $this->keuangan_model->get_all_parent_accounts();
            $this->data['harta'] = $this->keuangan_model->get_all_sub_parent_accounts(1);
            $this->data['hutang'] = $this->keuangan_model->get_all_sub_parent_accounts(2);
            $this->data['modal'] = $this->keuangan_model->get_all_sub_parent_accounts(3);
            $this->data['pendapatan'] = $this->keuangan_model->get_all_sub_parent_accounts(4);
            $this->data['beban'] = $this->keuangan_model->get_all_sub_parent_accounts(5);
            $this->data['beban_'] = $this->keuangan_model->get_all_sub_parent_accounts(6);
            //print_r($this->data['parent_accounts']);
            //print("<pre>".print_r($this->data['a103'],true)."</pre>");
            $this->page_construct('keuangan/add_accounts', $meta, $this->data);
        }


    }

    public function add_transaction(){
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $this->form_validation->set_rules('debet','debet','required');
        $this->form_validation->set_rules('kredit','kredit','required');
        $this->form_validation->set_rules('harga','harga','numeric');
        $this->form_validation->set_rules('total','total','numeric');
        $this->form_validation->set_rules('q','q','numeric');

        if($this->form_validation->run() == true){
            $post = $this->input->post();

            $data = array(
                                'debet' => $post['debet'],
                                'kredit' => $post['kredit'],
                                'date' => $post['date'],
                                'q' => $post['q'],
                                'total' => $post['total'],
                                'penyetor' => $post['penyetor'],
                                'price_at' => $post['harga'],
                                'bank' => $post['bank'],
                                'note' => $post['ket'],
                                'bkm' => $post['bkm']
                            );

            if($this->db->insert('sma_custom_keuangan_transaction', $data)){
                $this->session->set_flashdata('message', 'Berhasil ditambahkan');
                redirect('admin/keuangan/kas_masuk');
            }else{
                $this->session->set_flashdata('error', 'Gagal ditambahkan periksa kembali informasi yang anda berikan');
                redirect('admin/keuangan/kas_masuk');
            }

        }else{
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['sales'] = $this->db_model->getLatestSales();
            $this->data['quotes'] = $this->db_model->getLastestQuotes();
            $this->data['purchases'] = $this->db_model->getLatestPurchases();
            $this->data['transfers'] = $this->db_model->getLatestTransfers();
            $this->data['customers'] = $this->db_model->getLatestCustomers();
            $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
            $this->data['chatData'] = $this->db_model->getChartData();
            $this->data['stock'] = $this->db_model->getStockValue();
            $this->data['bs'] = $this->db_model->getBestSeller();
            $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
            $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
    
            $this->data['cashflows'] = $this->keuangan_model->get_kas_masuk();
            //print_r($this->db->last_query());
    
            $this->data['accounts']=$this->keuangan_model->get_accounts();
            $this->data['naps']=$this->keuangan_model->get_nap();
    
            $this->page_construct('keuangan/add_transaction', $meta, $this->data);
        }


    }



}
