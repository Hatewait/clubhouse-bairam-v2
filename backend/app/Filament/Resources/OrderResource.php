<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

use Filament\Resources\Resource;

use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Заказы';
    protected static ?string $pluralLabel     = 'Заказы';
    protected static ?string $modelLabel      = 'Заказ';
    protected static ?int    $navigationSort  = 60;

    /* =================== ФОРМА =================== */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Основное')
                        ->schema([
                            TextInput::make('number')->label('Номер заказа')->disabled()->dehydrated(false),

                            TextInput::make('client_name_snapshot')->label('Имя')->disabled()->dehydrated(false),
                            TextInput::make('client_phone_snapshot')->label('Телефон')->disabled()->dehydrated(false),
                            TextInput::make('client_email_snapshot')->label('Email')->disabled()->dehydrated(false),

                            TextInput::make('service_name_snapshot')->label('Услуга')->disabled()->dehydrated(false),
                            TextInput::make('price_snapshot')->label('Цена (за единицу)')->suffix('₽')->disabled()->dehydrated(false),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),

                    Section::make('Даты')
                        ->schema([
                            DatePicker::make('booking_date')->label('Дата начала')->native(false)->displayFormat('d.m.Y')->disabled()->dehydrated(false),
                            DatePicker::make('booking_end_date')->label('Дата завершения')->native(false)->displayFormat('d.m.Y')->disabled()->dehydrated(false),
                            TextInput::make('people_count')->label('Людей')->disabled()->dehydrated(false),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),

                    Section::make('Итого')
                        ->schema([
                            TextInput::make('final_total')->label('Сумма')->suffix('₽')->disabled()->dehydrated(false),
                        ])
                        ->columns(1)
                        ->columnSpanFull(),

                    Section::make('Управление')
                        ->schema([
                            Select::make('status')
                                ->label('Статус заказа')
                                ->options(Order::STATUSES)
                                ->required(),

                            Textarea::make('comment')
                                ->label('Комментарий менеджера')
                                ->rows(4),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ]),
            ])
            ->columns(1);
    }

    /* =================== ТАБЛИЦА =================== */
    public static function table(Table $table): Table
    {
        // Переменная для кнопки "Колонки" удалена

        // Роу-экшены
        $rowActions = [
            Tables\Actions\EditAction::make()->label(''),
        ];
        if (! app()->environment('production')) {
            $rowActions[] = Tables\Actions\DeleteAction::make()
                ->label('')
                ->hidden(fn (Order $record) => $record->status === Order::STATUS_ACTIVE);
        }

        // Бальк-экшены
        $bulkActions = [];
        if (! app()->environment('production')) {
            $bulkActions[] = Tables\Actions\DeleteBulkAction::make()
                ->requiresConfirmation()
                ->action(function ($records) {
                    // Фильтруем только заказы, которые НЕ активны
                    $nonActiveRecords = $records->filter(fn (Order $record) => $record->status !== Order::STATUS_ACTIVE);
                    
                    if ($nonActiveRecords->isEmpty()) {
                        \Filament\Notifications\Notification::make()
                            ->title('Невозможно удалить')
                            ->body('Нельзя удалять активные заказы')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $nonActiveRecords->each->delete();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Успешно удалено')
                        ->body('Удалено заказов: ' . $nonActiveRecords->count())
                        ->success()
                        ->send();
                });
        }

        return $table
            ->columns([
                TextColumn::make('row')->label('#')->rowIndex(),

                TextColumn::make('number')
                    ->label('№ Заказа')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('client_name_snapshot')
                    ->label('Клиент')
                    ->formatStateUsing(fn ($state, Order $r) => implode('<br>', array_filter([
                        $state,
                        $r->client_phone_snapshot ?: null,
                        $r->client_email_snapshot ?: null,
                    ])))
                    ->html()
                    ->searchable()
                    ->limit(60),

                TextColumn::make('service_name_snapshot')
                    ->label('Услуга')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('booking_date')
                    ->label('Даты')
                    ->formatStateUsing(function ($state, Order $r) {
                        $from = $r->booking_date?->format('d.m.Y');
                        $to   = $r->booking_end_date?->format('d.m.Y');
                        return (!$from) ? '—' : (($to && $to !== $from) ? "{$from} — {$to}" : $from);
                    })
                    ->sortable(),

                TextColumn::make('people_count')->label('Людей')->sortable(),

                TextColumn::make('days_count')
                    ->label('Дней')
                    ->getStateUsing(fn (Order $r) => $r->days_count),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Order::STATUS_ACTIVE    => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        Order::STATUS_COMPLETED => 'gray',
                        default                 => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => Order::STATUSES[$state] ?? $state),

                TextColumn::make('final_total')
                    ->label('Сумма')
                    ->money('RUB', true)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')

            // ВАЖНО: filters — сразу массив, без обёртки-closure.
            ->filters([
                // Диапазон по созданию
                Filter::make('created_between')
                    ->label('Дата создания')
                    ->form([
                        DatePicker::make('from')->label('С')->native(false)->displayFormat('d.m.Y')->closeOnDateSelection(),
                        DatePicker::make('to')->label('По')->native(false)->displayFormat('d.m.Y')->closeOnDateSelection(),
                    ])
                    ->query(fn (Builder $q, array $data) => $q
                        ->when($data['from'] ?? null, fn (Builder $qq, $v) => $qq->whereDate('created_at', '>=', $v))
                        ->when($data['to'] ?? null,   fn (Builder $qq, $v) => $qq->whereDate('created_at', '<=', $v))
                    )
                    ->indicateUsing(function (array $data): array {
                        $out = [];
                        if (!empty($data['from'])) $out[] = 'С ' . Carbon::parse($data['from'])->format('d.m.Y');
                        if (!empty($data['to']))   $out[] = 'По ' . Carbon::parse($data['to'])->format('d.m.Y');
                        return $out;
                    }),

                // Статус заказа
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(Order::STATUSES),
            ])

            ->headerActions([
                // Кнопка "Колонки" удалена
            ])

            ->actions($rowActions)
            ->bulkActions($bulkActions)
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(50);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\ViewOrder::route('/{record}'),
        ];
    }
}