@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
    
        <div class="row">
            <div class="col-sm-12">
            
                <div class="ibox float-e-margins">
                
                    <div class="ibox-content">
                            <a href="/IsQrCode">
                                <button class="btn btn-sm btn-primary" type="submit" class="image">返回图文</button>
                            </a>  
                        <table class="table table-striped table-bordered table-hover " id="editable">
                            <!-- 图表容器 DOM -->
    <div id="container" style="width: 600px;height:400px;"></div>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- 引入 highcharts.js -->
        <script src="http://cdn.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script>
        // 图表配置
        var options = {
            chart: {
                type: 'bar'                          //指定图表的类型，默认是折线图（line）
            },
            title: {
                text: '推广统计'                 // 标题
            },
            xAxis: {
                categories: [<?php echo $dataStr ?>]   // x 轴分类
            },
            yAxis: {
                title: {
                    text: '关注人数'                // y 轴标题
                }
            },
            series: [{                              // 数据列
                name: '推广',                        // 数据列名
                data:  [{{$dataInt}}]                    // 数据
            }]
        };
        // 图表初始化函数
        var chart = Highcharts.chart('container', options);
    </script>
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script>
        $(function(){
            $('.image').click(function(){
                alert(123);
                return false;
            })
        })
    </script>
    
 