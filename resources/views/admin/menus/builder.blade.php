<x-admin-layout>
    <x-slot name="title">Menu Builder</x-slot>
    <x-slot name="subtitle">{{ $menu->name ?? 'Create Menu' }}</x-slot>

    @livewire('menu-builder', ['menuId' => $menu->id ?? null])
</x-admin-layout>
