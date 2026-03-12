{{-- ══════════════════════════════════════════════════
     employee/leaves/create.blade.php
══════════════════════════════════════════════════ --}}
@extends('layouts.employee')
@section('title', __('emp.request_leave'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn{display:inline-flex;align-items:center;gap:7px;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-family:inherit;border:none}
.btn-primary{background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff}.btn-primary:hover{opacity:.9;color:#fff}
.btn-ghost{background:var(--surface2);color:var(--text);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:26px;max-width:600px}
.fg{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:580px){.fg{grid-template-columns:1fr}}
.fl{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;display:block;margin-bottom:6px}
.fin{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s;width:100%;box-sizing:border-box}
.fin:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
textarea.fin{min-height:90px;resize:vertical}
select.fin{cursor:pointer}
.err{font-size:11px;color:var(--accent2);margin-top:4px}
/* Days preview */
.days-preview{background:rgba(108,99,255,.08);border:1px solid rgba(108,99,255,.2);border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;margin-top:12px;font-size:13px}
.days-big{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:var(--accent)}
/* Type cards */
.type-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
@media(max-width:420px){.type-grid{grid-template-columns:1fr 1fr}}
.type-card{border:2px solid var(--border);border-radius:10px;padding:10px;text-align:center;cursor:pointer;transition:all .2s;background:none;font-family:inherit}
.type-card:hover{border-color:var(--accent);background:rgba(108,99,255,.05)}
.type-card.selected{border-color:var(--accent);background:rgba(108,99,255,.1)}
.type-icon{font-size:18px;margin-bottom:4px}
.type-label{font-size:11px;font-weight:700}
/* Balance warning */
.bal-warn{background:rgba(255,101,132,.08);border:1px solid rgba(255,101,132,.2);border-radius:10px;padding:10px 14px;font-size:12px;color:var(--accent2);display:flex;align-items:center;gap:8px;margin-top:10px}
</style>
@endpush
@section('content')

<div class="ph">
    <div>
        <h1>{{ __('emp.request_leave') }}</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ __('emp.request_leave_subtitle') }}</p>
    </div>
    <a href="{{ route('employee.leaves.index') }}" class="btn btn-ghost">
        <i class="fas fa-arrow-right"></i> {{ __('emp.back') }}
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ route('employee.leaves.store') }}">
        @csrf

        {{-- Leave type --}}
        <div style="margin-bottom:20px">
            <label class="fl">{{ __('emp.leave_type') }}</label>
            <input type="hidden" name="type" id="type-input" value="{{ old('type','annual') }}">
            <div class="type-grid">
                @foreach([
                    ['annual',    'fas fa-sun',              'emp.leave_annual'],
                    ['sick',      'fas fa-briefcase-medical','emp.leave_sick'],
                    ['emergency', 'fas fa-bolt',             'emp.leave_emergency'],
                    ['unpaid',    'fas fa-ban',              'emp.leave_unpaid'],
                    ['other',     'fas fa-ellipsis-h',       'emp.leave_other'],
                ] as [$val, $icon, $lang])
                <button type="button"
                        class="type-card {{ old('type','annual') === $val ? 'selected' : '' }}"
                        onclick="selectType('{{ $val }}')" id="tc-{{ $val }}">
                    <div class="type-icon"><i class="{{ $icon }}"></i></div>
                    <div class="type-label">{{ __($lang) }}</div>
                </button>
                @endforeach
            </div>
            @error('type')<div class="err">{{ $message }}</div>@enderror
        </div>

        {{-- Dates --}}
        <div class="fg" style="margin-bottom:16px">
            <div>
                <label class="fl">{{ __('emp.start_date') }}</label>
                <input type="date" name="start_date" id="start_date" class="fin"
                       value="{{ old('start_date') }}"
                       min="{{ today()->format('Y-m-d') }}"
                       oninput="calcDays()">
                @error('start_date')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="fl">{{ __('emp.end_date') }}</label>
                <input type="date" name="end_date" id="end_date" class="fin"
                       value="{{ old('end_date') }}"
                       min="{{ today()->format('Y-m-d') }}"
                       oninput="calcDays()">
                @error('end_date')<div class="err">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Days preview --}}
        <div class="days-preview" id="days-preview" style="{{ (old('start_date') && old('end_date')) ? '' : 'display:none' }}">
            <span style="color:var(--muted)">{{ __('emp.total_days') }}</span>
            <span class="days-big" id="days-count">0</span>
        </div>

        {{-- Balance warning (annual only) --}}
        <div class="bal-warn" id="bal-warn" style="display:none">
            <i class="fas fa-exclamation-triangle"></i>
            <span id="bal-warn-text"></span>
        </div>

        {{-- Reason --}}
        <div style="margin-top:16px;margin-bottom:20px">
            <label class="fl">{{ __('emp.reason') }} ({{ __('emp.optional') }})</label>
            <textarea name="reason" class="fin" placeholder="{{ __('emp.reason_hint') }}">{{ old('reason') }}</textarea>
        </div>

        {{-- Balance info --}}
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px;padding:10px 14px;background:var(--surface2);border-radius:10px;display:flex;align-items:center;gap:8px">
            <i class="fas fa-info-circle" style="color:var(--accent)"></i>
            {{ __('emp.annual_balance_left') }}: <strong style="color:var(--accent)">{{ $balance->remaining }}</strong> {{ __('emp.days') }} ({{ now()->year }})
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end">
            <a href="{{ route('employee.leaves.index') }}" class="btn btn-ghost">{{ __('emp.cancel') }}</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> {{ __('emp.submit_request') }}
            </button>
        </div>
    </form>
</div>

@endsection
@push('scripts')
<script>
const REMAINING = {{ $balance->remaining }};

function selectType(val) {
    document.getElementById('type-input').value = val;
    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('tc-' + val).classList.add('selected');
    calcDays();
}

function calcDays() {
    const s = document.getElementById('start_date').value;
    const e = document.getElementById('end_date').value;
    if (!s || !e) { document.getElementById('days-preview').style.display = 'none'; return; }
    const ms   = new Date(e) - new Date(s);
    const days = Math.round(ms / 86400000) + 1;
    if (days < 1) { document.getElementById('days-preview').style.display = 'none'; return; }
    document.getElementById('days-count').textContent = days;
    document.getElementById('days-preview').style.display = 'flex';

    const type = document.getElementById('type-input').value;
    const warn = document.getElementById('bal-warn');
    if (type === 'annual' && days > REMAINING) {
        warn.style.display = 'flex';
        document.getElementById('bal-warn-text').textContent =
            `{{ __("emp.balance_warning_prefix") }} ${days} {{ __("emp.days") }} / {{ __("emp.available") }}: ${REMAINING} {{ __("emp.days") }}`;
    } else {
        warn.style.display = 'none';
    }
}
</script>
@endpush


