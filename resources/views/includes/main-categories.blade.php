<!-- Main Categories Grid -->
<div class="categories-grid">
    @if(isset($mainCategories) && count($mainCategories) > 0)
        @foreach($mainCategories as $category)
            <a href="{{ route('categories.show', $category['type']) }}" class="category-card" style="text-decoration: none; color: inherit;">
                <div class="category-icon {{ $category['type'] }}" style="color: {{ $category['color'] }};">
                    <i class="{{ $category['icon'] }}"></i>
                </div>
                <h3 class="category-title">{{ $category['name'] }}</h3>
                <p class="category-description">{{ $category['description'] }}</p>
            </a>
        @endforeach
    @else
        <div style="text-align: center; color: white; grid-column: 1/-1; padding: 3rem;">
            <i class="fas fa-exclamation-triangle" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>لا توجد فئات متاحة</h3>
            <p>يرجى التواصل مع الإدارة لإضافة الفئات</p>
        </div>
    @endif
</div>