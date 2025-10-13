@extends('dashboard.layouts.app')
@section('title', 'Hasil Form BPKH')
@section('content')

 <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="page-content">
                <div class="container-fluid">

                   @include('dashboard.pages.form.components.sub-head')

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success mb-3">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <form method="get" action="{{ route('dashboard.form.bpkh.index') }}" class="row g-2 align-items-center mb-3">
                                        <div class="col-sm-8 col-md-6 col-lg-4">
                                            <input type="text" name="q" value="{{ $term ?? request('q') }}" class="form-control" placeholder="Cari Respondent, Nama, Petugas, Phone, Website" />
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>

                                    <h4 class="card-title">Tujuan</h4>
                                    <p class="card-title-desc">Form Penilaian (FP) ini adalah salah satu dari sejumlah alat analisis dalam metodologi SIGAP Award 2025 untuk implementasi IIG (Infrastruktur Informasi Geospasial) Kehutanan menggunakan Kerangka Kerja Informasi Geospasial Terintegrasi - Integrated Geospatial Information Framework (IGIF). Tujuan utama FP adalah untuk mengumpulkan informasi yang diperlukan guna menyelesaikan penilaian dasar (kondisi saat ini) dari pengembangan IIG Kehutanan.
                                    </p>

                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Respondent ID</th>
                                            <th>Nama BPKH</th>
                                            <th>Petugas BPKH</th>
                                            <th>Nomor Telepon / Nomor WhatsApp Aktif</th>
                                            <th>Situs Website</th>
                                            <th>Status Nilai</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($forms ?? []) as $form)
                                                <tr>
                                                    <td>{{ $forms->firstItem() + $loop->index }}</td>
                                                    <td>{{ $form->respondent_id }}</td>
                                                    <td>{{ $form->nama_bpkh }}</td>
                                                    <td>{{ $form->petugas_bpkh }}</td>
                                                    <td>{{ $form->phone }}</td>
                                                    <td>
                                                        @if(!empty($form->website))
                                                            <a href="{{ str_starts_with($form->website, 'http') ? $form->website : 'https://'.$form->website }}" target="_blank">{{ $form->website }}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $badgeClass = match($form->status_nilai) {
                                                                'pending' => 'bg-secondary',
                                                                'in_review' => 'bg-warning',
                                                                'scored' => 'bg-success',
                                                                default => 'bg-secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ $form->status_nilai }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.form.bpkh.show', $form->respondent_id) }}">Lihat Detail</a>
                                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.form.bpkh.score.edit', $form->respondent_id) }}">Nilai Form</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if(isset($forms))
                                        {{ $forms->links() }}
                                    @endif

                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->



                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->



        <!-- end main content-->


@endsection
