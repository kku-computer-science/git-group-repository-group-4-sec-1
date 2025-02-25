<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papers from {{ $from }} </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }

        body {
            font-family: "THSarabunNew";
            line-height: 0.8rem;
        }

        .date {
            text-align: right;
            font-size: 1.2rem;
        }

        .header {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
        }

        .sub-header {
            font-weight: bold;
            font-size: 1.4rem;
        }

        .content {
            line-height: 1.3rem;
            font-size: 1.2rem;
        }

        .year {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .email {
            line-height: 1rem;
            font-size: 1.1rem;
        }

        a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <p class="header">Publication Report</p>
    <p class="date">{{ $from }}</p>

    <p class="sub-header">Professor Information</p>
    <p class="content">
        {{ $author['fname_en'] ?? '' }} {{ $author['lname_en'] ?? '' }}, {{ $author['doctoral_degree'] ?? '' }}
        <br>
        {{ $author['academic_ranks_en'] ?? 'N/A' }}
        <br>
        <span>E-mail:</span> {{ $author['email'] ?? 'N/A' }}
    </p>

    <!-- Education -->
    @if (!empty($author['education']))
        <p class="sub-header">Education</p>
        <ul class="content">
            @foreach ($author['education'] as $edu)
                <li>{{ $edu['year'] ?? 'N/A' }} {{ $edu['qua_name'] ?? 'N/A' }} ({{ $edu['uname'] ?? 'N/A' }})</li>
            @endforeach
        </ul>
    @else
        <p class="content">No education data available.</p>
    @endif

    <!-- Expertise -->
    @if (!empty($author['experties']))
        <p class="sub-header">Research Expertise</p>
        <ul class="content">
            @foreach ($author['experties'] as $expertise)
                <li>{{ $expertise }}</li>
            @endforeach
        </ul>
    @else
        <p class="content">No expertise data available.</p>
    @endif

    @php
        $currentYear = now()->year;
        $earliestYear = $currentYear - 2;
        $categories = [
            'Publication papers' => $papers,
            'Books' => $books,
            'Other works' => $otherWorks,
        ];

        // ฟังก์ชันจัดกลุ่มตามปี (ใช้ static เพื่อลดการเรียกใช้งานซ้ำ)
        function groupByYear($publications, $currentYear, $earliestYear)
        {
            static $cache = [];

            // ใช้ cache เพื่อลดเวลาคำนวณใหม่
            $cacheKey = md5(json_encode($publications));
            if (isset($cache[$cacheKey])) {
                return $cache[$cacheKey];
            }

            $grouped = [];
            for ($year = $currentYear; $year >= $earliestYear; $year--) {
                $grouped[$year] = [];
            }
            $grouped["Before $earliestYear"] = [];

            foreach ($publications as $publication) {
                $pubYear = $publication['paper_yearpub'] ?? ($publication['ac_year'] ?? null);
                if ($pubYear && is_numeric($pubYear)) {
                    if ($pubYear >= $earliestYear) {
                        $grouped[$pubYear][] = $publication;
                    } else {
                        $grouped["Before $earliestYear"][] = $publication;
                    }
                } else {
                    $grouped["Before $earliestYear"][] = $publication;
                }
            }

            // เก็บผลลัพธ์ไว้ใน cache
            $cache[$cacheKey] = $grouped;
            return $grouped;
        }
    @endphp

    @foreach ($categories as $categoryName => $publications)
        <hr class="separator">
        <p class="sub-header">{{ $categoryName }}</p>

        @php
            $groupedPublications = groupByYear($publications, $currentYear, $earliestYear);
            $index = 1;
        @endphp

        @foreach ($groupedPublications as $year => $yearPublications)
            <p class="year">Year {{ $year }}</p>

            @if (!empty($yearPublications))
                @foreach ($yearPublications as $publication)
                    <p class="content">
                        <span>{{ $index }}.</span>

                        @if (!empty($publication['authors']))
                            @php
                                $ownerFname = trim($author['fname_en'] ?? '');
                                $ownerLname = trim($author['lname_en'] ?? '');

                                // แปลงชื่อเจ้าของให้เป็นรูปแบบของ publications
                                $ownerFnameParts = explode(' ', $ownerFname);
                                $ownerInitials =
                                    implode(
                                        '. ',
                                        array_map(fn($part) => strtoupper(substr($part, 0, 1)), $ownerFnameParts),
                                    ) . '.';
                                $ownerFormatted = "{$ownerLname}, {$ownerInitials}";

                                $formattedAuthors = collect($publication['authors'])
                                    ->map(function ($author) use ($ownerFormatted) {
                                        $fname = trim($author['fname_en'] ?? '');
                                        $lname = trim($author['lname_en'] ?? '');

                                        // ตรวจสอบว่าเป็นชื่อภาษาอังกฤษหรือไทย
                                        if (preg_match('/[ก-ฮ]/u', $fname) || preg_match('/[ก-ฮ]/u', $lname)) {
                                            $fullName = "{$fname} {$lname}";
                                        } else {
                                            // กรณีที่มีชื่อกลาง (เช่น "C. Soomlek" => "Soomlek, C.")
                                            $fname_parts = explode(' ', $fname);
                                            $initials =
                                                implode(
                                                    '. ',
                                                    array_map(
                                                        fn($part) => strtoupper(substr($part, 0, 1)),
                                                        $fname_parts,
                                                    ),
                                                ) . '.';

                                            $fullName = "{$lname}, {$initials}";
                                        }

                                        // ทำให้ชื่อเจ้าของเป็นตัวหนา ถ้าตรงกับ ownerFormatted
                                        return $fullName === $ownerFormatted
                                            ? "<strong>{$fullName}</strong>"
                                            : $fullName;
                                    })
                                    ->implode(', ');
                            @endphp
                            {!! $formattedAuthors !!}
                        @else
                            Unknown Author.
                        @endif

                        ({{ $publication['paper_yearpub'] ?? ($publication['ac_year'] ?? 'N/A') }})
                        @if (isset($publication['paper_name']))
                            <!-- Papers  -->
                            <i>{{ $publication['paper_name'] }}</i>.
                            @if (!empty($publication['paper_sourcetitle']))
                                {{ $publication['paper_sourcetitle'] }},
                            @endif
                            @if (!empty($publication['paper_volume']))
                                {{ $publication['paper_volume'] }}
                            @endif
                            @if (!empty($publication['paper_issue']))
                                ({{ $publication['paper_issue'] }}),
                            @endif
                            @if (!empty($publication['paper_page']))
                                pp. {{ $publication['paper_page'] }}.
                            @endif
                            @if (!empty($publication['paper_doi']))
                                @php
                                    $doi = $publication['paper_doi'];
                                    if (!Str::startsWith($doi, 'https://doi.org/')) {
                                        $doi = 'https://doi.org/' . $doi;
                                    }
                                @endphp
                                <a href="{{ $doi }}">{{ $doi }}</a>
                            @elseif (!empty($publication['paper_url']))
                                <a href="{{ $publication['paper_url'] }}">{{ $publication['paper_url'] }}</a>
                            @endif
                        @elseif (isset($publication['ac_type']) && $publication['ac_type'] == 'book')
                            <!-- Books  -->
                            <i>{{ $publication['ac_name'] }}</i>.
                            @if (!empty($publication['ac_sourcetitle']))
                                {{ $publication['ac_sourcetitle'] }}.
                            @endif
                        @else
                            <!-- Other Works -->
                            <i>{{ $publication['ac_name'] }}</i>.
                            @if (!empty($publication['ac_refnumber']))
                                (Reference No. {{ $publication['ac_refnumber'] }}).
                            @endif
                        @endif
                    </p>
                    @php $index++; @endphp
                @endforeach
            @else
                <p class="content">No publications available for this year.</p>
            @endif
        @endforeach
    @endforeach

</body>

</html>
