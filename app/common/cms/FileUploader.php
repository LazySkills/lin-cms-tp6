<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-18  */

namespace app\common\cms;



use app\exception\cms\LinFileException;
use app\model\LinFile;
use think\file\UploadedFile;
use think\facade\Filesystem;
use think\exception\ValidateException;

class FileUploader
{
    protected $files = [];
    /**
     * @var UploadedFile $file
     */
    protected $file;
    /**
     * @var string
     */
    protected $key;
    protected $data = [];

    public function __construct(array $files)
    {
        if (empty($files)){
            throw new LinFileException("未找到符合条件的文件资源");
        }
        if (count($files) >= config('lincms.file.nums')){
            throw new LinFileException("文件数量过多");
        }
        $this->files = $files;
    }

    public function upload()
    {
        foreach ($this->files as $key => $file){
            $this->file = $file;
            $this->key = $key;
            $this->verify();
            if ($linFile = $this->checkStoreDb($file)){
                $this->data[$this->key] = $linFile;
            }else{
                $info = $this->store();
                $this->data[$this->key] = $this->storeDb($info);
            }
            $this->data[$this->key]['key'] = $this->key;
        }
        return array_values($this->data);
    }

    protected function verify()
    {
        $type = $this->file->getOriginalExtension();
        if (in_array($type,config('lincms.file.image_type'))){
            $rule = config('lincms.file.validate.image');
        }elseif (in_array($type,config('lincms.file.video_type'))){
            $rule = config('lincms.file.validate.video');
        }else{
            throw new LinFileException('暂不支持的文件类型：'.$type);
        }
        if(empty($rule)) return;
        try {
            validate([
                $this->key=>[
                    'fileSize'=>1024*1024*2,
                    'fileExt'=>['jpg','jpeg','png'],
                ]
            ])->check([$this->key=>$this->file]);
        } catch (ValidateException $e) {
            throw new LinFileException($e->getMessage());
        }
    }

    protected function checkStoreDb(UploadedFile $file)
    {
        if ($linFile = LinFile::where(['md5' => $this->file->md5()])->find()){
            return $this->getPublicData($linFile['id'],$linFile['path']);
        }
        return false;
    }

    protected function store()
    {
        try{
            $info = Filesystem::disk('public')->putFile(
                $this->key,
                $this->file
            );
            if ($info === false) throw new \Exception('上传失败');
            return $info;
        }catch (\Exception $exception){
            throw new LinFileException($exception->getMessage());
        }
    }

    protected function storeDb(string $path)
    {
        try{
            $linFile = LinFile::create([
                'name' => basename($path),
                'path' => $path,
                'size' => $this->file->getSize(),
                'extension' => $this->file->getOriginalExtension(),
                'md5' => $this->file->md5(),
                'type' => 1
            ]);
            return $this->getPublicData($linFile['id'],$linFile['path']);
        }catch (\Exception $exception){
            throw new LinFileException('储存文件失败');
        }
    }

    protected function getPublicData($id,$path){
        return [
            'id' => $id,
            'path' => $path,
            'url' => request()->scheme().'://'.request()->host(). '/' . config('lincms.file.store_dir') . '/' . $path
        ];
    }

}