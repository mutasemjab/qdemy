@extends('layouts.app')
@section('title', __('panel.exam_results'))

@section('styles')
<style>
:root{
  --bg:#f7f9fc;
  --card:#ffffff;
  --ink:#0f172a;
  --muted:#64748b;
  --line:#e5e7eb;
  --primary:#2b6cb0;
  --primary-ink:#174274;
  --success:#0e9f6e;
  --danger:#d33a2c;
  --warning:#c28100;
}
.page-wrap{background:var(--bg)}
.head-bar{display:flex;justify-content:space-between;gap:12px;align-items:end;background:var(--card);border:1px solid var(--line);border-radius:14px;padding:16px;margin-bottom:16px}
.head-bar h2{margin:0;font-size:22px;color:var(--ink);font-weight:900}
.subtle{margin:4px 0 0 0;color:var(--muted);font-size:13px}
.head-actions{display:flex;gap:8px;flex-wrap:wrap}
.btnx{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 14px;font-weight:800;font-size:14px;text-decoration:none;cursor:pointer;border:1px solid var(--line);background:#fff;color:var(--ink);transition:box-shadow .16s,transform .16s,border-color .16s}
.btnx:hover{transform:translateY(-1px);box-shadow:0 8px 18px rgba(15,23,42,.08)}
.btnx i{font-size:14px}
.btnx-primary{background:var(--primary);border-color:var(--primary);color:#fff}
.btnx-secondary{background:#111827;border-color:#0b1220;color:#fff}
.btnx-outline{background:#fff;border-color:var(--line);color:var(--ink)}
.kpis{display:grid;grid-template-columns:repeat(12,1fr);gap:12px;margin-bottom:16px}
.kpis .col{grid-column:span 2}
@media(max-width:1200px){.kpis .col{grid-column:span 3}}
@media(max-width:992px){.kpis .col{grid-column:span 4}}
@media(max-width:576px){.kpis .col{grid-column:span 6}}
.info-box{display:flex;background:var(--card);border:1px solid var(--line);border-radius:12px;overflow:hidden}
.info-icon{width:70px;display:flex;align-items:center;justify-content:center}
.info-icon i{font-size:22px;color:#fff}
.info-body{padding:10px 12px}
.info-text{display:block;color:var(--muted);font-size:12px;font-weight:700}
.info-number{display:block;color:var(--ink);font-size:20px;font-weight:900;line-height:1.1}
.bg-info{background:#3b82f6}
.bg-success{background:#10b981}
.bg-danger{background:#ef4444}
.bg-warning{background:#f59e0b}
.bg-primary{background:#2b6cb0}
.bg-secondary{background:#6b7280}
.cardx{background:var(--card);border:1px solid var(--line);border-radius:14px;overflow:hidden}
.cardx .cardx-h{padding:14px 16px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:12px}
.cardx .cardx-b{padding:16px}
.progress{height:26px;border-radius:999px;overflow:hidden;background:#f3f4f6;border:1px solid var(--line)}
.progress-bar{height:100%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:12px;background:var(--success)}
.tools{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.input-sm{border:1px solid var(--line);border-radius:10px;padding:8px 10px;font-size:13px}
.select-sm{border:1px solid var(--line);border-radius:10px;padding:8px 10px;font-size:13px;background:#fff}
.tablex{width:100%;border-collapse:separate;border-spacing:0}
.tablex thead th{background:#f8fafc;border-bottom:1px solid var(--line);padding:12px 12px;font-size:12px;color:var(--muted);font-weight:800}
.tablex tbody td{border-bottom:1px solid var(--line);padding:12px}
.badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:900}
.badge-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.badge-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.btnbar{display:inline-flex;gap:6px}
.btn-ghost{border:1px solid var(--line);background:#fff;color:var(--ink);padding:8px 10px;border-radius:10px;font-weight:800}
.btn-ghost:hover{border-color:#cfd4dc;box-shadow:0 6px 16px rgba(15,23,42,.06)}
.cardx-f{padding:12px 16px;display:flex;justify-content:space-between;align-items:center;gap:10px}
.meta-muted{color:var(--muted);font-size:12px}
.chart-wrap{height:320px}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:992px){.grid-2{grid-template-columns:1fr}}
.modal-loading{display:flex;align-items:center;justify-content:center;flex-direction:column;padding:30px;color:var(--muted)}
.spinner{width:28px;height:28px;border-radius:50%;border:3px solid #e5e7eb;border-top-color:var(--primary);animation:spin 1s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.avatar{width:40px;height:40px;display:flex;align-items:center;justify-content:center;color:#94a3b8}
.row-inline{display:flex;align-items:center;gap:10px}
.w-100{width:100%}
.hidden{display:none}
</style>
@endsection

@section('content')
<div class="container-fluid page-wrap">
  <div class="head-bar">
    <div>
      <h2>{{ __('panel.exam_results') }}</h2>
      <p class="subtle">
        {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}
        @if($exam->course)
          • {{ app()->getLocale() == 'ar' ? $exam->course->title_ar : $exam->course->title_en }}
        @endif
      </p>
    </div>
    <div class="head-actions">
      <a href="{{ route('teacher.exams.show', $exam) }}" class="btnx btnx-outline">
        <i class="fas fa-eye"></i>{{ __('panel.view_exam') }}
      </a>
      <a href="{{ route('teacher.exams.index') }}" class="btnx btnx-secondary">
        <i class="fas fa-arrow-left"></i>{{ __('panel.back') }}
      </a>
    </div>
  </div>

  <div class="kpis">
    <div class="col">
      <div class="info-box">
        <div class="info-icon bg-info"><i class="fas fa-users"></i></div>
        <div class="info-body">
          <span class="info-text">{{ __('panel.total_attempts') }}</span>
          <span class="info-number">{{ $stats['total_attempts'] }}</span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="info-box">
        <div class="info-icon bg-success"><i class="fas fa-check-circle"></i></div>
        <div class="info-body">
          <span class="info-text">{{ __('panel.passed') }}</span>
          <span class="info-number">{{ $stats['passed_attempts'] }}</span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="info-box">
        <div class="info-icon bg-danger"><i class="fas fa-times-circle"></i></div>
        <div class="info-body">
          <span class="info-text">{{ __('panel.failed') }}</span>
          <span class="info-number">{{ $stats['total_attempts'] - $stats['passed_attempts'] }}</span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="info-box">
        <div class="info-icon bg-warning"><i class="fas fa-chart-line"></i></div>
        <div class="info-body">
          <span class="info-text">{{ __('panel.average_score') }}</span>
          <span class="info-number">
            {{ $stats['average_score'] ? number_format($stats['average_score'], 1) : 0 }}%
          </span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="info-box">
        <div class="info-icon bg-primary"><i class="fas fa-arrow-up"></i></div>
        <div class="info-body">
          <span class="info-text">{{ __('panel.highest_score') }}</span>
          <span class="info-number">
            {{ $stats['highest_score'] ? number_format($stats['highest_score'], 1) : 0 }}%
          </span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="info-box">
        <div class="info-icon bg-secondary"><i class="fas fa-arrow-down"></i></div>
        <div class="info-body">
          <span class="info-text">{{ __('panel.lowest_score') }}</span>
          <span class="info-number">
            {{ $stats['lowest_score'] ? number_format($stats['lowest_score'], 1) : 0 }}%
          </span>
        </div>
      </div>
    </div>
  </div>

  @if($stats['total_attempts'] > 0)
    <div class="cardx">
      <div class="cardx-h">
        <h6 class="m-0">{{ __('panel.pass_rate') }}</h6>
      </div>
      <div class="cardx-b">
        @php
          $passRate = ($stats['passed_attempts'] / max(1,$stats['total_attempts'])) * 100;
        @endphp
        <div class="progress">
          <div class="progress-bar" style="width: {{ $passRate }}%">
            {{ number_format($passRate,1) }}% ({{ $stats['passed_attempts'] }}/{{ $stats['total_attempts'] }})
          </div>
        </div>
        <div class="meta-muted" style="margin-top:6px">
          {{ $stats['passed_attempts'] }} {{ __('panel.students_passed_out_of') }}
          {{ $stats['total_attempts'] }} {{ __('panel.total_attempts') }}
        </div>
      </div>
    </div>
  @endif

  <div class="cardx" style="margin-top:16px">
    <div class="cardx-h">
      <h6 class="m-0">
        <i class="fas fa-table"></i> {{ __('panel.detailed_results') }}
      </h6>
      <div class="tools">
        <form method="GET" class="row-inline">
          <div class="row-inline">
            <input
              type="text"
              name="search"
              class="input-sm"
              placeholder="{{ __('panel.search_student') }}"
              value="{{ request('search') }}"
            >
            <button class="btnx btnx-outline" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
          <select name="status" class="select-sm" onchange="this.form.submit()">
            <option value="">{{ __('panel.all_results') }}</option>
            <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>
              {{ __('panel.passed_only') }}
            </option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
              {{ __('panel.failed_only') }}
            </option>
          </select>
        </form>
      </div>
    </div>

    <div class="cardx-b p-0">
      @if($attempts->count() > 0)
        <div class="table-responsive">
          <table class="tablex">
            <thead>
              <tr>
                <th>{{ __('panel.student') }}</th>
                <th class="text-center">{{ __('panel.score') }}</th>
                <th class="text-center">{{ __('panel.percentage') }}</th>
                <th class="text-center">{{ __('panel.status') }}</th>
                <th class="text-center">{{ __('panel.time_taken') }}</th>
                <th class="text-center">{{ __('panel.submitted_at') }}</th>
                <th class="text-center">{{ __('panel.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($attempts as $attempt)
                <tr>
                  <td>
                    <div class="row-inline">
                      <div class="avatar">
                        <i class="fas fa-user-circle fa-2x"></i>
                      </div>
                      <div>
                        <div style="font-weight:800;color:var(--ink)">{{ $attempt->user->name }}</div>
                        <div class="meta-muted">{{ $attempt->user->email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <div style="font-weight:900;color:var(--ink)">
                      {{ number_format($attempt->score, 2) }}
                    </div>
                    <div class="meta-muted">/ {{ number_format($exam->total_grade, 2) }}</div>
                  </td>
                  <td class="text-center">
                    <span class="badge {{ $attempt->is_passed ? 'badge-success' : 'badge-danger' }}">
                      {{ number_format($attempt->percentage, 1) }}%
                    </span>
                  </td>
                  <td class="text-center">
                    @if($attempt->is_passed)
                      <span class="badge badge-success">
                        <i class="fas fa-check"></i> {{ __('panel.passed') }}
                      </span>
                    @else
                      <span class="badge badge-danger">
                        <i class="fas fa-times"></i> {{ __('panel.failed') }}
                      </span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($attempt->submitted_at)
                      @php
                        $t = $attempt->started_at->diffInMinutes($attempt->submitted_at);
                        $h = intval($t/60);
                        $m = $t%60;
                      @endphp
                      <span class="meta-muted">
                        {{ $h>0 ? $h.'h '.$m.'m' : $m.'m' }}
                      </span>
                    @else
                      <span class="meta-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <span class="meta-muted">
                      {{ $attempt->submitted_at ? $attempt->submitted_at->format('Y-m-d H:i') : '-' }}
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="btnbar">
                      <a
                        href="{{ route('teacher.exams.attempts.view', [$exam, $attempt]) }}"
                        class="btn-ghost"
                        title="{{ __('panel.view_details') }}"
                      >
                        <i class="fas fa-eye"></i>
                      </a>

                      <button
                        type="button"
                        class="btn-ghost js-answers"
                        data-attempt="{{ $attempt->id }}"
                        title="{{ __('panel.view_answers') }}"
                      >
                        <i class="fas fa-list"></i>
                      </button>

                      <button
                        type="button"
                        class="btn-ghost js-download"
                        data-attempt="{{ $attempt->id }}"
                        title="{{ __('panel.download_result') }}"
                      >
                        <i class="fas fa-download"></i>
                      </button>

                      @if(auth()->user()->can('grade_manually'))
                        <button
                          type="button"
                          class="btn-ghost js-manual"
                          data-attempt="{{ $attempt->id }}"
                          data-student="{{ $attempt->user->name }}"
                          data-score="{{ number_format($attempt->score,2) }}"
                          title="{{ __('panel.manual_grade') }}"
                        >
                          <i class="fas fa-edit"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="cardx-f">
          <div class="meta-muted">
            {{ __('panel.showing') }} {{ $attempts->firstItem() ?? 0 }}
            {{ __('panel.to') }} {{ $attempts->lastItem() ?? 0 }}
            {{ __('panel.of') }} {{ $attempts->total() }} {{ __('panel.results') }}
          </div>
          <div>
            {{ $attempts->appends(request()->query())->links() }}
          </div>
        </div>
      @else
        <div class="p-5 text-center">
          <i class="fas fa-chart-bar fa-3x" style="color:#94a3b8"></i>
          <div style="font-weight:900;color:var(--ink);margin-top:8px">
            {{ __('panel.no_completed_attempts') }}
          </div>
          <div class="meta-muted" style="margin-top:4px">
            {{ __('panel.no_students_completed_exam') }}
          </div>
          <div style="margin-top:12px">
            <a href="{{ route('teacher.exams.show', $exam) }}" class="btnx btnx-primary">
              <i class="fas fa-eye"></i>{{ __('panel.view_exam_details') }}
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>

  @if($stats['total_attempts'] > 0)
    <div class="grid-2" style="margin-top:16px">
      <div class="cardx">
        <div class="cardx-h">
          <h6 class="m-0">{{ __('panel.score_distribution') }}</h6>
        </div>
        <div class="cardx-b">
          <div class="chart-wrap">
            <canvas id="scoreChart"></canvas>
          </div>
        </div>
      </div>
      <div class="cardx">
        <div class="cardx-h">
          <h6 class="m-0">{{ __('panel.pass_fail_ratio') }}</h6>
        </div>
        <div class="cardx-b">
          <div class="chart-wrap">
            <canvas id="passFailChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

<div class="modal fade" id="answersModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('panel.student_answers') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="answersContent">
        <div class="modal-loading">
          <div class="spinner"></div>
          <div style="margin-top:10px">{{ __('panel.loading') }}...</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btnx btnx-secondary" data-bs-dismiss="modal">
          {{ __('panel.close') }}
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="manualGradeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('panel.manual_grading') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="manualGradeForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">{{ __('panel.student_name') }}</label>
            <input type="text" class="form-control" id="studentName" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('panel.current_score') }}</label>
            <input type="text" class="form-control" id="currentScore" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('panel.new_score') }}</label>
            <input type="number" class="form-control" id="newScore" step="0.01" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('panel.grade_reason') }}</label>
            <textarea class="form-control" id="gradeReason" rows="3" placeholder="{{ __('panel.enter_reason_for_grade_change') }}"></textarea>
          </div>
          <div id="manualError" class="meta-muted hidden"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btnx btnx-outline" data-bs-dismiss="modal">
            {{ __('panel.cancel') }}
          </button>
          <button type="submit" class="btnx btnx-primary" id="manualSubmit">
            {{ __('panel.update_grade') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var searchInput = document.querySelector('input[name="search"]');
  if (searchInput) {
    var to;
    searchInput.addEventListener('input', function () {
      clearTimeout(to);
      to = setTimeout(function () {
        searchInput.form.submit();
      }, 500);
    });
  }

  document.querySelectorAll('.js-answers').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var attemptId = this.dataset.attempt;
      var modal = new bootstrap.Modal(document.getElementById('answersModal'));
      var content = document.getElementById('answersContent');
      content.innerHTML =
        '<div class="modal-loading"><div class="spinner"></div><div style="margin-top:10px">{{ __('panel.loading') }}...</div></div>';
      modal.show();

      fetch(`{{ route('teacher.exams.index') }}/{{ $exam->id }}/attempts/${attemptId}/answers`)
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(d => {
          content.innerHTML = d.html || '<div class="meta-muted">{{ __("panel.error_loading_answers") }}</div>';
        })
        .catch(() => {
          content.innerHTML = '<div class="meta-muted">{{ __("panel.error_loading_answers") }}</div>';
        });
    });
  });

  document.querySelectorAll('.js-download').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var attemptId = this.dataset.attempt;
      this.disabled = true;
      this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
      window.location.href = `{{ route('teacher.exams.index') }}/{{ $exam->id }}/attempts/${attemptId}/download`;
      setTimeout(() => {
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-download"></i>';
      }, 2000);
    });
  });

  var manualForm = document.getElementById('manualGradeForm');
  var manualModalEl = document.getElementById('manualGradeModal');
  var manualModal = new bootstrap.Modal(manualModalEl);
  var manualSubmit = document.getElementById('manualSubmit');

  document.querySelectorAll('.js-manual').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.getElementById('studentName').value = this.dataset.student || '';
      document.getElementById('currentScore').value = this.dataset.score || '';
      manualForm.dataset.attemptId = this.dataset.attempt;
      document.getElementById('newScore').value = '';
      document.getElementById('gradeReason').value = '';
      document.getElementById('manualError').classList.add('hidden');
      manualModal.show();
    });
  });

  manualForm && manualForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var attemptId = this.dataset.attemptId;
    var newScore = document.getElementById('newScore').value;
    var reason = document.getElementById('gradeReason').value;

    manualSubmit.disabled = true;
    manualSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`{{ route('teacher.exams.index') }}/{{ $exam->id }}/attempts/${attemptId}/manual-grade`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ score: newScore, reason: reason })
    })
      .then(r => r.json())
      .then(d => {
        if (d.success) {
          window.location.reload();
        } else {
          document.getElementById('manualError').textContent = d.message || '{{ __("panel.error_updating_grade") }}';
          document.getElementById('manualError').classList.remove('hidden');
          manualSubmit.disabled = false;
          manualSubmit.textContent = '{{ __("panel.update_grade") }}';
        }
      })
      .catch(() => {
        document.getElementById('manualError').textContent = '{{ __("panel.error_updating_grade") }}';
        document.getElementById('manualError').classList.remove('hidden');
        manualSubmit.disabled = false;
        manualSubmit.textContent = '{{ __("panel.update_grade") }}';
      });
  });

  @if($stats['total_attempts'] > 0)
    var scoreCtx = document.getElementById('scoreChart').getContext('2d');
    new Chart(scoreCtx, {
      type: 'bar',
      data: {
        labels: ['0-20%','21-40%','41-60%','61-80%','81-100%'],
        datasets: [{
          data: [
            {{ $attempts->where('percentage','<=',20)->count() }},
            {{ $attempts->whereBetween('percentage',[21,40])->count() }},
            {{ $attempts->whereBetween('percentage',[41,60])->count() }},
            {{ $attempts->whereBetween('percentage',[61,80])->count() }},
            {{ $attempts->where('percentage','>=',81)->count() }}
          ],
          backgroundColor: ['#ef4444','#f59e0b','#fb923c','#3b82f6','#10b981']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
      }
    });

    var pfCtx = document.getElementById('passFailChart').getContext('2d');
    new Chart(pfCtx, {
      type: 'doughnut',
      data: {
        labels: ['{{ __("panel.passed") }}','{{ __("panel.failed") }}'],
        datasets: [{
          data: [{{ $stats['passed_attempts'] }}, {{ $stats['total_attempts'] - $stats['passed_attempts'] }}],
          backgroundColor: ['#10b981','#ef4444']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  @endif
});
</script>
@endpush
