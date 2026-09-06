<ul>
    <li class="sidebar-header"><a href="{{ url('shops') }}" class="card-link">Shops</a></li>

    @if (Auth::check())
        <li class="sidebar-section">
            <div class="sidebar-section-header">My Currencies</div>
            @foreach (Auth::user()->getCurrencies(true) as $currency)
                @if ($currency->is_staff_currency == 1 && !Auth::user()->isStaff)
                @else
                    <div class="sidebar-item pr-3">{!! $currency->display($currency->quantity) !!}</div>
                @endif
            @endforeach
        </li>
    @endif

    <li class="sidebar-section">
        <div class="sidebar-section-header">Shops</div>
        @foreach ($shops as $shop)
            @if ($shop->is_staff)
                @if (auth::check() && auth::user()->isstaff)
                    <div class="sidebar-item"><a href="{{ $shop->url }}" class="{{ set_active('shops/' . $shop->id) }}">{{ $shop->name }}</a></div>
                @endif
            @else
                <div class="sidebar-item"><a href="{{ $shop->url }}" class="{{ set_active('shops/' . $shop->id) }}">{{ $shop->name }}</a></div>
            @endif
        @endforeach
        <div class="sidebar-item"><a href="{{ url('adoptions') }}" class="{{ set_active('adoptions') }}">{{ $adoptions->name }}</a></div>
    </li>

    @if (Auth::check())
        <li class="sidebar-section">
            <div class="sidebar-section-header">History</div>
            <div class="sidebar-item"><a href="{{ url('shops/history') }}" class="{{ set_active('shops/history') }}">Purchase History</a></div>
        </li>
    @endif
</ul>
