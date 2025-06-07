<?php

namespace App\Traits;

/**
 * Трейт для проверки прав администратора
 */
trait HasAdmin
{
    /**
     * Проверка, является ли пользователь администратором
     *
     * @return bool
     */
    public function isAdmin()
    {
        return
            $this->authData
            && isset($this->authData->role)
            && $this->authData->role === 'admin';
    }
}