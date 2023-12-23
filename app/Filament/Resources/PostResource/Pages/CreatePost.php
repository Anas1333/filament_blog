<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon; 

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $mytime = Carbon::now();
        
        $data['user_id'] = auth()->id();
        $data['published_at'] = $mytime->toDateTimeString();

        return $data;
    }
}
