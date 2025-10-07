

<div class="ud-panel" id="notifications">
  <div class="ud-title">{{ __('panel.notifications') }}</div>
  <div class="ud-list">
    @forelse($notifications as $note)
      <div class="ud-note {{ is_null($note->read_at) ? 'bg-light' : '' }}">
        <div class="ud-note-main">
          <b>{{ $note->title }}</b>
          <small>{{ $note->body }}</small>
        </div>
        <span class="ud-badge">
          {{ is_null($note->read_at) ? __('panel.new') : '' }}
        </span>

        {{-- زر لتحديد مقروء --}}
        @if(is_null($note->read_at))
          <form method="POST" action="{{ route('student.notifications.read', $note->id) }}">
            @csrf
            <button type="submit" class="ud-primary">{{ __('panel.mark_as_read') }}</button>
          </form>
        @endif
      </div>
    @empty
      <p class="text-muted">{{ __('panel.no_notifications') }}</p>
    @endforelse
  </div>
</div>
