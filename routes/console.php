<?php

use App\Models\Product;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('seed:test-books {count=500} {--truncate : Clear products table before seeding}', function () {
    $count = max(1, (int) $this->argument('count'));

    if ($this->option('truncate')) {
        Product::query()->delete();
        $this->info('Existing products removed.');
    }

    $faker = FakerFactory::create();

    $genres = [
        'Fantasy', 'Science Fiction', 'Mystery', 'Romance', 'History',
        'Horror', 'Biography', 'Business', 'Self-Help', 'Education',
    ];
    $ageGroups = ['Kids', 'Teens', 'Young Adult', 'Adult'];
    $publishers = [
        'Penguin Books', 'HarperCollins', 'Macmillan', 'Oxford Press',
        'Cambridge Press', 'Simon & Schuster', 'Pearson', 'McGraw-Hill',
    ];

    $rows = [];
    $chunkSize = 500;

    for ($i = 0; $i < $count; $i++) {
        $rows[] = [
            'Title' => $faker->unique()->sentence(mt_rand(2, 5)),
            'Author' => $faker->name(),
            'Rating' => $faker->randomFloat(2, 2.5, 5.0),
            'Review' => $faker->paragraph(mt_rand(1, 3)),
            'Price' => $faker->randomFloat(2, 120, 2800),
            'Stock' => $faker->numberBetween(0, 200),
            'ISBN' => '978' . $faker->unique()->numerify('##########'),
            'Publisher' => $faker->randomElement($publishers),
            'Genre' => $faker->randomElement($genres),
            'Age_Group' => $faker->randomElement($ageGroups),
            'Length' => $faker->numberBetween(18, 28),
            'Width' => $faker->numberBetween(12, 22),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (count($rows) === $chunkSize) {
            Product::query()->insert($rows);
            $rows = [];
        }
    }

    if (!empty($rows)) {
        Product::query()->insert($rows);
    }

    $this->info("Inserted {$count} test books successfully.");
})->purpose('Generate many test books for catalog/cart/checkout testing');
