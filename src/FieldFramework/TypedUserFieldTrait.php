<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\FieldFramework;

use Maximaster\BitrixEnums\Main\UserFieldBaseType;

trait TypedUserFieldTrait
{
    /**
     * Уникальный идентификатор типа.
     */
    abstract public static function id(): string;

    /**
     * Описание типа.
     */
    abstract public static function description(): string;

    /**
     * Базовый тип поля.
     */
    abstract public static function baseType(): UserFieldBaseType;

    /**
     * Описание типа столбца для базы данных.
     */
    abstract public static function columnTypeDefinition(): string;

    /**
     * {@inheritDoc}
     *
     * Функция регистрируется в качестве обработчика события OnUserTypeBuildList.
     * Возвращает массив описывающий тип пользовательского поля.
     */
    public static function getUserTypeDescription(): array
    {
        return [
            'USER_TYPE_ID' => static::id(),
            'CLASS_NAME' => static::class,
            'DESCRIPTION' => static::description(),
            'BASE_TYPE' => static::baseType()->getValue(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getDbColumnType(): string
    {
        return static::columnTypeDefinition();
    }
}
