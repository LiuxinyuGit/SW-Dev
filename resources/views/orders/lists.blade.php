@extends('./base.base')
@section('css')
<!-- TABLE STYLES-->
<link href="/assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet"/>
<meta name="_token" content="{{ csrf_token() }}"/>
@endsection
@section('js')
<!-- DATA TABLE SCRIPTS -->
<script src="/assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/assets/plugins/dataTables/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function () {
	layer.load();
	$.ajax({
		url:"{{route('orders.getLists')}}",
		type: 'POST',
		datatype:"json",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		},
		success:function(data){
			var str = '';
			var sta = ['未完成','已完成'];
			var btn = ['warning','success']
			var area = ['福田区','罗湖区','南山区','盐田区','宝安区','龙岗区','龙华区','坪山区','光明区'];
			$.each(JSON.parse(data),function(key,obj){
				str += '<tr><input type="hidden" value="'+obj.ord_id+'"><td>'+obj.ord_scheduled_time+'</td>';
				str += '<td>'+obj.ord_client+'</td><td>'+obj.client_phone+'</td><td>';
				str += '<button type="button" class="btn btn-'+btn[obj.ord_status]+' btn-xs">'+sta[obj.ord_status]+'</button></td>';
				str += '<td>'+area[obj.ord_area]+'</td><td>'+obj.ord_address+'</td><td>'+obj.ord_amount+'</td><td>'+(obj.ord_finish_time?obj.ord_finish_time:'暂未配送')+'</td><td>';
				str += '<a class="btn btn-primary btn-xs" role="button">查看</a>&nbsp;';
				str += '<a class="btn btn-primary btn-xs" href="{{route("goods.add")}}?ord_id='+obj.ord_id+'" role="button">修改</a>&nbsp;';
				str += '<a class="btn btn-primary btn-xs" onclick="del('+obj.ord_id+')" role="button">删除</a>';
				str += '</td></tr>';
			});
			$('#main').html(str);
			$('#dataTables').dataTable();
			layer.closeAll('loading');
		},
		error: function(xhr, type){
			alert('系统异常!');
			console.log(xhr);
		}
	});
});

$('#main').on('click','.btn-warning',function(){
	var ord_id = $(this).parents('tr:first').children('input:first').val();
	layer.confirm('确定改变该商品状态？',{btn:['是','否']},function(){
    	$.get("{{route('orders.chgBtn')}}",	{ord_id:ord_id},
    	function(msg){
    	    if(msg){
    	    	layer.msg('更改成功');
    	    } else {
    	    	layer.msg('更改失败');
    	    }
    	    setTimeout("location.reload()",1000);
    	});	    
    });
});

	$('#main').on('click','tr td:last a:first',function(){
		var ord_id = $(this).parents('tr:first').children('input:first').val();
alert(1);
	});

	$('#main').on('click','tr td:last a:last',function(){
		var ord_id = $(this).parents('tr:first').children('input:first').val();
		layer.confirm('确定删除该商品？',{btn:['是','否']},function(){
    	$.post("{{route('orders.del')}}",{ord_id:ord_id,_token:"{{csrf_token()}}"},
    	function(msg){
    		console.log(msg);
    	    if(msg){
    	    	layer.msg('更改成功');
    	    } else {
    	    	layer.msg('更改失败');
    	    }
    	    // setTimeout("location.reload()",1000);
    	});	    
    });
	});
</script>
@endsection
@section('main')
<div id="page-wrapper">
	<div class="header">
		<h1 class="page-header">
			{{ $result['model'] }}
			<small>
				{{ $result['title'] }}
			</small>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="#">
					订单
				</a>
			</li>
			<li class="active">
				{{ $result['title'] }}
			</li>
		</ol>
	</div>
	<div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						订单列表
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTables">
								<thead>
									<tr>
										<th>
											预定时间
										</th>
										<th>
											收件人
										</th>
										<th>
											联系电话
										</th>
										<th>
											状态
										</th>
										<th>
											区域
										</th>
										<th>
											收件地址
										</th>
										<th>
											金额
										</th>
										<th>
											配送时间
										</th>
										<th>
											操作
										</th>
									</tr>
								</thead>
								<tbody id="main"></tbody>
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
@endsection
