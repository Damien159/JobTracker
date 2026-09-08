<div class="max-w-6xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>

    {{-- Kennzahlen-Kacheln --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="border rounded-lg p-6">
            <p class="text-sm text-gray-500">Bewerbungen</p>
            <p class="text-3xl font-bold mt-1">{{ $total }}</p>
        </div>
        <div class="border rounded-lg p-6">
            <p class="text-sm text-gray-500">Interviews</p>
            <p class="text-3xl font-bold mt-1">{{ $statusCounts->get('interview', 0) }}</p>
        </div>
        <div class="border rounded-lg p-6">
            <p class="text-sm text-gray-500">Zusagen</p>
            <p class="text-3xl font-bold mt-1 text-brand-accent">{{ $statusCounts->get('zusage', 0) }}</p>
        </div>
        <div class="border rounded-lg p-6">
            <p class="text-sm text-gray-500">Interview-Rate</p>
            <p class="text-3xl font-bold mt-1">{{ $interviewRate }}%</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Status-Verteilung --}}
        <div class="border rounded-lg p-6">
            <h2 class="font-bold mb-4">Status-Verteilung</h2>
            <canvas id="statusChart" height="200"></canvas>
        </div>

        {{-- Bewerbungen pro Monat --}}
        <div class="border rounded-lg p-6">
            <h2 class="font-bold mb-4">Bewerbungen pro Monat</h2>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>
    </div>
</div>

@script
<script>
    const statusData = @json($statusCounts);
    const monthlyLabels = @json($monthlyLabels);
    const monthlyValues = @json($monthlyValues);

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#474747', '#FD105E', '#333333', '#888888'],
            }],
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
        },
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Bewerbungen',
                data: monthlyValues,
                backgroundColor: '#FD105E',
            }],
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } },
        },
    });
</script>
@endscript