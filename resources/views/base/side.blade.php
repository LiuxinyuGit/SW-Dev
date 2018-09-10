<nav class="navbar navbar-default top-navbar" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{route('/')}}"><strong>Sweet</strong></a>
        <div id="sideNav" href=""><i class="fa fa-caret-right"></i></div>
    </div>
</nav>
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <a {{$result['active'] == 0 ? 'class=active-menu':''}} href="{{route('/')}}"><i class="fa fa-dashboard"></i> 总览</a>
            </li>
            <li>
                <a {{$result['active'] == 1 ? 'class=active-menu':''}}  href="chart.html"><i class="fa fa-bar-chart-o"></i> 图表</a>
            </li>
            <li {{substr($result['active'],0) == 2 ? 'class=active':''}} >
                <a href="#"><i class="fa fa-cogs"></i> 材料管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a {{substr($result['active'],0) == 2 && substr($result['active'],-1) == 1 ? 'class=active-menu':''}} href="{{route('materials.lists')}}">材料清单</a>
                    </li>
                    <li>
                        <a {{substr($result['active'],0) == 2 && substr($result['active'],-1) == 2 ? 'class=active-menu':''}} href="{{route('materials.add')}}">材料{{substr($result['active'],0) == 2 && substr($result['active'],-1) == 2 ? $result['title']:'新增'}}</a>
                    </li>
                    <li>
                        <a {{substr($result['active'],0) == 2 && substr($result['active'],-1) == 3 ? 'class=active-menu':''}} href="{{route('materials.logs')}}">材料记录</a>
                    </li>
                </ul>
            </li>
            <li {{substr($result['active'],0) == 3 ? 'class=active':''}} >
                <a href="#"><i class="fa fa-gift"></i> 产品管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a {{substr($result['active'],0) == 3 && substr($result['active'],-1) == 1 ? 'class=active-menu':''}} href="{{route('goods.lists')}}">产品清单</a>
                    </li>
                    <li>
                        <a {{substr($result['active'],0) == 3 && substr($result['active'],-1) == 2 ? 'class=active-menu':''}} href="{{route('goods.add')}}">产品{{substr($result['active'],0) == 3 && substr($result['active'],-1) == 2 ? $result['title']:'新增'}}</a>
                    </li>
                    <li>
                        <a {{substr($result['active'],0) == 3 && substr($result['active'],-1) == 3 ? 'class=active-menu':''}} href="{{route('goods.logs')}}">产品记录</a>
                    </li>
                </ul>
            </li>
            <li {{substr($result['active'],0) == 4 ? 'class=active':''}} >
                <a href="#"><i class="fa fa-file-text-o"></i> 订单管理<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a {{substr($result['active'],0) == 4 && substr($result['active'],-1) == 1 ? 'class=active-menu':''}} href="{{route('orders.lists')}}">订单清单</a>
                    </li>
                    <li>
                        <a {{substr($result['active'],0) == 4 && substr($result['active'],-1) == 2 ? 'class=active-menu':''}} href="{{route('orders.add')}}">订单{{substr($result['active'],0) == 4 && substr($result['active'],-1) == 2 ? $result['title']:'新增'}}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>