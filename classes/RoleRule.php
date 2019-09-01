<?php
namespace x51\yii2\modules\auth\classes;
use \Yii;

class RoleRule
{
    protected static $_roles;
    protected static $_rules;
    protected static $_permissions;

    public static $cacheTime = 300;

    /**
     * Возвращает роли текущего пользователя
     *
     * @return array
     */
    public static function getRoles()
    {
        if (is_null(static::$_roles)) {
            if (static::$cacheTime > 0) {
                $cacheKey = [
                    'user' => Yii::$app->user->id,
                    'param' => 'roles',
                ];
                $cache = Yii::$app->cache;
                $data = $cache->get($cacheKey);
                if ($data !== false) {
                    static::$_roles = $data;
                    return $data;
                }
            }

            if (!empty(Yii::$app->authManager)) {
                if (!empty(Yii::$app->user->getIdentity())) { // пользователь авторизован
                    static::$_roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
                } else {
                    static::$_roles = [];
                    if ($arDefRoles = Yii::$app->authManager->getDefaultRoles()) {
                        foreach ($arDefRoles as $defRoleName) {
                            if ($defRole = Yii::$app->authManager->getRole($defRoleName)) {
                                static::$_roles[$defRoleName] = $defRole;
                            }
                        }
                    }
                }
            } else {
                static::$_roles = [];
            }
            if (static::$cacheTime > 0) {
                $cache->set($cacheKey, static::$_roles, static::$cacheTime);
            }
        }
        return static::$_roles;
    } // end getRoles

    /**
     * Возвращает разрешения текущего пользователя
     *
     * @return array
     */
    public static function getPermissions()
    {
        if (is_null(static::$_permissions)) {
            if (static::$cacheTime > 0) {
                $cacheKey = [
                    'user' => Yii::$app->user->id,
                    'param' => 'permissions',
                ];
                $cache = Yii::$app->cache;
                $data = $cache->get($cacheKey);
                if ($data !== false) {
                    static::$_permissions = $data;
                    return $data;
                }
            }

            if (!empty(Yii::$app->authManager)) {
                if (!empty(Yii::$app->user->getIdentity())) { // пользователь авторизован
                    static::$_permissions = Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->id);
                } elseif ($arDefRoles = Yii::$app->authManager->getDefaultRoles()) { // пользователь не авторизован и его роль по умолчанию
                    static::$_permissions = [];
                    foreach ($arDefRoles as $role) {
                        //echo 'Role = '.$objRole."\r\n<br>";
                        static::$_permissions = array_merge(static::$_permissions, Yii::$app->authManager->getPermissionsByRole($role));
                    }
                } else { // не авторизован и нет ролей по умолчанию
                    static::$_permissions = [];
                }
            } else {
                static::$_permissions = [];
            }
            if (static::$cacheTime > 0) {
                $cache->set($cacheKey, static::$_permissions, static::$cacheTime);
            }
        }
        return static::$_permissions;        
    } // end getPermissions

    /**
     * Выбирает только те роли, переданные в массиве $arPossible, которые есть у текущего пользователя
     *
     * @param array $arPossible
     * @return array
     */
    public static function chooseRoles(array $arPossible)
    {
        $roles = static::getRoles();
        $res = [];
        foreach ($arPossible as $role) {
            if (isset($roles[$role])) {
                $res[$role] = $roles[$role];
            }
        }
        return $res;
    } // end chooseRoles

    /**
     * Выбирает только те разрешения, по списку переданному во входном массиве, которые есть у текущего пользователя
     *
     * @param array $arPossible
     * @return array
     */
    public static function choosePermissions(array $arPossible)
    {
        $perms = static::getPermissions();
        $res = [];
        foreach ($arPossible as $perm) {
            if (isset($perms[$perm])) {
                $res[$perm] = $perms[$perm];
            }
        }
        return $res;
    } // end chooseRoles

    public static function getRule($ruleName)
    {
        if (!isset(static::$_rules[$ruleName])) {
            static::$_rules[$ruleName] = Yii::$app->authManager->getRule($ruleName);
        }
        return static::$_rules[$ruleName];
    }
} // end class
