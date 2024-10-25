<!-- resources/views/search.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.includes.head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-theme-classic">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #search-box {
            margin: 20px;
        }

        #results {
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination button,
        .pagination button {
            margin: 0 5px;
            padding: 10px 15px;
            border: none;
            background-color: #f1f1f1;
            color: #333;
            cursor: pointer;
        }

        .pagination button:disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }

        .pagination button.active {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- main-section starts -->
    <div class="wrapper .gray-bg-100">
        <!-- header start here -->
        <header id="mainHeader" class="main-header inner-header">
            @include('frontend.includes.header')
        </header>
        <main class="main-content">
            <!-- contect page start -->
            <section class="store-details banner-pad pb-0">
                <div class="container mt-5 mb-5">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-2 p-3 mb-3 mb-xl-4">
                                <div class="categ-group d-flex">
                                    <input type="text" id="search-input" class="form-control" placeholder="Search For"
                                        aria-label="Search For" value="">
                                    <button class="btn secondary-btn" id="search-button" type="submit">
                                        <img class="img-fluid" src="{{URL::asset('assets/images/search.png')}}"
                                            alt="search-ico">
                                    </button>
                                </div>
                                <div class="categ-group" id="filters">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="view-tabs-wrap">
                                <div class="view-tabs"id="focusFn">
                                    <div class="btn-view-grp me-2 me-md-3">
                                    <button type="button" onclick="ViewTab('tab1', this)" class="view-btn active-view">
                                        <img class="img-fluid tab-ico tab-dark"
                                            src="{{URL::asset('assets/images/tab-dark.png')}}" alt="tabs-ico">
                                        <img class="img-fluid tab-ico tab-light"
                                            src="{{URL::asset('assets/images/tab-ico.png');}}" alt="tabs-ico">
                                    </button>
                                    <button type="button" onclick="ViewTab('tab2', this)" class="view-btn">
                                        <img class="img-fluid tab-ico tab-dark"
                                            src="{{URL::asset('assets/images/list-view-dark.png');}}" alt="tabs-ico">
                                        <img class="img-fluid tab-ico tab-light"
                                            src="{{URL::asset('assets/images/list-view.png');}}" alt="tabs-ico">
                                    </button>
                                    </div>
                                    <p class="view-txt mb-0">
                                        Found
                                        <span class=" view-txt mb-0 totalCoupon"id="totalCount">
                                        </span>
                                        coupons
                                    </p>
                                </div>
                                <div id="loader" style="display: none;margin-top: 101px;margin-bottom: 1500px;">
                                    <i  class="fa fa-spinner fa-spin" style="display: flex;justify-content: center;font-size:40px;color:#003fadc7;"></i>
                                </div>
                                <div class="top-store mb-4 mb-xl-5">
                                    <div class="store-slide-wrap slider-wrap mb-0">
                                        <div class="swiper store-slide">
                                            <div class="swiper-wrapper"id="swiper-wrapper">

                                            </div>
                                            <div class="swiper-button-prev"id="btn_prev"></div>
                                            <div class="swiper-button-next"id="btn_next"></div>
                                        </div>
                                    </div>
                                </div> 
                                <div id="tab1" class="tab-content active tab-view-content list-view  coupons-wrapper1">
                                    <div id="results">    
                                </div>
                                </div>
                                <div id="tab2" class="tab-content tab-view-content tab-grid-view">
                                    <div class="row coupons-wrapper2 fix-coupn"id="results2">
                                    </div>
                                </div>
                                <div id="pageNumbers" class="pagination" style="text-align:center;">
                                </div>
                            
                        </div>
                    </div>
                </div>
            </section>

            @include('frontend.includes.footer')
            <!-- main footer end -->
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/algoliasearch"></script>
</body>

</html>
<script>
        function ViewTab(tabId, element) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active-view');
            });
            element.classList.add('active-view');
        }
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('tab1').classList.add('active');
        });
        document.addEventListener('DOMContentLoaded', function () {
            const storeSlider = new Swiper('.store-slide', {
                slidesPerView: 2,
                spaceBetween: 20,
                observer: true,
                observeParents: true,
                breakpoints: {
                    640: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 4,
                    },
                    1280: {
                        slidesPerView: 4,
                    }
                },
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.store-slide .swiper-button-next',
                    prevEl: '.store-slide .swiper-button-prev',
                },
            });
        });
