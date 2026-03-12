@extends('layouts.admin')
@section('title', $employee->name)

@push('styles')
<style>
:root{--g:linear-gradient(135deg,var(--accent),#8b7eff)}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800;margin-bottom:4px}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none;white-space:nowrap}
.btn-primary{background:var(--g);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}.btn-primary:hover{opacity:.88;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}

.layout{display:grid;grid-template-columns:300px 1fr;gap:20px}
@media(max-width:900px){.layout{grid-template-columns:1fr}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;margin-bottom:18px}
.card:last-child{margin-bottom:0}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.card-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;display:flex;align-items:center;gap:8px}
.card-title i{color:var(--accent);font-size:13px}
.dl-row{display:flex;justify-content:space-between;align-items:flex-start;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04);gap:12px;font-size:13px}
.dl-row:last-child{border-bottom:none}
.dl-label{color:var(--muted);font-weight:500;flex-shrink:0;min-width:110px}
.dl-val{font-weight:600;text-align:end}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-active{background:rgba(67,233,123,.1);color:#43e97b}
.badge-inactive{background:rgba(107,114,128,.1);color:var(--muted)}
.badge-vacation{background:rgba(247,183,49,.1);color:#f7b731}
.badge-paid{background:rgba(67,233,123,.1);color:#43e97b}
.badge-pending{background:rgba(107,114,128,.1);color:var(--muted)}

/* Profile card */
.profile-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;text-align:center;margin-bottom:18px}
.profile-avatar{width:96px;height:96px;border-radius:18px;object-fit:cover;margin:0 auto 14px;display:block;border:3px solid var(--border)}
.profile-name{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;margin-bottom:4px}
.profile-title{font-size:13px;color:var(--muted);margin-bottom:10px}
.profile-dept{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;background:var(--surface2);border:1px solid var(--border)}

/* Skills */
.skills-wrap{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.skill-tag{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(108,99,255,.1);color:var(--accent);border:1px solid rgba(108,99,255,.2)}

/* Commission row */
.comm-row{display:flex;align-items:center;gap:8px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px}
.comm-row:last-child{border-bottom:none}
.empty-note{color:var(--muted);font-size:13px;padding:8px 0;margin:0}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $employee->name }}</h1>
        <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
            <span class="badge badge-{{ $employee->status }}">{{ __('admin.'.$employee->status) }}</span>
            @if($employee->is_sales)
            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(0,198,255,.1);color:#00c6ff">
                💰 {{ __('admin.sales') }}
            </span>
            @endif
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> {{ __('admin.edit') }}
        </a>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i>
        </a>
    </div>
</div>

<div class="layout">
    {{-- LEFT --}}
    <div>
        {{-- Profile --}}
        <div class="profile-card">
            <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="profile-avatar">
            <div class="profile-name">{{ $employee->name }}</div>
            @if($employee->name_en)
            <div style="font-size:12px;color:var(--muted);margin-bottom:6px">{{ $employee->name_en }}</div>
            @endif
            <div class="profile-title">{{ $employee->job_title }}</div>
            @php
                $deptColors=['design'=>'#a78bfa','video'=>'#f472b6','development'=>'#34d399',
                    'social_media'=>'#38bdf8','marketing'=>'#fb923c','sales'=>'#00c6ff',
                    'accounting'=>'#fbbf24','management'=>'#6c63ff'];
                $dc=$deptColors[$employee->department]??'#6c63ff';
            @endphp
            <div class="profile-dept" style="border-color:{{ $dc }}22;background:{{ $dc }}11;color:{{ $dc }}">
                <span style="width:7px;height:7px;border-radius:50%;background:{{ $dc }};display:inline-block"></span>
                {{ __('admin.dept_'.$employee->department) }}
            </div>

            @if($employee->hire_date)
            <div style="font-size:11px;color:var(--muted);margin-top:12px">
                {{ __('admin.hire_date') }}: {{ $employee->hire_date->format('d M Y') }}
                <span style="color:var(--accent);font-weight:700">({{ $employee->hire_date->diffForHumans() }})</span>
            </div>
            @endif
        </div>

        {{-- Contact --}}
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-phone"></i> {{ __('admin.contact_info') }}</div></div>
            @if($employee->phone)
            <div class="dl-row"><span class="dl-label">{{ __('admin.phone') }}</span><span class="dl-val" dir="ltr">{{ $employee->phone }}</span></div>
            @endif
            @if($employee->email)
            <div class="dl-row"><span class="dl-label">{{ __('admin.email') }}</span><span class="dl-val" dir="ltr">{{ $employee->email }}</span></div>
            @endif
            @if(!$employee->phone && !$employee->email)
            <p class="empty-note">{{ __('admin.no_contact_info') }}</p>
            @endif
        </div>

        {{-- App account --}}
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-mobile-alt"></i> {{ __('admin.app_account') }}</div></div>
            @if($employee->user)
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.phone') }}</span>
                <span class="dl-val" dir="ltr">{{ $employee->user->phone }}</span>
            </div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.status') }}</span>
                <span class="dl-val">
                    <span style="color:{{ $employee->user->activate==1?'#43e97b':'var(--accent2)' }};font-weight:700">
                        {{ $employee->user->activate==1 ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </span>
            </div>
            @else
            <p class="empty-note">{{ __('admin.no_account') }}</p>
            @endif
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Salary & Commission --}}
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-money-bill-wave"></i> {{ __('admin.salary_commission') }}</div></div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.salary') }}</span>
                <span class="dl-val" style="font-family:'Syne',sans-serif;font-size:18px;color:var(--accent)">
                    {{ number_format($employee->salary, 3) }} {{ __('admin.jd') }}
                </span>
            </div>
            @if($employee->is_sales)
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.commission_rate') }}</span>
                <span class="dl-val">{{ $employee->commission_rate }}%</span>
            </div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.commission_type') }}</span>
                <span class="dl-val">{{ __('admin.'.$employee->commission_type) }}</span>
            </div>
            @php
                $totalComm  = $employee->commissions->sum('amount');
                $paidComm   = $employee->commissions->where('status','paid')->sum('amount');
                $pendingComm= $employee->commissions->where('status','pending')->sum('amount');
            @endphp
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.total_commissions') }}</span>
                <span class="dl-val">{{ number_format($totalComm,3) }} {{ __('admin.jd') }}</span>
            </div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.pending_commissions') }}</span>
                <span class="dl-val" style="color:#f7b731">{{ number_format($pendingComm,3) }} {{ __('admin.jd') }}</span>
            </div>
            @endif
        </div>

        {{-- Specializations --}}
        @if($employee->specializations)
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-star"></i> {{ __('admin.specializations') }}</div></div>
            <div class="skills-wrap">
                @foreach($employee->specializations as $skill)
                <span class="skill-tag">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Commissions history --}}
        @if($employee->is_sales)
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="fas fa-chart-line"></i> {{ __('admin.commissions_history') }}</div>
            </div>
            @forelse($employee->commissions->sortByDesc('created_at')->take(8) as $comm)
            <div class="comm-row">
                <span class="badge badge-{{ $comm->status }}" style="font-size:10px;flex-shrink:0">{{ __('admin.'.$comm->status) }}</span>
                <span style="flex:1;color:var(--muted)">
                    {{ $comm->contract?->client?->name ?? '—' }}
                </span>
                <span style="font-size:11px;color:var(--muted)">{{ $comm->rate }}%</span>
                <span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">
                    {{ number_format($comm->amount,3) }} {{ __('admin.jd') }}
                </span>
            </div>
            @empty
            <p class="empty-note">{{ __('admin.no_commissions') }}</p>
            @endforelse
        </div>
        @endif

        {{-- Notes --}}
        @if($employee->notes)
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-sticky-note"></i> {{ __('admin.notes') }}</div></div>
            <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">{{ $employee->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection