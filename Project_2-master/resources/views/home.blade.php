@extends('layouts.layout')
<style>
    .count {
        background-color: #f5f5f5;
        padding: 20px 0;
        border-radius: 5px;


    }

    .count-title {
        font-size: 40px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;
    }

    .count-text {
        font-size: 15px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;

    }

    .fa-2x {
        margin: 0 auto;
        float: none;
        display: table;
        color: #4ad1e5;
    }

    .news-item {
        position: relative;
        display: block;
        width: 100%;
        max-width: 960px;
        margin: 0 auto; 
    }

    .news-img {
        width: 100%;
        height: auto;
        max-height: 450px;
        object-fit: cover;
        display: block;
        transition: opacity 0.3s ease-in-out;
    }

    .news-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 16px;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .news-item:hover .news-overlay {
        opacity: 1;
    }

    .highlight-card {
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* เพิ่ม transition เพื่อให้การเปลี่ยนแปลงเรียบเนียน */
    cursor: pointer;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    }

        /* เมื่อ hover card */
    .highlight-card:hover {
        transform: scale(1.05); /* ขยายขนาด card ขึ้นเล็กน้อย */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเพื่อทำให้ดูเด่นขึ้น */
    }
    .card-img-top {
    height: 200px;
    object-fit: cover;
    }

    .card-tooltip {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 8px 0;
    font-size: 14px;
    text-align: center;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
    }

    .highlight-card:hover .card-tooltip {
    opacity: 1;
    }

    .card-title {
    color: #19568A;
    font-family: 'Kanit', sans-serif;
    font-size: 16px;
    }

    .card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 12px;
    text-align: center;
    transform: scale(1.05);
    }


    /* สไตล์สำหรับปุ่ม "อ่านเพิ่มเติม" */
    .btn-read-more-link {
        display: block;
        margin: 15px auto; /* จัดให้ปุ่มอยู่กลาง */
        width: 80%; /* ปรับความกว้างของปุ่ม */
    }

    .btn-read-more {
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgb(0, 0, 0 ,0.5)); /* ไล่สีจากโปร่งแสง (ใส) ไปเป็นสีฟ้าเข้ม */
        color: white;
        border: none;
        padding: 15px 0; /* ความสูงของปุ่ม */
        width: 100%; /* ปรับให้กว้างเต็มความกว้างของ parent container */
        border-radius: 5px; /* ทำให้มุมโค้งมน */
        font-size: 18px; /* ขนาดฟอนต์ */
        cursor: pointer;
        text-align: center; /* ให้ข้อความอยู่กลาง */
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-read-more:hover {
        background: linear-gradient(to bottom, rgba(61, 63, 65, 0.3), rgb(0, 0, 0, 0.7)); /* เพิ่มความชัดเจนเมื่อ hover */
        transform: scale(1.05); /* เพิ่มขนาดเมื่อ hover */
    }


