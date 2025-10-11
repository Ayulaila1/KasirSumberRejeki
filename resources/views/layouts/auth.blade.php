<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Auth Page</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @livewireStyles
</head>

<body>
    {{ $slot }}
    @livewireScripts
    @stack('scripts')
    <!-- ✅ Tambahkan di sini -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            let chartPendapatan = null;
        
            function renderChartPendapatan(data) {
                const ctx = document.getElementById('revenueChart');
                if (!ctx) return;
                if (chartPendapatan) chartPendapatan.destroy();
        
                chartPendapatan = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: data,
                            borderColor: '#7A4B47',
                            backgroundColor: 'rgba(122, 75, 71, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            }
        
            // ✅ Saat Livewire sudah dimuat, render pertama kali
            Livewire.on('renderChartPendapatan', (data) => {
                renderChartPendapatan(data);
            });
        });
    </script>
</body>

</html>