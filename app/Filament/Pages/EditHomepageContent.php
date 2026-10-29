<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\CompanyStats\CompanyStatResource;
use App\Filament\Resources\Milestones\MilestoneResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\ProcessSteps\ProcessStepResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Filament\Support\PageContentCatalog;
use App\Models\PageContent;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class EditHomepageContent extends Page implements HasSchemas
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static ?string $navigationLabel = 'Isi Halaman Depan';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Isi Halaman Depan';

    protected static ?string $slug = 'isi-halaman-depan';

    protected string $view = 'filament.pages.edit-homepage-content';

    /** @var array<string, mixed> */
    public ?array $data = [];

    /** Urutan tab mengikuti urutan section di halaman depan. */
    private const SECTION_ORDER = [
        'hero', 'cerita_kami', 'koleksi_produk', 'kualitas', 'rantai_pasok',
        'kemitraan', 'contact', 'footer', 'brand', 'seo',
    ];

    private const SECTION_ANCHORS = [
        'cerita_kami' => '#cerita-kami',
        'koleksi_produk' => '#koleksi-produk',
        'kualitas' => '#kualitas',
        'rantai_pasok' => '#rantai-pasok',
        'kemitraan' => '#kemitraan',
        'contact' => '#kontak',
        'footer' => '#kontak',
    ];

    /** Daftar item yang tampil di bagian website tertentu, dikelola lewat halaman daftarnya. */
    private const RELATED_LISTS = [
        'cerita_kami' => [['Tonggak Sejarah', MilestoneResource::class, Heroicon::OutlinedClock]],
        'kualitas' => [
            ['Statistik Perusahaan', CompanyStatResource::class, Heroicon::OutlinedChartBar],
            ['Sertifikasi & Akreditasi', CertificationResource::class, Heroicon::OutlinedShieldCheck],
        ],
        'rantai_pasok' => [['Tahapan Proses Ekspor', ProcessStepResource::class, Heroicon::OutlinedTruck]],
        'kemitraan' => [
            ['Mitra & Klien', PartnerResource::class, Heroicon::OutlinedBuildingOffice2],
            ['Testimoni Klien', TestimonialResource::class, Heroicon::OutlinedChatBubbleLeftRight],
        ],
    ];

    private const LONG_TEXT_KEYS = ['body', 'paragraph', 'description', 'note', 'address', 'facilities', 'copyright'];

    public function mount(): void
    {
        $this->ensureMediaRowsExist();
        $this->form->fill($this->loadState());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('sections')
                    ->persistTabInQueryString('bagian')
                    ->tabs($this->sectionTabs()),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Simpan Perubahan')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $rows = PageContent::query()->get()->keyBy(fn (PageContent $row): string => $row->section_key.'.'.$row->field_key);

        foreach ($state['fields'] ?? [] as $section => $fields) {
            foreach ($fields as $field => $value) {
                $row = $rows->get($section.'.'.$field);

                if ($row && ! $row->isMediaField() && $row->field_value !== ($value ?? '')) {
                    $row->update(['field_value' => $value ?? '']);
                }
            }
        }

        $this->form->saveRelationships();

        Notification::make()
            ->title('Perubahan tersimpan')
            ->body('Isi halaman depan sudah diperbarui.')
            ->success()
            ->actions([
                Action::make('lihat')
                    ->label('Lihat halaman depan')
                    ->url(route('home'), shouldOpenInNewTab: true),
            ])
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('previewWebsite')
                ->label('Lihat halaman depan')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(route('home'))
                ->openUrlInNewTab(),
        ];
    }

    /**
     * @return array<int, Tab>
     */
    private function sectionTabs(): array
    {
        return $this->contentRows()
            ->groupBy('section_key')
            ->sortBy(fn ($rows, string $section): int => $this->sectionPosition($section))
            ->map(fn ($rows, string $section): Tab => $this->sectionTab($section, $rows))
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, PageContent>  $rows
     */
    private function sectionTab(string $section, Collection $rows): Tab
    {
        $schema = [];

        if ($description = PageContentCatalog::sectionDescription($section)) {
            $schema[] = Text::make($description)->color('gray')->columnSpanFull();
        }

        if (isset(self::SECTION_ANCHORS[$section])) {
            $schema[] = Actions::make([
                Action::make('preview_'.$section)
                    ->label('Lihat bagian ini di website')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->size('sm')
                    ->url(route('home').self::SECTION_ANCHORS[$section])
                    ->openUrlInNewTab(),
            ])->columnSpanFull();
        }

        if (isset(self::RELATED_LISTS[$section])) {
            $schema[] = Section::make('Daftar yang tampil di bagian ini')
                ->description('Tambah, ubah, urutkan, atau sembunyikan item lewat tombol berikut.')
                ->schema([
                    Actions::make(array_map(
                        fn (array $list): Action => Action::make('manage_'.Str::slug($list[0], '_'))
                            ->label('Kelola '.$list[0])
                            ->icon($list[2])
                            ->color('gray')
                            ->url($list[1]::getUrl('index')),
                        self::RELATED_LISTS[$section],
                    )),
                ])
                ->compact()
                ->columnSpanFull();
        }

        if ($section === 'brand') {
            $schema[] = $this->mediaUpload('logo', PageContent::LOGO_COLLECTION, 'logo', 'Logo Perusahaan', 'Tampil di header, footer, dan tab browser. Unggah JPG, PNG, atau WebP berbentuk persegi, maksimal 5 MB. Kosongkan untuk memakai logo bawaan.', 'brand', 'logo_path');
        }

        if ($section === 'seo') {
            $schema[] = $this->mediaUpload('og_image', PageContent::OPEN_GRAPH_IMAGE_COLLECTION, 'open_graph', 'Gambar Pratinjau Media Sosial', 'Unggah JPG, PNG, atau WebP maksimal 5 MB. Rasio 1200 × 630 paling aman untuk WhatsApp, LinkedIn, dan Facebook. Kosongkan untuk memakai gambar bawaan.', 'seo', 'og_image_path');
        }

        foreach ($rows->reject(fn (PageContent $row): bool => $row->isMediaField()) as $row) {
            $schema[] = $this->fieldFor($row);
        }

        return Tab::make(PageContentCatalog::sectionLabel($section))
            ->id($section)
            ->schema($schema)
            ->columns(2);
    }

    private function fieldFor(PageContent $row): TextInput|Textarea|FileUpload
    {
        $name = "fields.{$row->section_key}.{$row->field_key}";
        $label = PageContentCatalog::fieldLabel($row->section_key, $row->field_key);
        $helper = PageContentCatalog::fieldHelper($row->section_key, $row->field_key);
        $isLong = Str::contains($row->field_key, self::LONG_TEXT_KEYS)
            || mb_strlen((string) $row->field_value) > 90
            || str_contains((string) $row->field_value, "\n");

        if ($row->field_key === 'archive_photo_path') {
            return FileUpload::make($name)
                ->label($label)
                ->helperText($helper)
                ->image()
                ->disk('public')
                ->directory('cerita-kami')
                ->visibility('public')
                ->maxSize(5120)
                ->imageEditor()
                ->columnSpanFull();
        }

        if ($isLong) {
            return Textarea::make($name)->label($label)->helperText($helper)->rows(3)->autosize()->columnSpanFull();
        }

        return TextInput::make($name)->label($label)->helperText($helper)->maxLength(500);
    }

    private function mediaUpload(string $name, string $collection, string $conversion, string $label, string $helper, string $section, string $field): Group
    {
        return Group::make([
            SpatieMediaLibraryFileUpload::make($name)
                ->label($label)
                ->helperText($helper)
                ->image()
                ->collection($collection)
                ->conversion($conversion)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(5120)
                ->imageEditor(),
        ])
            ->model(fn (): ?PageContent => PageContent::query()->where('section_key', $section)->where('field_key', $field)->first())
            ->columnSpanFull();
    }

    /**
     * @return Collection<int, PageContent>
     */
    private function contentRows(): Collection
    {
        return PageContent::query()->orderBy('id')->get();
    }

    /**
     * @return array{fields: array<string, array<string, string|null>>}
     */
    private function loadState(): array
    {
        $fields = [];

        foreach ($this->contentRows()->reject(fn (PageContent $row): bool => $row->isMediaField()) as $row) {
            $fields[$row->section_key][$row->field_key] = $row->field_value;
        }

        return ['fields' => $fields];
    }

    private function sectionPosition(string $section): int
    {
        $position = array_search($section, self::SECTION_ORDER, true);

        return $position === false ? count(self::SECTION_ORDER) : $position;
    }

    private function ensureMediaRowsExist(): void
    {
        PageContent::query()->firstOrCreate(['section_key' => 'brand', 'field_key' => 'logo_path'], ['field_value' => '']);
        PageContent::query()->firstOrCreate(['section_key' => 'seo', 'field_key' => 'og_image_path'], ['field_value' => '']);
    }
}
