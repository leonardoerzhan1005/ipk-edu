<section class="blog_4 mt_110 xs_mt_90 pt_120 xs_pt_100 pb_120 xs_pb_100">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 wow fadeInLeft">
                <div class="wsus__section_heading heading_left mb_50">
                    <h5>{{__('Latest blogs')}}</h5>
                    <h2>{{__('Our Latest News Feed.')}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row blog_4_slider">
        @forelse($blogs as $blog)
            @if($blog->translated_slug)
            <div class="col-xl-4 col-md-6 wow fadeInUp" style="margin-bottom: 30px !important; padding: 0 15px !important;">
                <div class="wsus__single_blog_vertical" style="display: flex !important; flex-direction: column !important; height: 100% !important; background: #fff !important; border-radius: 10px !important; overflow: hidden !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;">
                    <a href="{{ route('blog.show',['locale' => request()->route('locale'), 'slug' => $blog->translated_slug]) }}" style="position: relative !important; width: 100% !important; height: 280px !important; display: block !important; background-color: #f5f5f5 !important; overflow: hidden !important;">
                        <img src="{{ asset($blog->image) }}" alt="Blog" class="img-fluid" style="width: 100% !important; height: 100% !important; object-fit: contain !important;">
                        <span class="date" style="position: absolute !important; top: 20px !important; left: 20px !important; color: #fff !important; font-size: 14px !important; font-weight: 500 !important; background: var(--colorPrimary) !important; padding: 4px 10px !important; border-radius: 6px !important; z-index: 10 !important;">
                           
                            
                            {{ $blog->published_at
    ? $blog->published_at->translatedFormat('d F Y')
    : $blog->created_at->translatedFormat('d F Y') }}
                        </span>
                    </a>
                    <div style="padding: 25px !important; flex: 1 !important; display: flex !important; flex-direction: column !important;">
                        <ul style="display: flex !important; flex-wrap: wrap !important; gap: 15px !important; margin-bottom: 15px !important; list-style: none !important; padding: 0 !important;">
                            <li style="display: flex !important; align-items: center !important; gap: 5px !important; font-size: 14px !important; color: #666 !important;">
                                <span><img src="{{ asset('frontend/assets/images/user_icon_black.png') }}"
                                        alt="User" class="img-fluid" style="width: 16px !important;"></span>
                                By {{ $blog->author->name }}
                            </li>
                            <li style="display: flex !important; align-items: center !important; gap: 5px !important; font-size: 14px !important; color: #666 !important;">
                                <span><img src="{{ asset('frontend/assets/images/comment_icon_black.png') }}"
                                        alt="Comment" class="img-fluid" style="width: 16px !important;"></span>
                                {{ $blog->comments()->count() }} {{__('Comments')}}
                            </li>
                        </ul>
                        <a href="{{ route('blog.show',['locale' => request()->route('locale'), 'slug' => $blog->translated_slug]) }}" style="font-size: 20px !important; font-weight: 600 !important; color: #1a1a1a !important; margin-bottom: 12px !important; display: block !important; text-decoration: none !important; line-height: 1.4 !important;">
                            {{ Str::limit($blog->translated_title, 60) }}
                        </a>
                        <p style="color: #666 !important; font-size: 15px !important; line-height: 1.6 !important; margin-bottom: 20px !important; flex: 1 !important;">
                            {{ Str::limit(strip_tags($blog->translated_description), 100) }}
                        </p>
                        <a href="{{ route('blog.show',['locale' => request()->route('locale'), 'slug' => $blog->translated_slug]) }}" class="common_btn" style="align-self: flex-start !important;">
                            {{__('Read More')}} <i class="far fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div>{{__('No Blog Found')}}</div>
        @endforelse

    </div>
</section>
