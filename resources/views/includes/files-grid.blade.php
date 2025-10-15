<div class="files-grid">
    @foreach($files as $file)
        <div class="file-card">
            <div class="file-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="file-info">
                <h3 class="file-name">{{ $file->name }}</h3>
                <div class="file-meta">
                    <span class="file-type">
                        <i class="fas fa-file-alt"></i>
                        PDF
                    </span>
                    <span class="file-date">
                        <i class="fas fa-calendar"></i>
                        {{ $file->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="file-actions">
                <a href="{{ asset('assets/admin/uploads/'.$file->pdf) }}" target="_blank" class="btn-file-action btn-view">
                    <i class="fas fa-eye"></i>
                    عرض
                </a>
                <a href="{{ asset('assets/admin/uploads/'.$file->pdf) }}" download class="btn-file-action btn-download">
                    <i class="fas fa-download"></i>
                    تحميل
                </a>
            </div>
        </div>
    @endforeach
</div>