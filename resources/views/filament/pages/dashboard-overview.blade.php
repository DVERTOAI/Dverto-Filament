<x-filament-panels::page class="ac-dashboard-page ac-dash-index-page">

    <div class="ac-dash-index">

        @include('filament.pages.partials.dashboard-tabs', ['active' => 'overview'])

        {{-- Alerts --}}
        <div class="ac-dash-alerts">
            @foreach ($alerts as $alert)
            <article class="ac-dash-alert ac-dash-alert--{{ $alert['tone'] }}">
                <span class="ac-dash-alert-icon" aria-hidden="true">
                    @svg($alert['icon'], 'w-4 h-4')
                </span>
                <div class="ac-dash-alert-copy">
                    <p class="ac-dash-alert-title">{{ $alert['title'] }}</p>
                    <p class="ac-dash-alert-sub">{{ $alert['subtitle'] }}</p>
                </div>
            </article>
            @endforeach
        </div>

        {{-- KPI stats bar --}}
        <section class="ac-dashboard-stats ac-dash-stats-bar">
            @php
            $statTones = ['blue' => 'blue', 'teal' => 'green', 'orange' => 'orange', 'red' => 'violet'];
            @endphp
            @foreach ($kpis as $kpi)
            <article class="ac-dashboard-stat ac-dashboard-stat--{{ $statTones[$kpi['color']] ?? 'blue' }}">
                <span class="ac-dashboard-stat-icon" aria-hidden="true">
                    @svg($kpi['icon'], 'w-5 h-5')
                </span>
                <div class="ac-dashboard-stat-copy">
                    <p class="ac-dashboard-stat-label">{{ $kpi['label'] }}</p>
                    <div class="ac-dashboard-stat-value-row">
                        <span class="ac-dashboard-stat-value">
                            {{ $kpi['value'] }}@if($kpi['suffix'])<span class="ac-dash-kpi-suffix">{{ $kpi['suffix'] }}</span>@endif
                        </span>
                        <span class="ac-dashboard-stat-trend">{{ $kpi['badge'] }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </section>

        {{-- Charts row --}}
        <div class="ac-dash-grid ac-dash-grid--charts">
            <section class="ac-dash-panel ac-dash-panel--wide">
                <header class="ac-dash-panel-head">
                    <h3 class="ac-dash-panel-title">Monthly revenue trend</h3>
                    <span class="ac-dash-panel-meta">{{ $revenue['period'] }}</span>
                </header>
                <div class="ac-dash-chart ac-dash-chart--bar" wire:ignore>
                    <canvas id="revChart" role="img" aria-label="Monthly revenue bar chart"></canvas>
                </div>
            </section>

            <section class="ac-dash-panel">
                <header class="ac-dash-panel-head">
                    <h3 class="ac-dash-panel-title">Case types</h3>
                    <span class="ac-dash-panel-meta">This month</span>
                </header>
                <div class="ac-dash-chart ac-dash-chart--donut" wire:ignore>
                    <canvas id="caseChart" role="img" aria-label="Case types donut chart"></canvas>
                </div>
                <div class="ac-dash-legend">
                    @foreach ($caseTypes as $ct)
                    <div class="ac-dash-legend-row">
                        <span class="ac-dash-legend-label">{{ $ct['label'] }}</span>
                        <div class="ac-dash-legend-track">
                            <span class="ac-dash-legend-fill" style="width:{{ $ct['pct'] }}%;background:{{ $ct['color'] }}"></span>
                        </div>
                        <span class="ac-dash-legend-pct">{{ $ct['pct'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Three-column row --}}
        <div class="ac-dash-grid ac-dash-grid--triple">
            <section class="ac-dash-panel">
                <header class="ac-dash-panel-head">
                    <h3 class="ac-dash-panel-title">Department load</h3>
                    <span class="ac-dash-panel-meta">This week</span>
                </header>
                <div class="ac-dash-bars">
                    @foreach ($departments as $dept)
                    <div class="ac-dash-bar-row">
                        <span class="ac-dash-bar-dot" style="background:{{ $dept['color'] }}"></span>
                        <span class="ac-dash-bar-label">{{ $dept['name'] }}</span>
                        <div class="ac-dash-bar-track">
                            <span class="ac-dash-bar-fill" style="width:{{ $dept['pct'] }}%;background:{{ $dept['color'] }}"></span>
                        </div>
                        <span class="ac-dash-bar-pct {{ $dept['pctClass'] }}">{{ $dept['pct'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </section>

            <section class="ac-dash-panel">
                <header class="ac-dash-panel-head">
                    <h3 class="ac-dash-panel-title">Today's schedule</h3>
                    <span class="ac-dash-panel-meta">12 appointments</span>
                </header>
                <ul class="ac-dash-schedule">
                    @foreach ($schedule as $appt)
                    <li class="ac-dash-schedule-row">
                        <span class="ac-dash-schedule-time">{{ $appt['time'] }}</span>
                        <span class="ac-dash-schedule-avatar {{ $appt['avatarBg'] }}">{{ $appt['initials'] }}</span>
                        <span class="ac-dash-schedule-name">{{ $appt['name'] }}</span>
                        <span class="ac-dash-schedule-tag {{ $appt['tagBg'] }}">{{ $appt['type'] }}</span>
                    </li>
                    @endforeach
                </ul>
            </section>

            <section class="ac-dash-panel">
                <header class="ac-dash-panel-head">
                    <h3 class="ac-dash-panel-title">Average vitals</h3>
                    <span class="ac-dash-panel-chip ac-dash-panel-chip--danger">ICU</span>
                </header>
                <div class="ac-dash-vitals">
                    @foreach ($vitals as $vital)
                    <article class="ac-dash-vital">
                        <p class="ac-dash-vital-label">
                            @svg($vital['icon'], 'w-3.5 h-3.5')
                            {{ $vital['label'] }}
                        </p>
                        <p class="ac-dash-vital-value">{{ $vital['value'] }}</p>
                        <p class="ac-dash-vital-unit">{{ $vital['unit'] }}</p>
                        <span class="ac-dash-vital-status {{ $vital['statusClass'] }}">{{ $vital['status'] }}</span>
                    </article>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Admissions --}}
        <section class="ac-dash-panel">
            <header class="ac-dash-panel-head">
                <h3 class="ac-dash-panel-title">Recent admissions</h3>
            </header>
            <div class="ac-dash-table-wrap">
                <table class="ac-dash-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Ward</th>
                            <th class="ac-dash-hide-sm">Admitted</th>
                            <th class="ac-dash-hide-md">Condition</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admissions as $adm)
                        <tr>
                            <td>
                                <div class="ac-dash-table-patient">
                                    <span class="ac-dash-table-avatar {{ $adm['avBg'] }}">{{ $adm['initials'] }}</span>
                                    <span>{{ $adm['name'] }}</span>
                                </div>
                            </td>
                            <td class="ac-dash-table-muted">{{ $adm['ward'] }}</td>
                            <td class="ac-dash-table-muted ac-dash-hide-sm">{{ $adm['admitted'] }}</td>
                            <td class="ac-dash-hide-md">{{ $adm['condition'] }}</td>
                            <td>
                                <span class="ac-dash-status {{ $adm['stClass'] }}">
                                    <span class="ac-dash-status-dot"></span>
                                    {{ $adm['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

    </div>

    @script
    <script>
        const revenue = @json($revenue);
        const caseTypes = @json($caseTypes);

        const loadCharts = () => {
            const dark = document.documentElement.classList.contains('dark');
            const gridColor = dark ? 'rgba(255,255,255,0.06)' : 'rgba(226,232,240,0.9)';
            const textColor = dark ? 'rgba(148,163,184,0.9)' : 'rgba(100,116,139,0.95)';
            const tickFont = { size: 11, weight: '500' };

            const revEl = document.getElementById('revChart');
            if (revEl && !revEl.dataset.ready) {
                revEl.dataset.ready = '1';
                new Chart(revEl, {
                    type: 'bar',
                    data: {
                        labels: revenue.labels,
                        datasets: [{
                            label: 'Revenue ($M)',
                            data: revenue.values,
                            backgroundColor: revenue.values.map((_, i) =>
                                i === revenue.values.length - 1 ? '#6338f5' : 'rgba(99,56,245,0.18)'
                            ),
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: ctx => ' $' + ctx.parsed.y.toFixed(1) + 'M' } }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: textColor, font: tickFont } },
                            y: {
                                grid: { color: gridColor },
                                ticks: { color: textColor, font: tickFont, callback: v => '$' + v + 'M', maxTicksLimit: 4 },
                                min: 0,
                                max: 3,
                            }
                        }
                    }
                });
            }

            const caseEl = document.getElementById('caseChart');
            if (caseEl && !caseEl.dataset.ready) {
                caseEl.dataset.ready = '1';
                new Chart(caseEl, {
                    type: 'doughnut',
                    data: {
                        labels: caseTypes.map(c => c.label),
                        datasets: [{
                            data: caseTypes.map(c => c.pct),
                            backgroundColor: caseTypes.map(c => c.color),
                            borderWidth: 0,
                            hoverOffset: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + '%' } }
                        }
                    }
                });
            }
        };

        if (typeof Chart !== 'undefined') {
            loadCharts();
        } else {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
            s.onload = loadCharts;
            document.head.appendChild(s);
        }
    </script>
    @endscript

</x-filament-panels::page>
