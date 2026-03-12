@extends('layouts.admin')
@section('title', __('admin.nav_employees'))
@section('page-title', __('admin.nav_employees'))

@push('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 20px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;white-space:nowrap;border:none}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-sm{padding:6px 12px;font-size:12px;border-radius:8px}
.act-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;color:var(--muted);text-decoration:none;transition:all .15s;font-size:13px;background:none;border:none;cursor:pointer}
.act-btn:hover{background:var(--surface2);color:var(--text)}
.act-btn.del:hover{background:rgba(255,101,132,.1);color:var(--accent2)}

/* Stats */
.stats-strip{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px}
@media(max-width:900px){.stats-strip{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){.stats-strip{grid-template-columns:1fr 1fr}}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px}
.stat-val{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.stat-lbl{font-size:11px;color:var(--muted);margin-top:2px;text-transform:uppercase;letter-spacing:.5px}

/* Filters */
.filters{display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap}
.filters input,.filters select{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:9px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s}
.filters input:focus,.filters select:focus{border-color:var(--accent)}
.filters input{flex:1;min-width:180px}

/* Grid */
.employees-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
.emp-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;transition:border-color .2s;position:relative}
.emp-card:hover{border-color:rgba(108,99,255,.3)}

.emp-card-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px}
.emp-avatar{width:52px;height:52px;border-radius:12px;object-fit:cover;flex-shrink:0}
.emp-actions{display:flex;gap:4px}

.emp-name{font-family:'Syne',sans-serif;font-size:15px;font-weight:800;margin-bottom:2px}
.emp-title{font-size:12px;color:var(--muted)}

.emp-meta{display:flex;flex-wrap:wrap;gap:6px;margin:10px 0}
.emp-tag{font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;background:var(--surface2);color:var(--muted);border:1px solid var(--border)}
.emp-tag.sales{background:rgba(0,198,255,.1);color:#00c6ff;border-color:rgba(0,198,255,.2)}

.emp-footer{display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid var(--border);font-size:12px}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-active{background:rgba(67,233,123,.1);color:#43e97b}
.badge-inactive{background:rgba(107,114,128,.1);color:var(--muted)}
.badge-vacation{background:rgba(247,183,49,.1);color:#f7b731}

/* Dept dot */
.dept-dot{width:8px;height:8px;border-radius:50%;display:inline-block;flex-shrink:0}

.empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
.empty-state i{font-size:40px;margin-bottom:14px;opacity:.3;display:block}
.pagination-wrap{margin-top:20px;display:flex;justify-content:flex-end}
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>{{ __('admin.nav_employees') }}</h1>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('admin.add_employee') }}
    </a>
</div>

{{-- Stats --}}
<div class="stats-strip">
    <div class="stat-card">
        <div class="stat-val">{{ $stats['total'] }}</div>
        <div class="stat-lbl">{{ __('admin.total') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#43e97b">{{ $stats['active'] }}</div>
        <div class="stat-lbl">{{ __('admin.active') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#f7b731">{{ $stats['vacation'] }}</div>
        <div class="stat-lbl">{{ __('admin.vacation') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#00c6ff">{{ $stats['sales'] }}</div>
        <div class="stat-lbl">{{ __('admin.sales_team') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:var(--accent);font-size:16px">
            {{ number_format($stats['salary'],0) }} {{ __('admin.jd') }}
        </div>
        <div class="stat-lbl">{{ __('admin.monthly_salaries') }}</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.employees.index') }}">
    <div class="filters">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('admin.search_employees') }}">
        <select name="department" onchange="this.form.submit()">
            <option value="">— {{ __('admin.all_departments') }} —</option>
            @foreach(['design','video','development','social_media','marketing','sales','accounting','management'] as $d)
            <option value="{{ $d }}" {{ request('department')===$d?'selected':'' }}>{{ __('admin.dept_'.$d) }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()">
            <option value="">— {{ __('admin.all_statuses') }} —</option>
            @foreach(['active','inactive','vacation'] as $s)
            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ __('admin.'.$s) }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['search','department','status','is_sales']))
        <a href="{{ route('admin.employees.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-times"></i> {{ __('admin.clear') }}
        </a>
        @endif
        <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-search"></i></button>
    </div>
</form>

@if($employees->count())
<div class="employees-grid">
    @foreach($employees as $emp)
    @php
        $deptColors = [
            'design'=>'#a78bfa','video'=>'#f472b6','development'=>'#34d399',
            'social_media'=>'#38bdf8','marketing'=>'#fb923c','sales'=>'#00c6ff',
            'accounting'=>'#fbbf24','management'=>'#6c63ff'
        ];
        $color = $deptColors[$emp->department] ?? '#6c63ff';
    @endphp
    <div class="emp-card">
        <div class="emp-card-top">
            <div style="display:flex;gap:12px;align-items:flex-start">
                <img src="{{ $emp->avatar_url }}" alt="{{ $emp->name }}" class="emp-avatar">
                <div>
                    <div class="emp-name">{{ $emp->name }}</div>
                    <div class="emp-title">{{ $emp->job_title }}</div>
                    <div style="margin-top:5px;display:flex;gap:5px;align-items:center">
                        <span class="dept-dot" style="background:{{ $color }}"></span>
                        <span style="font-size:11px;color:var(--muted)">{{ __('admin.dept_'.$emp->department) }}</span>
                    </div>
                </div>
            </div>
            <div class="emp-actions">
                <a href="{{ route('admin.employees.show', $emp) }}" class="act-btn"><i class="fas fa-eye"></i></a>
                <a href="{{ route('admin.employees.edit', $emp) }}" class="act-btn"><i class="fas fa-pen"></i></a>
                <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" id="del-e{{ $emp->id }}">
                    @csrf @method('DELETE')
                    <button type="button" class="act-btn del"
                            onclick="confirmDelete('del-e{{ $emp->id }}','{{ __('admin.confirm_delete') }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Tags --}}
        <div class="emp-meta">
            @if($emp->is_sales)
            <span class="emp-tag sales">💰 {{ __('admin.sales') }} {{ $emp->commission_rate }}%</span>
            @endif
            @foreach(array_slice($emp->specializations ?? [], 0, 3) as $skill)
            <span class="emp-tag">{{ $skill }}</span>
            @endforeach
            @if(count($emp->specializations ?? []) > 3)
            <span class="emp-tag">+{{ count($emp->specializations)-3 }}</span>
            @endif
        </div>

        <div class="emp-footer">
            <span class="badge badge-{{ $emp->status }}">{{ __('admin.'.$emp->status) }}</span>
            <div style="text-align:end">
                <div style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">
                    {{ number_format($emp->salary,0) }} {{ __('admin.jd') }}
                </div>
                @if($emp->user)
                <div style="font-size:10px;color:#43e97b;margin-top:2px">
                    <i class="fas fa-circle" style="font-size:7px"></i> {{ __('admin.has_account') }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($employees->hasPages())
<div class="pagination-wrap">{{ $employees->links() }}</div>
@endif
@else
<div class="empty-state">
    <i class="fas fa-users"></i>
    <p>{{ __('admin.no_employees_yet') }}</p>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary" style="display:inline-flex;margin-top:16px">
        <i class="fas fa-plus"></i> {{ __('admin.add_employee') }}
    </a>
</div>
@endif

@endsection