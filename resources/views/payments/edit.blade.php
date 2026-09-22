@extends('layouts.app')

@section('content')
<!-- إخفاء الهيدر والشريط العلوي في هذه الصفحة تحديداً -->
<style>
    header, nav.navbar, .main-header, .topbar {
        display: none !important;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            
            <!-- كرت نموذج التعديل المركز في منتصف الشاشة -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- عنوان النموذج الرئيسي -->
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                ✏️ تعديل بيانات سند القبض
                            </h4>
                            <!-- <p class="text-light small mb-0">تحديث السند رقم #{{ $payment->receipt_number ?? $payment->id }}</p> -->
                        </div>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-bold">
                            ← رجوع
                        </a>
                    </div>

                    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- الطالب -->
                            <div class="col-md-6">
                                <label for="student_id" class="form-label fw-bold text-secondary small">
                                    🎓 الطالب <span class="text-danger">*</span>
                                </label>
                                <select class="form-select rounded-3 py-2 @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                    <option value="" disabled>-- اختر الطالب --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $payment->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الشعبة الدراسية (عرض اسم الدورة فقط وبشكل محدد تلقائياً) -->
                            <div class="col-md-6">
                                <label for="course_class_id" class="form-label fw-bold text-secondary small">
                                    💻 الشعبة الدراسية
                                </label>
                                <select class="form-select rounded-3 py-2 @error('course_class_id') is-invalid @enderror" id="course_class_id" name="course_class_id">
                                    <option value="">عام / قسط عام</option>
                                    @foreach($courseClasses as $class)
                                        <option value="{{ $class->id }}" {{ old('course_class_id', $payment->course_class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->course->title ?? $class->name ?? 'دورة دراسية' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- المبلغ المدفوع -->
                            <div class="col-md-6">
                                <label for="amount" class="form-label fw-bold text-secondary small">
                                    💵 المبلغ المدفوع ($) <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" class="form-control rounded-3 py-2 @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- تاريخ الدفع -->
                            <div class="col-md-6">
                                <label for="payment_date" class="form-label fw-bold text-secondary small">
                                    📅 تاريخ الدفع <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control rounded-3 py-2 @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- طريقة الدفع -->
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label fw-bold text-secondary small">
                                    💳 طريقة الدفع <span class="text-danger">*</span>
                                </label>
                                <select class="form-select rounded-3 py-2 @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                    <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>نقداً (Cash)</option>
                                    <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>بطاقة (Card)</option>
                                    <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي (Bank Transfer)</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- رقم السند -->
                            <div class="col-md-6">
                                <label for="receipt_number" class="form-label fw-bold text-secondary small">
                                    🔢 رقم السند
                                </label>
                                <input type="text" class="form-control rounded-3 py-2 @error('receipt_number') is-invalid @enderror" id="receipt_number" name="receipt_number" value="{{ old('receipt_number', $payment->receipt_number) }}" placeholder="مثال: REC-244">
                                @error('receipt_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ملاحظات -->
                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold text-secondary small">
                                    📝 ملاحظات إضافية
                                </label>
                                <textarea class="form-control rounded-3 @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes', $payment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- أزرار الحفظ والإلغاء -->
                            <div class="col-12 d-flex align-items-center gap-2 mt-4 pt-2">
                                <button type="submit" class="btn btn-warning fw-bold px-4 py-2 rounded-3 text-dark">
                                    ✓ تحديث البيانات
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-light fw-bold px-4 py-2 rounded-3 border">
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection