@extends('layouts.admin')
@section('title', __('admin.settings'))
@push('styles')
<style>
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.ph h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
/* Tabs */
.tabs{display:flex;gap:4px;background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:4px;margin-bottom:24px;flex-wrap:wrap}
.tab{flex:1;min-width:120px;padding:9px 16px;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;text-align:center;border:none;background:none;font-family:inherit;color:var(--muted);transition:all .2s;white-space:nowrap}
.tab.active{background:var(--surface);color:var(--text);box-shadow:0 2px 8px rgba(0,0,0,.15)}
.tab-panel{display:none}.tab-panel.active{display:block}
/* Cards */
.fc{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:26px;margin-bottom:20px}
.fct{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:9px;color:var(--text)}
.fct i{color:var(--accent)}
.fg{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:700px){.fg{grid-template-columns:1fr}}
.fl{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;display:block;margin-bottom:6px}
.fin{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s;width:100%;box-sizing:border-box}
.fin:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
select.fin{cursor:pointer}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:opacity .2s}
.btn-primary:hover{opacity:.9}
.btn-secondary{display:inline-flex;align-items:center;gap:7px;background:var(--surface2);color:var(--text);border:1.5px solid var(--border);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all .2s}
.btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
/* Map */
#location-map{width:100%;height:300px;border-radius:12px;border:1.5px solid var(--border);margin-top:12px;background:var(--surface2)}
.map-hint{font-size:11px;color:var(--muted);margin-top:6px;display:flex;align-items:center;gap:5px;flex-wrap:wrap}
.radius-preview{display:flex;align-items:center;gap:12px;margin-top:8px;padding:10px 14px;background:var(--surface2);border-radius:10px;font-size:12px;color:var(--muted)}
.radius-circle{width:36px;height:36px;border-radius:50%;border:2px solid var(--accent);background:rgba(108,99,255,.08);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--accent)}
/* Work schedule table */
.sch-table{width:100%;border-collapse:collapse}
.sch-table th{font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);font-weight:700;padding:9px 12px;text-align:start;border-bottom:1px solid var(--border);background:var(--surface2)}
.sch-table td{padding:10px 12px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.sch-table tr:last-child td{border-bottom:none}
.sch-table tr:hover td{background:rgba(255,255,255,.02)}
.day-name{font-weight:700;font-size:13px;min-width:90px}
.toggle-switch{position:relative;display:inline-block;width:42px;height:22px}
.toggle-switch input{opacity:0;width:0;height:0}
.toggle-slider{position:absolute;cursor:pointer;inset:0;background:var(--surface2);border:1.5px solid var(--border);border-radius:22px;transition:.2s}
.toggle-slider:before{content:'';position:absolute;height:14px;width:14px;left:3px;bottom:2px;background:var(--muted);border-radius:50%;transition:.2s}
input:checked + .toggle-slider{background:var(--accent);border-color:var(--accent)}
input:checked + .toggle-slider:before{transform:translateX(20px);background:#fff}
.time-pair{display:flex;align-items:center;gap:8px}
.fin-time{background:var(--surface2);border:1.5px solid var(--border);border-radius:8px;padding:7px 10px;font-size:12px;color:var(--text);font-family:inherit;outline:none;width:90px}
.fin-time:focus{border-color:var(--accent)}
.fin-time:disabled{opacity:.3;cursor:not-allowed}
.fin-grace{background:var(--surface2);border:1.5px solid var(--border);border-radius:8px;padding:7px 10px;font-size:12px;color:var(--text);font-family:inherit;outline:none;width:60px;text-align:center}
.fin-grace:disabled{opacity:.3;cursor:not-allowed}
.day-off-badge{display:inline-block;background:rgba(255,101,132,.1);color:var(--accent2);border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700}
/* Rates card */
.rate-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px}
.rate-hint{font-size:11px;color:var(--muted);margin-top:4px}
/* Toggle type radio */
.type-toggle{display:flex;gap:6px;margin-top:6px}
.type-btn{flex:1;padding:8px;border:1.5px solid var(--border);border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;text-align:center;background:none;font-family:inherit;color:var(--muted);transition:all .15s}
.type-btn.active{border-color:var(--accent);color:var(--accent);background:rgba(108,99,255,.08)}
</style>
@endpush
@section('content')

<div class="ph">
    <h1>{{ __('admin.settings') }}</h1>
</div>

@if(session('success'))
<div style="background:rgba(67,233,123,.08);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:12px 16px;margin-bottom:20px;color:#43e97b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Tabs --}}
<div class="tabs">
    <button class="tab active" onclick="switchTab('company')">
        <i class="fas fa-building" style="margin-inline-end:6px"></i>{{ __('admin.tab_company') }}
    </button>
    <button class="tab" onclick="switchTab('schedule')">
        <i class="fas fa-calendar-week" style="margin-inline-end:6px"></i>{{ __('admin.tab_schedule') }}
    </button>
    <button class="tab" onclick="switchTab('payroll')">
        <i class="fas fa-coins" style="margin-inline-end:6px"></i>{{ __('admin.tab_payroll_rates') }}
    </button>
    <button class="tab" onclick="switchTab('location')">
        <i class="fas fa-map-marker-alt" style="margin-inline-end:6px"></i>{{ __('admin.tab_location') }}
    </button>
</div>

{{-- ════════════════ TAB 1: Company ════════════════ --}}
<div class="tab-panel active" id="panel-company">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')
        <div class="fc">
            <div class="fct"><i class="fas fa-building"></i> {{ __('admin.company_info') }}</div>
            <div>
                <label class="fl">{{ __('admin.company_name') }}</label>
                <input type="text" name="company_name" class="fin" value="{{ old('company_name', $company_name) }}">
            </div>
        </div>
        {{-- pass through other settings unchanged --}}
        <input type="hidden" name="company_lat"               value="{{ $company_lat }}">
        <input type="hidden" name="company_lng"               value="{{ $company_lng }}">
        <input type="hidden" name="attendance_radius"         value="{{ $attendance_radius }}">
        <input type="hidden" name="absence_deduction_type"    value="{{ $absence_deduction_type }}">
        <input type="hidden" name="absence_deduction_value"   value="{{ $absence_deduction_value }}">
        <input type="hidden" name="late_deduction_per_minute" value="{{ $late_deduction_per_minute }}">
        <input type="hidden" name="overtime_rate_per_hour"    value="{{ $overtime_rate_per_hour }}">
        <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> {{ __('admin.save_settings') }}</button>
        </div>
    </form>
</div>

{{-- ════════════════ TAB 2: Work Schedule ════════════════ --}}
<div class="tab-panel" id="panel-schedule">
    <form method="POST" action="{{ route('admin.settings.schedule') }}">
        @csrf @method('PUT')
        <div class="fc">
            <div class="fct"><i class="fas fa-calendar-week"></i> {{ __('admin.work_schedule') }}</div>
            <p style="font-size:13px;color:var(--muted);margin:-10px 0 18px">{{ __('admin.work_schedule_hint') }}</p>

            <div style="overflow-x:auto">
                <table class="sch-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.day') }}</th>
                            <th>{{ __('admin.working_day') }}</th>
                            <th>{{ __('admin.start_time') }}</th>
                            <th>{{ __('admin.end_time') }}</th>
                            <th>{{ __('admin.grace_minutes') }}</th>
                            <th>{{ __('admin.expected_hours') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dayNamesAr = [0=>'الأحد',1=>'الاثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس',5=>'الجمعة',6=>'السبت'];
                        @endphp
                        @foreach($schedules as $sch)
                        <tr id="row-{{ $sch->day_of_week }}">
                            <td>
                                <input type="hidden" name="schedule[{{ $loop->index }}][day_of_week]" value="{{ $sch->day_of_week }}">
                                <span class="day-name">{{ $dayNamesAr[$sch->day_of_week] }}</span>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           name="schedule[{{ $loop->index }}][is_working_day]"
                                           value="1"
                                           {{ $sch->is_working_day ? 'checked' : '' }}
                                           onchange="toggleDay({{ $sch->day_of_week }}, this.checked)">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td>
                                <input type="time"
                                       name="schedule[{{ $loop->index }}][start_time]"
                                       class="fin-time"
                                       id="start-{{ $sch->day_of_week }}"
                                       value="{{ $sch->start_time ?? '' }}"
                                       {{ !$sch->is_working_day ? 'disabled' : '' }}
                                       onchange="updateExpected({{ $sch->day_of_week }})">
                            </td>
                            <td>
                                <input type="time"
                                       name="schedule[{{ $loop->index }}][end_time]"
                                       class="fin-time"
                                       id="end-{{ $sch->day_of_week }}"
                                       value="{{ $sch->end_time ?? '' }}"
                                       {{ !$sch->is_working_day ? 'disabled' : '' }}
                                       onchange="updateExpected({{ $sch->day_of_week }})">
                            </td>
                            <td>
                                <input type="number"
                                       name="schedule[{{ $loop->index }}][grace_minutes]"
                                       class="fin-grace"
                                       id="grace-{{ $sch->day_of_week }}"
                                       value="{{ $sch->grace_minutes }}"
                                       min="0" max="120"
                                       placeholder="0"
                                       {{ !$sch->is_working_day ? 'disabled' : '' }}>
                                <span style="font-size:11px;color:var(--muted)"> {{ __('admin.min') }}</span>
                            </td>
                            <td>
                                <span id="expected-{{ $sch->day_of_week }}" style="font-size:13px;font-weight:700;color:var(--accent)">
                                    @if($sch->is_working_day && $sch->start_time && $sch->end_time)
                                        {{ $sch->expectedMinutes() / 60 }}h
                                    @else
                                        <span class="day-off-badge">{{ __('admin.day_off') }}</span>
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> {{ __('admin.save_schedule') }}</button>
        </div>
    </form>
</div>

{{-- ════════════════ TAB 3: Payroll Rates ════════════════ --}}
<div class="tab-panel" id="panel-payroll">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')
        {{-- hidden pass-through --}}
        <input type="hidden" name="company_name"     value="{{ $company_name }}">
        <input type="hidden" name="company_lat"      value="{{ $company_lat }}">
        <input type="hidden" name="company_lng"      value="{{ $company_lng }}">
        <input type="hidden" name="attendance_radius" value="{{ $attendance_radius }}">

        {{-- Absence deduction --}}
        <div class="fc">
            <div class="fct"><i class="fas fa-user-times"></i> {{ __('admin.absence_deduction_settings') }}</div>
            <div class="rate-row">
                <div>
                    <label class="fl">{{ __('admin.absence_deduction_type') }}</label>
                    <div class="type-toggle">
                        <button type="button" class="type-btn {{ $absence_deduction_type==='daily'?'active':'' }}"
                                onclick="setDeductType('daily')" id="type-daily">
                            <i class="fas fa-calendar-day"></i> {{ __('admin.deduct_full_day') }}
                        </button>
                        <button type="button" class="type-btn {{ $absence_deduction_type==='percentage'?'active':'' }}"
                                onclick="setDeductType('percentage')" id="type-percentage">
                            <i class="fas fa-percentage"></i> {{ __('admin.deduct_percentage') }}
                        </button>
                    </div>
                    <input type="hidden" name="absence_deduction_type" id="absence_deduction_type" value="{{ $absence_deduction_type }}">
                </div>
                <div>
                    <label class="fl" id="deduct-val-label">
                        @if($absence_deduction_type === 'percentage')
                            {{ __('admin.deduct_percentage_of_daily') }}
                        @else
                            {{ __('admin.deduct_fixed_per_day') }} (JD) — {{ __('admin.zero_means_full_daily') }}
                        @endif
                    </label>
                    <input type="number" name="absence_deduction_value" class="fin"
                           id="absence_deduction_value"
                           step="0.01" min="0"
                           value="{{ old('absence_deduction_value', $absence_deduction_value) }}">
                    <div class="rate-hint" id="deduct-hint">
                        @if($absence_deduction_type === 'percentage')
                            {{ __('admin.deduct_percentage_hint') }}
                        @else
                            {{ __('admin.deduct_fixed_hint') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Late deduction --}}
        <div class="fc">
            <div class="fct"><i class="fas fa-clock"></i> {{ __('admin.late_deduction_settings') }}</div>
            <div class="rate-row">
                <div>
                    <label class="fl">{{ __('admin.late_deduction_per_minute') }} (JD)</label>
                    <input type="number" name="late_deduction_per_minute" class="fin"
                           step="0.001" min="0"
                           value="{{ old('late_deduction_per_minute', $late_deduction_per_minute) }}">
                    <div class="rate-hint">{{ __('admin.late_deduction_hint') }}</div>
                </div>
                <div style="background:var(--surface2);border-radius:12px;padding:14px;font-size:12px;color:var(--muted);display:flex;align-items:center;gap:10px">
                    <i class="fas fa-info-circle" style="color:var(--accent);font-size:16px;flex-shrink:0"></i>
                    <span>{{ __('admin.late_deduction_example') }}</span>
                </div>
            </div>
        </div>

        {{-- Overtime --}}
        <div class="fc">
            <div class="fct"><i class="fas fa-bolt"></i> {{ __('admin.overtime_settings') }}</div>
            <div class="rate-row">
                <div>
                    <label class="fl">{{ __('admin.overtime_rate_per_hour') }} (JD)</label>
                    <input type="number" name="overtime_rate_per_hour" class="fin"
                           step="0.01" min="0"
                           value="{{ old('overtime_rate_per_hour', $overtime_rate_per_hour) }}">
                    <div class="rate-hint">{{ __('admin.overtime_hint') }}</div>
                </div>
                <div style="background:rgba(67,233,123,.04);border:1px solid rgba(67,233,123,.15);border-radius:12px;padding:14px;font-size:12px;color:var(--muted);display:flex;align-items:center;gap:10px">
                    <i class="fas fa-info-circle" style="color:#43e97b;font-size:16px;flex-shrink:0"></i>
                    <span>{{ __('admin.overtime_example') }}</span>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> {{ __('admin.save_settings') }}</button>
        </div>
    </form>
</div>

{{-- ════════════════ TAB 4: Location ════════════════ --}}
<div class="tab-panel" id="panel-location">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')
        {{-- hidden pass-through --}}
        <input type="hidden" name="company_name"              value="{{ $company_name }}">
        <input type="hidden" name="absence_deduction_type"    value="{{ $absence_deduction_type }}">
        <input type="hidden" name="absence_deduction_value"   value="{{ $absence_deduction_value }}">
        <input type="hidden" name="late_deduction_per_minute" value="{{ $late_deduction_per_minute }}">
        <input type="hidden" name="overtime_rate_per_hour"    value="{{ $overtime_rate_per_hour }}">

        <div class="fc">
            <div class="fct"><i class="fas fa-map-marker-alt"></i> {{ __('admin.company_location') }}</div>
            <p style="font-size:13px;color:var(--muted);margin:-10px 0 18px">{{ __('admin.location_hint') }}</p>

            <div class="fg">
                <div>
                    <label class="fl">{{ __('admin.latitude') }}</label>
                    <input type="text" name="company_lat" id="lat-input" class="fin"
                           value="{{ old('company_lat', $company_lat) }}"
                           placeholder="31.9539" oninput="updateMapFromInputs()">
                </div>
                <div>
                    <label class="fl">{{ __('admin.longitude') }}</label>
                    <input type="text" name="company_lng" id="lng-input" class="fin"
                           value="{{ old('company_lng', $company_lng) }}"
                           placeholder="35.9106" oninput="updateMapFromInputs()">
                </div>
                <div>
                    <label class="fl">{{ __('admin.attendance_radius') }} ({{ __('admin.meters') }})</label>
                    <input type="number" name="attendance_radius" id="radius-input" class="fin"
                           value="{{ old('attendance_radius', $attendance_radius) }}"
                           min="50" max="5000" step="50"
                           oninput="document.getElementById('radius-val').textContent=this.value;if(circle)circle.setRadius(parseInt(this.value)||200)">
                    <div class="radius-preview">
                        <div class="radius-circle" id="radius-val">{{ $attendance_radius }}</div>
                        <span>{{ __('admin.radius_hint') }}</span>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-end">
                    <button type="button" class="btn-secondary" onclick="useMyLocation()" style="width:100%;justify-content:center">
                        <i class="fas fa-crosshairs"></i> {{ __('admin.use_my_location') }}
                    </button>
                </div>
            </div>

            <div style="margin-top:16px">
                <label class="fl">{{ __('admin.click_map_to_set') }}</label>
                <div id="location-map"></div>
                <div class="map-hint">
                    <i class="fas fa-info-circle"></i> {{ __('admin.map_click_hint') }}
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> {{ __('admin.save_settings') }}</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
// ── Tabs ──────────────────────────────────────────────
function switchTab(name) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    event.currentTarget.classList.add('active');
    if (name === 'location') setTimeout(() => map && map.invalidateSize(), 100);
}

// ── Work schedule toggle ──────────────────────────────
function toggleDay(dow, isWorking) {
    ['start','end','grace'].forEach(prefix => {
        const el = document.getElementById(prefix + '-' + dow);
        if (el) el.disabled = !isWorking;
    });
    const exp = document.getElementById('expected-' + dow);
    if (exp && !isWorking) exp.innerHTML = '<span class="day-off-badge">{{ __("admin.day_off") }}</span>';
    if (exp && isWorking) exp.textContent = '—';
}

function updateExpected(dow) {
    const s = document.getElementById('start-' + dow)?.value;
    const e = document.getElementById('end-' + dow)?.value;
    const el = document.getElementById('expected-' + dow);
    if (!s || !e || !el) return;
    const [sh,sm] = s.split(':').map(Number);
    const [eh,em] = e.split(':').map(Number);
    const mins = (eh*60+em) - (sh*60+sm);
    if (mins > 0) {
        const h = Math.floor(mins/60), m = mins%60;
        el.textContent = h + 'h' + (m ? ' ' + m + 'm' : '');
        el.style.color = 'var(--accent)';
    } else {
        el.textContent = '—';
    }
}

// ── Absence deduction type ────────────────────────────
function setDeductType(type) {
    document.getElementById('absence_deduction_type').value = type;
    document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('type-' + type).classList.add('active');
    const lbl  = document.getElementById('deduct-val-label');
    const hint = document.getElementById('deduct-hint');
    if (type === 'percentage') {
        lbl.textContent  = '{{ __("admin.deduct_percentage_of_daily") }}';
        hint.textContent = '{{ __("admin.deduct_percentage_hint") }}';
    } else {
        lbl.textContent  = '{{ __("admin.deduct_fixed_per_day") }} (JD) — {{ __("admin.zero_means_full_daily") }}';
        hint.textContent = '{{ __("admin.deduct_fixed_hint") }}';
    }
}

// ── Map (location tab) ────────────────────────────────
const initLat  = {{ $company_lat ?: 31.9539 }};
const initLng  = {{ $company_lng ?: 35.9106 }};
const hasLoc   = {{ ($company_lat && $company_lng) ? 'true' : 'false' }};
let map, marker, circle;

document.addEventListener('DOMContentLoaded', function () {
    map = L.map('location-map').setView([initLat, initLng], hasLoc ? 16 : 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    if (hasLoc) setMarker(initLat, initLng);
    map.on('click', e => { setMarker(e.latlng.lat, e.latlng.lng); map.setView([e.latlng.lat, e.latlng.lng], 16); });
});

function setMarker(lat, lng) {
    if (marker) { marker.remove(); circle.remove(); }
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    const radius = parseInt(document.getElementById('radius-input').value) || 200;
    circle = L.circle([lat, lng], { radius, color:'#6c63ff', fillColor:'#6c63ff', fillOpacity:.1, weight:2 }).addTo(map);
    marker.on('dragend', e => {
        const p = e.target.getLatLng();
        setCoords(p.lat.toFixed(7), p.lng.toFixed(7));
        circle.setLatLng([p.lat, p.lng]);
    });
    setCoords(lat.toFixed ? lat.toFixed(7) : lat, lng.toFixed ? lng.toFixed(7) : lng);
}

function setCoords(lat, lng) {
    document.getElementById('lat-input').value = lat;
    document.getElementById('lng-input').value = lng;
}

function updateMapFromInputs() {
    const lat = parseFloat(document.getElementById('lat-input').value);
    const lng = parseFloat(document.getElementById('lng-input').value);
    if (!isNaN(lat) && !isNaN(lng)) { setMarker(lat, lng); map.setView([lat, lng], 16); }
}

function useMyLocation() {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(p => {
        setMarker(p.coords.latitude, p.coords.longitude);
        map.setView([p.coords.latitude, p.coords.longitude], 17);
    });
}
</script>
@endpush