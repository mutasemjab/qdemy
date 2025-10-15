<a href="{{ isset($params) ? route($route, $params) : route($route) }}" class="back-btn" style="display: block;">
    <i class="fas fa-arrow-right"></i>
    {{ $text ?? 'رجوع' }}
</a>