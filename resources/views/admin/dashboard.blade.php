@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <h3 class="fw-bold mb-4">Thống kê</h3>

    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="date" id="from" value="{{ $from->toDateString() }}" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" id="to" value="{{ $to->toDateString() }}" class="form-control">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="fw-semibold text-muted">Tổng đơn hàng hoàn thành</div>
                <h4 class="fw-bold total-orders">0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="fw-semibold text-muted">Doanh thu</div>
                <h4 class="fw-bold text-danger total-revenue">0 ₫</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="fw-semibold text-muted">Lợi nhuận</div>
                <h4 class="fw-bold text-success total-profit">0 ₫</h4>
            </div>
        </div>

    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <h6 class="fw-bold mb-3">Thống kê doanh thu & lợi nhuận</h6>
                <div class="chart-line">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6 class="fw-bold mb-3">Trạng thái đơn hàng hiện tại</h6>
                <div class="chart-doughnut">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.chart-line {
    height: 320px;
    position: relative;
}

.chart-doughnut {
    height: 320px;
    position: relative;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const statusMap = {
    pending: 'Chờ xử lý',
    processing: 'Đang xử lý',
    completed: 'Hoàn thành',
    cancelled: 'Đã huỷ'
};
let revenueChart;
let statusChart;

function loadDashboard(from, to) {
    fetch(`/admin/dashboard/data?from=${from}&to=${to}`)
        .then(res => res.json())
        .then(data => {

            document.querySelector('.total-orders').innerText = data.totalOrders;
            document.querySelector('.total-revenue').innerText =
                Number(data.totalRevenue).toLocaleString() + ' ₫';
            document.querySelector('.total-profit').innerText =
                Number(data.totalProfit).toLocaleString() + ' ₫';

            const revenueCtx = document.getElementById('revenueChart');
            if (revenueChart) revenueChart.destroy();

            revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Doanh thu',
                            data: data.revenue,
                            backgroundColor: '#dc3545',
                            borderRadius: 6,
                            barThickness: 22
                        },
                        {
                            label: 'Lợi nhuận',
                            data: data.profit,
                            backgroundColor: '#198754',
                            borderRadius: 6,
                            barThickness: 22
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: {
                            stacked: false
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' ₫';
                                }
                            }
                        }
                    }
                }
            });


            const statusCtx = document.getElementById('statusChart');
            if (statusChart) statusChart.destroy();

          const statusKeys = Object.keys(data.status);

            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusKeys.map(key => statusMap[key] ?? key),
                    datasets: [{
                        data: statusKeys.map(key => data.status[key]),
                        backgroundColor: [
                            '#ffc107',
                            '#0d6efd',
                            '#198754',
                            '#dc3545'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });

        });
}

document.getElementById('filterForm').addEventListener('submit', function(e){
    e.preventDefault();
    loadDashboard(
        document.getElementById('from').value,
        document.getElementById('to').value
    );
});

loadDashboard(
    document.getElementById('from').value,
    document.getElementById('to').value
);
</script>
@endsection
