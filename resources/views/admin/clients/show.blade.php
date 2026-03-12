@extends('layouts.admin')
@section('title', $client->name)

@push('styles')
<style>
:root{--g:linear-gradient(135deg,var(--accent),#8b7eff)}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;margin-bottom:6px}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none;white-space:nowrap}
.btn-primary{background:var(--g);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}.btn-primary:hover{opacity:.88;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.kpi-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
@media(max-width:800px){.kpi-strip{grid-template-columns:1fr 1fr}}
.kpi{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px 20px}
.kpi-val{font-family:'Syne',sans-serif;font-size:26px;font-weight:800}
.kpi-lbl{font-size:11px;color:var(--muted);margin-top:3px;text-transform:uppercase;letter-spacing:.5px}
.detail-layout{display:grid;grid-template-columns:340px 1fr;gap:20px}
@media(max-width:1000px){.detail-layout{grid-template-columns:1fr}}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;margin-bottom:18px}
.card:last-child{margin-bottom:0}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.card-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;display:flex;align-items:center;gap:8px}
.card-title i{color:var(--accent);font-size:13px}
.dl-row{display:flex;justify-content:space-between;align-items:flex-start;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04);gap:12px;font-size:13px}
.dl-row:last-child{border-bottom:none}
.dl-label{color:var(--muted);font-weight:500;flex-shrink:0;min-width:110px}
.dl-val{font-weight:600;text-align:end;word-break:break-word}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-active,.badge-paid,.badge-done,.badge-completed,.badge-low{background:rgba(67,233,123,.1);color:#43e97b}
.badge-pending,.badge-in_progress,.badge-sent{background:rgba(0,198,255,.1);color:#00c6ff}
.badge-paused,.badge-medium,.badge-review{background:rgba(247,183,49,.1);color:#f7b731}
.badge-closed,.badge-cancelled,.badge-high,.badge-urgent,.badge-overdue{background:rgba(255,101,132,.1);color:var(--accent2)}
.badge-todo,.badge-draft{background:rgba(107,114,128,.1);color:var(--muted)}
.svc-item{display:flex;align-items:center;justify-content:space-between;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:11px 14px;margin-bottom:8px}
.svc-item:last-child{margin-bottom:0}
.svc-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.svc-name{font-size:13px;font-weight:600}
.svc-sub{font-size:11px;color:var(--muted);margin-top:2px}
.task-row,.inv-row{display:flex;align-items:center;gap:8px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px}
.task-row:last-child,.inv-row:last-child{border-bottom:none}
.prog-bar{width:50px;height:4px;background:var(--surface2);border-radius:99px;overflow:hidden;flex-shrink:0}
.prog-fill{height:100%;background:var(--g);border-radius:99px}
.link-sm{font-size:12px;color:var(--accent);font-weight:600;text-decoration:none}
.link-sm:hover{text-decoration:underline}
.empty-note{color:var(--muted);font-size:13px;padding:8px 0;margin:0}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $client->name }}</h1>
        <div style="display:flex;gap:6px;flex-wrap:wrap">
            <span class="badge badge-{{ $client->status }}">{{ __('admin.'.$client->status) }}</span>
            <span class="badge badge-{{ $client->priority }}">{{ __('admin.'.$client->priority) }}</span>
            @if($client->industry)<span style="font-size:12px;color:var(--muted)">{{ $client->industry }}</span>@endif
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-primary"><i class="fas fa-pen"></i> {{ __('admin.edit') }}</a>
        <a href="{{ route('admin.tasks.create') }}?client_id={{ $client->id }}" class="btn btn-ghost"><i class="fas fa-plus"></i> {{ __('admin.add_task') }}</a>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i></a>
    </div>
</div>

<div class="kpi-strip">
    <div class="kpi"><div class="kpi-val" style="color:var(--accent)">JD {{ number_format($client->monthly_value,0) }}</div><div class="kpi-lbl">{{ __('admin.monthly_value') }}</div></div>
    <div class="kpi"><div class="kpi-val">{{ $client->tasks->count() }}</div><div class="kpi-lbl">{{ __('admin.total_tasks') }}</div></div>
    <div class="kpi"><div class="kpi-val">{{ $client->clientServices->count() }}</div><div class="kpi-lbl">{{ __('admin.active_services') }}</div></div>
</div>

<div class="detail-layout">
    {{-- LEFT --}}
    <div>
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-address-card"></i> {{ __('admin.contact_info') }}</div></div>
            @foreach([
                [__('admin.contact_person'), $client->contact_person],
                [__('admin.phone'), $client->phone],
                ['WhatsApp', $client->whatsapp],
                [__('admin.email'), $client->email],
                [__('admin.address'), $client->address],
                [__('admin.industry'), $client->industry],
            ] as [$lbl,$val])
            @if($val)
            <div class="dl-row">
                <span class="dl-label">{{ $lbl }}</span>
                <span class="dl-val" style="font-weight:{{ in_array($lbl,[__('admin.address'),__('admin.notes')])?'400':'600' }};color:{{ in_array($lbl,[__('admin.address')])?'var(--muted)':'var(--text)' }}">{{ $val }}</span>
            </div>
            @endif
            @endforeach
        </div>

        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-file-contract"></i> {{ __('admin.contract_details') }}</div></div>
            <div class="dl-row"><span class="dl-label">{{ __('admin.contract_start') }}</span><span class="dl-val">{{ $client->contract_start?->format('d M Y') ?? '—' }}</span></div>
            <div class="dl-row">
                <span class="dl-label">{{ __('admin.contract_end') }}</span>
                <span class="dl-val">
                    @if($client->contract_end)
                        @php $diff = now()->diffInDays($client->contract_end, false) @endphp
                        <span style="color:{{ $diff<0?'var(--accent2)':($diff<=30?'#f7b731':'var(--text)') }}">
                            {{ $client->contract_end->format('d M Y') }}
                            @if($diff<0) ({{ __('admin.expired') }}) @elseif($diff<=30) ({{ $diff }}d) @endif
                        </span>
                    @else —
                    @endif
                </span>
            </div>
            <div class="dl-row"><span class="dl-label">{{ __('admin.assigned_sales') }}</span><span class="dl-val">{{ $client->assignedSales?->name ?? '—' }}</span></div>
        </div>

        @if($client->notes)
        <div class="card">
            <div class="card-head"><div class="card-title"><i class="fas fa-sticky-note"></i> {{ __('admin.notes') }}</div></div>
            <p style="font-size:13px;color:var(--muted);line-height:1.7;margin:0">{{ $client->notes }}</p>
        </div>
        @endif
    </div>

    {{-- RIGHT --}}
    <div>
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="fas fa-layer-group"></i> {{ __('admin.services') }}</div>
                <span style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">JD {{ number_format($client->clientServices->sum('price'),0) }}/{{ __('admin.month') }}</span>
            </div>
            @forelse($client->clientServices as $cs)
            <div class="svc-item">
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="svc-dot" style="background:{{ $cs->service->color??'#6366f1' }}"></div>
                    <div>
                        <div class="svc-name">{{ app()->getLocale()==='ar'?$cs->service->name:($cs->service->name_en??$cs->service->name) }}</div>
                        @if($cs->details)<div class="svc-sub">{{ $cs->details }}</div>@endif
                        @if($cs->start_date)<div class="svc-sub">{{ __('admin.from') }}: {{ $cs->start_date->format('d M Y') }}</div>@endif
                    </div>
                </div>
                <div style="text-align:end">
                    <div style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent)">JD {{ number_format($cs->price,0) }}</div>
                    <span class="badge badge-{{ $cs->status }}" style="font-size:10px;margin-top:3px">{{ __('admin.'.$cs->status) }}</span>
                </div>
            </div>
            @empty<p class="empty-note">{{ __('admin.no_services') }}</p>
            @endforelse
        </div>

        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="fas fa-tasks"></i> {{ __('admin.nav_tasks') }} ({{ $client->tasks->count() }})</div>
                <a href="{{ route('admin.tasks.create') }}?client_id={{ $client->id }}" class="link-sm">+ {{ __('admin.add_task') }}</a>
            </div>
            @forelse($client->tasks->take(6) as $task)
            <div class="task-row">
                <span class="badge badge-{{ $task->status }}" style="font-size:10px;flex-shrink:0">{{ __('admin.'.$task->status) }}</span>
                <span class="badge badge-{{ $task->priority }}" style="font-size:10px;flex-shrink:0">{{ __('admin.'.$task->priority) }}</span>
                <a href="{{ route('admin.tasks.show', $task) }}" style="flex:1;text-decoration:none;color:var(--text);font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $task->title }}</a>
                <div class="prog-bar"><div class="prog-fill" style="width:{{ $task->progress }}%"></div></div>
                <span style="font-size:11px;color:var(--muted)">{{ $task->progress }}%</span>
            </div>
            @empty<p class="empty-note">{{ __('admin.no_tasks') }}</p>
            @endforelse
            @if($client->tasks->count() > 6)
                <div style="text-align:center;margin-top:12px"><a href="{{ route('admin.tasks.index') }}?client_id={{ $client->id }}" class="link-sm">{{ __('admin.view_all') }} ({{ $client->tasks->count() }})</a></div>
            @endif
        </div>

        <div class="card">
          
            
        </div>
    </div>
</div>
@endsection