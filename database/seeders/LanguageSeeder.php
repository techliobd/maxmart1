<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇺🇸',
                'is_default' => true,
                'is_active' => true,
                'direction' => 'ltr',
            ],
            [
                'code' => 'es',
                'name' => 'Spanish',
                'native_name' => 'Español',
                'flag' => '🇪🇸',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'ltr',
            ],
            [
                'code' => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'flag' => '🇫🇷',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'ltr',
            ],
            [
                'code' => 'de',
                'name' => 'German',
                'native_name' => 'Deutsch',
                'flag' => '🇩🇪',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'ltr',
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '🇸🇦',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'rtl',
            ],
            [
                'code' => 'bn',
                'name' => 'Bengali',
                'native_name' => 'বাংলা',
                'flag' => '🇧🇩',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'ltr',
            ],
            [
                'code' => 'hi',
                'name' => 'Hindi',
                'native_name' => 'हिन्दी',
                'flag' => '🇮🇳',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'ltr',
            ],
            [
                'code' => 'zh',
                'name' => 'Chinese',
                'native_name' => '中文',
                'flag' => '🇨🇳',
                'is_default' => false,
                'is_active' => true,
                'direction' => 'ltr',
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
