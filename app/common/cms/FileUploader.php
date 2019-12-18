<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-18  */

namespace app\common\cms;



use app\exception\cms\LinFileException;
use think\file\UploadedFile;

class FileUploader
{
    protected $files = [];

    public function __construct(array $files)
    {
        if (empty($files)){
            throw new LinFileException("未找到符合条件的文件资源");
        }
        if (count($files) < config('lincms.file.nums')){
            throw new LinFileException("文件数量过多");
        }
        $this->files = $files;
    }

    public function upload()
    {

    }

    protected function verify(UploadedFile $file)
    {
        $type = $file->getType();
        if (in_array($type,config('lincms.file.image_type'))){
            $rule = config('lincms.file.validate.image');
        }elseif (in_array($type,config('lincms.file.video_type'))){
            $rule = config('lincms.file.validate.video');
        }else{
            throw new LinFileException('暂不支持的文件类型：'.$type);
        }
        if(empty($rule)) return;
        try {
            validate($rule)->check([$file]);
        } catch (\think\exception\ValidateException $e) {
            throw new LinFileException($e->getMessage());
        }
    }

    protected function store_db(UploadedFile $file)
    {

    }

    protected function store(string $name,UploadedFile $file)
    {
        $info = \think\facade\Filesystem::putFile(
            root_path().'/public/'.config_path('lincms.file.store_dir').'/'.$name,
            $file
        );
        dump($info);die;
    }

    protected function generateMd5(UploadedFile $file)
    {
        $md5 = md5_file($file->getOriginalName());
        return $md5;
    }


}