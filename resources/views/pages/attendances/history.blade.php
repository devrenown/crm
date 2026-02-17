@extends('layouts.app')

@push('page-styles')
    <!-- Page Css -->
    <!-- /Page Css -->
@endpush

@section('page-content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <x-breadcrumb>
            <x-slot name="title">{{ __('Attendance History') }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ __('Attendance History') }}
                </li>
            </ul>

            <x-slot name="right">
                <div class="col-auto ms-auto">
                    <div class="btn-group btn-group-sm">
                        <button id="exportCsv" class="btn btn-outline-primary">CSV</button>
                        <button id="exportPdf" class="btn btn-outline-danger">PDF</button>
                    </div>
                    <a href="{{ route('attendances.index') }}" class="btn btn-dark btn-sm">Back</a>
                </div>
            </x-slot>
        </x-breadcrumb>
        <!-- /Page Header -->


        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="attendanceTable" class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Date') }} </th>
                                <th>{{ __('Punch In') }}</th>
                                <th>{{ __('Punch Out') }}</th>
                                <th>{{ __('Total Hours') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($attendances as $date => $records)

                            @php
                                $totalMinutes = $records->sum(fn ($r) =>
                                    $r->endTime ? $r->startTime->diffInMinutes($r->endTime) : 0
                                );

                                $hours   = intdiv($totalMinutes, 60);
                                $minutes = $totalMinutes % 60;

                                $punchIn  = $records->min('startTime');
                                $punchOut = $records->whereNotNull('endTime')->max('endTime');

                                $recordDate = \Carbon\Carbon::parse($date);
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ format_date($date) }}</td>

                                <td>{{ $punchIn?->format('h:i A') }}</td>

                                <td>
                                    @if (!$punchOut && $recordDate->lt(now()->startOfDay()))
                                        <span class="text-danger">Miss Out</span>
                                    @else
                                        {{ $punchOut?->format('h:i A') }}
                                    @endif
                                </td>

                                <td>{{ sprintf('%02d:%02d', $hours, $minutes) }}</td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No attendance records found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>

                    <div class="mt-3">
                        {{ $paginator->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('page-scripts')

    @vite([
        "resources/js/datatables.js"
    ])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#attendanceTable').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                dom: 'rt',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        title: 'Attendance History'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Attendance History',
                        orientation: 'portrait',
                        pageSize: 'A4'
                    }
                ]
            });

            $('#exportCsv').on('click', function () {
                table.button('.buttons-csv').trigger();
            });

            $('#exportPdf').on('click', function () {
                table.button('.buttons-pdf').trigger();
            });
        });
        </script>
    @endpush


@endsection