<?php

namespace App\Filament\Resources\Flats\Pages;

use App\Filament\Resources\Flats\FlatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFlats extends ListRecords
{
    protected static string $resource = FlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
