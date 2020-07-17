<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan_model extends CI_Model {
	public function __construct()
    {
        parent::__construct();
    }

    public function get_accounts(){
        $this->db->select('*')
            ->from('sma_custom_keuangan_account')
            ->where('id !=',1)
            ->where('id !=',2)
            ->where('id !=',3)
            ->where('id !=',4)
            ->where('id !=',5)
            ->where('id !=',6);
    	$data=$this->db->order_by('account_number', 'asc')->get()->result();
    	return $data;
    }

    public function get_nominal_bank($id_akun){
    	$data_kredit=$this->db->get_where('sma_custom_bank_account_mutations', array('bank_account_id'=>$id_akun, 'type'=>'kredit'))->result();
    	$data_debit=$this->db->get_where('sma_custom_bank_account_mutations', array('bank_account_id'=>$id_akun, 'type'=>'debit'))->result();

    	$kredit=0;
    	$debit=0;

    	foreach($data_kredit as $kre){
    		$kredit=$kredit + $kre->nominal;
    	}

    	foreach($data_debit as $deb){
    		$debit=$debit + $deb->nominal;
    	}

    	return $kredit - $debit;

    }

    public function update_data($data,$kode){
    	 $this->db->update('sma_custom_keuangan_akun', array('awal_debet'=>$data), array('kode_rekening'=>$kode));
    }

    public function get_payment_data(){
        $this->db->select('*')
            ->from('sma_payments')
            ->where('sale_id !=', '');
        $sale_data = $this->db->get()->result();

        $this->db->select('*')
            ->from('sma_payments')
            ->where('purchase_id !=', '');
        $purchase_data = $this->db->get()->result();

        $sale=0;
        $purchase=0;

        foreach ($sale_data as $key => $sale_val) {
            $sale=$sale + $sale_val->amount;
        }

        foreach ($purchase_data as $key => $purchase_val) {
            $purchase = $purchase + $purchase_val->amount;
        }

        return $sale - $purchase;

    }

    public function get_purchase_data(){
        $purchase_data = $this->db->get('sma_purchases')->result();
        $purchase=0;

        foreach ($purchase_data as $key => $value) {
            $purchase=$purchase + $value->total;
        }

        return $purchase;
    }

    public function get_product_data(){
        $products_data = $this->db->get('sma_products')->result();
        $products=0;

        foreach ($products_data as $key => $value) {
            $price_tmp = $value->quantity * $value->price;
            $products=$products + $price_tmp;
        }

        return $products;
    }

    public function get_kas_masuk($tgl=null){
        if($tgl==null){
            $this->db->select('sma_custom_keuangan_kas_masuk.*, sma_custom_keuangan_nap.tan as tan_nap, sma_custom_keuangan_nap.nop as nop_nap, sma_custom_keuangan_nap.nap as nap_nap')
                ->from('sma_custom_keuangan_kas_masuk')
                ->join('sma_custom_keuangan_nap', 'sma_custom_keuangan_kas_masuk.nap_id = sma_custom_keuangan_nap.id');
            $data = $this->db->get()->result();
            if($data){
                return $data;
            }else{
                return false;
            }
        }else{

        }
    } 

    public function get_nap(){
        $data = $this->db->get('sma_custom_keuangan_nap')->result();
        if($data){
            return $data;
        }else{
            return false;
        }
    } 

    public function get_nap_by_nop($nop, $from=null, $to=null){
        $this->db->select('sma_custom_keuangan_nap.*, mts_d, mts_k', FALSE);
        $this->db->from('sma_custom_keuangan_nap');

        if($from !=null and $to !=null){
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_d FROM sma_custom_keuangan_kas_keluar where tgl between "'.$from.'" and "'.$to.'" GROUP BY nap_id) as B', 'sma_custom_keuangan_nap.id = B.nap_id', 'left', 'left');
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_k FROM sma_custom_keuangan_kas_masuk where tgl between "'.$from.'" and "'.$to.'" GROUP BY nap_id) as C', 'sma_custom_keuangan_nap.id = C.nap_id', 'left');
        }else{
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_d FROM sma_custom_keuangan_kas_keluar GROUP BY nap_id) as B', 'sma_custom_keuangan_nap.id = B.nap_id', 'left', 'left');
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_k FROM sma_custom_keuangan_kas_masuk GROUP BY nap_id) as C', 'sma_custom_keuangan_nap.id = C.nap_id', 'left');
        }

        $this->db->like('sma_custom_keuangan_nap.nop', $nop, 'after');

        //$this->db->group_by('sma_custom_keuangan_nap.id');
        
        $data = $this->db->get()->result();
        //print_r($this->db->last_query());

        return $data;
    } 

    public function get_nap_w_value($from=null, $to=null){
                //$this->datatables->select('sma_custom_keuangan_nap.*, mts_d, mts_k')
            //->join('(SELECT nap_id, SUM(jumlah) AS mts_d FROM sma_custom_keuangan_kas_keluar GROUP BY nap_id) as B', 'sma_custom_keuangan_nap.id = B.nap_id', 'left')
            //->join('(SELECT nap_id, SUM(jumlah) AS mts_k FROM sma_custom_keuangan_kas_masuk GROUP BY nap_id) as C', 'sma_custom_keuangan_nap.id = C.nap_id', 'left');
        $this->db->select('sma_custom_keuangan_nap.*, mts_d, mts_k', FALSE);
        $this->db->from('sma_custom_keuangan_nap');

        if($from !=null and $to !=null){
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_d FROM sma_custom_keuangan_kas_keluar where tgl between "'.$from.'" and "'.$to.'" GROUP BY nap_id) as B', 'sma_custom_keuangan_nap.id = B.nap_id', 'left', 'left');
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_k FROM sma_custom_keuangan_kas_masuk where tgl between "'.$from.'" and "'.$to.'" GROUP BY nap_id) as C', 'sma_custom_keuangan_nap.id = C.nap_id', 'left');
        }else{
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_d FROM sma_custom_keuangan_kas_keluar GROUP BY nap_id) as B', 'sma_custom_keuangan_nap.id = B.nap_id', 'left', 'left');
            $this->db->join('(SELECT nap_id, SUM(jumlah) AS mts_k FROM sma_custom_keuangan_kas_masuk GROUP BY nap_id) as C', 'sma_custom_keuangan_nap.id = C.nap_id', 'left');
        }

        //$this->db->group_by('sma_custom_keuangan_nap.id');
        
        $data = $this->db->get()->result();
        //print_r($this->db->last_query());

        return $data;
    } 

    public function get_kas_masuk_by_id($id){
        $this->db->select('A.*, B.tan, B.nop, B.nap')
            ->from('sma_custom_keuangan_kas_masuk as A')
            ->join('sma_custom_keuangan_nap as B', 'A.nap_id = B.id', 'left')
            ->where('A.id',$id);
        $data = $this->db->get()->row();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    public function get_kas_keluar_by_id($id){
        $this->db->select('A.*, B.tan, B.nop, B.nap')
            ->from('sma_custom_keuangan_kas_keluar as A')
            ->join('sma_custom_keuangan_nap as B', 'A.nap_id = B.id', 'left')
            ->where('A.id',$id);
        $data = $this->db->get()->row();
        if($data){
            return $data;
        }else{
            return false;
        }
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function get_all_parent_accounts(){
        $data=$this->db->get_where('sma_custom_keuangan_account', array('parent_id'=>0))->result();
        return $data;
    }

     public function get_all_sub_parent_accounts($id){
        $data=$this->db->get_where('sma_custom_keuangan_account', array('parent_id'=>$id))->result();
        return $data;
    }

    public function get_accounts_by_id($id){
        $data=$this->db->get_where('sma_custom_keuangan_account', array('id'=>$id))->row();
        return $data;
    }

    public function get_uniq_number($st, $nd, $rd){
         $tmp_number = str_pad($rd, 4, '0', STR_PAD_LEFT);
         $nmb = $st.','.$nd.','.$tmp_number;

         $check = $this->db->get_where('sma_custom_keuangan_account', array('account_number'=>$nmb))->row();

         if($check){
            $rd=(int)$rd+1;
            return $this->get_uniq_number($st, $nd, $rd);
         }else{
            return $nmb;
         }
    }

    public function get_nd_uniq_number($st, $nd){
         $tmp_number = str_pad($nd, 2, '0', STR_PAD_LEFT);
         $nmb = $st.','.$tmp_number.','.'0000';
         $check = $this->db->get_where('sma_custom_keuangan_account', array('account_number'=>$nmb))->row();

         if($check){
            return $this->get_nd_uniq_number($st, (int)$nd+1);
         }else{
            return $nmb;
         }
    }

    public function get_sub_account_from_parent($id){
        $this->db->select('*')
            ->order_by('account_number', 'asc')
            ->from('sma_custom_keuangan_account')
            ->where('parent_id', $id);
        $data = $this->db->get()->result();
        return $data;

    }

    public function get_sub_account(){
        $this->db->select('*')
            ->order_by('account_number', 'asc')
            ->from('sma_custom_keuangan_account')
            ->where('parent_id =1 or parent_id =2 or parent_id =3 or parent_id =4 or parent_id =5 or parent_id =6');
        $data = $this->db->get()->result();
        return $data;

    }

    public function get_child_account(){
        $this->db->select('*')
            ->order_by('account_number', 'asc')
            ->from('sma_custom_keuangan_account')
            ->where('parent_id !=1 and parent_id !=2 and parent_id !=3 and parent_id !=4 and parent_id !=5 and parent_id !=6')
            ->where('parent_id !=0');
        $data = $this->db->get()->result();
        return $data;

    }

    public function get_transactions($from=null, $to=null){
        $this->db->select('A.*, B.name as debet_name, C.name as kredit_name', FALSE);
         $this->db->order_by('date', 'desc');
        $this->db->from('sma_custom_keuangan_transaction as A')
            ->join('sma_custom_keuangan_account as B', 'B.account_number = A.debet')
            ->join('sma_custom_keuangan_account as C', 'C.account_number = A.kredit');

        if($from !=null and $to !=null){
            $this->db->where('date >=', $from);
            $this->db->where('date <=', $to);
        }
        
        $data = $this->db->get()->result();
        return $data;

    }

    public function mutasi_saldo_last_month($parent_id, $from=null, $to=null){
        $check = $this->db->get_where('sma_custom_keuangan_account', array('id'=>$parent_id))->row();
        $check_pid = $check->parent_id;

        $where = '';
        if($from != null and $to != null){
             $where = 'where date >= "'.$from.'" and date <= "'.$to.'" ';
        }

        if($check_pid==1 or $check_pid==5 or $check_pid ==6){
            $q = '(total_debet - total_kredit) as last_month';
        }else{
            $q = '(total_kredit - total_debet) as last_month';
        }
       
        $data = $this->db->query('SELECT A.*, '.$q.' FROM sma_custom_keuangan_account as A 
                    left JOIN 
                    (SELECT *, SUM(total) AS total_debet FROM sma_custom_keuangan_transaction AS D '.$where.' GROUP BY debet) 
                    AS B ON A.account_number = B.debet 
                    left JOIN 
                    (SELECT *, SUM(total) AS total_kredit FROM sma_custom_keuangan_transaction AS E '.$where.' GROUP BY kredit)  
                    AS C ON A.account_number = C.kredit 
                    WHERE A.parent_id ='.$parent_id.' GROUP BY A.id')->result();

        return $data;
    }

    public function mutasi_saldo($parent_id, $from=null, $to=null){
        $check = $this->db->get_where('sma_custom_keuangan_account', array('id'=>$parent_id))->row();
        $check_pid = $check->parent_id;

        $last_month_from = date('Y-m-d', strtotime('-1 month', strtotime($from))).' '.'00:00:00';
        $last_month_to = date('Y-m-t', strtotime('-1 month', strtotime($from))).' '.'23:59:59';

        $where_last_month = 'where date <= "'.$last_month_to.'" ';
        $debet_last_month = '(Select *, sum(total) AS total_debet_last FROM sma_custom_keuangan_transaction '.$where_last_month.' GROUP BY debet) as F ON A.account_number = F.debet';
        $kredit_last_month = '(Select *, sum(total) AS total_kredit_last FROM sma_custom_keuangan_transaction '.$where_last_month.' GROUP BY kredit) as G ON A.account_number = G.kredit';

        $where = '';
        if($from != null and $to != null){
             $where = 'where date >= "'.$from.'" and date <= "'.$to.'" ';
        }

        if($check_pid==1 or $check_pid==5 or $check_pid ==6){
            $q = '(ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) + ifnull(total_debet, 0) - ifnull(total_kredit, 0)) as saldo_akhir';
        }else{
            $q = '(ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) + ifnull(total_kredit, 0) - ifnull(total_debet,0)) as saldo_akhir';
        }
       
        $data = $this->db->query('SELECT A.*, '.$q.', total_debet, total_kredit, '.$saldo_akhir.' FROM sma_custom_keuangan_account as A 
                    left JOIN 
                    (SELECT *, SUM(total) AS total_debet FROM sma_custom_keuangan_transaction AS D '.$where.' GROUP BY debet) 
                    AS B ON A.account_number = B.debet 
                    left JOIN 
                    (SELECT *, SUM(total) AS total_kredit FROM sma_custom_keuangan_transaction AS E '.$where.' GROUP BY kredit)  
                    AS C ON A.account_number = C.kredit 
                    left join
                    '.$debet_last_month.' 
                    left join
                    '.$kredit_last_month.'
                    WHERE A.parent_id ='.$parent_id.' GROUP BY A.id')->result();
        //print_r($this->db->last_query().'</br>');

        return $data;
    }

    public function mutasi_saldo_by_id($id, $from=null, $to=null){
        $check = $this->db->get_where('sma_custom_keuangan_account', array('id'=>$id))->row();
        $check_pid = $check->account_number;
        $pid_ = (int)$check_pid[0];

        $last_month_from = date('Y-m-d', strtotime('-1 month', strtotime($from))).' '.'00:00:00';
        $last_month_to = date('Y-m-t', strtotime('-1 month', strtotime($from))).' '.'23:59:59';

        $where_last_month = 'where date <= "'.$last_month_to.'" ';
        $debet_last_month = '(Select *, sum(total) AS total_debet_last FROM sma_custom_keuangan_transaction '.$where_last_month.' GROUP BY debet) as F ON A.account_number = F.debet';
        $kredit_last_month = '(Select *, sum(total) AS total_kredit_last FROM sma_custom_keuangan_transaction '.$where_last_month.' GROUP BY kredit) as G ON A.account_number = G.kredit';

        $where = '';
        if($from != null and $to != null){
             $where = 'where date >= "'.$from.'" and date <= "'.$to.'" ';
        }

        if($pid_==1 or $pid_==5 or $pid_ ==6){
            $q = '(ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) + ifnull(total_debet, 0) - ifnull(total_kredit, 0)) as saldo_akhir';
        }else{
            $q = '(ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) + ifnull(total_kredit, 0) - ifnull(total_debet,0)) as saldo_akhir';
        }
       
        $data = $this->db->query('SELECT A.*, '.$q.', total_debet, total_kredit, '.$saldo_akhir.' FROM sma_custom_keuangan_account as A 
                    left JOIN 
                    (SELECT *, SUM(total) AS total_debet FROM sma_custom_keuangan_transaction AS D '.$where.' GROUP BY debet) 
                    AS B ON A.account_number = B.debet 
                    left JOIN 
                    (SELECT *, SUM(total) AS total_kredit FROM sma_custom_keuangan_transaction AS E '.$where.' GROUP BY kredit)  
                    AS C ON A.account_number = C.kredit 
                    left join
                    '.$debet_last_month.' 
                    left join
                    '.$kredit_last_month.'
                    WHERE A.id ='.$id.' GROUP BY A.id')->row();
        //print_r($this->db->last_query().'</br>');

        return $data;
    }

    public function get_laba_rugi($date){
         $this->db->select('sum(total) as total_keuntungan')
                        ->from('sma_custom_keuangan_transaction')
                        ->where('date <=', $date)
                        ->like('kredit', '4', 'after');

        $keuntungan = $this->db->get()->row();

        $this->db->select('sum(total) as total_beban')
                        ->from('sma_custom_keuangan_transaction')
                        ->where('date <=', $date)
                        ->where('(debet like "5%" or debet like "6%")');

        $beban = $this->db->get()->row();
        //print_r($this->db->last_query());
        //print_r((float)$keuntungan->total_keuntungan - (float)$beban->total_beban);
        return (float)$keuntungan->total_keuntungan - (float)$beban->total_beban;

    }

    public function get_laba_rugi_arus_kas($from, $to){
         $this->db->select('sum(total) as total_keuntungan')
                        ->from('sma_custom_keuangan_transaction')
                        ->where('date >=', $from)
                        ->where('date <=', $to)
                        ->like('kredit', '4', 'after');

        $keuntungan = $this->db->get()->row();

        $this->db->select('sum(total) as total_beban')
                        ->from('sma_custom_keuangan_transaction')
                        ->where('date >=', $from)
                        ->where('date <=', $to)
                        ->where('(debet like "5%" or debet like "6%")');

        $beban = $this->db->get()->row();
        //print_r($this->db->last_query());
        //print_r((float)$keuntungan->total_keuntungan - (float)$beban->total_beban);
        return (float)$keuntungan->total_keuntungan - (float)$beban->total_beban;

    }

    public function get_total_kas_last_month($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id WHERE B.name LIKE "%kas%"')->result();
        $total_kas = 0;
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_saldo_by_id($value->id, $from, $to);
            $total_kas = (float)$tmp->last_month + $total_kas;         
        }
        return $total_kas;
        //print_r($total_kas);
    }

     public function get_total_kas_this_month($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id WHERE B.name LIKE "%kas%"')->result();
        $total_kas = 0;
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_saldo_by_id($value->id, $from, $to);
            $total_kas = (float)$tmp->saldo_akhir + $total_kas;         
        }
        return $total_kas;
        //print_r($total_kas);
    }

    public function get_piutang($from, $to){
        $data = $this->db->get_where('sma_custom_keuangan_account', array('parent_id'=>69))->result();
        $total = 0;
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_saldo_by_id($value->id, $from, $to);
            $total = (float)$tmp->saldo_akhir + $total;                
        }

        print_r($total);
    }

    public function get_mutasi_from_parent_id($id,$from, $to){
        $data = $this->db->get_where('sma_custom_keuangan_account', array('parent_id' => $id))->result();
        $data_r = array();
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_saldo_by_id($value->id, $from, $to);
            $data_r[$value->name] = $tmp;
        }

        return $data_r;
    }

    public function neraca_arus_operasi($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                        JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id WHERE B.account_number LIKE "1%" AND 
                        (B.name not LIKE "%kas%" AND B.name not LIKE "%harta%")')->result();
        $data_r = array();
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_saldo_by_id($value->id, $from, $to);
            $data_r[$value->name] = $tmp;
        }
        //print_r($data_r);
        return $data_r;
    }

    public function neraca_arus_pendanaan($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                        JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id 
                        WHERE (A.account_number like "3%" OR A.account_number like "2%") AND 
                        (A.parent_id !=2 AND A.parent_id !=3)')->result();
        $data_r = array();
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_saldo_by_id($value->id, $from, $to);
            $data_r[$value->name] = $tmp;
        }
        //print_r($data_r);
        return $data_r;
    }
