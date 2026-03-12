@extends('layouts.admin')
@section('title', isset($employee) ? __('admin.edit_employee') : __('admin.add_employee'))
@section('page-title', isset($employee) ? __('admin.edit_employee') : __('admin.add_employee'))

@push('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.page-header h1{font-family:'Syne',sans-serif;font-size:22px;font-weight:800}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),#8b7eff);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:opacity .2s;box-shadow:0 4px 14px rgba(108,99,255,.3);text-decoration:none}
.btn-primary:hover{opacity:.9;color:#fff}
.btn-secondary{display:inline-flex;align-items:center;gap:8px;background:var(--surface2);color:var(--text);border:1px solid var(--border);border-radius:10px;padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;font-family:inherit;cursor:pointer}
.btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
.form-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:26px;margin-bottom:20px}
.form-card-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:9px}
.form-card-title i{color:var(--accent)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
@media(max-width:700px){.form-grid,.form-grid-3{grid-template-columns:1fr}}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-label{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px}
.form-control{background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s,box-shadow .2s;width:100%;box-sizing:border-box}
.form-control:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(108,99,255,.12)}
.form-control::placeholder{color:var(--muted)}
.form-control.is-invalid{border-color:var(--accent2)}
.field-error{font-size:11px;color:var(--accent2)}
.form-hint{font-size:11px;color:var(--muted)}

/* Avatar preview */
.avatar-preview{width:80px;height:80px;border-radius:14px;object-fit:cover;border:2px solid var(--border)}
.avatar-upload-area{display:flex;align-items:center;gap:16px;flex-wrap:wrap}

