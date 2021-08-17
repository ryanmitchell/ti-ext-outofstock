<div class="row-fluid">
    @if($noLocation)
    <p class="p-4">@lang('thoughtco.outofstock::default.text_select_location')</p>
    @else
    {!! $this->renderList() !!}
    @endif
</div>
