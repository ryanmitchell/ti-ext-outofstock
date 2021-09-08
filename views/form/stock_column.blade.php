                    @if ($out_of_stock->contains($value))
                        <a class="btn font-weight-bold p-0 text-danger" href="{{ $out_of_stock_url.'/stock/'.$value }}">@lang('thoughtco.outofstock::default.button_stock')</a>
                    @else
                        <button class="btn font-weight-bold p-0 dropdown-toggle text-secondary" type="button" data-toggle="dropdown">@lang('thoughtco.outofstock::default.button_nostock')</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          @foreach ($out_of_stock_delays as $delay)
							<a class="dropdown-item" href="{{ $out_of_stock_url.'/nostock/'.$value.'?hours='.$delay['time'] }}">{{ $delay['label'] }}</a>
                          @endforeach
						    @if ($out_of_stock_location)<a class="dropdown-item" href="{{ $out_of_stock_url.'/nostock/'.$value.'?hours=closing' }}">@lang('thoughtco.outofstock::default.button_closing')</a>@endif
						    <a class="dropdown-item" href="{{ $out_of_stock_url.'/nostock/'.$value }}">@lang('thoughtco.outofstock::default.button_forever')</a>
						</div>
                    @endif
