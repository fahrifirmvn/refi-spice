<?php

namespace App\Filament\Resources\ContentRevisions\Pages;

use App\Filament\Resources\ContentRevisions\ContentRevisionResource;
use Filament\Resources\Pages\ListRecords;

class ListContentRevisions extends ListRecords
{
    protected static string $resource = ContentRevisionResource::class;
}
