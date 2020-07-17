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
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>General Entries - <?= $month_now ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/general') ?>">
                        <div class="form-group">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/general') ?>">Reset</a>
                    </form>
                    <hr>

                    <?php 
                    
                    foreach ($notes as $key => $value): 
                        $total_debet = 0;
                        $total_kredit = 0;
                    ?>
                        <table width="100%" class="table table-bordered" style="font-size: 14px !important">
                            <tr>
                                <th colspan="7">note uid : <?= $key ?></th>
                            </tr>
                            <tr >
                                <th width="10%" class="text-center">Date</th>
                                <th class="text-center">Note</th>
                                <th class="text-center">Account</th>
                                <th class="text-center">Q</th>
                                <th class="text-center">Harga @</th>
                                <th class="text-center">Debet</th>
                                <th class="text-center">Kredit</th>
                            </tr>
                            <?php $c = 1; foreach ($value as $key1 => $val): ?>
                                <tr>
                                    <?php
                                        if($c==1){
                                            echo '<td rowspan="'. count($value) .'">'.$val->date.'</td>';
                                        }
                                        $c++;
                                    ?>
                                    <td><?= $val->note ?></td>
                                    <td><?= $val->name ?> - <?= $val->account ?></td>
                                    <td><?= $val->q ?></td>
                                    <td><?= number_format($val->price_at,0,",",".") ?></td>
                                    <td class="text-center">
                                        <?php
                                            if($val->type == 'debet'){
                                                $total_debet = $total_debet + $val->total;
                                                echo number_format($val->total,0,",",".");
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            if($val->type == 'kredit'){
                                                $total_kredit = $total_kredit + $val->total;
                                                echo number_format($val->total,0,",",".");
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                                <tr>
                                    <th colspan="5" class="text-right"> Total</th>
                                    <th class="text-center"><?= number_format($total_debet,0,",",".") ?></th>
                                    <th class="text-center"><?= number_format($total_kredit,0,",",".") ?></th>
                                </tr>
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
