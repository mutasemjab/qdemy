@extends('layouts.app')
@section('title',$package?->name)

@section('content')
<section class="pkgo-wrap">


    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{$package?->name}}</h2> <br>
            <p>ssssssssssss{{$package?->descriptq}}</p>
        </div>
    </div>

    @if($is_type_class && $categoriesTree && $categoriesTree->count())
    <div class="co-chooser co-chooser-wrapper">
        <button class="co-chooser-btn" id="ctgChooserBtn">
            @if($choosen_lessons && $choosen_lessons->count())
              @foreach($choosen_lessons as $lesson)
              <h4> {{$lesson->localized_name}} / {{$lesson?->parent->localized_name}} </h4>
              @endforeach
            @else
              <h4 class='default-select'>{{__('messages.choose_class')}}</h4>
            @endif
            <i class="fa-solid fa-caret-down"></i>
        </button>
        <form action="{{ route('package',['package'=>$package->id]) }}" method='get'>
            @CSRF

            <ul class="co-chooser-list" id="ctgChooserList">
                <li><a href="javascript:void(0)" class='text-decoration-none'>
                    {{__('messages.all classes')}}</a>
                </li>
                @foreach($categoriesTree as $categories)
                    @if($categories && count($categories))
                        @foreach($categories as $class)
                        @if($class['category']?->is_optional) @continue; @endif
                        <li>

                            @if($class['category']?->type == 'class')
                            {{$class['name']}}
                            @else
                            <label for="lessonsIds_{{$class['id']}}">{{$class['category']?->getBreadcrumbAttribute()}}</label>
                            <label for="lessonsIds_{{$class['id']}}">{{$class['name']}}</label>
                            <input type="checkbox" value="{{$class['id']}}" name="lessonsIds[{{$class['id']}}]" id="lessonsIds_{{$class['id']}}"
                                data-parent="@if($class['category']?->parent) {{$class['category']?->parent->localized_name}} @endif"
                                data-grand-parent="@if($class['category']?->parent?->parent) {{$class['category']?->parent?->parent->localized_name}} @endif">
                            @endif
                        </li>
                        @endforeach
                    @endif
                @endforeach
            </ul>

            <button type="submit" class="pkgo-head">
                {{ sprintf('%g', $package->price) }} <span>{{CURRENCY}}</span>
            </button>

        </form>
    </div>
    @endif

    @if($courses)
    <div class="pkgo-row">
        <div class="pkgo-side pkgo-side-1">
        <span>{{ $package->name }}</span>
        </div>
        <div class="pkgo-mid">
        <div class="pkgo-title">
            <h3>{{ $package->name }}</h3>
            <!-- <span class="pkgo-year">{{$package->description}}</span> -->
        </div>
        <p class="pkgo-desc">sssssssssssss</p>
        <ul class="pkgo-tags">
            <li>sssssssssssss</li>
        </ul>
        </div>
        <div class="pkgo-cta-col">
        <a href="{{route('package',$package)}}" class="pkgo-cta">{{__('messages.add_to_cart')}}</a>
        </div>
    </div>
    @endif

</section>
@endsection

@push('scripts')
<script>
// cards-order minimal interactions
const ctgBtn = document.getElementById('ctgChooserBtn');
const ctgList = document.getElementById('ctgChooserList');
if(ctgBtn && ctgList){
  ctgBtn.addEventListener('click',()=>{
    ctgBtn.parentElement.classList.toggle('open');
  });
  ctgList.querySelectorAll('li').forEach(li=>{
    li.addEventListener('click',()=>{
      ctgBtn.insertAdjacentHTML('afterbegin', '<h4>'+li.dataset.label || li.querySelector('label').textContent.trim()+'</h4>');
      ctgBtn.parentElement.classList.remove('open');
    });
  });
  document.addEventListener('click',e=>{
    if(!ctgBtn.parentElement.contains(e.target)){
      ctgBtn.parentElement.classList.remove('open');
    }
  });
}
</script>
@endpush
