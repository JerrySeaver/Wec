    @extends('public.header')
    <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
        <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeInRight">
        
            <div class="row">
                <div class="col-sm-12">
                
                    <div class="ibox float-e-margins">
<div class="ajax">
                        <div class="ibox-content">
                            <table class="table table-striped table-bordered table-hover " id="editable">
                            <!-- <input type="text" class="WeaId"> -->
                                <!-- 图表容器 DOM -->
                                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                            </table>
                        </div>
                   
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- 引入 highcharts.js -->
        
    <script>
        // 图表配置
        var chart = Highcharts.chart('container', {
            chart: {
                type: 'columnrange', // columnrange 依赖 highcharts-more.js
                inverted: true
            },
            title: {
                text: '北京一周实时天气'
            },
            subtitle: {
                text: '实时天气'
            },
            xAxis: {
                categories: [<?php echo $week ?>]
            },
            yAxis: {
                title: {
                    text: '温度 ( °C )'
                }
            },
            tooltip: {
                valueSuffix: '°C'
            },
            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '°C';
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '温度',
                data: [
                    @foreach($temp as $k => $v)
                    [{{$v}}],
                    @endforeach
                    
                ]
            }]
        });
    </script>
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script>
        $(function(){
            $('.WeaId').blur(function(){
                var WeaId=$(this).val();
                if(WeaId=="")
                {
                    WeaId="北京";
                }
                $.post(
                    "/weather", 
                    { WeaId:WeaId},
                    function(res){
                        $('.ajax').empty();
                        $('.ajax').append(res);
                    });
            })
        })
    </script>
 </div>
 