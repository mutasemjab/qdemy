@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Parent Profile') }}</h3>
                    <div>
                        @can('parent-edit')
                            <a href="{{ route('parents.edit', $parent) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
                            </a>
                        @endcan
                        <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Parents') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Parent Header Section -->
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="parent-avatar-section">
                                @if($parent->user && $parent->user->photo)
                                    <img src="{{ asset('storage/' . $parent->user->photo) }}" 
                                         alt="{{ $parent->name }}" 
                                         class="parent-avatar">
                                @else
                                    <div class="parent-avatar-placeholder">
                                        {{ substr($parent->name, 0, 2) }}
                                    </div>
                                @endif
                                <h3 class="parent-name">{{ $parent->name }}</h3>
                                <span class="parent-badge">
                                    <i class="fas fa-user-friends"></i> {{ __('messages.Parent') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <!-- Parent Statistics -->
                                <div class="col-md-4">
                                    <div class="stat-card bg-primary">
                                        <div class="stat-icon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <div class="stat-content">
                                            <h4>#{{ $parent->id }}</h4>
                                            <p>{{ __('messages.Parent ID') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="stat-card bg-success">
                                        <div class="stat-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="stat-content">
                                            <h4>{{ $parent->students->count() }}</h4>
                                            <p>{{ __('messages.Total Students') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="stat-card {{ $parent->user ? 'bg-info' : 'bg-secondary' }}">
                                        <div class="stat-icon">
                                            <i class="fas fa-{{ $parent->user ? 'user-check' : 'user-times' }}"></i>
                                        </div>
                                        <div class="stat-content">
                                            <h4>{{ $parent->user ? __('messages.Active') : __('messages.No Account') }}</h4>
                                            <p>{{ __('messages.User Account') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Account Information Section -->
                    @if($parent->user)
                    <div class="info-section">
                        <div class="section-header">
                            <h5><i class="fas fa-user-circle text-success"></i> {{ __('messages.User Account Information') }}</h5>
                        </div>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label><i class="fas fa-envelope"></i> {{ __('messages.Email') }}</label>
                                        <div class="info-value">
                                            <a href="mailto:{{ $parent->user->email }}" class="text-primary">
                                                {{ $parent->user->email }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @if($parent->user->phone)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label><i class="fas fa-phone"></i> {{ __('messages.Phone') }}</label>
                                        <div class="info-value">
                                            <a href="tel:{{ $parent->user->phone }}" class="text-primary">
                                                {{ $parent->user->phone }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($parent->user->category)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label><i class="fas fa-tags"></i> {{ __('messages.Category') }}</label>
                                        <div class="info-value">
                                            <span class="badge badge-warning">{{ $parent->user->category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label><i class="fas fa-toggle-on"></i> {{ __('messages.Account Status') }}</label>
                                        <div class="info-value">
                                            <span class="badge badge-{{ $parent->user->activate == 1 ? 'success' : 'danger' }}">
                                                {{ $parent->user->activate == 1 ? __('messages.Active') : __('messages.Inactive') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($parent->user->balance > 0)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label><i class="fas fa-wallet"></i> {{ __('messages.Balance') }}</label>
                                        <div class="info-value">
                                            <span class="text-success font-weight-bold">${{ number_format($parent->user->balance, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Students Section -->
                    <div class="info-section">
                        <div class="section-header">
                            <h5><i class="fas fa-graduation-cap text-info"></i> {{ __('messages.Associated Students') }}</h5>
                            <span class="badge badge-info">{{ $parent->students->count() }} {{ __('messages.Students') }}</span>
                        </div>
                        <div class="section-content">
                            @if($parent->students->count() > 0)
                                <div class="students-grid">
                                    @foreach($parent->students as $student)
                                        <div class="student-card">
                                            <div class="student-avatar">
                                                @if($student->photo)
                                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                                         alt="{{ $student->name }}">
                                                @else
                                                    <div class="student-avatar-placeholder">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="student-info">
                                                <h6>{{ $student->name }}</h6>
                                                @if($student->email)
                                                    <small class="text-muted">{{ $student->email }}</small>
                                                @endif
                                                @if($student->category)
                                                    <div class="mt-1">
                                                        <span class="badge badge-sm badge-info">{{ $student->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="student-actions">
                                                <a href="{{ route('users.show', $student) }}" 
                                                   class="btn btn-outline-info btn-sm" 
                                                   title="{{ __('messages.View Student') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('parent-edit')
                                                    <form action="{{ route('parents.remove-student', [$parent, $student]) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('{{ __('messages.Are you sure you want to remove this student from the parent?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                title="{{ __('messages.Remove') }}">
                                                            <i class="fas fa-unlink"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted"></i>
                                    <h6 class="text-muted mt-2">{{ __('messages.No students associated with this parent') }}</h6>
                                </div>
                            @endif

                            <!-- Add Student Section -->
                            @can('parent-edit')
                            @php
                                $availableStudents = App\Models\User::where('role_name', 'student')
                                    ->whereNotIn('id', $parent->students->pluck('id'))
                                    ->get();
                            @endphp
                            @if($availableStudents->count() > 0)
                            <div class="add-student-section">
                                <h6><i class="fas fa-plus-circle"></i> {{ __('messages.Add Student to Parent') }}</h6>
                                <form action="{{ route('parents.add-student', $parent) }}" method="POST" class="add-student-form">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <select name="student_id" class="form-control" required>
                                                <option value="">{{ __('messages.Select Student') }}</option>
                                                @foreach($availableStudents as $availableStudent)
                                                    <option value="{{ $availableStudent->id }}">
                                                        {{ $availableStudent->name }}
                                                        @if($availableStudent->email)
                                                            ({{ $availableStudent->email }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fas fa-plus"></i> {{ __('messages.Add Student') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                            @endcan
                        </div>
                    </div>

                    <!-- Record Information Section -->
                    <div class="info-section">
                        <div class="section-header">
                            <h5><i class="fas fa-info-circle text-secondary"></i> {{ __('messages.Record Information') }}</h5>
                        </div>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label><i class="fas fa-calendar-plus"></i> {{ __('messages.Created At') }}</label>
                                        <div class="info-value">{{ $parent->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label><i class="fas fa-calendar-edit"></i> {{ __('messages.Last Updated') }}</label>
                                        <div class="info-value">{{ $parent->updated_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                                @if($parent->user)
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label><i class="fas fa-user-plus"></i> {{ __('messages.User Created') }}</label>
                                        <div class="info-value">{{ $parent->user->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="actions-section">
                        <div class="btn-group" role="group">
                            @can('parent-edit')
                                <a href="{{ route('parents.edit', $parent) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> {{ __('messages.Edit Parent') }}
                                </a>
                            @endcan
                           
                            <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> {{ __('messages.All Parents') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Parent Avatar Section */
.parent-avatar-section {
    text-align: center;
    padding: 20px;
}

.parent-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffc107;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-bottom: 15px;
}

.parent-avatar-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    margin: 0 auto 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.parent-name {
    margin: 10px 0;
    color: #333;
    font-weight: 600;
}

.parent-badge {
    background: #ffc107;
    color: #333;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Statistics Cards */
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    color: white;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
}

.stat-icon {
    font-size: 2.5rem;
    margin-right: 15px;
    opacity: 0.8;
}

.stat-content h4 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: bold;
}

.stat-content p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Information Sections */
.info-section {
    margin-bottom: 30px;
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
}

.section-header {
    background: white;
    padding: 15px 20px;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h5 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.section-content {
    padding: 20px;
}

.info-item {
    margin-bottom: 15px;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.info-value {
    font-size: 1rem;
    color: #333;
}

/* Students Grid */
.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
}

.student-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.student-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.student-avatar {
    width: 50px;
    height: 50px;
    margin-right: 15px;
    flex-shrink: 0;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.student-avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #17a2b8;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
}

.student-info {
    flex: 1;
}

.student-info h6 {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.student-actions {
    display: flex;
    gap: 5px;
}

.badge-sm {
    font-size: 0.7rem;
    padding: 3px 8px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
}

/* Add Student Section */
.add-student-section {
    background: white;
    border: 2px dashed #28a745;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.add-student-section h6 {
    color: #28a745;
    margin-bottom: 15px;
    font-weight: 600;
}

.add-student-form {
    margin: 0;
}

/* Actions Section */
.actions-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .students-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .parent-avatar,
    .parent-avatar-placeholder {
        width: 120px;
        height: 120px;
    }
    
    .stat-content h4 {
        font-size: 1.5rem;
    }
}
</style>
@endsection