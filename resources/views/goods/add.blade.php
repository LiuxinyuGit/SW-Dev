@extends('./base.base')

@section('js')
<script>
  $('#submit').on('click',function(){
    $.ajax({
      url:"{{route('goods.save')}}",
      type:"POST",
      datatype:"JSON",
      data:$('form').serialize(),
      success:function(data){
        // console.log(JSON.parse(data));
        if(JSON.parse(data)){
          layer.msg('操作成功!');
        } else {
          layer.msg('操作失败,请稍后再试!');
        }
        window.setTimeout("window.location.href=\"{{route('goods.lists')}}\"",1000);
      },
      error: function(xhr, type){
        alert('Ajax error!');
      }
    });
  });
</script>
@endsection


@section('main')
<div id="page-wrapper" >
  <div class="header">
    <h1 class="page-header">{{$result['model']}}<small>{{$result['title']}}</small></h1>
    <ol class="breadcrumb">
      <li><a href="{{route('goods.lists')}}">产品</a></li>
      <li class="active">{{$result['title']}}</li>
    </ol>
  </div>

  <div id="page-inner">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">基本信息</div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-6">
                <form role="form">
                  {{csrf_field()}}
                  <input type="hidden" name="go_id" value="{{isset($result['data']->go_id) ? $result['data']->go_id:''}}">
                  <div class="form-group input-group">
                    <span class="input-group-addon">产品名称</span>
                    <input type="text" class="form-control" name="go_name" value="{{isset($result['data']->go_name) ? $result['data']->go_name:''}}">
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group input-group">
                        <span class="input-group-addon">产品数量</span>
                        <input type="text" class="form-control" name="go_number" value="{{isset($result['data']->go_number) ? $result['data']->go_number:''}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group input-group">
                        <span class="input-group-addon">产品单价¥</span>
                        <input type="text" class="form-control" name="go_price" value="{{isset($result['data']->go_price) ? $result['data']->go_price:''}}">
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-default" id="submit">提交</button>
                  <button type="reset" class="btn btn-default">重置</button>
                </form>
              </div>
              <!-- /.col-lg-6 (nested) -->
            </div>
            <!-- /.row (nested) -->
          </div>
          <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
      </div>
      <!-- /.col-lg-12 -->
    </div>
  </div>
  <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
@endsection
