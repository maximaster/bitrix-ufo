<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Bitrix\Main\UserField\TypeBase;
use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixUfo\Contract\BitrixUserFieldType;
use Maximaster\BitrixUfo\Contract\UserFieldType;
use Maximaster\BitrixUfo\FieldFramework\InvisibleUserFieldTrait;
use Maximaster\BitrixUfo\FieldFramework\TypedUserFieldTrait;

/**
 * Поле, которое хранит телефонный номер.
 */
class PhoneNumberField extends TypeBase implements BitrixUserFieldType, UserFieldType
{
    // TODO Данные трейты повторно вызываются, так как по какой-то причине не подхватываются из родительского класса
    use TypedUserFieldTrait;
    use InvisibleUserFieldTrait;

    /**
     * {@inheritDoc}
     */
    public static function id(): string
    {
        return 'ufo_phone_number';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Представление "Телефон".';
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
        return 'VARCHAR(50)';
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

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkFields(array $userField, $value, $userId = false): array
    {
        // Добавить проверки после того, как будет известен формат.
        return [];
    }
}
