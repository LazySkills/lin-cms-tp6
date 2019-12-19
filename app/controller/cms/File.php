<?php

namespace app\controller\cms;

use app\annotation\{
    Doc
};
use app\common\cms\FileUploader;
use app\exception\cms\LinFileException;
use think\annotation\route\{
    Group,Route
};

/**
 * Class File 文件管理
 * @Group("cms/file")
 * @package app\controller
 */
class File
{

    /**
     * @Route(value="",method="POST")
     * @Doc(value="文件上传",group="管理.文件",hide="false")
     */
    public function uploader()
    {
        try{
            $files = request()->file();
        }catch (\Exception $exception){
            throw new LinFileException();
        }
        $result = (new FileUploader($files))->upload();
        return json($result,200);
    }


}
