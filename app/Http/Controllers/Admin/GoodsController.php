<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Brand;
use App\Model\GoodsType;
use App\Model\Goods;
use App\Model\GoodsGallery;
use App\Tools\ToolsAdmin;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    //列表页面
    public function list()
    {
    	return view('admin.goods.list');
    }
    
    //获取列表数据的接口
    public function getGoodsData(Request $request)
    {
    	$params = $request->all();

    	$return = [
    	    'code' => 2000,
    	    'msg'  => '获取商品列表数据'
    	];

    	$goods = new Goods();

    	$data = $this->getPageList($goods)->toArray();
        // dd($data);
        $return['data'] = [
            'list' => $data['data'],
            'current_page' =>$data['current_page'],
            'total_page'   =>$data['last_page']
        ];

        return json_encode($return);
    }
    
    //修改属性值
    public function changeAttr(Request $request)
    {
    	$params = $request->all();

    	$return = [
    	    'code' => 2000,
    	    'msg'  => '修改商品属性成功'
    	];

    	$goods =Goods::find($params['id']);
        
        //组装的数据
    	$data = [
    	    $params['key'] => $params['val']
    	];

    	$res = $this->storeData($goods,$data);

    	if(!$res){
    		$return = [
    	    'code' => 4000,
    	    'msg'  => '修改商品属性失败'
    	    ];
    	}

    	return json_encode($return);
    }
    
    //添加页面
    public function add()
    {
    	//商品类型
    	$type = new GoodsType();

    	$assign['type_list'] = $this->getDataList($type,['status'=> GoodsType::USE_ABLE]);
    	//商品品牌
    	$brand = new Brand();

    	$assign['brand_list'] = $this->getDataList($brand,['status'=>Brand::USE_ABLE]);
    	//商品分类
    	$category = new Category();
       
    	$assign['cate_list'] = $this->getDataList($category,['status'=>Category::USE_ABLE]);
    	$assign['cate_list'] = ToolsAdmin::buildTreeString($assign['cate_list'],0,0,'f_id');

    	//商品货号
    	$assign['goods_sn'] = ToolsAdmin::buildGoodsSn();
    	return view('admin.goods.add',$assign);
    }
    
    //执行添加的操作
    public function store(Request $request)
    {
    	$params = $request->all();
        
        //上传图片的上限
    	if(isset($params['gallery']) && count($params['gallery'])>5){
    		return redirect()->back()->msg('msg','已经超过相册上传的上限');
    	}

    	// dd($params);
    	$params = $this->delToken($params);
        
        //拿到相册的数据
    	$gallery = $params['gallery'];
        unset($params['gallery']);
    	try{
    		//事务开启
    		DB::beginTransaction();

    		//添加商品信息
    		$goods = new Goods();
    		$goodsId = $this->storeDataGetId($goods,$params);
    		
    		//添加相册信息
    		// dd($gallery);
    		//1 格式化相册的数据
    		$gallery_data = [];//初始化的值
    		foreach($gallery as $key => $value){
    			//判断是否上传了图片
    			if(array_key_exists('image_url',$value)){
    				$value['image_url'] = ToolsAdmin::uploadFile($value['image_url']);//上传后图片地址
    			
    			    $value['goods_id'] = $params['id'];
    			    $gallery_data[$key] = $value;//组装新的数据
    			}    			
    			
    		}
    		// dd($gallery_data);
    		// 2.执行添加的操作
    		if(!empty($gallery_data)){
    			$goodsGallery = new GoodsGallery();
    		    $this->storeDataMany($goodsGallery,$gallery_data);//执行多条添加操作
    		}

    		//事务提交
    		DB::commit();
    	}catch(\Exception $e){
    		DB::rollback();
            
            \Log::error('商品添加失败'.$e->getMessage());
    		return redirect()->back()->with('msg','商品添加失败');
    	}

    	return redirect('/admin/goods/list');
    }
    
    //编辑页面
    public function edit($id)
    {
    	//商品类型
    	$type = new GoodsType();

    	$assign['type_list'] = $this->getDataList($type,['status'=> GoodsType::USE_ABLE]);
    	//商品品牌
    	$brand = new Brand();

    	$assign['brand_list'] = $this->getDataList($brand,['status'=>Brand::USE_ABLE]);
    	//商品分类
    	$category = new Category();
       
    	$assign['cate_list'] = $this->getDataList($category,['status'=>Category::USE_ABLE]);
    	$assign['cate_list'] = ToolsAdmin::buildTreeString($assign['cate_list'],0,0,'f_id');
        
        $goods = new Goods();
    	$assign['info'] = $this->getDataInfo($goods,$id);
    	return view('admin.goods.edit',$assign);
    }
    
    //执行编辑
    public function doEdit(Request $request)
    {
    	$params = $request->all();
        // dd($params);
    	//上传图片的上限
    	if(isset($params['gallery']) && count($params['gallery'])>5){
    		return redirect()->back()->msg('msg','已经超过相册上传的上限');
    	}

    	// dd($params);
    	$params = $this->delToken($params);
        
        //拿到相册的数据
    	$gallery = $params['gallery'];
        unset($params['gallery']);
    	try{
    		//事务开启
    		DB::beginTransaction();

    		//添加商品信息
    		$goods = Goods::find($params['id']);
    		$this->storeData($goods,$params);
    		
    		//添加相册信息
    		// dd($gallery);
    		// array_key_exists('image_url', $gallery);
    		//1 格式化相册的数据
    		$gallery_data = [];//初始化的值
    		foreach($gallery as $key => $value){
    			//判断是否上传了图片
    			if(array_key_exists('image_url',$value)){
    				$value['image_url'] = ToolsAdmin::uploadFile($value['image_url']);//上传后图片地址
    			
    			    $value['goods_id'] = $params['id'];
    			    $gallery_data[$key] = $value;//组装新的数据
    			}    			
    			
    		}
    		// dd($gallery_data);
    		// 2.执行添加的操作
    		if(!empty($gallery_data)){
    			$goodsGallery = new GoodsGallery();
    		    $this->storeDataMany($goodsGallery,$gallery_data);//执行多条添加操作
    		}
    		
    		//事务提交
    		DB::commit();
    	}catch(\Exception $e){
    		DB::rollback();
            
            \Log::error('商品添加失败'.$e->getMessage());
    		return redirect()->back()->with('msg','商品添加失败');
    	}

    	return redirect('/admin/goods/list');
    }
    
    //删除商品
    public function del($id)
    {
    	$return = [
    	    'code' => 2000,
    	    'msg'  => '删除商品成功'
    	];

    	$goods = new Goods();
    	$gallery = new GoodsGallery();

    	try{
    		DB::beginTransaction();

            //删除商品
            $this->delData($goods,$id);
            //删除相册
            $this->delData($gallery,$id,'goods_id');

    		Db::commit();
    		return json_encode($return);
    	}catch(\Exception $e){
    		DB::rollback();

    		\Log::error('商品删除失败'.$e->getMessage());

    		$return = [
	    	    'code' => $e->getCode(),
	    	    'msg'  => $e->getMessage()
	    	    ];
    	}

    	return json_encode($return);
    	
    }
}
