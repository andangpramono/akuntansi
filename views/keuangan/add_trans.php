<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
function row_status($x)
{
    if ($x == null) {
        return '';
    } elseif ($x == 'pending') {
        return '<div class="text-center"><span class="label label-warning">' . lang($x) . '</span></div>';
    } elseif ($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received') {
        return '<div class="text-center"><span class="label label-success">' . lang($x) . '</span></div>';
    } elseif ($x == 'partial' || $x == 'transferring') {
        return '<div class="text-center"><span class="label label-info">' . lang($x) . '</span></div>';
    } elseif ($x == 'due') {
        return '<div class="text-center"><span class="label label-danger">' . lang($x) . '</span></div>';
    } else {
        return '<div class="text-center"><span class="label label-default">' . lang($x) . '</span></div>';
    }
}

?>
    <style type="text/css">
        tr.noBorder td {
            border: 0 !important;
        }
    </style>
    <div class="box" style="margin-bottom: 15px;">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i> Add Transaction</h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="form_note_trans" method="post" action="<?= admin_url('keuangan/add_trans') ?>">
                        <div class="form-group" style="padding: 8px !important">
                            <label>Tanggal</label>
                            <input type="datetime-local" name="date" class="form-control">
                        </div>
                        <table class="table table-borderless" id="table_form">
                            <tr id="row_1">
                                <th>Note</th>
                                <th>Akun</th>
                                <th>D/K</th>
                                <th>Q</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th width="70px">

                                <a id="add_note" class="btn btn-xs btn-primary"><i class="fa fa-plus-circle"></i> </a>
                                <a id="min_note" class="btn btn-xs btn-danger"><i class="fa fa-minus-circle"></i> </a>

                                </th>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="note[]" required=""></td>
                                <td>
                                    <select name="account[]" class="form-control">
                                        <?php foreach ($accounts as $key => $value) {
                                            echo '<option value="'.$value->account_number.'">('.$value->account_number.') '.$value->name.'</option>';
                                        } ?>                                        
                                    </select>
                                </td>
                                <td>
                                    <select name="d_k[]" class="form-control row_1_select" onchange="dk('select.row_1_select','row_1_total')">
                                        <option value="debet">Debet</option>
                                        <option value="kredit">Kredit</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="q[]" required="" class="form-control row_1" style="width: 70px !important" value="0" onchange="count('input.row_1', 'row_1_total')">
                                </td>
                                <td>
                                    <input type="text" name="harga[]" required="" class="form-control row_1" value="0" onchange="count('input.row_1', 'row_1_total')">
                                </td>
                                <td>
                                    <input type="text" name="total[]" id="row_1_total" readonly="" class="form-control total debet" value="0">
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        <table class="table table-borderless align-right">
                            <tr>
                                <th class="text-right">
                                    <input type="text" id="total_note_debet" name="" disabled="" readonly="">
                                </th>
                                <th>
                                    <input type="text" id="total_note_kredit" name="" disabled="" readonly="">
                                </th>
                            </tr>
                        </table>
                        <hr>
                        <input type="submit" class="btn btn-primary" name="">
                    </form>
                </div>
            </div>
        </div>
    </div>


<script type="text/javascript">
    $(document).ready(function () {
        $('.order').click(function () {
            window.location.href = '<?=admin_url()?>orders/view/' + $(this).attr('id') + '#comments';
        });
        $('.invoice').click(function () {
            window.location.href = '<?=admin_url()?>orders/view/' + $(this).attr('id');
        });
        $('.quote').click(function () {
            window.location.href = '<?=admin_url()?>quotes/view/' + $(this).attr('id');
        });
    });

   $('#print_this').click(function (){
       $('#print_area').printThis({
            importCSS: true,
        });
   });

    $('#form_note_trans').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
    });
    
</script>

<script type="text/javascript">
    $('#add_note').click(function (){
        $('#add_note').css('display','none');
        var rowCount = $('#table_form tr').length;
        var url = "<?= admin_url('keuangan_ajax/add_note/') ?>"+rowCount;
        $.ajax({url: url, success: function(result){
            $('#table_form tr:last').after(result);
            $('#add_note').css('display','inline-block');
        }});
    });

    $('#min_note').click(function (){
        var rowCount = $('#table_form tr').length;
        if(rowCount>2){
            $('#table_form tr:last').remove();
        }        
    });
</script>

<script type="text/javascript">
    function flow(){
        var total_debet = 0;
        var total_kredit = 0;
        var debet = document.querySelectorAll('input.total.debet');
        var kredit = document.querySelectorAll('input.total.kredit');
        for (i = 0; i < debet.length; i++) {
            total_debet = total_debet + parseFloat(debet[i].value);
        }
        for (i = 0; i < kredit.length; i++) {
            total_kredit = total_kredit + parseFloat(kredit[i].value);
        }

        document.getElementById('total_note_debet').value = total_debet;
        document.getElementById('total_note_kredit').value = total_kredit;
    }

    function count(selector, id_total){
        var x = document.querySelectorAll(selector);
        var total = 1;
        for (i = 0; i < x.length; i++) {
             total = total * parseInt(x[i].value);
             console.log(parseInt(x[i].value));
        }
        document.getElementById(id_total).value = total;
        flow();
      
    }

    function dk(selector, id_total){
        var x = document.querySelectorAll(selector);
        var type = x[0].options[x[0].selectedIndex].value;

        var y = document.getElementById(id_total);
        y.classList.remove('debet');
        y.classList.remove('kredit');
        y.classList.add(type);

        flow();
    }

    flow();
</script>
