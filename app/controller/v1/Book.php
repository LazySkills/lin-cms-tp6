<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-19  */

namespace app\controller\v1;

use LinCmsTp6\annotation\{Auth, Logger};
use paa\annotation\Doc;
use think\annotation\route\{
    Group
};
use app\model\Book as BookModel;
use think\annotation\Route;

/**
 * Class Book
 * @Group(value="v1/book")
 * @package app\controller\v1
 */
class Book
{
    /**
     * @Route(value=":bid",method="GET")
     * @Auth(value="查询指定bid的图书",group="图书",hide="false")
     * @Doc(value="查询指定bid的图书",group="插件.图书",hide="false")
     */
    public function getBook($bid)
    {
        $result = BookModel::where('id',$bid)->find();
        return json($result);
    }

    /**
     * @Route(value="",method="GET")
     * @Auth(value="查询指定bid的图书",group="图书",hide="false")
     * @Doc(value="查询指定bid的图书",group="插件.图书",hide="false")
     */
    public function getBooks()
    {
        $result = BookModel::select();
        return json($result);
    }

    /**
     * @Route(value="",method="POST")
     * @Auth(value="新建图书",group="图书",hide="false")
     * @Doc(value="新建图书",group="插件.图书",hide="false")
     */
    public function create()
    {
        BookModel::create(request()->post());
        return writeJson(201, '', '新建图书成功');
    }

    /**
     * @Route(value="",method="PUT")
     * @Auth(value="更新图书",group="图书",hide="false")
     * @Doc(value="更新图书",group="插件.图书",hide="false")
     */
    public function update()
    {
        $params = request()->put();
        $bookModel = new BookModel();
        $bookModel->update($params,['id' => $params['id']]);
        return writeJson(201, '', '更新图书成功');
    }

    /**
     * @Route(value=":bid",method="DELETE")
     * @Auth(value="删除图书",group="图书",hide="false")
     * @Logger(value="删除了id为 {bid} 的图书")
     * @Doc(value="删除图书",group="插件.图书",hide="false")
     */
    public function delete($bid)
    {
        BookModel::destroy($bid);
        return writeJson(201, '', '删除图书成功');
    }
}