</style>
@section('content')
<div class="container home"> <br>
<h2 class="text-center">Highlight</h2>
        <div class="container d-sm-flex justify-content-center mt-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                @if($highlightNews->isNotEmpty())
                <style>
                /* วงกลมสีดำโปร่งแสงอยู่ด้านหลัง */
                .carousel-control-prev::before{
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 50px;
                    height: 50px;
                    background-color: rgba(0, 0, 0, 0.5); /* สีดำโปร่งแสง */
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    z-index: -1; /* ให้วงกลมอยู่ด้านหลังลูกศร */
                }
                .carousel-control-next::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 50px;
                    height: 50px;
                    background-color: rgba(0, 0, 0, 0.5); /* สีดำโปร่งแสง */
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    z-index: -1; /* ให้วงกลมอยู่ด้านหลังลูกศร */
                }
            </style>
                        <div class="carousel-indicators">
                            @foreach ($highlightNews as $index => $news)
                                <button type="button" data-bs-target="#carouselExampleIndicators" 
                                    data-bs-slide-to="{{ $index }}" 
                                    class="{{ $index == 0 ? 'active' : '' }}" 
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        
                        <div class="carousel-inner">
                            @foreach ($highlightNews as $index => $news)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <a href="{{ url('/news/' . $news->news_id) }}" class="news-item">
                                        <img src="{{ asset('storage/' . $news->path_banner_img) }}" class="d-block w-100" alt="{{ $news->title }}">
                                        <div class="news-overlay">อ่านเพิ่มเติม</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- ปุ่มซ้ายขวา -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                @else
                    <img src="{{ asset('img/Banner1.png') }}" alt="banner" class="w-100 h-100">
                @endif
            </div>
        </div>
    
    <!-- แสดงข่าวhighlight 6 ข่าว -->

    <div id="highlight-container"><br>
    <h2 class="text-center">ข่าวประชาสัมพันธ์</h2>
    <br>
            @if ($latestNews->isEmpty())
                <p class="text-center">ไม่มีไฮไลท์.</p>
            @else
                <div class="row">
                    @foreach ($latestNews->take(6) as $latestNews)
                        <div class="col-md-4 col-sm-6 col-12 mb-3 highlight-item">
                            <a href="{{ route('news.details', $latestNews['news_id'] ?? '#') }}" class="text-decoration-none">
                                <div class="card highlight-card">
                                @if (!empty($latestNews['banner']))
                                    <!-- ถ้ามี URL และเป็นภาพจริง -->
                                    <img src="{{ asset('storage/' . $latestNews['banner']) }}" class="card-img-top rounded img-fluid" alt="{{ $latestNews['title'] ?? 'ไม่มีชื่อเรื่อง' }}">
                                @else
                                    <!-- ถ้าไม่มีรูป หรือไม่สามารถโหลดรูป -->
                                    <div class="card-img-top rounded img-fluid text-center" style="border: 2px solid #000; padding: 100px; font-size: 20px; color: #000;">
                                        ไม่พบรูปภาพ
                                    </div>
                                @endif
                                    <div class="card-tooltip">อ่านเพิ่มเติม</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($latestNews['title'] ?? 'ไม่มีชื่อเรื่อง', 50) }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                    @endforeach
                    
                    <a href="{{ route('highlight.index') }}" class="btn-read-more-link">
                        <button type="button" class="btn-read-more">อ่านเพิ่มเติม</button>
                    </a>
                </div>
                
            @endif
        </div>
</div>




    <!-- Modal -->



    <div class="container card-cart d-sm-flex justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="chart" style="height: 350px;">
                        <canvas id="barChart1"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <br>

    <div class="container mt-3">

        <div class="row text-center">
            <div class="col">
                <div class="count" id='all'>

                </div>
            </div>
            <div class="col">
                <div class="count" id='scopus'>

                </div>
            </div>
            <div class="col">
                <div class="count" id='wos'>

                </div>
            </div>
            <div class="col">
                <div class="count" id='tci'>

                </div>
            </div>
            <div class="col">
                <div class="count" id='google_s'>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="myModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reference (APA)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="name">
                    <!-- <p>Modal body text goes here.</p> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>




    <div class="container mixpaper pb-10 mt-3">
        <h3>{{ trans('message.publications') }}</h3>
        @foreach($papers as $n => $pe)
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$n}}" aria-expanded="true" aria-controls="collapseOne">
                        @if (!$loop->last)
                        {{$n}}
                        @else
                        Before {{$n}}
                        @endif

                    </button>
                </h2>
                <div id="collapse{{$n}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        @foreach($pe as $n => $p)
                        <div class="row mt-2 mb-3 border-bottom">
                            <div id="number" class="col-sm-1">
                                <h6>[{{$n+1}}]</h6>
                            </div>
                            <div id="paper2" class="col-sm-11">
                                <p class="hidden">
                                    <b>{{$p['paper_name']}}</b> (
                                    <link>{{$p['author']}}</link>), {{$p['paper_sourcetitle']}}, {{$p['paper_volume']}},
                                    {{$p['paper_yearpub']}}.
                                    <a href="{{$p['paper_url']}} " target="_blank">[url]</a> <a href="https://doi.org/{{$p['paper_doi']}}" target="_blank">[doi]</a>
                                    <!-- <a href="{{ route('bibtex',['id'=>$p['id']])}}">
                                        [อ้างอิง]
                                    </a> -->
                                    <button style="padding: 0;"class="btn btn-link open_modal" value="{{$p['id']}}">[อ้างอิง]</button>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
        @endforeach
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<!-- ลิงก์ Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ลิงก์ JavaScript ของ Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var myCarousel = document.querySelector("#carouselExampleIndicators");
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 5000,
            wrap: true
        });

        document.querySelector(".carousel-control-prev").addEventListener("click", function (event) {
            event.preventDefault();
            console.log("Previous button clicked"); // เพิ่มการตรวจสอบ
            carousel.prev();
        });

        document.querySelector(".carousel-control-next").addEventListener("click", function (event) {
            event.preventDefault();
            console.log("Next button clicked"); // เพิ่มการตรวจสอบ
            carousel.next();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".moreBox").slice(0, 1).show();     
        if ($(".blogBox:hidden").length != 0) { 
            $("#loadMore").show();
        }
        $("#loadMore").on('click', function(e) {
            e.preventDefault();
            $(".moreBox:hidden").slice(0, 1).slideDown();
            if ($(".moreBox:hidden").length == 0) {
                $("#loadMore").fadeOut('slow');
            }
        });
    });
