@extends('dashboard.layouts.app')
@section('title', 'Dashboard')

@push('styles')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
    .profile-user-wid img {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .profile-user-wid img:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Sigap Award Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Beranda</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                  @php
                        $user = Auth::user();
                        $profileImage = $user?->profile_image
                        ? asset('storage/'.$user->profile_image)
                        : asset('dashboard-assets/images/users/user-dummy-img.jpg');
                    @endphp
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>{{ $user?->name }}</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('dashboard-assets/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="avatar-md profile-user-wid mb-4 mx-auto" style="width: 100px; height: 100px;">

                                    <a href="{{ $profileImage }}" class="glightbox" data-glightbox="title: {{ $user?->name }}; description: Dashboard Profile">
                                        <img src="{{ $profileImage }}" alt="{{ $user?->name }}" class="img-thumbnail rounded-circle"
                                             style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    </a>
                                </div>
                                <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                                    <p class="text-muted mb-0 badge badge-soft-primary p-2 font-size-12"><i class="mdi mdi-account"></i> {{ strtoupper($roleDisplay ?? '') }}</p>
                                    <a href="{{ route('profile.index') }}" class="btn btn-primary waves-effect waves-light btn-sm">Update Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.form.bpkh.index') }}" class="text-reset">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Form BPKH Total Submit</p>
                                            <h4 class="mb-0">{{ $countBpkh ?? 0 }}</h4>
                                            <small class="text-muted">Last sync: {{ $lastSyncBpkhText ?? '-' }}</small>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="bx bx-archive-in font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.form.produsen-dg.index') }}" class="text-reset">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Form Produsen DG Total Submit</p>
                                            <h4 class="mb-0">{{ $countProdusen ?? 0 }}</h4>
                                            <small class="text-muted">Last sync: {{ $lastSyncProdusenText ?? '-' }}</small>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-success">
                                                    <i class="bx bx-archive-in font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.presentation.bpkh.index') }}" class="text-reset">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Total Penilaian Presentasi BPKH yang Sudah Dinilai</p>
                                            <h4 class="mb-0">{{ $countPresentasiBpkh ?? 0 }}</h4>
                                            <small class="text-muted">Presentasi BPKH yang sudah mendapat penilaian</small>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-info">
                                                    <i class="bx bx-clipboard font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.presentation.produsen.index') }}" class="text-reset">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Total Penilaian Presentasi Produsen yang Sudah Dinilai</p>
                                            <h4 class="mb-0">{{ $countPresentasiProdusen ?? 0 }}</h4>
                                            <small class="text-muted">Presentasi Produsen yang sudah mendapat penilaian</small>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-warning">
                                                    <i class="bx bx-clipboard font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- Chart Row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Statistik Form BPKH</h4>
                            <select id="filterBpkh" class="form-select form-select-sm" style="width: auto;">
                                <option value="5" selected>Top 5</option>
                                <option value="10">Top 10</option>
                                <option value="all">Semua</option>
                            </select>
                        </div>
                        <p class="card-title-desc">Berdasarkan Nilai Bobot {{ $bobotBpkh ?? 45 }}%</p>
                        <div style="position: relative; height: 400px;">
                            <canvas id="chartBpkh"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Statistik Form Produsen DG</h4>
                            <select id="filterProdusen" class="form-select form-select-sm" style="width: auto;">
                                <option value="5" selected>Top 5</option>
                                <option value="10">Top 10</option>
                                <option value="all">Semua</option>
                            </select>
                        </div>
                        <p class="card-title-desc">Berdasarkan Nilai Bobot {{ $bobotProdusen ?? 45 }}%</p>
                        <div style="position: relative; height: 400px;">
                            <canvas id="chartProdusen"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end chart row -->

        <!-- Presentation Statistics Row -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3"><i class="bx bx-bar-chart-alt-2 me-2"></i>Statistik Hasil Penilaian Presentasi</h4>
            </div>
        </div>

        <div class="row">
            <!-- BPKH Presentation Stats -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="bx bx-trophy font-size-24 text-white"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Presentasi BPKH</h5>
                                    <p class="text-muted mb-0 small">Berdasarkan Nilai Bobot Akhir (35%)</p>
                                </div>
                            </div>
                            <select id="filterPresentasiBpkh" class="form-select form-select-sm" style="width: auto;">
                                <option value="3" selected>Top 3</option>
                                <option value="5">Top 5</option>
                                <option value="10">Top 10</option>
                                <option value="all">Semua</option>
                            </select>
                        </div>

                        <div class="presentation-stats-list" id="listPresentasiBpkh">
                            @php
                                $maxScore = $statsPresentasiBpkh->max('nilai_final_dengan_bobot') ?? 35;
                            @endphp
                            @forelse($statsPresentasiBpkh as $index => $item)
                            <div class="stats-item mb-3 p-3 rounded" data-index="{{ $index }}" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-left: 4px solid {{ $index === 0 ? '#FFD700' : ($index === 1 ? '#C0C0C0' : ($index === 2 ? '#CD7F32' : '#6c757d')) }}; {{ $index >= 3 ? 'display: none;' : '' }}">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="rank-badge me-3">
                                            @if($index < 3)
                                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, {{ $index === 0 ? '#FFD700, #FFA500' : ($index === 1 ? '#C0C0C0, #808080' : '#CD7F32, #8B4513') }}); box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
                                                    <span class="text-white fw-bold">{{ $index + 1 }}</span>
                                                </div>
                                            @else
                                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-secondary">
                                                    <span class="text-white fw-bold">{{ $index + 1 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-dark">{{ $item->nama_bpkh }}</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i class="bx bx-user font-size-12"></i> {{ $item->total_juri_menilai ?? 0 }} Juri Penilai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <h4 class="mb-0 fw-bold" style="color: #28a745;">{{ number_format($item->nilai_final_dengan_bobot, 2) }}</h4>
                                        <small class="text-muted">/ 35.00</small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ ($item->nilai_final_dengan_bobot / $maxScore) * 100 }}%; background: linear-gradient(90deg, #28a745 0%, #20c997 100%);"
                                         aria-valuenow="{{ $item->nilai_final_dengan_bobot }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $maxScore }}">
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bx bx-info-circle font-size-24 text-white"></i>
                                </div>
                                <h6 class="text-muted">Belum ada data penilaian presentasi</h6>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produsen Presentation Stats -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center me-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                    <i class="bx bx-trophy font-size-24 text-white"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Presentasi Produsen</h5>
                                    <p class="text-muted mb-0 small">Berdasarkan Nilai Bobot Akhir (35%)</p>
                                </div>
                            </div>
                            <select id="filterPresentasiProdusen" class="form-select form-select-sm" style="width: auto;">
                                <option value="3" selected>Top 3</option>
                                <option value="5">Top 5</option>
                                <option value="10">Top 10</option>
                                <option value="all">Semua</option>
                            </select>
                        </div>

                        <div class="presentation-stats-list" id="listPresentasiProdusen">
                            @php
                                $maxScoreProdusen = $statsPresentasiProdusen->max('nilai_final_dengan_bobot') ?? 35;
                            @endphp
                            @forelse($statsPresentasiProdusen as $index => $item)
                            <div class="stats-item mb-3 p-3 rounded" data-index="{{ $index }}" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%); border-left: 4px solid {{ $index === 0 ? '#FFD700' : ($index === 1 ? '#C0C0C0' : ($index === 2 ? '#CD7F32' : '#6c757d')) }}; {{ $index >= 3 ? 'display: none;' : '' }}">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="rank-badge me-3">
                                            @if($index < 3)
                                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, {{ $index === 0 ? '#FFD700, #FFA500' : ($index === 1 ? '#C0C0C0, #808080' : '#CD7F32, #8B4513') }}); box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
                                                    <span class="text-white fw-bold">{{ $index + 1 }}</span>
                                                </div>
                                            @else
                                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-secondary">
                                                    <span class="text-white fw-bold">{{ $index + 1 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-dark">{{ $item->nama_instansi }}</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bx bx-user font-size-12"></i> {{ $item->total_juri_menilai ?? 0 }} Juri Penilai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <h4 class="mb-0 fw-bold" style="color: #11998e;">{{ number_format($item->nilai_final_dengan_bobot, 2) }}</h4>
                                        <small class="text-muted">/ 35.00</small>
                                    </div>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ ($item->nilai_final_dengan_bobot / $maxScoreProdusen) * 100 }}%; background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);"
                                         aria-valuenow="{{ $item->nilai_final_dengan_bobot }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $maxScoreProdusen }}">
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bx bx-info-circle font-size-24 text-white"></i>
                                </div>
                                <h6 class="text-muted">Belum ada data penilaian presentasi</h6>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end presentation stats row -->

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

