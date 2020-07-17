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
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>Laporan Neraca - <?= $month_now ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/neraca_saldo') ?>">
                        <div class="form-group">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/neraca_saldo') ?>">Reset</a>
                    </form>
                    <br>
                    <table class="table table-borderless">
                        <tr>
                            <th></th>
                            <th>Aktiva</th>
                            <th class="text-right"><?= $month_last ?></th>
                            <th class="text-right"><?= $month_now ?></th>
                        </tr>
                        <?php 
                        $t_aktiva = 0;
                        $t_aktiva_last = 0;
                        foreach ($aktiva as $key => $value): ?>
                            <?php if($value): ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th><?= $key ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            <?php endif ?>
                                <?php foreach ($value as $key1 => $val): 
                                    $t_aktiva = $t_aktiva + $val->saldo_akhir;
                                    $t_aktiva_last = $t_aktiva_last + $val->last_month;
                                ?>
                                    <tr>
                                        <td><?= $val->account_number ?></td>
                                        <td><?= $val->name ?></td>
                                        <td class="text-right"><?= number_format($val->last_month,0,",",".") ?></td>
                                        <td class="text-right"><?= number_format($val->saldo_akhir,0,",",".") ?></td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                        <?php endforeach; ?>
                        <tr>
                            <th></th>
                            <th>Total Aktiva</th>
                            <th class="text-right"><?= number_format($t_aktiva_last,0,",",".") ?></th>
                            <th class="text-right"><?= number_format($t_aktiva,0,",",".") ?></th>             
                        </tr>
                        <tr>
                            <th></th>
                            <th>Passiva</th>
                            <th></th>
                            <th></th>
                        </tr>
                         <?php 
                        $t_hutang = 0;
                        $t_hutang_last = 0;
                        foreach ($hutang as $key => $value): ?>
                            <?php if($value): ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th><?= $key ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            <?php endif ?>
                                <?php foreach ($value as $key1 => $val): 
                                    $t_hutang = $t_hutang+ $val->saldo_akhir;
                                    $t_hutang_last = $t_hutang_last + $val->last_month;
                                ?>
                                    <tr>
                                        <td><?= $val->account_number ?></td>
                                        <td><?= $val->name ?></td>
                                        <td class="text-right"><?= number_format($val->last_month,0,",",".") ?></td>
                                        <td class="text-right"><?= number_format($val->saldo_akhir,0,",",".") ?></td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                        <?php endforeach; ?>

                         <?php 
                        $t_modal = 0;
                        $t_modal_last = 0;
                        foreach ($modal as $key => $value): ?>
                            <?php if($value): ?>
                                <tr>
                                    <th width="10%"></th>
                                    <th><?= $key ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            <?php endif ?>
                                <?php foreach ($value as $key1 => $val): 
                                    $t_modal = $t_modal+ $val->saldo_akhir;
                                    $t_modal_last = $t_modal_last+ $val->last_month;
                                ?>
                                    <tr>
                                        <td><?= $val->account_number ?></td>
                                        <td><?= $val->name ?></td>
                                        <td class="text-right"><?= number_format($val->last_month,0,",",".") ?></td>
                                        <td class="text-right"><?= number_format($val->saldo_akhir,0,",",".") ?></td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                        <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td>Laba/Rugi</td>
                                <td class="text-right"><?= number_format($laba_last,0,",",".") ?></td>
                                <td class="text-right"><?= number_format($laba,0,",",".") ?></td>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Total Passiva</th>
                                
                                <th class="text-right"><?= number_format($laba_last+$t_modal_last+$t_hutang_last,0,",",".") ?></th>

                                <th class="text-right"><?= number_format($laba+$t_pembelian+$t_modal+$t_beban+$t_pendapatan+$t_hutang   ,0,",",".") ?></th>            
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