</script>
<script>
    var year = <?php echo $year; ?>;
    var paper_tci = <?php echo $paper_tci; ?>;
    var paper_scopus = <?php echo $paper_scopus; ?>;
    var paper_wos = <?php echo $paper_wos; ?>;
    var paper_google_s = <?php echo $paper_google_s; ?>;
    var areaChartData = {

        labels: year,

        datasets: [{
                label: 'SCOPUS',
                backgroundColor: '#3994D6',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: '#3994D6',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#3994D6',
                data: paper_scopus
            },
            {
                label: 'TCI',
                backgroundColor: '#83E4B5',
                borderColor: 'rgba(255, 255, 255, 0.5)',
                pointRadius: false,
                pointColor: '#83E4B5',
                pointStrokeColor: '#3b8bba',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#83E4B5',
                data: paper_tci
            },
            {
                label: 'WOS',
                backgroundColor: '#FCC29A',
                borderColor: 'rgba(0, 0, 255, 1)',
                pointRadius: false,
                pointColor: '#FCC29A',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#FCC29A',
                data: paper_wos
            },

            {
                label: 'GOOGLE SCHOLAR',
                backgroundColor: '#c18ce6',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: '#c18ce6',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#c18ce6',
                data: paper_google_s
            },
        ]
    }



    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart1').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false,
        scales: {
            yAxes: [{
                formatter: function() {
                    return Math.abs(this.value);
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Number',

                },
                ticks: {
                    reverse: false,
                    stepSize: 10
                },
            }],
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Year'
                }
            }]
        },

        title: {
            display: true,
            text: 'Report the total number of articles ( 5 years : cumulative)',
            fontSize: 20
        }


    }

    new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    })
