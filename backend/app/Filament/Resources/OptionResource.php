<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionResource\Pages;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OptionResource extends Resource
{
    protected static ?string $model = Option::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Дополнительные услуги';
    protected static ?string $pluralModelLabel = 'Дополнительные услуги';
    protected static ?string $modelLabel = 'Дополнительная услуга';
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([

                // Левая колонка
                Forms\Components\Section::make('Основное')
                    ->columns(12)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Наименование')
                            ->required()
                            ->maxLength(190)
                            ->columnSpan(8),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Активна')
                            ->inline(false)
                            ->columnSpan(4)
                            ->default(true),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание на сайт')
                            ->rows(5)
                            ->columnSpan(12),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Фото (для сайта)')
                            ->image()
                            ->imageEditor()
                            ->directory('options')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->columnSpan(12),

                        Forms\Components\TextInput::make('price')
                            ->label('Цена, ₽')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->columnSpan(12)
                            ->helperText('Обязательное поле для расчета стоимости'),

                        Forms\Components\Toggle::make('show_price_on_site')
                            ->label('Отображать на сайте')
                            ->default(true)
                            ->columnSpan(12)
                            ->helperText('Если выключено, на сайте будет отображаться "По запросу"')
                            ->hidden(),

                        Forms\Components\Checkbox::make('price_per_day')
                            ->label('За сутки')
                            ->helperText('Умножать цену на количество дней в заявке')
                            ->columnSpan(6),

                        Forms\Components\Checkbox::make('price_per_person')
                            ->label('За человека')
                            ->helperText('Умножать цену на количество людей в заявке')
                            ->columnSpan(6),
                    ])->columnSpan(12),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('image_status')
                    ->label('Фото')
                    ->getStateUsing(function ($record) {
                        return !empty($record->image_path);
                    })
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Цена, ₽')
                    ->formatStateUsing(function ($state, Option $record) {
                        if (!$record->show_price_on_site) {
                            return 'По запросу';
                        }
                        
                        $price = number_format($state, 0, ',', ' ') . ' ₽';
                        $multipliers = [];
                        
                        if ($record->price_per_person) {
                            $multipliers[] = 'за чел.';
                        }
                        if ($record->price_per_day) {
                            $multipliers[] = 'за сутки';
                        }
                        
                        if (!empty($multipliers)) {
                            $price .= ' (' . implode(' + ', $multipliers) . ')';
                        }
                        
                        return $price;
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активность')
                    ->trueLabel('Только активные')
                    ->falseLabel('Только отключенные')
                    ->placeholder('Все'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Редактировать'),
                Tables\Actions\DeleteAction::make()
                    ->label('Удалить')
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('frontend_options');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Удалить выбранные')
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('frontend_options');
                    }),
                Tables\Actions\BulkAction::make('toggle_active')
                    ->label('Переключить активность')
                    ->icon('heroicon-o-eye')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->update(['is_active' => !$record->is_active]);
                        }
                        \Illuminate\Support\Facades\Cache::forget('frontend_options');
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOptions::route('/'),
            'create' => Pages\CreateOption::route('/create'),
            'edit'   => Pages\EditOption::route('/{record}/edit'),
        ];
    }
}