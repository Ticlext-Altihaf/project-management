<?php

namespace App\Filament\Pages;

use App\Helpers\KanbanScrumHelper;
use App\Models\Project;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Kanban extends Page implements HasForms
{
    use InteractsWithForms, KanbanScrumHelper;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $slug = 'kanban/{project}';

    protected static string $view = 'filament.pages.kanban';

    protected static bool $shouldRegisterNavigation = false;

    protected $listeners = [
        'recordUpdated',
        'closeTicketDialog'
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        if ($this->project->type === 'scrum') {
            $this->redirect(Scrum::getUrl(['project' => $this->project->id]));
        } elseif (
            $this->project->owner_id != auth()->user()->id
            &&
            !$this->project->users->where('id', auth()->user()->id)->count()
        ) {
            abort(403);
        }
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->button()
                ->label(__('Refresh'))
                ->color('gray')
                ->action(function () {
                    $this->getRecords();
                    Notification::make()->title(__('Kanban board updated'))->success()->send();
                })
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return $this->kanbanHeading();
    }

    protected function getFormSchema(): array
    {
        return $this->formSchema();
    }

}