</script>
<script>
    // แสดงผลตัวเลขสรุป
    //ดึงค่าจำนวนบทความทั้งหมดจาก PHP แต่ละแหล่งที่มา
    var paper_tci = <?php echo $paper_tci_numall; ?>;
    var paper_scopus = <?php echo $paper_scopus_numall; ?>;
    var paper_wos = <?php echo $paper_wos_numall; ?>;
    var paper_google_s = <?php echo $paper_google_s_numall?>; 
    //console.log(paper_scopus)
    let sumtci = paper_tci;
    let sumsco = paper_scopus;
    let sumwos = paper_wos;
    let sumgoogle = paper_google_s;
    (function($) {
        
        let sum = paper_wos + paper_tci + paper_scopus + paper_google_s;
        //console.log(sum);
        //$("#scopus").append('data-to="100"');
        document.getElementById("all").innerHTML += `
                <i class="count-icon fa fa-book fa-2x"></i>
                <h2 class="timer count-title count-number" data-to="${sum}" data-speed="1500"></h2>
                <p class="count-text ">SUMMARY</p>`
        document.getElementById("scopus").innerHTML += `
                <i class="count-icon fa fa-book fa-2x"></i>
                <h2 class="timer count-title count-number" data-to="${sumsco}" data-speed="1500"></h2>
                <p class="count-text ">SCOPUS</p>`
        document.getElementById("wos").innerHTML += `
                <i class="count-icon fa fa-book fa-2x"></i>
                <h2 class="timer count-title count-number" data-to="${sumwos}" data-speed="1500"></h2>
                <p class="count-text ">WOS</p>`
        document.getElementById("tci").innerHTML += `
                <i class="count-icon fa fa-book fa-2x"></i>
                <h2 class="timer count-title count-number" data-to="${sumtci}" data-speed="1500"></h2>
                <p class="count-text ">TCI</p>`
        document.getElementById("google_s").innerHTML += `
                <i class="count-icon fa fa-book fa-2x"></i>
                <h2 class="timer count-title count-number" data-to="${sumgoogle}" data-speed="1500"></h2>
                <p class="count-text ">GOOGLE SCHOLAR</p>`
        //document.getElementById("scopus").appendChild('data-to="100"');
        $.fn.countTo = function(options) {
            options = options || {};

            return $(this).each(function() {
                // set options for current element
                var settings = $.extend({}, $.fn.countTo.defaults, {
                    from: $(this).data('from'),
                    to: $(this).data('to'),
                    speed: $(this).data('speed'),
                    refreshInterval: $(this).data('refresh-interval'),
                    decimals: $(this).data('decimals')
                }, options);

                // how many times to update the value, and how much to increment the value on each update
                var loops = Math.ceil(settings.speed / settings.refreshInterval),
                    increment = (settings.to - settings.from) / loops;

                // references & variables that will change with each update
                var self = this,
                    $self = $(this),
                    loopCount = 0,
                    value = settings.from,
                    data = $self.data('countTo') || {};

                $self.data('countTo', data);

                // if an existing interval can be found, clear it first
                if (data.interval) {
                    clearInterval(data.interval);
                }
                data.interval = setInterval(updateTimer, settings.refreshInterval);

                // initialize the element with the starting value
                render(value);
                
                function updateTimer() {
                    value += increment;
                    loopCount++;

                    render(value);

                    if (typeof(settings.onUpdate) == 'function') {
                        settings.onUpdate.call(self, value);
                    }

                    if (loopCount >= loops) {
                        // remove the interval
                        $self.removeData('countTo');
                        clearInterval(data.interval);
                        value = settings.to;

                        if (typeof(settings.onComplete) == 'function') {
                            settings.onComplete.call(self, value);
                        }
                    }
                }

                function render(value) {
                    var formattedValue = settings.formatter.call(self, value, settings);
                    $self.html(formattedValue);
                }
            });
        };

        $.fn.countTo.defaults = {
            from: 0, // the number the element should start at
            to: 0, // the number the element should end at
            speed: 1000, // how long it should take to count between the target numbers
            refreshInterval: 100, // how often the element should be updated
            decimals: 0, // the number of decimal places to show
            formatter: formatter, // handler for formatting the value before rendering
            onUpdate: null, // callback method for every time the element is updated
            onComplete: null // callback method for when the element finishes updating
        };

        function formatter(value, settings) {
            return value.toFixed(settings.decimals);
        }
    }(jQuery));

    jQuery(function($) {
        // custom formatting example
        $('.count-number').data('countToOptions', {
            formatter: function(value, options) {
                return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
            }
        });

        // start all the timers
        $('.timer').each(count);

        function count(options) {
            var $this = $(this);
            options = $.extend({}, options || {}, $this.data('countToOptions') || {});
            $this.countTo(options);
        }
    });
</script>
<script>
    $(document).on('click', '.open_modal', function() {
        //var url = "domain.com/yoururl";
        var tour_id = $(this).val();
        $.get('/bib/' + tour_id, function(data) { 
            //success data
            console.log(data);
            $( ".bibtex-biblio" ).remove(); 
            document.getElementById("name").innerHTML += `${data}` 
            // $('#tour_id').val(data.id);
            // $('#name').val(data);
            // $('#details').val(data.details);
            // $('#btn-save').val("update");
            $('#myModal').modal('show');
        })
    });
</script>
@endsection