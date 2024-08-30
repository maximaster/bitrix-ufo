<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий SQL-тип столбца значения поля.
 */
interface DatabaseColumnTypeConfigurable
{
    /**
     * Функция вызывается при добавлении нового UF-поля для конструирования SQL-запроса создания столбца значений поля.
     *
     * <p>Вызывается из метода DbColumnType объекта $USER_FIELD_MANAGER.</p>
     *
     * TODO рассмотреть возможность использовать параметр
     * <p>В стандартном варианте битрикс передает параметр $userField (массив, описывающий поле).</p>
     * <p>Но он нигде не используется. Поэтому данный параметр удален.</p>
     *
     * @see CUserTypeManager::DbColumnType
     */
    public static function getDbColumnType(): string;
}
