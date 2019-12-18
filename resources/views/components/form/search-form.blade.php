<div class="search-container">
    {!! Form::open(['url' => $searchRoute, 'id' => 'searchForm']) !!}
        <span class="field search-input-container">
            {!! Form::text('needle', $needle, ['class' => 'gui-input search-input', 'id' => 'needle']) !!}
        </span>
        <button id="button-search" class="btn btn-info search-button">
            <i class="fa fa-search mr10"></i>{{ $params['button-label'] ?? 'Search' }}
        </button>
        @if (!empty($needle))
            <button class="btn btn-default reset-button equis" type="button" data-route="{{ $cancelRoute }}">x</button>
        @endif
    {!! Form::close() !!}
</div>