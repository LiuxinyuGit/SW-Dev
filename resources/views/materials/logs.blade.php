@extends('./base.base')

@section('css')
    <meta name="_token" content="{{ csrf_token() }}"/>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // layer.load();
            getLogs();
        });

        function getLogs(){
            $.ajax({
                url:"{{route('materials.getLogs')}}",
                type: 'POST',
                datatype:"json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success:function(data){
                    // console.log(data);
                    var type = ['补充','修改','使用','删除'];
                    var str = '';
                    $.each(JSON.parse(data),function(key,obj){
                        str += '<tr><td>'+obj.ma_name+'</td><td>'+type[obj.sign]+'</td><td>'+obj.number+'</td>';
                        str += '<td>'+obj.residue+'</td><td>¥'+obj.ori_price+'</td><td>¥'+obj.ma_price+'</td><td>'+obj.date+'</td></tr>';
                    });
                    $('#main').html(str);
                    // layer.closeAll('loading');
                },
                error: function(xhr, type){
                    alert('Ajax error!');
                }
            });
        }
    </script>
@endsection

@section('main')
    <div id="page-wrapper" >
        <div class="header">
            <h1 class="page-header">{{$result['model']}}<small>{{$result['title']}}</small></h1>
            <ol class="breadcrumb">
                <li><a href="#">材料</a></li>
                <li class="active">{{$result['title']}}</li>
            </ol>
        </div>
        <div id="page-inner" style="padding: 0 0 0 30px !important;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>材料名称</th>
                            <th>操作</th>
                            <th>变更数量</th>
                            <th>剩余数量</th>
                            <th>材料原价</th>
                            <th>材料单价</th>
                            <th>操作时间</th>
                        </tr>
                    </thead>
                    <tbody id="main">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
@endsection