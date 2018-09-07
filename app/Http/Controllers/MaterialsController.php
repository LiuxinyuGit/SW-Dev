<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Models\Materials;

class MaterialsController extends Controller
{
    private $result  = array('model' => '材料管理');

    //显示列表页
    public function lists(){
        $result = $this->result;
        $result['title'] = '列表';
        $result['active'] = '2-1';
    	return view('materials/lists')->with('result',$result);
    }

    //新增/修改页面
    public function add(){
        $result = $this->result;
        $id = Input::get('ma_id');
        if($id){
            $data = Materials::getInfo($id);
            $result['data'] = $data;
            $result['title'] = '修改';
            $result['active'] = '2-2';
            return view('materials/add')->with('result',$result);
        }
        $result['title'] = '新增';
        $result['active'] = '2-2';
        return view('materials/add')->with('result',$result);
    }

    //材料记录页面
    public function logs(){
        $result = $this->result;
        $result['title'] = '记录';
        $result['active'] = '2-3';
        return view('materials/logs')->with('result',$result);
    }

    //获取全部记录
    public function getLogs(){
        $logs = Materials::getLogs();
        return json_encode($logs);
    }

    //获取全部材料信息
    public function getLists(){
        $lists = Materials::getLists();
        return json_encode($lists);
    }

    //材料补充
    public function increase(){
        $ma_id = Input::get('ma_id');
        $in_numb = Input::get('in_numb');
        $in_date = Input::get('in_date');
        $result = Materials::increase($ma_id,$in_numb,$in_date);
        return json_encode($result);
    }

    //保存信息
    public function save(Request $request){
        $result = Materials::saveInfo($request);
        return json_encode($result);
    }

    //删除信息
    public function del(){
        $ma_id = Input::get('ma_id');
        $result = Materials::delById($ma_id);
        return json_encode($result);
    }
}
