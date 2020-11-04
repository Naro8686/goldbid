@foreach($auctions as $auction)
    @include('site.include.auction')
@endforeach
<div class="w-100 mt-25">
    {!! $auctions->render() !!}
</div>
