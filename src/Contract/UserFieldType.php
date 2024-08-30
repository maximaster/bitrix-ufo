<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

use Maximaster\BitrixEnums\Main\UserFieldBaseType;

/**
 * Интерфейс для всех типов пользовательских полей.
 */
interface UserFieldType
{
    /**
     * Уникальный идентификатор типа.
     *
     * @psalm-return non-empty-string
     */
    public static function id(): string;

    /**
     * Описание типа.
     */
    public static function description(): string;

    /**
     * Базовый тип поля.
     */
    public static function baseType(): UserFieldBaseType;

    /**
     * Описание типа столбца для базы данных.
     */
    public static function columnTypeDefinition(): string;
}
