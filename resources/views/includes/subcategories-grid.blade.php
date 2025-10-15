<div class="subcategories-grid">
    @foreach($categories as $category)
        @if($category->hasChildren())
            <a href="{{ route('categories.subcategories', [$type, $category->id]) }}" 
               class="subcategory-card" style="text-decoration: none; color: inherit;">
                <div class="subcategory-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <h3 class="subcategory-title">{{ $category->name }}</h3>
                <p style="color: #666; font-size: 0.9rem; margin-top: 0.5rem;">يحتوي على المزيد</p>
            </a>
        @else
            @if($type == 'files')
                <a href="{{ route('category.files', $category->id) }}" 
                   class="subcategory-card" style="text-decoration: none; color: inherit;">
                    <div class="subcategory-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="subcategory-title">{{ $category->name }}</h3>
                    <p style="color: #28a745; font-size: 0.9rem; margin-top: 0.5rem; font-weight: bold;">جاهز للاستخدام</p>
                </a>
            @elseif($type == 'lessons')
                <a href="{{ route('category.lessons', $category->id) }}" 
                   class="subcategory-card" style="text-decoration: none; color: inherit;">
                    <div class="subcategory-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h3 class="subcategory-title">{{ $category->name }}</h3>
                    <p style="color: #27ae60; font-size: 0.9rem; margin-top: 0.5rem; font-weight: bold;">جاهز للمشاهدة</p>
                </a>
            @else
                 <a href="{{ route('category.exams', $category->id) }}" 
                   class="subcategory-card" style="text-decoration: none; color: inherit;">
                    <div class="subcategory-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="subcategory-title">{{ $category->name }}</h3>
                    <p style="color: #28a745; font-size: 0.9rem; margin-top: 0.5rem; font-weight: bold;">جاهز للاستخدام</p>
                </a>
            @endif
        @endif
    @endforeach
</div>