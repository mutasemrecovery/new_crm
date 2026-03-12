@extends('layouts.admin')
@section('title', __('admin.edit_task'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:opacity .2s;box-shadow:0 4px 14px rgba(108,99,255,.3)}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;background:var(--surface2);color:var(--text);border:1px solid var(--border);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;font-family:inherit;cursor:pointer}
.btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
.btn-danger{display:inline-flex;align-items:center;gap:8px;background:rgba(255,101,132,.1);color:var(--accent2);border:1.5px solid rgba(255,101,132,.2);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all .2s}
.btn-danger:hover{background:rgba(255,101,132,.2)}
.fc{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:26px;margin-bottom:20px}
.fct{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:9px}
.fct i{color:var(--accent)}
.fg{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:700px){.fg{grid-template-columns:1fr}}
.fg2{grid-column:1/-1}
.fl{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;display:block;margin-bottom:6px}
.fin{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s,box-shadow .2s;width:100%}
.fin:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.fin::placeholder{color:var(--muted)}
.fe{font-size:11px;color:var(--accent2);margin-top:3px}
.eg{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:8px}
.ec{display:flex;align-items:center;gap:8px;background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:9px 12px;cursor:pointer;transition:all .2s;user-select:none}
.ec.sel{border-color:var(--accent);background:rgba(108,99,255,.08)}
.ec input{accent-color:var(--accent);width:15px;height:15px;cursor:pointer;flex-shrink:0}
.ea{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden}
.ea img{width:100%;height:100%;object-fit:cover;border-radius:50%}

