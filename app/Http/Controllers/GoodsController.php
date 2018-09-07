<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Models\Goods;

class GoodsController extends Controller
{
  private $result = array('model' => '产品管理');

  //显示列表页
  public function lists(){
    $result = $this->result;
    $result['title'] = '列表';
    $result['active'] = '3-1';
    return view('goods/lists')->with('result',$result);
  }

  //获取全部产品信息
  public function getLists(){
    $lists = Goods::getLists();
    return json_encode($lists);
  }

  //新增/修改页面
  public function add(){
    $result = $this->result;
    $id = Input::get('go_id');
    if($id){
      $data = Goods::getInfo($id);
      $result['data'] = $data;
      $result['title'] = '修改';
      $result['active'] = '3-2';
      // dump($result);die;
      return view('goods/add')->with('result',$result);
    }
    $result['title'] = '新增';
    $result['active'] = '3-2';
    return view('goods/add')->with('result',$result);
  }

  //产品记录页面
  public function logs(){
    $result = $this->result;
    $result['title'] = '记录';
    $result['active'] = '3-3';
    return view('goods/logs')->with('result',$result);
  }

  //获取全部记录
  public function getLogs(){
    $logs = Goods::getLogs();
    return json_encode($logs);
  }

  //变更状态按钮
  public function chgBtn(){
    $id = Input::get('go_id');
    $result = Goods::chgBtn($id);
    return json_encode($result);
  }

  //产品补充
  public function increase(){
    $go_id = Input::get('go_id');
    $in_numb = Input::get('in_numb');
    $in_date = Input::get('in_date');
    $result = Goods::increase($go_id,$in_numb,$in_date);
    return json_encode($result);
  }

  //保存信息
  public function save(Request $request){
    $result = Goods::saveInfo($request);
    // return $request;
    return json_encode($result);
  }

  //删除信息
  public function del(){
    $go_id = Input::get('go_id');
    $result = Goods::delById($go_id);
    return json_encode($result);
  }

}
