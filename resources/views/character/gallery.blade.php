@extends('character.layout', ['isMyo' => $character->is_myo_slot])

@section('profile-title')
    {{ $character->fullName }}'s Gallery
@endsection

@section('meta-img')
    {{ $character->image->thumbnailUrl }}
@endsection

@section('profile-content')
    @if ($character->is_myo_slot)
        {!! breadcrumbs(['MYO Slot Masterlist' => 'myos', $character->fullName => $character->url, 'Gallery' => $character->url . '/gallery']) !!}
    @else
        {!! breadcrumbs([
            $character->category->masterlist_sub_id ? $character->category->sublist->name . ' Masterlist' : 'Character masterlist' => $character->category->masterlist_sub_id ? 'sublist/' . $character->category->sublist->key : 'masterlist',
            $character->fullName => $character->url,
            'Gallery' => $character->url . '/gallery',
        ]) !!}
    @endif

    @include('character._header', ['character' => $character])

    <p>These images are user-submitted and should not be confused with the official record of the character's design and history visible <a href="{{ url($character->url . '/images') }}">here</a>.</p>

    @if ($character->gallerySubmissions->count())
        {!! $submissions->render() !!}

        <div class="d-flex align-content-around flex-wrap mb-2">
            @foreach ($submissions as $submission)
                @include('galleries._thumb', ['submission' => $submission, 'gallery' => false])
            @endforeach
        </div>

        {!! $submissions->render() !!}
        <div class="text-center mt-4 small text-muted">{{ $submissions->total() }} result{{ $submissions->total() == 1 ? '' : 's' }} found.</div>
    @else
        <p>No submissions found!</p>
    @endif

@endsection
