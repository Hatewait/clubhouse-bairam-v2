<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'клиент';
    protected static ?string $pluralModelLabel = 'Клиенты';
    protected static ?string $navigationLabel = 'Клиенты';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(12)->schema([
                            TextInput::make('name')
                                ->label('Имя')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(4),

                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(
                                    table: 'clients',
                                    column: 'email',
                                    ignoreRecord: true
                                )
                                ->validationMessages([
                                    'unique' => 'Клиент с таким email уже существует.',
                                ])
                                ->columnSpan(4),

                            TextInput::make('phone')
                                ->label('Телефон')
                                ->tel()
                                ->required()
                                ->maxLength(50)
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, \Closure $fail) {
                                            $normalized = self::normalizePhone($value);
                                            if ($normalized === null) {
                                                $fail('Номер телефона содержит недопустимые символы. Введите корректный номер телефона.');
                                                return;
                                            }
                                            
                                            // Дополнительная проверка длины
                                            $digits = preg_replace('/\D+/', '', $normalized);
                                            if (strlen($digits) < 10 || strlen($digits) > 11) {
                                                $fail('Номер телефона должен содержать от 10 до 11 цифр.');
                                            }
                                        };
                                    },
                                ])
                                ->dehydrateStateUsing(fn ($state) => self::normalizePhone($state))
                                ->unique(
                                    table: 'clients',
                                    column: 'phone',
                                    ignoreRecord: true
                                )
                                ->validationMessages([
                                    'unique' => 'Клиент с таким телефоном уже существует.',
                                ])
                                ->columnSpan(4),
                        ]),

                        Textarea::make('manager_comment')
                            ->label('Комментарий менеджера')
                            ->rows(8)
                            ->autosize()
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ])
                    ->columns(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('№')
                    ->rowIndex()
                    ->alignCenter(),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Имя')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Телефон')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('Y-m-d H:i') // будет использовать глобальный timezone из config/app.php
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at_period')
                    ->label('Период создания')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('С даты'),
                        Forms\Components\DatePicker::make('until')->label('По дату'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actionsColumnLabel('Действие')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->label(''), // убрали подпись

                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->label('') // убрали подпись
                    ->visible(fn ($record) => !$record->applications()->exists()),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit'   => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    private static function normalizePhone(?string $raw): ?string
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === '') {
            return null; // Если нет цифр, возвращаем null
        }

        // Проверяем длину - если слишком много цифр, возвращаем null
        if (strlen($digits) > 11) {
            return null;
        }

        if (strlen($digits) === 11 && $digits[0] === '8') {
            $digits[0] = '7';
        }

        if (strlen($digits) === 10) {
            $digits = '7' . $digits;
        }

        if (strlen($digits) === 11 && $digits[0] === '7') {
            return '+' . $digits;
        }

        // Если длина не соответствует ожидаемой, возвращаем null
        if (strlen($digits) < 10) {
            return null;
        }

        return '+' . $digits;
    }

}