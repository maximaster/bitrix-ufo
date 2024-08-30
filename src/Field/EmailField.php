<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserField\TypeBase;
use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixTableFields\Field\EmailField as EmailOrmField;
use Maximaster\BitrixUfo\Contract\BitrixUserFieldType;
use Maximaster\BitrixUfo\Contract\ConvertibleValue;
use Maximaster\BitrixUfo\Contract\EntityFieldProvider;
use Maximaster\BitrixUfo\Contract\UserFieldType;
use Maximaster\BitrixUfo\Exception\InvalidArgumentException;
use Maximaster\BitrixUfo\FieldFramework\InvisibleUserFieldTrait;
use Maximaster\BitrixUfo\FieldFramework\TypedUserFieldTrait;
use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use Nepada\EmailAddress\RfcEmailAddress;

/**
 * Поле, которое хранит представление e-mail.
 */
class EmailField extends TypeBase implements
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
        return 'ufo_email';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Представление адреса e-mail.';
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
        /*
         * Примечание:
         * Первоначальная версия RFC 3696 описывала 320 как максимальную длину, но впоследствии
         * Джон Кленсин принял неверное значение, поскольку путь определен как Path = "<" [ A-d-l ":" ] Mailbox ">"
         * Таким образом, элемент почтового ящика (т.е. адрес электронной почты) имеет угловые скобки вокруг него,
         * чтобы сформировать путь, максимальная длина которого составляет 254 символа,
         * тем самым ограничить длину пути до 256 символов или менее.
         * Максимальная длина, указанная в RFC 5321 (http://tools.ietf.org/html/rfc5321#section-4.5.3), гласит:
         * "Максимальная общая длина обратного или прямого пути составляет 256 символов".
         * RFC 3696 был исправлен http://www.rfc-editor.org/errata_search.php?rfc=3696.
         * также три из канонических примеров на самом деле являются недействительными адресами.
         * */
        return 'VARCHAR(255)';
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
     *
     * @psalm-param array<string, mixed> $userField
     * @psalm-param array<string, mixed> $userField
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
                'text' => 'Значение имеет невалидный формат e-mail.',
            ],
        ];

        // TODO Возможно стоит использовать filter_var($value, FILTER_VALIDATE_EMAIL).
        //      Если обработка через FILTER_VALIDATE_EMAIL, то по RFC 822, но есть ряд ограничений.
        if (is_string($value) === true) {
            try {
                RfcEmailAddress::fromString($value);
            } catch (InvalidEmailAddressException $exception) {
                return $errors;
            }

            return [];
        }

        if (is_object($value) === true && is_a($value, EmailAddress::class, false) === true) {
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
        return EmailOrmField::on($name, $parameters);
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

        if (is_object($value) === true && is_a($value, EmailAddress::class, false) === true) {
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

        if (is_object($value) === true && is_a($value, EmailAddress::class, false) === true) {
            return $value;
        }

        if (is_string($value) === true) {
            return RfcEmailAddress::fromString($value);
        }

        throw new InvalidArgumentException('Не удалось преобразовать значение email после выборки его из БД.');
    }
}
