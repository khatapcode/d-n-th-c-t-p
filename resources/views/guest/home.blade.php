@extends('layouts.public')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-brand-950 text-white">
    <div class="absolute inset-0">
        <img src="{{ asset('images/art/hero.svg') }}" alt="" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-950/95 to-brand-900/70"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 lg:py-28 grid lg:grid-cols-[1.05fr_.95fr] gap-12 items-center">
        <div>
            <span class="inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-[.22em] text-brand-300 bg-white/5 border border-white/10 rounded-full px-3.5 py-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> Tuyển sinh lớp mới
            </span>
            <h1 class="font-display text-[2.6rem] sm:text-5xl lg:text-[3.4rem] leading-[1.1] mt-5">
                Học vẽ bài bản.<br>
                <span class="text-brand-300">Tạo tác phẩm thật.</span>
            </h1>
            <p class="mt-5 text-brand-100/80 max-w-xl leading-relaxed">
                Hình họa nền tảng, màu nước, sơn dầu và digital art — lớp nhỏ, giáo viên theo sát từng buổi,
                tác phẩm được duyệt và trưng bày trong gallery của trường.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Đăng ký học ngay <i class="bx bx-right-arrow-alt text-xl"></i></a>
                <a href="#courses" class="btn btn-lg !border-white/25 !text-white hover:!bg-white/10">Xem khóa học</a>
            </div>

            <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-lg">
                @foreach ([
                    [$numbers['students'], 'Học viên'],
                    [$numbers['teachers'], 'Giáo viên'],
                    [$numbers['classes'], 'Lớp đang mở'],
                    [$numbers['artworks'], 'Tác phẩm'],
                ] as [$value, $label])
                    <div>
                        <p class="text-2xl font-bold text-white">{{ $value }}</p>
                        <p class="text-[11px] uppercase tracking-wider text-brand-200/70 mt-0.5">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative">
            <div class="grid grid-cols-2 gap-4">
                @foreach ($courses->take(4) as $i => $course)
                    <div class="relative rounded-2xl overflow-hidden {{ in_array($i, [1, 2]) ? 'mt-7' : '' }} shadow-2xl shadow-black/40">
                        @if ($course->image)
                            <img src="{{ asset('images/courses/'.$course->image) }}" alt="{{ $course->name }}" class="w-full h-44 sm:h-52 object-cover">
                        @else
                            <div class="w-full h-44 sm:h-52 bg-brand-800"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3">
                            <p class="text-[10px] uppercase tracking-wider text-brand-300 font-bold">{{ $course->level }}</p>
                            <p class="text-sm font-semibold leading-tight">{{ $course->name }}</p>
                        </div>
                    </div>
                @endforeach
                @if ($courses->isEmpty())
                    <div class="col-span-2 h-72 rounded-2xl bg-white/5 border border-white/10"></div>
                @endif
            </div>
            <div class="absolute -bottom-6 -left-4 sm:-left-8 card p-4 !bg-white/95 backdrop-blur flex items-center gap-3 shadow-xl">
                <span class="avatar bg-brand-100 text-brand-700"><i class="bx bxs-trophy text-lg"></i></span>
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-[#7d8f87] font-bold">Lớp học giới hạn</p>
                    <p class="text-sm font-semibold text-[#10231c]">Tối đa 12 – 20 học viên</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- GIỚI THIỆU --}}
