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
<?php if (true) {

    ?>
    <style type="text/css">
        .table>thead>tr>td.purple, .table>tbody>tr>td.purple, .table>tfoot>tr>td.purple, .table>thead>tr>th.purple, .table>tbody>tr>th.purple, .table>tfoot>tr>th.purple, .table>thead>tr.purple>td, .table>tbody>tr.purple>td, .table>tfoot>tr.purple>td, .table>thead>tr.purple>th, .table>tbody>tr.purple>th, .table>tfoot>tr.purple>th {
                background-color: #F7E2FF;
        }
    </style>
    <div class="box" style="margin-bottom: 15px;">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i>Add Account</h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="<?= admin_url('keuangan/add_account') ?>">
                        <div class="form-group">
                            <label>Account Parent</label>
                            <select name="account_parent" class="form-control" id="parent_accounts" required="">
                                <?php foreach ($parent_accounts as $key => $value): ?>
                                    <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">   
                            <label>Sub Account Parent</label>
                            <select name="sub_account_parent" class="form-control" id="sub_account_parent" required="">
                                
                            </select>
                        </div>
                        <div class="form-group">   
                            <label>Code Account</label>
                            <input type="text" name="code" id='code' class="form-control" required="">
                        </div>

                         <div class="form-group">   
                            <label>Name</label>
                            <input type="text" name="name" id='name' class="form-control" required="">
                        </div>

                        <input class="btn btn-sm btn-success" type="submit" name="ok">
                    </form>
                    <hr>
                    <table class="table">
                        <?php 
                            echo '
                                    <tr>
                                        <th>Name<th>
                                        <th>No Account<th>
                                    <tr>
                            '; 
                             echo '
                                    <tr>
                                        <td>Harta<td>
                                        <td><td>
                                    <tr>
                            '; 

                            foreach ($harta as $key => $value) {
                                echo '
                                    <tr>
                                        <td style="padding-left:30px">'.$value->name.'<td>
                                        <td>'.$value->account_number.'<td>
                                    <tr>
                                ';

                                $data = $this->keuangan_model->get_all_sub_parent_accounts($value->id);
                                if($data){
                                    foreach ($data as $key1 => $value1) {
                                        echo '
                                            <tr>
                                                <td style="padding-left:60px">'.$value1->name.'<td>
                                                <td>'.$value1->account_number.'<td>
                                            <tr>
                                        ';
                                    }
                                }
                            }

                            echo '
                                    <tr>
                                        <td>Hutang<td>
                                        <td><td>
                                    <tr>
                            '; 

                            foreach ($hutang as $key => $value) {
                                echo '
                                    <tr>
                                        <td style="padding-left:30px">'.$value->name.'<td>
                                        <td>'.$value->account_number.'<td>
                                    <tr>
                                ';

                                $data = $this->keuangan_model->get_all_sub_parent_accounts($value->id);
                                if($data){
                                    foreach ($data as $key1 => $value1) {
                                        echo '
                                            <tr>
                                                <td style="padding-left:60px">'.$value1->name.'<td>
                                                <td>'.$value1->account_number.'<td>
                                            <tr>
                                        ';
                                    }
                                }
                            }

                            echo '
                                    <tr>
                                        <td>Modal<td>
                                        <td><td>
                                    <tr>
                            '; 

                            foreach ($modal as $key => $value) {
                                echo '
                                    <tr>
                                        <td style="padding-left:30px">'.$value->name.'<td>
                                        <td>'.$value->account_number.'<td>
                                    <tr>
                                ';

                                $data = $this->keuangan_model->get_all_sub_parent_accounts($value->id);
                                if($data){
                                    foreach ($data as $key1 => $value1) {
                                        echo '
                                            <tr>
                                                <td style="padding-left:60px">'.$value1->name.'<td>
                                                <td>'.$value1->account_number.'<td>
                                            <tr>
                                        ';
                                    }
                                }
                            }

                            echo '
                                    <tr>
                                        <td>Pendapatan<td>
                                        <td><td>
                                    <tr>
                            '; 

                            foreach ($pendapatan as $key => $value) {
                                echo '
                                    <tr>
                                        <td style="padding-left:30px">'.$value->name.'<td>
                                        <td>'.$value->account_number.'<td>
                                    <tr>
                                ';

                                $data = $this->keuangan_model->get_all_sub_parent_accounts($value->id);
                                if($data){
                                    foreach ($data as $key1 => $value1) {
                                        echo '
                                            <tr>
                                                <td style="padding-left:60px">'.$value1->name.'<td>
                                                <td>'.$value1->account_number.'<td>
                                            <tr>
                                        ';
                                    }
                                }
                            }

                            echo '
                                    <tr>
                                        <td>Beban<td>
                                        <td><td>
                                    <tr>
                            '; 

                            foreach ($beban as $key => $value) {
                                echo '
                                    <tr>
                                        <td style="padding-left:30px">'.$value->name.'<td>
                                        <td>'.$value->account_number.'<td>
                                    <tr>
                                ';

                                $data = $this->keuangan_model->get_all_sub_parent_accounts($value->id);
                                if($data){
                                    foreach ($data as $key1 => $value1) {
                                        echo '
                                            <tr>
                                                <td style="padding-left:60px">'.$value1->name.'<td>
                                                <td>'.$value1->account_number.'<td>
                                            <tr>
                                        ';
                                    }
                                }
                            }

                            foreach ($beban_ as $key => $value) {
                                echo '
                                    <tr>
                                        <td style="padding-left:30px">'.$value->name.'<td>
                                        <td>'.$value->account_number.'<td>
                                    <tr>
                                ';

                                $data = $this->keuangan_model->get_all_sub_parent_accounts($value->id);
                                if($data){
                                    foreach ($data as $key1 => $value1) {
                                        echo '
                                            <tr>
                                                <td style="padding-left:60px">'.$value1->name.'<td>
                                                <td>'.$value1->account_number.'<td>
                                            <tr>
                                        ';
                                    }
                                }
                            }
    
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $( document ).ready(function() {
        $.ajax({url: "<?= admin_url('keuangan_ajax/get_sub_parent/1') ?>", success: function(result){
            $("#sub_account_parent").html(result);
        }});
        
    });

    $("#parent_accounts").change(function(){
        var parent_id = $('#parent_accounts').find(":selected").val();
        $('#sub_account_parent').find('option').remove();
        $.ajax({url: "<?= admin_url('keuangan_ajax/get_sub_parent/') ?>"+parent_id, success: function(result){
            $("#sub_account_parent").html(result);
            var sub_parent_id = $('#sub_parent_accounts').find(":selected").val();
            $.ajax({url: "<?= admin_url('keuangan_ajax/get_recommend_number/') ?>"+parent_id+'/'+sub_parent_id, success: function(result){
                $('#code').val(result);
            }});
        }});
        
    });

    $("#sub_account_parent").change(function(){
        var parent_id = $('#parent_accounts').find(":selected").val();

        var sub_parent_id = $('#sub_account_parent').find(":selected").val();
            $.ajax({url: "<?= admin_url('keuangan_ajax/get_recommend_number/') ?>"+parent_id+'/'+sub_parent_id, success: function(result){
                $('#code').val(result);
        }});
        
    });
    
</script>

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
