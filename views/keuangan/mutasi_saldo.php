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
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>Mutasi Saldo - <?= $month_now ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/mutasi') ?>">
                        <div class="form-group">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/mutasi') ?>">Reset</a>
                    </form>
                    <hr>
                    <?php foreach ($accounts as $key => $akuns): ?>
                        <br>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="6"><?= $key ?></th>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Saldo Awal</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Saldo Akhir</th>
                            </tr>
                            <?php foreach ($akuns as $key => $value): ?>
                                <tr>
                                    <td><?= $value->account_number ?></td>
                                    <td><?= $value->name ?></td>
                                    <td><?= number_format($value->last_month,0,",",".") ?></td>
                                    <td><?= number_format($value->total_debet,0,",",".") ?></td>
                                    <td><?= number_format($value->total_kredit,0,",",".") ?></td>
                                    <td><?= number_format($value->saldo_akhir,0,",",".") ?></td>
                                </tr>
                            <?php endforeach;
                                if(!$akuns){
                                    echo '<tr><td colspan="6">Data belum tersedia</td></tr>';
                                }
                            ?>

                        </table>
                    <?php endforeach ?>

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
