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
      url:"{{route('goods.getLists')}}",
      type: 'POST',
      datatype:"json",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success:function(data){
        var str = '';
        var arr = ['在售','停售'];
        var btn = ['success','warning'];
        $.each(JSON.parse(data),function(key,obj){
          str += '<tr><input type="hidden" value="'+obj.go_id+'"><td>'+obj.go_name+'</td>';
          str += '<td>'+obj.go_number+'</td><td>¥'+obj.go_price+'</td><td>';
          str += '<button type="button" class="btn btn-'+btn[obj.go_status]+' btn-xs" onclick="chgBtn(this)">'+arr[obj.go_status]+'</button></td><td>';
          str += '<a class="btn btn-primary btn-xs" onclick="increase('+obj.go_id+',this)" role="button">补充</a>&nbsp;';
          str += '<a class="btn btn-primary btn-xs" href="{{route("goods.add")}}?go_id='+obj.go_id+'" role="button">修改</a>&nbsp;';
          str += '<a class="btn btn-primary btn-xs" onclick="del('+obj.go_id+')" role="button">删除</a>';
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

  function chgBtn(obj){
    var go_id = $(obj).closest('tr').children('input:first').val();
    layer.confirm('变更当前状态?',['取消','确定'],function(){
      $.ajax({
        url:"{{route('goods.chgBtn')}}",
        type:"POST",
        datatype:"JSON",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        data:{
          go_id:go_id
        },
        success:function(data){
          if(JSON.parse(data)){
            layer.msg('更改成功!');
          } else {
            layer.msg('更改失败,请稍后再试!');
          }
          window.setTimeout("location.reload()",500);
        },
        error: function(xhr, type){
          alert('Ajax error!');
        }
      });
    });
  }

  function increase(go_id,obj){
    var go_name =$(obj).closest('tr').children('td:first').html();
    $('.modal-title').html('对'+go_name+'进行补充');
    $('input[name=go_id]').val(go_id);
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
      url:"{{route('goods.increase')}}",
      type:"POST",
      datatype:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      data:{
        go_id:$('input[name=go_id]').val(),
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
        url:"{{route('goods.del')}}",
        type:"POST",
        datatype:"JSON",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        data:{
          go_id:id
        },
        success:function(data){
          // console.log(JSON.parse(data));
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
      <li><a href="#">产品</a></li>
      <li class="active">{{$result['title']}}</li>
    </ol>
  </div>
  <div id="page-inner">
    <div class="row">
      <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
          <div class="panel-heading">材料列表</div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables">
                <thead>
                  <tr>
                    <th>名称</th>
                    <th>库存数量</th>
                    <th>单价</th>
                    <th>状态</th>
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
          <input name="go_id" type="hidden">
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
