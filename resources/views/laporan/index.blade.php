@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>
        Laporan Sistem Peminjaman
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-1"></i>
                Export Excel
            </button>
            <button type="button" class="btn btn-danger" onclick="exportToPDF()">
                <i class="fas fa-file-pdf me-1"></i>
                Export PDF
            </button>
        </div>
    </div>
</div>

{{-- Filter Laporan --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form method="GET" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="startDate" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="endDate" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="endDate" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </div>
                    <div class="btn-group me-2">
            <a href="{{ route('export.barang') }}" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Barang
            </a>
            <a href="{{ route('export.peminjaman', request()->query()) }}" class="btn btn-info">
                <i class="fas fa-file-excel me-1"></i> Export Peminjaman
            </a>
            <a href="{{ route('export.transaksi', request()->query()) }}" class="btn btn-warning">
                <i class="fas fa-file-excel me-1"></i> Export Transaksi
            </a>
</div>
        </form>
    </div>
</div>
@endsection