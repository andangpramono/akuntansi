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
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>Laporan Laba-rugi - <?= $month_now ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/laba_rugi') ?>">
                        <div class="form-group">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/laba_rugi') ?>">Reset</a>
                    </form>
                    <br>
                    <table class="table table-borderless">
                        <?php 
                        $sa = 0;
                        foreach ($penjualan as $key => $value): ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th><?= $key ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <?php foreach ($value as $key1 => $val): 
                                    $sa = $sa + $val->total_kredit;
                                ?>
                                    <tr>
                                        <td><?= $val->account_number ?></td>
                                        <td><?= $val->name ?></td>
                                        <td class="text-right"><?= number_format($val->total_kredit,0,",",".") ?></td>
                                        <td></td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                        <?php endforeach; ?>
                                <tr>
                                    <th></th>
                                    <th>Total <?= $key ?></th>
                                    <th></th>
                                    <th class="text-right"><?= number_format($sa,0,",",".") ?></th>
                                </tr>

                                <tr>
                                    <td colspan="4"></td>
                                </tr>

                        <?php 
                        $total_pem = 0;
                        foreach ($pembelian as $key => $value): ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th><?= $key ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <?php foreach ($value as $key1 => $val): 
                                    $total_pem = $total_pem + $val->total_debet;
                                ?>
                                    <tr>
                                        <td><?= $val->account_number ?></td>
                                        <td><?= $val->name ?></td>
                                        <td class="text-right"><?= number_format($val->total_debet,0,",",".") ?></td>
                                        <td></td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                        <?php endforeach; ?>
                                <tr>
                                    <th></th>
                                    <th>Total <?= $key ?></th>
                                    <th></th>
                                    <th class="text-right"><?= number_format($total_pem,0,",",".") ?></th>
                                </tr>

                                <tr>
                                    <td colspan="4"></td>
                                </tr>

                        <?php 
                        $total_bl = 0;
                        foreach ($beban_lain as $key => $value): ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th><?= $key ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <?php foreach ($value as $key1 => $val): 
                                    $total_bl = $total_bl + $val->total_debet;
                                ?>
                                    <tr>
                                        <td><?= $val->account_number ?></td>
                                        <td><?= $val->name ?></td>
                                        <td class="text-right"><?= number_format($val->total_debet,0,",",".") ?></td>
                                        <td></td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                        <?php endforeach; ?>
                            <?php if($beban_lain): ?>
                                <tr>
                                    <th></th>
                                    <th>Total <?= $key ?></th>
                                    <th></th>
                                    <th class="text-right"><?= number_format($total_bl,0,",",".") ?></th>
                                </tr>
                            <?php endif ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th>Laba/Rugi</th>
                                    <th></th>
                                    <th class="text-right"><?= number_format($sa-$total_pem-$total_bl,0,",",".") ?></th>
                                </tr>
                    </table>

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
</script>
