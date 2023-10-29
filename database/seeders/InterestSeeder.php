<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = array("Traveling",
            "Outdoor",
            "Activities",
            "Music",
            "Art",
            "Sports",
            "Food",
            "Reading",
            "Gaming",
            "Technology",
            "Gym",
            "Cooking",
            "Dancing",
            "Photography",
            "Fashion",
            "Yoga",
            "Mindfulness",
            "Pets",
            "Movies",
            "Literature",
            "Hiking",
            "Writing",
            "History",
            "Science",
            "Nature",
            "Meditation",
            "Theater",
            "Animals",
            "Fashion",
            "DIY (Do It Yourself)",
            "Gardening",
            "Language Exchange",
            "Guitarists",
            "J-Pop",
            "K-Pop",
        );

        foreach($list as $item) {
            $passion = new Interest();
            $passion->name = $item;
            $passion->save();
        }
    }
}
