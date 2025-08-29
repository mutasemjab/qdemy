@extends('layouts.app')
@section('title',$package?->name)

@section('content')
<section class="pkgo-wrap">


    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{$package?->name}}</h2> <br>
        </div>
    </div>

    @if($is_type_class && $categoriesTree && $categoriesTree->count())
    <div class="co-chooser">
        <button class="co-chooser-btn" id="coChooserBtn">
            <span>{{ $clas?->localized_name ?? __('messages.choose class') }}</span>
            <i class="fa-solid fa-caret-down"></i>
        </button>

        <ul class="co-chooser-list" id="coChooserList">
            <li><a href="javascript:void(0)" class='text-decoration-none'>
                {{__('messages.all classes')}}</a>
            </li>
            @foreach($categoriesTree as $categories)
                @if($categories && count($categories))
                    @foreach($categories as $class)
                    <li>
                        <a class='text-decoration-none'
                            href="{{route('package',['package'=>$package->id,'clas'=>$class['id']])}}">
                            <!-- @if($class['category']?->parent) {{$class['category']?->parent->localized_name}} @endif >  -->
                            {{$class['name']}}
                        </a>
                    </li>
                    @endforeach
                @endif
            @endforeach
        </ul>


    </div>
    @endif

    <div type="submit" class="pkgo-head">
        {{ sprintf('%g', $package->price) }} <span>{{CURRENCY}}</span>
    </div>


    @if($lessons && $lessons->count())
    <div class="sp2-box">
      @foreach ($lessons as $lesson)
        <div class="sp2-group">
          <button class="sp2-group-head">
            <i class="fa-solid fa-plus"></i>
            <span>{{ $lesson->localized_name }}</span>
          </button>
          <div class="sp2-panel">
            <table class="sp2-table">
              <thead>
              <tr>
                <th>{{ $lesson->localized_name }}</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>
                    @if($lesson->is_optional == 0)

                        {{ $lesson->localized_name }}

                        <button>{{ __('messages.add to cart') }}</button>

                    @else
                        @php $optionals = CategoryRepository()->getOtionalSubjectsForField($lesson); @endphp
                        @foreach ($optionals as $optional)
                            <div class="sp2-group">
                            <button class="sp2-group-head">
                                <i class="fa-solid fa-plus"></i>
                                <span>{{ $optional->localized_name }}</span>
                            </button>
                            <div class="sp2-panel">
                                <table class="sp2-table">
                                <thead>
                                <tr>
                                    <!-- <th>{{ $optional->localized_name }}</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        {{ $optional->localized_name }}
                                        <button>{{ __('messages.add to cart') }}</button>
                                    </td>
                                </tr>
                                </tbody>
                                </table>
                            </div>
                            </div>
                        @endforeach

                    @endif
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      @endforeach
    </div>
    @endif


</section>
@endsection
