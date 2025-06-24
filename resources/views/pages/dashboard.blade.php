@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="icon" href="{{ asset('img/logo_pemda.png') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        .card:hover {
            transform: translateY(-10px);
            transition: 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .card-header h6 {
            min-height: 100px; /* atau 60px kalau mau lebih tinggi */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .chart-card {
            border-radius: 0.75rem;
            overflow: hidden;
        }


        .rules-card {
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 5px 0;
        }

        .rules-card h2.section-title {
            color: #800000;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .rules-card ul {
            list-style-type: none;
            padding-left: 0;
            font-size: 16px;
            color: #555;
        }

        .rules-card ul li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }

        .rules-card ul li::before {
            content: "✓";
            color: #800000;
            font-weight: bold;
            position: absolute;
            left: 0;
            top: 0;
            font-size: 18px;
        }

        .custom-chart-text p {
            margin-bottom: 5px !important;
            line-height: 1.2 !important;
            font-size: 12px;
        }

        #loadingIndicator {
            display: none;
        }

        #loadingIndicator i {
            font-size: 5rem;
        }

        #loadingIndicator p {
            font-size: 1.2rem;
            font-weight: 800;
            color: #101010;
        }

        #chartContainer {
            display: none !important;
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }

        .donut-center-wrapper {
            justify-content: center !important;
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

    </style>

@endpush


@section('main')
<div class="main-content">
    <section class="section">
        <div class="rules-card">
            <div class="card-body">
                {{-- Loading Spinner --}}
                <div id="loadingIndicator" class="text-center my-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                    <p class="text-muted">Load Data, Tunggu sadiki...</p>
                </div>

                {{-- Chart container akan ditambahkan lewat JS --}}
                <div id="chartWrapper" style="display: none;"></div>

            </div>
        </div>
    </section>
</div>
@endsection



@push('scripts')
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = {!! json_encode($chartData) !!};
            const subkegiatanNames = {!! json_encode($subkegiatanNames) !!};
            const belanjaChart = {!! json_encode($belanjaChart) !!};

            const loadingIndicator = document.getElementById('loadingIndicator');
            const chartWrapper = document.getElementById('chartWrapper');

            loadingIndicator.style.display = 'block';

            setTimeout(() => {
                let chartContainer = document.createElement('div');
                chartContainer.className = 'row';
                chartContainer.style.display = 'flex';
                chartContainer.style.flexWrap = 'wrap';

                function formatNumber(number) {
                    const numericValue = typeof number === 'string'
                        ? parseFloat(number.replace(/[^\d.-]/g, ''))
                        : Number(number);
                    return isNaN(numericValue)
                        ? '0'
                        : new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(numericValue);
                }

                let totalAllAnggaran = 0;
                let totalAllPemakaian = 0;
                let totalAllSisa = 0;

                Object.keys(chartData).forEach(function (subkegiatan_id) {
                    const subName = subkegiatanNames[subkegiatan_id] || 'Sub Kegiatan Tidak Diketahui';
                    const totalDana = parseInt(chartData[subkegiatan_id].total_dana, 10) || 0;
                    const totalPemakaian = parseInt(chartData[subkegiatan_id].total_pemakaian, 10) || 0;
                    const totalAnggaran = totalDana + totalPemakaian;

                    totalAllAnggaran += totalAnggaran;
                    totalAllPemakaian += totalPemakaian;
                    totalAllSisa += totalDana;

                    const itemWrapper = document.createElement('div');
                    itemWrapper.classList.add('col-md-4', 'col-lg-3', 'mb-3');

                    itemWrapper.innerHTML = `
                        <div class="card ">
                            <div class="card-header bg-light">
                                <h6 class="text-dark text-center">${subName}</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chart_${subkegiatan_id}" height="200"></canvas>
                                <div class="text-center custom-chart-text">
                                    <p>Total Anggaran: <strong>Rp ${formatNumber(totalAnggaran)}</strong></p>
                                    <p>Terpakai: <strong>Rp ${formatNumber(totalPemakaian)}</strong></p>
                                    <p>Dana Tersisa: <strong>Rp ${formatNumber(totalDana)}</strong></p>
                                </div>
                            </div>
                        </div>
                    `;

                    chartContainer.prepend(itemWrapper);

                    const ctx = itemWrapper.querySelector(`#chart_${subkegiatan_id}`).getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Sisa Anggaran', 'Terpakai'],
                            datasets: [{
                                data: [totalDana, totalPemakaian],
                                backgroundColor: ['#2e7d32', '#ff6384'],
                                hoverBackgroundColor: ['#388e3c', '#ff7a99']
                            }]
                        },
                        options: {
                            responsive: true,
                            tooltips: {
                                callbacks: {
                                    label: function (tooltipItem, data) {
                                        const label = data.labels[tooltipItem.index] || '';
                                        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || 0;
                                        return `${label}: Rp ${formatNumber(value)}`;
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10
                                }
                            }
                        }
                    });
                });

                const persenPemakaian = totalAllAnggaran > 0 ? ((totalAllPemakaian / totalAllAnggaran) * 100).toFixed(2) : 0;
                const persenSisa = totalAllAnggaran > 0 ? ((totalAllSisa / totalAllAnggaran) * 100).toFixed(2) : 0;

                const summaryContainer = document.createElement('div');
                summaryContainer.classList.add('col-12', 'mb-4');
                summaryContainer.innerHTML = `
                    <div class="card shadow rounded-lg border-0" style="background-color: #ffffff;">
                        <div class="card-body py-4 px-3">
                            <h5 class="text-center text-dark mb-4">REALISASI ANGGARAN - POS WAKIL BUPATI</h5>
                            <div class="row text-center d-flex">
                                <div class="col-md-4 mb-3 mb-md-0 d-flex">
                                    <div class="p-3 rounded flex-fill" style="background-color: #f8f9fa;">
                                        <div class="text-muted fs-4">Total Anggaran</div>
                                        <h4 class="text-dark mt-1 mb-0">Rp ${formatNumber(totalAllAnggaran)}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0 d-flex">
                                    <div class="p-3 rounded flex-fill" style="background-color: #fff3f3;">
                                        <div class="text-muted fs-4">Realisasi</div>
                                        <h4 class="text-danger mt-1 mb-1">Rp ${formatNumber(totalAllPemakaian)}</h4>
                                        <div class="text-muted fs-5">${persenPemakaian}% dari Total</div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex">
                                    <div class="p-3 rounded flex-fill" style="background-color: #f3fff5;">
                                        <div class="text-muted fs-4">Sisa Anggaran</div>
                                        <h4 class="text-success mt-1 mb-1">Rp ${formatNumber(totalAllSisa)}</h4>
                                        <div class="text-muted fs-5">${persenSisa}% dari total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                chartContainer.prepend(summaryContainer);
                // chartContainer.appendChild(summaryContainer);

               // ===== Donut Chart Belanja Modal & Jasa dalam Card =====
                const donutSection = document.createElement('div');
                donutSection.className = 'col-md-4 col-lg-3 mb-3';

                donutSection.innerHTML = `
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="text-dark text-center">Realisasi Belanja Modal & Jasa/Habis Pakai</h6>
                        </div>
                        <div class="card-body text-center">
                            <canvas id="donutChartJenisBelanja" height="200" ></canvas>
                            <div id="donutChartLegend" class="d-flex flex-column align-items-center"></div>
                            <div id="totalRealisasiBelanja" class="mt-2 text-dark font-weight-bold"></div>
                        </div>
                    </div>
                `;

                chartContainer.appendChild(donutSection);

                // Persiapkan data donut chart
                const belanjaChart = {!! json_encode($belanjaChart) !!};
                const totalBelanjaJenis = belanjaChart.data.reduce((sum, val) => sum + parseFloat(val), 0);

                const ctxDonut = donutSection.querySelector('#donutChartJenisBelanja').getContext('2d');
                const warnaDonut = ['#007bff', '#ff6384']; // Warna untuk Modal & Jasa

                // Render Donut Chart
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: belanjaChart.labels,
                        datasets: [{
                            data: belanjaChart.data,
                            backgroundColor: warnaDonut,
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: { display: false },
                        cutoutPercentage: 70,
                        tooltips: {
                            callbacks: {
                                label: function (tooltipItem, data) {
                                    const label = data.labels[tooltipItem.index] || '';
                                    const value = data.datasets[0].data[tooltipItem.index] || 0;
                                    const percent = totalBelanjaJenis > 0 ? ((value / totalBelanjaJenis) * 100).toFixed(2) : '0.00';
                                    return `${label}: Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percent}%)`;
                                }
                            }
                        }
                    },
                    plugins: [] // <--- Tidak perlu plugin untuk beforeDraw lagi
                });

                // Manual legend di bawah chart
                const legendContainer = donutSection.querySelector('#donutChartLegend');
                belanjaChart.labels.forEach((label, index) => {
                    const value = belanjaChart.data[index];
                    const percent = totalBelanjaJenis > 0 ? ((value / totalBelanjaJenis) * 100).toFixed(2) : '0.00';
                    const color = warnaDonut[index % warnaDonut.length];

                    const legendItem = document.createElement('div');
                    legendItem.className = 'mb-1 d-flex align-items-center';

                    legendItem.innerHTML = `
                        <div style="width: 14px; height: 14px; background-color: ${color}; border-radius: 3px; margin-right: 8px;"></div>
                        <div style="font-size: 13px;">
                            <strong>${label}</strong>: Rp ${new Intl.NumberFormat('id-ID').format(value)}
                            <span class="text-muted">(${percent}%)</span>
                        </div>
                    `;

                    legendContainer.prepend(legendItem);
                });

                // Tambahkan total realisasi di bawah legend
                const totalRealisasiBelanja = donutSection.querySelector('#totalRealisasiBelanja');
                totalRealisasiBelanja.innerHTML = `
                    Total Realisasi: <strong>Rp ${new Intl.NumberFormat('id-ID').format(totalBelanjaJenis)}</strong>
                `;

                // Tampilkan konten
                loadingIndicator.style.display = 'none';
                chartWrapper.style.display = 'block';
                chartWrapper.appendChild(chartContainer);
            }, 500);
        });
    </script>

@endpush
