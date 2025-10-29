<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Основные услуги';
    protected static ?string $modelLabel = 'Основная услуга';
    protected static ?string $pluralModelLabel = 'Основные услуги';
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(12)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Наименование')
                                ->columnSpan(8)
                                ->required()
                                ->maxLength(190),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Активна')
                                ->inline(false)
                                ->default(true)
                                ->columnSpan(4),
                        ]),

                        Forms\Components\Grid::make(12)->schema([
                            Forms\Components\Textarea::make('site_description')
                                ->label('Описание (для сайта)')
                                ->rows(6)
                                ->columnSpan(8),

                            Forms\Components\FileUpload::make('image_path')
                                ->label('Фото (для сайта)')
                                ->image()
                                ->imageEditor()
                                ->directory('services')
                                ->preserveFilenames()
                                ->downloadable()
                                ->openable()
                                ->columnSpan(4),
                        ]),
                    ])
                    ->columns(1),
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

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Статус')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активность')
                    ->boolean()
                    ->trueLabel('Только активные')
                    ->falseLabel('Только отключённые'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Редактировать'),
                Tables\Actions\DeleteAction::make()
                    ->label('Удалить')
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('frontend_services');
                        \Illuminate\Support\Facades\Cache::forget('frontend_bundles');
                    }),
            ])
            // ВАЖНО: не добавляем тут CreateAction, чтобы не было дублирования рядом с «Колонки»
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Удалить выбранные')
                    ->after(function () {
                        \Illuminate\Support\Facades\Cache::forget('frontend_services');
                        \Illuminate\Support\Facades\Cache::forget('frontend_bundles');
                    }),
                Tables\Actions\BulkAction::make('toggle_active')
                    ->label('Переключить активность')
                    ->icon('heroicon-o-eye')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->update(['is_active' => !$record->is_active]);
                        }
                        \Illuminate\Support\Facades\Cache::forget('frontend_services');
                        \Illuminate\Support\Facades\Cache::forget('frontend_bundles');
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}