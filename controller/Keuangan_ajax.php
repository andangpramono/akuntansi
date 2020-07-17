<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan_ajax extends MY_Controller
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

    public function get_sub_parent($id)
    {

        $datas = $this->keuangan_model->get_all_sub_parent_accounts($id);
        echo '<option value=0 selected="">New Account</option>';
        foreach ($datas as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

    }

    public function get_recommend_number($id, $sub_id){
        $parent_id = $this->keuangan_model->get_accounts_by_id($id);
        $sub_parent_id = $this->keuangan_model->get_accounts_by_id($sub_id);
        if($sub_id!=0){
            if(!$parent_id){
                echo 'Wrong Account Parent';
            }elseif(!$sub_parent_id){
                echo 'Wrong Sub Account Parent';
            }else{
                if($parent_id->id != $sub_parent_id->parent_id){
                    echo 'Wrong Sub Account Parent';
                }else{
                    $tmp_rec_number = explode(',', $sub_parent_id->account_number);
                    $tmp_number = $this->keuangan_model->get_uniq_number($tmp_rec_number[0], $tmp_rec_number[1], $tmp_rec_number[2]);
                    print_r($tmp_number);
                }
            }
        }else{
            $str_parent = (string)$id.'0';
            $tmp_number = $this->keuangan_model->get_nd_uniq_number($str_parent, '00');
            print_r($tmp_number);
        }
    }

    public function add_note($row){
        $accounts=$this->keuangan_model->get_child_account();
        $row_n = "row_".$row;
        $id = 'id="row_'.$row.'"';
        $onchange = "count('input.".$row_n."', '".$row_n."_total')";
        $select = "dk('select.row_".$row."_select','".$row_n."_total')";
        echo '
                <tr '.$id.'>
                                <td><input type="text" class="form-control" name="note[]" required=""></td>
                                <td>
                                    <select name="account[]" class="form-control">
        ';
        foreach ($accounts as $key => $value) {
        echo '<option value="'.$value->account_number.'">('.$value->account_number.') '.$value->name.'</option>';
        }
        echo '
                                    </select>
                                </td>
                                <td>
                                    <select name="d_k[]" class="form-control '.$row_n.'_select" onchange="'.$select.'">
                                        <option value="debet">Debet</option>
                                        <option value="kredit">Kredit</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="q[]" value="0" required="" class="form-control '.$row_n.'" style="width: 70px !important" onchange="'.$onchange.'">
                                </td>
                                <td>
                                    <input type="text" name="harga[]" value="0" required="" class="form-control '.$row_n.'" onchange="'.$onchange.'">
                                </td>
                                <td>
                                    <input type="text" name="total[]" id="row_'.$row.'_total" readonly="" class="form-control total debet" value="0">
                                </td>
                                <td></td>
                </tr>

            ';

    }



}
