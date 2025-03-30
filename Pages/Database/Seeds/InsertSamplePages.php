<?php

namespace App\Modules\Pages\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class InsertSamplePages extends Seeder
{
    public function run()
    {
        // Set the locale to Lithuanian
        $faker = Factory::create('lt_LT');

        $data = [];

        for ($i = 0; $i < 100; $i++) {
            $timestamp         = $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s');
            $title             = rtrim($faker->sentence(mt_rand(3, 7)), '.');
            $contentParagraphs = $faker->paragraphs(mt_rand(30, 50));
            $content           = '<p>' . implode('</p><p>', $contentParagraphs) . '</p>';
            $slug              = url_title($title, '-', true);
            $data[]            = [
                'title'      => $title,
                'content'    => $content,
                'excerpt'    => $faker->sentence(mt_rand(20, 30)),
                'slug'       => $slug,
                'category'   => $faker->randomElement(['News', 'Page', 'Article']),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        $this->db->table('pages')->insertBatch($data);
    }
}
