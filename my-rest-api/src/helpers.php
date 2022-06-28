<?php

if (!function_exists('get_valid_user_types')) {

    /**
     * 获取过滤后的有效用户类型.
     *
     * @param array $types
     * @return array
     */
    function get_valid_user_types(array $types): array
    {
        // 有效的用户类型，可以定义成常量
        $userTypes = [1,2,3];

        return array_filter($types, function ($val) use ($userTypes) {
            return in_array($val, $userTypes);
        });
    }
}