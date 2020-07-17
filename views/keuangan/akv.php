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
<?php if (($Owner || $Admin) && $chatData) {
    foreach ($chatData as $month_sale) {
        $months[] = date('M-Y', strtotime($month_sale->month));
        $msales[] = $month_sale->sales;
        $mtax1[] = $month_sale->tax1;
        $mtax2[] = $month_sale->tax2;
        $mpurchases[] = $month_sale->purchases;
        $mtax3[] = $month_sale->ptax;
    }
    ?>
    <div class="box" style="margin-bottom: 15px;">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>AKV (<?= $this->input->get('from').' - '.$this->input->get('to') ?>)</h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/akv') ?>">
                        <div class="form-group">
                            <label>Dari</label>
                            <input type="date" name="from" class="form-control" required="">
                        </div>
                        <div class="form-group">   
                            <label>Sampai</label>
                            <input type="date" name="to" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/akv') ?>">Reset</a>
                    </form>
                    <br>
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="4">I. PENERIMAAN</th>
                        </tr>
                        <tr>
                            <th class="text-center">40,00,00</th>
                            <th class="text-center">A. Penjualan</th>
                            <th></th>
                            <th class="text-right">Rp.</th>
                        </tr>
                        <?php foreach ($a4010 as $key => $value): 
                            $total = $total + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= (float)$value->mts_k-(float)$value->mts_d ?></td>
                        </tr>
                        <?php endforeach ?>
                        <?php foreach ($a4011 as $key => $value): 
                            $total = $total + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= (float)$value->mts_k-(float)$value->mts_d ?></td>
                        </tr>
                        <?php endforeach ?>
                        <?php foreach ($a4001 as $key => $value): 
                            $total = $total + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= (float)$value->mts_k-(float)$value->mts_d ?></td>
                        </tr>
                        <?php endforeach ?>
                         <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">_____________________ +</td>
                        </tr>
                         <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= $total ?></td>
                        </tr>

                        <?php 
                        $total_potongan = 0;
                        foreach ($a4012 as $key => $value): 
                        $total_potongan = $total_potongan + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= abs((float)$value->mts_k-(float)$value->mts_d) ?></td>
                        </tr>
                        <?php endforeach ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">_____________________ +</td>
                        </tr>
                         <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= abs($total_potongan) ?></td>
                        </tr>

                        <tr>
                            <th></th>
                            <th class="text-right">TOTAL PENJUALAN (A)</th>
                            <th></th>
                            <th class="text-right">Rp. <?= $total + $total_potongan ?></th>
                        </tr>

                         <tr>
                            <th class="text-center">40,20,00</th>
                            <th class="text-center">B. Pendapatan Lain-lain</th>
                            <th></th>
                            <th class="text-right"></th>
                        </tr>
                        <?php 
                        $total_lain = 0;
                        foreach ($a4020 as $key => $value): 
                        $total_lain = $total_lain + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= (float)$value->mts_k-(float)$value->mts_d ?></td>
                        </tr>
                        <?php endforeach ?>
                        <?php 
                        foreach ($a4099 as $key => $value): 
                        $total_lain = $total_lain + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= (float)$value->mts_k-(float)$value->mts_d ?></td>
                        </tr>
                        <?php endforeach ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">_____________________ +</td>
                        </tr>
                         <tr>
                            <td></td>
                            <td class="text-right"><b>TOTAL PENDAPATAN LAIN-LAIN (B)</b></td>
                            <td class="text-right"></td>
                            <th class="text-right">Rp. <?= $total_lain ?></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-right"><b>TOTAL PENERIMAAN (A+B)</b></th>
                            <th class="text-right"></th>
                            <th class="text-right">Rp. <?= $total_lain + $total_potongan + $total ?></th>
                        </tr>

                         <tr>
                            <th colspan="4">II. PENGELUARAN</th>
                        </tr>
                        <tr>
                            <th class="text-center">50,00,00</th>
                            <th class="text-center">A. Pengeluaran Pulsa CS</th>
                            <th></th>
                            <th class="text-right"></th>
                        </tr>
                        <?php 
                        $total_pulsa=0;
                        foreach ($a5000 as $key => $value): 
                        $total_pulsa = $total_pulsa + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= abs((float)$value->mts_k-(float)$value->mts_d) ?></td>
                        </tr>
                        <?php endforeach ?>
                         <tr>
                            <th></th>
                            <th class="text-right">TOTAL BIAYA PULSA CS (A)</th>
                            <th></th>
                            <th class="text-right">Rp. <?= abs($total_pulsa) ?></th>
                        </tr>
                         <tr>
                            <th class="text-center">60,00,00</th>
                            <th class="text-center">B. Biaya Operasional Produk (BOP)</th>
                            <th></th>
                            <th class="text-right"></th>
                        </tr>
                         <?php 
                        $total_bop=0;
                        foreach ($a6 as $key => $value): 
                        $total_bop = $total_bop + (float)$value->mts_k-(float)$value->mts_d;
                        ?>
                        <tr>
                            <td><?= $value->nop ?></td>
                            <td><?= $value->nap ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?= abs((float)$value->mts_k-(float)$value->mts_d) ?></td>
                        </tr>
                        <?php endforeach ?>
                         <tr>
                            <th></th>
                            <th class="text-right">TOTAL BIAYA OPERASIONAL (BOP) (B)</th>
                            <th></th>
                            <th class="text-right">Rp. <?= abs($total_bop) ?></th>
                        </tr>
                         <tr>
                            <th></th>
                            <th class="text-right">TOTAL PENGELUARAN (A+B)</th>
                            <th></th>
                            <th class="text-right">Rp. <?= abs($total_bop + $total_pulsa) ?></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="text-right"></th>
                            <th></th>
                            <th class="text-right"></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-right">KENAIKAN(PENURUNAN) AKTIVA BERSIH</th>
                            <th></th>
                            <th class="text-right">Rp. <?= $total + $total_potongan + $total_lain + $total_pulsa + $total_bop ?></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


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

<?php if (($Owner || $Admin || $GP['reports-products']) && $chatData) { ?>
    <style type="text/css" media="screen">
        .tooltip-inner {
            max-width: 500px;
        }
    </style>
    <script src="<?= $assets; ?>js/hc/highcharts.js"></script>
    <script type="text/javascript">
        $(function () {
            Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
                return {
                    radialGradient: {cx: 0.5, cy: 0.3, r: 0.7},
                    stops: [[0, color], [1, Highcharts.Color(color).brighten(-0.3).get('rgb')]]
                };
            });
            $('#ov-chart').highcharts({
                chart: {},
                credits: {enabled: false},
                title: {text: ''},
                xAxis: {categories: <?= json_encode($months); ?>},
                yAxis: {min: 0, title: ""},
                tooltip: {
                    shared: true,
                    followPointer: true,
                    formatter: function () {
                        if (this.key) {
                            return '<div class="tooltip-inner hc-tip" style="margin-bottom:0;">' + this.key + '<br><strong>' + currencyFormat(this.y) + '</strong> (' + formatNumber(this.percentage) + '%)';
                        } else {
                            var s = '<div class="well well-sm hc-tip" style="margin-bottom:0;"><h2 style="margin-top:0;">' + this.x + '</h2><table class="table table-striped"  style="margin-bottom:0;">';
                            $.each(this.points, function () {
                                s += '<tr><td style="color:{series.color};padding:0">' + this.series.name + ': </td><td style="color:{series.color};padding:0;text-align:right;"> <b>' +
                                currencyFormat(this.y) + '</b></td></tr>';
                            });
                            s += '</table></div>';
                            return s;
                        }
                    },
                    useHTML: true, borderWidth: 0, shadow: false, valueDecimals: site.settings.decimals,
                    style: {fontSize: '14px', padding: '0', color: '#000000'}
                },
                series: [{
                    type: 'column',
                    name: '<?= lang("sp_tax"); ?>',
                    data: [<?php
                    echo implode(', ', $mtax1);
                    ?>]
                },
                    {
                        type: 'column',
                        name: '<?= lang("order_tax"); ?>',
                        data: [<?php
                    echo implode(', ', $mtax2);
                    ?>]
                    },
                    {
                        type: 'column',
                        name: '<?= lang("sales"); ?>',
                        data: [<?php
                    echo implode(', ', $msales);
                    ?>]
                    }, {
                        type: 'spline',
                        name: '<?= lang("purchases"); ?>',
                        data: [<?php
                    echo implode(', ', $mpurchases);
                    ?>],
                        marker: {
                            lineWidth: 2,
                            states: {
                                hover: {
                                    lineWidth: 4
                                }
                            },
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: 'white'
                        }
                    }, {
                        type: 'spline',
                        name: '<?= lang("pp_tax"); ?>',
                        data: [<?php
                    echo implode(', ', $mtax3);
                    ?>],
                        marker: {
                            lineWidth: 2,
                            states: {
                                hover: {
                                    lineWidth: 4
                                }
                            },
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: 'white'
                        }
                    }, {
                        type: 'pie',
                        name: '<?= lang("stock_value"); ?>',
                        data: [
                            ['', 0],
                            ['', 0],
                            ['<?= lang("stock_value_by_price"); ?>', <?php echo $stock->stock_by_price; ?>],
                            ['<?= lang("stock_value_by_cost"); ?>', <?php echo $stock->stock_by_cost; ?>],
                        ],
                        center: [80, 42],
                        size: 80,
                        showInLegend: false,
                        dataLabels: {
                            enabled: false
                        }
                    }]
            });
        });
    </script>

    <script type="text/javascript">
        $(function () {
            <?php if ($lmbs) { ?>
            $('#lmbschart').highcharts({
                chart: {type: 'column'},
                title: {text: ''},
                credits: {enabled: false},
                xAxis: {type: 'category', labels: {rotation: -60, style: {fontSize: '13px'}}},
                yAxis: {min: 0, title: {text: ''}},
                legend: {enabled: false},
                series: [{
                    name: '<?=lang('sold');?>',
                    data: [<?php
                    foreach ($lmbs as $r) {
                        if($r->quantity > 0) {
                            echo "['".$r->product_name."<br>(".$r->product_code.")', ".$r->quantity."],";
                        }
                    }
                    ?>],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#000',
                        align: 'right',
                        y: -25,
                        style: {fontSize: '12px'}
                    }
                }]
            });
            <?php } if ($bs) { ?>
            $('#bschart').highcharts({
                chart: {type: 'column'},
                title: {text: ''},
                credits: {enabled: false},
                xAxis: {type: 'category', labels: {rotation: -60, style: {fontSize: '13px'}}},
                yAxis: {min: 0, title: {text: ''}},
                legend: {enabled: false},
                series: [{
                    name: '<?=lang('sold');?>',
                    data: [<?php
                foreach ($bs as $r) {
                    if($r->quantity > 0) {
                        echo "['".$r->product_name."<br>(".$r->product_code.")', ".$r->quantity."],";
                    }
                }
                ?>],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#000',
                        align: 'right',
                        y: -25,
                        style: {fontSize: '12px'}
                    }
                }]
            });
            <?php } ?>
        });
    </script>

<?php } ?>
