<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
  private $result = array('model' => 'SWEET');

  public function lists(){
    $result = $this->result;
    $result['active'] = 0;
    $result['title'] = '首页';
  	return view('index/index')->with('result',$result);
  }
}
