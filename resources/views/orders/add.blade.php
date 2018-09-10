@extends('./base.base')
@section('css')
<!-- DATE PICKER -->
<link href="/assets/plugins/Datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="/assets/css/style.css" rel="stylesheet"/>
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
				<a href="{{ route('orders.lists') }}">
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
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						基本信息
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<form role="form">
									{{ csrf_field() }}
									<input type="hidden" name="ord_amount"/>
									<input type="hidden" name="ord_content"/>
									<input type="hidden" name="ord_id" value="{{ isset($result['data']->ord_id) ? $result['data']->ord_id:'' }}">
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group input-group">
												<span class="input-group-addon">
													收件人
												</span>
												<input type="text" class="form-control" name="ord_client" value="{{ isset($result['data']->ord_client) ? $result['data']->ord_client:'' }}">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group input-group">
												<span class="input-group-addon">
													联系电话
												</span>
												<input type="text" class="form-control" name="client_phone" value="{{ isset($result['data']->client_phone) ? $result['data']->client_phone:'' }}">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group input-group">
												<span class="input-group-addon">
													收货时间
												</span>
												<input type="text" class="form-control" name="ord_scheduled_time" value="{{ isset($result['data']->ord_scheduled_time) ? $result['data']->ord_scheduled_time:'' }}" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group input-group">
												<span class="input-group-addon">
													所在区域
												</span>
												<select class="form-control" name="ord_area">
													<option value="">请选择</option>
													@foreach($area as $k =>	$v)
													<option value="{{ $k }}" @if($k == (isset($result['data']->ord_area)?$result['data']->ord_area:'') ) selected @endif >
														{{ $v }}
													</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-lg-8">
											<div class="form-group input-group">
												<span class="input-group-addon">
													详细地址
												</span>
												<input type="text" class="form-control" name="ord_address" oninput="listen()" value="{{ isset($result['data']->ord_address) ? $result['data']->ord_address:'' }}">
											</div>
										</div>
									</div>
									<div @if(isset($result['data']->ord_id)) style="display:none;" @endif >
										<div id="map"></div>
									<div id="selectTitle" class="list-select">
										<div class="list-title">
											选择商品
										</div>
										<div class="list-body">
											<div class="item-box left-box">
												<!-- 左边框初始化待选项 -->
												<ul class="item-list">
													@foreach($goods as $value)
													<li class="item" data-id="{{ $value->go_id }}" data-price="{{ $value->go_price }}" data-name="{{ $value->go_name }}">
														{{ $value->go_name }}
														<label>
															（剩余：{{ $value->go_number }}）
														</label>
														<div class="item-act">
															<div class="item-input input-group input-group-sm">
																<span class="input-group-addon minus">
																	<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
																</span>
																<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="0"/>
																<span class="input-group-addon plus">
																	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
																</span>
															</div>
															<h6>
																<span class="glyphicon glyphicon-yen" aria-hidden="true"></span>
																<small>
																	{{ $value->go_price }}
																</small>
															</h6>
														</div>
													</li>
													@endforeach
												</ul>
											</div>
											<div class="center-box">
												<button type="button" class="add">
													确认
												</button>
												<button type="button" class="remove-all">
													清空
												</button>
											</div>
											<div class="item-box right-box">
												<!-- 右边框存放已选项 -->
											</div>
										</div>
									</div>
									</div>

									<button type="button" id="submit" class="btn btn-default">
										提交
									</button>
									<button type="reset" id="reset" class="btn btn-default">
										重置
									</button>
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

@section('js')
<!-- DATE PICKER -->
<script src="/assets/plugins/Datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="http://api.map.baidu.com/api?v=2.0&ak=H5RHpSKVlBw3fTq4WOYdhMGGHxiDQG7f"></script>
<script>
//百度地图API
var map = new BMap.Map("map");
var point = new BMap.Point(114.063402, 22.54899);

//监听器标记
var sign = 0;

//地址更新前的输入值
var oldval = '';

//订单内容
var order = '';
//订单金额
var SumPrice = 0;

window.onload = function() {
    map.centerAndZoom(point, 13);
    map.enableScrollWheelZoom(); //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom(); //启用地图惯性拖拽，默认禁用
}

function listen() {
    var val = $('input[name=ord_address]').val();
    if (val == oldval) {
        clearInterval(sign);
        getMap();
    } else {
        oldval = val;
        clearInterval(sign);
        sign = setInterval("listen();", 1500);
    }
}

function getMap() {
    // 创建地址解析器实例
    var myGeo = new BMap.Geocoder();
    // 将地址解析结果显示在地图上,并调整地图视野
    myGeo.getPoint(oldval, function(newPoint) {
        if (newPoint) {
            map.centerAndZoom(newPoint, 14);
            map.clearOverlays();
            map.addOverlay(new BMap.Marker(newPoint));
        } else {
    	    layer.msg('未匹配到该地址');
    	    map.centerAndZoom(point, 13);
        }
    }, "深圳市");
}
</script>
<script>
	$(document).ready(function(){
		$('input[name=ord_scheduled_time]').datetimepicker({
			format:"yyyy-mm-dd",
			minView:2,
			autoclose:true,
			todayBtn:true,
			startDate:"2018-01-01"
		});
	});
	$('#submit').on('click',function(){
		if(order == '' && $('input[name=ord_id]').val() == ''){
            layer.msg('确认商品了吗..');
			return;
		}
		$('input[name=ord_content]').val(order);
		$('input[name=ord_amount]').val(SumPrice);
		$.ajax({
			url:"{{route('orders.save')}}",
			type:"POST",
			datatype:"JSON",
			data:$('form').serialize(),
			success:function(data){
				if(JSON.parse(data)){
				  layer.msg('操作成功!');
				} else {
				  layer.msg('操作失败..');
				}
				window.setTimeout("window.location.href=\"{{route('orders.lists')}}\"",1000);
			},
			error: function(xhr, type){
				alert('Ajax error!');
				console.log(xhr);
			}
		});
	});

    $('#reset').on('click', function(){
        $('.right-box').html('');
        order = '';
    });

	$('.plus').on('click',function(){
		var ipt = $(this).prev();
		ipt.val(Number(ipt.val())+1);
	});

	$('.minus').on('click',function(){
		var ipt = $(this).next();
		var val = Number(ipt.val())-1;
		ipt.val((val>0)?val:0);
	});

	$('.add').click(function(){
		var list = [];
		SumPrice = 0;
		var html = '<p>已选商品：</p><div><ul class="item-list">';
		$('.item-list input').each(function(){
			var num = $(this).val();
			if(num != 0){
				var id = $(this).parents('li:first').attr('data-id');
				var price = $(this).parents('li:first').attr('data-price');
				var name = $(this).parents('li:first').attr('data-name');
				html += '<li class="item">'+name+'<span><label class="list-label">'+num+'&nbsp;份</label>';
				html += '<label class="list-label">'+(num*price).toFixed(2)+'<span class="glyphicon glyphicon-yen" aria-hidden="true"></span></label></span></li>';
				list.push(id + ':' + num);
				SumPrice = Number(SumPrice) + num*price;
			}
		});
		html += '</ul></div><p>订单金额：<span class="glyphicon glyphicon-yen" aria-hidden="true"></span>'+SumPrice.toFixed(2)+'</p>';
		$('.right-box').html(html);

		order = list.join('|');
	});

	$('.remove-all').on('click', function(){
        $('.right-box').html('');
        $('.item-list input').each(function(){
            $(this).val(0);
        });
        order = '';
	});
</script>
@endsection