</script>
<script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.24.0/dist/algoliasearch-lite.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    // const searchClient = algoliasearch('L5BF36U7WM', 'dcab3de424353d7fb0e648bf89facfe7');
    // const index = searchClient.initIndex('Bountii_Live_Db');
    const searchClient = algoliasearch('9S71E3IS73', 'dc9f3ea84d4414ff141a23355ccb280d');
    const index = searchClient.initIndex('Bountii_Staging_DB');
    const searchBox = document.querySelector('#search-input');
    const resultsContainer = document.querySelector('#results');
    const results2Container = document.querySelector('#results2');
    const prevPageButton = document.querySelector('#prevPage');
    const nextPageButton = document.querySelector('#nextPage');
    const pageNumbersContainer = document.querySelector('#pageNumbers');
    const totalCountContainer = document.querySelector('#totalCount');
    const filtersContainer = document.querySelector('#filters');
    const loader = document.getElementById('loader');

    let activeFilters = {};
    let currentPage = 0;
    let totalPages = 1;

    let expandedFacets = new Set();


    const ipAddress = @json($ipAddress);
    const couponData = @json($coupondata);

    const couponDataMap = {};
    couponData.forEach(coupon => {
        couponDataMap[coupon.coupon_id] = {
            yes_count: coupon.yes_count,
            percentage: coupon.percentage
        };
    });
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');
    const brandId = urlParams.get('brand');
    const searchData = urlParams.get('search_data');
    if (categoryId) {
        activeFilters['CategoryName'] = [categoryId];
    }
    if (brandId) {
        activeFilters['BrandName'] = [brandId];
    }

    if (searchData) {
        searchBox.value = searchData;
    }

    const artAndCraftFilters = extractFilters(buildFilters(), 'BrandName');
    const catherineHillerFilters = extractFilters(buildFilters(), 'CategoryName');

    if (artAndCraftFilters.length > 0) {
        activeFilters['BrandName'] = artAndCraftFilters;
    }
    if (catherineHillerFilters.length > 0) {
        activeFilters['CategoryName'] = catherineHillerFilters;
    }
    function fetchFacets() {
        const filters = buildFilters();
        let activeName = '';
        if (activeFilters['CategoryName'] && activeFilters['CategoryName'].length > 0) {
            activeName = activeFilters['CategoryName'][0]; 
        } else if (activeFilters['BrandName'] && activeFilters['BrandName'].length > 0) {
            activeName = activeFilters['BrandName'][0];
        }
        if (activeName == "4 Wheel Drive Hardware" || activeName == "Black Diamond Equipment") {
            document.getElementById('btn_prev').classList.add('swiper-button-lock');
            document.getElementById('btn_next').classList.add('swiper-button-lock');
        }
        index.search(activeName, {
            facets: ['CategoryName', 'BrandName'], 
        }).then(({ facets }) => {
            //console.log(`Facet values for search query "${activeName}":`, facets);
            displayFilters(facets);
        }).catch(err => {
            console.error('Error fetching data from Algolia:', err);
        });
    }

    function buildFilters() {
        return Object.entries(activeFilters).map(([facet, values]) => {
            return values.map(value => `${facet}:"${value}"`).join(' OR ');
            }).join(' AND ');
    }
    function displayFilters(facets) {
        loader.style.display = 'block';
        let facetsHtml = '';
        let i = 1;
        let facetIndex = 0;

        for (const [facetKey, facetValues] of Object.entries(facets)) {
            const isExpanded = expandedFacets.has(facetKey);
            const valuesToShow = isExpanded ? Object.entries(facetValues) : Object.entries(facetValues).slice(0, 100000);

            if (facetIndex > 0) {
                facetsHtml += '</ul>';
            }

            facetsHtml += `<div class="border-title mb-3 mb-xl-4 d-flex justify-content-between">
                                <h4 class="fw-700">${facetKey}</h4>
                                <div class="categ_search">
                                    <div class="search-icon" data-facet="${facetKey}" style="cursor: pointer;">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <input type="text" class="facet-search d-none" data-facet="${facetKey}" placeholder="Search ${facetKey}...">
                                </div>
                            </div>`;
            facetsHtml += `<ul class="menu mb-0 badge-lst" id="filters-${facetKey}" role="group" aria-label="Basic checkbox toggle button group">`;

            const selectedValues = activeFilters[facetKey] || [];
            selectedValues.forEach(value => {
                const count = facetValues[value] || 0;
                const inputId = `btncheck${facetKey}${i++}`;
                facetsHtml += `
                    <li class="badge-lst-item filterCat" style="cursor: pointer;" data-facet-key="${facetKey}" data-facet-value="${value}">
                        <input type="checkbox" class="btn-check" value="${value}" id="${inputId}" autocomplete="off"
                            data-facet="${facetKey}" checked>
                        <label class="fw-500 btn-label" for="${inputId}">
                            ${value} (${count})
                        </label>
                    </li>
                `;
            });

            valuesToShow.forEach(([facet, count]) => {
                if (!selectedValues.includes(facet)) {
                    const inputId = `btncheck${facetKey}${i++}`;
                    facetsHtml += `
                        <li class="badge-lst-item filterCat" style="cursor: pointer;" data-facet-key="${facetKey}" data-facet-value="${facet}">
                            <input type="checkbox" class="btn-check" value="${facet}" id="${inputId}" autocomplete="off"
                                data-facet="${facetKey}">
                            <label class="fw-500 btn-label" for="${inputId}">
                                ${facet} (${count})
                            </label>
                        </li>
                    `;
                }
            });

            if (Object.entries(facetValues).length > 100000) {
                facetsHtml += `<button class="show-more" data-facet="${facetKey}">${isExpanded ? 'Show Fewer' : 'Show More'}</button>`;
            }

            facetIndex++;
        }

        facetsHtml += '</ul>';
        filtersContainer.innerHTML = facetsHtml;
        attachEventListeners();
        loader.style.display = 'none';
        document.getElementById('swiper-wrapper').style.display = '';
    }





    function isChecked(facetKey, facetValue) {
            return activeFilters[facetKey] && activeFilters[facetKey].includes(facetValue);
            
        }

    function attachEventListeners() {
        filtersContainer.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', handleCheckboxChange);
        });

        filtersContainer.querySelectorAll('.show-more').forEach(button => {
            button.addEventListener('click', handleShowMore);
        });

        filtersContainer.querySelectorAll('.facet-search').forEach(input => {
            input.addEventListener('input', handleFacetSearch);
        });
        filtersContainer.querySelectorAll('.filterCat').forEach(button => {
            button.addEventListener('click', focusFn);
        });

        filtersContainer.querySelectorAll('.search-icon').forEach(icon => {
            icon.addEventListener('click', handleSearchIconClick);
        });
    }

    function handleSearchIconClick(event) {
        const icon = event.target.closest('.search-icon'); 
        const facetKey = icon.getAttribute('data-facet');
        const searchInput = document.querySelector(`input.facet-search[data-facet="${facetKey}"]`);
        if (searchInput.classList.contains('d-none')) {
            searchInput.classList.remove('d-none');
            searchInput.focus();
        } else {
            searchInput.classList.add('d-none');
        }
    }

    function fetchAllFacetsForSearch(facetKey, searchQuery) {
        index.search(searchQuery, {
            facets: ['*'],
        }).then(({ facets }) => {
            const facetValues = facets[facetKey] || {};
            const facetList = document.getElementById(`filters-${facetKey}`);
            
            if (!facetList) {
                console.error(`No facet list found for facet key "${facetKey}"`);
                return;
            }

            facetList.innerHTML = '';
            const selectedValues = activeFilters[facetKey] || [];

            selectedValues.forEach((value, index) => {
                const count = facetValues[value] || 0; 
                const inputId = `btncheck${facetKey}${index + 1}`;
                const listItem = `
                    <li class="badge-lst-item filterCat" style="cursor: pointer;" data-facet-key="${facetKey}" data-facet-value="${value}">
                        <input type="checkbox" class="btn-check" value="${value}" id="${inputId}" autocomplete="off"
                            data-facet="${facetKey}" checked>
                        <label class="fw-500 btn-label" for="${inputId}">
                            ${value}
                        </label>
                    </li>
                `;
                facetList.innerHTML += listItem;
            });

            Object.entries(facetValues).forEach(([value, count], index) => {
                if (!selectedValues.includes(value)) {
                    const inputId = `btncheck${facetKey}${index + 1 + selectedValues.length}`;
                    const listItem = `
                        <li class="badge-lst-item filterCat" style="cursor: pointer;" data-facet-key="${facetKey}" data-facet-value="${value}">
                            <input type="checkbox" class="btn-check" value="${value}" id="${inputId}" autocomplete="off"
                                data-facet="${facetKey}">
                            <label class="fw-500 btn-label" for="${inputId}">
                                ${value} (${count})
                            </label>
                        </li>
                    `;
                    facetList.innerHTML += listItem;
                }
            });

            facetList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });
        }).catch(err => {
            console.error('Error fetching all facets from Algolia for search:', err);
        });
    }

    function handleFacetSearch(event) {
        const input = event.target;
        const facetKey = input.getAttribute('data-facet');
        const filterValue = input.value.toLowerCase();

        if (filterValue.length > 0) {
            fetchAllFacetsForSearch(facetKey, filterValue);
        } else {
            fetchFacets(facetKey);  
        }
    }
    function focusFn(event) {
            if (window.innerWidth < 1000) {
                const targetDiv = document.querySelector('#focusFn');
                targetDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                console.log(targetDiv);
                targetDiv.focus();
            }
        }

    function handleCheckboxChange(event) {
        const checkbox = event.target;
        const facetKey = checkbox.getAttribute('data-facet');
        const facetValue = checkbox.value;

        if (!activeFilters[facetKey]) {
            activeFilters[facetKey] = [];
        }

        if (checkbox.checked) {
            if (!activeFilters[facetKey].includes(facetValue)) {
                activeFilters[facetKey].push(facetValue);
            }

        } else {
            activeFilters[facetKey] = activeFilters[facetKey].filter(value => value !== facetValue);
        }

        if (activeFilters[facetKey].length === 0) {
            delete activeFilters[facetKey];
        }
        performSearch();
        fetchFacets();  
    }


    function handleShowMore(event) {
        const button = event.target;
        const facetKey = button.getAttribute('data-facet');

        if (expandedFacets.has(facetKey)) {
            expandedFacets.delete(facetKey);
        } else {
            expandedFacets.add(facetKey);
        }

        fetchFacets();
    }



    function extractFilters(filters, facetKey) {
        const pattern = new RegExp(`${facetKey}:"([^"]+)"`, 'g');
        const matches = filters.match(pattern);
        if (matches) {
            return matches.map(match => match.replace(`${facetKey}:"`, '').replace('"', ''));
        }
        return [];
    }
    function performSearch(page = 0) {
                    loader.style.display = 'block';
                    const query = searchBox.value;
                    const filters = buildFilters();
                    index.search(query, { 
                        page, 
                        hitsPerPage: 10,
                        facetFilters: Object.entries(activeFilters).map(([facet, values]) => values.map(value => `${facet}:${value}`))
                    }).then(({ hits, nbPages, nbHits }) => {
                        const uniqueBrands = new Set();
                        const brandHtml = hits.filter(hit => {
                            const brandName = hit["BrandName"];
                            if (!uniqueBrands.has(brandName)) {
                                uniqueBrands.add(brandName);
                                return true;
                            }
                            return false;
                        }).map(hit => {
                            var image = hit["brand_image"];
                            const brandName =hit["BrandName"];
                            const transformedLink = brandName.replace(/\s+/g, '-').toLowerCase();
                            if (image == '') {
                                image = 'images/default_logo.jpeg';
                            }
                            $.ajax({
                                url: '{{ url('upload') }}/' + image,
                                type: 'HEAD',
                                async: false,
                                error: function() {
                                    image = 'images/default_logo.jpeg';
                                },
                                success: function() {
                                    //console.log('file_exist');
                                }
                                });
                            const defaultImageUrl = '{{ url('assets/images/default_logo.jpeg') }}';
                            return `
                                <div class="swiper-slide swiper-slide-active" style="width:23%">
                                    <div class="card-bg card-5">
                                        <a href="{{ url('store') }}/${transformedLink }" class="">
                                            <div class="icon-float" style="background-image: url('{{ url('upload') }}/${image}');" onload="this.style.backgroundImage='url({{ asset('assets/images/default_logo.jpeg') }})'" onerror="this.onerror=null;this.style.backgroundImage='url({{ asset('assets/images/default_logo.jpeg') }})';"><img src="{{ url('upload') }}/${image}" style="display:none;" onerror="this.parentNode.style.backgroundImage='url({{ asset('assets/images/default_logo.jpeg') }})'"></div>
                                            <div class="text-wrap">
                                                <span class="d-block text-white">${brandName}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `;
                        }).join('');

                        document.getElementById('swiper-wrapper').innerHTML = brandHtml;


                    const resultsHtml = hits.map(hit => {
                    var image = hit["brand_image"];
                    const storelink = hit["BrandName"];
                    const transformedLink = storelink.replace(/\s+/g, '-').toLowerCase();
                    const title = hit["coupon_desc"];
                    const coupon = hit["coupon_code"];
                    const affiliate_link =hit["affiliate_link"];;
                    let numericValue = hit["id"];
                    const yes_count = couponDataMap[numericValue]?.yes_count || 10;
                    const percentage = couponDataMap[numericValue]?.percentage || "100%";
                    if (image == '') {
                        image = 'images/default_logo.jpeg';
                    }
                    $.ajax({
                         url: '{{ url('upload') }}/' + image,
                         type: 'HEAD',
                         async: false,
                        error: function() {
                            image = 'images/default_logo.jpeg';
                        },
                         success: function() {
                             //console.log('file_exist');
                        }
                        });
                    const defaultImageUrl = '{{ url('assets/images/default_logo.jpeg') }}';
                    return `
                        <div class="deal-group cat-hide cat-${numericValue}">
                            <div class="deal-card">
                                <div class="box-row flex-wrap hstack justify-content-between">
                                    <div class="hstack flex-wrap gap-3 gap-xxl-4">  
                                        <a href="{{ url('store') }}/${transformedLink}" class="fix-anchor">
                                            <div class="content-img">
                                                <img class="img-fluid mb-2" src="{{ url('upload') }}/${image}" alt="group-ico"onerror="this.onerror=null;this.src='{{ asset('assets/images/default_logo.jpeg') }}'">
                                            </div>
                                            <p class="text-dark text-center fw-700">${hit["BrandName"]}</p>
                                        </a>
                                        <div class="content">
                                            <h2 class="off-title">${title}</h2>
                                            <div class="coupon-meta-data">
                                            ${yes_count > 10 ?`
                                                <div>
                                                <p>Successfully Used: <span class="grey-text-200 Coupon_yes_count-${numericValue}">
                                                    ${yes_count} Times
                                                </span></p>
                                                </div>`:''}   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="coupn-btn-wrap">
                                        <form action="" method="">
                                            <input type="hidden" name="coupon_id" value="${numericValue}">
                                            <input type="hidden" name="action" id="action" value="no">
                                            <input type="hidden" name="ip" value="${ipAddress}">
                                            <input type="hidden" name="coupon_code" id="coupon_code" value="${coupon}">
                                            <button type="button" class="coupn-btn w-100 coupon_btn_1 cid-${numericValue} view" id="cid-${numericValue}"
                                                data-value="hide-1" data-id="${numericValue}"
                                                data-code="${coupon}" data-affiliate="${affiliate_link}">
                                                <span class="coupn-code">${coupon}</span>
                                                <span class="coupn-title ">Get Code</span>
                                            </button>
                                            <div class="view_display coupn-btn coupon_btn_2" style="display:none;text-align:center;">
                                                <span class="is-code-works" style="display: none;">
                                                    <span class="d-block pb-2">Did Code ${coupon} work?</span>
                                                    <span class="options text-center">
                                                        <span class="option-btn-no no badge text-bg-danger No">No</span>
                                                        <span class="option-btn-yes no badge text-bg-success Yes">Yes</span>
                                                    </span>
                                                </span>
                                                <span class="option-pane-no" style="display: none;">
                                                    <span>Thank You</span>
                                                    <p>Your Answer Help Us Improve</p>
                                                </span>
                                                <span class="option-pane-yes">
                                                    <span class="how-much-form">
                                                        <span>How much did you save ?</span>
                                                        <span class="form-input-wrap">
                                                            <span>&#36;</span>
                                                            <input type="text" class="form-control" name="value" value="0" maxlength="7" 
                                                            oninput="validateValue(this)" required> 
                                                            <a class="coupon-form-btn submit">Submit</a>
                                                        </span>
                                                    </span>
                                                </span>
                                            </div>
                                            <button type="button"
                                                class="coupn-btn w-100 coupon_btn_3 content hide-1 ${numericValue}"
                                                style="display: none;">
                                                <span class="coupn-title coupon-code">${coupon}</span>
                                            </button>
                                        </form>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                resultsContainer.innerHTML = resultsHtml;
                const results2Html = hits.map(hit => {
                    var image = hit["brand_image"];
                    const storelink = hit["BrandName"];
                    const transformedLink = storelink.replace(/\s+/g, '-').toLowerCase();
                    const title = hit["coupon_desc"];
                    const coupon = hit["coupon_code"];
                    const affiliate_link =hit["affiliate_link"];
                    let numericValue = hit["id"];
                    const yes_count = couponDataMap[numericValue]?.yes_count || 10;
                    const percentage = couponDataMap[numericValue]?.percentage || "100%";
                    if (image == '') {
                        image = 'images/default_logo.jpeg';
                    }
                    $.ajax({
                         url: '{{ url('upload') }}/' + image,
                         type: 'HEAD',
                         async: false,
                        error: function() {
                            image = 'images/default_logo.jpeg';
                        },
                         success: function() {
                             //console.log('file_exist');
                        }
                        });
                    const defaultImageUrl = '{{ url('assets/images/default_logo.jpeg') }}';
                    return `
                    <div class="col-sm-6">
                        <div class="deal-group cat-hide cat-${numericValue}">
                            <div class="deal-card">
                                <div class="box-row flex-wrap hstack justify-content-between">
                                    <div class="hstack flex-wrap gap-3 gap-xxl-4">  
                                        <a href="{{ url('store') }}/${transformedLink}" class="fix-anchor">
                                            <div class="content-img">
                                                <img class="img-fluid mb-2" src="{{ url('upload') }}/${image}" alt="group-ico"onerror="this.onerror=null;this.src='{{ asset('assets/images/default_logo.jpeg') }}'">
                                            </div>
                                            <p class="text-dark text-center fw-700">${hit["BrandName"]}</p>
                                        </a>
                                        <div class="content">
                                            <h2 class="off-title">${title}</h2>
                                            <div class="coupon-meta-data">
                                            ${yes_count > 10 ?`
                                                <div>
                                                <p>Successfully Used: <span class="grey-text-200 Coupon_yes_count-${numericValue}">
                                                    ${yes_count} Times
                                                </span></p>
                                                </div>`:''}   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="coupn-btn-wrap">
                                        <form action="" method="">
                                            <input type="hidden" name="coupon_id" value="${numericValue}">
                                            <input type="hidden" name="action" id="action" value="no">
                                            <input type="hidden" name="ip" value="${ipAddress}">
                                            <input type="hidden" name="coupon_code" id="coupon_code" value="${coupon}">
                                            <button type="button" class="coupn-btn w-100 coupon_btn_1 cid-${numericValue} view" id="cid-${numericValue}"
                                                data-value="hide-1" data-id="${numericValue}"
                                                data-code="${coupon}" data-affiliate="${affiliate_link}">
                                                <span class="coupn-code">${coupon}</span>
                                                <span class="coupn-title ">Get Code</span>
                                            </button>
                                            <div class="view_display coupn-btn coupon_btn_2" style="display:none;text-align:center;">
                                                <span class="is-code-works" style="display: none;">
                                                    <span class="d-block pb-2">Did Code ${coupon} work?</span>
                                                    <span class="options text-center">
                                                        <span class="option-btn-no no badge text-bg-danger No">No</span>
                                                        <span class="option-btn-yes no badge text-bg-success Yes">Yes</span>
                                                    </span>
                                                </span>
                                                <span class="option-pane-no" style="display: none;">
                                                    <span>Thank You</span>
                                                    <p>Your Answer Help Us Improve</p>
                                                </span>
                                                <span class="option-pane-yes">
                                                    <span class="how-much-form">
                                                        <span>How much did you save ?</span>
                                                        <span class="form-input-wrap">
                                                            <span>&#36;</span>
                                                            <input type="text" class="form-control" name="value" value="0" maxlength="7" 
                                                            oninput="validateValue(this)" required>                                                             
                                                            <a class="coupon-form-btn submit">Submit</a>
                                                        </span>
                                                    </span>
                                                </span>
                                            </div>
                                            <button type="button"
                                                class="coupn-btn w-100 coupon_btn_3 content hide-1 ${numericValue}"
                                                style="display: none;">
                                                <span class="coupn-title coupon-code">${coupon}</span>
                                            </button>
                                        </form>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }).join('');

                results2Container.innerHTML = results2Html;
                currentPage = page;
                totalPages = nbPages;
                if (totalPages !== 0) {
                    renderPageNumbers();
                } else {
                    pageNumbersContainer.innerHTML = '';
                    const noResultsMessage = document.createElement('h4');
                    noResultsMessage.textContent = 'No results found';
                    pageNumbersContainer.appendChild(noResultsMessage);
                }
                totalCountContainer.textContent = `${nbHits}`;
                loader.style.display = 'none';
            }).catch(err => {
                console.error('Error performing search:', err); 
                loader.style.display = 'none';
            });
        } 

        function renderPageNumbers() {
                pageNumbersContainer.innerHTML = '';

                // Create "Prev" button
                const prevPageButton = document.createElement('button');
                prevPageButton.id = 'prevPage';
                prevPageButton.textContent = '«';
                prevPageButton.disabled = currentPage === 0;
                prevPageButton.addEventListener('click', () => {
                    if (currentPage > 0) {
                        performSearch(currentPage - 1);
                    }
                });
                pageNumbersContainer.appendChild(prevPageButton);

                const createPageButton = (pageNumber, isCurrent = false) => {
                    const button = document.createElement('button');
                    button.textContent = pageNumber + 1;
                    if (isCurrent) {
                        button.classList.add('active');
                    }
                    button.addEventListener('click', () => performSearch(pageNumber));
                    pageNumbersContainer.appendChild(button);
                };

                const renderEllipsis = () => {
                    const span = document.createElement('span');
                    span.textContent = '...';
                    pageNumbersContainer.appendChild(span);
                };

                createPageButton(0, currentPage === 0);  // First page

                if (currentPage > 2) {
                    renderEllipsis();
                }

                const startPage = Math.max(currentPage - 2, 1);
                const endPage = Math.min(currentPage + 2, totalPages - 2);

                for (let i = startPage; i <= endPage; i++) {
                    createPageButton(i, i === currentPage);
                }

                if (currentPage < totalPages - 3) {
                    renderEllipsis();
                }

                if (totalPages > 1) {
                    createPageButton(totalPages - 1, currentPage === totalPages - 1);  // Last page
                }

                // Create "Next" button
                const nextPageButton = document.createElement('button');
                nextPageButton.id = 'nextPage';
                nextPageButton.textContent = '»';
                nextPageButton.disabled = currentPage === totalPages - 1;
                nextPageButton.addEventListener('click', () => {
                    if (currentPage < totalPages - 1) {
                        performSearch(currentPage + 1);
                    }
                });
                pageNumbersContainer.appendChild(nextPageButton);
            }

            fetchFacets();
            performSearch();
            document.querySelector('#search-button').addEventListener('click', () => performSearch());

});

</script>
<script>
function validateValue(input) {
    // Remove any non-numeric characters except the decimal point
    input.value = input.value.replace(/[^0-9.]/g, '');

    // Ensure only two decimal places
    if (input.value.includes('.')) {
        let parts = input.value.split('.');
        parts[1] = parts[1].slice(0, 2);
        input.value = parts.join('.');
    }

    // Limit the maximum value to 9999.99
    if (parseFloat(input.value) > 9999.99) {
        input.value = '9999.99';
    }
}
</script>