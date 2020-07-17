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
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>RMS (<?= $this->input->get('from').' - '.$this->input->get('to') ?>)</h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="<?= admin_url('keuangan/rms') ?>">
                        <div class="form-group">
                            <label>Dari</label>
                            <input type="date" name="from" class="form-control" required="">
                        </div>
                        <div class="form-group">   
                            <label>Sampai</label>
                            <input type="date" name="to" class="form-control" required="">
                        </div>
                        <input class="btn btn-sm btn-success" type="submit" name="ok" value="Filter">
                        <a class="btn btn-sm btn-success" href="<?= admin_url('keuangan/rms') ?>">Reset</a>
                    </form>
                    <br>
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th class="text-center">TAN</th>
                            <th class="text-center">NOP</th>
                            <th class="text-center">NAP</th>
                            <th class="text-center">mts D</th>
                            <th class="text-center">mts K</th>
                        </tr>

                        <?php foreach ($a100000 as $key => $value) : ?>
                            <tr class="danger">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a101000 as $key => $value) : ?>
                            <tr class="info">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_k ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a1020 as $key => $value) : ?>
                            <tr>
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a1021 as $key => $value) : ?>
                            <tr class="success">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                         <?php foreach ($a103 as $key => $value) : ?>
                            <tr class="danger">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_k ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a104 as $key => $value) : ?>
                            <tr class="danger">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_k ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a105 as $key => $value) : ?>
                            <tr class="danger">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_k ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a106 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a107 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a108 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a109 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                         <?php foreach ($a11000 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                         <?php foreach ($a11100 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                         <?php foreach ($a11110 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a11120 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a11200 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a20 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a30 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_k ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a40 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_k ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($a50 as $key => $value) : ?>
                            <tr class="default">
                                <td><?= $value->tan ?></td>
                                <td><?= $value->nop ?></td>
                                <td><?= $value->nap ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                                <td class="text-center"><?= $value->mts_d ?></td>
                            </tr>
                        <?php endforeach; ?>
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