<ul>
    @if (Request::is('surrenders/new'))
        <li class="sidebar-header"><a href="{{ url('adoptions') }}" class="card-link">{{ $name }}</a></li>
    @else
        <li class="sidebar-header"><a href="{{ url('shops') }}" class="card-link">Shops</a></li>
        @auth
            <li class="sidebar-section">
                <div class="sidebar-section-header">My Currencies</div>
                @foreach (Auth::user()->getCurrencies(true) as $currency)
                    @if ($currency->is_staff_currency == 1 && !Auth::user()->isStaff)
                    @else
                        <div class="sidebar-item pr-3">{!! $currency->display($currency->quantity) !!}</div>
                    @endif
                @endforeach
            </li>
        @endauth
    @endif

        <li class="sidebar-section">
            <div class="sidebar-section-header">Shops</div>
            @if (!Request::is('surrenders/new'))
            @foreach ($shops as $shop)
                @if ($shop->is_staff)
                    @if (auth::check() && auth::user()->isstaff)
                        <div class="sidebar-item"><a href="{{ $shop->url }}" class="{{ set_active('shops/' . $shop->id) }}">{{ $shop->name }}</a></div>
                    @endif
                @else
                    <div class="sidebar-item"><a href="{{ $shop->url }}" class="{{ set_active('shops/' . $shop->id) }}">{{ $shop->name }}</a></div>
                @endif
            @endforeach
            @endif
            <div class="sidebar-item"><a href="{{ url('adoptions') }}" class="{{ set_active('adoptions') }}">{{ $name }}</a></div>
        </li>

    @auth
        <li class="sidebar-section">
            <div class="sidebar-section-header">History</div>
            <div class="sidebar-item"><a href="{{ url('adoptions/history') }}" class="{{ set_active('adoptions/history') }}">Adoption History</a></div>
            <div class="sidebar-item"><a href="{{ url('surrenders') }}">Surrender History</a></div>
        </li>
    @endauth
</ul>