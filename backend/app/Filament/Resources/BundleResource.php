<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BundleResource\Pages;
use App\Models\Bundle;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Forms\Components\GalleryUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class BundleResource extends Resource
{
    protected static ?string $model = Bundle::class;

    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Форматы отдыха';
    protected static ?string $pluralLabel     = 'Форматы отдыха';
    protected static ?string $modelLabel      = 'Формат отдыха';
    protected static ?string $pluralModelLabel = 'Форматы отдыха';
    protected static ?int    $navigationSort  = 30;
    
    public static function getPluralModelLabel(): string
    {
        return 'Форматы отдыха';
    }

    /* ========= ФОРМА ========= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    // Первая строка: поля
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Наименование')
                            ->required()
                            ->maxLength(190),

                        Forms\Components\TextInput::make('price')
                            ->label('Цена')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->suffix('₽')
                            ->helperText('Обязательное поле для расчета стоимости'),
                    ]),

                    // Вторая строка: чекбоксы
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true),

                        Forms\Components\Toggle::make('show_price_on_site')
                            ->label('Отображать на сайте')
                            ->default(true)
                            ->helperText('Если выключено, на сайте будет отображаться "По запросу"')
                            ->hidden(),
                    ]),
                ]),

            Forms\Components\Section::make('Контент для сайта')
                ->description('Описание и изображение, которые будут отображаться на сайте')
                ->schema([
                    Forms\Components\Grid::make(12)->schema([
                        Forms\Components\Textarea::make('site_subtitle')
                            ->label('Подзаголовок (для сайта)')
                            ->rows(2)
                            ->columnSpan(8)
                            ->helperText('Краткий подзаголовок под основным заголовком'),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание (для сайта)')
                            ->rows(6)
                            ->columnSpan(8)
                            ->helperText('Подробное описание формата отдыха для клиентов'),

                        Forms\Components\FileUpload::make('gallery')
                            ->label('Галерея фотографий')
                            ->image()
                            ->imageEditor()
                            ->directory('bundles')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->imagePreviewHeight('120')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('600')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->maxFiles(10)
                            ->acceptedFileTypes(['image/*'])
                            ->columnSpanFull()
                            ->helperText('Загружайте фотографии для галереи. Первое фото в списке будет титульным и отображаться первой на сайте. Перетаскивайте для изменения порядка.'),
                    ]),
                ])->collapsed(false),

            Forms\Components\Section::make('Включённые услуги')
                ->description('Выберите услуги, которые входят в данный формат отдыха. Клиенты будут получать все выбранные услуги при бронировании формата отдыха.')
                ->schema([
                    Forms\Components\Select::make('services')
                        ->label('Услуги в формате отдыха')
                        ->relationship('services', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->getOptionLabelFromRecordUsing(fn (Service $record): string => "{$record->name}")
                        ->helperText('Можно выбрать несколько услуг. Используйте поиск для быстрого поиска по названию.')
                        ->placeholder('Выберите услуги для формата отдыха...')
                        ->columnSpanFull(),
                ])->collapsed(false),
        ])->columns(1);
    }

    /* ========= ТАБЛИЦА ========= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),

                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\IconColumn::make('gallery_status')
                    ->label('Галерея')
                    ->getStateUsing(function ($record) {
                        // Получаем галерею из записи
                        $gallery = $record->gallery;
                        
                        // Проверяем есть ли фото в галерее
                        if (!$gallery || !is_array($gallery) || empty($gallery)) {
                            return false; // Нет фото
                        }
                        
                        return true; // Есть фото
                    })
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->alignCenter(),

                TextColumn::make('price')
                    ->label('Цена')
                    ->getStateUsing(function ($record) {
                        $price = $record->price;
                        $showOnSite = $record->show_price_on_site;
                        
                        if (!$showOnSite) {
                            return 'По запросу';
                        }
                        return number_format($price, 0, ',', ' ') . ' ₽';
                    })
                    ->sortable(),


                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Активность')
                    ->placeholder('Все')
                    ->trueLabel('Только активные')
                    ->falseLabel('Только отключённые')
                    ->queries(
                        true: fn (Builder $q) => $q->where('is_active', true),
                        false: fn (Builder $q) => $q->where('is_active', false),
                        blank: fn (Builder $q) => $q
                    ),


                Filter::make('services_count')
                    ->label('Количество услуг')
                    ->form([
                        Forms\Components\Select::make('services_count')
                            ->label('Услуг')
                            ->options([
                                '0' => 'Без услуг',
                                '1-2' => '1-2 услуги',
                                '3-5' => '3-5 услуг',
                                '6+' => '6+ услуг',
                            ])
                            ->placeholder('Все'),
                    ])
                    ->query(fn (Builder $q, array $data) => match ($data['services_count'] ?? null) {
                        '0' => $q->whereDoesntHave('services'),
                        '1-2' => $q->has('services', '<=', 2),
                        '3-5' => $q->has('services', '>=', 3)->has('services', '<=', 5),
                        '6+' => $q->has('services', '>=', 6),
                        default => $q,
                    }),
            ])
            // ВАЖНО: тут больше НЕТ CreateAction — чтобы не было второй кнопки в таблице
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->hidden(fn (Bundle $record) => $record->hasRelatedRecords())
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('frontend_bundles');
                        \Illuminate\Support\Facades\Cache::forget('frontend_services');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        // Фильтруем только форматы отдыха без связанных записей
                        $safeRecords = $records->filter(fn (Bundle $record) => !$record->hasRelatedRecords());
                        
                        if ($safeRecords->isEmpty()) {
                            Notification::make()
                                ->title('Невозможно удалить')
                                ->body('Нельзя удалять форматы отдыха, на основе которых созданы заявки или заказы')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $safeRecords->each->delete();
                        
                        Notification::make()
                            ->title('Успешно удалено')
                            ->body('Удалено форматов отдыха: ' . $safeRecords->count())
                            ->success()
                            ->send();
                    })
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('frontend_bundles');
                        \Illuminate\Support\Facades\Cache::forget('frontend_services');
                    }),
                Tables\Actions\BulkAction::make('toggle_active')
                    ->label('Переключить активность')
                    ->icon('heroicon-o-eye')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->update(['is_active' => !$record->is_active]);
                        }
                        \Illuminate\Support\Facades\Cache::forget('frontend_bundles');
                        \Illuminate\Support\Facades\Cache::forget('frontend_services');
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBundles::route('/'),
            'create' => Pages\CreateBundle::route('/create'),
            'edit'   => Pages\EditBundle::route('/{record}/edit'),
        ];
    }
}