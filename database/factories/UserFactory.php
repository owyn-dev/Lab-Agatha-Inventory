<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'username' => $this->faker->unique()->userName(),
            'password' => self::$password ??= Hash::make('password'),
        ];
    }

    public function withRandomRole(array $availableRoles): Factory
    {
        return $this->afterCreating(function (User $user) use ($availableRoles): void {
            $randomRole = $this->faker->randomElement($availableRoles);

            Role::firstOrCreate(['name' => $randomRole]);

            $user->assignRole($randomRole);
        });
    }
}
