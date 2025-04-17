@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Admin</h2>

    <div class="row">
        {{-- Bar Chart: Penjualan Harian --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan Harian</h6>
                </div>
                <div class="card-body">
                    <canvas id="barChartPenjualan" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Pie Chart: Persentase Produk --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Persentase Penjualan Produk</h6>
                </div>
                <div class="card-body text-center">
                    <canvas id="pieChartProduk" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
      const createChart = (id, type, data, options) => {
        new Chart(document.getElementById(id), { type, data, options });
      };
    
      createChart("barChartPenjualan", "bar", {
        labels: {!! json_encode($labels) !!},
        datasets: [{
          label: 'Jumlah Penjualan',
          data: {!! json_encode($data) !!},
          backgroundColor: 'rgba(78, 115, 223, 0.7)',
          borderColor: 'rgba(78, 115, 223, 1)',
          borderWidth: 1,
          maxBarThickness: 40
        }]
      }, {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 10 },
            title: { display: true, text: 'Jumlah Penjualan' }
          },
          x: {
            title: { display: true, text: 'Tanggal Penjualan' }
          }
        },
        plugins: { legend: { display: false } }
      });
    
      createChart("pieChartProduk", "pie", {
        labels: {!! json_encode($produkLabels) !!},
        datasets: [{
          data: {!! json_encode($produkData) !!},
          backgroundColor: {!! json_encode($produkColors) !!}
        }]
      }, {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { boxWidth: 12, padding: 10 }
          },
          tooltip: {
            callbacks: {
              label: ({ label = '', raw = 0, dataset }) => {
                const total = dataset.data.reduce((a, b) => a + b, 0);
                const percentage = ((raw / total) * 100).toFixed(1);
                return `${label}: ${raw} (${percentage}%)`;
              }
            }
          }
        }
      });
    });
    </script>
    
@endsection