//////////////////////////////////// new transaction ///////////////////////////////////////////////////////////

    public function get_transaction_uid(){
        $check = $this->db->order_by('uid', 'desc')->get('sma_custom_keuangan_transaction_v')->row();
        if($check){
            return $check->uid+1;
        }else{
            return 1;
        }
    }

    public function get_note_by_uid($from, $to){
        $this->db->select('uid')
            ->from('sma_custom_keuangan_transaction_v')
            ->where('date >= "'.$from.'" and date <="'.$to.'"')
            ->group_by('uid');

        $data = $this->db->get()->result();

        return $data;
    }

    public function get_note($uid){
        $this->db->select('A.*, B.name, B.account_number')
            ->from('sma_custom_keuangan_transaction_v as A')
            ->join('sma_custom_keuangan_account As B', 'A.account = B.account_number')
            ->where('uid',$uid)
            ->order_by('id');
        $data = $this->db->get()->result();
        return $data;
    }

    public function mutasi($id, $pid, $from, $to){
        $last_month_from = date('Y-m-d', strtotime('-1 month', strtotime($from))).' '.'00:00:00';
        $last_month_to = date('Y-m-t', strtotime('-1 month', strtotime($from))).' '.'23:59:59';
        $where_last_month = 'and date <= "'.$last_month_to.'" ';

        $debet_last_month = '(SELECT SUM(total) AS total_debet_last, ACCOUNT 
                            FROM sma_custom_keuangan_transaction_v AS F WHERE TYPE = "debet" '.$where_last_month.' GROUP BY F.account) AS G
                            ON A.account_number = G.account ';
        $kredit_last_month ='(SELECT SUM(total) AS total_kredit_last, ACCOUNT 
                            FROM sma_custom_keuangan_transaction_v AS H WHERE TYPE = "kredit" '.$where_last_month.' GROUP BY H.account) AS I
                            ON A.account_number = I.account ';

        if($id==1 or $id==5 or $id ==6){
            $q = '(ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) + ifnull(total_debet, 0) - ifnull(total_kredit, 0)) as saldo_akhir';
        }else{
            $q = '(ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) + ifnull(total_kredit, 0) - ifnull(total_debet,0)) as saldo_akhir';
        }

        $where = 'date >= "'.$from.'" and date <= "'.$to.'" ';

        $main_query = 'select A.*, total_debet, total_kredit,'.$q.','.$saldo_akhir.'
                    FROM sma_custom_keuangan_account AS A 
                    left JOIN (SELECT SUM(total) AS total_debet, ACCOUNT 
                    FROM sma_custom_keuangan_transaction_v AS B WHERE TYPE = "debet" and '.$where.' GROUP BY B.account) AS C
                    ON A.account_number = C.account 
                    left JOIN (SELECT SUM(total) AS total_kredit, ACCOUNT 
                    FROM sma_custom_keuangan_transaction_v AS D WHERE TYPE = "kredit" and '.$where.' GROUP BY D.account) AS E
                    ON A.account_number = E.account
                    left join '.$debet_last_month.' left join '.$kredit_last_month.' WHERE A.parent_id='.$pid;

        $data = $this->db->query($main_query)->result();
        //print_r($this->db->last_query());
        return $data;
    }
    public function mutasi_by_id($id, $pid, $from, $to){
        $last_month_from = date('Y-m-d', strtotime('-1 month', strtotime($from))).' '.'00:00:00';
        $last_month_to = date('Y-m-t', strtotime('-1 month', strtotime($from))).' '.'23:59:59';
        $where_last_month = 'and date <= "'.$last_month_to.'" ';

        $debet_last_month = '(SELECT SUM(total) AS total_debet_last, ACCOUNT 
                            FROM sma_custom_keuangan_transaction_v AS F WHERE TYPE = "debet" '.$where_last_month.' GROUP BY F.account) AS G
                            ON A.account_number = G.account ';
        $kredit_last_month ='(SELECT SUM(total) AS total_kredit_last, ACCOUNT 
                            FROM sma_custom_keuangan_transaction_v AS H WHERE TYPE = "kredit" '.$where_last_month.' GROUP BY H.account) AS I
                            ON A.account_number = I.account ';

        if($id==1 or $id==5 or $id ==6){
            $q = '(ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_debet_last, 0) - ifnull(total_kredit_last, 0)) + ifnull(total_debet, 0) - ifnull(total_kredit, 0)) as saldo_akhir';
        }else{
            $q = '(ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) as last_month';
            $saldo_akhir = '((ifnull(total_kredit_last, 0) - ifnull(total_debet_last, 0)) + ifnull(total_kredit, 0) - ifnull(total_debet,0)) as saldo_akhir';
        }

        $where = 'date >= "'.$from.'" and date <= "'.$to.'" ';

        $main_query = 'select A.*, total_debet, total_kredit,'.$q.','.$saldo_akhir.'
                    FROM sma_custom_keuangan_account AS A 
                    left JOIN (SELECT SUM(total) AS total_debet, ACCOUNT 
                    FROM sma_custom_keuangan_transaction_v AS B WHERE TYPE = "debet" and '.$where.' GROUP BY B.account) AS C
                    ON A.account_number = C.account 
                    left JOIN (SELECT SUM(total) AS total_kredit, ACCOUNT 
                    FROM sma_custom_keuangan_transaction_v AS D WHERE TYPE = "kredit" and '.$where.' GROUP BY D.account) AS E
                    ON A.account_number = E.account
                    left join '.$debet_last_month.' left join '.$kredit_last_month.' WHERE A.id='.$pid;

        $data = $this->db->query($main_query)->row();
        return $data;
    }

    public function get_laba($date){
         $this->db->select('sum(total) as total_keuntungan')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date <=', $date)
                        ->like('account', '4', 'after')
                        ->where('type', 'kredit');

        $keuntungan = $this->db->get()->row();

        $this->db->select('sum(total) as total_beban')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date <=', $date)
                        ->where('(account like "5%" or account like "6%")')
                        ->where('type', 'debet');

        $beban = $this->db->get()->row();

        $this->db->select('sum(total) as total_debet')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date <=', $date)
                        ->like('account', '4', 'after')
                        ->where('type', 'debet');

        $keuntungan_ = $this->db->get()->row();

        $this->db->select('sum(total) as total_kredit')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date <=', $date)
                        ->where('(account like "5%" or account like "6%")')
                        ->where('type', 'kredit');

        $beban_ = $this->db->get()->row();
        //print_r($this->db->last_query());
        //print_r((float)$keuntungan->total_keuntungan - (float)$beban->total_beban);
        return (float)$keuntungan->total_keuntungan - (float)$beban->total_beban - (float)$keuntungan_->total_debet + (float)$beban_->total_kredit;

    }

    public function get_laba_cash_flow($from, $to){
         $this->db->select('sum(total) as total_keuntungan')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date >=', $from)
                        ->where('date <=', $to)
                        ->like('account', '4', 'after')
                        ->where('type', 'kredit');

        $keuntungan = $this->db->get()->row();

        $this->db->select('sum(total) as total_beban')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date >=', $from)
                        ->where('date <=', $to)
                        ->where('(account like "5%" or account like "6%")')
                        ->where('type', 'debet');

        $beban = $this->db->get()->row();

        $this->db->select('sum(total) as total_debet')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date >=', $from)
                        ->where('date <=', $to)
                        ->like('account', '4', 'after')
                        ->where('type', 'debet');

        $keuntungan_ = $this->db->get()->row();

        $this->db->select('sum(total) as total_kredit')
                        ->from('sma_custom_keuangan_transaction_v')
                        ->where('date >=', $from)
                        ->where('date <=', $to)
                        ->where('(account like "5%" or account like "6%")')
                        ->where('type', 'kredit');

        $beban_ = $this->db->get()->row();
        //print_r($this->db->last_query());
        //print_r((float)$keuntungan->total_keuntungan - (float)$beban->total_beban);
        return (float)$keuntungan->total_keuntungan - (float)$beban->total_beban - (float)$keuntungan_->total_debet + (float)$beban_->total_kredit;

    }

    public function neraca_arus_operasi_v2($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                        JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id WHERE B.account_number LIKE "1%" AND 
                        (B.name not LIKE "%kas%" AND B.name not LIKE "%harta%")')->result();
        $data_r = array();
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_by_id(1,$value->id, $from, $to);
            $data_r[$value->name] = $tmp;
        }
        //print_r($data);
        return $data_r;
    }

    public function neraca_arus_pendanaan_v2($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                        JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id 
                        WHERE (A.account_number like "3%" OR A.account_number like "2%") AND 
                        (A.parent_id !=2 AND A.parent_id !=3)')->result();
        $data_r = array();
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_by_id(3,$value->id, $from, $to);
            $data_r[$value->name] = $tmp;
        }
        //print_r($data_r);
        return $data_r;
    }

    public function get_total_kas_by_month($from, $to){
        $data = $this->db->query('SELECT A.*, B.name AS name_parent FROM sma_custom_keuangan_account AS A 
                JOIN sma_custom_keuangan_account AS B ON A.parent_id = B.id WHERE B.name LIKE "%kas%"')->result();
        $total_kas = 0;
        foreach ($data as $key => $value) {
            $tmp  = $this->mutasi_by_id(1,$value->id, $from, $to);
            $total_kas = (float)$tmp->saldo_akhir + $total_kas;         
        }
        return $total_kas;
        //print_r($total_kas);
    }

}