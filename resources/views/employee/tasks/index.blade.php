@extends('layouts.employee')
@section('title', __('emp.my_tasks'))

@push('styles')
<style>
/* ── Page Header ─────────────────────────────────── */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 16px;
    flex-wrap: wrap;
}
.page-header h1 {
    font-family: 'Syne', sans-serif;
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 4px;
}
.page-sub {
    font-size: 13px;
    color: var(--muted);
}

/* ── Stats Strip ─────────────────────────────────── */
.stats-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 26px;
}
@media(max-width: 700px) { .stats-strip { grid-template-columns: 1fr 1fr; } }

.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 18px 20px;
    cursor: pointer;
    transition: border-color .2s, transform .15s;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--stat-color, var(--accent));
    opacity: 0;
    transition: opacity .2s;
}
.stat-card:hover { transform: translateY(-2px); border-color: var(--stat-color, var(--accent)); }
.stat-card:hover::before { opacity: .04; }
.stat-card.active { border-color: var(--stat-color, var(--accent)); }
.stat-card.active::before { opacity: .06; }

.stat-val {
    font-family: 'Syne', sans-serif;
    font-size: 30px;
    font-weight: 800;
    color: var(--stat-color, var(--accent));
    position: relative;
}
.stat-lbl {
    font-size: 11px;
    color: var(--muted);
    margin-top: 3px;
    text-transform: uppercase;
    letter-spacing: .6px;
    position: relative;
}

/* ── Filters ─────────────────────────────────────── */
.filters-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    align-items: center;
}
.search-wrap {
    flex: 1;
    min-width: 200px;
    display: flex;
    align-items: center;
    gap: 9px;
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 0 14px;
    transition: border-color .2s;
}
.search-wrap:focus-within { border-color: var(--accent); }
.search-wrap i { color: var(--muted); font-size: 13px; }
.search-wrap input {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    color: var(--text);
    font-size: 13px;
    padding: 10px 0;
    font-family: inherit;
}
.filter-select {
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    color: var(--text);
    font-family: inherit;
    outline: none;
    cursor: pointer;
    transition: border-color .2s;
}
.filter-select:focus { border-color: var(--accent); }
.btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border-radius: 10px;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    border: none;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.btn-ghost {
    background: var(--surface2);
    color: var(--text);
    border: 1.5px solid var(--border);
}
.btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

/* ── Task Grid ───────────────────────────────────── */
.tasks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
}

.task-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    text-decoration: none;
    color: var(--text);
    display: block;
    transition: transform .2s, border-color .2s, box-shadow .2s;
    position: relative;
    overflow: hidden;
}
.task-card::after {
    content: '';
    position: absolute;
    top: 0;
    inset-inline-start: 0;
    width: 3px;
    height: 100%;
    background: var(--priority-color, var(--accent));
    border-radius: 0 0 0 0;
    transition: width .2s;
}
.task-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,.25);
    border-color: rgba(255,255,255,.12);
    color: var(--text);
}
.task-card:hover::after { width: 4px; }

/* Overdue glow */
.task-card.overdue {
    border-color: rgba(255,101,132,.2);
}
.task-card.overdue::after {
    background: var(--accent2);
}