<section id="intro" class="max-w-6xl mx-auto px-4 sm:px-6 py-20">
    <div class="grid lg:grid-cols-[.9fr_1.1fr] gap-12 items-start">
        <div>
            <p class="eyebrow">Vì sao chọn Madmun</p>
            <h2 class="font-display text-3xl sm:text-4xl mt-3 leading-tight">Một studio vận hành như một trường học nghiêm túc.</h2>
            <p class="text-[#4b5f56] mt-4 leading-relaxed">
                Không chỉ là lớp học cuối tuần. Mỗi học viên có hồ sơ, lịch sử điểm danh, học phí rõ ràng và
                một gallery cá nhân để lưu giữ tiến bộ theo thời gian.
            </p>
            <a href="#contact" class="inline-flex items-center gap-2 mt-6 text-sm font-semibold text-brand-700 hover:gap-3 transition-all">
                Đặt lịch tư vấn <i class="bx bx-right-arrow-alt text-lg"></i>
            </a>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach ([
                ['bxs-palette', 'Lộ trình theo trình độ', 'Từ người chưa biết cầm cọ đến luyện thi portfolio, chia cấp độ rõ ràng.'],
                ['bxs-user-voice', 'Giáo viên kèm sát', 'Lớp nhỏ, mỗi buổi có nhận xét trực tiếp trên tác phẩm của bạn.'],
                ['bxs-receipt', 'Học phí minh bạch', 'Hóa đơn từng lớp, theo dõi đã nộp / chờ xác nhận ngay trong tài khoản.'],
                ['bxs-image-alt', 'Gallery học viên', 'Tác phẩm được duyệt sẽ xuất hiện trong phòng trưng bày của trường.'],
            ] as [$icon, $title, $desc])
                <div class="card card-pad card-hover">
                    <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center"><i class="bx {{ $icon }} text-xl"></i></span>
                    <h3 class="font-semibold mt-4 text-[15px]">{{ $title }}</h3>
                    <p class="text-sm text-[#7d8f87] mt-1.5 leading-relaxed">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- KHÓA HỌC --}}
<section id="courses" class="bg-white border-y border-[#eef4f0] py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-9">
            <div>
                <p class="eyebrow">Chương trình</p>
                <h2 class="font-display text-3xl sm:text-4xl mt-2">Khóa học nổi bật</h2>
            </div>
            <a href="{{ route('register') }}" class="btn btn-soft btn-sm">Tư vấn chọn khóa <i class="bx bx-right-arrow-alt text-base"></i></a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($courses as $course)
                <a href="{{ route('course.show', $course) }}" class="card card-hover overflow-hidden flex flex-col group">
                    <div class="relative h-44 overflow-hidden bg-brand-100">
                        @if ($course->image)
                            <img src="{{ asset('images/courses/'.$course->image) }}" alt="{{ $course->name }}"
                                 class="w-full h-full object-cover group-hover:scale-[1.04] transition duration-500">
                        @endif
                        <span class="absolute top-3 left-3 badge badge-green !bg-white/95">
                            {{ ['beginner'=>'Cơ bản','intermediate'=>'Trung cấp','advanced'=>'Nâng cao'][$course->level] ?? $course->level }}
                        </span>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="font-semibold text-[16px] leading-snug">{{ $course->name }}</h3>
                        <p class="text-sm text-[#7d8f87] mt-2 line-clamp-2 flex-1">{{ $course->description }}</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-[#f0f4f1]">
                            <div>
                                <p class="text-lg font-bold text-brand-700">{{ number_format($course->tuition_fee) }}đ</p>
                                <p class="text-[11px] text-[#9aaba3]">{{ $course->duration_hours }} giờ · {{ $course->classes_count ?? $course->classes->count() }} lớp</p>
                            </div>
                            <span class="w-8 h-8 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="bx bx-right-arrow-alt text-lg"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full empty"><i class="bx bxs-book-open"></i>Chưa có khóa học nào được mở.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- LỊCH KHAI GIẢNG --}}
