<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет методы для поля с множественным значением.
 */
interface BitrixMultipleField extends MultyEditable, AdminListMultyViewable, AdminListMultyEditable
{
}
