<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdPosition;
use App\Model\Ad;
use App\Tools\toolsAdmin;

class AdController extends Controller
{
	protected $position = null;
	protected $ad = null;

	public function __construct()
	{
		$this->position = new AdPosition();
		$this->ad =new Ad();
	}
    //广告列表页面
    public function list()
    {
    	$assign['list'] = $this->ad->getAdList();
    	return view('admin.ad.list',$assign);
    }

    //添加页面
    public function add()
    {
    	$assign['position'] = $this->position->getList();//获取广告位列表

    	return view('admin.ad.add',$assign);
    }
    
    //执行添加的操作
    public function store(Request $request)
    {
    	$params = $request->all();
    	// dd($params);
    	if(!isset($params['image_url']) || empty($params['image_url'])){
    		return redirect()->back()->with('msg','请先上传图片');
    	}

    	$params['image_url'] = ToolsAdmin::uploadFile($params['image_url']);

    	$params = $this->delToken($params);

    	$ad = new Ad();

    	$res = $this->storeData($ad,$params);

    	if(!$res){
    		return redirect()->back()->with('msg','添加广告失败');
    	}

    	return redirect('/admin/ad/list');
    }
    
    //编辑页面
    public function edit($id)
    {
    	$assign['position'] = $this->position->getList();//获取广告位列表
    	$ad =new Ad();
    	$assign['info'] = $this->getDataInfo($ad,$id);
    	return view('admin.ad.edit',$assign);
    }
    
    //执行编辑的过程
    public function doEdit(Request $request)
    {
    	$params = $request->all();
        // dd($params);
        //只有当图片上传选中的时候才上传图片
    	if(isset($params['image_url']) && !empty($params['image_url'])){

    		$params['image_url'] = ToolsAdmin::uploadFile($params['image_url']);
    	}

    	$params = $this->delToken($params);

    	$ad = Ad::find($params['id']);//先查询出来对象
        // dd($ad);
    	$res = $this->storeData($ad,$params);
        // dd($res);
    	if(!$res){
    		return redirect()->back()->with('msg','修改广告失败');
    	}

    	return redirect('/admin/ad/list');
  	
    }
    
    //删除
    public function del($id)
    {
    	$ad = new Ad();

    	$res = $this->delData($ad,$id);

    	return redirect('/admin/ad/list');
    }
}
