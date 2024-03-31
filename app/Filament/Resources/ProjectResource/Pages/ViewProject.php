<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Pages\Scrum;
use App\Filament\Resources\ProjectResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban')
                ->label(
                    fn ()
                    => ($this->record->type === 'scrum' ? __('Scrum board') : __('Kanban board'))
                )
                ->icon('heroicon-o-view-columns')
                ->color('gray')
                ->url(function () {
                    if ($this->record->type === 'scrum') {
                        return Scrum::getUrl(['project' => $this->record->id]);
                    } else {
                        return \App\Filament\Pages\Kanban::getUrl(['project' => $this->record->id]);
                    }
                }),

            Actions\EditAction::make(),
        ];
    }
}
