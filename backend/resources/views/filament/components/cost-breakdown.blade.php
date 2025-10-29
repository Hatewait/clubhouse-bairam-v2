@if(empty($breakdown))
    <div class="text-gray-500 text-sm">Нет данных для расчета</div>
@else
    <div class="space-y-1">
        @foreach($breakdown as $item)
            <div class="flex justify-between items-center py-1 px-2 bg-gray-50 rounded text-sm">
                <span class="font-bold text-gray-700">{{ $item['name'] }}</span>
                <span class="text-gray-600 {{ $item['type'] === 'service' ? 'italic' : '' }}">
                    {{ $item['type'] === 'service' ? 'Включено в стоимость' : $item['price'] }}
                </span>
            </div>
        @endforeach
    </div>
@endif
