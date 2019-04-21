<?php

namespace App\Tools;

use Excel;
/**
 * Excel 文件批量导入导出的功能
 */
class ToolsExcel
{
	//excel文件的导入的功能
	public static function import($files)
	{
		// dd($files);
		if(empty($files)){
			return false;
		}

		$data = Excel::load($files->path(),function($reader){

		},'UTF-8')->toArray();
		// dd($data);
		return $data;
	}

	//文件导出的操作
	public static function exportData($exportData,$title="商品数据")
	{
		if(empty($exportData)){
			return false;
		}

		/**
		 * 如果你要导出csv或者xlsx文件，只需将export方法中的参数改成csv或xlsx即可
		 * 如果还要将该Excel文件保存到服务器上，可以使用store方法
		 */
		Excel::create(iconv('UTF-8','GBK',date("YmdHis").$title),function($excel) use ($exportData){
			$excel->sheet('goods',function($sheet) use ($exportData){
				$sheet->rows($exportData);
			});
		})->export('xls');
	}
}