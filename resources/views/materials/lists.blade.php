@extends('./base.base')

@section('css')
<!-- TABLE STYLES-->
<link href="/assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
<!-- DATE PICKER -->
<link href="/assets/plugins/Datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<meta name="_token" content="{{ csrf_token() }}"/>
@endsection

@section('js')
<!-- DATA TABLE SCRIPTS -->
<script src="/assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/assets/plugins/dataTables/dataTables.bootstrap.js"></script>
<!-- DATE PICKER -->
<script src="/assets/plugins/Datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script>
  $(document).ready(function () {
    layer.load();
    $.ajax({
      url:"{{route('materials.getLists')}}",
      type: 'POST',
      datatype:"json",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success:function(data){
        var str = '';
        $.each(JSON.parse(data),function(key,obj){
          str += '<tr><td>'+obj.ma_name+'</td><td>'+obj.ma_number+'</td>';
          str += '<td>¥'+obj.ma_price+'</td><td>'+obj.ma_last_date+'</td><td>';
          str += '<input type="hidden" value="'+obj.ma_id+'">';
          str += '<a class="btn btn-primary btn-xs" onclick="increase('+obj.ma_id+',this)" role="button">补充</a>&nbsp;';
          str += '<a class="btn btn-primary btn-xs" href="{{route("materials.add")}}?ma_id='+obj.ma_id+'" role="button">修改</a>&nbsp;';
          str += '<a class="btn btn-primary btn-xs" onclick="del('+obj.ma_id+')" role="button">删除</a>';
          str += '</td></tr>';
        });
        $('#main').html(str);
        $('#dataTables').dataTable();
        layer.closeAll('loading');
      },
      error: function(xhr, type){
        alert('Ajax error!');
      }
    });
  });

  function increase(ma_id,obj){
    var ma_name =$(obj).closest('tr').children('td:first').html();
    $('.modal-title').html('对'+ma_name+'进行补充');
    $('input[name=ma_id]').val(ma_id);
    $('#in_date').datetimepicker({
      format:"yyyy-mm-dd",
      minView:2,
      autoclose:true,
      todayBtn:true,
      startDate:"2018-01-01"
    });
    $('#myModal').modal();
  }
  function postIncrease(){
    $.ajax({
      url:"{{route('materials.increase')}}",
      type:"POST",
      datatype:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      data:{
        ma_id:$('input[name=ma_id]').val(),
        in_numb:$('#in_numb').val(),
        in_date:$('#in_date').val()
      },
      success:function(data){
        // console.log(data);
        if(JSON.parse(data)){
          layer.msg('操作成功!');
        } else {
          layer.msg('操作失败,请稍后再试!');
        }
        window.setTimeout("location.reload()",1000);
      },
      error: function(xhr, type){
        console.log(xhr);
      }
    });
  }

  function del(id){
    layer.confirm('确认删除该条信息?',['取消','确定'],function(){
      $.ajax({
        url:"{{route('materials.del')}}",
        type:"POST",
        datatype:"JSON",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        data:{
          ma_id:id
        },
        success:function(data){
          if(JSON.parse(data)){
            layer.msg('删除成功!');  
          } else {
            layer.msg('删除失败,请稍后再试!');  
          }
          window.setTimeout("location.reload()",1000);
        },
        error: function(xhr, type){
          alert('Ajax error!');
        }
      });
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
  <div id="page-inner">
    <div class="row">
      <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
          <div class="panel-heading">
           材料列表
         </div>
         <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables">
              <thead>
                <tr>
                  <th>材料名称</th>
                  <th>库存数量</th>
                  <th>材料单价</th>
                  <th>上次补货时间</th>
                  <th>操作</th>
                </tr>
              </thead>

              <tbody id="main">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!--End Advanced Tables -->
    </div>
  </div>
</div>
<!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="container" style="width: inherit !important;">
          <input name="ma_id" type="hidden">
          <div class="row">
            <div class="col-xs-6">
              <div class="input-group input-group-sm">
                <span class="input-group-addon">补充数量:</span>
                <input id="in_numb" name="number" type="text" class="form-control">
              </div>
            </div>
            <div class="col-xs-6">
              <div class="input-group input-group-sm">
                <span class="input-group-addon">补充时间:</span>
                <input id="in_date" name="date" type="text" class="form-control" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" onclick="postIncrease()">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal -->
</div>
<!-- /.Modal -->
@endsection