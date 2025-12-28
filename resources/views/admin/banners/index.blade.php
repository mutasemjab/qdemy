@extends('layouts.admin')
@section('title')
    {{ __('messages.banners') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.banners') }} </h3>
            <a href="{{ route('banners.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
        __('messages.banners') }}</a>
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('banners.index') }}" enctype="multipart/form-data">
                @csrf
                <div class="row my-3">
                    <div class="col-md-3">
                        <input autofocus type="text" placeholder="{{ __('messages.Search') }}" name="search"
                            class="form-control" value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-success "> {{ __('messages.Search') }} </button>
                    </div>
                </div>
            </form>

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('banner-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.photo_for_desktop') }}</th>
                                <th>{{ __('messages.photo_for_mobile') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>

                                        <td>
                                            <div class="image">
                                                <img class="custom_img"
                                                    src="{{ asset('assets/admin/uploads') . '/' . $info->photo_for_desktop }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="image">
                                                <img class="custom_img"
                                                    src="{{ asset('assets/admin/uploads') . '/' . $info->photo_for_mobile }}">
                                            </div>
                                        </td>

                                        <td>
                                            @can('user-edit')
                                                <a href="{{ route('banners.edit', $info->id) }}" class="btn btn-sm btn-primary">
                                                    {{ __('messages.Edit') }}
                                                </a>
                                            @endcan
                                            @can('banner-delete')
                                                <form action="{{ route('banners.destroy', $info->id) }}" method="POST"
                                                    onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                                                </form>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $data->links() }}
                    @else
                        <div class="alert alert-danger">
                            {{ __('messages.No_data') }}
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/banners.js') }}"></script>
@endsection