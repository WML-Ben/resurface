<?php
    $params = $params ?? null;

    $links = $params['links'] ?? 7;
    $query = '&' . ($params['query'] ?? http_build_query(Input::except(['page', '_token'])));
    $routeParams = $params['routeParams'] ?? [];
    $pageLimits = $params['pageLimits'] ?? [10, 20];

    $firstCaption = $params['first-caption'] ?? '«';
    $lastCaption = $params['last-caption'] ?? '»';

    $uClass = $params['ul-class'] ?? 'pagination custom-pagination';
    $liClass = $params['li-class'] ?? 'li-class';
    $liFirstClass = $params['li-first-class'] ?? 'li-first-class';
    $liLastClass = $params['li-last-class'] ?? 'li-last-class';
    $liEdgeClass = $params['li-edge-class'] ?? 'li-edge-class';
    $liInnerClass = $params['li-inner-class'] ?? 'li-inner-class';
    $selectedClass = $params['selected-class'] ?? 'active selected';
    $disabledClass = $params['disabled-class'] ?? 'disabled';

    $lang = $params['lang'] ?? 'en';
?>

<div class="row pagination-container clearfix text-center">
    <div class="col-xs-12 col-sm-4">
        <div class="pull-left pagination-info">
            @if ($collection->total() == 0)
                <span>{{ $lang == 'sp' ? 'No hay elementos que mostrar' : 'There is no item to show' }}.</span>
            @else
                @if ($collection->perPage() > $collection->total())
                    <span>{{ $lang == 'sp' ? 'Mostrando' : 'Showing' }} {!! $collection->total() !!} {{ str_plural($lang == 'sp' ? 'elemento' : 'item') }}</span>
                @else
                    <span>{{ $lang == 'sp' ? 'Mostrando' : 'Showing' }} {{ $collection->perPage() }} {{ $lang == 'sp' ? 'de' : 'of' }} {{ $collection->total() }} {{ str_plural($lang == 'sp' ? 'elemento' : 'item') }}</span>
                @endif
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 pagination-pages">
        {{ $lang == 'sp' ? 'Elementos por p&aacute;gina' : 'Items per page' }}:
        <select id="pageItems" class="form-control">
            @foreach ($pageLimits as $pageLimit)
                <option value="{{ route($routeName, array_merge($routeParams, Request::query(), ['page' => 0, 'perPage' => $pageLimit])) }}"{{ Request::input('perPage') == $pageLimit ? ' selected' : '' }}>{{ $pageLimit }}</option>
            @endforeach
            <option value="{{ route($routeName, array_merge($routeParams, Request::query(), ['page' => 0, 'perPage' => $collection->total()])) }}"{{ Request::input('perPage') == $collection->total() ? ' selected' : '' }}>{{ $lang == 'sp' ? 'Todo' : 'All' }}</option>
        </select>
    </div>
    <div class="col-xs-12 col-sm-4 pull-right pagination-handlers text-right">
        @if ($collection->lastPage() > 1)
            <ul class="{!! $uClass !!}">
                <li class="{!! $liClass !!} {!! $liFirstClass !!} {!! $liEdgeClass !!} {{ ($collection->currentPage() == 1) ? $disabledClass : '' }}">
                    <a href="{{ $collection->url(1) . $query }}">{!! $firstCaption !!}</a>
                </li>
                @for ($i = 1; $i <= $collection->lastPage(); $i++)
                    <?php
                    $halfTotalLinks = floor($links / 2);
                    $from = $collection->currentPage() - $halfTotalLinks;
                    $to = $collection->currentPage() + $halfTotalLinks;
                    if ($collection->currentPage() < $halfTotalLinks) {
                        $to += $halfTotalLinks - $collection->currentPage();
                    }
                    if ($collection->lastPage() - $collection->currentPage() < $halfTotalLinks) {
                        $from -= $halfTotalLinks - ($collection->lastPage() - $collection->currentPage()) - 1;
                    }
                    ?>
                    @if ($from < $i && $i < $to)
                        <li class="{!! $liClass !!} {!! $liInnerClass !!} {{ ($collection->currentPage() == $i) ? $selectedClass : '' }}">
                            <a href="{{ $collection->url($i) . $query }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor
                <li class="{!! $liClass !!} {!! $liLastClass !!} {!! $liEdgeClass !!} {{ ($collection->currentPage() == $collection->lastPage()) ? $disabledClass : '' }}">
                    <a href="{{ $collection->url($collection->lastPage()) . $query }}">{!! $lastCaption !!}</a>
                </li>
            </ul>
        @endif
    </div>
</div>