@if ($openClasses->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-20">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
            <p class="eyebrow">Lịch khai giảng</p>
            <h2 class="font-display text-3xl sm:text-4xl mt-2">Lớp đang nhận đăng ký</h2>
        </div>
        <span class="text-sm text-[#7d8f87]">Đăng nhập để gửi yêu cầu giữ chỗ</span>
    </div>
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Lớp</th>
                        <th>Khóa học</th>
                        <th>Giáo viên</th>
                        <th>Lịch học</th>
                        <th>Khai giảng</th>
                        <th class="text-center">Còn chỗ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($openClasses as $class)
                        @php $left = max(0, $class->max_students - $class->students_count); @endphp
                        <tr>
                            <td>
                                <p class="font-semibold">{{ $class->name }}</p>
                                <p class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</p>
                            </td>
                            <td class="text-[#4b5f56]">{{ $class->course->name ?? '—' }}</td>
                            <td class="text-[#4b5f56]">{{ $class->teacher->name ?? 'Đang cập nhật' }}</td>
                            <td class="text-[#4b5f56]">{{ $class->schedule ?? '—' }}</td>
                            <td class="text-[#4b5f56]">{{ optional($class->start_date)->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $left > 0 ? 'badge-green' : 'badge-red' }}">{{ $left > 0 ? $left.' chỗ' : 'Hết chỗ' }}</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('register') }}" class="btn btn-ghost btn-sm">Đăng ký</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif

{{-- GIÁO VIÊN --}}
<section id="teachers" class="bg-[#f4f8f5] py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-xl mx-auto mb-10">
            <p class="eyebrow">Đội ngũ</p>
            <h2 class="font-display text-3xl sm:text-4xl mt-2">Người đồng hành cùng bạn</h2>
            <p class="text-sm text-[#7d8f87] mt-3">Mỗi giáo viên phụ trách tối đa vài lớp để đảm bảo thời gian kèm từng học viên.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($teachers as $teacher)
                <div class="card card-pad card-hover text-center">
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl font-bold">
                        {{ mb_substr($teacher->name, 0, 1) }}
                    </div>
                    <h3 class="font-semibold mt-4">{{ $teacher->name }}</h3>
                    <p class="text-xs text-brand-600 font-medium mt-0.5">Giáo viên</p>
                    <p class="text-sm text-[#7d8f87] mt-2.5 leading-relaxed line-clamp-3">{{ $teacher->bio ?: 'Giảng dạy nền tảng hội họa và luyện kỹ năng quan sát.' }}</p>
                </div>
            @empty
                <div class="col-span-full empty"><i class="bx bxs-user-voice"></i>Đội ngũ đang được cập nhật.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- GALLERY --}}
<section id="gallery" class="bg-brand-950 text-white py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.22em] text-brand-300">Phòng trưng bày</p>
                <h2 class="font-display text-3xl sm:text-4xl mt-2">Tác phẩm của học viên</h2>
            </div>
            <p class="text-sm text-brand-200/70 max-w-xs">Mỗi tác phẩm được giáo viên duyệt và nhận xét trước khi trưng bày.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            @forelse ($artworks as $art)
                <figure class="group relative rounded-2xl overflow-hidden bg-brand-900 {{ $loop->index % 5 === 0 ? 'row-span-2' : '' }}">
                    <img src="{{ asset('images/artworks/'.$art->image) }}" alt="{{ $art->title }}"
                         class="w-full h-40 {{ $loop->index % 5 === 0 ? 'md:h-full' : '' }} object-cover group-hover:scale-105 transition duration-500">
                    <figcaption class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/80 to-transparent">
                        <p class="text-xs font-semibold">{{ $art->title }}</p>
                        <p class="text-[10px] text-brand-200/80">{{ $art->owner->name ?? 'Học viên' }}</p>
                    </figcaption>
                </figure>
            @empty
                <div class="col-span-full text-center py-10 text-brand-200/60 text-sm">
                    <i class="bx bxs-image text-4xl block mb-2 text-brand-500"></i>
                    Chưa có tác phẩm được duyệt.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- REVIEW --}}
