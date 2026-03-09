<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AuthorizationSeeder;
use Database\Seeders\ModuleGroupSeeder;
use Database\Seeders\ModuleSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            ModuleGroupSeeder::class,
            ModuleSeeder::class,
            AuthorizationTypeSeeder::class,
            RoleSeeder::class,
            AuthorizationSeeder::class,
            AdminSeeder::class,

            // MemberSeeder::class,
            // ThreadTopicSeeder::class,
            // ThreadSeeder::class,
            // ThreadCommentSeeder::class,

            // NewsTypeSeeder::class,
            // NewsSeeder::class,
            // PartnerTypeSeeder::class,
            // PartnerSeeder::class,
            // ParittaTypeSeeder::class,
            // ParittaSeeder::class,

            // ViharaSeeder::class,
            // GallerySeeder::class,
            // VideoSeeder::class,
            // AgendaSeeder::class,
            // OrganizationSeeder::class,
            // ViharaImageSeeder::class,

            // SanghaBankAccountSeeder::class,

            // SanghaPositionCategorySeeder::class,
            // SanghaPositionSeeder::class,
            // GuidancePositionSeeder::class,

            // RegionSeeder::class,
            // ProvinceSeeder::class,

            // HistorySeeder::class,
            // CharterSeeder::class,
            // LogoMeaningSeeder::class,

        ]);

        Schema::enableForeignKeyConstraints();
    }
}
