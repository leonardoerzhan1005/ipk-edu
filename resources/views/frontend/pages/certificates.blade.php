@extends('frontend.layouts.master')

@section('content')
    <!-- Hero (blue) -->
    <section class="wsus__breadcrumb course_details_breadcrumb">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <h1 class="fw-bold mb-2">Сертификаттар</h1>
                <p class="mb-0" style="opacity:.95;max-width:900px;">Біздің институттан алған сертификаттарыңызды тексеріңіз және жүктеп алыңыз</p>
            </div>
        </div>
    </section>

    <!-- Search card -->
    <section class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 rounded-4 cert-search">
                <div class="card-body p-4 p-md-5">
                    <h3 class="mb-3 fw-semibold">Сертификатты іздеу</h3>
                    <form action="{{ request()->url() }}" method="GET" class="d-flex flex-column flex-md-row gap-3 align-items-stretch align-items-md-center">
                        <div class="position-relative flex-grow-1">
                            <input type="text" name="q" class="form-control form-control-lg cert-input" placeholder="Сертификат нөмірі немесе толық аты-жөні">
                            <span class="position-absolute end-0 top-0 h-100 d-flex align-items-center px-3 text-muted"><i class="far fa-search"></i></span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg px-4">Іздеу</button>
                    </form>
                    <div class="mt-4">
                        <div class="text-muted mb-2">Мысалы:</div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="cert-chip">CERT-2025-001234</span>
                            <span class="cert-chip">Әлібек Серіков</span>
                        </div>
                    </div>

                    @if(isset($query) && $query !== '')
                        <div class="mt-4">
                            @if($result)
                                <div class="alert alert-success rounded-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div>
                                            <div class="fw-semibold">Тексеру нәтижесі</div>
                                            <div class="small text-muted">Код: {{ $result->code }}</div>
                                            <div class="small">Аты-жөні: {{ $result->user->name }} · Курс: {{ $result->course->title }}</div>
                                        </div>
                                        @if($result->file_path)
                                            <a href="/{{ $result->file_path }}" class="btn btn-outline-primary">PDF жүктеу</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning rounded-3">Нәтиже табылмады. Деректерді тексеріп, қайта көріңіз.</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="pb-4">
        <div class="container">
            <div class="p-4 p-md-5 rounded-4 border bg-light cert-info">
                <h4 class="mb-3 fw-semibold">Сертификат туралы</h4>
                <p class="mb-3">Біздің институт ұсынатын сертификаттар мемлекеттік үлгідегі құжат болып табылады және біліктілікті растайды.</p>
                <p class="mb-3">Сертификат алу үшін курс бағдарламасын толық аяқтап, қорытынды тестілеуден өту қажет.</p>
                <p class="mb-0">Сертификаттың түпнұсқалығын осы бетте тексеруге болады. Сертификат нөмірі немесе аты-жөніңізді енгізіңіз.</p>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-5">
        <div class="container">
            <h4 class="fw-semibold mb-4">Жиі қойылатын сұрақтар</h4>
            <div class="accordion accordion-flush" id="certFaq">
                <div class="accordion-item border rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fq1">Сертификатты қайдан алуға болады?</button>
                    </h2>
                    <div id="fq1" class="accordion-collapse collapse" data-bs-parent="#certFaq">
                        <div class="accordion-body">Сертификаттың түпнұсқасын курс аяқталғаннан кейін 10 жұмыс күні ішінде институт кеңсесінен алуға болады. Сондай-ақ, электрондық нұсқасын осы бетте жүктеп алуға болады.</div>
                    </div>
                </div>
                <div class="accordion-item border rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fq2">Сертификаттың жарамдылық мерзімі қандай?</button>
                    </h2>
                    <div id="fq2" class="accordion-collapse collapse" data-bs-parent="#certFaq">
                        <div class="accordion-body">Сертификаттың жарамдылық мерзімі курс түріне байланысты. Біліктілікті арттыру курстары сертификаттарының жарамдылық мерзімі әдетте 3-5 жыл, ал тілдік курстар сертификаттарының жарамдылық мерзімі шектеусіз болады.</div>
                    </div>
                </div>
                <div class="accordion-item border rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fq3">Сертификатты жоғалтып алсам не істеуім керек?</button>
                    </h2>
                    <div id="fq3" class="accordion-collapse collapse" data-bs-parent="#certFaq">
                        <div class="accordion-body">Сертификатты жоғалтқан жағдайда, институт кеңсесіне жазбаша өтініш беру қажет. Сертификаттың көшірмесін жасау үшін төлем алынады.</div>
                    </div>
                </div>
                <div class="accordion-item border rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fq4">Сертификаттың түпнұсқалығын қалай тексеруге болады?</button>
                    </h2>
                    <div id="fq4" class="accordion-collapse collapse" data-bs-parent="#certFaq">
                        <div class="accordion-body">Сертификаттың түпнұсқалығын осы бетте сертификат нөмірін немесе аты-жөніңізді енгізу арқылы тексеруге болады. Сондай-ақ, институт кеңсесіне хабарласуға болады.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-5">
        <div class="container text-center">
            <h5 class="mb-3">Басқа сұрақтар бойынша бізге хабарласыңыз</h5>
            <a href="{{ localizedRoute('contact.index') }}" class="btn btn-primary btn-lg px-4">Байланысқа шығу</a>
        </div>
    </section>
@endsection

<style>
    .cert-search .cert-input{ padding-right:3rem; }
    .cert-chip{ display:inline-block;padding:.5rem .75rem;border-radius:999px;background:#F3F4F6;color:#111827;border:1px solid #E5E7EB; }
    .cert-info{ background:#F8FAFC !important; border-color:#E5E7EB !important; }
</style>