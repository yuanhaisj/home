<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Chapter;

class ChapterController extends Controller
{
    //获取小说章节列表成功
    public function chapterList($novelId)
    {
    	$chapter = new Chapter();

    	$list = $chapter->getApiChapterList($novelId);

    	$return = [
            'code' =>2000,
            'msg'  =>"获取小说章节列表成功",
            'data' =>$list
    	];

    	return json_encode($return);
    }
}
