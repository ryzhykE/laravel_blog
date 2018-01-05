@extends('layout')

@section('content')
<!--main content start-->
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @section('content')

                            <div class="page-header">
                                <h3>My Favorites</h3>
                            </div>
                            @forelse ($myFavorites as $myFavorite)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ $myFavorite->title }}
                                </div>

                                <div class="panel-body">
                                    {{ $myFavorite->body }}
                                </div>
                                @if (Auth::check())
                                <div class="panel-footer">
                                    <favorite
                                        :post={{ $myFavorite->id }}
                                        :favorited={{ $myFavorite->favorited() ? 'true' : 'false' }}
                                        ></favorite>
                                </div>
                                @endif
                            </div>
                            @empty
                            <p>You have no favorite posts.</p>
                            @endforelse

                @endsection
            </div>
            @include('pages._sidebar')
        </div>
    </div>
</div>
<!-- end main content-->
@endsection