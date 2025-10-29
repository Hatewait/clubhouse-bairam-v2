<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Models\Client;
use App\Models\Service;
use App\Models\Bundle;
use App\Models\Option;
use App\Services\CalendarService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon  = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Заявки';
    protected static ?string $pluralLabel     = 'Заявки';
    protected static ?string $modelLabel      = 'Заявка';
    protected static ?int    $navigationSort  = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    // БЛОК 1: Данные клиента
                    Section::make('Данные клиента')
                        ->schema([
                            Grid::make(12)->schema([
                                // Левая колонка - поиск клиента и его данные
                                Group::make()->schema([
                                    Select::make('client_id')
                                        ->label('Клиент')
                                        ->options(function () {
                                            return Client::orderBy('name')->pluck('name', 'id')->toArray();
                                        })
                                        ->getSearchResultsUsing(function (string $search): array {
                                            return Client::searchText($search)
                                                ->orderBy('name')
                                                ->limit(50)
                                                ->get()
                                                ->mapWithKeys(fn (Client $client) => [
                                                    $client->id => $client->name . ' — ' . ($client->phone_pretty ?? '') . ' — ' . ($client->email ?? '')
                                                ])
                                                ->toArray();
                                        })
                                        ->getOptionLabelFromRecordUsing(
                                            fn (Client $c) => trim($c->name . ' — ' . ($c->phone_pretty ?? '') . ' — ' . ($c->email ?? ''))
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->reactive()
                                        ->disabled(fn (?Application $record) => $record && $record->order()->exists())
                                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                            if ($state) {
                                                $client = Client::find($state);
                                                if ($client) {
                                                    $set('client_name_view', $client->name ?? '');
                                                    $set('client_phone_view', $client->phone_pretty ?? '');
                                                    $set('client_email_view', $client->email ?? '');
                                                }
                                            } else {
                                                $set('client_name_view', '');
                                                $set('client_phone_view', '');
                                                $set('client_email_view', '');
                                            }
                                        })
                                        ->helperText('Поиск по имени, телефону или email клиента'),

                                    TextInput::make('client_name_view')
                                        ->label('Имя клиента')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->extraAttributes([
                                            'class' => 'cursor-pointer select-all',
                                            'onclick' => 'copyToClipboard(this.value)',
                                            'title' => 'Нажмите для копирования'
                                        ])
                                        ->afterStateHydrated(fn (TextInput $c, $state, ?Application $r) => $c->state($r?->client?->name ?? '')),

                                    TextInput::make('client_phone_view')
                                        ->label('Телефон клиента')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->extraAttributes([
                                            'class' => 'cursor-pointer select-all',
                                            'onclick' => 'copyToClipboard(this.value)',
                                            'title' => 'Нажмите для копирования'
                                        ])
                                        ->afterStateHydrated(fn (TextInput $c, $state, ?Application $r) => $c->state($r?->client?->phone_pretty ?? '')),

                                    TextInput::make('client_email_view')
                                        ->label('Email клиента')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->extraAttributes([
                                            'class' => 'cursor-pointer select-all',
                                            'onclick' => 'copyToClipboard(this.value)',
                                            'title' => 'Нажмите для копирования'
                                        ])
                                        ->afterStateHydrated(fn (TextInput $c, $state, ?Application $r) => $c->state($r?->client?->email ?? '')),
                                ])->columnSpan(6),

                                // Правая колонка - пожелания клиента и комментарий менеджера
                                Group::make()->schema([
                                    Textarea::make('client_wishes')
                                        ->label('Пожелания клиента (с сайта)')
                                        ->rows(4)
                                        ->disabled()
                                        ->dehydrated(false),

                                    Textarea::make('comment')
                                        ->label('Комментарий менеджера')
                                        ->rows(4)
                                        ->placeholder('Введите комментарий менеджера...')
                                        ->helperText('Комментарий виден только администраторам'),
                                ])->columnSpan(6),
                            ]),
                        ])
                        ->columnSpanFull(),

                    // БЛОК 2: Данные по заявке
                    Section::make('Данные по заявке')
                        ->schema([
                            Select::make('bundle_id')
                                ->label('Формат отдыха')
                                ->relationship('bundle', 'name')
                                ->getOptionLabelFromRecordUsing(
                                    fn (Bundle $bundle) => $bundle->name . ' (' . $bundle->price_pretty . ')'
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->reactive()
                                ->disabled(fn (?Application $record) => $record && $record->order()->exists())
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::recalcTotal($get, $set);
                                    self::updateServicesFromBundle($get, $set);
                                }),

                            Select::make('services')
                                ->label('Основные услуги')
                                ->relationship('services', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->getOptionLabelFromRecordUsing(
                                    fn ($record) => $record->name
                                )
                                ->reactive()
                                ->disabled(fn (?Application $record) => $record && $record->order()->exists())
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::recalcTotal($get, $set);
                                })
                                ->columnSpanFull()
                                ->helperText('Основные услуги из выбранного формата отдыха. Можно добавлять и удалять услуги вручную.')
                                ->placeholder('Выберите основные услуги для заявки...'),

                            Select::make('addons')
                                ->label('Дополнительные услуги')
                                ->relationship('addons', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->getOptionLabelFromRecordUsing(
                                    fn ($record) => $record->name . ' (' . number_format($record->price, 0, ',', ' ') . ' ₽)'
                                )
                                ->reactive()
                                ->disabled(fn (?Application $record) => $record && $record->order()->exists())
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::recalcTotal($get, $set);
                                })
                                ->columnSpanFull()
                                ->helperText('Можно выбрать несколько услуг. Используйте поиск для быстрого поиска по названию.')
                                ->placeholder('Выберите дополнительные услуги для заявки...'),
                        ])
                        ->columnSpanFull(),

                    Section::make('Даты и параметры')
                        ->schema([
                            DatePicker::make('booking_date')
                                ->label('Дата с')
                                ->native(false)
                                ->displayFormat('d.m.Y')
                                ->closeOnDateSelection()
                                ->nullable()
                                ->required()
                                ->reactive()
                                ->live()
                                ->disabled(fn (?Application $record) => $record && $record->order()->exists())
                                ->disabledDates(function (Get $get, ?Application $record) {
                                    $calendarService = app(CalendarService::class);
                                    return $calendarService->getBlockedDatesForDatePicker(
                                        null, // начальная дата не выбрана
                                        null, // конечная дата не выбрана
                                        $record?->id // исключаем текущую заявку при редактировании
                                    );
                                })
                                ->suffixAction(
                                    FormAction::make('clear_booking_date')
                                        ->tooltip('Очистить дату «с»')
                                        ->icon('heroicon-m-x-mark')
                                        ->action(function (Get $get, Set $set) {
                                            $set('booking_date', null);
                                            $set('booking_end_date', null); // также очищаем конечную дату
                                            self::recalcTotal($get, $set);
                                        })
                                )
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    // Очищаем конечную дату при изменении начальной
                                    $set('booking_end_date', null);
                                    self::recalcTotal($get, $set);
                                }),

                            DatePicker::make('booking_end_date')
                                ->label('Дата по')
                                ->native(false)
                                ->displayFormat('d.m.Y')
                                ->required()
                                ->helperText('Минимум 3 дня (2 ночи), максимум 10 дней. Учитываются забронированные диапазоны.')
                                ->disabled(fn (Get $get, ?Application $record) => !$get('booking_date') || ($record && $record->order()->exists()))
                                ->reactive()
                                ->live()
                                ->minDate(fn (Get $get) => $get('booking_date') ? Carbon::parse($get('booking_date'))->addDays(2) : null)
                                ->maxDate(fn (Get $get) => $get('booking_date') ? Carbon::parse($get('booking_date'))->addDays(9) : null)
                                ->rules([
                                    'required',
                                    'after:booking_date',
                                ])
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::recalcTotal($get, $set);
                                    self::validateDateRange($get, $set);
                                }),

                            TextInput::make('people_count')
                                ->label('Людей')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->maxValue(999)
                                ->required()
                                ->reactive()
                                ->disabled(fn (?Application $record) => $record && $record->order()->exists())
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::recalcTotal($get, $set);
                                }),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),

                    // БЛОК 4: Статус и расчет стоимости
                    Section::make('Статус и расчет стоимости')
                        ->schema([
                            \Filament\Forms\Components\View::make('cost_breakdown')
                                ->label('Состав заявки')
                                ->view('filament.components.cost-breakdown')
                                ->viewData(function (?Application $record): array {
                                    if (!$record) {
                                        return ['breakdown' => []];
                                    }
                                    
                                    $breakdown = [];
                                    
                                    // Формат отдыха
                                    if ($record->bundle) {
                                        $price = number_format($record->bundle->price, 0, ',', ' ') . ' ₽';
                                        $breakdown[] = [
                                            'name' => $record->bundle->name,
                                            'price' => $price,
                                            'type' => 'bundle'
                                        ];
                                    }
                                    
                                    // Основные услуги
                                    if ($record->services->isNotEmpty()) {
                                        foreach ($record->services as $service) {
                                            $breakdown[] = [
                                                'name' => $service->name,
                                                'price' => 'включено',
                                                'type' => 'service'
                                            ];
                                        }
                                    }
                                    
                                    // Дополнительные услуги
                                    if ($record->addons->isNotEmpty()) {
                                        foreach ($record->addons as $addon) {
                                            $qty = $addon->pivot->quantity ?? 1;
                                            $basePrice = $addon->price;
                                            $people = max(1, (int) $record->people_count);
                                            $nights = max(1, $record->nights_count);
                                            
                                            $modifiers = [];
                                            $finalPrice = $basePrice;
                                            
                                            if ($addon->price_per_person) {
                                                $modifiers[] = "за человека ({$people})";
                                                $finalPrice *= $people;
                                            }
                                            if ($addon->price_per_day) {
                                                $modifiers[] = "за сутки ({$nights})";
                                                $finalPrice *= $nights;
                                            }
                                            
                                            $price = number_format($finalPrice, 0, ',', ' ') . ' ₽';
                                            
                                            if (empty($modifiers)) {
                                                $breakdown[] = [
                                                    'name' => $addon->name,
                                                    'price' => $price,
                                                    'type' => 'addon'
                                                ];
                                            } else {
                                                $modifierStr = implode(' x ', $modifiers);
                                                $breakdown[] = [
                                                    'name' => $addon->name . ' x ' . $modifierStr,
                                                    'price' => $price,
                                                    'type' => 'addon'
                                                ];
                                            }
                                        }
                                    }
                                    
                                    return ['breakdown' => $breakdown];
                                })
                                ->columnSpanFull(),

                            Grid::make(2)->schema([
                                Select::make('status')
                                    ->label('Статус')
                                    ->options(Application::STATUSES)
                                    ->default(Application::STATUS_NEW)
                                    ->required()
                                    ->disabled(fn (?Application $record) => $record && $record->order()->exists()),

                                TextInput::make('total_price')
                                    ->label('Итоговая стоимость')
                                    ->disabled()
                                    ->numeric()
                                    ->suffix('₽')
                                    ->helperText('Рассчитывается автоматически на основе формата отдыха и опций с учетом модификаторов')
                                    ->dehydrated(true)
                                    ->dehydrateStateUsing(fn ($state) => (int) $state)
                                    ->afterStateHydrated(function (TextInput $component, $state, Get $get, ?Application $record) {
                                        if ($record) {
                                            // Используем метод calculateTotal() для правильного расчета
                                            $total = $record->calculateTotal();
                                            $component->state($total);
                                        }
                                    }),
                            ]),
                        ])
                        ->columnSpanFull(),
                ]),
            ])
            ->columns(1);
    }

    private static function recalcTotal(Get $get, Set $set): void
    {
        $bundleId = (int)($get('bundle_id') ?? 0);
        $people   = (int)($get('people_count') ?? 1);
        $fromStr  = $get('booking_date');
        $toStr    = $get('booking_end_date');
        
        // Рассчитываем количество ночей из дат
        $nights = 2; // По умолчанию
        if ($fromStr && $toStr) {
            $start = Carbon::parse($fromStr);
            $end = Carbon::parse($toStr);
            $nights = max(1, $start->diffInDays($end));
        }

        $total = 0;

        // Цена формата отдыха
        if ($bundleId) {
            $bundle = Bundle::find($bundleId);
            if ($bundle) {
                $total += (int)$bundle->price;
            }
        }

        // Дополнительные опции с модификаторами
        $addonIds = $get('addons') ?? [];
        if (!empty($addonIds)) {
            $addons = Option::whereIn('id', $addonIds)->get();
            foreach ($addons as $addon) {
                $price = (int)$addon->price;
                
                // Применяем множители
                if ($addon->price_per_person) {
                    $price *= $people;
                }
                if ($addon->price_per_day) {
                    $price *= $nights;
                }
                
                $total += $price;
            }
        }

        $set('total_price', $total);
    }

    private static function updateServicesFromBundle(Get $get, Set $set): void
    {
        $bundleId = (int)($get('bundle_id') ?? 0);
        
        if ($bundleId) {
            $bundle = Bundle::find($bundleId);
            if ($bundle) {
                // Получаем ID услуг из формата отдыха
                $serviceIds = $bundle->services->pluck('id')->toArray();
                $set('services', $serviceIds);
            }
        } else {
            // Если формат отдыха не выбран, очищаем услуги
            $set('services', []);
        }
    }

    public static function table(Table $table): Table
    {
        // Переменные для кнопки "Колонки" удалены

        return $table
            ->columns([
                TextColumn::make('row')->label('#')->rowIndex(),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('client.name')
                    ->label('Клиент')
                    ->formatStateUsing(function ($state, Application $record) {
                        $lines = array_filter([
                            $state,
                            $record->client?->phone_pretty ?: null,
                            $record->client?->email ?: null,
                        ]);
                        return implode('<br>', $lines);
                    })
                    ->html()
                    ->searchable()
                    ->limit(60),

                TextColumn::make('bundle.name')
                    ->label('Формат отдыха')
                    ->formatStateUsing(function ($state, Application $record) {
                        if (!$state) return '—';
                        $bundle = $record->bundle;
                        return $bundle ? $bundle->name : $state;
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('booking_date')
                    ->label('Даты')
                    ->formatStateUsing(function ($state, Application $record) {
                        $from = $record->booking_date?->format('d.m.Y');
                        $to   = $record->booking_end_date?->format('d.m.Y');
                        return (!$from) ? '—' : (($to && $to !== $from) ? "{$from} — {$to}" : $from);
                    })
                    ->sortable(),

                TextColumn::make('people_count')->label('Людей')->sortable(),

                TextColumn::make('days_count')
                    ->label('Дней')
                    ->getStateUsing(fn (Application $r) => $r->days_count),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Application::STATUS_NEW       => 'warning',
                        Application::STATUS_PAID      => 'success',
                        Application::STATUS_CANCELLED => 'danger',
                        Application::STATUS_COMPLETED => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => Application::STATUSES[$state] ?? $state),

                TextColumn::make('total_price_calc')
                    ->label('Сумма')
                    ->formatStateUsing(function ($state, Application $record) {
                        $calculatedTotal = $record->calculateTotal();
                        return number_format($calculatedTotal, 0, ',', ' ') . ' ₽';
                    })
                    ->sortable()
                    ->getStateUsing(fn (Application $record) => $record->calculateTotal()),

                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(Application::STATUSES),

                Filter::make('created_range')
                    ->label('Создана (диапазон)')
                    ->form([
                        DatePicker::make('created_from')->label('С')->native(false)->displayFormat('d.m.Y'),
                        DatePicker::make('created_until')->label('По')->native(false)->displayFormat('d.m.Y'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                // Кнопка "Колонки" удалена
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->visible(fn ($record) => !$record->order()->exists()),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->visible(fn ($record) => !$record->order()->exists()),
            ])
            ->bulkActions([])
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(50);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit'   => Pages\EditApplication::route('/{record}/edit'),
        ];
    }

    private static function validateDateRange(Get $get, Set $set): void
    {
        $bookingDate = $get('booking_date');
        $bookingEndDate = $get('booking_end_date');
        
        if ($bookingDate && $bookingEndDate) {
            try {
                $start = Carbon::parse($bookingDate);
                $end = Carbon::parse($bookingEndDate);
                $days = $start->diffInDays($end);
                
                if ($days < 2) {
                    // Показываем мягкое предупреждение
                    Notification::make()
                        ->title('Предупреждение')
                        ->body('Минимум 3 дня (2 ночи). Выбранный диапазон слишком короткий.')
                        ->warning()
                        ->send();
                } elseif ($days > 9) {
                    // Показываем мягкое предупреждение
                    Notification::make()
                        ->title('Предупреждение')
                        ->body('Максимум 10 дней. Выбранный диапазон слишком длинный.')
                        ->warning()
                        ->send();
                }
                
                // Проверяем пересечения с забронированными диапазонами
                $calendarService = app(\App\Services\CalendarService::class);
                $conflictCheck = $calendarService->checkDateRangeConflict(
                    $start->format('Y-m-d'),
                    $end->format('Y-m-d')
                );
                
                if ($conflictCheck['has_conflicts']) {
                    $conflictMessages = [];
                    foreach ($conflictCheck['conflicts'] as $conflict) {
                        if ($conflict['type'] === 'blocked') {
                            $conflictMessages[] = "Дата {$conflict['date']} заблокирована: {$conflict['reason']}";
                        } elseif ($conflict['type'] === 'application') {
                            $conflictMessages[] = "Пересечение с заявкой #{$conflict['id']} ({$conflict['client_name']}) на период {$conflict['start_date']} - {$conflict['end_date']}";
                        } elseif ($conflict['type'] === 'order') {
                            $conflictMessages[] = "Пересечение с заказом {$conflict['number']} ({$conflict['client_name']}) на период {$conflict['start_date']} - {$conflict['end_date']}";
                        }
                    }
                    
                    Notification::make()
                        ->title('Конфликт дат')
                        ->body('Выбранный период пересекается с уже забронированными датами: ' . implode('; ', $conflictMessages))
                        ->danger()
                        ->send();
                }
            } catch (\Exception $e) {
                // Игнорируем ошибки парсинга дат
            }
        }
    }

}