@if ($reviews->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-20">
    <div class="text-center mb-10">
        <p class="eyebrow">Học viên nói gì</p>
        <h2 class="font-display text-3xl sm:text-4xl mt-2">Những buổi học để lại dấu ấn</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-5">
        @foreach ($reviews->take(3) as $review)
            <blockquote class="card card-pad">
                <div class="text-amber-500 text-sm">{{ str_repeat('★', $review->rating) }}<span class="text-[#e5ebe7]">{{ str_repeat('★', 5 - $review->rating) }}</span></div>
                <p class="mt-3 text-[#4b5f56] leading-relaxed">“{{ $review->content }}”</p>
                <footer class="mt-4 flex items-center gap-2.5">
                    <span class="avatar !w-8 !h-8 !text-xs">{{ mb_substr($review->author_name, 0, 1) }}</span>
                    <span class="text-sm font-medium">{{ $review->author_name }}</span>
                </footer>
            </blockquote>
        @endforeach
    </div>
</section>
@endif

{{-- CTA + LIÊN HỆ --}}
<section id="contact" class="max-w-6xl mx-auto px-4 sm:px-6 pb-4">
    <div class="card overflow-hidden grid lg:grid-cols-2">
        <div class="p-8 sm:p-10 bg-gradient-to-br from-brand-700 to-brand-900 text-white">
            <p class="text-[11px] font-bold uppercase tracking-[.22em] text-brand-300">Bắt đầu</p>
            <h2 class="font-display text-3xl mt-3 leading-tight">Buổi học thử đầu tiên<br>miễn phí.</h2>
            <p class="text-brand-100/80 text-sm mt-4 leading-relaxed">Để lại thông tin, đội ngũ sẽ gọi tư vấn lộ trình và xếp lớp phù hợp trong 24h.</p>

            <div class="mt-8 space-y-3 text-sm">
                <p class="flex items-center gap-2.5"><i class="bx bx-phone text-brand-300 text-lg"></i> 090 123 4567</p>
                <p class="flex items-center gap-2.5"><i class="bx bx-envelope text-brand-300 text-lg"></i> hello@madmun.art</p>
                <p class="flex items-center gap-2.5"><i class="bx bx-map text-brand-300 text-lg"></i> 123 Nguyễn Huệ, Quận 1, TP.HCM</p>
            </div>

            <div class="mt-8 flex gap-3">
                <a href="{{ route('register') }}" class="btn btn-lg bg-white text-brand-800 hover:bg-brand-50">Đăng ký học viên</a>
            </div>
        </div>

        <div class="p-8 sm:p-10">
            <h3 class="font-semibold text-lg">Gửi câu hỏi cho chúng tôi</h3>
            @if (session('success'))
                <div class="mt-4 flex items-start gap-2 text-sm bg-brand-50 text-brand-800 border border-brand-100 rounded-xl px-4 py-3">
                    <i class="bx bxs-check-circle text-lg mt-0.5"></i> {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('contact.submit') }}" class="mt-5 space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="label" for="c-name">Họ tên</label>
                        <input id="c-name" name="name" value="{{ old('name') }}" placeholder="Họ tên của bạn" class="input" required>
                    </div>
                    <div>
                        <label class="label" for="c-phone">Số điện thoại</label>
                        <input id="c-phone" name="phone" value="{{ old('phone') }}" placeholder="090..." class="input">
                    </div>
                </div>
                <div>
                    <label class="label" for="c-email">Email</label>
                    <input id="c-email" type="email" name="email" value="{{ old('email') }}" placeholder="ban@example.com" class="input" required>
                </div>
                <div>
                    <label class="label" for="c-msg">Nội dung</label>
                    <textarea id="c-msg" name="message" rows="3" placeholder="Bạn quan tâm khóa học nào?" class="input" required>{{ old('message') }}</textarea>
                </div>
                @if ($errors->any())
                    <p class="text-xs text-red-600">{{ $errors->first() }}</p>
                @endif
                <button class="btn btn-primary w-full">Gửi liên hệ <i class="bx bx-send text-base"></i></button>
            </form>
        </div>
    </div>
</section>
@endsection
