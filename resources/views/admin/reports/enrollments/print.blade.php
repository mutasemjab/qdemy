<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.course_enrollment_reports') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .print-date {
            color: #666;
            font-size: 14px;
        }
        
        .statistics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        
        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        
        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('messages.course_enrollment_reports') }}</h1>
        <div class="print-date">
            {{ __('messages.print_date') }}: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <div class="statistics">
        <div class="stat-card">
            <h3>{{ __('messages.total_enrollments') }}</h3>
            <div class="value">{{ $statistics['total_enrollments'] }}</div>
        </div>
        <div class="stat-card">
            <h3>{{ __('messages.unique_students') }}</h3>
            <div class="value">{{ $statistics['unique_students'] }}</div>
        </div>
        <div class="stat-card">
            <h3>{{ __('messages.unique_courses') }}</h3>
            <div class="value">{{ $statistics['unique_courses'] }}</div>
        </div>
        <div class="stat-card">
            <h3>{{ __('messages.total_revenue') }}</h3>
            <div class="value">{{ number_format($statistics['total_revenue'], 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('messages.id') }}</th>
                <th>{{ __('messages.student') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.phone') }}</th>
                <th>{{ __('messages.course') }}</th>
                <th>{{ __('messages.teacher') }}</th>
                <th>{{ __('messages.subject') }}</th>
                <th>{{ __('messages.enrollment_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $enrollment)
            <tr>
                <td>{{ $enrollment->id }}</td>
                <td>{{ $enrollment->user->name }}</td>
                <td>{{ $enrollment->user->email }}</td>
                <td>{{ $enrollment->user->phone }}</td>
                <td>{{ app()->getLocale() == 'ar' ? $enrollment->course->title_ar : $enrollment->course->title_en }}</td>
                <td>{{ $enrollment->course->teacher->name }}</td>
                <td>{{ app()->getLocale() == 'ar' ? $enrollment->course->subject->name_ar : $enrollment->course->subject->name_en }}</td>
                <td>{{ $enrollment->created_at->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">{{ __('messages.no_enrollments_found') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>{{ __('messages.generated_by_system') }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            {{ __('messages.print') }}
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">
            {{ __('messages.close') }}
        </button>
    </div>

    <script>
        window.onload = function() {
            // Auto print when page loads (optional)
            // window.print();
        }
    </script>
</body>
</html>