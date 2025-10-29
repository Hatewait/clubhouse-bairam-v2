<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\View;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ViewOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    // Данные клиента
                    Section::make('Данные клиента')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('client_name_snapshot')
                                        ->label('Имя')
                                        ->disabled()
                                        ->dehydrated(false),
                                    
                                    TextInput::make('client_phone_snapshot')
                                        ->label('Телефон')
                                        ->disabled()
                                        ->dehydrated(false),
                                    
                                    TextInput::make('client_email_snapshot')
                                        ->label('Email')
                                        ->disabled()
                                        ->dehydrated(false),
                                ]),
                            
                            Grid::make(2)
                                ->schema([
                                    Textarea::make('client_comment')
                                        ->label('Комментарий клиента')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->rows(3)
                                        ->formatStateUsing(function ($record) {
                                            if (!$record) return '';
                                            
                                            // Получаем актуальные пожелания клиента из связанной заявки
                                            if ($record->application) {
                                                return $record->application->client_wishes ?? '';
                                            }
                                            
                                            // Фоллбек на сохраненный снэпшот
                                            return $record->client_comment ?? '';
                                        }),
                                    
                                    Textarea::make('comment')
                                        ->label('Комментарий менеджера')
                                        ->rows(3),
                                ]),
                        ])
                        ->columnSpanFull(),

                    // Услуги и параметры заказа
                    Section::make('Услуги и параметры заказа')
                        ->schema([
                            Select::make('bundle_display')
                                ->label('Формат отдыха')
                                ->disabled()
                                ->dehydrated(false)
                                ->options(function ($record) {
                                    if (!$record || !$record->service_name_snapshot) {
                                        return [];
                                    }
                                    return [$record->service_name_snapshot => $record->service_name_snapshot];
                                })
                                ->afterStateHydrated(function ($component, $state, $record) {
                                    if ($record && $record->service_name_snapshot) {
                                        $component->state($record->service_name_snapshot);
                                    }
                                })
                                ->helperText('Формат отдыха из заявки'),

                            Select::make('main_services_display')
                                ->label('Основные услуги')
                                ->multiple()
                                ->disabled()
                                ->dehydrated(false)
                                ->options(function ($record) {
                                    if (!$record || !$record->bundle_services_snapshot) {
                                        return [];
                                    }
                                    
                                    $services = json_decode($record->bundle_services_snapshot, true);
                                    if (!is_array($services)) {
                                        return [];
                                    }
                                    
                                    $options = [];
                                    foreach ($services as $service) {
                                        $serviceName = is_array($service) ? $service['name'] : $service;
                                        $serviceId = is_array($service) ? $service['id'] : null;
                                        $key = $serviceId ?: $serviceName;
                                        $options[$key] = $serviceName;
                                    }
                                    
                                    return $options;
                                })
                                ->afterStateHydrated(function ($component, $state, $record) {
                                    if ($record && $record->bundle_services_snapshot) {
                                        $services = json_decode($record->bundle_services_snapshot, true);
                                        if (is_array($services)) {
                                            $values = [];
                                            foreach ($services as $service) {
                                                $serviceId = is_array($service) ? $service['id'] : null;
                                                $serviceName = is_array($service) ? $service['name'] : $service;
                                                $key = $serviceId ?: $serviceName;
                                                $values[] = $key;
                                            }
                                            $component->state($values);
                                        }
                                    }
                                })
                                ->helperText('Основные услуги из выбранного формата отдыха')
                                ->placeholder('Основные услуги не выбраны')
                                ->columnSpanFull(),

                            Select::make('additional_services_display')
                                ->label('Дополнительные услуги')
                                ->multiple()
                                ->disabled()
                                ->dehydrated(false)
                                ->options(function ($record) {
                                    if (!$record || !$record->addons_snapshot) {
                                        return [];
                                    }
                                    
                                    $addons = json_decode($record->addons_snapshot, true);
                                    if (!is_array($addons)) {
                                        return [];
                                    }
                                    
                                    $options = [];
                                    foreach ($addons as $addon) {
                                        $addonName = is_array($addon) ? $addon['name'] : $addon;
                                        $addonId = is_array($addon) ? $addon['id'] : null;
                                        $key = $addonId ?: $addonName;
                                        $options[$key] = $addonName;
                                    }
                                    
                                    return $options;
                                })
                                ->afterStateHydrated(function ($component, $state, $record) {
                                    if ($record && $record->addons_snapshot) {
                                        $addons = json_decode($record->addons_snapshot, true);
                                        if (is_array($addons)) {
                                            $values = [];
                                            foreach ($addons as $addon) {
                                                $addonId = is_array($addon) ? $addon['id'] : null;
                                                $addonName = is_array($addon) ? $addon['name'] : $addon;
                                                $key = $addonId ?: $addonName;
                                                $values[] = $key;
                                            }
                                            $component->state($values);
                                        }
                                    }
                                })
                                ->helperText('Дополнительные услуги, выбранные для заявки')
                                ->placeholder('Дополнительные услуги не выбраны')
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),

                    // Даты и параметры
                    Section::make('Даты и параметры')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('booking_dates')
                                        ->label('Даты бронирования')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->formatStateUsing(function ($record) {
                                            if (!$record) return '—';
                                            $start = $record->booking_date ? $record->booking_date->format('d.m.Y') : '—';
                                            $end = $record->booking_end_date ? $record->booking_end_date->format('d.m.Y') : '—';
                                            return "{$start} - {$end}";
                                        }),
                                    
                                    TextInput::make('days_count')
                                        ->label('Количество дней')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->formatStateUsing(function ($record) {
                                            return $record ? $record->nights_count . ' дней' : '—';
                                        }),
                                    
                                    TextInput::make('people_count')
                                        ->label('Количество людей')
                                        ->disabled()
                                        ->dehydrated(false),
                                ]),
                        ])
                        ->columnSpanFull(),

                    // Статус и расчет стоимости
                    Section::make('Статус и расчет стоимости')
                        ->schema([
                            View::make('cost_breakdown')
                                ->label('Состав заказа')
                                ->view('filament.components.cost-breakdown')
                                ->viewData(function (?Order $record): array {
                                    if (!$record) {
                                        return ['breakdown' => []];
                                    }
                                    
                                    $breakdown = [];
                                    
                                    // Формат отдыха
                                    if ($record->service_name_snapshot) {
                                        $price = number_format($record->bundle_price_snapshot ?? 0, 0, ',', ' ') . ' ₽';
                                        $breakdown[] = [
                                            'name' => $record->service_name_snapshot,
                                            'price' => $price,
                                            'type' => 'bundle'
                                        ];
                                    }
                                    
                                    // Основные услуги
                                    if ($record->bundle_services_snapshot) {
                                        $services = json_decode($record->bundle_services_snapshot, true);
                                        if (is_array($services)) {
                                            foreach ($services as $service) {
                                                $serviceName = is_array($service) ? $service['name'] : $service;
                                                $breakdown[] = [
                                                    'name' => $serviceName,
                                                    'price' => 'включено',
                                                    'type' => 'service'
                                                ];
                                            }
                                        }
                                    }
                                    
                                    // Дополнительные услуги
                                    if ($record->addons_snapshot) {
                                        $addons = json_decode($record->addons_snapshot, true);
                                        if (is_array($addons)) {
                                            // Получаем связанную заявку для расчета цен
                                            $application = $record->application;
                                            $people = max(1, (int) $record->people_count);
                                            $nights = max(1, $record->nights_count);
                                            
                                            foreach ($addons as $addon) {
                                                $addonName = is_array($addon) ? $addon['name'] : $addon;
                                                $addonId = is_array($addon) ? $addon['id'] : null;
                                                
                                                // Получаем цену из связанной заявки
                                                $basePrice = 0;
                                                $pricePerPerson = false;
                                                $pricePerDay = false;
                                                
                                                if ($application && $addonId) {
                                                    $addonModel = $application->addons()->where('options.id', $addonId)->first();
                                                    if ($addonModel) {
                                                        $basePrice = $addonModel->price;
                                                        $pricePerPerson = $addonModel->price_per_person;
                                                        $pricePerDay = $addonModel->price_per_day;
                                                    }
                                                }
                                                
                                                // Рассчитываем итоговую цену с учетом модификаторов
                                                $finalPrice = $basePrice;
                                                $modifiers = [];
                                                
                                                if ($pricePerPerson && $people > 1) {
                                                    $finalPrice *= $people;
                                                    $modifiers[] = "за человека ({$people})";
                                                }
                                                
                                                if ($pricePerDay && $nights > 1) {
                                                    $finalPrice *= $nights;
                                                    $modifiers[] = "за сутки ({$nights})";
                                                }
                                                
                                                $price = number_format($finalPrice, 0, ',', ' ') . ' ₽';
                                                
                                                if (empty($modifiers)) {
                                                    $breakdown[] = [
                                                        'name' => $addonName,
                                                        'price' => $price,
                                                        'type' => 'addon'
                                                    ];
                                                } else {
                                                    $modifierStr = implode(' x ', $modifiers);
                                                    $breakdown[] = [
                                                        'name' => $addonName . ' x ' . $modifierStr,
                                                        'price' => $price,
                                                        'type' => 'addon'
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                    
                                    return ['breakdown' => $breakdown];
                                })
                                ->columnSpanFull(),

                            Grid::make(2)->schema([
                                Select::make('status')
                                    ->label('Статус заказа')
                                    ->options(Order::STATUSES)
                                    ->required(),

                                TextInput::make('final_total')
                                    ->label('Итоговая стоимость')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffix(' ₽')
                                    ->formatStateUsing(function ($record) {
                                        if (!$record) return '0';
                                        $total = $record->total_price ?? 0;
                                        return number_format($total, 0, ',', ' ');
                                    }),
                            ]),
                        ])
                        ->columnSpanFull(),
                ]),
            ])
            ->columns(1);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Сохранить')
                ->color('success')
                ->keyBindings(['mod+s']),
            
            $this->getCancelFormAction()
                ->label('Закрыть')
                ->color('danger')
                ->keyBindings(['escape']),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Сохранить')
            ->color('success')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Закрыть')
            ->color('danger')
            ->keyBindings(['escape']);
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Синхронизация статуса с заявкой происходит автоматически через модель Order
        Notification::make()
            ->title('Заказ обновлен')
            ->success()
            ->send();
    }
}