/* Status badge hint */
.status-hint{display:inline-flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:20px;font-weight:700;margin-inline-start:8px}
.hint-todo{background:rgba(107,114,128,.12);color:var(--muted)}
.hint-in_progress{background:rgba(0,198,255,.12);color:#00c6ff}
.hint-review{background:rgba(247,183,49,.12);color:#f7b731}
.hint-done{background:rgba(67,233,123,.12);color:#43e97b}
.hint-cancelled{background:rgba(255,101,132,.12);color:var(--accent2)}
</style>
@endpush

@section('content')
<div class="ph">
    <div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:4px;display:flex;align-items:center;gap:6px">
            <a href="{{ route('admin.tasks.index') }}" style="color:var(--muted);text-decoration:none">{{ __('admin.nav_tasks') }}</a>
            <i class="fas fa-chevron-{{ app()->getLocale()==='ar'?'left':'right' }}" style="font-size:9px"></i>
            <a href="{{ route('admin.tasks.show', $task) }}" style="color:var(--muted);text-decoration:none">{{ Str::limit($task->title, 30) }}</a>
            <i class="fas fa-chevron-{{ app()->getLocale()==='ar'?'left':'right' }}" style="font-size:9px"></i>
            <span>{{ __('admin.edit') }}</span>
        </div>
        <h1>
            {{ __('admin.edit_task') }}
            <span class="status-hint hint-{{ $task->status }}">{{ __('admin.'.$task->status) }}</span>
        </h1>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('admin.tasks.show', $task) }}" class="btn-secondary">
            <i class="fas fa-eye"></i> {{ __('admin.view') }}
        </a>
        <a href="{{ route('admin.tasks.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.tasks.update', $task) }}">
    @csrf @method('PUT')

    {{-- ── Basic Info ── --}}
    <div class="fc">
        <div class="fct"><i class="fas fa-tasks"></i> {{ __('admin.basic_info') }}</div>
        <div class="fg">

            {{-- Title --}}
            <div class="fg2">
                <label class="fl">{{ __('admin.task_title') }} *</label>
                <input type="text" name="title" class="fin @error('title') is-invalid @enderror"
                       value="{{ old('title', $task->title) }}" required autofocus>
                @error('title')<div class="fe">{{ $message }}</div>@enderror
            </div>

            {{-- Client --}}
            <div>
                <label class="fl">{{ __('admin.client') }} *</label>
                <select name="client_id" class="fin" required>
                    <option value="">— {{ __('admin.none') }} —</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ old('client_id', $task->client_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>
                @error('client_id')<div class="fe">{{ $message }}</div>@enderror
            </div>

            {{-- Service --}}
            <div>
                <label class="fl">{{ __('admin.service') }}</label>
                <select name="service_id" class="fin">
                    <option value="">— {{ __('admin.none') }} —</option>
                    @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ old('service_id', $task->service_id) == $s->id ? 'selected' : '' }}>
                        {{ app()->getLocale() === 'ar' ? $s->name : ($s->name_en ?? $s->name) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="fl">{{ __('admin.task_status') }} *</label>
                <select name="status" class="fin" required id="status-sel">
                    @foreach(['todo','in_progress','review','done','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $task->status) === $s ? 'selected' : '' }}>
                        {{ __('admin.'.$s) }}
                    </option>
                    @endforeach
                </select>
                @error('status')<div class="fe">{{ $message }}</div>@enderror
            </div>

            {{-- Priority --}}
            <div>
                <label class="fl">{{ __('admin.task_priority') }} *</label>
                <select name="priority" class="fin" required>
                    @foreach(['urgent','high','medium','low'] as $p)
                    <option value="{{ $p }}" {{ old('priority', $task->priority) === $p ? 'selected' : '' }}>
                        {{ __('admin.'.$p) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Due date --}}
            <div>
                <label class="fl">{{ __('admin.due_date') }}</label>
                <input type="date" name="due_date" class="fin"
                       value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
            </div>

            {{-- Progress --}}
            <div class="fg2">
                <label class="fl">{{ __('admin.progress') }}: <span id="pl">{{ old('progress', $task->progress) }}%</span></label>
                <div style="display:flex;align-items:center;gap:12px">
                    <input type="range" name="progress" id="progress-range"
                           min="0" max="100" step="5"
                           value="{{ old('progress', $task->progress) }}"
                           style="flex:1;accent-color:var(--accent)">
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent);min-width:36px;text-align:end" id="pv">
                        {{ old('progress', $task->progress) }}%
                    </span>
                </div>
                {{-- Progress bar preview --}}
                <div style="height:4px;background:var(--surface2);border-radius:99px;margin-top:10px;overflow:hidden">
                    <div id="pbar" style="height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),#8b7eff);transition:width .2s;width:{{ old('progress', $task->progress) }}%"></div>
                </div>
            </div>

            {{-- Description --}}
            <div class="fg2">
                <label class="fl">{{ __('admin.task_description') }}</label>
                <textarea name="description" class="fin" rows="4">{{ old('description', $task->description) }}</textarea>
            </div>

            {{-- Notes --}}
            <div class="fg2">
                <label class="fl">{{ __('admin.notes') }}</label>
                <textarea name="notes" class="fin" rows="2">{{ old('notes', $task->notes) }}</textarea>
            </div>

        </div>
    </div>

    {{-- ── Assign Employees ── --}}
    @if($employees->count())
    <div class="fc">
        <div class="fct"><i class="fas fa-user-check"></i> {{ __('admin.assigned_to') }}</div>
        <div class="eg">
            @foreach($employees as $emp)
            @php $checked = in_array($emp->id, old('employees', $assignedIds)) @endphp
            <label class="ec {{ $checked ? 'sel' : '' }}" id="ec-{{ $emp->id }}">
                <input type="checkbox" name="employees[]" value="{{ $emp->id }}"
                       {{ $checked ? 'checked' : '' }}
                       onchange="document.getElementById('ec-{{ $emp->id }}').classList.toggle('sel',this.checked)">
                <div class="ea">
                    @if($emp->avatar)
                    <img src="{{ asset('storage/'.$emp->avatar) }}" alt="{{ $emp->name }}">
                    @else
                    {{ strtoupper(mb_substr($emp->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div style="font-size:12px;font-weight:600">{{ $emp->name }}</div>
                    <div style="font-size:10px;color:var(--muted)">{{ $emp->job_title ?? $emp->department ?? '' }}</div>
                </div>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Actions ── --}}
    <div style="display:flex;gap:12px;justify-content:space-between;align-items:center;flex-wrap:wrap">
        {{-- Delete --}}
        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" id="delete-form">
            @csrf @method('DELETE')
            <button type="button" class="btn-danger"
                    onclick="confirmDelete('delete-form','{{ addslashes(__('admin.confirm_delete')) }}')">
                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
            </button>
        </form>

        <div style="display:flex;gap:12px">
            <a href="{{ route('admin.tasks.show', $task) }}" class="btn-secondary">{{ __('admin.cancel') }}</a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> {{ __('admin.save_changes') }}
            </button>
        </div>
    </div>

</form>
@endsection

@push('scripts')
<script>
const range = document.getElementById('progress-range');
if (range) {
    range.addEventListener('input', () => {
        const v = range.value;
        document.getElementById('pl').textContent  = v + '%';
        document.getElementById('pv').textContent  = v + '%';
        document.getElementById('pbar').style.width = v + '%';
    });
}
</script>
@endpush