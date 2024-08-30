<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixUfo\Contract\BitrixUserFieldType;
use Maximaster\BitrixUfo\Contract\UserFieldType;
use Maximaster\BitrixUfo\FieldFramework\InvisibleUserFieldTrait;
use Maximaster\BitrixUfo\FieldFramework\TypedUserFieldTrait;

/**
 * Поле которое ничего не хранит и всегда возвращает пустую строку.
 */
class EmptyField implements BitrixUserFieldType, UserFieldType
{
    use TypedUserFieldTrait;
    use InvisibleUserFieldTrait;

    /**
     * {@inheritDoc}
     */
    public static function id(): string
    {
        return 'ufo_empty';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Пустота';
    }

    /**
     * {@inheritDoc}
     */
    public static function baseType(): UserFieldBaseType
    {
        return UserFieldBaseType::STRING();
    }

    /**
     * {@inheritDoc}
     */
    public static function columnTypeDefinition(): string
    {
        return 'CHAR(0)';
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkFields(array $userField, $value, $userId = false): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public static function prepareSettings(array $userField): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSettingsHTML($userField, array $htmlControl, bool $isVarsFromForm): string
    {
        return '';
    }
}
