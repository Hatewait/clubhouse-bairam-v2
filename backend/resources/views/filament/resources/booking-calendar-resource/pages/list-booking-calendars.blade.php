<x-filament-panels::page>
    <div class="booking-calendar-container">
        <!-- Навигация по годам -->
        <div class="calendar-navigation">
            <div class="nav-controls">
                <button type="button" id="prevYear" class="nav-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <div class="year-display">
                    <span id="currentYear">{{ $currentYear }}</span>
                </div>
                <button type="button" id="nextYear" class="nav-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Календарь на весь год -->
        <div class="year-calendar-wrapper">
            @for($month = 1; $month <= 12; $month++)
                <div class="month-calendar">
                    <div class="month-header">
                        <h3 class="month-title">{{ $monthNames[$month] }}</h3>
                    </div>
                    
                    <div class="calendar-grid">
                        <!-- Заголовки дней недели -->
                        <div class="calendar-header">
                            <div class="day-header">П</div>
                            <div class="day-header">В</div>
                            <div class="day-header">С</div>
                            <div class="day-header">Ч</div>
                            <div class="day-header">П</div>
                            <div class="day-header">С</div>
                            <div class="day-header">В</div>
                        </div>

                        <!-- Дни календаря -->
                        <div class="calendar-days">
                            @foreach($yearData[$month]['days'] as $week)
                                @foreach($week as $day)
                                    <div class="calendar-day {{ $day['isCurrentMonth'] ? 'current-month' : 'other-month' }} {{ $day['isBooked'] ? 'booked' : 'available' }} {{ $day['isToday'] ? 'today' : '' }}">
                                        <span class="day-number">{{ $day['day'] }}</span>
                                        @if($day['isBooked'])
                                            <div class="booked-indicator"></div>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Легенда -->
        <div class="calendar-legend">
            <div class="legend-item">
                <div class="legend-color available"></div>
                <span>Доступно</span>
            </div>
            <div class="legend-item">
                <div class="legend-color booked"></div>
                <span>Забронировано</span>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .booking-calendar-container {
            max-width: 1400px;
            margin: 0 auto;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .calendar-navigation {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .nav-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #f8f9fa;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            background: #ffffff;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .nav-button:hover {
            background: #e9ecef;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .nav-button:active {
            transform: translateY(0);
        }

        .year-display {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            min-width: 120px;
            text-align: center;
        }

        .year-calendar-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2rem;
            overflow-x: auto;
            padding-bottom: 1rem;
        }

        .month-calendar {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            min-width: 280px;
            width: 280px;
            flex-shrink: 0;
        }

        .month-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .month-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            text-align: center;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0;
            min-width: 280px;
            width: 280px;
        }

        .calendar-header {
            display: contents;
        }

        .day-header {
            background: #f8f9fa;
            padding: 0.75rem 0.25rem;
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }

        .calendar-days {
            display: contents;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            border-right: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
            transition: none;
            cursor: default;
            min-width: 40px;
            width: 40px;
            height: 40px;
        }

        .calendar-day:nth-child(7n) {
            border-right: none;
        }

        .calendar-day.other-month {
            background: #f8f9fa;
            color: #adb5bd;
        }

        .calendar-day.current-month {
            background: #ffffff;
            color: #2c3e50;
        }

        .calendar-day.today {
            background: #e3f2fd;
        }

        .calendar-day.today .day-number {
            background: #2196f3;
            color: #ffffff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .calendar-day.booked {
            background: #ffebee;
            position: relative;
        }

        .calendar-day.booked .day-number {
            color: #d32f2f;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .booked-indicator {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: #f44336;
            border-radius: 50%;
        }

        .calendar-day.available .day-number {
            color: #2c3e50;
            font-size: 0.75rem;
        }

        .day-number {
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1;
        }

        .calendar-legend {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-color.available {
            background: #ffffff;
            border: 2px solid #e9ecef;
        }

        .legend-color.booked {
            background: #ffebee;
            border: 2px solid #f44336;
        }

        /* Убираем все hover эффекты */
        .calendar-day:hover {
            background: inherit !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .calendar-day.booked:hover {
            background: #ffebee !important;
        }

        .calendar-day.available:hover {
            background: #ffffff !important;
        }

        .calendar-day.other-month:hover {
            background: #f8f9fa !important;
        }

        .calendar-day.today:hover {
            background: #e3f2fd !important;
        }

        /* Адаптивность - горизонтальный скролл вместо сжатия */
        @media (max-width: 1200px) {
            .year-calendar-wrapper {
                justify-content: flex-start;
            }
        }

        @media (max-width: 900px) {
            .year-calendar-wrapper {
                justify-content: flex-start;
            }
        }

        @media (max-width: 600px) {
            .year-calendar-wrapper {
                justify-content: flex-start;
            }
            
            .month-calendar {
                min-width: 260px;
                width: 260px;
            }
            
            .calendar-grid {
                min-width: 260px;
                width: 260px;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentYear = {{ $currentYear }};

            const prevButton = document.getElementById('prevYear');
            const nextButton = document.getElementById('nextYear');
            const yearDisplay = document.getElementById('currentYear');

            function updateCalendar(year) {
                fetch(`/api/calendar/year?year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        updateCalendarDisplay(data, year);
                    })
                    .catch(error => {
                        console.error('Ошибка загрузки календаря:', error);
                    });
            }

            function updateCalendarDisplay(data, year) {
                yearDisplay.textContent = year;
                
                // Обновляем каждый месяц
                for (let month = 1; month <= 12; month++) {
                    const monthData = data.months[month];
                    if (monthData) {
                        updateMonthDisplay(month, monthData);
                    }
                }
            }

            function updateMonthDisplay(month, monthData) {
                const monthContainer = document.querySelector(`.month-calendar:nth-child(${month + 1})`);
                if (!monthContainer) return;

                const calendarDays = monthContainer.querySelector('.calendar-days');
                if (!calendarDays) return;

                let html = '';
                monthData.days.forEach(week => {
                    week.forEach(day => {
                        const classes = [
                            'calendar-day',
                            day.isCurrentMonth ? 'current-month' : 'other-month',
                            day.isBooked ? 'booked' : 'available',
                            day.isToday ? 'today' : ''
                        ].filter(Boolean).join(' ');

                        html += `
                            <div class="${classes}">
                                <span class="day-number">${day.day}</span>
                                ${day.isBooked ? '<div class="booked-indicator"></div>' : ''}
                            </div>
                        `;
                    });
                });
                
                calendarDays.innerHTML = html;
            }

            prevButton.addEventListener('click', function() {
                currentYear--;
                updateCalendar(currentYear);
            });

            nextButton.addEventListener('click', function() {
                currentYear++;
                updateCalendar(currentYear);
            });
        });
    </script>
    @endpush
</x-filament-panels::page>