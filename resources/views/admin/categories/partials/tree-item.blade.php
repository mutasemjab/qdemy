<div class="category-item border-bottom" data-category-id="{{ $category->id }}">
    <div class="d-flex align-items-center p-3 category-header"
         style="padding-left: {{ ($level * 20) + 15 }}px !important;">

        <!-- Toggle Button -->
        @if($category->children()->where('is_active', true)->count() > 0)
            <button type="button" class="btn btn-sm btn-link p-0 mr-2 toggle-btn"
                    data-toggle="collapse"
                    data-target="#children-{{ $category->id }}"
                    aria-expanded="false">
                <i class="fas fa-chevron-right transition-icon"></i>
            </button>
        @else
            <span class="mr-4"></span>
        @endif

        <!-- Category Icon -->
        @if($category->icon)
            <span class="mr-2" style="color: {{ $category->color }}">
                <i class="{{ $category->icon }}"></i>
            </span>
        @endif

        <!-- Category Info -->
        <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="font-weight-bold">{{ $category->name_ar }}</span>
                    @if($category->name_en)
                        <small class="text-muted ml-2">({{ $category->name_en }})</small>
                    @endif

                     @if($category->type == 'lesson')
                            <span class="badge badge-success mr-1" title="{{ __('messages.Lesson - Teachable subject') }}">
                                <i class="fas fa-book-open"></i> {{ __('messages.Lesson') }}
                            </span>
                        @elseif($category->type == 'major')
                            <span class="badge badge-primary mr-1" title="{{ __('messages.Major - Main program') }}">
                                <i class="fas fa-star"></i> {{ __('messages.Major') }}
                            </span>
                        @else
                            <span class="badge badge-secondary mr-1" title="{{ __('messages.Class - Organizational category') }}">
                                <i class="fas fa-folder"></i> {{ __('messages.Class') }}
                            </span>
                        @endif

                    <!-- Badges -->
                    <div class="mt-1">
                        @if($category->is_active)
                            <span class="badge badge-success mr-1">{{ __('messages.Active') }}</span>
                        @else
                            <span class="badge badge-danger mr-1">{{ __('messages.Inactive') }}</span>
                        @endif

                        @if($category->children()->count() > 0)
                            <span class="badge badge-info mr-1">
                                {{ $category->children()->count() }} {{ __('messages.Children') }}
                            </span>
                        @endif

                        <span class="badge badge-secondary">{{ __('messages.Level') }} {{ $level + 1 }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="btn-group" role="group">
                    @can('category-table')
                        <a href="{{ route('categories.show', $category) }}"
                           class="btn btn-sm btn-outline-info"
                           title="{{ __('messages.View') }}">
                            <i class="fas fa-eye"></i>
                        </a>
                    @endcan

                    @can('category-edit')
                        <a href="{{ route('categories.edit', $category) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="{{ __('messages.Edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endcan

                    @can('category-edit')
                        <form method="POST" action="{{ route('categories.toggle-status', $category) }}"
                              class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-{{ $category->is_active ? 'warning' : 'success' }}"
                                    title="{{ $category->is_active ? __('messages.Deactivate') : __('messages.Activate') }}">
                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                            </button>
                        </form>
                    @endcan

                </div>
            </div>
        </div>
    </div>

    <!-- Children -->
    @if($category->children()->where('is_active', true)->count() > 0)
        <div class="collapse" id="children-{{ $category->id }}">
            @foreach($category->children()->where('is_active', true)->orderBy('sort_order')->orderBy('name_ar')->get() as $child)
                @include('admin.categories.partials.tree-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
