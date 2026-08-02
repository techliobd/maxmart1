<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\HomepageSection;
use Illuminate\Support\Facades\DB;

class HomepageSectionSorter extends Component
{
    public array $sections = [];
    public array $sectionOrder = [];

    protected $listeners = ['refreshComponent' => 'loadSections'];

    public function mount(): void
    {
        $this->loadSections();
    }

    public function loadSections(): void
    {
        $this->sections = HomepageSection::orderBy('order')->get()->toArray();
        $this->sectionOrder = collect($this->sections)->pluck('id')->toArray();
    }

    public function moveUp(int $sectionId): void
    {
        $index = array_search($sectionId, $this->sectionOrder);
        if ($index > 0) {
            $temp = $this->sectionOrder[$index];
            $this->sectionOrder[$index] = $this->sectionOrder[$index - 1];
            $this->sectionOrder[$index - 1] = $temp;
            $this->saveOrder();
        }
    }

    public function moveDown(int $sectionId): void
    {
        $index = array_search($sectionId, $this->sectionOrder);
        if ($index < count($this->sectionOrder) - 1) {
            $temp = $this->sectionOrder[$index];
            $this->sectionOrder[$index] = $this->sectionOrder[$index + 1];
            $this->sectionOrder[$index + 1] = $temp;
            $this->saveOrder();
        }
    }

    public function toggleVisibility(int $sectionId): void
    {
        $section = HomepageSection::find($sectionId);
        if ($section) {
            $section->update(['is_visible' => !$section->is_visible]);
            $this->loadSections();
            $this->dispatch('showSuccess', message: 'Section visibility updated!');
            $this->dispatch('sectionsUpdated');
        }
    }

    protected function saveOrder(): void
    {
        DB::beginTransaction();
        try {
            foreach ($this->sectionOrder as $index => $sectionId) {
                HomepageSection::where('id', $sectionId)->update(['order' => $index + 1]);
            }
            DB::commit();
            $this->loadSections();
            $this->dispatch('showSuccess', message: 'Section order updated!');
            $this->dispatch('sectionsUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showError', message: 'Failed to update order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.homepage-section-sorter');
    }
}
