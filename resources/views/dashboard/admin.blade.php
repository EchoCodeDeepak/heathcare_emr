@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
                    <p class="text-muted mb-0">Welcome, {{ Auth::user()->name }}!</p>
                </div>
                <div>
                    <!-- <span class="badge bg-success">System Admin</span> -->
                </div>
            </div>
            <hr>
        </div>

        <!-- Statistics Cards -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stat-card stat-card-primary shadow-sm">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stat-card-title mb-0">Total Users</h6>
                            <h2 class="mb-0">{{ App\Models\User::count() }}</h2>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="stat-card-footer Total  px-3 py-2">
                    View Users <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stat-card stat-card-success shadow-sm">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stat-card-title mb-0">Total Records</h6>
                            <h2 class="mb-0">{{ App\Models\PatientMedicalRecord::count() }}</h2>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('medical-records.index') }}" class="stat-card-footer px-3 py-2 w-100">
                    View Medical Records <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stat-card stat-card-warning shadow-sm">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stat-card-title mb-0">Total Roles</h6>
                            <h2 class="mb-0">{{ App\Models\Role::count() }}</h2>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.roles.index') }}" class="stat-card-footer px-3 py-2">
                    Manage Roles <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stat-card stat-card-info shadow-sm">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="stat-card-title mb-0">Permissions</h6>
                            <h2 class="mb-0">{{ App\Models\Permission::count() }}</h2>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('permissions.index') }}" class="stat-card-footer px-3 py-2">
                    Manage <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <!-- User Roles Distribution (Using ApexCharts) -->
        <div class="col-lg-5 col-md-12 mb-4">
            <div class="chart-card shadow-sm">
                <div class="chart-card-header">
                    <h5 class="chart-card-title mb-0">
                        <i class="fas fa-chart-pie"></i> User Roles Overview
                    </h5>
                </div>
                <div class="chart-card-body">
                    <div id="rolesDonutChart" class="apex-chart-container"></div>
                    <div class="chart-legend" id="rolesLegend"></div>
                </div>
            </div>
        </div>

        <!-- Users by Role (Horizontal Bar Chart - keep Chart.js for this one) -->
        <div class="col-lg-7 col-md-12 mb-4">
            <div class="chart-card shadow-sm">
                <div class="chart-card-header">
                    <h5 class="chart-card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Users by Role
                    </h5>
                </div>
                <div class="chart-card-body">
                    <canvas id="userRoleBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>

<!-- Chart.js (only for bar chart) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var roleColorMap = @json($roleColors);
        var roleLabels = @json($roleLabels);
        var roleData = @json($roleData);
        var totalUsers = {{ App\Models\User::count() }};

        // =====================
        // ApexCharts for Donut
        // =====================
        var donutOptions = {
            series: roleData,
            chart: {
                type: 'donut',
                height: 320,
                animations: {
                    enabled: false, // NO ANIMATIONS
                    speed: 0,
                    animateGradually: {
                        enabled: false
                    },
                    dynamicAnimation: {
                        enabled: false
                    }
                },
                events: {
                    mounted: function(chartContext, config) {
                        console.log('Chart mounted - no animations');
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: roleColorMap,
            labels: roleLabels,
            stroke: {
                width: 0
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#334155',
                                offsetY: -5
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#1e293b',
                                offsetY: 5,
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Users',
                                color: '#64748b',
                                fontSize: '14px',
                                fontWeight: 400,
                                formatter: function(w) {
                                    return totalUsers;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val, { seriesIndex, w }) {
                        var percentage = ((val / totalUsers) * 100).toFixed(1);
                        return val + ' (' + percentage + '%)';
                    }
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'none' // No hover effects
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none'
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 280
                    }
                }
            }]
        };

        var donutChart = new ApexCharts(document.querySelector("#rolesDonutChart"), donutOptions);
        donutChart.render();

        // Generate custom legend for donut chart
        var rolesLegend = document.getElementById('rolesLegend');
        for (var i = 0; i < roleLabels.length; i++) {
            var value = roleData[i];
            var percentage = ((value / totalUsers) * 100).toFixed(1);
            var color = roleColorMap[i];

            var legendItem = document.createElement('div');
            legendItem.className = 'legend-item';
            legendItem.innerHTML =
                '<span class="legend-color" style="background-color: ' + color + '"></span>' +
                '<span class="legend-label">' + roleLabels[i] + '</span>' +
                '<span class="legend-value">' + value + ' (' + percentage + '%)</span>';
            rolesLegend.appendChild(legendItem);
        }

        // =====================
        // Chart.js for Bar Chart (keep existing)
        // =====================
        var userRoleCtx = document.getElementById('userRoleBarChart').getContext('2d');
        var userRoleBarChart = new Chart(userRoleCtx, {
            type: 'bar',
            data: {
                labels: roleLabels,
                datasets: [{
                    label: 'Users',
                    data: roleData,
                    backgroundColor: roleColorMap,
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 28
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                hover: {
                    animationDuration: 0
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                var value = context.raw;
                                var percentage = ((value / totalUsers) * 100).toFixed(1);
                                return ' ' + value + ' users (' + percentage + '%)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            padding: 8
                        }
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#334155',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12,
                                weight: '500'
                            },
                            padding: 8
                        }
                    }
                }
            }
        });

        // Add value labels at the end of each bar
        var addValueLabels = function() {
            var chart = userRoleBarChart;
            var ctx = chart.ctx;

            ctx.font = "bold 11px Inter, sans-serif";
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';

            chart.data.datasets.forEach(function(dataset, datasetIndex) {
                var meta = chart.getDatasetMeta(datasetIndex);
                meta.data.forEach(function(bar, index) {
                    var value = roleData[index];
                    var percentage = ((value / totalUsers) * 100).toFixed(1);
                    var labelText = value + ' (' + percentage + '%)';
                    var textX = bar.x + 8;
                    var textY = bar.y;

                    ctx.fillStyle = '#334155';
                    ctx.fillText(labelText, textX, textY);
                });
            });
        };

        userRoleBarChart.options.plugins.afterDraw = addValueLabels;
        userRoleBarChart.update();
    });
