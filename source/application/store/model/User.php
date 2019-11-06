<?php

namespace app\store\model;

use app\common\model\User as UserModel;

/**
 * 用户模型
 * Class User
 * @package app\store\model
 */
class User extends UserModel
{
    /**
     * 获取当前用户总数
     * @param $day
     * @return int|string
     */
    public function getUserTotal($day = null)
    {
        if (!is_null($day)) {
            $startTime = strtotime($day);
            $this->where('create_time', '>=', $startTime)
                ->where('create_time', '<', $startTime + 86400);
        }
        return $this->where('is_delete', '=', '0')->count();
    }

    /**
     * 获取用户列表
     * @param string $nickName
     * @param int $gender
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($nickName = '', $gender = -1)
    {
        // 检索条件：微信昵称
        !empty($nickName) && $this->where('nickName', 'like', "%$nickName%");
        // 检索条件：性别
        if ($gender !== '' && $gender > -1) {
            $this->where('gender', '=', (int)$gender);
        }
        return $this->where('is_delete', '=', '0')
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

}
