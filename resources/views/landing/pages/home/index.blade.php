@extends('landing.layouts.app')
@section('content')

<div class="wrapper">
    @include('landing.pages.home.partials.box-form-choice')
    @include('landing.pages.home.partials.team-info')
    @include('landing.pages.home.partials.launch-date')
    @include('landing.pages.home.partials.box-counter')
    @include('landing.pages.home.partials.box-journal')
</div>





@endsection


@push('scripts')
<script>
    window.LAUNCH_DATES = {
        startDate: @json(optional($launchStart)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 1, 2025 00:00:00',
        finishDate: @json(optional($launchFinish)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 10, 2025 00:00:00'
    };
</script>
@endpush