@include('dashboard.layouts.components.info_modal')
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from backend
const dataBpkh = @json($chartBpkh ?? []);
const dataProdusen = @json($chartProdusen ?? []);
const bobotBpkh = {{ $bobotBpkh ?? 45 }};
const bobotProdusen = {{ $bobotProdusen ?? 45 }};

// Chart instances
let chartBpkhInstance = null;
let chartProdusenInstance = null;

// Initialize BPKH Chart
function initBpkhChart(limit = 5) {
    const ctx = document.getElementById('chartBpkh');
    if (!ctx) return;

    // Filter data based on limit
    let filteredData = limit === 'all' ? dataBpkh : dataBpkh.slice(0, parseInt(limit));

    // Destroy previous chart if exists
    if (chartBpkhInstance) {
        chartBpkhInstance.destroy();
    }

    // Create gradient for modern look
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 400, 0);
    gradient.addColorStop(0, 'rgba(85, 110, 230, 0.9)');
    gradient.addColorStop(1, 'rgba(85, 110, 230, 0.5)');

    chartBpkhInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: filteredData.map(item => item.label),
            datasets: [{
                label: 'Nilai Bobot ' + bobotBpkh + '%',
                data: filteredData.map(item => item.value),
                backgroundColor: gradient,
                borderColor: 'rgba(85, 110, 230, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal bar
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(85, 110, 230, 1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return ' Nilai: ' + context.parsed.x.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    title: {
                        display: true,
                        text: 'Nilai Bobot Total',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
}

// Initialize Produsen Chart
function initProdusenChart(limit = 5) {
    const ctx = document.getElementById('chartProdusen');
    if (!ctx) return;

    // Filter data based on limit
    let filteredData = limit === 'all' ? dataProdusen : dataProdusen.slice(0, parseInt(limit));

    // Destroy previous chart if exists
    if (chartProdusenInstance) {
        chartProdusenInstance.destroy();
    }

    // Create gradient for modern look
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 400, 0);
    gradient.addColorStop(0, 'rgba(52, 195, 143, 0.9)');
    gradient.addColorStop(1, 'rgba(52, 195, 143, 0.5)');

    chartProdusenInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: filteredData.map(item => item.label),
            datasets: [{
                label: 'Nilai Bobot ' + bobotProdusen + '%',
                data: filteredData.map(item => item.value),
                backgroundColor: gradient,
                borderColor: 'rgba(52, 195, 143, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal bar
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(52, 195, 143, 1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return ' Nilai: ' + context.parsed.x.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    title: {
                        display: true,
                        text: 'Nilai Bobot Total',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
}

// Initialize everything on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    initBpkhChart(5); // Default Top 5
    initProdusenChart(5); // Default Top 5

    // Initialize GLightbox
    if (typeof GLightbox !== 'undefined') {
        GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    }

    // Filter event listeners
    const filterBpkh = document.getElementById('filterBpkh');
    if (filterBpkh) {
        filterBpkh.addEventListener('change', function() {
            initBpkhChart(this.value);
        });
    }

    const filterProdusen = document.getElementById('filterProdusen');
    if (filterProdusen) {
        filterProdusen.addEventListener('change', function() {
            initProdusenChart(this.value);
        });
    }

    // Filter Presentation Stats - BPKH
    const filterPresentasiBpkh = document.getElementById('filterPresentasiBpkh');
    if (filterPresentasiBpkh) {
        filterPresentasiBpkh.addEventListener('change', function() {
            const limit = this.value;
            const items = document.querySelectorAll('#listPresentasiBpkh .stats-item');

            items.forEach((item, index) => {
                if (limit === 'all') {
                    item.style.display = '';
                } else {
                    item.style.display = index < parseInt(limit) ? '' : 'none';
                }
            });
        });
    }

    // Filter Presentation Stats - Produsen
    const filterPresentasiProdusen = document.getElementById('filterPresentasiProdusen');
    if (filterPresentasiProdusen) {
        filterPresentasiProdusen.addEventListener('change', function() {
            const limit = this.value;
            const items = document.querySelectorAll('#listPresentasiProdusen .stats-item');

            items.forEach((item, index) => {
                if (limit === 'all') {
                    item.style.display = '';
                } else {
                    item.style.display = index < parseInt(limit) ? '' : 'none';
                }
            });
        });
    }
});
</script>

<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
@endpush