</script>

<style>
    .btn-block {
        display: block;
        width: 100%;
    }

    .chart-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        height: 100%;
    }

    .chart-card:hover {
        box-shadow: var(--shadow-md);
    }

    .chart-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--bg-subtle);
    }

    .chart-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .chart-card-title i {
        margin-right: 0.5rem;
        color: var(--primary-500);
    }

    .chart-card-body {
        padding: 1.5rem;
        position: relative;
        min-height: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* ApexCharts container */
    .apex-chart-container {
        width: 100%;
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Prevent any animations in ApexCharts */
    .apexcharts-canvas,
    .apexcharts-inner,
    .apexcharts-svg {
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    /* Chart.js canvas styles */
    .chart-card-body canvas {
        max-width: 100%;
        max-height: 100%;
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    .chart-legend {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.35rem 0;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    .legend-label {
        flex: 1;
        font-size: 0.875rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .legend-value {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    @media (max-width: 991px) {
        .col-lg-5,
        .col-lg-7 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .apex-chart-container {
            height: 280px;
        }
    }

    @media (max-width: 768px) {
        .apex-chart-container {
            height: 250px;
        }
    }

    .stat-card {
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        background: #ffffff;
        border: 1px solid var(--border-color);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card-body {
        padding: 1.25rem;
    }

    .stat-card-title {
        font-size: 0.875rem;
        opacity: 0.85;
        font-weight: 500;
        color: var(--text-primary);
    }

    .stat-card h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-top: 0.5rem;
        color: var(--text-primary);
    }

    .stat-card-icon {
        font-size: 2.5rem;
        opacity: 0.2;
    }

    .stat-card-primary {
        border-left: 4px solid var(--primary-500);
    }

    .stat-card-primary .stat-card-icon {
        color: var(--primary-500);
    }

    .stat-card-primary .stat-card-footer {
        background: var(--bg-subtle);
        border-top: 1px solid var(--border-color);
        color: var(--primary-600);
    }

    .stat-card-primary .stat-card-footer:hover {
        background: var(--primary-50);
    }

    .stat-card-success {
        border-left: 4px solid var(--success-500);
    }

    .stat-card-success .stat-card-icon {
        color: var(--success-500);
    }

    .stat-card-success .stat-card-footer {
        background: var(--bg-subtle);
        border-top: 1px solid var(--border-color);
        color: var(--success-600);
    }

    .stat-card-success .stat-card-footer:hover {
        background: var(--success-50);
    }

    .stat-card-warning {
        border-left: 4px solid var(--warning-500);
    }

    .stat-card-warning .stat-card-icon {
        color: var(--warning-500);
    }

    .stat-card-warning .stat-card-footer {
        background: var(--bg-subtle);
        border-top: 1px solid var(--border-color);
        color: var(--warning-600);
    }

    .stat-card-warning .stat-card-footer:hover {
        background: var(--warning-50);
    }

    .stat-card-info {
        border-left: 4px solid var(--info-500);
    }

    .stat-card-info .stat-card-icon {
        color: var(--info-500);
    }

    .stat-card-info .stat-card-footer {
        background: var(--bg-subtle);
        border-top: 1px solid var(--border-color);
        color: var(--info-600);
    }

    .stat-card-info .stat-card-footer:hover {
        background: var(--info-50);
    }

    /* Nuclear option for ApexCharts */
    .apexcharts-series,
    .apexcharts-datalabel,
    .apexcharts-datalabels,
    .apexcharts-datalabel-group,
    .apexcharts-pie,
    .apexcharts-pie-series,
    .apexcharts-pie-area {
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    /* Prevent hover on ApexCharts */
    .apexcharts-canvas:hover {
        transform: none !important;
    }
</style>
@endsection