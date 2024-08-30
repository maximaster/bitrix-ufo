<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserField\TypeBase;
use League\Uri\Contracts\UriException;
use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;
use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixTableFields\Field\UrlField as UrlOrmField;
use Maximaster\BitrixUfo\Contract\BitrixUserFieldType;
use Maximaster\BitrixUfo\Contract\ConvertibleValue;
use Maximaster\BitrixUfo\Contract\EntityFieldProvider;
use Maximaster\BitrixUfo\Contract\UserFieldType;
use Maximaster\BitrixUfo\Exception\InvalidArgumentException;
use Maximaster\BitrixUfo\FieldFramework\InvisibleUserFieldTrait;
use Maximaster\BitrixUfo\FieldFramework\TypedUserFieldTrait;

/**
 * Поле, которое хранит представление url.
 */
class UrlField extends TypeBase implements
    BitrixUserFieldType,
    UserFieldType,
    EntityFieldProvider,
    ConvertibleValue
{
    // TODO Данные трейты повторно вызываются, так как по какой-то причине не подхватываются из родительского класса
    use TypedUserFieldTrait;
    use InvisibleUserFieldTrait;

    /**
     * {@inheritDoc}
     */
    public static function id(): string
    {
        return 'ufo_url';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Представление "URL".';
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
        // TODO Стоит ли как-то ограничивать?
        // Формально, длина URL не ограничена, но браузеры имеют ограничения по длине URL.
        // Не рекомендуется использовать URL длиной более 2048 символов,
        // так как Microsoft Internet Explorer имеет именно такое ограничение[10].
        return 'TEXT';
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
        $errors = [
            [
                'id' => $userField['FIELD_NAME'],
                'text' => 'Значение имеет невалидный формат url.',
            ],
        ];

        if (is_string($value) === true) {
            // TODO Возможно стоит использовать filter_var($value, FILTER_VALIDATE_URL), добавив флаги.
            //      Но в таком случае интернациональные доменные имена не пройдут проверку.
            try {
                Uri::createFromString($value);
            } catch (UriException $exception) {
                return $errors;
            }

            return [];
        }

        if (is_object($value) === true && is_a($value, UriInterface::class, false) === true) {
            return [];
        }

        return $errors;
    }

    /**
     * {@inheritDoc}
     *
     * @throws SystemException
     */
    public static function getEntityField(string $name, array $parameters): Field
    {
        return UrlOrmField::on($name, $parameters);
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function onBeforeSave(array $userField, $value, $userId = false)
    {
        if (is_string($value) === true) {
            return $value;
        }

        if (is_object($value) === true && is_a($value, UriInterface::class, false) === true) {
            return (string) $value;
        }

        throw new InvalidArgumentException('Не удалось преобразовать значение для сохранения его в БД.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     */
    public static function onAfterFetch(array $userField, array $rawValue)
    {
        $value = $rawValue['VALUE'];

        if (is_object($value) === true && is_a($value, UriInterface::class, false) === true) {
            return $value;
        }

        if (is_string($value) === true) {
            return Uri::createFromString($value);
        }

        throw new InvalidArgumentException('Не удалось преобразовать значение url после выборки его из БД.');
    }
}
