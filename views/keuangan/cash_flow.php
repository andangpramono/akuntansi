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
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>Laporan Arus Kas - <?= $month_now ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/cash_flow') ?>">
                        <div class="form-group">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/cash_flow') ?>">Reset</a>
                        <a class="btn btn-sm btn-warning" id="print_this" >Print</a>
                    </form>
                    <br>
                    <table class="table table-borderless" id="print_area">

                        <tr>
                            <th></th>
                            <th>Arus Kas dari Kegiatan Operasional</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Laba/Rugi</td>
                            <td class="text-right"><?= number_format($laba,0,",",".") ?></td>
                            <td></td>
                        </tr>


                        <?php 
                        $op = $laba;
                        foreach ($operasi as $key => $value) : 
                             $op = $op + (-1*($value->saldo_akhir - $value->last_month));
                        ?>
                             <tr>
                                <td></td>
                                <td><?= $value->name ?></td>
                                <td class="text-right"><?= number_format(-1*($value->saldo_akhir - $value->last_month),0,",",".") ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th></th>
                            <th>Total Arus Kas dari Kegiatan Operasional</th>
                            <th></th>
                            <th class="text-right"><?= number_format($op,0,",",".") ?></th>
                        </tr>

                         <tr>
                            <th></th>
                            <th>Arus Kas dari Kegiatan Pendanaan</th>
                            <th></th>
                            <th></th>
                        </tr>

                        <?php 
                        $pen = 0;
                        foreach ($pendanaan as $key => $value) : 
                             $pen = $pen + (1*($value->saldo_akhir - $value->last_month));
                        ?>
                             <tr>
                                <td></td>
                                <td><?= $value->name ?></td>
                                <td class="text-right"><?= number_format(1*($value->saldo_akhir - $value->last_month),0,",",".") ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th></th>
                            <th>Arus Kas dari Kegiatan Pendanaan</th>
                            <th></th>
                            <th class="text-right"><?= number_format($pen,0,",",".") ?></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Total Kegiatan Kas</th>
                            <th></th>
                            <th class="text-right"><?= number_format($pen + $op,0,",",".") ?></th>
                        </tr>
                         <tr>
                            <th></th>
                            <th>Saldo Awal Kas</th>
                            <th></th>
                            <th class="text-right"><?= number_format($total_kas,0,",",".") ?></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Saldo Kas Seharusnya</th>
                            <th></th>
                            <th class="text-right"><?= number_format($total_kas + $pen + $op,0,",",".") ?></th>
                        </tr>
                         <tr>
                            <th></th>
                            <th>Saldo Akhir Kenyataan</th>
                            <th></th>
                            <th class="text-right"><?= number_format($total_kas_this_month,0,",",".") ?></th>
                        </tr>
                    </table>
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
    
</script>
