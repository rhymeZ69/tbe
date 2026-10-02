<?php

namespace Database\Seeders;

use App\Models\VideoTour;
use Illuminate\Database\Seeder;

class VideoTourSeeder extends Seeder
{
    public function run(): void
    {
        $tours = [
            [
                'stage_number' => 1,
                'title'        => 'Livestock Sourcing & Rearing',
                'stage_tag'    => 'Livestock',
                'description'  => 'Healthy, ethically raised animals on our own farms and trusted partner farms. Every animal is vetted against strict quality and welfare criteria.',
                'video_source' => 'https://www.youtube.com/watch?v=9sWtw_EtHKI',
                'video_type'   => 'youtube',
                'sort_order'   => 1,
            ],
            [
                'stage_number' => 2,
                'title'        => 'Halal Slaughtering & Processing',
                'stage_tag'    => 'Slaughter',
                'description'  => 'Certified halal slaughtering performed under qualified Shariah supervision, followed by immediate chilling and expert cutting.',
                'video_source' => 'videos/slaughtering.mp4',
                'video_type'   => 'mp4',
                'sort_order'   => 2,
            ],
            [
                'stage_number' => 3,
                'title'        => 'Hygienic Packing & Cold Chain',
                'stage_tag'    => 'Packing',
                'description'  => 'Vacuum-packed or bulk-packed in hygienic facilities, then moved through an unbroken cold chain to your destination, worldwide.',
                'video_source' => 'videos/packing.mp4',
                'video_type'   => 'mp4',
                'sort_order'   => 3,
            ],
        ];

        foreach ($tours as $row) {
            VideoTour::updateOrCreate(
                ['stage_number' => $row['stage_number']],
                $row
            );
        }
    }
}