/* Toggle switch */
.toggle-row{display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1.5px solid var(--border);border-radius:10px;cursor:pointer;transition:border-color .2s}
.toggle-row:has(input:checked){border-color:var(--accent);background:rgba(108,99,255,.06)}
.toggle-label{font-size:13px;font-weight:600;flex:1}
.toggle-switch{position:relative;width:36px;height:20px;display:inline-block;flex-shrink:0}
.toggle-switch input{opacity:0;width:0;height:0}
.toggle-slider{position:absolute;inset:0;background:var(--surface);border-radius:99px;transition:.25s;cursor:pointer;border:1px solid var(--border)}
.toggle-slider:before{content:'';position:absolute;width:12px;height:12px;left:3px;bottom:3px;background:var(--muted);border-radius:50%;transition:.25s}
.toggle-switch input:checked+.toggle-slider{background:var(--accent);border-color:var(--accent)}
.toggle-switch input:checked+.toggle-slider:before{transform:translateX(16px);background:#fff}

/* Commission fields */
.commission-fields{background:rgba(0,198,255,.04);border:1px solid rgba(0,198,255,.15);border-radius:12px;padding:16px;margin-top:12px;display:none}
.commission-fields.open{display:block}

/* Account section */
.account-section{background:rgba(108,99,255,.04);border:1px solid rgba(108,99,255,.15);border-radius:12px;padding:16px;margin-top:12px;display:none}
.account-section.open{display:block}

/* Tags input */
.tags-display{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.tag-chip{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);color:var(--muted)}
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>{{ isset($employee) ? __('admin.edit_employee') : __('admin.add_employee') }}</h1>
    <a href="{{ isset($employee) ? route('admin.employees.show',$employee) : route('admin.employees.index') }}"
       class="btn-secondary">
        <i class="fas fa-arrow-{{ app()->getLocale()==='ar'?'right':'left' }}"></i> {{ __('admin.back') }}
    </a>
</div>

<form method="POST"
      action="{{ isset($employee) ? route('admin.employees.update',$employee) : route('admin.employees.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($employee)) @method('PUT') @endif

    {{-- ══ Basic Info ══════════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-user"></i> {{ __('admin.basic_info') }}</div>
        <div class="form-grid">

            {{-- Avatar --}}
            <div class="form-group full">
                <label class="form-label">{{ __('admin.avatar') }}</label>
                <div class="avatar-upload-area">
                    <img src="{{ isset($employee) ? $employee->avatar_url : 'https://ui-avatars.com/api/?name=New&background=6c63ff&color=fff&size=128' }}"
                         id="avatar-preview" alt="" class="avatar-preview">
                    <div>
                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                               onchange="previewAvatar(this)" style="display:none">
                        <button type="button" class="btn-secondary" style="font-size:12px;padding:8px 14px"
                                onclick="document.getElementById('avatar-input').click()">
                            <i class="fas fa-upload"></i> {{ __('admin.upload_photo') }}
                        </button>
                        <p class="form-hint" style="margin-top:6px">JPG, PNG — {{ __('admin.max_2mb') }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.name_ar') }} *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $employee->name ?? '') }}" required>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.name_en') }}</label>
                <input type="text" name="name_en" class="form-control"
                       value="{{ old('name_en', $employee->name_en ?? '') }}" dir="ltr">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.phone') }}</label>
                <input type="text" name="phone" class="form-control" dir="ltr"
                       value="{{ old('phone', $employee->phone ?? '') }}" placeholder="+9627xxxxxxxx">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.email') }}</label>
                <input type="email" name="email" class="form-control" dir="ltr"
                       value="{{ old('email', $employee->email ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.job_title') }} *</label>
                <input type="text" name="job_title" class="form-control @error('job_title') is-invalid @enderror"
                       value="{{ old('job_title', $employee->job_title ?? '') }}" required>
                @error('job_title')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.department') }} *</label>
                <select name="department" class="form-control" required>
                    @foreach(['design','video','development','social_media','marketing','sales','accounting','management'] as $d)
                    <option value="{{ $d }}" {{ old('department', $employee->department ?? 'design') === $d ? 'selected' : '' }}>
                        {{ __('admin.dept_'.$d) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.status') }} *</label>
                <select name="status" class="form-control" required>
                    @foreach(['active','inactive','vacation'] as $s)
                    <option value="{{ $s }}" {{ old('status', $employee->status ?? 'active') === $s ? 'selected' : '' }}>
                        {{ __('admin.'.$s) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.hire_date') }}</label>
                <input type="date" name="hire_date" class="form-control"
                       value="{{ old('hire_date', isset($employee->hire_date) ? $employee->hire_date->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group full">
                <label class="form-label">{{ __('admin.specializations') }}</label>
                <input type="text" name="specializations" class="form-control"
                       value="{{ old('specializations', $employee->specializations_string ?? '') }}"
                       placeholder="{{ __('admin.specializations_hint') }}"
                       oninput="renderTags(this)">
                <span class="form-hint">{{ __('admin.specializations_hint') }}</span>
                <div class="tags-display" id="tags-display">
                    @foreach($employee->specializations ?? [] as $sk)
                    <span class="tag-chip">{{ $sk }}</span>
                    @endforeach
                </div>
            </div>

            <div class="form-group full">
                <label class="form-label">{{ __('admin.notes') }}</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $employee->notes ?? '') }}</textarea>
            </div>

        </div>
    </div>

    {{-- ══ Salary & Commission ══════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-money-bill-wave"></i> {{ __('admin.salary_commission') }}</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">{{ __('admin.salary') }} ({{ __('admin.jd') }})</label>
                <input type="number" name="salary" class="form-control"
                       value="{{ old('salary', $employee->salary ?? 0) }}" min="0" step="0.001">
            </div>

            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">{{ __('admin.sales_employee') }}</label>
                <label class="toggle-row">
                    <span class="toggle-label">{{ __('admin.is_sales_hint') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_sales" value="1"
                               id="is-sales-toggle"
                               {{ old('is_sales', $employee->is_sales ?? false) ? 'checked' : '' }}
                               onchange="toggleCommission(this)">
                        <span class="toggle-slider"></span>
                    </label>
                </label>
            </div>

        </div>

        {{-- Commission fields (visible if is_sales checked) --}}
        <div class="commission-fields {{ (old('is_sales', $employee->is_sales ?? false)) ? 'open' : '' }}" id="commission-fields">
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">{{ __('admin.commission_rate') }} %</label>
                    <input type="number" name="commission_rate" class="form-control"
                           value="{{ old('commission_rate', $employee->commission_rate ?? 0) }}"
                           min="0" max="100" step="0.01">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.commission_type') }}</label>
                    <select name="commission_type" class="form-control">
                        <option value="per_deal" {{ old('commission_type',$employee->commission_type??'per_deal')==='per_deal'?'selected':'' }}>
                            {{ __('admin.per_deal') }}
                        </option>
                        <option value="monthly_percentage" {{ old('commission_type',$employee->commission_type??'')==='monthly_percentage'?'selected':'' }}>
                            {{ __('admin.monthly_percentage') }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ App Account ═════════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-card-title"><i class="fas fa-mobile-alt"></i> {{ __('admin.app_account') }}</div>

        @if(isset($employee) && $employee->user)
        {{-- existing account --}}
        <div style="background:rgba(67,233,123,.06);border:1px solid rgba(67,233,123,.2);border-radius:12px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <div style="font-size:13px;font-weight:700;color:#43e97b">
                    <i class="fas fa-check-circle"></i> {{ __('admin.account_exists') }}
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px">
                    {{ __('admin.phone') }}: {{ $employee->user->phone }} —
                    <span style="color:{{ $employee->user->activate==1?'#43e97b':'var(--accent2)' }}">
                        {{ $employee->user->activate==1 ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">{{ __('admin.change_phone') }}</label>
                <input type="text" name="user_phone" class="form-control @error('user_phone') is-invalid @enderror" dir="ltr"
                       value="{{ old('user_phone', $employee->user->phone) }}">
                @error('user_phone')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.new_password') }}</label>
                <input type="password" name="user_password" class="form-control"
                       placeholder="{{ __('admin.leave_empty_no_change') }}">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.account_status') }}</label>
                <label class="toggle-row">
                    <span class="toggle-label">{{ __('admin.account_active') }}</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="user_activate" value="1"
                               {{ $employee->user->activate==1 ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </label>
            </div>
        </div>

        @else
        {{-- create new account --}}
        <label class="toggle-row" style="margin-bottom:12px">
            <span class="toggle-label">{{ __('admin.create_account_hint') }}</span>
            <label class="toggle-switch">
                <input type="checkbox" name="create_account" value="1" id="create-account-toggle"
                       {{ old('create_account') ? 'checked' : '' }}
                       onchange="toggleAccountFields(this)">
                <span class="toggle-slider"></span>
            </label>
        </label>

        <div class="account-section {{ old('create_account') ? 'open' : '' }}" id="account-section">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">{{ __('admin.phone') }} *</label>
                    <input type="text" name="user_phone" class="form-control @error('user_phone') is-invalid @enderror"
                           dir="ltr" value="{{ old('user_phone') }}" placeholder="+9627xxxxxxxx">
                    @error('user_phone')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('admin.password') }} *</label>
                    <input type="password" name="user_password" class="form-control @error('user_password') is-invalid @enderror"
                           value="{{ old('user_password') }}" placeholder="{{ __('admin.min_6_chars') }}">
                    @error('user_password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:4px">
        <a href="{{ isset($employee) ? route('admin.employees.show',$employee) : route('admin.employees.index') }}"
           class="btn-secondary">{{ __('admin.cancel') }}</a>
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i>
            {{ isset($employee) ? __('admin.update') : __('admin.create') }}
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleCommission(cb) {
    document.getElementById('commission-fields').classList.toggle('open', cb.checked);
}

function toggleAccountFields(cb) {
    document.getElementById('account-section').classList.toggle('open', cb.checked);
}

function renderTags(input) {
    const container = document.getElementById('tags-display');
    const skills    = input.value.split(',').map(s => s.trim()).filter(Boolean);
    container.innerHTML = skills.map(s =>
        `<span class="tag-chip">${s}</span>`
    ).join('');
}
</script>
@endpush