@extends('layouts.admin')
@section('title', __('admin.add_task'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:opacity .2s;box-shadow:0 4px 14px rgba(108,99,255,.3)}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;background:var(--surface2);color:var(--text);border:1px solid var(--border);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;font-family:inherit;cursor:pointer}
.btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
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
.ea{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0}
</style>
@endpush
@section('content')
<div class="ph">
    <h1>{{ __('admin.add_task') }}</h1>
    <a href="{{ route('admin.tasks.index') }}" class="btn-secondary"><i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i> {{ __('admin.back') }}</a>
</div>
<form method="POST" action="{{ route('admin.tasks.store') }}">
    @csrf
    <div class="fc">
        <div class="fct"><i class="fas fa-tasks"></i> {{ __('admin.basic_info') }}</div>
        <div class="fg">
            <div class="fg2">
                <label class="fl">{{ __('admin.task_title') }} *</label>
                <input type="text" name="title" class="fin @error('title') is-invalid @enderror" value="{{ old('title') }}" required autofocus>
                @error('title')<div class="fe">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="fl">{{ __('admin.client') }} *</label>
                <select name="client_id" class="fin" required>
                    <option value="">— {{ __('admin.none') }} —</option>
                    @foreach($clients as $c)<option value="{{ $c->id }}" {{ (old('client_id',$preClient?->id))==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="fl">{{ __('admin.service') }}</label>
                <select name="service_id" class="fin">
                    <option value="">— {{ __('admin.none') }} —</option>
                    @foreach($services as $s)<option value="{{ $s->id }}" {{ old('service_id')==$s->id?'selected':'' }}>{{ app()->getLocale()==='ar'?$s->name:($s->name_en??$s->name) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="fl">{{ __('admin.task_status') }} *</label>
                <select name="status" class="fin" required>
                    @foreach(['todo','in_progress','review','done','cancelled'] as $s)<option value="{{ $s }}" {{ old('status','todo')===$s?'selected':'' }}>{{ __('admin.'.$s) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="fl">{{ __('admin.task_priority') }} *</label>
                <select name="priority" class="fin" required>
                    @foreach(['urgent','high','medium','low'] as $p)<option value="{{ $p }}" {{ old('priority','medium')===$p?'selected':'' }}>{{ __('admin.'.$p) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="fl">{{ __('admin.due_date') }}</label>
                <input type="date" name="due_date" class="fin" value="{{ old('due_date') }}">
            </div>
            <div class="fg2">
                <label class="fl">{{ __('admin.progress') }}: <span id="pl">{{ old('progress',0) }}%</span></label>
                <div style="display:flex;align-items:center;gap:12px">
                    <input type="range" name="progress" min="0" max="100" step="5" value="{{ old('progress',0) }}" style="flex:1;accent-color:var(--accent)" oninput="document.getElementById('pl').textContent=this.value+'%'">
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent);min-width:36px;text-align:end" id="pv">{{ old('progress',0) }}%</span>
                </div>
            </div>
            <div class="fg2">
                <label class="fl">{{ __('admin.task_description') }}</label>
                <textarea name="description" class="fin" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="fg2">
                <label class="fl">{{ __('admin.notes') }}</label>
                <textarea name="notes" class="fin" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>
    @if($employees->count())
    <div class="fc">
        <div class="fct"><i class="fas fa-user-check"></i> {{ __('admin.assigned_to') }}</div>
        <div class="eg">
            @foreach($employees as $emp)
            @php $ch = in_array($emp->id, old('employees',[])) @endphp
            <label class="ec {{ $ch?'sel':'' }}" id="ec-{{ $emp->id }}">
                <input type="checkbox" name="employees[]" value="{{ $emp->id }}" {{ $ch?'checked':'' }}
                       onchange="document.getElementById('ec-{{ $emp->id }}').classList.toggle('sel',this.checked)">
                <div class="ea">{{ strtoupper(mb_substr($emp->name,0,1)) }}</div>
                <div><div style="font-size:12px;font-weight:600">{{ $emp->name }}</div><div style="font-size:10px;color:var(--muted)">{{ $emp->department??'' }}</div></div>
            </label>
            @endforeach
        </div>
    </div>
    @endif
    <div style="display:flex;gap:12px;justify-content:flex-end">
        <a href="{{ route('admin.tasks.index') }}" class="btn-secondary">{{ __('admin.cancel') }}</a>
        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> {{ __('admin.create') }}</button>
    </div>
</form>
@endsection
@push('scripts')
<script>
const r=document.querySelector('input[name=progress]');
if(r){r.addEventListener('input',()=>{document.getElementById('pl').textContent=r.value+'%';document.getElementById('pv').textContent=r.value+'%';});}
</script>
@endpush