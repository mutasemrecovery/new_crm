@extends('layouts.employee')
@section('title', $task->title)

@push('styles')
    <style>
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 26px;
            gap: 16px;
            flex-wrap: wrap
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
            border: none;
            white-space: nowrap
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            color: #fff;
            box-shadow: 0 4px 14px rgba(108, 99, 255, .3)
        }

        .btn-primary:hover {
            opacity: .9;
            color: #fff
        }

        .btn-ghost {
            background: var(--surface2);
            color: var(--text);
            border: 1.5px solid var(--border)
        }

        .btn-ghost:hover {
            border-color: var(--accent);
            color: var(--accent)
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px
        }

        @media(max-width:1000px) {
            .detail-layout {
                grid-template-columns: 1fr
            }
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 18px
        }

        .card:last-child {
            margin-bottom: 0
        }

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border)
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .card-title i {
            color: var(--accent);
            font-size: 13px
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700
        }

        .badge-todo {
            background: rgba(107, 114, 128, .12);
            color: var(--muted)
        }

        .badge-in_progress {
            background: rgba(0, 198, 255, .12);
            color: #00c6ff
        }

        .badge-review {
            background: rgba(247, 183, 49, .12);
            color: #f7b731
        }

        .badge-done {
            background: rgba(67, 233, 123, .12);
            color: #43e97b
        }

        .badge-cancelled {
            background: rgba(255, 101, 132, .12);
            color: var(--accent2)
        }

        .badge-urgent {
            background: rgba(255, 50, 50, .15);
            color: #ff4444
        }

        .badge-high {
            background: rgba(255, 101, 132, .12);
            color: var(--accent2)
        }

        .badge-medium {
            background: rgba(247, 183, 49, .12);
            color: #f7b731
        }

        .badge-low {
            background: rgba(67, 233, 123, .12);
            color: #43e97b
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px
        }

        .status-option {
            padding: 9px 6px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            border: 1.5px solid transparent;
            font-family: inherit;
            text-align: center;
            transition: all .15s;
            background: var(--surface2)
        }

        .status-option:hover {
            transform: translateY(-1px)
        }

        .status-option.todo {
            color: var(--muted);
            border-color: rgba(107, 114, 128, .25)
        }

        .status-option.in_progress {
            color: #00c6ff;
            border-color: rgba(0, 198, 255, .25)
        }

        .status-option.review {
            color: #f7b731;
            border-color: rgba(247, 183, 49, .25)
        }

        .status-option.done {
            color: #43e97b;
            border-color: rgba(67, 233, 123, .25)
        }

        .status-option.cancelled {
            color: var(--accent2);
            border-color: rgba(255, 101, 132, .25)
        }

        .status-option.current {
            opacity: 1 !important
        }

        .status-option.todo.current {
            background: rgba(107, 114, 128, .12)
        }

        .status-option.in_progress.current {
            background: rgba(0, 198, 255, .10)
        }

        .status-option.review.current {
            background: rgba(247, 183, 49, .10)
        }

        .status-option.done.current {
            background: rgba(67, 233, 123, .10)
        }

        .status-option.cancelled.current {
            background: rgba(255, 101, 132, .10)
        }

        .status-option:not(.current) {
            opacity: .55
        }

        .status-option:not(.current):hover {
            opacity: 1
        }

        .timeline {
            position: relative;
            padding-inline-start: 16px
        }

        .timeline::before {
            content: '';
            position: absolute;
            inset-inline-start: 7px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--border);
            border-radius: 99px
        }

        .timeline-item {
            position: relative;
            padding-bottom: 16px;
            padding-inline-start: 18px
        }

        .timeline-item:last-child {
            padding-bottom: 0
        }

        .tl-dot {
            position: absolute;
            inset-inline-start: -9px;
            top: 4px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--surface);
            flex-shrink: 0
        }

        .tl-header {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 3px;
            flex-wrap: wrap
        }

        .tl-name {
            font-size: 12px;
            font-weight: 700
        }

        .tl-time {
            font-size: 11px;
            color: var(--muted)
        }

        .tl-change {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap
        }

        .tl-note {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            font-style: italic;
            background: var(--surface2);
            border-radius: 6px;
            padding: 4px 8px;
            display: inline-block
        }

        .tl-arrow {
            font-size: 9px;
            color: var(--muted)
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .04);
            font-size: 13px;
            gap: 12px
        }

        .info-row:last-child {
            border-bottom: none
        }

        .info-label {
            color: var(--muted);
            font-weight: 500;
            flex-shrink: 0
        }

        .info-val {
            font-weight: 600;
            text-align: end
        }

        .team-member {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .04)
        }

        .team-member:last-child {
            border-bottom: none
        }

        .member-av {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden
        }

        .member-av img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .remove-btn {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: rgba(255, 101, 132, .08);
            border: 1px solid rgba(255, 101, 132, .15);
            color: var(--accent2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            transition: all .15s;
            padding: 0;
            flex-shrink: 0
        }

        .remove-btn:hover {
            background: rgba(255, 101, 132, .18);
            border-color: rgba(255, 101, 132, .35)
        }

        .assign-form {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border)
        }

        .assign-select {
            flex: 1;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--text);
            font-family: inherit;
            outline: none
        }

        .assign-select:focus {
            border-color: var(--accent)
        }

        .comment-item {
            display: flex;
            gap: 11px;
            padding: 13px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .04)
        }

        .comment-item:last-child {
            border-bottom: none
        }

        .comment-av {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0
        }

        .comment-av.user-av {
            background: linear-gradient(135deg, var(--accent), #8b7eff)
        }

        .comment-av.admin-av {
            background: linear-gradient(135deg, #f7b731, #fb923c)
        }

        .comment-meta {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 4px;
            flex-wrap: wrap
        }

        .comment-author {
            font-size: 13px;
            font-weight: 700
        }

        .comment-time {
            font-size: 11px;
            color: var(--muted)
        }

        .admin-badge {
            font-size: 9px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 20px;
            background: rgba(247, 183, 49, .15);
            color: #f7b731
        }

        .comment-body {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.65
        }

        .comment-form {
            display: flex;
            gap: 10px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
            align-items: flex-end
        }

        .comment-input {
            flex: 1;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            color: var(--text);
            font-family: inherit;
            outline: none;
            resize: none;
            min-height: 42px;
            transition: border-color .2s
        }

        .comment-input:focus {
            border-color: var(--accent)
        }

        .comment-input::placeholder {
            color: var(--muted)
        }

        .note-input {
            width: 100%;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
            color: var(--text);
            font-family: inherit;
            outline: none;
            margin-top: 8px;
            transition: border-color .2s;
            box-sizing: border-box
        }

        .note-input:focus {
            border-color: var(--accent)
        }

        .note-input::placeholder {
            color: var(--muted)
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div style="display:flex;gap:8px;align-items:center">
            <a href="{{ route('employee.tasks.index') }}" class="btn btn-ghost" style="padding:9px 12px">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            </a>
            <div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:3px">{{ $task->client->name }}</div>
                <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;line-height:1.3">
                    {{ $task->title }}
                </div>
                <div style="display:flex;gap:5px;margin-top:6px;flex-wrap:wrap">
                    <span class="badge badge-{{ $task->status }}">{{ __('emp.' . $task->status) }}</span>
                    <span class="badge badge-{{ $task->priority }}">{{ __('emp.' . $task->priority) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div
            style="background:rgba(67,233,123,.08);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:12px 16px;margin-bottom:16px;color:#43e97b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="detail-layout">

        {{-- ══ LEFT ══ --}}
        <div>

            {{-- Status Change --}}
            @if (!in_array($task->status, ['cancelled']))
                <div class="card">
                    <div class="card-head">
                        <div class="card-title"><i class="fas fa-exchange-alt"></i> {{ __('emp.change_status') }}</div>
                    </div>
                    <form action="{{ route('employee.tasks.status', $task) }}" method="POST" id="status-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" id="status-input" value="{{ $task->status }}">
                        <div class="status-grid">
                            @foreach (['todo', 'in_progress', 'review', 'done', 'cancelled'] as $s)
                                <button type="button"
                                    class="status-option {{ $s }} {{ $task->status === $s ? 'current' : '' }}"
                                    onclick="selectStatus('{{ $s }}')">
                                    <i class="fas {{ \App\Models\TaskStatusLog::statusIcon($s) }}"
                                        style="display:block;font-size:14px;margin-bottom:3px"></i>
                                    {{ __('emp.' . $s) }}
                                </button>
                            @endforeach
                        </div>
                        <textarea name="note" class="note-input" placeholder="{{ __('emp.status_note_hint') }}" rows="2"></textarea>
                        <button type="submit" class="btn btn-primary" id="save-status-btn"
                            style="width:100%;justify-content:center;margin-top:10px;display:none">
                            <i class="fas fa-save"></i> {{ __('emp.save_status') }}
                        </button>
                    </form>
                </div>
            @endif

            {{-- Description --}}
            @if ($task->description || $task->notes)
                <div class="card">
                    <div class="card-head">
                        <div class="card-title"><i class="fas fa-align-left"></i> {{ __('emp.description') }}</div>
                    </div>
                    @if ($task->description)
                        <p style="font-size:14px;color:var(--muted);line-height:1.75;margin:0 0 12px">
                            {{ $task->description }}</p>
                    @endif
                    @if ($task->notes)
                        <div style="{{ $task->description ? 'padding-top:12px;border-top:1px solid var(--border)' : '' }}">
                            <div
                                style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
                                {{ __('emp.notes') }}
                            </div>
                            <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">{{ $task->notes }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Comments --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">
                        <i class="fas fa-comments"></i> {{ __('emp.comments') }}
                        <span
                            style="font-size:11px;background:var(--surface2);border:1px solid var(--border);border-radius:20px;padding:2px 8px;color:var(--muted)">
                            {{ $task->comments->count() }}
                        </span>
                    </div>
                </div>

                @forelse($task->comments->sortBy('created_at') as $comment)
                    <div class="comment-item">
                        <div class="comment-av {{ $comment->is_admin ? 'admin-av' : 'user-av' }}">
                            {{ $comment->initial }}
                        </div>
                        <div style="flex:1">
                            <div class="comment-meta">
                                <span class="comment-author">{{ $comment->author_name }}</span>
                                @if ($comment->is_admin)
                                    <span class="admin-badge">{{ __('emp.manager') }}</span>
                                @endif
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="comment-body">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted);font-size:13px;padding:8px 0;margin:0">{{ __('emp.no_comments') }}</p>
                @endforelse

                <form action="{{ route('employee.tasks.comment', $task) }}" method="POST">
                    @csrf
                    <div class="comment-form">
                        <div class="comment-av user-av">
                            {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <textarea name="body" class="comment-input" rows="1" placeholder="{{ __('emp.write_comment') }}"
                            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'" required></textarea>
                        <button type="submit" class="btn btn-primary" style="padding:10px 14px;align-self:flex-end">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Status History --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title"><i class="fas fa-history"></i> {{ __('emp.status_history') }}</div>
                </div>
                @if ($task->statusLogs->count())
                    <div class="timeline">
                        @foreach ($task->statusLogs->sortBy('created_at') as $log)
                            @php $color = \App\Models\TaskStatusLog::statusColor($log->to_status); @endphp
                            <div class="timeline-item">
                                <div class="tl-dot" style="background:{{ $color }}"></div>
                                <div class="tl-header">
                                    <span class="tl-name">{{ $log->changer_name }}</span>
                                    @if ($log->changer_type === 'admin')
                                        <span
                                            style="font-size:9px;font-weight:800;padding:2px 7px;border-radius:20px;background:rgba(247,183,49,.15);color:#f7b731">
                                            {{ __('emp.manager') }}
                                        </span>
                                    @endif
                                    <span class="tl-time">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="tl-change">
                                    @if ($log->from_status)
                                        <span class="badge badge-{{ $log->from_status }}"
                                            style="font-size:9px;padding:2px 7px">
                                            {{ __('emp.' . $log->from_status) }}
                                        </span>
                                        <i
                                            class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} tl-arrow"></i>
                                    @endif
                                    <span class="badge badge-{{ $log->to_status }}" style="font-size:9px;padding:2px 7px">
                                        {{ __('emp.' . $log->to_status) }}
                                    </span>
                                </div>
                                @if ($log->note)
                                    <div class="tl-note">{{ $log->note }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color:var(--muted);font-size:13px;margin:0">{{ __('emp.no_history') }}</p>
                @endif
            </div>

        </div>

        {{-- ══ RIGHT ══ --}}
        <div>

            {{-- Task info --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title"><i class="fas fa-info-circle"></i> {{ __('emp.task_details') }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('emp.client') }}</span>
                    <span class="info-val">{{ $task->client->name }}</span>
                </div>
                @if ($task->service)
                    <div class="info-row">
                        <span class="info-label">{{ __('emp.service') }}</span>
                        <span class="info-val">{{ $task->service->name }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">{{ __('emp.priority') }}</span>
                    <span class="info-val">
                        <span class="badge badge-{{ $task->priority }}">{{ __('emp.' . $task->priority) }}</span>
                    </span>
                </div>
                @if ($task->due_date)
                    <div class="info-row">
                        <span class="info-label">{{ __('emp.due_date') }}</span>
                        <span class="info-val" style="color:{{ $task->isOverdue() ? 'var(--accent2)' : 'var(--text)' }}">
                            {{ $task->due_date->format('d M Y') }}
                        </span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">{{ __('emp.created') }}</span>
                    <span class="info-val" style="color:var(--muted)">{{ $task->created_at->format('d M Y') }}</span>
                </div>
            </div>

            {{-- Team + Assign --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">
                        <i class="fas fa-users"></i> {{ __('emp.team') }}
                    </div>
                </div>

                @foreach ($task->employees as $mem)
                    <div class="team-member">
                        <div class="member-av">
                            @if ($mem->avatar)
                                <img src="{{ asset('storage/' . $mem->avatar) }}" alt="{{ $mem->name }}">
                            @else
                                {{ strtoupper(mb_substr($mem->name, 0, 1)) }}
                            @endif
                        </div>
                        <div style="flex:1">
                            <div style="font-size:13px;font-weight:600">{{ $mem->name }}</div>
                            <div style="font-size:11px;color:var(--muted)">{{ $mem->job_title }}</div>
                        </div>
                        @if ($mem->id === $employee->id)
                            {{-- نفسك: تظهر شارة "أنت" --}}
                            <span
                                style="font-size:10px;font-weight:800;padding:2px 8px;border-radius:20px;background:rgba(108,99,255,.12);color:var(--accent)">
                                {{ __('emp.you') }}
                            </span>
                            <form action="{{ route('employee.tasks.remove', [$task, $mem]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="remove-btn" title="{{ __('emp.remove_from_task') }}"
                                    onclick="if(confirm('{{ addslashes(__('emp.confirm_remove')) }}')) this.closest('form').submit()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        @elseif(!in_array($task->status, ['done', 'cancelled']))
                            {{-- موظف آخر: تظهر زر X --}}
                            <form action="{{ route('employee.tasks.remove', [$task, $mem]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="remove-btn" title="{{ __('emp.remove_from_task') }}"
                                    onclick="if(confirm('{{ addslashes(__('emp.confirm_remove')) }}')) this.closest('form').submit()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach

                {{-- Assign form --}}
                @if (isset($otherEmployees) && $otherEmployees->count() && !in_array($task->status, ['done', 'cancelled']))
                    <form action="{{ route('employee.tasks.assign', $task) }}" method="POST">
                        @csrf
                        <div class="assign-form">
                            <select name="employee_id" class="assign-select" required>
                                <option value="">{{ __('emp.assign_to') }}...</option>
                                @foreach ($otherEmployees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} — {{ $emp->job_title }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" style="padding:8px 14px;flex-shrink:0">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const currentStatus = '{{ $task->status }}';

        function selectStatus(status) {
            document.getElementById('status-input').value = status;
            document.querySelectorAll('.status-option').forEach(btn => {
                btn.classList.remove('current');
                btn.style.opacity = '.55';
            });
            const selected = document.querySelector('.status-option.' + status);
            if (selected) {
                selected.classList.add('current');
                selected.style.opacity = '1';
            }
            document.getElementById('save-status-btn').style.display =
                status !== currentStatus ? 'flex' : 'none';
        }
    </script>
@endpush
