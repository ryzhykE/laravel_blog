@extends('layout')

@section('content')
<!--main content start-->
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <article class="post">
                    <div class="post-thumb">
                        <a href="{{route('post.show', $post->slug)}}"><img src="{{$post->getImage()}}" alt=""></a>
                    </div>
                    <div class="post-content">
                        <header class="entry-header text-center text-uppercase">
                            @if($post->hasCategory())
                            <h6><a href="{{route('category.show', $post->category->slug)}}"> {{$post->getCategoryTitle()}}</a></h6>
                            @endif
                            <h1 class="entry-title"><a href="{{route('post.show', $post->slug)}}">{{$post->title}}</a></h1>


                        </header>
                        <div class="entry-content">
                            {!! $post->content !!}
                        </div>
                        <div class="decoration">
                        @foreach($post->tags as $tag)
                            <a href="{{route('tag.show', $tag->slug)}}" class="btn btn-default">{{$tag->title}}</a>
                        @endforeach
                        </div>

                        <div class="social-share">
							<span class="social-share-title pull-left text-capitalize">
                                 On {{$post->getDate()}}
                            </span>
                            <ul class="text-center pull-right">
                                <li><a class="s-facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="s-twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a class="s-google-plus" href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a class="s-linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a class="s-instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </article>
                <div class="row"><!--blog next previous-->
                    <div class="col-md-6">
                    	@if($post->hasPrevious())
                        <div class="single-blog-box">
                            <a href="{{route('post.show', $post->getPrevious()->slug)}}">
                                <img src="{{$post->getPrevious()->getImage()}}" alt="">
                                <div class="overlay">
                                    <div class="promo-text">
                                        <p><i class=" pull-left fa fa-angle-left"></i></p>
                                        <h5>{{$post->getPrevious()->title}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                    	@if($post->hasNext())
                        <div class="single-blog-box">
                            <a href="{{route('post.show', $post->getNext()->slug)}}">
                                <img src="{{$post->getNext()->getImage()}}" alt="">
                                <div class="overlay">
                                    <div class="promo-text">
                                        <p><i class=" pull-right fa fa-angle-right"></i></p>
                                        <h5>{{$post->getNext()->title}}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                </div><!--blog next previous end-->

                <div class="related-post-carousel"><!--related post carousel-->
                    <div class="related-heading">
                        <h4>You might also like</h4>
                    </div>
                    <div class="items">
                    @foreach($post->related() as $item)
                        <div class="single-item">
                            <a href="{{route('post.show', $item->slug)}}">
                                <img src="{{$item->getImage()}}" alt="">
                                <p>{{$item->title}}</p>
                            </a>
                        </div>
                    @endforeach

                    </div>
                </div><!--related post carousel-->
                
            </div>
            @include('pages._sidebar')
        </div>
    </div>
</div>
<!-- end main content-->
@endsection