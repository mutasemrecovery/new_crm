@extends('layouts.admin')
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

        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 1.3
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

        .btn-danger {
            background: rgba(255, 101, 132, .1);
            color: var(--accent2);
            border: 1.5px solid rgba(255, 101, 132, .2)
        }

        .btn-danger:hover {
            background: rgba(255, 101, 132, .2)
        }

        .btn-success {
            background: rgba(67, 233, 123, .1);
            color: #43e97b;
            border: 1.5px solid rgba(67, 233, 123, .2)
        }

        .btn-success:hover {
            background: rgba(67, 233, 123, .2)
        }

        /* Layout */
        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px
        }

        @media(max-width:1050px) {
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

        /* Badges */
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

        /* Status grid */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 7px
        }

        .status-btn {
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

        .status-btn:hover {
            transform: translateY(-1px)
        }

        .status-btn.todo {
            color: var(--muted);
            border-color: rgba(107, 114, 128, .25)
        }

        .status-btn.in_progress {
            color: #00c6ff;
            border-color: rgba(0, 198, 255, .25)
        }

        .status-btn.review {
            color: #f7b731;
            border-color: rgba(247, 183, 49, .25)
        }

        .status-btn.done {
            color: #43e97b;
            border-color: rgba(67, 233, 123, .25)
        }

        .status-btn.cancelled {
            color: var(--accent2);
            border-color: rgba(255, 101, 132, .25)
        }

        .status-btn:not(.current) {
            opacity: .5
        }

        .status-btn:not(.current):hover {
            opacity: 1
        }

        .status-btn.current.todo {
            background: rgba(107, 114, 128, .12);
            opacity: 1
        }

        .status-btn.current.in_progress {
            background: rgba(0, 198, 255, .10);
            opacity: 1
        }

        .status-btn.current.review {
            background: rgba(247, 183, 49, .10);
            opacity: 1
        }

        .status-btn.current.done {
            background: rgba(67, 233, 123, .10);
            opacity: 1
        }

        .status-btn.current.cancelled {
            background: rgba(255, 101, 132, .10);
            opacity: 1
        }

        /* Info rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
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

        /* Team */
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
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
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

        /* Assign */
        .assign-row {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border)
        }

        .assign-sel {
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

        .assign-sel:focus {
            border-color: var(--accent)
        }

        /* Timeline */
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
            flex-wrap: wrap;
            margin-top: 2px
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

        /* Comments */
        .comment-item {
            display: flex;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .04)
        }

        .comment-item:last-child {
            border-bottom: none
        }

        .comment-av {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0
        }

        .comment-av.adm {
            background: linear-gradient(135deg, #f7b731, #fb923c)
        }

        .comment-av.usr {
            background: linear-gradient(135deg, var(--accent), #8b7eff)
        }

        .comment-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
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

        .adm-badge {
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
            margin-top: 16px;
            padding-top: 16px;
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

        .note-inp {
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
            box-sizing: border-box
        }

        .note-inp:focus {
            border-color: var(--accent)
        }

        .note-inp::placeholder {
            color: var(--muted)
        }

        /* Breadcrumb */
        .bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px
        }

        .bc a {
            color: var(--muted);
            text-decoration: none
        }

        .bc a:hover {
            color: var(--accent)
        }

        .bc i {
            font-size: 9px
        }
    </style>
@endpush

@section('content')

    {{-- Header --}}
    <div class="page-header">
        <div>
            <div class="bc">
                <a href="{{ route('admin.tasks.index') }}">{{ __('admin.nav_tasks') }}</a>
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
                <span>{{ Str::limit($task->title, 40) }}</span>
            </div>
            <h1>{{ $task->title }}</h1>
            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
                <span class="badge badge-{{ $task->status }}">{{ __('admin.' . $task->status) }}</span>
                <span class="badge badge-{{ $task->priority }}">{{ __('admin.' . $task->priority) }}</span>
                @if ($task->service)
                    <span
                        style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(108,99,255,.1);color:var(--accent);border:1px solid rgba(108,99,255,.2)">
                        {{ $task->service->name }}
                    </span>
                @endif
                @if ($task->isOverdue())
                    <span
                        style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(255,101,132,.1);color:var(--accent2)">
                        <i class="fas fa-exclamation-triangle" style="font-size:9px"></i> {{ __('admin.overdue') }}
                    </span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if ($task->status === 'review')
                <form action="{{ route('admin.tasks.complete', $task) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-double"></i> {{ __('admin.approve_done') }}
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-primary">
                <i class="fas fa-pen"></i> {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div
            style="background:rgba(67,233,123,.08);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:12px 16px;margin-bottom:18px;color:#43e97b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($task->status === 'review')
        <div
            style="background:rgba(247,183,49,.06);border:1px solid rgba(247,183,49,.2);border-radius:12px;padding:14px 18px;margin-bottom:18px;display:flex;align-items:center;gap:12px">
            <i class="fas fa-clock" style="color:#f7b731;font-size:18px;flex-shrink:0"></i>
            <div>
                <div style="font-size:13px;font-weight:700;color:#f7b731">{{ __('admin.task_awaiting_review') }}</div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px">{{ __('admin.task_awaiting_review_hint') }}
                </div>
            </div>
        </div>
    @endif

    <div class="detail-layout">

        {{-- ══ LEFT ══ --}}
        <div>

            {{-- Status Change --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title"><i class="fas fa-exchange-alt"></i> {{ __('admin.change_status') }}</div>
                </div>
                <form action="{{ route('admin.tasks.status', $task) }}" method="POST" id="status-form">
                    @csrf
                    <input type="hidden" name="status" id="status-input" value="{{ $task->status }}">
                    <div class="status-grid">
                        @foreach (['todo', 'in_progress', 'review', 'done', 'cancelled'] as $s)
                            <button type="button"
                                class="status-btn {{ $s }} {{ $task->status === $s ? 'current' : '' }}"
                                onclick="selectStatus('{{ $s }}')">
                                <i class="fas {{ \App\Models\TaskStatusLog::statusIcon($s) }}"
                                    style="display:block;font-size:14px;margin-bottom:3px"></i>
                                {{ __('admin.' . $s) }}
                            </button>
                        @endforeach
                    </div>
                    <textarea name="note" class="note-inp" rows="2" placeholder="{{ __('admin.status_note_hint') }}"></textarea>
                    <button type="submit" id="save-status-btn" class="btn btn-primary"
                        style="width:100%;justify-content:center;margin-top:10px;display:none">
                        <i class="fas fa-save"></i> {{ __('admin.save_status') }}
                    </button>
                </form>
            </div>

            {{-- Description / Notes --}}
            @if ($task->description || $task->notes)
                <div class="card">
                    <div class="card-head">
                        <div class="card-title"><i class="fas fa-align-left"></i> {{ __('admin.description') }}</div>
                    </div>
                    @if ($task->description)
                        <p
                            style="font-size:14px;color:var(--muted);line-height:1.75;white-space:pre-wrap;margin:0 0 {{ $task->notes ? '14px' : '0' }}">
                            {{ $task->description }}</p>
                    @endif
                    @if ($task->notes)
                        <div style="{{ $task->description ? 'padding-top:14px;border-top:1px solid var(--border)' : '' }}">
                            <div
                                style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:7px">
                                {{ __('admin.notes') }}
                            </div>
                            <p style="font-size:13px;color:var(--muted);line-height:1.7;white-space:pre-wrap;margin:0">
                                {{ $task->notes }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Comments --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">
                        <i class="fas fa-comments"></i> {{ __('admin.comments') }}
                        <span
                            style="font-size:11px;background:var(--surface2);border:1px solid var(--border);border-radius:20px;padding:2px 8px;color:var(--muted)">
                            {{ $task->comments->count() }}
                        </span>
                    </div>
                </div>

                @forelse($task->comments->sortBy('created_at') as $comment)
                    <div class="comment-item">
                        <div class="comment-av {{ $comment->is_admin ? 'adm' : 'usr' }}">
                            {{ $comment->initial }}
                        </div>
                        <div style="flex:1">
                            <div class="comment-meta">
                                <span class="comment-author">{{ $comment->author_name }}</span>
                                @if ($comment->is_admin)
                                    <span class="adm-badge">{{ __('admin.admin') }}</span>
                                @endif
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="comment-body">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted);font-size:13px;padding:4px 0;margin:0">{{ __('admin.no_comments') }}</p>
                @endforelse

                <form action="{{ route('admin.tasks.comments.store', $task) }}" method="POST">
                    @csrf
                    <div class="comment-form">
                        <div class="comment-av adm">
                            {{ strtoupper(mb_substr(auth()->guard('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <textarea name="body" class="comment-input" rows="1" placeholder="{{ __('admin.write_comment') }}"
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
                    <div class="card-title"><i class="fas fa-history"></i> {{ __('admin.status_history') }}</div>
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
                                            style="font-size:9px;font-weight:800;padding:2px 7px;border-radius:20px;background:rgba(247,183,49,.15);color:#f7b731">{{ __('admin.admin') }}</span>
                                    @endif
                                    <span class="tl-time">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="tl-change">
                                    @if ($log->from_status)
                                        <span class="badge badge-{{ $log->from_status }}"
                                            style="font-size:9px;padding:2px 7px">{{ __('admin.' . $log->from_status) }}</span>
                                        <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"
                                            style="font-size:9px;color:var(--muted)"></i>
                                    @endif
                                    <span class="badge badge-{{ $log->to_status }}"
                                        style="font-size:9px;padding:2px 7px">{{ __('admin.' . $log->to_status) }}</span>
                                </div>
                                @if ($log->note)
                                    <div class="tl-note">{{ $log->note }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color:var(--muted);font-size:13px;margin:0">{{ __('admin.no_history') }}</p>
                @endif
            </div>

        </div>

        {{-- ══ RIGHT ══ --}}
        <div>

            {{-- Task details --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title"><i class="fas fa-info-circle"></i> {{ __('admin.task_details') }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('admin.client') }}</span>
                    <span class="info-val">
                        <a href="{{ route('admin.clients.show', $task->client) }}"
                            style="color:var(--accent);text-decoration:none">{{ $task->client->name }}</a>
                    </span>
                </div>
                @if ($task->service)
                    <div class="info-row">
                        <span class="info-label">{{ __('admin.service') }}</span>
                        <span class="info-val">{{ $task->service->name }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">{{ __('admin.priority') }}</span>
                    <span class="info-val"><span
                            class="badge badge-{{ $task->priority }}">{{ __('admin.' . $task->priority) }}</span></span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('admin.progress') }}</span>
                    <span class="info-val">
                        <span
                            style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">{{ $task->progress }}%</span>
                    </span>
                </div>
                @if ($task->due_date)
                    <div class="info-row">
                        <span class="info-label">{{ __('admin.due_date') }}</span>
                        <span class="info-val" style="color:{{ $task->isOverdue() ? 'var(--accent2)' : 'var(--text)' }}">
                            {{ $task->due_date->format('d M Y') }}
                            @if ($task->isOverdue())
                                <div style="font-size:10px;color:var(--accent2)">{{ $task->due_date->diffForHumans() }}
                                </div>
                            @endif
                        </span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">{{ __('admin.created_at') }}</span>
                    <span class="info-val" style="color:var(--muted)">{{ $task->created_at->format('d M Y') }}</span>
                </div>
            </div>

            {{-- Assigned employees --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-title">
                        <i class="fas fa-users"></i> {{ __('admin.assigned_to') }}
                        <span
                            style="font-size:11px;background:var(--surface2);border:1px solid var(--border);border-radius:20px;padding:2px 8px;color:var(--muted)">
                            {{ $task->employees->count() }}
                        </span>
                    </div>
                </div>

                @forelse($task->employees as $emp)
                    <div class="team-member">
                        <div class="member-av">
                            @if ($emp->avatar)
                                <img src="{{ asset('storage/' . $emp->avatar) }}" alt="{{ $emp->name }}">
                            @else
                                {{ strtoupper(mb_substr($emp->name, 0, 1)) }}
                            @endif
                        </div>
                        <div style="flex:1">
                            <div style="font-size:13px;font-weight:600">{{ $emp->name }}</div>
                            <div style="font-size:11px;color:var(--muted)">{{ $emp->job_title }}</div>
                        </div>
                        {{-- Remove button --}}
                        <form action="{{ route('admin.tasks.employees.remove', [$task, $emp]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger"
                                style="padding:5px 9px;font-size:11px;border-radius:7px"
                                onclick="if(confirm('{{ addslashes(__('admin.confirm_delete')) }}')) this.closest('form').submit()">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p style="color:var(--muted);font-size:13px;margin:0 0 10px">{{ __('admin.no_employees_assigned') }}
                    </p>
                @endforelse

                {{-- Assign new employee --}}
                @php $assignable = $allEmployees->whereNotIn('id', $task->employees->pluck('id')); @endphp
                @if ($assignable->count())
                    <form action="{{ route('admin.tasks.assign', $task) }}" method="POST">
                        @csrf
                        <div class="assign-row">
                            <select name="employee_id" class="assign-sel" required>
                                <option value="">{{ __('admin.assign_employee') }}...</option>
                                @foreach ($assignable as $emp)
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

            {{-- Delete --}}
            <div class="card" style="padding:16px">
                <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" id="delete-task-form">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-danger" style="width:100%;justify-content:center"
                        onclick="confirmDelete('delete-task-form','{{ addslashes(__('admin.confirm_delete')) }}')">
                        <i class="fas fa-trash"></i> {{ __('admin.delete_task') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const currentStatus = '{{ $task->status }}';

        function selectStatus(status) {
            document.getElementById('status-input').value = status;
            document.querySelectorAll('.status-btn').forEach(b => {
                b.classList.remove('current');
                b.style.opacity = '.5';
            });
            const sel = document.querySelector('.status-btn.' + status);
            if (sel) {
                sel.classList.add('current');
                sel.style.opacity = '1';
            }
            document.getElementById('save-status-btn').style.display =
                status !== currentStatus ? 'flex' : 'none';
        }
    </script>
@endpush
