<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\UserRole;
use Tests\TestCase;

/**
 * UserRole enum unit tests.
 */
class UserRoleTest extends TestCase
{
    /**
     * Test user role values.
     */
    public function test_user_role_values(): void
    {
        $values = UserRole::values();

        $this->assertContains(UserRole::ADMIN->value, $values);
        $this->assertContains(UserRole::REVIEWER->value, $values);
        $this->assertContains(UserRole::SPEAKER->value, $values);
    }

    /**
     * Test registration roles.
     */
    public function test_registration_roles(): void
    {
        $roles = UserRole::registrationRoles();

        $this->assertContains(UserRole::REVIEWER->value, $roles);
        $this->assertContains(UserRole::SPEAKER->value, $roles);
        $this->assertNotContains(UserRole::ADMIN->value, $roles);
    }
}
