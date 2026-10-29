@php($issues = $this->getIssues())

<x-filament-widgets::widget>
    <x-filament::section heading="Kelengkapan konten" description="Hal yang perlu dilengkapi agar website tampil sempurna.">
        @if (count($issues) === 0)
            <p class="flex items-center gap-2 text-sm text-success-600">
                <x-filament::icon icon="heroicon-o-check-circle" class="h-5 w-5" />
                Semua konten aktif sudah lengkap.
            </p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($issues as $issue)
                    <li class="flex items-center justify-between gap-3 py-2 text-sm">
                        <span class="text-gray-700">{{ $issue['label'] }}</span>
                        <a href="{{ $issue['url'] }}" class="inline-flex items-center gap-2 font-semibold text-warning-600 hover:underline">
                            {{ $issue['count'] }} item
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
