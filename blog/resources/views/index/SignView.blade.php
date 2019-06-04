@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
    
        <div class="row">
            <div class="col-sm-12">
            
                <div class="ibox float-e-margins">
                
                    <div class="ibox-content">
   
                        <table class="table table-striped table-bordered table-hover " id="editable">
                            <thead>
                            <tr>
                                    <th>用户名</th>
                                    <th>连续签到天数</th>
                                    <th>积分</th>
                                </tr>
                                <tr>
                                    <th>{{$username['username']}}</th>
                                    <th>{{$username['sing_number']}}</th>
                                    <th>{{$username['integral']}}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                
                            </tfoot>   
                    </div>
                    
                        </table>
                    </div>
                    <div align="center">
                            <button class="btn btn-sm btn-info">签到</button>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script>
        $(function(){
           $(".btn").click(function(){
                $.post(
                    '/Sign', 
                    function(res){
                        alert(res);
                });
               return false;
           })
        })
    </script>
    
 