.task-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 12px;
    gap: 8px;
}
.task-badges { display: flex; gap: 5px; flex-wrap: wrap; }
.badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}
.badge-todo        { background: rgba(107,114,128,.12); color: var(--muted); }
.badge-in_progress { background: rgba(0,198,255,.12);   color: #00c6ff; }
.badge-review      { background: rgba(247,183,49,.12);  color: #f7b731; }
.badge-done        { background: rgba(67,233,123,.12);  color: #43e97b; }
.badge-cancelled   { background: rgba(255,101,132,.12); color: var(--accent2); }
.badge-urgent      { background: rgba(255,50,50,.15);   color: #ff4444; }
.badge-high        { background: rgba(255,101,132,.12); color: var(--accent2); }
.badge-medium      { background: rgba(247,183,49,.12);  color: #f7b731; }
.badge-low         { background: rgba(67,233,123,.12);  color: #43e97b; }

.task-title {
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.4;
}
.task-client {
    font-size: 12px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 14px;
}

/* Progress */
.progress-wrap { margin-bottom: 14px; }
.progress-header {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: var(--muted);
    margin-bottom: 5px;
}
.progress-bar {
    height: 5px;
    background: var(--surface2);
    border-radius: 99px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, var(--accent), #8b7eff);
    transition: width .4s ease;
}
.progress-fill.done { background: linear-gradient(90deg, #43e97b, #00c6ff); }

.task-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid var(--border);
    font-size: 12px;
}
.task-date {
    display: flex;
    align-items: center;
    gap: 4px;
    color: var(--muted);
}
.task-date.overdue { color: var(--accent2); font-weight: 600; }

.teammates {
    display: flex;
}
.teammate-av {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    border: 2px solid var(--surface);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: 800;
    color: #fff;
    margin-inline-start: -7px;
}
.teammate-av:first-child { margin-inline-start: 0; }

/* Empty state */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: var(--muted);
}
.empty-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    background: var(--surface2);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 28px;
}
.empty-state h3 {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 6px;
    color: var(--text);
}

/* Pagination */
.pagination-wrap { margin-top: 24px; display: flex; justify-content: center; }

/* Service tag */
.service-tag {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 6px;
    background: rgba(108,99,255,.1);
    color: var(--accent);
    font-weight: 600;
    border: 1px solid rgba(108,99,255,.2);
}
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1>{{ __('emp.my_tasks') }}</h1>
        <p class="page-sub">{{ __('emp.my_tasks_sub') }}</p>
    </div>
</div>

@if(isset($noEmployee) && $noEmployee)
<div class="empty-state">
    <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
    <h3>{{ __('emp.no_employee_profile') }}</h3>
    <p style="font-size:13px;margin-top:6px">{{ __('emp.no_employee_profile_hint') }}</p>
</div>
@else

{{-- Stats --}}
<div class="stats-strip">
    @php
        $statItems = [
            'todo'        => ['label' => __('emp.todo'),        'color' => '#6b7280'],
            'in_progress' => ['label' => __('emp.in_progress'), 'color' => '#00c6ff'],
            'review'      => ['label' => __('emp.review'),      'color' => '#f7b731'],
            'done'        => ['label' => __('emp.done'),        'color' => '#43e97b'],
        ];
    @endphp
    @foreach($statItems as $key => $item)
    <div class="stat-card {{ request('status') === $key ? 'active' : '' }}"
         style="--stat-color: {{ $item['color'] }}"
         onclick="filterStatus('{{ $key }}')">
        <div class="stat-val">{{ $stats[$key] }}</div>
        <div class="stat-lbl">{{ $item['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" id="filter-form">
    <div class="filters-bar">
        <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="{{ __('emp.search_tasks') }}">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()" id="status-select">
            <option value="">{{ __('emp.all_statuses') }}</option>
            @foreach(['todo','in_progress','review','done','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ __('emp.'.$s) }}
            </option>
            @endforeach
        </select>
        <select name="priority" class="filter-select" onchange="this.form.submit()">
            <option value="">{{ __('emp.all_priorities') }}</option>
            @foreach(['urgent','high','medium','low'] as $p)
            <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                {{ __('emp.'.$p) }}
            </option>
            @endforeach
        </select>
        @if(request()->hasAny(['search','status','priority']))
        <a href="{{ route('employee.tasks.index') }}" class="btn btn-ghost">
            <i class="fas fa-times"></i> {{ __('emp.clear') }}
        </a>
        @endif
        <button type="submit" class="btn btn-ghost"><i class="fas fa-search"></i></button>
    </div>
</form>

{{-- Tasks Grid --}}
@if($tasks->count())
<div class="tasks-grid">
    @foreach($tasks as $task)
    @php
        $priorityColors = [
            'urgent' => '#ff4444',
            'high'   => '#ff6584',
            'medium' => '#f7b731',
            'low'    => '#43e97b',
        ];
        $isOverdue = $task->due_date && $task->due_date->isPast() && !in_array($task->status, ['done','cancelled']);
    @endphp
    <a href="{{ route('employee.tasks.show', $task) }}"
       class="task-card {{ $isOverdue ? 'overdue' : '' }}"
       style="--priority-color: {{ $priorityColors[$task->priority] ?? 'var(--accent)' }}">

        <div class="task-top">
            <div class="task-badges">
                <span class="badge badge-{{ $task->status }}">{{ __('emp.'.$task->status) }}</span>
                <span class="badge badge-{{ $task->priority }}">{{ __('emp.'.$task->priority) }}</span>
            </div>
            @if($task->service)
            <span class="service-tag">{{ $task->service->name }}</span>
            @endif
        </div>

        <div class="task-title">{{ $task->title }}</div>

        <div class="task-client">
            <i class="fas fa-building" style="font-size:10px"></i>
            {{ $task->client->name }}
        </div>

        @if($task->progress > 0 || $task->status === 'in_progress')
        <div class="progress-wrap">
            <div class="progress-header">
                <span>{{ __('emp.progress') }}</span>
                <span>{{ $task->progress }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill {{ $task->status === 'done' ? 'done' : '' }}"
                     style="width: {{ $task->progress }}%"></div>
            </div>
        </div>
        @endif

        <div class="task-footer">
            <div class="task-date {{ $isOverdue ? 'overdue' : '' }}">
                @if($task->due_date)
                <i class="fas fa-calendar{{ $isOverdue ? '-times' : '' }}" style="font-size:10px"></i>
                @if($isOverdue)
                    {{ __('emp.overdue') }}: {{ $task->due_date->format('d M') }}
                @else
                    {{ $task->due_date->format('d M Y') }}
                @endif
                @else
                <i class="fas fa-infinity" style="font-size:10px"></i>
                {{ __('emp.no_due_date') }}
                @endif
            </div>

            @if($task->employees->count() > 1)
            <div class="teammates">
                @foreach($task->employees->take(4) as $emp)
                <div class="teammate-av" title="{{ $emp->name }}">
                    {{ strtoupper(mb_substr($emp->name, 0, 1)) }}
                </div>
                @endforeach
                @if($task->employees->count() > 4)
                <div class="teammate-av" style="background:var(--surface2);color:var(--muted);font-size:8px">
                    +{{ $task->employees->count() - 4 }}
                </div>
                @endif
            </div>
            @endif
        </div>
    </a>
    @endforeach
</div>

@if($tasks->hasPages())
<div class="pagination-wrap">{{ $tasks->links() }}</div>
@endif

@else
<div class="empty-state">
    <div class="empty-icon"><i class="fas fa-clipboard-check"></i></div>
    <h3>{{ __('emp.no_tasks_yet') }}</h3>
    <p style="font-size:13px;margin-top:6px">{{ __('emp.no_tasks_hint') }}</p>
</div>
@endif

@endif
@endsection

@push('scripts')
<script>
function filterStatus(status) {
    const select = document.getElementById('status-select');
    if (select.value === status) {
        select.value = '';
    } else {
        select.value = status;
    }
    document.getElementById('filter-form').submit();
}
</script>
@endpush