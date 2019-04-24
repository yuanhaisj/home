<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdPosition;
use App\Model\Ad;
use App\Tools\toolsAdmin;
use App\Tools\ToolsOss;
use Excel;

use OSS\OssClient;
use OSS\Core\OssException;

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
        // dd(\Config::get('oss'));
    	$assign['list'] = $this->ad->getAdList();

        $oss = new ToolsOss();
        //处理图片对象
        foreach ($assign['list'] as $key => $value) {
            $value['image_url'] = $oss->getUrl($value['image_url'],true);

            $assign['list'][$key] = $value;
        }
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

        // $oss = new ToolsOss();

        // $filePath = $oss->putFile($params['image_url']);

        // $path = $oss->getUrl($filePath,true);

        // dd($path);

        //oss文件上传测试
        $files = $params['image_url'];
        // dd($files);        
        Excel::load($files->path(),function($reader){
            $data = $reader->all()->toArray();
            // dd($data);
        }); 

        // // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
        // $accessKeyId = "LTAIycQaP0Kpx3H7";
        // $accessKeySecret = "ZpyyfaudibLijBhwrtBA3iFsQsZ64L";
        // // Endpoint以杭州为例，其它Region请按实际情况填写。
        // $endpoint = "http://oss-cn-beijing.aliyuncs.com";
        // // 存储空间名称
        // $bucket ="yh871182693";
        // // 文件名称
        // $object = "uploads/".date('Y-m-d')."/".date("YmdHis",time()).rand(0,10000).".".$files->extension();
        // // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
        // $filePath = $files->path();//上传的临时文件

        // try{
        //     $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

        //     $ossClient->uploadFile($bucket, $object, $filePath);
        // } catch(OssException $e) { 
        //     printf(__FUNCTION__ . ": FAILED\n");
        //     printf($e->getMessage() . "\n");
        //     return;
        // }
        // dd('success');


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
