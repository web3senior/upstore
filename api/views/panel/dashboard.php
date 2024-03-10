<?php
$data = $this->data;
(new Session)->init();
$admin_information = (new Session)->get('adminInfo');

function _statisticsCard($name, $value, $icon, $size = 2, $gradient = '')
{
    return '<div class="ms-Grid-col ms-sm12 ms-md' . $size . '">
    <div class="card">
        <div class="card-body d-flex flex-column align-items-left" style="' . $gradient . '">
        <i class="goldtext ms-Icon ms-Icon--' . $icon . ' ms-fontSize-28" aria-hidden="true"></i>
       <span class="ms-fontSize-24 ms-fontWeight-bold mt-20">' . $value . '</span>
        <span class="mt-10">' . $name . '</span>
		</div>
	</div>
</div>';
}

//number_format(($data['totalOfCashbox'][0]['sum'] ? $data['totalOfCashbox'][0]['sum'] : 0), 2, '.', ',')
$chartData = [];
foreach ($data['expert_chart'] as $key => $value) {
    array_push($chartData, [$value['province_name'], $value['total']]);
}


$accountingChartData = [];
foreach ($data['chart_accounting'] as $key => $value) {
    array_push($accountingChartData, [$value['type'] ? 'بستانکار' : 'بدهکار', $value['total']]);
}

$expertType = [];
foreach ($data['chart_expert'] as $key => $value) {
    switch ($value['score']) {
        case '0':
            $type = "امتیاز آور";
            break;
        case '1':
            $type = "دو/سوم";
            break;
        case '3':
            $type = "عضو ساده";
            break;

        default:
        $type = "نا مشخص";
            break;
    }
    array_push($expertType, [$type, $value['total']]);
}
?>
<script>
    //localStorage.setItem('Name', 'Amir');
    //localStorage.clear();
</script>
<section class="dashboard">
    <div class="__frame">
        <div class="ms-Grid">
            <div class="ms-Grid-row">
                <div class="ms-Grid-col ms-sm12">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--Info ms-fontSize-16" aria-hidden="true"></i>
                            <span class="v-m">
                                قراردادهای رو به اتمام
                                مهندسین
                                |
                                امروز:
                                <span dir="ltr">[<?= jdate('Y-m-d', time(), '', '', 'fa') ?>]</span>
                            </span>
                        </div>
                        <div class="card-body">

                            <?php
                            foreach ($data['deal_to_end_list'] as $key => $value) {
                            ?>
                                <div class="alert alert--danger">
                                    قرارداد شماره
                                    <strong class="badge badge-danger"><?= $value['id'] ?></strong>
                                    رو به اتمام است.
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?= _statisticsCard('مهندسین', $data['expert'][0]['total'], 'UserGauge', 3, null) ?>
                <?= _statisticsCard('شرکت ها', $data['company'][0]['total'], 'Work', 3, 'baeg, #9A00B2 35.05%, #FBFBFE 76.78%);') ?>
                <?= _statisticsCard('قراردادها', $data['deal'][0]['total'], 'Picture', 3, null) ?>
                <?= _statisticsCard('خدمات', $data['service'][0]['total'], 'CRMServices', 3, null) ?>
                <?= _statisticsCard('کل حساب', number_format($data['accounting'][0]['total'] ?? 0), 'Money', 3, '') ?>
                <?= _statisticsCard('حقوقی', $data['legal'][0]['total'], 'Compare', 3, '') ?>
                <?= _statisticsCard('گذرواژه', $data['password'][0]['total'], 'Lock', 3, '') ?>
                <?= _statisticsCard('کارمندان', $data['employee'][0]['total'], 'Teamwork', 3, '') ?>
                <?= _statisticsCard('آرشیو', $data['employee'][0]['total'], 'Archive', 3, '') ?>
                <?= _statisticsCard('رتبه', $data['employee'][0]['total'], 'Medal', 3, '') ?>
                <?= _statisticsCard('دپارتمان', $data['department'][0]['total'], 'Sections', 3, '') ?>

                <div class="ms-Grid-col ms-sm3">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--Message ms-fontSize-16" aria-hidden="true"></i>
                            <span class="v-m">
                                سامانه پیامک
                            </span>
                        </div>
                        <div class="card-body text-center" style="background:#FAE5C3">
                            <i class="ms-Icon ms-Icon--Message ms-fontSize-24" aria-hidden="true"></i>
                            <br />
                            <a href="http://onlinepanel.ir/MyLogin.aspx?ReturnUrl=%2f%3fmodule%3dTicketing&module=Ticketing" target="_blank">ورود به سامانه</a>
                        </div>
                    </div>
                </div>

                <div class="ms-Grid-col ms-sm6">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--UserGauge ms-fontSize-16" aria-hidden="true"></i>
                            <span class="v-m">
                                قراردادهای رو به اتمام

                            </span>
                        </div>
                        <div class="card-body">
                            <div id="deal_chart"></div>
                        </div>
                    </div>
                </div>

                <div class="ms-Grid-col ms-sm6">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--UserGauge ms-fontSize-16" aria-hidden="true"></i>
                            <span class="v-m">مهندسین بر اساس استان و شهر</span>
                        </div>
                        <div class="card-body">
                            <div id="chart_div"></div>
                        </div>
                    </div>
                </div>

                <div class="ms-Grid-col ms-sm6">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--UserGauge ms-fontSize-16" aria-hidden="true"></i>
                            <span class="v-m">تفکیک امتیازآوری مهندسین</span>
                        </div>
                        <div class="card-body">
                            <div id="chart_div2"></div>
                        </div>
                    </div>
                </div>


                <div class="ms-Grid-col ms-sm6">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--UserGauge ms-fontSize-16" aria-hidden="true"></i>
                            <span class="v-m">حسابداری</span>
                        </div>
                        <div class="card-body">
                            <div id="chart_accounting"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows(<?= json_encode($chartData) ?>);

        // Set chart options
        var options = {
            'width': '100%',
            'height': 400,
            'legend': 'top',
            'title': 'استان ها',
            'is3D': true,
            fontName: 'Vazir',
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);





        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
            ['کل قراردادها', <?= $data['deal'][0]['total'] ?>],
            ['قراردادها رو به اتمام', <?= $data['deal_to_end'][0]['total'] ?>],
        ]);

        // Set chart options
        var options = {
            'width': '100%',
            'height': 400,
            'legend': 'top',
            'title': '',
            'is3D': true,
            fontName: 'Vazir',
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('deal_chart'));
        chart.draw(data, options);





        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows(<?= json_encode($expertType) ?>);

        // Set chart options
        var options = {
            'width': '100%',
            'height': 400,
            'legend': 'top',
            'title': '',
            'is3D': true,
            fontName: 'Vazir',
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart.draw(data, options);

        //======================================
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows(<?= json_encode($accountingChartData) ?>);
        new google.visualization.PieChart(document.getElementById('chart_accounting')).draw(data, {
            width: '100%',
            height: 400,
            legend: 'top',
            title: 'حسابداری',
            colors: ['red', 'green'],
            is3D: true,
            fontName: 'Vazir',
            legend: {
                alignment: 'center',
                position: 'right',
                textStyle: {
                    bold: true
                }
            }
        });


    }
</script>