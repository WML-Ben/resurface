<?php
$params = $params ?? null;

$links = $params['links'] ?? 7;
$query = '&' . ($params['query'] ?? http_build_query(Input::except(['page', '_token'])));

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
?>

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