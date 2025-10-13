<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? 'Dashboard' }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                        @foreach($breadcrumbs as $breadcrumb)
                            @if(isset($breadcrumb['active']) && $breadcrumb['active'])
                                <li class="breadcrumb-item active">{{ $breadcrumb['name'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    @if(isset($breadcrumb['url']) && $breadcrumb['url'])
                                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                                    @else
                                        <a href="javascript: void(0);">{{ $breadcrumb['name'] }}</a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @else
                        <li class="breadcrumb-item active">Dashboard</li>
                    @endif
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
