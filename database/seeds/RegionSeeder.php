<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 地区填充的数据
     * @return void
     */
    public function run()
    {
        //
        $database = file_get_contents(base_path('database/seeds')."/region.sql");//file_get_contents() 函数把整个文件读入一个字符串中。base_path()根路径

        DB::connection()->getPdo()->exec($database);//链接数据库
    }
}
