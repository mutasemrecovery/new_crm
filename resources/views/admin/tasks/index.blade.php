@extends('layouts.admin')
@section('title', __('admin.nav_tasks'))
@push('styles')
<style>
.ph{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 20px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;white-space:nowrap;border:none}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;box-shadow:0 4px 14px rgba(108,99,255,.3)}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-secondary{background:var(--surface2);color:var(--text);border:1px solid var(--border)!important}
.btn-secondary:hover,.btn-secondary.active{border-color:var(--accent)!important;color:var(--accent)}
.sr{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
@media(max-width:700px){.sr{grid-template-columns:1fr 1fr}}
.sm{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px;cursor:pointer;transition:border-color .2s}
.sm:hover{border-color:rgba(255,255,255,.18)}
.sv{font-family:'Syne',sans-serif;font-size:26px;font-weight:800}.sl{font-size:12px;color:var(--muted);margin-top:2px}
.fb{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;align-items:center}
.fs{background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:9px 14px;color:var(--text);font-size:13px;font-family:inherit;outline:none;cursor:pointer}
.fs:focus{border-color:var(--accent)}
.sw{flex:1;min-width:200px;display:flex;align-items:center;background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:0 14px;gap:8px}
.sw input{flex:1;background:none;border:none;outline:none;color:var(--text);font-size:13px;padding:9px 0;font-family:inherit}
.sw i{color:var(--muted);font-size:13px}
/* Kanban */
.kb{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:1100px){.kb{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.kb{grid-template-columns:1fr}}
.kc{background:var(--surface2);border-radius:14px;padding:14px;min-height:280px}
.kch{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--border)}
.kct{font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px}
.kd{width:8px;height:8px;border-radius:50%}
.kn{font-size:11px;background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:2px 8px;color:var(--muted)}
.tc{background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:12px 13px;margin-bottom:9px;transition:transform .15s,border-color .15s;display:block;text-decoration:none;color:var(--text)}
.tc:hover{transform:translateY(-2px);border-color:rgba(255,255,255,.14);color:var(--text)}
.tt{font-size:13px;font-weight:600;margin-bottom:8px;line-height:1.35}
.tm{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
.td{font-size:11px;color:var(--muted);display:flex;align-items:center;gap:3px}
.td.ov{color:var(--accent2)}
.avs{display:flex}.av{width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#fff;border:1.5px solid var(--surface);margin-inline-start:-6px}
.av:first-child{margin-inline-start:0}
.pm{height:3px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-top:9px}
.pf{height:100%;background:linear-gradient(90deg,var(--accent),#8b7eff);border-radius:99px}
.badge{padding:3px 9px;border-radius:20px;font-size:10px;font-weight:700;white-space:nowrap}
.badge-todo{background:rgba(107,114,128,.12);color:var(--muted)}
.badge-in_progress{background:rgba(0,198,255,.12);color:#00c6ff}
.badge-review{background:rgba(247,183,49,.12);color:#f7b731}
.badge-done{background:rgba(67,233,123,.12);color:#43e97b}
.badge-cancelled{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-urgent{background:rgba(255,50,50,.15);color:#ff3232}
.badge-high{background:rgba(255,101,132,.12);color:var(--accent2)}
.badge-medium{background:rgba(247,183,49,.12);color:#f7b731}
.badge-low{background:rgba(67,233,123,.12);color:#43e97b}
/* List view */
.tc2{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.at{width:100%;border-collapse:collapse;font-size:13px}
.at th{text-align:start;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:600;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--surface2);white-space:nowrap}
.at td{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.at tr:last-child td{border-bottom:none}
.at tr:hover td{background:rgba(255,255,255,.02)}
.ab{display:inline-flex;align-items:center;justify-content:center;width:29px;height:29px;border-radius:7px;color:var(--muted);text-decoration:none;transition:all .15s;font-size:12px;background:none;border:none;cursor:pointer}
.ab:hover{background:var(--surface2);color:var(--text)}
.ab.d:hover{background:rgba(255,101,132,.1);color:var(--accent2)}
.empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
.empty-state i{font-size:40px;margin-bottom:14px;opacity:.25;display:block}
</style>
@endpush
@section('content')
<div class="ph">
    <div><h1>{{ __('admin.nav_tasks') }}</h1><p style="font-size:13px;color:var(--muted);margin-top:4px">{{ __('admin.tasks_subtitle') }}</p></div>
    <div style="display:flex;gap:8px;align-items:center">
        <div style="display:flex;gap:6px">
            <button class="btn btn-secondary active" id="btnK" onclick="setView('kanban')"><i class="fas fa-columns"></i></button>
            <button class="btn btn-secondary" id="btnL" onclick="setView('list')"><i class="fas fa-list"></i></button>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('admin.add_task') }}</a>
    </div>
</div>
<div class="sr">
    <div class="sm" onclick="qs('todo')"><div class="sv" style="color:var(--muted)">{{ $stats['todo'] }}</div><div class="sl">{{ __('admin.todo') }}</div></div>
    <div class="sm" onclick="qs('in_progress')"><div class="sv" style="color:#00c6ff">{{ $stats['in_progress'] }}</div><div class="sl">{{ __('admin.in_progress') }}</div></div>
    <div class="sm" onclick="qs('review')"><div class="sv" style="color:#f7b731">{{ $stats['review'] }}</div><div class="sl">{{ __('admin.review') }}</div></div>
    <div class="sm" onclick="qs('done')"><div class="sv" style="color:#43e97b">{{ $stats['done'] }}</div><div class="sl">{{ __('admin.done') }}</div></div>
</div>
<form method="GET" id="ff">
    <div class="fb">
        <div class="sw"><i class="fas fa-search"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}..."></div>
        <select name="status" class="fs" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_statuses') }}</option>
            @foreach(['todo','in_progress','review','done','cancelled'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ __('admin.'.$s) }}</option>@endforeach
        </select>
        <select name="priority" class="fs" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_priorities') }}</option>
            @foreach(['urgent','high','medium','low'] as $p)<option value="{{ $p }}" {{ request('priority')===$p?'selected':'' }}>{{ __('admin.'.$p) }}</option>@endforeach
        </select>
        <select name="client_id" class="fs" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_clients') }}</option>
            @foreach($clients as $c)<option value="{{ $c->id }}" {{ request('client_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
        </select>
        <button type="submit" class="btn btn-primary" style="padding:9px 16px"><i class="fas fa-search"></i></button>
        @if(request()->hasAny(['search','status','priority','client_id']))
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary" style="padding:9px 14px"><i class="fas fa-times"></i></a>
        @endif
        <input type="hidden" name="view" id="vi" value="{{ request('view','kanban') }}">
    </div>
</form>

@php
$grouped = $tasks->getCollection()->groupBy('status');
$cols = ['todo'=>['label'=>__('admin.todo'),'color'=>'#6b7280'],'in_progress'=>['label'=>__('admin.in_progress'),'color'=>'#00c6ff'],'review'=>['label'=>__('admin.review'),'color'=>'#f7b731'],'done'=>['label'=>__('admin.done'),'color'=>'#43e97b']];
@endphp

<div id="vK">
<div class="kb">
    @foreach($cols as $key => $col)
    <div class="kc">
        <div class="kch">
            <div class="kct"><div class="kd" style="background:{{ $col['color'] }}"></div>{{ $col['label'] }}</div>
            <span class="kn">{{ $grouped[$key]?->count() ?? 0 }}</span>
        </div>
        @forelse($grouped[$key] ?? [] as $task)
        <a href="{{ route('admin.tasks.show',$task) }}" class="tc">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px">
                <span class="badge badge-{{ $task->priority }}">{{ __('admin.'.$task->priority) }}</span>
                @if($task->service)<span style="font-size:10px;color:var(--muted)">{{ $task->service->name }}</span>@endif
            </div>
            <div class="tt">{{ $task->title }}</div>
            <div class="tm">
                <span class="td"><i class="fas fa-building" style="font-size:9px"></i> {{ Str::limit($task->client->name,20) }}</span>
                @if($task->due_date)
                    <span class="td {{ $task->isOverdue()?'ov':'' }}"><i class="fas fa-calendar" style="font-size:9px"></i> {{ $task->due_date->format('M d') }}</span>
                @endif
                @if($task->employees->count())
                    <div class="avs" style="margin-inline-start:auto">
                        @foreach($task->employees->take(3) as $e)<div class="av" title="{{ $e->name }}">{{ strtoupper(mb_substr($e->name,0,1)) }}</div>@endforeach
                    </div>
                @endif
            </div>
            @if($task->progress > 0)<div class="pm"><div class="pf" style="width:{{ $task->progress }}%"></div></div>@endif
        </a>
        @empty
            <div style="text-align:center;padding:24px;color:var(--muted);font-size:12px">{{ __('admin.no_tasks') }}</div>
        @endforelse
    </div>
    @endforeach
</div>
</div>

<div id="vL" style="display:none">
<div class="tc2">
    @if($tasks->count())
    <div style="overflow-x:auto">
        <table class="at">
            <thead><tr>
                <th>{{ __('admin.task') }}</th><th>{{ __('admin.client') }}</th>
                <th>{{ __('admin.status') }}</th><th>{{ __('admin.priority') }}</th>
                <th>{{ __('admin.progress') }}</th><th>{{ __('admin.due_date') }}</th><th>{{ __('admin.assigned_to') }}</th><th></th>
            </tr></thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td style="font-weight:600;max-width:200px">{{ Str::limit($task->title,45) }}</td>
                    <td style="color:var(--muted)">{{ $task->client->name }}</td>
                    <td><span class="badge badge-{{ $task->status }}">{{ __('admin.'.$task->status) }}</span></td>
                    <td><span class="badge badge-{{ $task->priority }}">{{ __('admin.'.$task->priority) }}</span></td>
                    <td style="min-width:90px">
                        <div style="display:flex;align-items:center;gap:7px">
                            <div style="flex:1;height:4px;background:var(--surface2);border-radius:99px;overflow:hidden"><div style="height:100%;width:{{ $task->progress }}%;background:var(--accent);border-radius:99px"></div></div>
                            <span style="font-size:11px;color:var(--muted)">{{ $task->progress }}%</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:{{ $task->isOverdue()?'var(--accent2)':'var(--muted)' }}">{{ $task->due_date?->format('Y-m-d') ?? '—' }}</td>
                    <td>
                        <div class="avs">
                            @foreach($task->employees->take(3) as $e)<div class="av" title="{{ $e->name }}" style="width:26px;height:26px;font-size:10px">{{ strtoupper(mb_substr($e->name,0,1)) }}</div>@endforeach
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:3px">
                            <a href="{{ route('admin.tasks.show',$task) }}" class="ab"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.tasks.edit',$task) }}" class="ab"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.tasks.destroy',$task) }}" method="POST" id="dt-{{ $task->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="ab d" onclick="confirmDelete('dt-{{ $task->id }}','{{ addslashes(__('admin.confirm_delete')) }}')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state"><i class="fas fa-tasks"></i><p>{{ __('admin.no_tasks_yet') }}</p></div>
    @endif
</div>
</div>
@endsection
@push('scripts')
<script>
function setView(v){
    document.getElementById('vK').style.display=v==='kanban'?'':'none';
    document.getElementById('vL').style.display=v==='list'?'':'none';
    document.getElementById('btnK').classList.toggle('active',v==='kanban');
    document.getElementById('btnL').classList.toggle('active',v==='list');
    document.getElementById('vi').value=v;
    localStorage.setItem('taskView',v);
}
function qs(s){document.querySelector('select[name=status]').value=s;document.getElementById('ff').submit();}
setView(localStorage.getItem('taskView')||'{{ request("view","kanban") }}');
</script>
@endpush