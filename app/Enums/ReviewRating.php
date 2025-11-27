<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Review rating enumeration.
 */
enum ReviewRating: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;
    case TEN = 10;

    /**
     * Get all rating values as an array.
     *
     * @return array<int>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get minimum rating value.
     */
    public static function min(): int
    {
        return self::ONE->value;
    }

    /**
     * Get maximum rating value.
     */
    public static function max(): int
    {
        return self::TEN->value;
    }

    /**
     * Get label for a rating value.
     */
    public function label(): string
    {
        return match ($this) {
            self::ONE => '1 - Poor',
            self::TWO => '2 - Fair',
            self::THREE => '3 - Good',
            self::FOUR => '4 - Very Good',
            self::FIVE => '5 - Excellent',
            self::TEN => '10 - Outstanding',
        };
    }

    /**
     * Get all rating options with labels.
     *
     * @return array<int, array{value: int, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $rating) => [
                'value' => $rating->value,
                'label' => $rating->label(),
            ],
            self::cases()
        );
    }
}
