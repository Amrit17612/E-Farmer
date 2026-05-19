@extends('layouts.app')
@section('title', $scheme->title)
@section('page-title', 'Scheme Details')

@section('content')
<div style="max-width:860px;">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;">
        <a href="{{ route('schemes.index') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 style="font-size:20px;font-weight:700;">Scheme Details</h2>
    </div>

    {{-- Hero Banner --}}
    <div style="background:linear-gradient(135deg,#2E7D32,#1B5E20);border-radius:16px;padding:36px 40px;color:#fff;margin-bottom:24px;position:relative;overflow:hidden;">
        <div style="position:absolute;right:-30px;top:-30px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
        <div style="position:absolute;left:-40px;bottom:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
        <div style="position:relative;">
            <span style="background:rgba(255,255,255,0.18);padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;display:inline-block;margin-bottom:14px;">
                <i class="bi bi-tag"></i> {{ $scheme->category }}
            </span>
            <h1 style="font-size:26px;font-weight:800;margin-bottom:10px;line-height:1.3;">{{ $scheme->title }}</h1>
            <div style="font-size:13px;opacity:.75;margin-bottom:20px;">
                <i class="bi bi-building"></i> {{ $scheme->ministry }}
                @if($scheme->deadline)
                    &nbsp;·&nbsp;<i class="bi bi-calendar-event"></i> Deadline: {{ $scheme->deadline->format('d M Y') }}
                @endif
            </div>
            <form method="POST" action="{{ route('schemes.apply', $scheme) }}">
                @csrf
                <button type="submit" class="btn" style="background:#fff;color:#2E7D32;font-weight:700;padding:13px 32px;border-radius:10px;font-size:15px;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:8px;"
                        {{ $scheme->is_expired ? 'disabled' : '' }}>
                    <i class="bi bi-send-fill"></i>
                    {{ $scheme->is_expired ? 'Application Closed' : 'Apply for This Scheme' }}
                </button>
            </form>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
        <div>
            {{-- Description --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><span class="card-title"><i class="bi bi-info-circle" style="color:#2E7D32;"></i> About This Scheme</span></div>
                <div class="card-body">
                    <p style="font-size:14px;color:#4A4A4A;line-height:1.8;">{{ $scheme->description }}</p>
                </div>
            </div>

            {{-- Eligibility --}}
            @if($scheme->eligibility)
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><span class="card-title"><i class="bi bi-person-check" style="color:#2E7D32;"></i> Eligibility</span></div>
                <div class="card-body">
                    <p style="font-size:14px;color:#4A4A4A;line-height:1.8;">{{ $scheme->eligibility }}</p>
                </div>
            </div>
            @endif

            {{-- Benefits --}}
            @if($scheme->benefits)
            <div class="card" style="margin-bottom:20px;border:1px solid #E8F5E9;">
                <div class="card-header" style="background:#F9FBF9;"><span class="card-title" style="color:#2E7D32;"><i class="bi bi-gift-fill"></i> Benefits</span></div>
                <div class="card-body">
                    <p style="font-size:14px;color:#4A4A4A;line-height:1.8;">{{ $scheme->benefits }}</p>
                </div>
            </div>
            @endif
        </div>

        <div>
            {{-- Documents Required --}}
            @if($scheme->documents_required)
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><span class="card-title"><i class="bi bi-file-earmark-text" style="color:#2E7D32;"></i> Documents Required</span></div>
                <div class="card-body">
                    <ul style="list-style:none;padding:0;">
                        @foreach($scheme->documents_required as $doc)
                        <li style="padding:8px 0;border-bottom:1px solid #F5F5F5;font-size:13px;display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-check-circle-fill" style="color:#66BB6A;"></i> {{ $doc }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Quick Info --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Quick Info</span></div>
                <div class="card-body" style="padding:16px 20px;">
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:#9E9E9E;">Status</span>
                            <span class="badge {{ $scheme->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $scheme->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:#9E9E9E;">Category</span>
                            <strong>{{ $scheme->category }}</strong>
                        </div>
                        @if($scheme->deadline)
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:#9E9E9E;">Deadline</span>
                            <strong style="{{ $scheme->is_expired ? 'color:#c62828' : 'color:#2E7D32' }}">{{ $scheme->deadline->format('d M Y') }}</strong>
                        </div>
                        @endif
                        @if($scheme->apply_link)
                        <a href="{{ $scheme->apply_link }}" target="_blank" class="btn btn-outline btn-sm" style="justify-content:center;">
                            <i class="bi bi-box-arrow-up-right"></i> Official Portal
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
